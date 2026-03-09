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
    private string $locale;

    public function __construct(User $user, ?string $locale = null)
    {
        $this->user = $user;
        $this->profile = $user->profile;
        $this->locale = $locale ?? app()->getLocale();
    }

    public function generatePlan(): array
    {
        $calories = $this->calculateDailyCalorieIntake();

        return [
            'daily_calorie_intake' => round($calories),
            'macros' => [
                'protein_grams' => round(($calories * 0.3) / 4), // 30% Protein
                'carb_grams' => round(($calories * 0.4) / 4),    // 40% Carbs
                'fat_grams' => round(($calories * 0.3) / 9),     // 30% Fat
            ],
            'daily_meals' => $this->generateMeals($calories),
            'advice' => $this->getNutritionalAdvice(),
        ];
    }

    private function calculateDailyCalorieIntake(): float
    {
        // Harris-Benedict formula from PDF
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

    private function generateMeals(float $dailyCalories): array
    {
        $meals = [];
        $mealNames = $this->getMealNames();
        $isMuscleGoal = strtoupper($this->profile->goal) === "MASSE MUSCULAIRE";

        // Calorie distribution: breakfast 25%, lunch 35%, dinner 30%, snack 10%
        $breakfastCal = round($dailyCalories * 0.25);
        $lunchCal = round($dailyCalories * ($isMuscleGoal ? 0.30 : 0.35));
        $dinnerCal = round($dailyCalories * 0.30);
        $snackCal = $isMuscleGoal ? round($dailyCalories * 0.15) : 0;

        $meals[] = [
            'name' => $mealNames['breakfast'],
            'meal_type' => 'breakfast',
            'items' => $this->generateBreakfastItems(),
            'estimated_calories' => $breakfastCal,
        ];

        $lunchItems = $this->generateLunchOrDinnerItems('lunch');
        $meals[] = [
            'name' => $mealNames['lunch'],
            'meal_type' => 'lunch',
            'items' => array_column($lunchItems, 'name'),
            'food_details' => $lunchItems,
            'estimated_calories' => $lunchCal,
        ];

        $dinnerItems = $this->generateLunchOrDinnerItems('dinner');
        $meals[] = [
            'name' => $mealNames['dinner'],
            'meal_type' => 'dinner',
            'items' => array_column($dinnerItems, 'name'),
            'food_details' => $dinnerItems,
            'estimated_calories' => $dinnerCal,
        ];

        if ($isMuscleGoal) {
            $meals[] = [
                'name' => $mealNames['snack'],
                'meal_type' => 'snack',
                'items' => ["50g d'amandes", "1 pomme"],
                'food_details' => [
                    ['name' => "50g d'amandes", 'kcal_per_100g' => 634, 'food_type' => 'fruit_sec'],
                    ['name' => '1 pomme', 'kcal_per_100g' => 53, 'food_type' => 'fruit'],
                ],
                'estimated_calories' => $snackCal,
            ];
        }

        return $meals;
    }

    private function getMealNames(): array
    {
        $names = [
            'en' => [
                'breakfast' => 'Breakfast',
                'lunch' => 'Lunch',
                'dinner' => 'Dinner',
                'snack' => 'Snack',
            ],
            'fr' => [
                'breakfast' => 'Petit déjeuner',
                'lunch' => 'Déjeuner',
                'dinner' => 'Dîner',
                'snack' => 'Collation',
            ],
            'ar' => [
                'breakfast' => 'فطور',
                'lunch' => 'غداء',
                'dinner' => 'عشاء',
                'snack' => 'وجبة خفيفة',
            ],
        ];

        return $names[$this->locale] ?? $names['fr'];
    }

    private function generateBreakfastItems(): array
    {
        if (empty($this->profile->breakfast_preferences)) {
            return ["150g fromage blanc", "100g flocon d'avoine", "1 banane"];
        }

        return $this->profile->breakfast_preferences;
    }

    /**
     * Returns an array of food detail objects with name, kcal_per_100g, and food_type.
     */
    private function generateLunchOrDinnerItems(string $mealType): array
    {
        $main = FoodItem::where('category', 'platPrincipal');
        $side = FoodItem::where('category', 'accompagnement');
        $dessert = FoodItem::where('category', 'dessert');

        if ($this->profile->is_vegetarian) {
            $main->whereJsonContains('tags', 'vegetarien');
        } else {
            $main->where(function ($query) {
                $query->whereJsonContains('tags', 'viande')
                    ->orWhereJsonContains('tags', 'poisson');
            });
        }

        // Rule: "PAS DE FRUITS LE SOIR" (No fruits in the evening)
        if ($mealType === 'dinner') {
            $dessert->whereJsonDoesntContain('tags', 'fruit');
        }

        $mainItem = $main->inRandomOrder()->first();
        $sideItem = $side->inRandomOrder()->first();
        $vegItem = FoodItem::whereJsonContains('tags', 'legume')->inRandomOrder()->first();
        $dessertItem = $dessert->inRandomOrder()->first();

        return [
            $this->formatFoodDetail($mainItem, 'Filet de poulet', 'plat_principal'),
            $this->formatFoodDetail($sideItem, 'Riz', 'accompagnement'),
            $this->formatFoodDetail($vegItem, 'Haricots verts', 'legume'),
            $this->formatFoodDetail($dessertItem, 'Yaourt nature', 'dessert'),
        ];
    }

    /**
     * Extracts calorie info and food type from a FoodItem's tags.
     */
    private function formatFoodDetail(?FoodItem $item, string $fallbackName, string $fallbackType): array
    {
        if (!$item) {
            return [
                'name' => $fallbackName,
                'kcal_per_100g' => null,
                'food_type' => $fallbackType,
            ];
        }

        $kcal = null;
        $foodType = $fallbackType;
        $tags = $item->tags ?? [];

        // Extract kcal from tags (format: "287.0kcal")
        foreach ($tags as $tag) {
            if (str_ends_with($tag, 'kcal')) {
                $kcal = (float) rtrim($tag, 'kcal');
            }
        }

        // Detect food type from tags
        foreach ($tags as $tag) {
            if (in_array($tag, ['viande', 'poisson', 'legume', 'feculent', 'fruit', 'laitage', 'oeuf', 'vegetarien'])) {
                $foodType = $tag;
                break;
            }
        }

        return [
            'name' => $item->name,
            'kcal_per_100g' => $kcal,
            'food_type' => $foodType,
        ];
    }

    /**
     * Returns localized advice based on user's medical/family history.
     */
    private function getNutritionalAdvice(): array
    {
        $adviceList = [];
        $conditions = array_merge($this->profile->medical_history ?? [], $this->profile->family_history ?? []);

        if (empty($conditions)) {
            return [];
        }

        $dbAdvice = NutritionAdvice::whereIn('condition_name', $conditions)->get();

        $propheticField = "prophetic_advice_{$this->locale}";

        foreach ($dbAdvice as $advice) {
            $propheticAdvice = $advice->{$propheticField}
                ?? $advice->prophetic_advice_fr
                ?? $advice->prophetic_advice_en
                ?? null;

            $adviceList[] = [
                'condition' => $advice->condition_name,
                'avoid' => $advice->foods_to_avoid ?? [],
                'eat' => $advice->foods_to_eat ?? [],
                'prophetic_advice' => $propheticAdvice,
            ];
        }
        return $adviceList;
    }
}
