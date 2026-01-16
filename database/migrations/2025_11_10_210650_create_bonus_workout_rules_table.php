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
        Schema::create('bonus_workout_rules', function (Blueprint $table) {
            $table->id();
            $table->string('level'); // "DÉBUTANT", "INTERMÉDIAIRE", "AVANCÉ", "ALL"
            $table->string('type'); // "ABDOS", "POMPES", "GAINAGE", or combined types
            $table->string('sets');
            $table->string('reps');
            $table->string('recovery');
            $table->string('duration')->nullable(); // "12 MIN", "20 MIN", etc.
            $table->string('exercise_count')->nullable(); // "3-4", "4-5", etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_workout_rules');
    }
};
