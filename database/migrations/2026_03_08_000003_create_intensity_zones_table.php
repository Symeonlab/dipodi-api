<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intensity_zones', function (Blueprint $table) {
            $table->id();
            $table->string('color')->unique(); // blue, green, yellow, orange, red
            $table->string('name_fr');
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('intensity_range'); // e.g. "50-60%"
            $table->text('description_fr');
            $table->text('description_en');
            $table->text('description_ar');
            $table->unsignedTinyInteger('rpe_min')->default(1);
            $table->unsignedTinyInteger('rpe_max')->default(10);
            $table->unsignedTinyInteger('sort_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intensity_zones');
    }
};
