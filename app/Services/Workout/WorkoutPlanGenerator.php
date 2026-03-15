<?php

namespace App\Services\Workout;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Exercise;
use App\Models\PlayerProfile;
use App\Models\TrainingDayLogic;
use App\Models\WorkoutSession;
use App\Models\WorkoutTheme;
use App\Models\HomeWorkoutRule;
use App\Services\Workout\MatchAwarePlanGenerator;
use Illuminate\Support\Facades\Log;

class WorkoutPlanGenerator
{
    private UserProfile $profile;
    private User $user;

    /**
     * Maps user training_location values to theme types.
     * These match the onboarding options stored in user_profiles.training_location.
     */
    private const LOCATION_TYPE_MAP = [
        'gym'     => 'gym',
        'home'    => 'home',
        'outdoor' => 'cardio',
        'mixed'   => 'gym',      // mixed = gym + cardio rotation
    ];

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->profile = $user->profile;
    }

    /**
     * Main public function. Deletes old plan and generates a new one.
     *
     * Algorithm:
     * 1. Build weekly schedule around match day (match, recovery, mobility, training, rest)
     * 2. Load player profile and theme distribution percentages
     * 3. Use TrainingDayLogic to decide how many days are "principal" vs "random"
     * 4. For each training day, select a theme using zone-aware weighted random:
     *    - Principal days: pick from profile's assigned themes (weighted by %)
     *    - Random days: pick any theme of the correct type
     *    - Avoid scheduling high-fatigue zones (freshness_24h < 0.5) on consecutive days
     * 5. Calculate Foster Load (RPE × duration) and save session metadata
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

        // 5. Determine theme type based on training location
        $themeType = $this->resolveThemeType();

        // 6. Track weekly load for overtraining prevention
        $weeklyLoad = 0;
        $usedZones = [];
        $usedThemeIds = [];
        $principalCount = 0;
        $maxPrincipal = $dayLogic?->theme_principal_count ?? $totalTrainingDays;

        // 7. Generate sessions with zone-aware scheduling
        foreach ($schedule as $day) {
            $isPrincipal = $principalCount < $maxPrincipal;

            $sessionLoad = $this->generateSessionForDay(
                $day,
                $playerProfile,
                $themeType,
                $usedZones,
                $usedThemeIds,
                $isPrincipal,
                $weeklyLoad
            );

            $weeklyLoad += $sessionLoad;

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
     *
     * @return int The session's Foster Load (for weekly accumulation)
     */
    private function generateSessionForDay(
        array $daySchedule,
        ?PlayerProfile $playerProfile,
        string $themeType,
        array &$usedZones,
        array &$usedThemeIds,
        bool $isPrincipal,
        int $currentWeeklyLoad
    ): int {
        $dayName = $daySchedule['day'];
        $dayType = $daySchedule['type'];

        // Default: Rest day
        $sessionThemeName = "Repos";
        $exercises = collect();
        $warmup = null;
        $finisher = "5 min retour au calme (étirements)";
        $zoneColor = null;
        $rpe = null;
        $estimatedLoad = 0;
        $sleepRecommendation = null;
        $hydrationRecommendation = null;
        $workoutTheme = null;

        // ─── Match Day ────────────────────────────────────────────────
        if ($dayType === MatchAwarePlanGenerator::T_MATCH) {
            $sessionThemeName = "Jour de Match";
            $zoneColor = 'red';
            $rpe = 9;
        }

        // ─── Recovery / Mobility Day ──────────────────────────────────
        if ($dayType === MatchAwarePlanGenerator::T_RECOVERY) {
            $sessionThemeName = "Récupération active";
            $workoutTheme = WorkoutTheme::where('name', 'Récupération active')->first();
            $exercises = $this->getExercisesForTheme('KINE MOBILITÉ', null, 5);
            $warmup = "5 min marche / vélo stationnaire";
            $zoneColor = 'blue';
            $rpe = 2;
        }

        if ($dayType === MatchAwarePlanGenerator::T_MOBILITY) {
            $sessionThemeName = "Mobilité & Récupération";
            $workoutTheme = WorkoutTheme::where('name', 'Mobilité & Récupération')->first();
            $exercises = $this->getExercisesForTheme('KINE MOBILITÉ', null, 5);
            $warmup = "5 min échauffement léger";
            $zoneColor = 'blue';
            $rpe = 2;
        }

        // ─── Pre-Match Bonus Day (J-1) ─────────────────────────────
        // 24h before match: bonus only (abdos/gainage/pompes)
        // No gym, no cardio, no musculation — recovery-focused
        if ($dayType === MatchAwarePlanGenerator::T_PRE_MATCH_BONUS) {
            $sessionThemeName = "Bonus Pré-Match";
            $exercises = collect(); // No main exercises — bonus finisher only
            $warmup = "5 min corde à sauter (optionnel)";
            $finisher = $this->getDynamicBonusFinisher();
            $zoneColor = 'blue';
            $rpe = 2;
        }

        // ─── Training Day (High Intensity / Strength) ─────────────────
        if ($dayType === MatchAwarePlanGenerator::T_HIGH_INTENSITY || $dayType === MatchAwarePlanGenerator::T_STRENGTH) {

            if (!$playerProfile) {
                Log::warning("No PlayerProfile found for position: {$this->profile->position}");
                $sessionThemeName = "Entraînement Général";
            } else {
                // Select theme based on profile, type, zone awareness, and theme variety
                $workoutTheme = $this->getDynamicTheme(
                    $playerProfile,
                    $themeType,
                    $usedZones,
                    $usedThemeIds,
                    $isPrincipal
                );
                $sessionThemeName = $workoutTheme ? ($workoutTheme->display_name ?? $workoutTheme->name) : "Entraînement Général";
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
                // If this zone has poor 24h recovery, mark it as "used" to avoid next day
                if ($freshness24h < 0.5) {
                    $usedZones[] = $zoneColor;
                }
                // Clear zones from 2+ days ago (they've recovered)
                if (count($usedZones) > 2) {
                    array_shift($usedZones);
                }
            }

            // Track used theme IDs for variety
            if ($workoutTheme) {
                $usedThemeIds[] = $workoutTheme->id;
            }

            // Calculate estimated Foster load: RPE × Duration (minutes)
            if ($rpe && $themeRules?->duration) {
                $durationMinutes = $this->parseDurationMinutes($themeRules->duration);
                $estimatedLoad = $rpe * $durationMinutes;
            }

            // Get exercise count from theme rules, default to 5
            $exerciseCount = (int) ($themeRules?->exercise_count ?? 5);

            // Pre-fetch feedback adjustment for exercise count
            $preAdjust = FeedbackAdjustmentService::getAdjustments(
                $this->user->id,
                $workoutTheme?->name ?? $sessionThemeName
            );
            $adjustedExerciseCount = max(3, min(10, $exerciseCount + ($preAdjust['exercise_count_delta'] ?? 0)));

            // ─── Exercise Selection ───────────────────────────────────
            $actualType = $workoutTheme?->type ?? $themeType;

            if ($actualType === 'home') {
                // Home workouts: use home workout rules for circuit parameters
                $exercises = $this->getExercisesForTheme('MAISON', null, $adjustedExerciseCount);
            } elseif ($actualType === 'cardio') {
                // Cardio: try to match sub_category to the theme's zone_color intensity
                $exercises = $this->getExercisesForTheme('CARDIO', null, $adjustedExerciseCount);
            } elseif ($actualType === 'mobility') {
                $exercises = $this->getExercisesForTheme('KINE MOBILITÉ', null, $adjustedExerciseCount);
            } else {
                // Gym: match exercises to the theme's muscle group focus
                $subCategory = $this->mapThemeToSubCategory($workoutTheme?->name);
                $exercises = $this->getExercisesForTheme('MUSCULATION', $subCategory, $adjustedExerciseCount);
            }

            // Warmup intensity matches the session zone
            $warmup = $this->getZoneAwareWarmup($zoneColor);
            $finisher = $this->getDynamicBonusFinisher();

            // For strength days near match: reduce intensity
            if ($dayType === MatchAwarePlanGenerator::T_STRENGTH && $rpe && $rpe > 7) {
                $rpe = max(5, $rpe - 2);
                $estimatedLoad = (int) ($estimatedLoad * 0.7);
            }
        }

        // ─── Feedback-based adjustments ───────────────────────────────
        $themeRulesForAdjust = $workoutTheme?->rules;

        $baseSets = (int) ($themeRulesForAdjust->sets ?? 4);
        $baseReps = $themeRulesForAdjust->reps ?? '8-12';
        $baseRecovery = $themeRulesForAdjust->recovery_time ?? '1 min 30';
        $baseExerciseCount = $exercises->count();

        $adjustments = FeedbackAdjustmentService::getAdjustments(
            $this->user->id,
            $workoutTheme?->name ?? $sessionThemeName
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

        // ─── Save Session ─────────────────────────────────────────────
        $sessionData = [
            'user_id' => $this->user->id,
            'day' => $dayName,
            'theme' => $workoutTheme?->name ?? $sessionThemeName,
            'warmup' => $warmup,
            'finisher' => $finisher,
        ];

        // Build rich metadata
        // Use fn($v) => $v !== null to preserve false, 0, and 0.0 values
        $metadata = array_filter([
            'zone_color'                => $zoneColor,
            'display_name'              => $workoutTheme?->display_name,
            'quality_method'            => $workoutTheme?->quality_method,
            'rpe'                       => $rpe,
            'mets'                      => $themeRulesForAdjust?->mets,
            'estimated_load'            => $estimatedLoad,
            'sleep_recommendation'      => $sleepRecommendation,
            'hydration_recommendation'  => $hydrationRecommendation,
            'is_principal_theme'        => in_array($dayType, [
                MatchAwarePlanGenerator::T_HIGH_INTENSITY,
                MatchAwarePlanGenerator::T_STRENGTH,
            ]) && $isPrincipal,
            'supercomp_window'          => $themeRulesForAdjust?->supercomp_window,
            'gain_prediction'           => $themeRulesForAdjust?->gain_prediction,
            'injury_risk'               => $themeRulesForAdjust?->injury_risk,
            'freshness_24h'             => $themeRulesForAdjust?->freshness_24h,
            'freshness_48h'             => $themeRulesForAdjust?->freshness_48h,
            'weekly_load_so_far'        => $currentWeeklyLoad + $estimatedLoad,
        ], fn($v) => $v !== null);

        if (!empty($metadata)) {
            $sessionData['metadata'] = $metadata;
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

        return $estimatedLoad;
    }

    /**
     * Resolve the training location to a theme type.
     * Handles both new onboarding values and legacy French strings.
     */
    private function resolveThemeType(): string
    {
        $location = strtolower(trim($this->profile->training_location ?? 'gym'));

        // Normalize underscores to spaces for flexible matching
        $normalized = str_replace('_', ' ', $location);

        // Direct match (new onboarding values)
        if (isset(self::LOCATION_TYPE_MAP[$location])) {
            return self::LOCATION_TYPE_MAP[$location];
        }

        // Extended mapping covering all known location value formats:
        // - "Si ..." prefix (legacy French from original programme doc)
        // - UPPER_CASE_UNDERSCORE (seeder/DB enum values)
        // - lower_case_underscore (API input)
        // - "lower case spaces" (normalized)
        $extendedMap = [
            // Gym / Musculation
            'si musculation en salle'             => 'gym',
            'musculation en salle'                => 'gym',
            'si musculation et cardio en salle'   => 'gym',
            'musculation et cardio en salle'      => 'gym',
            'salle'                               => 'gym',

            // Cardio
            'si cardio en salle'                  => 'cardio',
            'cardio en salle'                     => 'cardio',
            'si dehors'                           => 'cardio',
            'dehors'                              => 'cardio',
            'outdoor'                             => 'cardio',

            // Home
            'si maison'                           => 'home',
            'maison'                              => 'home',
            'home'                                => 'home',
        ];

        // Try direct location key
        if (isset($extendedMap[$location])) {
            return $extendedMap[$location];
        }

        // Try normalized (underscores → spaces)
        if (isset($extendedMap[$normalized])) {
            return $extendedMap[$normalized];
        }

        // Keyword-based fallback
        if (str_contains($normalized, 'maison') || str_contains($normalized, 'home')) {
            return 'home';
        }
        if (str_contains($normalized, 'cardio') || str_contains($normalized, 'dehors') || str_contains($normalized, 'outdoor')) {
            return 'cardio';
        }

        return 'gym';
    }

    /**
     * Dynamically selects a workout theme based on user profile and location.
     *
     * Improvements over previous version:
     * - Proper fallback to ANY theme of the right type if profile has no mappings
     * - Theme variety: avoids repeating the same theme within a week
     * - For "mixed" locations, alternates between gym and cardio themes
     * - Zone-aware: skips high-fatigue zones used the previous day
     */
    private function getDynamicTheme(
        PlayerProfile $playerProfile,
        string $themeType,
        array $usedZones = [],
        array $usedThemeIds = [],
        bool $isPrincipal = true
    ): ?WorkoutTheme {

        // For "mixed" location: alternate gym/cardio based on principal flag
        $rawLocation = strtolower(trim($this->profile->training_location ?? ''));
        if ($rawLocation === 'mixed' || str_contains($rawLocation, 'musculation et cardio') || str_contains(str_replace('_', ' ', $rawLocation), 'musculation et cardio')) {
            $themeType = $isPrincipal ? 'gym' : 'cardio';
        }

        // Get all theme percentages for this profile and location type
        $themes = $playerProfile->themes()->where('type', $themeType)->get();

        // Fallback: if profile has no themes for this type, select with zone awareness
        if ($themes->isEmpty()) {
            $query = WorkoutTheme::where('type', $themeType)
                ->whereNotIn('id', $usedThemeIds);

            // Apply zone-awareness even in fallback mode
            if (!empty($usedZones)) {
                $zoneAware = (clone $query)->whereNotIn('zone_color', $usedZones)->get();
                if ($zoneAware->isNotEmpty()) {
                    return $zoneAware->random();
                }
            }

            $fallback = $query->inRandomOrder()->first();
            if (!$fallback) {
                // Everything used this week, allow repeats but still respect zones
                $fallback = WorkoutTheme::where('type', $themeType)->inRandomOrder()->first();
            }

            return $fallback;
        }

        // 1. Filter out zones with poor freshness from previous day
        if (!empty($usedZones)) {
            $filtered = $themes->filter(fn ($t) => !in_array($t->zone_color, $usedZones));
            if ($filtered->isNotEmpty()) {
                $themes = $filtered;
            }
        }

        // 2. Prefer themes not yet used this week (variety)
        if (!empty($usedThemeIds)) {
            $varied = $themes->filter(fn ($t) => !in_array($t->id, $usedThemeIds));
            if ($varied->isNotEmpty()) {
                $themes = $varied;
            }
        }

        // 3. Weighted random selection using pivot percentages
        $totalWeight = $themes->sum('pivot.percentage');
        if ($totalWeight <= 0) {
            return $themes->random();
        }

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
     * Maps a theme name to a likely exercise sub_category.
     * This helps select more relevant exercises from the database.
     */
    private function mapThemeToSubCategory(?string $themeName): ?string
    {
        if (!$themeName) return null;

        $lower = strtolower($themeName);

        // Force / strength themes → compound movements
        if (str_contains($lower, 'force')) return 'FORCE';
        if (str_contains($lower, 'puissance')) return 'PUISSANCE';

        // Hypertrophy → isolation + compound
        if (str_contains($lower, 'hypertrophie')) return 'HYPERTROPHIE';
        if (str_contains($lower, 'volume')) return 'VOLUME';

        // Endurance themes
        if (str_contains($lower, 'endurance')) return 'ENDURANCE';

        // Body composition
        if (str_contains($lower, 'perte') || str_contains($lower, 'sèche')) return 'CIRCUIT';
        if (str_contains($lower, 'condition')) return 'FONCTIONNEL';

        // Recovery / prevention
        if (str_contains($lower, 'prévention') || str_contains($lower, 'tendineux')) return 'PRÉVENTION';
        if (str_contains($lower, 'réathlétisation')) return 'RÉATHLÉTISATION';
        if (str_contains($lower, 'coordination')) return 'COORDINATION';
        if (str_contains($lower, 'remise')) return 'REMISE EN FORME';

        return null;
    }

    /**
     * Returns a warmup instruction adjusted for the session's intensity zone.
     */
    private function getZoneAwareWarmup(?string $zoneColor): string
    {
        return match ($zoneColor) {
            'red'    => "15 min échauffement progressif (cardio → mobilité → activation neuromusculaire → montées en charge)",
            'orange' => "12 min échauffement (cardio léger → mobilité dynamique → activation musculaire)",
            'yellow' => "10 min échauffement (cardio léger + mobilité articulaire)",
            'green'  => "8 min échauffement (marche rapide + mobilité)",
            'blue'   => "5 min échauffement léger",
            default  => "10 min échauffement (cardio léger + mobilité)",
        };
    }

    /**
     * Helper function to get exercises from the DB.
     * Tries to match sub_category, falls back to category-only.
     */
    private function getExercisesForTheme(?string $category, ?string $subCategory, int $limit = 5)
    {
        $query = Exercise::query();

        if ($category) {
            $query->where('category', $category);
        }
        if ($subCategory) {
            $query->where('sub_category', 'like', '%' . $subCategory . '%');
        }

        $results = $query->inRandomOrder()->limit($limit)->get();

        // Fallback: if sub-category was too specific, try category only
        if ($results->count() < $limit && $subCategory && $category !== 'KINE MOBILITÉ') {
            $remaining = $limit - $results->count();
            $extras = Exercise::where('category', $category ?? 'MUSCULATION')
                ->whereNotIn('id', $results->pluck('id')->toArray())
                ->inRandomOrder()
                ->limit($remaining)
                ->get();
            $results = $results->merge($extras);
        }

        // Final fallback: if still empty, get generic exercises
        if ($results->isEmpty()) {
            return Exercise::where('category', 'MUSCULATION')
                ->inRandomOrder()
                ->limit($limit)
                ->get();
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
     * Helper to get the bonus finisher text, adjusted for the user's level.
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
