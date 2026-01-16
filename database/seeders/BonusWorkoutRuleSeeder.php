<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BonusWorkoutRule;

class BonusWorkoutRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            // =============================================
            // ABDOS RULES
            // =============================================
            // DÉBUTANT
            [
                'level' => 'DÉBUTANT',
                'type' => 'ABDOS',
                'sets' => '3',
                'reps' => '10-20',
                'recovery' => '45 sec',
                'duration' => '12 MIN',
                'exercise_count' => '3-4'
            ],
            // INTERMÉDIAIRE
            [
                'level' => 'INTERMÉDIAIRE',
                'type' => 'ABDOS',
                'sets' => '4',
                'reps' => '20-40',
                'recovery' => '30 sec',
                'duration' => '20 MIN',
                'exercise_count' => '4-5'
            ],
            // AVANCÉ
            [
                'level' => 'AVANCÉ',
                'type' => 'ABDOS',
                'sets' => '5',
                'reps' => '20-60 sec',
                'recovery' => '20 sec',
                'duration' => '28 MIN',
                'exercise_count' => '5-6'
            ],

            // =============================================
            // GAINAGE RULES
            // =============================================
            // DÉBUTANT
            [
                'level' => 'DÉBUTANT',
                'type' => 'GAINAGE',
                'sets' => '3',
                'reps' => '20-30 sec',
                'recovery' => '45 sec',
                'duration' => '10 MIN',
                'exercise_count' => '3-4'
            ],
            // INTERMÉDIAIRE
            [
                'level' => 'INTERMÉDIAIRE',
                'type' => 'GAINAGE',
                'sets' => '4',
                'reps' => '30-45 sec',
                'recovery' => '30 sec',
                'duration' => '18 MIN',
                'exercise_count' => '4-5'
            ],
            // AVANCÉ
            [
                'level' => 'AVANCÉ',
                'type' => 'GAINAGE',
                'sets' => '5',
                'reps' => '45-60 sec',
                'recovery' => '20 sec',
                'duration' => '25 MIN',
                'exercise_count' => '5-6'
            ],

            // =============================================
            // POMPES RULES
            // =============================================
            // DÉBUTANT
            [
                'level' => 'DÉBUTANT',
                'type' => 'POMPES',
                'sets' => '3',
                'reps' => '8-12',
                'recovery' => '60 sec',
                'duration' => '12 MIN',
                'exercise_count' => '3-4'
            ],
            // INTERMÉDIAIRE
            [
                'level' => 'INTERMÉDIAIRE',
                'type' => 'POMPES',
                'sets' => '4',
                'reps' => '10-12',
                'recovery' => '45 sec',
                'duration' => '20 MIN',
                'exercise_count' => '4-5'
            ],
            // AVANCÉ
            [
                'level' => 'AVANCÉ',
                'type' => 'POMPES',
                'sets' => '5',
                'reps' => '10-15',
                'recovery' => '30 sec',
                'duration' => '30 MIN',
                'exercise_count' => '5-6'
            ],

            // =============================================
            // COMBINED WORKOUT RULES
            // =============================================
            [
                'level' => 'ALL',
                'type' => 'GAINAGE + ABDOS',
                'sets' => '2-4',
                'reps' => 'variable',
                'recovery' => 'variable',
                'duration' => 'variable',
                'exercise_count' => '2-4 GAINAGE + 2-4 ABDOS'
            ],
            [
                'level' => 'ALL',
                'type' => 'GAINAGE + POMPES',
                'sets' => '2-4',
                'reps' => 'variable',
                'recovery' => 'variable',
                'duration' => 'variable',
                'exercise_count' => '2-4 GAINAGE + 2-4 POMPES'
            ],
            [
                'level' => 'ALL',
                'type' => 'POMPES + ABDOS',
                'sets' => '2-4',
                'reps' => 'variable',
                'recovery' => 'variable',
                'duration' => 'variable',
                'exercise_count' => '2-4 POMPES + 2-4 ABDOS'
            ],
            [
                'level' => 'ALL',
                'type' => 'POMPES + GAINAGE',
                'sets' => '2-4',
                'reps' => 'variable',
                'recovery' => 'variable',
                'duration' => 'variable',
                'exercise_count' => '2-4 POMPES + 2-4 GAINAGE'
            ],
            [
                'level' => 'ALL',
                'type' => 'ABDOS + GAINAGE',
                'sets' => '2-4',
                'reps' => 'variable',
                'recovery' => 'variable',
                'duration' => 'variable',
                'exercise_count' => '2-4 ABDOS + 2-4 GAINAGE'
            ],
            [
                'level' => 'ALL',
                'type' => 'ABDOS + POMPES',
                'sets' => '2-4',
                'reps' => 'variable',
                'recovery' => 'variable',
                'duration' => 'variable',
                'exercise_count' => '2-4 ABDOS + 2-4 POMPES'
            ],
        ];

        foreach ($rules as $rule) {
            BonusWorkoutRule::create($rule);
        }
    }
}
