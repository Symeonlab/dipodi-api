<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_profile_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('workout_theme_id')->constrained()->onDelete('cascade');
            $table->integer('percentage'); // e.g., 35
        });
    }
    public function down(): void { Schema::dropIfExists('player_profile_themes'); }
};
