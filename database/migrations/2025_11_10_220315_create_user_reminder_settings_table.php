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
        Schema::create('user_reminder_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->boolean('breakfast_enabled')->default(true);
            $table->time('breakfast_time')->default('08:00:00');
            $table->boolean('lunch_enabled')->default(true);
            $table->time('lunch_time')->default('12:00:00');
            $table->boolean('dinner_enabled')->default(true);
            $table->time('dinner_time')->default('19:00:00');
            $table->boolean('workout_enabled')->default(true);
            $table->time('workout_time')->default('17:00:00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reminder_settings');
    }
};
