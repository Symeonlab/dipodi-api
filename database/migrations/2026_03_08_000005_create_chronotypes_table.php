<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chronotypes', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // lion, bear, wolf, dolphin
            $table->string('name_fr');
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('wake_time'); // e.g. "05h00-06h00"
            $table->string('peak_start'); // e.g. "06h00"
            $table->string('peak_end'); // e.g. "12h00"
            $table->string('bedtime'); // e.g. "22h00"
            $table->text('description_fr');
            $table->text('description_en');
            $table->text('description_ar');
            $table->text('character_fr');
            $table->text('character_en');
            $table->text('character_ar');
            $table->string('icon')->nullable(); // emoji or icon name
            $table->unsignedTinyInteger('sort_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chronotypes');
    }
};
