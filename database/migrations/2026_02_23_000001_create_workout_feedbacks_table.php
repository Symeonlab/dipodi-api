<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Post-workout feedback questionnaire.
     * Captures how the user felt about the workout to customize future sessions.
     */
    public function up(): void
    {
        Schema::create('workout_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('session_day');          // e.g. "Monday", "Day 1"
            $table->string('session_theme');         // e.g. "Upper Body", "Cardio"
            $table->integer('exercises_completed')->default(0);
            $table->integer('elapsed_seconds')->default(0);

            // Core feedback questions (1-5 scale)
            $table->tinyInteger('difficulty_rating')->nullable()->comment('1=Too Easy, 5=Too Hard');
            $table->tinyInteger('energy_level')->nullable()->comment('1=Exhausted, 5=Energized');
            $table->tinyInteger('enjoyment_rating')->nullable()->comment('1=Boring, 5=Loved It');
            $table->tinyInteger('muscle_soreness')->nullable()->comment('1=None, 5=Very Sore');

            // Optional specifics
            $table->json('sore_areas')->nullable()->comment('Array of body area strings');
            $table->boolean('completed_all_sets')->nullable();
            $table->string('skipped_reason')->nullable()->comment('If sets were skipped: too_hard, too_easy, injury, time');

            // Free text
            $table->text('notes')->nullable()->comment('Additional user notes');

            // Customization preferences
            $table->string('preferred_adjustment')->nullable()->comment('increase_intensity, decrease_intensity, more_rest, fewer_exercises, keep_same');

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'session_theme']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_feedbacks');
    }
};
