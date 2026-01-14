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
        Schema::create('onboarding_options', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // e.g., 'discipline', 'goal', 'level', 'location', 'injury_location'
            $table->string('key')->unique(); // e.g., 'FOOTBALL', 'goal.lose_weight', 'SI_DEHORS'
            $table->string('name_en');
            $table->string('name_fr');
            $table->string('name_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_options');
    }
};
