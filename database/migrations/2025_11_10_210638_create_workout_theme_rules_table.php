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
        Schema::create('workout_theme_rules', function (Blueprint $table) {
            $table->id();
            // Link to the 'workout_themes' table
            $table->foreignId('workout_theme_id')->constrained()->onDelete('cascade');

            // Data from the PDF (e.g., "4 et 8 exercices")
            $table->string('exercise_count'); // e.g., "4-8"
            $table->string('sets'); // e.g., "2-5"
            $table->string('reps'); // e.g., "25-35"
            $table->string('recovery_time'); // e.g., "30 sec a 1 min"
            $table->string('load_type'); // e.g., "charges légères"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_theme_rules');
    }
};
