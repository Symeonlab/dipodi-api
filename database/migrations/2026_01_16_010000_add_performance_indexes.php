<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add indexes for better query performance on frequently accessed columns.
     */
    public function up(): void
    {
        // User Progress - frequently queried by user_id and date
        Schema::table('user_progress', function (Blueprint $table) {
            $table->index('user_id', 'idx_user_progress_user_id');
            $table->index('date', 'idx_user_progress_date');
        });

        // Workout Sessions - frequently queried by user_id and day
        Schema::table('workout_sessions', function (Blueprint $table) {
            $table->index('user_id', 'idx_workout_sessions_user_id');
            $table->index('day', 'idx_workout_sessions_day');
            $table->index(['user_id', 'day'], 'idx_workout_sessions_user_day');
        });

        // Exercises - frequently filtered by category and sub_category
        Schema::table('exercises', function (Blueprint $table) {
            $table->index('category', 'idx_exercises_category');
            $table->index('sub_category', 'idx_exercises_sub_category');
            $table->index(['category', 'sub_category'], 'idx_exercises_cat_subcat');
        });

        // User Profiles - frequently queried by user_id and discipline
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->index('discipline', 'idx_user_profiles_discipline');
            $table->index('position', 'idx_user_profiles_position');
            $table->index('goal', 'idx_user_profiles_goal');
        });

        // User Goals - frequently queried by status
        Schema::table('user_goals', function (Blueprint $table) {
            $table->index('user_id', 'idx_user_goals_user_id');
            $table->index('status', 'idx_user_goals_status');
            $table->index(['user_id', 'status'], 'idx_user_goals_user_status');
        });

        // User Favorite Exercises - pivot table optimization
        Schema::table('user_favorite_exercises', function (Blueprint $table) {
            $table->index('user_id', 'idx_user_fav_exercises_user_id');
            $table->index('exercise_id', 'idx_user_fav_exercises_exercise_id');
        });

        // Nutrition Advice - frequently searched by condition
        Schema::table('nutrition_advice', function (Blueprint $table) {
            $table->index('condition_name', 'idx_nutrition_advice_condition');
        });

        // Food Items - frequently filtered by category
        Schema::table('food_items', function (Blueprint $table) {
            $table->index('category', 'idx_food_items_category');
        });

        // Posts - frequently filtered by published status
        Schema::table('posts', function (Blueprint $table) {
            $table->index('is_published', 'idx_posts_published');
        });

        // Onboarding Options - frequently filtered by type
        Schema::table('onboarding_options', function (Blueprint $table) {
            $table->index('type', 'idx_onboarding_options_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropIndex('idx_user_progress_user_id');
            $table->dropIndex('idx_user_progress_date');
        });

        Schema::table('workout_sessions', function (Blueprint $table) {
            $table->dropIndex('idx_workout_sessions_user_id');
            $table->dropIndex('idx_workout_sessions_day');
            $table->dropIndex('idx_workout_sessions_user_day');
        });

        Schema::table('exercises', function (Blueprint $table) {
            $table->dropIndex('idx_exercises_category');
            $table->dropIndex('idx_exercises_sub_category');
            $table->dropIndex('idx_exercises_cat_subcat');
        });

        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropIndex('idx_user_profiles_discipline');
            $table->dropIndex('idx_user_profiles_position');
            $table->dropIndex('idx_user_profiles_goal');
        });

        Schema::table('user_goals', function (Blueprint $table) {
            $table->dropIndex('idx_user_goals_user_id');
            $table->dropIndex('idx_user_goals_status');
            $table->dropIndex('idx_user_goals_user_status');
        });

        Schema::table('user_favorite_exercises', function (Blueprint $table) {
            $table->dropIndex('idx_user_fav_exercises_user_id');
            $table->dropIndex('idx_user_fav_exercises_exercise_id');
        });

        Schema::table('nutrition_advice', function (Blueprint $table) {
            $table->dropIndex('idx_nutrition_advice_condition');
        });

        Schema::table('food_items', function (Blueprint $table) {
            $table->dropIndex('idx_food_items_category');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('idx_posts_published');
        });

        Schema::table('onboarding_options', function (Blueprint $table) {
            $table->dropIndex('idx_onboarding_options_type');
        });
    }
};
