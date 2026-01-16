<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // 0. Admin Account (always seed first)
            AdminSeeder::class,

            // 1. Static App Data
            OnboardingOptionSeeder::class,
            NutritionAdviceSeeder::class,
            FoodItemSeeder::class,
            ExerciseSeeder::class,
            PlayerProfileSeeder::class,
            WorkoutThemeRuleSeeder::class,
            BonusWorkoutRuleSeeder::class,
            InterestSeeder::class,
            AchievementSeeder::class,

            // 2. Test Users (depends on static data)
            UserSeeder::class,

            // 3. Data related to users (depends on UserSeeder)
            UserReminderSettingSeeder::class,
            UserFavoriteExerciseSeeder::class,
        ]);

    }
}
