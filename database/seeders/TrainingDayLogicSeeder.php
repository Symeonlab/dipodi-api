<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingDayLogic;

class TrainingDayLogicSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'total_days' => 1,
                'theme_principal_count' => 1,
                'random_count' => 0,
                'alt_theme_count' => null,
                'alt_random_count' => null,
            ],
            [
                'total_days' => 2,
                'theme_principal_count' => 2,
                'random_count' => 0,
                'alt_theme_count' => null,
                'alt_random_count' => null,
            ],
            [
                'total_days' => 3,
                'theme_principal_count' => 2,
                'random_count' => 1,
                'alt_theme_count' => null,
                'alt_random_count' => null,
            ],
            [
                'total_days' => 4,
                'theme_principal_count' => 2,
                'random_count' => 2,
                'alt_theme_count' => 3,
                'alt_random_count' => 1,
            ],
            [
                'total_days' => 5,
                'theme_principal_count' => 3,
                'random_count' => 2,
                'alt_theme_count' => 4,
                'alt_random_count' => 1,
            ],
            [
                'total_days' => 6,
                'theme_principal_count' => 3,
                'random_count' => 3,
                'alt_theme_count' => 4,
                'alt_random_count' => 2,
            ],
            [
                'total_days' => 7,
                'theme_principal_count' => 4,
                'random_count' => 3,
                'alt_theme_count' => 5,
                'alt_random_count' => 2,
            ],
        ];

        foreach ($rows as $row) {
            TrainingDayLogic::updateOrCreate(
                ['total_days' => $row['total_days']],
                $row
            );
        }
    }
}
