<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "QUADRICEPS", "Biceps curl assis rotation"
            $table->string('category'); // e.g., "KINE RENFORCEMENT", "MUSCULATION", "CARDIO"
            $table->string('sub_category')->nullable(); // e.g., "PIEDS", "BRAS", "TAPIS PUISSANCE"
            $table->text('video_url')->nullable(); // The YouTube link [cite: 1248]
            $table->text('description')->nullable(); // For any extra notes
            $table->float('met_value')->nullable(); // MET value from PDF [cite: 145, 155, 232]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
