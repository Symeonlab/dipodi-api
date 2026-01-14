<?php

namespace App\Services\Nutrition;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\FoodItem;
use App\Models\NutritionAdvice;

class NutritionPlanGenerator
{
    private UserProfile $profile;
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->profile = $user->profile;
    }

    public function generatePlan(): array
    {
        $calories = $this->calculateDailyCalorieIntake();

        return [
            'daily_calorie_intake' => round($calories),
            'macros' => [ // Example macros, you can customize this
                'protein_grams' => round(($calories * 0.3) / 4), // 30% Protein
                'carb_grams' => round(($calories * 0.4) / 4),    // 40% Carbs
                'fat_grams' => round(($calories * 0.3) / 9),     // 30% Fat
            ],
            'daily_meals' => $this->generateMeals(),
            'advice' => $this->getNutritionalAdvice(), // This is now dynamic
        ];
    }

    private function calculateDailyCalorieIntake(): float
    {
        // Formula from PDF
        $mb = 0;
        if (strtoupper($this->profile->gender) === 'HOMME') {
            $mb = 66 + (13.7 * $this->profile->weight) + (5.0 * $this->profile->height) - (6.5 * $this->profile->age);
        } else { // FEMME
            $mb = 655 + (9.6 * $this->profile->weight) + (1.8 * $this->profile->height) - (4.7 * $this->profile->age);
        }

        // NAP coefficients from PDF
        $activityMultiplier = 1.2; // Default to sedentary
        $level = strtoupper($this->profile->activity_level);

        if (in_array($level, ["ACTIVE", "ACTIF", "MODÉRÉ", "MODERE"])) {
            $activityMultiplier = 1.4;
        } elseif (in_array($level, ["TRÈS ACTIVE", "TRÈS ACTIF", "TRES ACTIVE", "TRES ACTIF"])) {
            $activityMultiplier = 1.6;
        } elseif (in_array($level, ["EXTRÊMEMENT ACTIVE", "EXTREMEMENT ACTIVE", "SPORTIF DE HAUT NIVEAU"])) {
            $activityMultiplier = 1.8;
        }

        $totalCalories = $mb * $activityMultiplier;

        // Adjust for goal
        switch (strtoupper($this->profile->goal)) {
            case "PERDRE DU POIDS":
                $totalCalories *= 0.85; // -15%
                break;
            case "MASSE MUSCULAIRE":
                $totalCalories *= 1.15; // +15%
                break;
        }

        return $totalCalories;
    }

    private function generateMeals(): array
    {
        $meals = [];

        $meals[] = [
            'name' => 'Petit déjeuner',
            'items' => $this->generateBreakfastItems(),
        ];

        $meals[] = [
            'name' => 'Déjeuner',
            'items' => $this->generateLunchOrDinnerItems('Déjeuner'),
        ];

        $meals[] = [
            'name' => 'Dîner',
            'items' => $this->generateLunchOrDinnerItems('Dîner'),
        ];

        if (strtoupper($this->profile->goal) === "MASSE MUSCULAIRE") {
            $meals[] = [
                'name' => 'Collation',
                'items' => ["50g d'amandes", "1 pomme"], // Example
            ];
        }

        return $meals;
    }

    private function generateBreakfastItems(): array
    {
        if (empty($this->profile->breakfast_preferences)) {
            // Default example from PDF
            return ["150g fromage blanc", "100g flocon d'avoine", "1 banane"];
        }

        // This is a simple pass-through; you can expand this
        return $this->profile->breakfast_preferences;
    }

    private function generateLunchOrDinnerItems(string $mealName): array
    {
        // Dynamic generation from your DB
        $main = FoodItem::where('category', 'platPrincipal');
        $side = FoodItem::where('category', 'accompagnement');
        $dessert = FoodItem::where('category', 'dessert');

        if ($this->profile->is_vegetarian) {
            $main->whereJsonContains('tags', 'vegetarien');
        } else {
            $main->whereJsonContains('tags', 'viande')
                ->orWhereJsonContains('tags', 'poisson');
        }

        // Rule: "PAS DE FRUITS LE SOIR"
        if ($mealName === 'Dîner') {
            $dessert->whereJsonDoesntContain('tags', 'fruit');
        }

        return [
            $main->inRandomOrder()->first()->name ?? 'Filet de poulet',
            $side->inRandomOrder()->first()->name ?? 'Riz',
            FoodItem::whereJsonContains('tags', 'legume')->inRandomOrder()->first()->name ?? 'Haricots verts',
            $dessert->inRandomOrder()->first()->name ?? 'Yaourt nature',
        ];
    }

    /**
     * This function is now fully dynamic and queries the database.
     */
    private function getNutritionalAdvice(): array
    {
        $adviceList = [];
        $conditions = array_merge($this->profile->medical_history ?? [], $this->profile->family_history ?? []);

        if (empty($conditions)) {
            return [];
        }

        // Query the DB for all relevant conditions at once
        $dbAdvice = NutritionAdvice::whereIn('condition_name', $conditions)->get();

        foreach ($dbAdvice as $advice) {
            $adviceList[] = [
                'condition' => $advice->condition_name,
                'avoid' => $advice->foods_to_avoid, // Already cast to array
                'eat' => $advice->foods_to_eat,     // Already cast to array
                'prophetic_advice' => $advice->prophetic_advice_fr // Add en/ar logic later
            ];
        }
        return $adviceList;
    }
}
