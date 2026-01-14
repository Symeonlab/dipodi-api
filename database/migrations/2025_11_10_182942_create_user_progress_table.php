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
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->float('weight')->nullable();
            $table->string('mood')->nullable(); // e.g., "Good", "Tired"
            $table->text('notes')->nullable();
            $table->string('workout_completed')->nullable(); // e.g., "SPRINT Tapis"
            $table->timestamps();

            $table->unique(['user_id', 'date']); // One entry per user per day
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
