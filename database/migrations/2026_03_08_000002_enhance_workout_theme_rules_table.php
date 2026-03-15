<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workout_theme_rules', function (Blueprint $table) {
            $table->decimal('mets', 4, 1)->nullable();
            $table->string('duration')->nullable(); // e.g. "90-120 min"
            $table->string('charges')->nullable(); // e.g. "85-100 %"
            $table->string('speed_intensity')->nullable(); // e.g. "Lente-contrôlée"
            $table->string('sleep_requirement')->nullable(); // e.g. "9h"
            $table->string('hydration')->nullable(); // e.g. "1.00L"
            $table->decimal('freshness_24h', 3, 2)->nullable(); // e.g. 0.30
            $table->decimal('freshness_48h', 3, 2)->nullable(); // e.g. 0.60
            $table->decimal('freshness_72h', 3, 2)->nullable(); // e.g. 0.95
            $table->unsignedTinyInteger('rpe')->nullable(); // 1-10
            $table->unsignedInteger('load_ua')->nullable(); // e.g. 540
            $table->unsignedTinyInteger('impact')->nullable(); // 1-5
            $table->string('daily_alert_threshold')->nullable(); // e.g. "600 u.a."
            $table->string('weekly_alert_threshold')->nullable(); // e.g. "1200 u.a."
            $table->string('elastic_recoil')->nullable(); // e.g. "Diminue"
            $table->string('cfa')->nullable(); // e.g. "Moyen"
            $table->string('supercomp_window')->nullable(); // e.g. "48h"
            $table->string('gain_prediction')->nullable(); // e.g. "Explosivité & Réaction"
            $table->string('injury_risk')->nullable(); // e.g. "Très Élevé"
            $table->json('target_profiles')->nullable(); // profile names
        });
    }

    public function down(): void
    {
        Schema::table('workout_theme_rules', function (Blueprint $table) {
            $table->dropColumn([
                'mets', 'duration', 'charges', 'speed_intensity',
                'sleep_requirement', 'hydration',
                'freshness_24h', 'freshness_48h', 'freshness_72h',
                'rpe', 'load_ua', 'impact',
                'daily_alert_threshold', 'weekly_alert_threshold',
                'elastic_recoil', 'cfa',
                'supercomp_window', 'gain_prediction', 'injury_risk',
                'target_profiles',
            ]);
        });
    }
};
