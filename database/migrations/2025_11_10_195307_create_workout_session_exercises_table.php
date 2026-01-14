<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_session_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_session_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('sets'); // "4"
            $table->string('reps'); // "8-12"
            $table->string('recovery'); // "1 min 30 sec"
            $table->string('video_url')->nullable();
        });
    }
    public function down(): void { Schema::dropIfExists('workout_session_exercises'); }
};
