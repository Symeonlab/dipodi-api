<?php

namespace App\Services\Workout;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Exercise;
use App\Models\PlayerProfile;
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
        $trainingDays = $this->profile->training_days ?? []; // Use the correct snake_case
        $schedule = MatchAwarePlanGenerator::generate($this->profile->match_day, $trainingDays);

        // 3. Find the user's DB Player Profile
        $playerProfile = PlayerProfile::where('name', $this->profile->position)->first();

        // 4. Generate a session for each day
        foreach ($schedule as $day) {
            $this->generateSessionForDay($day, $playerProfile);
        }
    }

    /**
     * Generates a specific session based on the day's type and user profile.
     */
    private function generateSessionForDay(array $daySchedule, ?PlayerProfile $playerProfile)
    {
        $dayName = $daySchedule['day'];
        $dayType = $daySchedule['type'];

        // Default: Rest day
        $sessionThemeName = "Repos";
        $exercises = collect(); // Use an empty collection
        $warmup = null;
        $finisher = "5 min retour au calme (étirements)";

        if ($dayType === MatchAwarePlanGenerator::T_MATCH) {
            $sessionThemeName = "Jour de Match";
        }

        if ($dayType === MatchAwarePlanGenerator::T_RECOVERY || $dayType === MatchAwarePlanGenerator::T_MOBILITY) {
            $sessionThemeName = "Mobilité & Récupération";
            $exercises = $this->getExercisesForTheme(null, 'KINE MOBILITÉ', 5);
            $warmup = "5 min échauffement léger";
        }

        if ($dayType === MatchAwarePlanGenerator::T_HIGH_INTENSITY || $dayType === MatchAwarePlanGenerator::T_STRENGTH) {
            if (!$playerProfile) {
                Log::warning("No PlayerProfile found for position: {$this->profile->position}");
                $sessionThemeName = "Entraînement Général";
                $exercises = $this->getExercisesForTheme('MUSCULATION', null, 5);
            } else {
                // Find the theme based on location and profile percentages
                $workoutTheme = $this->getDynamicTheme($playerProfile);
                $sessionThemeName = $workoutTheme ? $workoutTheme->name : "Entraînement Général";

                // Get exercises
                $exercises = $this->getExercisesForTheme(null, $sessionThemeName, 5);
            }
            $warmup = "10 min échauffement (cardio léger + mobilité)";

            // Add dynamic bonus finisher
            $finisher = $this->getDynamicBonusFinisher();
        }

        // Get the dynamic rules for this theme
        $themeRules = \App\Models\WorkoutTheme::where('name', $sessionThemeName)
            ->first()
            ?->rules; // Assumes a 'rules' relationship on WorkoutTheme model

        // Save the session
        $session = WorkoutSession::create([
            'user_id' => $this->user->id,
            'day' => $dayName,
            'theme' => $sessionThemeName,
            'warmup' => $warmup,
            'finisher' => $finisher,
        ]);

        // Save the exercises for the session
        foreach ($exercises as $exercise) {
            $session->exercises()->create([
                'name' => $exercise->name,
                'sets' => $themeRules->sets ?? '4',
                'reps' => $themeRules->reps ?? '8-12',
                'recovery' => $themeRules->recovery_time ?? '1 min 30',
                'video_url' => $exercise->video_url,
            ]);
        }
    }

    /**
     * Dynamically selects a workout theme based on user profile and location.
     */
    private function getDynamicTheme(PlayerProfile $playerProfile): ?\App\Models\WorkoutTheme
    {
        $location = $this->profile->training_location; // e.g., "SI CARDIO EN SALLE"
        $type = 'gym'; // default

        if ($location === 'SI CARDIO EN SALLE') $type = 'cardio';
        if ($location === 'SI DEHORS') $type = 'outside';
        if ($location === 'SI MAISON') $type = 'home';
        if ($location === 'SI MUSCULATION ET CARDIO EN SALLE') $type = 'gym'; // Default to gym for combo

        // Get all theme percentages for this profile and location type
        $themes = $playerProfile->themes()->where('type', $type)->get();
        if ($themes->isEmpty()) {
            return null; // No themes found for this location
        }

        // Weighted random selection
        $totalWeight = $themes->sum('pivot.percentage');
        if ($totalWeight <= 0) return $themes->random(); // Fallback if percentages are 0

        $randomValue = rand(1, $totalWeight);

        foreach ($themes as $theme) {
            // --- THIS IS THE FIX ---
            // Use '->' (arrow) instead of '.' (dot) to access object properties
            if ($randomValue <= $theme->pivot->percentage) {
                return $theme;
            }
            $randomValue -= $theme->pivot->percentage;
            // --- END OF FIX ---
        }

        return $themes->random(); // Fallback
    }

    // --- THIS IS THE MISSING FUNCTION ---
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
    // --- END OF MISSING FUNCTION ---

    // --- THIS IS THE MISSING FUNCTION ---
    /**
     * Helper to get the bonus finisher text
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
    // --- END OF MISSING FUNCTION ---
}
