<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_workout_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_profile_id')->constrained()->onDelete('cascade');
            $table->string('objective'); // perte_de_poids, renforcement
            $table->string('duration'); // e.g. "35-45 min"
            $table->string('exercise_count'); // e.g. "8-10"
            $table->string('circuits'); // e.g. "4-5"
            $table->string('effort_time'); // e.g. "40 sec"
            $table->string('rest_time'); // e.g. "20 sec"
            $table->string('recovery_time'); // e.g. "90 sec"
            $table->unsignedTinyInteger('sort_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_workout_rules');
    }
};
