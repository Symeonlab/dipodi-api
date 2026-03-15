<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workout_themes', function (Blueprint $table) {
            $table->string('discipline')->nullable()->after('type'); // football, padel, fitness_women, fitness_men
            $table->string('zone_color')->nullable()->after('discipline'); // blue, green, yellow, orange, red
            $table->string('quality_method')->nullable()->after('zone_color'); // e.g. "Force maximale"
            $table->string('display_name')->nullable()->after('quality_method'); // e.g. "Le Blindage"
            $table->integer('sort_order')->default(0)->after('display_name');
        });
    }

    public function down(): void
    {
        Schema::table('workout_themes', function (Blueprint $table) {
            $table->dropColumn(['discipline', 'zone_color', 'quality_method', 'display_name', 'sort_order']);
        });
    }
};
