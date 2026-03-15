<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Goal type (weight_loss, muscle_gain, maintain, custom)
            $table->string('goal_type');

            // Target metrics
            $table->decimal('target_weight', 5, 2)->nullable();
            $table->decimal('target_waist', 5, 2)->nullable();
            $table->decimal('target_chest', 5, 2)->nullable();
            $table->decimal('target_hips', 5, 2)->nullable();
            $table->integer('target_workouts_per_week')->default(3);

            // Starting metrics (captured when goal is created)
            $table->decimal('start_weight', 5, 2)->nullable();
            $table->decimal('start_waist', 5, 2)->nullable();
            $table->decimal('start_chest', 5, 2)->nullable();
            $table->decimal('start_hips', 5, 2)->nullable();

            // Progress tracking
            $table->decimal('current_progress_percentage', 5, 2)->default(0);
            $table->integer('weeks_completed')->default(0);
            $table->integer('total_weeks')->default(12); // Default 12-week program

            // Dates
            $table->date('start_date');
            $table->date('target_date');

            // Status
            $table->enum('status', ['active', 'completed', 'paused', 'abandoned'])->default('active');
            $table->timestamp('completed_at')->nullable();

            // Achievements unlocked (JSON array of achievement keys)
            $table->json('achievements')->nullable();

            // Notes
            $table->text('notes')->nullable();

            $table->timestamps();
        });

        // Achievements table for gamification
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'first_workout', 'week_streak_4'
            $table->string('name_en');
            $table->string('name_fr');
            $table->string('name_ar');
            $table->text('description_en');
            $table->text('description_fr');
            $table->text('description_ar');
            $table->string('icon')->nullable(); // SF Symbol name
            $table->integer('points')->default(10);
            $table->string('category'); // workout, nutrition, consistency, milestone
            $table->timestamps();
        });

        // Pivot table for users and achievements
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            $table->timestamp('earned_at');
            $table->unique(['user_id', 'achievement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('user_goals');
    }
};
