<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlayerProfile;
use App\Models\WorkoutTheme;
use Illuminate\Support\Facades\DB;

class PlayerProfileSeeder extends Seeder
{
    public function run(): void
    {

        // 1. Create all themes from PDF
        $themes = [
            // Gym
            'ENDURANCE DE FORCE' => 'gym', 'FORCE MAX' => 'gym', 'MASSE MUSCULAIRE' => 'gym',
            'PERTE DE POIDS' => 'gym', 'REMISE EN FORME' => 'gym', 'RÉPÉTITIONS DES EFFORTS' => 'gym',
            'FORCE EXPLOSIVE' => 'gym',
            // Cardio
            'PUISSANCE' => 'cardio', 'ENDURANCE' => 'cardio', 'RÉSISTANCE' => 'cardio', 'SPRINT' => 'cardio',
            // Outside
            'CARDIO' => 'outside', 'BOX TO BOX' => 'outside',
            // Home
            'RENFORCEMENT' => 'home', 'BRÛLER DES CALORIES' => 'home'
        ];

        foreach ($themes as $name => $type) {
            WorkoutTheme::create(['name' => $name, 'type' => $type]);
        }

        // 2. Create all profiles from WorkoutGenerator.swift
        $profiles = [
            'GARDIENS' => [
                'PANTHERE (PUISSANT)' => [
                    'gym' => ['ENDURANCE DE FORCE' => 35, 'FORCE MAX' => 35, 'MASSE MUSCULAIRE' => 30, 'RÉPÉTITIONS DES EFFORTS' => 25, 'FORCE EXPLOSIVE' => 40],
                    'cardio' => ['PUISSANCE' => 40, 'ENDURANCE' => 20, 'RÉSISTANCE' => 20, 'SPRINT' => 50],
                    'outside' => ['CARDIO' => 40, 'PUISSANCE' => 50, 'REMISE EN FORME' => 20, 'BOX TO BOX' => 20],
                ],
                'PIEUVRE (HABILE)' => [
                    'gym' => ['ENDURANCE DE FORCE' => 40, 'FORCE MAX' => 25, 'MASSE MUSCULAIRE' => 30, 'RÉPÉTITIONS DES EFFORTS' => 40, 'FORCE EXPLOSIVE' => 25],
                    'cardio' => ['PUISSANCE' => 50, 'ENDURANCE' => 40, 'RÉSISTANCE' => 60, 'SPRINT' => 30],
                ],
                // ... Add ARAIGNEE and CHAT here ...
            ],
            'DÉFENSEURS' => [
                'CASSEUR (DURE)' => [
                    'gym' => ['ENDURANCE DE FORCE' => 35, 'FORCE MAX' => 45, 'MASSE MUSCULAIRE' => 45, 'RÉPÉTITIONS DES EFFORTS' => 35, 'FORCE EXPLOSIVE' => 54],
                    'cardio' => ['PUISSANCE' => 50, 'ENDURANCE' => 45, 'RÉSISTANCE' => 45, 'SPRINT' => 40],
                ],
                // ... Add CONTROLEUR, POLYVALENT, RELANCEUR here ...
            ],
            'MILIEUX' => [
                'ARCHITECTE (CONSTRUCTION)' => [
                    'gym' => ['ENDURANCE DE FORCE' => 30, 'FORCE MAX' => 25, 'MASSE MUSCULAIRE' => 20, 'RÉPÉTITIONS DES EFFORTS' => 30, 'FORCE EXPLOSIVE' => 35],
                    'cardio' => ['PUISSANCE' => 35, 'ENDURANCE' => 50, 'RÉSISTANCE' => 50, 'SPRINT' => 30],
                ],
                // ... Add GAZELLE, PITBULL, ROCK here ...
            ],
            'ATTAQUANTS' => [
                'MAGICIEN (TALENTUEUX)' => [
                    'gym' => ['ENDURANCE DE FORCE' => 35, 'FORCE MAX' => 35, 'MASSE MUSCULAIRE' => 35, 'RÉPÉTITIONS DES EFFORTS' => 35, 'FORCE EXPLOSIVE' => 35],
                    'cardio' => ['PUISSANCE' => 50, 'ENDURANCE' => 50, 'RÉSISTANCE' => 40, 'SPRINT' => 40],
                ],
                // ... Add RENARD, SNIPER, TANK here ...
            ]
        ];

        // 3. Link them all together
        foreach ($profiles as $group => $playerProfiles) {
            foreach ($playerProfiles as $profileName => $themeTypes) {
                $dbProfile = PlayerProfile::create(['name' => $profileName, 'group' => $group]);

                foreach ($themeTypes as $type => $themeList) {
                    foreach ($themeList as $themeName => $percentage) {
                        $dbTheme = WorkoutTheme::where('name', $themeName)->where('type', $type)->first();
                        if ($dbTheme) {
                            DB::table('player_profile_themes')->insert([
                                'player_profile_id' => $dbProfile->id,
                                'workout_theme_id' => $dbTheme->id,
                                'percentage' => $percentage
                            ]);
                        }
                    }
                }
            }
        }
    }
}
