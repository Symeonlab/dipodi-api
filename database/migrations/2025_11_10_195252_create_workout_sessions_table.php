<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('day'); // e.g., "LUNDI", "MARDI"
            $table->string('theme'); // "FORCE MAX", "ENDURANCE"
            $table->text('warmup')->nullable();
            $table->text('finisher')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('workout_sessions'); }
};
