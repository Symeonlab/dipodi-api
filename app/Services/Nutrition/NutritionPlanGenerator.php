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

        // Localized meal names
        $mealNames = $this->getMealNames();

        $meals[] = [
            'name' => $mealNames['breakfast'],
            'items' => $this->generateBreakfastItems(),
        ];

        $meals[] = [
            'name' => $mealNames['lunch'],
            'items' => $this->generateLunchOrDinnerItems('lunch'),
        ];

        $meals[] = [
            'name' => $mealNames['dinner'],
            'items' => $this->generateLunchOrDinnerItems('dinner'),
        ];

        if (strtoupper($this->profile->goal) === "MASSE MUSCULAIRE") {
            $meals[] = [
                'name' => $mealNames['snack'],
                'items' => ["50g d'amandes", "1 pomme"], // Example
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
            // Default example from PDF
            return ["150g fromage blanc", "100g flocon d'avoine", "1 banane"];
        }

        // This is a simple pass-through; you can expand this
        return $this->profile->breakfast_preferences;
    }

    private function generateLunchOrDinnerItems(string $mealType): array
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

        // Rule: "PAS DE FRUITS LE SOIR" (No fruits in the evening)
        if ($mealType === 'dinner') {
            $dessert->whereJsonDoesntContain('tags', 'fruit');
        }

        // Get localized food names
        $nameField = $this->locale === 'ar' ? 'name_ar' : ($this->locale === 'en' ? 'name_en' : 'name');

        $mainItem = $main->inRandomOrder()->first();
        $sideItem = $side->inRandomOrder()->first();
        $vegItem = FoodItem::whereJsonContains('tags', 'legume')->inRandomOrder()->first();
        $dessertItem = $dessert->inRandomOrder()->first();

        return [
            $mainItem->{$nameField} ?? $mainItem->name ?? 'Filet de poulet',
            $sideItem->{$nameField} ?? $sideItem->name ?? 'Riz',
            $vegItem->{$nameField} ?? $vegItem->name ?? 'Haricots verts',
            $dessertItem->{$nameField} ?? $dessertItem->name ?? 'Yaourt nature',
        ];
    }

    /**
     * This function is now fully dynamic and queries the database.
     * Returns localized advice based on user's medical/family history.
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

        // Determine locale-specific field names
        $propheticField = "prophetic_advice_{$this->locale}";
        $avoidField = "foods_to_avoid_{$this->locale}";
        $eatField = "foods_to_eat_{$this->locale}";

        foreach ($dbAdvice as $advice) {
            // Get prophetic advice with fallback chain: requested locale -> fr -> en
            $propheticAdvice = $advice->{$propheticField}
                ?? $advice->prophetic_advice_fr
                ?? $advice->prophetic_advice_en
                ?? null;

            // Get foods to avoid with locale fallback
            $foodsToAvoid = $advice->{$avoidField}
                ?? $advice->foods_to_avoid
                ?? [];

            // Get foods to eat with locale fallback
            $foodsToEat = $advice->{$eatField}
                ?? $advice->foods_to_eat
                ?? [];

            $adviceList[] = [
                'condition' => $advice->condition_name,
                'avoid' => $foodsToAvoid,
                'eat' => $foodsToEat,
                'prophetic_advice' => $propheticAdvice,
            ];
        }
        return $adviceList;
    }
}
