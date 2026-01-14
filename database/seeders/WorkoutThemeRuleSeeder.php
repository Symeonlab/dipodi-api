<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\WorkoutTheme;
use App\Models\WorkoutThemeRule;

class WorkoutThemeRuleSeeder extends Seeder
{
    public function run(): void
    {

        $themes = WorkoutTheme::all()->keyBy('name');

        // Data from "ALGO SPORT & NUTRITIONS DOSSIER.pdf"
        $rules = [
            // MUSCULATION EN SALLE (p. 6-8)
            'ENDURANCE DE FORCE' => ['exercise_count' => '4-8', 'sets' => '2-5', 'reps' => '25-35', 'recovery_time' => '30 sec - 1 min', 'load_type' => 'légères'],
            'FORCE MAX' => ['exercise_count' => '6-7', 'sets' => '2-5', 'reps' => '2-6', 'recovery_time' => '3-5 min', 'load_type' => 'très lourdes'],
            'MASSE MUSCULAIRE' => ['exercise_count' => '4-6', 'sets' => '8-12', 'reps' => '8-10', 'recovery_time' => '1-2 min', 'load_type' => 'moyennes'],
            'PERTE DE POIDS' => ['exercise_count' => '6-7', 'sets' => '6-12', 'reps' => '13-18', 'recovery_time' => '10-30 sec', 'load_type' => 'légères'],
            'REMISE EN FORME' => ['exercise_count' => '6-7', 'sets' => '4-7', 'reps' => '8-12', 'recovery_time' => '3-4 min', 'load_type' => 'moyennes'],
            'RÉPÉTITIONS DES EFFORTS' => ['exercise_count' => '6-8', 'sets' => '5-8', 'reps' => '7-15 sec efforts', 'recovery_time' => '10-20 sec', 'load_type' => 'moyennes'],
            'FORCE EXPLOSIVE' => ['exercise_count' => '6-7', 'sets' => '2-7', 'reps' => '2-7', 'recovery_time' => '2-3 min', 'load_type' => 'lourdes'],

            // CARDIO EN SALLE (p. 8-11)
            'PUISSANCE' => ['exercise_count' => '1-3 séries', 'sets' => '1-3', 'reps' => '5-45 sec efforts', 'recovery_time' => '10-30 sec', 'load_type' => 'bonne allure'],
            'ENDURANCE' => ['exercise_count' => '1 série (longue)', 'sets' => '1', 'reps' => '12-20 min', 'recovery_time' => '1-2 min active', 'load_type' => 'allure normale'],
            'RÉSISTANCE' => ['exercise_count' => '2-11 séries', 'sets' => '2-11', 'reps' => '45 sec - 5 min efforts', 'recovery_time' => '1-3 min active', 'load_type' => 'bonne allure'],
            'SPRINT' => ['exercise_count' => '2-4 séries', 'sets' => '2-4', 'reps' => '4 sprints (7-11s)', 'recovery_time' => '45s - 1m 20s', 'load_type' => 'vitesse max'],

            // MAISON (p. 13)
            'RENFORCEMENT' => ['exercise_count' => '5', 'sets' => '5-8 tours', 'reps' => '20-30 sec', 'recovery_time' => '10-20 sec', 'load_type' => 'poids de corps'],
            'BRÛLER DES CALORIES' => ['exercise_count' => '5', 'sets' => '5-8 tours', 'reps' => '20-30 sec', 'recovery_time' => '10-20 sec', 'load_type' => 'poids de corps'],

            // DEHORS (p. 14-16) - Note: 'CARDIO' is a sub-theme here
            'CARDIO' => ['exercise_count' => '1-3 séries', 'sets' => '1-3', 'reps' => '30 sec - 4 min efforts', 'recovery_time' => '30s - 3 min', 'load_type' => 'grosse allure'],
            'BOX TO BOX' => ['exercise_count' => '1-3 séries', 'sets' => '1-3', 'reps' => '10 sec - 4 min efforts', 'recovery_time' => '10s - 3 min', 'load_type' => 'grosse allure'],
        ];

        foreach ($rules as $themeName => $rule) {
            if (isset($themes[$themeName])) {
                WorkoutThemeRule::create(array_merge(
                    ['workout_theme_id' => $themes[$themeName]->id],
                    $rule
                ));
            }
        }
    }
}
