<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\FeedbackAnswer;
use App\Models\FeedbackCategory;
use App\Models\FeedbackSession;
use App\Models\WorkoutFeedback;
use App\Services\Workout\FeedbackAdjustmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WorkoutFeedbackController extends Controller
{
    /**
     * Get the post-workout feedback questions from the database.
     *
     * GET /workout-feedback/questions
     */
    public function questions(Request $request): JsonResponse
    {
        $category = FeedbackCategory::where('key', 'post_workout')
            ->where('is_active', true)
            ->first();

        if (! $category) {
            return ApiResponse::notFound(__('api.category_not_found'));
        }

        $questions = $category->activeQuestions()
            ->get()
            ->map(fn ($q) => [
                'id'             => $q->id,
                'category'       => 'post_workout',
                'question'       => $q->question,
                'answer_type'    => $q->answer_type,
                'answer_options' => $q->answer_options,
                'sort_order'     => $q->sort_order,
            ]);

        return ApiResponse::success([
            'category' => [
                'key'  => $category->key,
                'name' => $category->name,
                'icon' => $category->icon,
            ],
            'questions' => $questions,
        ]);
    }

    /**
     * Submit post-workout feedback.
     *
     * POST /workout-feedback
     */
    public function submit(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            // Workout context (always required)
            'session_day'          => 'required|string|max:50',
            'session_theme'        => 'required|string|max:100',
            'exercises_completed'  => 'required|integer|min:0',
            'elapsed_seconds'      => 'required|integer|min:0',

            // Questionnaire answers (primary mode)
            'answers'              => 'nullable|array',
            'answers.*.question_id' => 'required_with:answers|integer',
            'answers.*.value'      => 'required_with:answers|string|max:1000',

            // Legacy flat fields (fallback / backward-compatible)
            'difficulty_rating'    => 'nullable|integer|min:1|max:5',
            'energy_level'         => 'nullable|integer|min:1|max:5',
            'enjoyment_rating'     => 'nullable|integer|min:1|max:5',
            'muscle_soreness'      => 'nullable|integer|min:1|max:5',
            'sore_areas'           => 'nullable|array',
            'sore_areas.*'         => 'string|max:50',
            'completed_all_sets'   => 'nullable|boolean',
            'skipped_reason'       => 'nullable|string|in:too_hard,too_easy,injury,time',
            'notes'                => 'nullable|string|max:500',
            'preferred_adjustment' => 'nullable|string|in:increase_intensity,decrease_intensity,more_rest,fewer_exercises,more_variety,keep_same',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }

        $user = $request->user();

        try {
            DB::beginTransaction();

            // -----------------------------------------------------------
            // A. Always store in workout_feedbacks for quick analytics
            // -----------------------------------------------------------

            $difficultyRating   = $request->difficulty_rating;
            $energyLevel        = $request->energy_level;
            $enjoymentRating    = $request->enjoyment_rating;
            $muscleSoreness     = $request->muscle_soreness;
            $completedAllSets   = $request->completed_all_sets;
            $notes              = $request->notes;
            $preferredAdjustment = $request->preferred_adjustment;

            if ($request->has('answers') && ! $request->has('difficulty_rating')) {
                $answersMap = $this->mapAnswersToFields($request->answers);
                $difficultyRating   = $answersMap['difficulty_rating'] ?? $difficultyRating;
                $energyLevel        = $answersMap['energy_level'] ?? $energyLevel;
                $enjoymentRating    = $answersMap['enjoyment_rating'] ?? $enjoymentRating;
                $muscleSoreness     = $answersMap['muscle_soreness'] ?? $muscleSoreness;
                $completedAllSets   = $answersMap['completed_all_sets'] ?? $completedAllSets;
                $notes              = $answersMap['notes'] ?? $notes;
                $preferredAdjustment = $answersMap['preferred_adjustment'] ?? $preferredAdjustment;
            }

            $feedback = WorkoutFeedback::create([
                'user_id'              => $user->id,
                'session_day'          => $request->session_day,
                'session_theme'        => $request->session_theme,
                'exercises_completed'  => $request->exercises_completed,
                'elapsed_seconds'      => $request->elapsed_seconds,
                'difficulty_rating'    => $difficultyRating,
                'energy_level'         => $energyLevel,
                'enjoyment_rating'     => $enjoymentRating,
                'muscle_soreness'      => $muscleSoreness,
                'sore_areas'           => $request->sore_areas,
                'completed_all_sets'   => $completedAllSets,
                'skipped_reason'       => $request->skipped_reason,
                'notes'                => $notes,
                'preferred_adjustment' => $preferredAdjustment,
            ]);

            // -----------------------------------------------------------
            // B. Also store in feedback_sessions/answers (questionnaire)
            // -----------------------------------------------------------

            $sessionData = null;

            if ($request->has('answers') && is_array($request->answers)) {
                $category = FeedbackCategory::where('key', 'post_workout')->first();

                if ($category) {
                    $session = FeedbackSession::create([
                        'user_id'            => $user->id,
                        'category_id'        => $category->id,
                        'session_uuid'       => (string) Str::uuid(),
                        'total_questions'    => $category->activeQuestions()->count(),
                        'answered_questions' => count($request->answers),
                        'status'             => 'in_progress',
                    ]);

                    foreach ($request->answers as $answerData) {
                        FeedbackAnswer::create([
                            'session_id'   => $session->id,
                            'question_id'  => $answerData['question_id'],
                            'user_id'      => $user->id,
                            'answer_value' => $answerData['value'],
                        ]);
                    }

                    // Complete the session
                    $session->markCompleted();
                    $session->insights = $session->generateInsights();
                    $session->save();

                    $sessionData = $session->toApiArray();
                }
            }

            DB::commit();

            // -----------------------------------------------------------
            // C. Get adjustment recommendation
            // -----------------------------------------------------------

            $adjustments = FeedbackAdjustmentService::getAdjustments(
                $user->id,
                $request->session_theme
            );

            return ApiResponse::success([
                'feedback'       => $feedback->toApiArray(),
                'recommendation' => $adjustments['recommendation'],
                'adjustments'    => [
                    'intensity_modifier'   => $adjustments['intensity_modifier'],
                    'exercise_count_delta' => $adjustments['exercise_count_delta'],
                    'rest_time_modifier'   => $adjustments['rest_time_modifier'],
                    'confidence'           => $adjustments['confidence'],
                ],
                'stats'          => $adjustments['stats'],
                'session'        => $sessionData,
            ], __('api.feedback_saved'));

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Workout feedback save failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return ApiResponse::serverError(__('api.feedback_save_failed'));
        }
    }

    /**
     * Get workout feedback history for the user.
     *
     * GET /workout-feedback/history
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user();

        $feedbacks = WorkoutFeedback::forUser($user->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(fn ($f) => $f->toApiArray());

        return ApiResponse::success($feedbacks);
    }

    /**
     * Get workout recommendation + adjustments based on recent feedback.
     *
     * GET /workout-feedback/recommendation/{theme}
     */
    public function recommendation(Request $request, string $theme): JsonResponse
    {
        $user = $request->user();
        $adjustments = FeedbackAdjustmentService::getAdjustments($user->id, $theme);

        // Get recent stats
        $recentFeedback = WorkoutFeedback::forUser($user->id)
            ->forTheme($theme)
            ->recent(14)
            ->get();

        $stats = [
            'average_difficulty' => round($recentFeedback->avg('difficulty_rating') ?? 0, 1),
            'average_energy'     => round($recentFeedback->avg('energy_level') ?? 0, 1),
            'average_enjoyment'  => round($recentFeedback->avg('enjoyment_rating') ?? 0, 1),
            'total_sessions'     => $recentFeedback->count(),
        ];

        return ApiResponse::success([
            'recommendation' => $adjustments['recommendation'],
            'adjustments'    => [
                'intensity_modifier'   => $adjustments['intensity_modifier'],
                'exercise_count_delta' => $adjustments['exercise_count_delta'],
                'rest_time_modifier'   => $adjustments['rest_time_modifier'],
                'confidence'           => $adjustments['confidence'],
            ],
            'stats' => $stats,
        ]);
    }

    /**
     * Map questionnaire answers to the flat workout_feedbacks fields.
     */
    private function mapAnswersToFields(array $answers): array
    {
        $mapped = [];

        $questionIds = collect($answers)->pluck('question_id');
        $questions = \App\Models\FeedbackQuestion::whereIn('id', $questionIds)
            ->get()
            ->keyBy('id');

        foreach ($answers as $answer) {
            $question = $questions->get($answer['question_id']);
            if (! $question) continue;

            $sortOrder = $question->sort_order;
            $value = $answer['value'];

            switch ($sortOrder) {
                case 1: $mapped['difficulty_rating'] = $this->scaleToFive($value); break;
                case 2: $mapped['energy_level'] = $this->scaleToFive($value); break;
                case 3: $mapped['enjoyment_rating'] = $this->scaleToFive($value); break;
                case 4: $mapped['completed_all_sets'] = strtolower($value) === 'yes' || $value === '1' || $value === 'true'; break;
                case 5: $mapped['muscle_soreness'] = $this->scaleToFive($value); break;
                case 8: $mapped['preferred_adjustment'] = $value; break;
                case 9: $mapped['notes'] = $value; break;
            }
        }

        return $mapped;
    }

    /**
     * Convert a value (possibly 1-10 or 1-5) to a 1-5 scale.
     */
    private function scaleToFive(string $value): int
    {
        $num = (int) $value;
        if ($num > 5) {
            return max(1, min(5, (int) round($num / 2)));
        }
        return max(1, min(5, $num));
    }
}
