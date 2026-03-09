<?php

namespace App\Services\Workout;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Exercise;
use App\Models\PlayerProfile;
use App\Models\TrainingDayLogic;
use App\Models\WorkoutSession;
use App\Services\Workout\MatchAwarePlanGenerator;
use Illuminate\Support\Facades\Log;

class WorkoutPlanGenerator
{
    private UserProfile $profile;
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->profile = $user->profile;
    }

    /**
     * Main public function. Deletes old plan and generates a new one.
     */
    public function generateAndSaveWeeklyPlan()
    {
        // 1. Delete old plan
        WorkoutSession::where('user_id', $this->user->id)->delete();

        // 2. Get weekly schedule (match, rest, high_intensity, etc.)
        $trainingDays = $this->profile->training_days ?? [];
        $schedule = MatchAwarePlanGenerator::generate($this->profile->match_day, $trainingDays);

        // 3. Find the user's DB Player Profile
        $playerProfile = PlayerProfile::where('name', $this->profile->position)->first();

        // 4. Get training day logic for theme distribution
        $totalTrainingDays = count(array_filter($schedule, fn ($d) => in_array($d['type'], [
            MatchAwarePlanGenerator::T_HIGH_INTENSITY,
            MatchAwarePlanGenerator::T_STRENGTH,
        ])));
        $dayLogic = TrainingDayLogic::where('total_days', min(max($totalTrainingDays, 1), 7))->first();

        // 5. Generate sessions with zone-aware scheduling
        $usedZones = [];
        $principalCount = 0;
        $maxPrincipal = $dayLogic?->theme_principal_count ?? $totalTrainingDays;

        foreach ($schedule as $day) {
            $isPrincipal = $principalCount < $maxPrincipal;
            $this->generateSessionForDay($day, $playerProfile, $usedZones, $isPrincipal);

            if (in_array($day['type'], [
                MatchAwarePlanGenerator::T_HIGH_INTENSITY,
                MatchAwarePlanGenerator::T_STRENGTH,
            ])) {
                $principalCount++;
            }
        }
    }

    /**
     * Generates a specific session based on the day's type and user profile.
     */
    private function generateSessionForDay(array $daySchedule, ?PlayerProfile $playerProfile, array &$usedZones = [], bool $isPrincipal = true)
    {
        $dayName = $daySchedule['day'];
        $dayType = $daySchedule['type'];

        // Default: Rest day
        $sessionThemeName = "Repos";
        $exercises = collect();
        $warmup = null;
        $finisher = "5 min retour au calme (étirements)";
        $zoneColor = null;
        $rpe = null;
        $estimatedLoad = null;
        $sleepRecommendation = null;
        $hydrationRecommendation = null;

        if ($dayType === MatchAwarePlanGenerator::T_MATCH) {
            $sessionThemeName = "Jour de Match";
        }

        if ($dayType === MatchAwarePlanGenerator::T_RECOVERY || $dayType === MatchAwarePlanGenerator::T_MOBILITY) {
            $sessionThemeName = "Mobilité & Récupération";
            $exercises = $this->getExercisesForTheme('KINE MOBILITÉ', null, 5);
            $warmup = "5 min échauffement léger";
            $zoneColor = 'blue';
            $rpe = 2;
        }

        if ($dayType === MatchAwarePlanGenerator::T_HIGH_INTENSITY || $dayType === MatchAwarePlanGenerator::T_STRENGTH) {
            $workoutTheme = null;

            if (!$playerProfile) {
                Log::warning("No PlayerProfile found for position: {$this->profile->position}");
                $sessionThemeName = "Entraînement Général";
            } else {
                // Find the theme based on location and profile percentages
                // Avoid same high-intensity zone on consecutive days (freshness check)
                $workoutTheme = $this->getDynamicTheme($playerProfile, $usedZones);
                $sessionThemeName = $workoutTheme ? $workoutTheme->name : "Entraînement Général";
            }

            // Extract zone/RPE data from theme rules
            $themeRules = $workoutTheme?->rules;
            $zoneColor = $workoutTheme?->zone_color;
            $rpe = $themeRules?->rpe;
            $sleepRecommendation = $themeRules?->sleep_requirement;
            $hydrationRecommendation = $themeRules?->hydration;

            // Track used zones for supercompensation-aware scheduling
            if ($zoneColor) {
                $freshness24h = $themeRules?->freshness_24h ?? 1.0;
                if ($freshness24h < 0.5) {
                    $usedZones[] = $zoneColor;
                }
            }

            // Calculate estimated Foster load: RPE x Duration (minutes)
            if ($rpe && $themeRules?->duration) {
                $durationMinutes = $this->parseDurationMinutes($themeRules->duration);
                $estimatedLoad = $rpe * $durationMinutes;
            }

            // Get exercise count from theme rules, default to 5
            $exerciseCount = $themeRules?->exercise_count ?? 5;

            // Pre-fetch adjustment to know if exercise count should change
            $preAdjust = FeedbackAdjustmentService::getAdjustments(
                $this->user->id,
                $workoutTheme?->name ?? $sessionThemeName
            );
            $adjustedExerciseCount = max(3, min(10, $exerciseCount + ($preAdjust['exercise_count_delta'] ?? 0)));

            // Select exercises based on theme type
            $themeType = $workoutTheme?->type ?? 'gym';
            if ($themeType === 'cardio') {
                $exercises = $this->getExercisesForTheme('CARDIO', null, $adjustedExerciseCount);
            } elseif ($themeType === 'home') {
                $exercises = $this->getExercisesForTheme('MAISON', null, $adjustedExerciseCount);
            } else {
                $exercises = $this->getExercisesForTheme('MUSCULATION', null, $adjustedExerciseCount);
            }

            $warmup = "10 min échauffement (cardio léger + mobilité)";
            $finisher = $this->getDynamicBonusFinisher();
        }

        // Get the dynamic rules for this theme
        $themeModel = \App\Models\WorkoutTheme::where('name', $sessionThemeName)->first();
        $themeRulesForAdjust = $themeModel?->rules;

        // --- Feedback-based adjustments ---
        $baseSets = (int) ($themeRulesForAdjust->sets ?? 4);
        $baseReps = $themeRulesForAdjust->reps ?? '8-12';
        $baseRecovery = $themeRulesForAdjust->recovery_time ?? '1 min 30';
        $baseExerciseCount = $exercises->count();

        $adjustments = FeedbackAdjustmentService::getAdjustments(
            $this->user->id,
            $sessionThemeName
        );

        $adjusted = FeedbackAdjustmentService::applyToWorkoutParams(
            $baseSets,
            $baseReps,
            $baseRecovery,
            $baseExerciseCount,
            $adjustments
        );

        // Trim exercise list to match adjusted count
        if ($adjusted['exercise_count'] < $exercises->count()) {
            $exercises = $exercises->take($adjusted['exercise_count']);
        }

        // Save the session with enhanced zone/RPE data
        $sessionData = [
            'user_id' => $this->user->id,
            'day' => $dayName,
            'theme' => $sessionThemeName,
            'warmup' => $warmup,
            'finisher' => $finisher,
        ];

        // Add zone/RPE metadata as JSON in a metadata field if available
        $metadata = array_filter([
            'zone_color' => $zoneColor,
            'rpe' => $rpe,
            'estimated_load' => $estimatedLoad,
            'sleep_recommendation' => $sleepRecommendation,
            'hydration_recommendation' => $hydrationRecommendation,
            'is_principal_theme' => $isPrincipal,
            'supercomp_window' => $themeRulesForAdjust?->supercomp_window,
            'gain_prediction' => $themeRulesForAdjust?->gain_prediction,
            'injury_risk' => $themeRulesForAdjust?->injury_risk,
        ]);

        if (!empty($metadata)) {
            $sessionData['metadata'] = json_encode($metadata);
        }

        $session = WorkoutSession::create($sessionData);

        // Save the exercises for the session
        foreach ($exercises as $exercise) {
            $session->exercises()->create([
                'name' => $exercise->name,
                'sets' => (string) $adjusted['sets'],
                'reps' => $adjusted['reps'],
                'recovery' => $adjusted['recovery'],
                'video_url' => $exercise->video_url,
            ]);
        }
    }

    /**
     * Dynamically selects a workout theme based on user profile and location.
     * Avoids zones with poor 24h freshness that were used the previous day.
     */
    private function getDynamicTheme(PlayerProfile $playerProfile, array $usedZones = []): ?\App\Models\WorkoutTheme
    {
        $location = $this->profile->training_location;
        $type = 'gym';

        if ($location === 'SI CARDIO EN SALLE') $type = 'cardio';
        if ($location === 'SI DEHORS') $type = 'outside';
        if ($location === 'SI MAISON') $type = 'home';
        if ($location === 'SI MUSCULATION ET CARDIO EN SALLE') $type = 'gym';

        // Get all theme percentages for this profile and location type
        $themes = $playerProfile->themes()->where('type', $type)->get();
        if ($themes->isEmpty()) {
            return null;
        }

        // Filter out themes in zones with poor freshness from previous day
        if (!empty($usedZones)) {
            $filtered = $themes->filter(fn ($t) => !in_array($t->zone_color, $usedZones));
            if ($filtered->isNotEmpty()) {
                $themes = $filtered;
            }
        }

        // Weighted random selection
        $totalWeight = $themes->sum('pivot.percentage');
        if ($totalWeight <= 0) return $themes->random();

        $randomValue = rand(1, $totalWeight);

        foreach ($themes as $theme) {
            if ($randomValue <= $theme->pivot->percentage) {
                return $theme;
            }
            $randomValue -= $theme->pivot->percentage;
        }

        return $themes->random();
    }

    /**
     * Helper function to get exercises from the DB.
     */
    private function getExercisesForTheme(?string $category, ?string $subCategory, int $limit = 5)
    {
        $query = Exercise::query();

        if ($category) {
            $query->where('category', $category);
        }
        if ($subCategory) {
            // Find exercises matching the theme name, e.g., "ENDURANCE", "SPRINT"
            $query->where('sub_category', 'like', '%' . $subCategory . '%');
        }

        $results = $query->inRandomOrder()->limit($limit)->get();

        // Fallback if no specific sub-category matches
        if ($results->isEmpty() && $subCategory && $category !== 'KINE MOBILITÉ') {
            return Exercise::where('category', 'MUSCULATION')->inRandomOrder()->limit($limit)->get();
        }

        return $results;
    }

    /**
     * Parses duration string like "90-120 min" into average minutes.
     */
    private function parseDurationMinutes(string $duration): int
    {
        if (preg_match('/(\d+)\s*-\s*(\d+)/', $duration, $matches)) {
            return (int) (((int) $matches[1] + (int) $matches[2]) / 2);
        }
        if (preg_match('/(\d+)/', $duration, $matches)) {
            return (int) $matches[1];
        }
        return 60;
    }

    /**
     * Helper to get the bonus finisher text.
     */
    private function getDynamicBonusFinisher(): string
    {
        $level = $this->profile->level ?? 'DÉBUTANT';
        $rules = \App\Models\BonusWorkoutRule::where('level', $level)->get();

        if ($rules->isEmpty()) {
            return '5 min Abdos & Gainage';
        }

        $finisherText = "PARTIE BONUS ($level):\n";
        foreach ($rules as $rule) {
            $finisherText .= "- {$rule->type}: {$rule->sets} sets, {$rule->reps} reps, {$rule->recovery} repos\n";
        }
        return $finisherText;
    }
}
