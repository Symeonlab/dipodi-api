<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_day_logic', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('total_days')->unique(); // 1-7
            $table->unsignedTinyInteger('theme_principal_count');
            $table->unsignedTinyInteger('random_count');
            $table->unsignedTinyInteger('alt_theme_count')->nullable(); // alternative distribution
            $table->unsignedTinyInteger('alt_random_count')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_day_logic');
    }
};
