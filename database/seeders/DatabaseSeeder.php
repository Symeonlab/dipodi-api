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

            // 1. Static App Data - Onboarding & Interests
            OnboardingOptionSeeder::class,
            InterestSeeder::class,

            // 2. DIPODDI Programme (comprehensive data from DIPODDI_PROGRAMME.xlsx)
            //    Seeds: PlayerProfiles, WorkoutThemes+Rules, Exercises, NutritionAdvice, BonusWorkoutRules
            //    This replaces the individual seeders: ExerciseSeeder, PlayerProfileSeeder,
            //    WorkoutThemeRuleSeeder, BonusWorkoutRuleSeeder, NutritionAdviceSeeder
            DipoddiProgrammeSeeder::class,

            // 2b. Programme Enhancement (zone colors, freshness, RPE, supercompensation)
            DipoddiProgrammeEnhancementSeeder::class,

            // 3. Additional cardio themes & profile-theme mappings
            DipoddiCardioAndMappingsSeeder::class,

            // 4. Food items (not included in DipoddiProgrammeSeeder)
            FoodItemSeeder::class,

            // 5. Achievements, Feedback & Health Assessment
            AchievementSeeder::class,
            FeedbackSeeder::class,
            HealthAssessmentSeeder::class,

            // 6. New Programme Data (Intensity Zones, Sleep, Prophetic Medicine, etc.)
            IntensityZoneSeeder::class,
            SleepProtocolSeeder::class,
            PropheticMedicineSeeder::class,
            TrainingDayLogicSeeder::class,
            HomeWorkoutRuleSeeder::class,

            // 7. Test Users (depends on static data)
            UserSeeder::class,

            // 8. Data related to users (depends on UserSeeder)
            UserReminderSettingSeeder::class,
            UserFavoriteExerciseSeeder::class,
        ]);
    }
}
