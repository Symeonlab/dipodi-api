<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\FeedbackAnswer;
use App\Models\FeedbackCategory;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackSession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FeedbackController extends Controller
{
    /**
     * Get available feedback categories for the user.
     */
    public function categories(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->profile;

        $categories = FeedbackCategory::active()
            ->orderBy('sort_order')
            ->get()
            ->filter(function ($category) use ($profile) {
                if ($category->discipline && $profile) {
                    if ($category->discipline !== $profile->discipline) {
                        return false;
                    }
                }
                if ($category->position && $profile) {
                    if (strtolower($profile->position ?? '') !== strtolower($category->position)) {
                        return false;
                    }
                }
                if ($category->goal && $profile) {
                    if (strtolower($profile->goal ?? '') !== strtolower($category->goal)) {
                        return false;
                    }
                }
                if ($category->requires_injury) {
                    if (!($profile?->has_injury ?? false)) {
                        return false;
                    }
                }
                return true;
            })
            ->map(function ($category) {
                return [
                    'key' => $category->key,
                    'name' => $category->name,
                    'icon' => $category->icon,
                    'questions_count' => $category->activeQuestions()->count(),
                ];
            })
            ->values();

        return ApiResponse::success($categories);
    }

    /**
     * Get questions for a specific category.
     */
    public function questions(Request $request, string $categoryKey): JsonResponse
    {
        $category = FeedbackCategory::where('key', $categoryKey)
            ->where('is_active', true)
            ->first();

        if (!$category) {
            return ApiResponse::notFound(__('api.category_not_found'));
        }

        $questions = $category->activeQuestions()
            ->get()
            ->map(function ($question) use ($categoryKey) {
                return [
                    'id' => $question->id,
                    'category' => $categoryKey,
                    'question' => $question->question,
                    'answer_type' => $question->answer_type,
                    'answer_options' => $question->answer_options,
                    'sort_order' => $question->sort_order,
                ];
            });

        return ApiResponse::success($questions);
    }

    /**
     * Submit feedback answers.
     */
    public function submit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_key' => 'required|string|exists:feedback_categories,key',
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|integer|exists:feedback_questions,id',
            'answers.*.value' => 'required|string|max:1000',
            'session_id' => 'nullable|string|uuid',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }

        $user = $request->user();
        $category = FeedbackCategory::where('key', $request->category_key)->first();

        try {
            DB::beginTransaction();

            $sessionUuid = $request->session_id ?? (string) Str::uuid();
            $session = FeedbackSession::firstOrCreate(
                [
                    'session_uuid' => $sessionUuid,
                    'user_id' => $user->id,
                ],
                [
                    'category_id' => $category->id,
                    'total_questions' => $category->activeQuestions()->count(),
                    'status' => 'in_progress',
                ]
            );

            foreach ($request->answers as $answerData) {
                FeedbackAnswer::updateOrCreate(
                    [
                        'session_id' => $session->id,
                        'question_id' => $answerData['question_id'],
                    ],
                    [
                        'user_id' => $user->id,
                        'answer_value' => $answerData['value'],
                    ]
                );
            }

            $session->answered_questions = $session->answers()->count();

            if ($session->answered_questions >= $session->total_questions) {
                $session->markCompleted();
                $session->insights = $session->generateInsights();
                $session->save();
            } else {
                $session->save();
            }

            DB::commit();

            $message = $session->status === 'completed'
                ? __('api.feedback_submitted')
                : __('api.progress_saved');

            return ApiResponse::success($session->toApiArray(), $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return ApiResponse::serverError(
                config('app.debug') ? $e->getMessage() : __('api.feedback_save_failed')
            );
        }
    }

    /**
     * Get feedback history for the user.
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user();

        $sessions = FeedbackSession::forUser($user->id)
            ->completed()
            ->with('category')
            ->orderBy('completed_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'category' => $session->category?->key,
                    'category_name' => $session->category?->name,
                    'total_questions' => $session->total_questions,
                    'answered_questions' => $session->answered_questions,
                    'average_score' => $session->average_score,
                    'insights' => $session->insights ?? [],
                    'completed_at' => $session->completed_at?->toIso8601String(),
                ];
            });

        return ApiResponse::success($sessions);
    }

    /**
     * Get a specific feedback session.
     */
    public function session(Request $request, int $sessionId): JsonResponse
    {
        $user = $request->user();

        $session = FeedbackSession::forUser($user->id)
            ->with(['category', 'answers.question'])
            ->find($sessionId);

        if (!$session) {
            return ApiResponse::notFound(__('api.session_not_found'));
        }

        return ApiResponse::success([
            'id' => $session->id,
            'category' => $session->category?->key,
            'category_name' => $session->category?->name,
            'total_questions' => $session->total_questions,
            'answered_questions' => $session->answered_questions,
            'average_score' => $session->average_score,
            'status' => $session->status,
            'insights' => $session->insights ?? [],
            'completed_at' => $session->completed_at?->toIso8601String(),
            'answers' => $session->answers->map(function ($answer) {
                return [
                    'question_id' => $answer->question_id,
                    'question' => $answer->question?->question,
                    'value' => $answer->answer_value,
                ];
            }),
        ]);
    }

    /**
     * Get feedback statistics for the user.
     */
    public function stats(Request $request): JsonResponse
    {
        $user = $request->user();

        $completedCount = FeedbackSession::forUser($user->id)->completed()->count();
        $avgScore = FeedbackSession::forUser($user->id)
            ->completed()
            ->whereNotNull('average_score')
            ->avg('average_score');

        $categoryBreakdown = FeedbackSession::forUser($user->id)
            ->completed()
            ->select('category_id', DB::raw('COUNT(*) as count'), DB::raw('AVG(average_score) as avg_score'))
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category?->key,
                    'category_name' => $item->category?->name,
                    'sessions_count' => $item->count,
                    'average_score' => round($item->avg_score, 2),
                ];
            });

        return ApiResponse::success([
            'total_sessions' => $completedCount,
            'overall_average_score' => $avgScore ? round($avgScore, 2) : null,
            'by_category' => $categoryBreakdown,
        ]);
    }
}
