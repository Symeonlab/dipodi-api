<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\BonusWorkoutRule;

class BonusWorkoutRuleSeeder extends Seeder
{
    public function run(): void
    {

        $rules = [
            // DÉBUTANT (p. 16)
            ['level' => 'DÉBUTANT', 'type' => 'ABDOS', 'sets' => '2-3', 'reps' => '10-15', 'recovery' => '20-30 sec'],
            ['level' => 'DÉBUTANT', 'type' => 'POMPES', 'sets' => '4-5', 'reps' => '5-8', 'recovery' => '30-55 sec'],
            ['level' => 'DÉBUTANT', 'type' => 'GAINAGE', 'sets' => '4-5', 'reps' => '10-15 sec', 'recovery' => '20-55 sec'],

            // INTERMÉDIAIRE (p. 17)
            ['level' => 'INTERMÉDIAIRE', 'type' => 'ABDOS', 'sets' => '3-5', 'reps' => '15-25', 'recovery' => '15-25 sec'],
            ['level' => 'INTERMÉDIAIRE', 'type' => 'POMPES', 'sets' => '4-6', 'reps' => '12-16', 'recovery' => '30-45 sec'],
            ['level' => 'INTERMÉDIAIRE', 'type' => 'GAINAGE', 'sets' => '5-7', 'reps' => '20-35 sec', 'recovery' => '15-25 sec'],

            // AVANCÉ (p. 17)
            ['level' => 'AVANCÉ', 'type' => 'ABDOS', 'sets' => '4-6', 'reps' => '25-32', 'recovery' => '12-22 sec'],
            ['level' => 'AVANCÉ', 'type' => 'POMPES', 'sets' => '5-8', 'reps' => '15-25', 'recovery' => '40-55 sec'],
            ['level' => 'AVANCÉ', 'type' => 'GAINAGE', 'sets' => '6-7', 'reps' => '55s - 1m 30s', 'recovery' => '10-23 sec'],
        ];

        foreach ($rules as $rule) {
            BonusWorkoutRule::create($rule);
        }
    }
}
