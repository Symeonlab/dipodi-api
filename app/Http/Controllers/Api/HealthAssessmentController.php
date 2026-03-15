<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\HealthAssessmentCategory;
use App\Models\HealthAssessmentQuestion;
use App\Models\HealthAssessmentSession;
use App\Models\HealthAssessmentAnswer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthAssessmentController extends Controller
{
    /**
     * Get all active health assessment categories.
     */
    public function categories(Request $request): JsonResponse
    {
        $discipline = $request->query('discipline');

        $query = HealthAssessmentCategory::where('is_active', true)
            ->withCount(['questions' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('sort_order');

        if ($discipline) {
            $query->where(function ($q) use ($discipline) {
                $q->where('discipline', $discipline)
                  ->orWhereNull('discipline');
            });
        }

        $categories = $query->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'key' => $category->key,
                'name' => $category->name,
                'icon' => $category->icon,
                'discipline' => $category->discipline,
                'questions_count' => $category->questions_count,
            ];
        });

        return ApiResponse::success($categories);
    }

    /**
     * Get questions for a specific category.
     */
    public function questions(string $categoryKey): JsonResponse
    {
        $category = HealthAssessmentCategory::where('key', $categoryKey)
            ->where('is_active', true)
            ->first();

        if (!$category) {
            return ApiResponse::notFound(__('api.category_not_found'));
        }

        $questions = $category->questions()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'question' => $question->question,
                    'answer_type' => $question->answer_type,
                    'answer_options' => $question->answer_options,
                    'is_critical' => $question->is_critical,
                    'sort_order' => $question->sort_order,
                ];
            });

        return ApiResponse::success([
            'category' => [
                'id' => $category->id,
                'key' => $category->key,
                'name' => $category->name,
                'icon' => $category->icon,
            ],
            'questions' => $questions,
        ]);
    }

    /**
     * Get all questions grouped by category for a full assessment.
     */
    public function fullAssessment(Request $request): JsonResponse
    {
        $discipline = $request->query('discipline');

        $query = HealthAssessmentCategory::where('is_active', true)
            ->with(['questions' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order');

        if ($discipline) {
            $query->where(function ($q) use ($discipline) {
                $q->where('discipline', $discipline)
                  ->orWhereNull('discipline');
            });
        }

        $categories = $query->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'key' => $category->key,
                'name' => $category->name,
                'icon' => $category->icon,
                'discipline' => $category->discipline,
                'questions' => $category->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'question' => $question->question,
                        'answer_type' => $question->answer_type,
                        'answer_options' => $question->answer_options,
                        'is_critical' => $question->is_critical,
                        'sort_order' => $question->sort_order,
                    ];
                }),
            ];
        });

        return ApiResponse::success($categories);
    }

    /**
     * Start a new health assessment session.
     */
    public function startSession(Request $request): JsonResponse
    {
        $user = $request->user();

        // Check for incomplete sessions
        $incompleteSession = HealthAssessmentSession::where('user_id', $user->id)
            ->whereIn('status', ['started', 'in_progress'])
            ->first();

        if ($incompleteSession) {
            return ApiResponse::success(
                $this->formatSession($incompleteSession),
                __('api.session_resuming')
            );
        }

        // Create new session
        $session = HealthAssessmentSession::create([
            'user_id' => $user->id,
            'status' => 'started',
            'total_questions' => HealthAssessmentQuestion::where('is_active', true)->count(),
            'answered_questions' => 0,
        ]);

        return ApiResponse::created(
            $this->formatSession($session),
            __('api.assessment_started')
        );
    }

    /**
     * Submit answers for the health assessment.
     */
    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => 'required|exists:health_assessment_sessions,id',
            'answers' => 'required|array|min:1',
            'answers.*.question_id' => 'required|exists:health_assessment_questions,id',
            'answers.*.answer_value' => 'required|string',
            'is_complete' => 'boolean',
        ]);

        $user = $request->user();
        $session = HealthAssessmentSession::where('id', $validated['session_id'])
            ->where('user_id', $user->id)
            ->first();

        if (!$session) {
            return ApiResponse::notFound(__('api.session_not_found'));
        }

        if ($session->status === 'completed') {
            return ApiResponse::error(__('api.session_already_completed'), 400);
        }

        DB::transaction(function () use ($validated, $session, $user) {
            foreach ($validated['answers'] as $answer) {
                HealthAssessmentAnswer::updateOrCreate(
                    [
                        'session_id' => $session->id,
                        'question_id' => $answer['question_id'],
                    ],
                    [
                        'user_id' => $user->id,
                        'answer_value' => $answer['answer_value'],
                    ]
                );
            }

            // Update session progress
            $answeredCount = HealthAssessmentAnswer::where('session_id', $session->id)->count();
            $session->answered_questions = $answeredCount;
            $session->status = 'in_progress';

            // Check if complete
            if ($validated['is_complete'] ?? false) {
                $session->status = 'completed';
                $session->completed_at = now();
                $session->generateInsights();
            }

            $session->save();
        });

        $session->refresh();

        $message = $session->status === 'completed'
            ? __('api.assessment_completed')
            : __('api.answers_saved');

        return ApiResponse::success($this->formatSession($session), $message);
    }

    /**
     * Get assessment history for the current user.
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user();

        $sessions = HealthAssessmentSession::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(function ($session) {
                return $this->formatSession($session);
            });

        return ApiResponse::success($sessions);
    }

    /**
     * Get a specific session with its answers.
     */
    public function session(int $sessionId, Request $request): JsonResponse
    {
        $user = $request->user();

        $session = HealthAssessmentSession::where('id', $sessionId)
            ->where('user_id', $user->id)
            ->with(['answers.question.category'])
            ->first();

        if (!$session) {
            return ApiResponse::notFound(__('api.session_not_found'));
        }

        $answersGrouped = $session->answers->groupBy(function ($answer) {
            return $answer->question->category->key ?? 'unknown';
        })->map(function ($answers, $categoryKey) {
            $category = $answers->first()->question->category ?? null;
            return [
                'category' => $category ? [
                    'key' => $category->key,
                    'name' => $category->name,
                    'icon' => $category->icon,
                ] : null,
                'answers' => $answers->map(function ($answer) {
                    return [
                        'question_id' => $answer->question_id,
                        'question' => $answer->question->question,
                        'answer_value' => $answer->answer_value,
                        'is_positive' => $answer->isPositive(),
                    ];
                }),
            ];
        });

        return ApiResponse::success([
            'session' => $this->formatSession($session),
            'answers_by_category' => $answersGrouped,
        ]);
    }

    /**
     * Get latest assessment insights for the user.
     */
    public function insights(Request $request): JsonResponse
    {
        $user = $request->user();

        $latestSession = HealthAssessmentSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderByDesc('completed_at')
            ->first();

        if (!$latestSession) {
            return ApiResponse::success([
                'has_assessment' => false,
                'message' => __('api.no_assessment'),
            ]);
        }

        // Count positive answers (health concerns)
        $concernsCount = HealthAssessmentAnswer::where('session_id', $latestSession->id)
            ->get()
            ->filter(function ($answer) {
                return $answer->isPositive();
            })
            ->count();

        // Get critical concerns
        $criticalConcerns = HealthAssessmentAnswer::where('session_id', $latestSession->id)
            ->whereHas('question', function ($q) {
                $q->where('is_critical', true);
            })
            ->with('question.category')
            ->get()
            ->filter(function ($answer) {
                return $answer->isPositive();
            })
            ->map(function ($answer) {
                return [
                    'question' => $answer->question->question,
                    'category' => $answer->question->category->name ?? __('api.general'),
                ];
            });

        return ApiResponse::success([
            'has_assessment' => true,
            'completed_at' => $latestSession->completed_at,
            'total_questions' => $latestSession->total_questions,
            'concerns_count' => $concernsCount,
            'critical_concerns' => $criticalConcerns,
            'insights' => $latestSession->insights,
            'recommendations' => $latestSession->recommendations,
        ]);
    }

    /**
     * Format session data for API response.
     */
    private function formatSession(HealthAssessmentSession $session): array
    {
        return [
            'id' => $session->id,
            'status' => $session->status,
            'total_questions' => $session->total_questions,
            'answered_questions' => $session->answered_questions,
            'progress_percentage' => $session->total_questions > 0
                ? round(($session->answered_questions / $session->total_questions) * 100, 1)
                : 0,
            'insights' => $session->insights,
            'recommendations' => $session->recommendations,
            'started_at' => $session->created_at,
            'completed_at' => $session->completed_at,
        ];
    }
}
