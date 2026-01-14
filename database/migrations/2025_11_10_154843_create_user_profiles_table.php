<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // --- Sport (from OnboardingData.swift) ---
            $table->string('discipline')->nullable();
            $table->string('position')->nullable();
            $table->boolean('in_club')->nullable();
            $table->string('match_day')->nullable();
            $table->json('training_days')->nullable(); // [String]
            $table->string('training_focus')->nullable();
            $table->string('level')->nullable();
            $table->boolean('has_injury')->nullable();
            $table->string('injury_location')->nullable();
            $table->string('training_location')->nullable();
            $table->json('gym_preferences')->nullable(); // [String]
            $table->json('cardio_preferences')->nullable(); // [String]
            $table->json('outdoor_preferences')->nullable(); // [String]
            $table->json('home_preferences')->nullable(); // [String]

            // --- Personal Info (from OnboardingData.swift) ---
            // 'firstName' and 'lastName' are handled by the main 'users' table 'name' field
            $table->string('gender')->nullable();
            $table->double('height')->nullable();
            $table->double('weight')->nullable();
            $table->integer('age')->nullable();
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('pro_level')->nullable();
            $table->string('apple_id')->nullable()->unique(); // For Apple Sign In

            // --- Nutrition (from OnboardingData.swift) ---
            $table->double('ideal_weight')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('activity_level')->nullable();
            $table->string('goal')->nullable();
            $table->string('morphology')->nullable();
            $table->string('hormonal_issues')->nullable();
            $table->boolean('is_vegetarian')->nullable();
            $table->string('meals_per_day')->nullable();
            $table->json('breakfast_preferences')->nullable(); // [String]
            $table->json('bad_habits')->nullable(); // [String]
            $table->string('snacking_habits')->nullable();
            $table->string('vegetable_consumption')->nullable();
            $table->string('fish_consumption')->nullable();
            $table->string('meat_consumption')->nullable();
            $table->string('dairy_consumption')->nullable();
            $table->string('sugary_food_consumption')->nullable();
            $table->string('cereal_consumption')->nullable();
            $table->string('starchy_food_consumption')->nullable();
            $table->string('sugary_drink_consumption')->nullable();
            $table->string('egg_consumption')->nullable();
            $table->string('fruit_consumption')->nullable();
            $table->boolean('takes_medication')->nullable();
            $table->boolean('has_diabetes')->nullable();
            $table->json('family_history')->nullable(); // [String]
            $table->json('medical_history')->nullable(); // [String]

            // --- App Logic ---
            $table->boolean('is_onboarding_complete')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
