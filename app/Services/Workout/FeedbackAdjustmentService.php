<?php

namespace App\Services\Workout;

use App\Models\FeedbackAnswer;
use App\Models\FeedbackCategory;
use App\Models\FeedbackSession;
use App\Models\WorkoutFeedback;
use Illuminate\Support\Facades\Log;

/**
 * Analyses accumulated post-workout feedback and produces concrete
 * workout plan adjustments:
 *  - intensity modifier   (multiplier for sets / reps)
 *  - exercise count delta (+ or − exercises per session)
 *  - rest time modifier   (multiplier for recovery between sets)
 *  - theme weight adjustments (boost/penalise themes user likes/dislikes)
 *
 * The adjustments are consumed by WorkoutPlanGenerator when the user
 * regenerates their weekly plan.
 */
class FeedbackAdjustmentService
{
    /**
     * Compute adjustments for a given user, optionally scoped to a theme.
     *
     * @return array{
     *   intensity_modifier: float,
     *   exercise_count_delta: int,
     *   rest_time_modifier: float,
     *   preferred_adjustment: string|null,
     *   recommendation: string,
     *   confidence: float,
     *   feedback_count: int,
     *   stats: array
     * }
     */
    public static function getAdjustments(int $userId, ?string $theme = null, int $lookbackDays = 30): array
    {
        // -----------------------------------------------------------------
        // 1. Gather recent feedback from BOTH sources:
        //    a) workout_feedbacks table   (dedicated post-workout form)
        //    b) feedback_sessions/answers (generic questionnaire system)
        // -----------------------------------------------------------------

        $adjustments = [
            'intensity_modifier'   => 1.0,   // 1.0 = no change
            'exercise_count_delta' => 0,      // +/- exercises
            'rest_time_modifier'   => 1.0,    // 1.0 = no change
            'preferred_adjustment' => null,
            'recommendation'       => 'keep_same',
            'confidence'           => 0.0,    // 0-1, how much data we have
            'feedback_count'       => 0,
            'stats'                => [],
        ];

        // --- Source A: workout_feedbacks ---
        $query = WorkoutFeedback::forUser($userId)->recent($lookbackDays);
        if ($theme) {
            $query->forTheme($theme);
        }
        $workoutFeedbacks = $query->orderByDesc('created_at')->limit(10)->get();

        // --- Source B: post_workout feedback_sessions ---
        $postWorkoutCategory = FeedbackCategory::where('key', 'post_workout')->first();
        $sessionAnswers = collect();

        if ($postWorkoutCategory) {
            $sessions = FeedbackSession::forUser($userId)
                ->completed()
                ->where('category_id', $postWorkoutCategory->id)
                ->where('created_at', '>=', now()->subDays($lookbackDays))
                ->orderByDesc('completed_at')
                ->limit(10)
                ->with('answers.question')
                ->get();

            foreach ($sessions as $session) {
                $sessionAnswers = $sessionAnswers->merge($session->answers);
            }
        }

        $totalFeedbacks = $workoutFeedbacks->count() + ($sessionAnswers->isNotEmpty() ? 1 : 0);
        $adjustments['feedback_count'] = $totalFeedbacks;

        if ($totalFeedbacks === 0) {
            return $adjustments;
        }

        // -----------------------------------------------------------------
        // 2. Aggregate metrics from workout_feedbacks
        // -----------------------------------------------------------------

        $avgDifficulty = null;
        $avgEnergy     = null;
        $avgEnjoyment  = null;
        $avgSoreness   = null;
        $lastPreferred = null;

        if ($workoutFeedbacks->isNotEmpty()) {
            $avgDifficulty = round($workoutFeedbacks->avg('difficulty_rating'), 2);
            $avgEnergy     = round($workoutFeedbacks->avg('energy_level'), 2);
            $avgEnjoyment  = round($workoutFeedbacks->avg('enjoyment_rating'), 2);
            $avgSoreness   = round($workoutFeedbacks->whereNotNull('muscle_soreness')->avg('muscle_soreness') ?? 0, 2);
            $lastPreferred = $workoutFeedbacks->first()?->preferred_adjustment;
        }

        // -----------------------------------------------------------------
        // 3. Merge questionnaire-based scale answers (if any)
        // -----------------------------------------------------------------

        if ($sessionAnswers->isNotEmpty()) {
            $scaleAnswers = $sessionAnswers->filter(fn ($a) => $a->question?->answer_type === 'scale')
                ->pluck('answer_value')
                ->filter(fn ($v) => is_numeric($v))
                ->map(fn ($v) => (float) $v);

            $avgQuestionnaireScore = $scaleAnswers->isNotEmpty() ? $scaleAnswers->average() : null;

            // If we don't have workout_feedback data, use questionnaire scores
            // (map 1-10 scale to 1-5 scale for consistency)
            if ($avgDifficulty === null && $avgQuestionnaireScore !== null) {
                $normalized = $avgQuestionnaireScore / 2; // 10 → 5
                $avgDifficulty = $normalized;
                $avgEnergy     = $normalized;
                $avgEnjoyment  = $normalized;
            }

            // Check for multi/adjustment answers
            $multiAnswer = $sessionAnswers
                ->first(fn ($a) => $a->question?->answer_type === 'multi');
            if ($multiAnswer && !$lastPreferred) {
                $lastPreferred = $multiAnswer->answer_value;
            }
        }

        // -----------------------------------------------------------------
        // 4. Compute adjustments from aggregated data
        // -----------------------------------------------------------------

        $adjustments['stats'] = [
            'avg_difficulty' => $avgDifficulty,
            'avg_energy'     => $avgEnergy,
            'avg_enjoyment'  => $avgEnjoyment,
            'avg_soreness'   => $avgSoreness,
        ];

        // Confidence = how many data points we have (max at 5+)
        $adjustments['confidence'] = min(1.0, $totalFeedbacks / 5);

        // --- Intensity modifier ---
        if ($avgDifficulty !== null) {
            if ($avgDifficulty >= 4.5) {
                // Too hard → decrease
                $adjustments['intensity_modifier'] = 0.8;
                $adjustments['recommendation'] = 'decrease_intensity';
            } elseif ($avgDifficulty >= 4.0) {
                $adjustments['intensity_modifier'] = 0.9;
                $adjustments['recommendation'] = 'decrease_intensity';
            } elseif ($avgDifficulty <= 1.5) {
                // Too easy → increase
                $adjustments['intensity_modifier'] = 1.25;
                $adjustments['recommendation'] = 'increase_intensity';
            } elseif ($avgDifficulty <= 2.0) {
                $adjustments['intensity_modifier'] = 1.15;
                $adjustments['recommendation'] = 'increase_intensity';
            }
        }

        // --- Energy + difficulty combo ---
        if ($avgEnergy !== null && $avgDifficulty !== null) {
            if ($avgEnergy <= 2 && $avgDifficulty >= 3.5) {
                // Low energy + high difficulty → needs more rest
                $adjustments['rest_time_modifier'] = 1.3;
                $adjustments['recommendation'] = 'more_rest';
            }
        }

        // --- Enjoyment-based variety ---
        if ($avgEnjoyment !== null && $avgEnjoyment <= 2) {
            $adjustments['recommendation'] = 'more_variety';
        }

        // --- Soreness-based exercise reduction ---
        if ($avgSoreness !== null && $avgSoreness >= 4) {
            $adjustments['exercise_count_delta'] = -1;
            $adjustments['rest_time_modifier'] = max($adjustments['rest_time_modifier'], 1.2);
        }

        // --- Explicit preference takes priority (if set and not keep_same) ---
        if ($lastPreferred && $lastPreferred !== 'keep_same') {
            $adjustments['preferred_adjustment'] = $lastPreferred;
            $adjustments['recommendation'] = $lastPreferred;

            // Apply the explicit preference
            switch ($lastPreferred) {
                case 'increase_intensity':
                    $adjustments['intensity_modifier'] = max($adjustments['intensity_modifier'], 1.15);
                    break;
                case 'decrease_intensity':
                    $adjustments['intensity_modifier'] = min($adjustments['intensity_modifier'], 0.85);
                    break;
                case 'more_rest':
                    $adjustments['rest_time_modifier'] = max($adjustments['rest_time_modifier'], 1.3);
                    break;
                case 'fewer_exercises':
                    $adjustments['exercise_count_delta'] = min($adjustments['exercise_count_delta'], -2);
                    break;
                case 'more_variety':
                    // This is handled at theme selection level, not here
                    break;
            }
        }

        Log::info("FeedbackAdjustmentService: user={$userId} theme={$theme}", $adjustments);

        return $adjustments;
    }

    /**
     * Apply adjustments to workout plan parameters.
     * Call this inside WorkoutPlanGenerator when building a session.
     *
     * @param  int    $baseSets       e.g. 4
     * @param  string $baseReps       e.g. "8-12"
     * @param  string $baseRecovery   e.g. "1 min 30"
     * @param  int    $baseExerciseCount e.g. 5
     * @param  array  $adjustments    from getAdjustments()
     * @return array{sets: int, reps: string, recovery: string, exercise_count: int}
     */
    public static function applyToWorkoutParams(
        int $baseSets,
        string $baseReps,
        string $baseRecovery,
        int $baseExerciseCount,
        array $adjustments
    ): array {
        $intensityMod = $adjustments['intensity_modifier'] ?? 1.0;
        $exerciseDelta = $adjustments['exercise_count_delta'] ?? 0;
        $restMod = $adjustments['rest_time_modifier'] ?? 1.0;

        // Adjust sets (round, min 2, max 6)
        $newSets = max(2, min(6, (int) round($baseSets * $intensityMod)));

        // Adjust reps (parse "8-12" format)
        $newReps = self::adjustReps($baseReps, $intensityMod);

        // Adjust recovery time
        $newRecovery = self::adjustRecovery($baseRecovery, $restMod);

        // Adjust exercise count (min 3, max 10)
        $newExerciseCount = max(3, min(10, $baseExerciseCount + $exerciseDelta));

        return [
            'sets'           => $newSets,
            'reps'           => $newReps,
            'recovery'       => $newRecovery,
            'exercise_count' => $newExerciseCount,
        ];
    }

    /**
     * Adjust a rep range string by an intensity modifier.
     *  "8-12"  × 1.15 → "9-14"
     *  "10"    × 0.85 → "8"
     */
    private static function adjustReps(string $reps, float $modifier): string
    {
        // Handle range format "8-12"
        if (str_contains($reps, '-')) {
            $parts = explode('-', $reps);
            $low  = max(1, (int) round((int) $parts[0] * $modifier));
            $high = max($low, (int) round((int) ($parts[1] ?? $parts[0]) * $modifier));
            return "{$low}-{$high}";
        }

        // Handle single number "10 reps" or "10"
        $num = (int) preg_replace('/[^0-9]/', '', $reps);
        if ($num > 0) {
            $adjusted = max(1, (int) round($num * $modifier));
            // Preserve text suffix if present
            $suffix = preg_replace('/[0-9]+/', '', $reps);
            return $adjusted . trim($suffix);
        }

        return $reps; // Return unchanged if can't parse
    }

    /**
     * Adjust recovery time by a modifier.
     *  "1 min 30" × 1.3 → "1 min 57"
     *  "90"       × 1.3 → "117" (seconds)
     */
    private static function adjustRecovery(string $recovery, float $modifier): string
    {
        // Parse "X min Y" format
        if (preg_match('/(\d+)\s*min\s*(\d*)/', $recovery, $matches)) {
            $totalSeconds = (int) $matches[1] * 60 + (int) ($matches[2] ?: 0);
            $adjusted = (int) round($totalSeconds * $modifier);
            $mins = intdiv($adjusted, 60);
            $secs = $adjusted % 60;
            return $secs > 0 ? "{$mins} min {$secs}" : "{$mins} min";
        }

        // Pure number = seconds
        if (is_numeric(trim($recovery))) {
            $adjusted = (int) round((int) $recovery * $modifier);
            return (string) $adjusted;
        }

        return $recovery; // Return unchanged
    }
}
