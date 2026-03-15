<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomeWorkoutRule;
use App\Models\PlayerProfile;

class HomeWorkoutRuleSeeder extends Seeder
{
    public function run(): void
    {
        $sortOrder = 0;

        // =============================================
        // PERTE DE POIDS (Weight Loss)
        // =============================================

        // --- FITNESS HOMME ---
        $fitnessHommeWeightLoss = [
            'L\'Athlétique'   => ['duration' => '35-45 min', 'exercise_count' => '8-10',  'circuits' => '4-5', 'effort_time' => '40 sec', 'rest_time' => '20 sec', 'recovery_time' => '90 sec'],
            'Le Massif'       => ['duration' => '40-50 min', 'exercise_count' => '6-8',   'circuits' => '3-4', 'effort_time' => '30 sec', 'rest_time' => '30 sec', 'recovery_time' => '120 sec'],
            'Le Sec'          => ['duration' => '30-40 min', 'exercise_count' => '10-12',  'circuits' => '5-6', 'effort_time' => '45 sec', 'rest_time' => '15 sec', 'recovery_time' => '60 sec'],
            'Le Fonctionnel'  => ['duration' => '35-45 min', 'exercise_count' => '8-10',  'circuits' => '4-5', 'effort_time' => '40 sec', 'rest_time' => '20 sec', 'recovery_time' => '90 sec'],
            'Le Force Brute'  => ['duration' => '40-50 min', 'exercise_count' => '6-8',   'circuits' => '3-4', 'effort_time' => '30 sec', 'rest_time' => '30 sec', 'recovery_time' => '120 sec'],
        ];

        foreach ($fitnessHommeWeightLoss as $name => $params) {
            $this->createRule($name, 'perte_de_poids', $params, ++$sortOrder);
        }

        // --- FITNESS FEMME ---
        $fitnessFemmeWeightLoss = [
            'La Silhouette'        => ['duration' => '35-45 min', 'exercise_count' => '8-10', 'circuits' => '4-5', 'effort_time' => '40 sec', 'rest_time' => '20 sec', 'recovery_time' => '90 sec'],
            'La Tonique'           => ['duration' => '40-50 min', 'exercise_count' => '7-9',  'circuits' => '4-5', 'effort_time' => '35 sec', 'rest_time' => '25 sec', 'recovery_time' => '90 sec'],
            'La Fine'              => ['duration' => '30-40 min', 'exercise_count' => '10-12', 'circuits' => '3-4', 'effort_time' => '45 sec', 'rest_time' => '15 sec', 'recovery_time' => '60 sec'],
            'L\'Athlète Puissante' => ['duration' => '40-50 min', 'exercise_count' => '6-8',  'circuits' => '4-5', 'effort_time' => '30 sec', 'rest_time' => '30 sec', 'recovery_time' => '120 sec'],
            'Bien-être'            => ['duration' => '25-35 min', 'exercise_count' => '6-8',  'circuits' => '3-4', 'effort_time' => '30 sec', 'rest_time' => '30 sec', 'recovery_time' => '90 sec'],
        ];

        foreach ($fitnessFemmeWeightLoss as $name => $params) {
            $this->createRule($name, 'perte_de_poids', $params, ++$sortOrder);
        }

        // --- FOOTBALL GARDIEN ---
        $gardienParams = ['duration' => '30-40 min', 'exercise_count' => '6-8', 'circuits' => '5-6', 'effort_time' => '30 sec', 'rest_time' => '30 sec', 'recovery_time' => '60 sec'];
        foreach (['La Panthère', 'La Pieuvre', 'Le Chat', 'L\'Araignée'] as $name) {
            $this->createRule($name, 'perte_de_poids', $gardienParams, ++$sortOrder);
        }

        // --- FOOTBALL DÉFENSEUR ---
        $defenseurParams = ['duration' => '45-55 min', 'exercise_count' => '8-10', 'circuits' => '4-5', 'effort_time' => '40 sec', 'rest_time' => '20 sec', 'recovery_time' => '90 sec'];
        foreach (['Le Contrôleur', 'Le Casseur', 'Le Relanceur', 'Le Polyvalent'] as $name) {
            $this->createRule($name, 'perte_de_poids', $defenseurParams, ++$sortOrder);
        }

        // --- FOOTBALL MILIEU ---
        $milieuParams = ['duration' => '55-70 min', 'exercise_count' => '10-12', 'circuits' => '5-6', 'effort_time' => '45 sec', 'rest_time' => '15 sec', 'recovery_time' => '60 sec'];
        foreach (['L\'Architecte', 'The Rock', 'Le Pitbull', 'La Gazelle'] as $name) {
            $this->createRule($name, 'perte_de_poids', $milieuParams, ++$sortOrder);
        }

        // --- FOOTBALL ATTAQUANT ---
        $attaquantParams = ['duration' => '40-50 min', 'exercise_count' => '8-10', 'circuits' => '5-6', 'effort_time' => '35 sec', 'rest_time' => '25 sec', 'recovery_time' => '75 sec'];
        foreach (['Le Magicien', 'Le Sniper', 'Le Tank', 'Le Renard'] as $name) {
            $this->createRule($name, 'perte_de_poids', $attaquantParams, ++$sortOrder);
        }

        // --- PADEL (all profiles) ---
        $padelWeightLossParams = ['duration' => '35-45 min', 'exercise_count' => '8-10', 'circuits' => '4-5', 'effort_time' => '40 sec', 'rest_time' => '20 sec', 'recovery_time' => '90 sec'];
        foreach (['Le Métronome', 'Le Marathonien', 'Le Stressé', 'Le Smasheur', 'L\'Aérien', 'Le Joueur Lourd', 'Le Défenseur', 'Le Fragile', 'Le Vétéran', 'Le Matinal'] as $name) {
            $this->createRule($name, 'perte_de_poids', $padelWeightLossParams, ++$sortOrder);
        }

        // =============================================
        // RENFORCEMENT (Strengthening)
        // =============================================

        // --- FITNESS HOMME ---
        $fitnessHommeRenforcement = [
            'L\'Athlétique'   => ['duration' => '40-50 min', 'exercise_count' => '6-8',  'circuits' => '4-5', 'effort_time' => '45 sec', 'rest_time' => '30 sec', 'recovery_time' => '120 sec'],
            'Le Massif'       => ['duration' => '45-55 min', 'exercise_count' => '5-7',  'circuits' => '3-4', 'effort_time' => '40 sec', 'rest_time' => '40 sec', 'recovery_time' => '150 sec'],
            'Le Sec'          => ['duration' => '35-45 min', 'exercise_count' => '8-10', 'circuits' => '5-6', 'effort_time' => '50 sec', 'rest_time' => '20 sec', 'recovery_time' => '90 sec'],
            'Le Fonctionnel'  => ['duration' => '40-50 min', 'exercise_count' => '7-9',  'circuits' => '4-5', 'effort_time' => '45 sec', 'rest_time' => '25 sec', 'recovery_time' => '105 sec'],
            'Le Force Brute'  => ['duration' => '45-55 min', 'exercise_count' => '5-7',  'circuits' => '3-4', 'effort_time' => '40 sec', 'rest_time' => '40 sec', 'recovery_time' => '150 sec'],
        ];

        foreach ($fitnessHommeRenforcement as $name => $params) {
            $this->createRule($name, 'renforcement', $params, ++$sortOrder);
        }

        // --- FITNESS FEMME ---
        $fitnessFemmeRenforcement = [
            'La Silhouette'        => ['duration' => '35-45 min', 'exercise_count' => '6-8',  'circuits' => '4-5', 'effort_time' => '40 sec', 'rest_time' => '30 sec', 'recovery_time' => '105 sec'],
            'La Tonique'           => ['duration' => '40-50 min', 'exercise_count' => '6-8',  'circuits' => '4-5', 'effort_time' => '40 sec', 'rest_time' => '30 sec', 'recovery_time' => '105 sec'],
            'La Fine'              => ['duration' => '30-40 min', 'exercise_count' => '8-10', 'circuits' => '3-4', 'effort_time' => '50 sec', 'rest_time' => '20 sec', 'recovery_time' => '90 sec'],
            'L\'Athlète Puissante' => ['duration' => '40-50 min', 'exercise_count' => '5-7',  'circuits' => '4-5', 'effort_time' => '35 sec', 'rest_time' => '35 sec', 'recovery_time' => '120 sec'],
            'Bien-être'            => ['duration' => '25-35 min', 'exercise_count' => '5-7',  'circuits' => '3-4', 'effort_time' => '35 sec', 'rest_time' => '35 sec', 'recovery_time' => '105 sec'],
        ];

        foreach ($fitnessFemmeRenforcement as $name => $params) {
            $this->createRule($name, 'renforcement', $params, ++$sortOrder);
        }

        // --- FOOTBALL GARDIEN ---
        $gardienRenforcementParams = ['duration' => '35-45 min', 'exercise_count' => '5-7', 'circuits' => '5-6', 'effort_time' => '35 sec', 'rest_time' => '35 sec', 'recovery_time' => '90 sec'];
        foreach (['La Panthère', 'La Pieuvre', 'Le Chat', 'L\'Araignée'] as $name) {
            $this->createRule($name, 'renforcement', $gardienRenforcementParams, ++$sortOrder);
        }

        // --- FOOTBALL DÉFENSEUR ---
        $defenseurRenforcementParams = ['duration' => '50-60 min', 'exercise_count' => '7-9', 'circuits' => '4-5', 'effort_time' => '45 sec', 'rest_time' => '25 sec', 'recovery_time' => '105 sec'];
        foreach (['Le Contrôleur', 'Le Casseur', 'Le Relanceur', 'Le Polyvalent'] as $name) {
            $this->createRule($name, 'renforcement', $defenseurRenforcementParams, ++$sortOrder);
        }

        // --- FOOTBALL MILIEU ---
        $milieuRenforcementParams = ['duration' => '55-70 min', 'exercise_count' => '8-10', 'circuits' => '5-6', 'effort_time' => '50 sec', 'rest_time' => '20 sec', 'recovery_time' => '90 sec'];
        foreach (['L\'Architecte', 'The Rock', 'Le Pitbull', 'La Gazelle'] as $name) {
            $this->createRule($name, 'renforcement', $milieuRenforcementParams, ++$sortOrder);
        }

        // --- FOOTBALL ATTAQUANT ---
        $attaquantRenforcementParams = ['duration' => '45-55 min', 'exercise_count' => '7-9', 'circuits' => '5-6', 'effort_time' => '40 sec', 'rest_time' => '30 sec', 'recovery_time' => '90 sec'];
        foreach (['Le Magicien', 'Le Sniper', 'Le Tank', 'Le Renard'] as $name) {
            $this->createRule($name, 'renforcement', $attaquantRenforcementParams, ++$sortOrder);
        }

        // --- PADEL (all profiles) ---
        $padelRenforcementParams = ['duration' => '40-50 min', 'exercise_count' => '7-9', 'circuits' => '4-5', 'effort_time' => '45 sec', 'rest_time' => '25 sec', 'recovery_time' => '105 sec'];
        foreach (['Le Métronome', 'Le Marathonien', 'Le Stressé', 'Le Smasheur', 'L\'Aérien', 'Le Joueur Lourd', 'Le Défenseur', 'Le Fragile', 'Le Vétéran', 'Le Matinal'] as $name) {
            $this->createRule($name, 'renforcement', $padelRenforcementParams, ++$sortOrder);
        }
    }

    /**
     * Create a home workout rule for a given profile name and objective.
     * Skips silently if the profile does not exist in the database.
     */
    private function createRule(string $profileName, string $objective, array $params, int $sortOrder): void
    {
        $profileId = PlayerProfile::where('name', $profileName)->first()?->id;

        if (!$profileId) {
            return;
        }

        HomeWorkoutRule::updateOrCreate(
            [
                'player_profile_id' => $profileId,
                'objective' => $objective,
            ],
            array_merge($params, [
                'player_profile_id' => $profileId,
                'objective' => $objective,
                'sort_order' => $sortOrder,
            ])
        );
    }
}
