<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkoutTheme;
use App\Models\WorkoutThemeRule;
use App\Models\PlayerProfile;

/**
 * CORRECTION SEEDER - Fixes all misalignments found during Excel cross-reference audit.
 *
 * Issues fixed:
 * 1. 10 gym themes had wrong display_names (shuffled during enhancement seeder)
 * 2. 3 gym themes had wrong zone_colors
 * 3. Theme ID 16 had wrong name ("Renforcement tendineux" -> "Renforcement articulaire")
 * 4. Missing theme "Repetitions des efforts / Enchainement d'Actions" (yellow zone)
 * 5. Rule data (RPE, METs, freshness, supercomp, gain, injury) was mismatched due to display_name shuffle
 * 6. discipline column was NULL on all themes
 * 7. 24 missing cardio themes from Excel
 * 8. Unmapped gym themes (Renforcement articulaire, Reathlétisation)
 */
class DipoddiProgrammeCorrectionSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Starting DIPODDI Programme Correction...');

        $this->fixGymThemes();
        $this->addMissingGymTheme();
        $this->addMissingCardioThemes();
        $this->populateDisciplineColumn();
        $this->fixUnmappedThemes();

        $this->command->info('DIPODDI Programme Correction complete!');
    }

    /**
     * Fix all gym theme display_names, zone_colors, names, and rule data.
     * Data source: DIPODDI PROGRAMME-2.xlsx, sheet "MUSCULATION EN SALLE"
     */
    private function fixGymThemes(): void
    {
        $this->command->info('Fixing gym theme display_names, zones, and rules...');

        // Complete correct mapping from Excel: [db_name => [display_name, zone_color, quality_method, rule_data]]
        $corrections = [
            'Force maximale' => [
                'display_name' => 'Le Blindage',
                'zone_color' => 'red',
                'quality_method' => 'Force maximale',
                'sort_order' => 1,
                'rule' => [
                    'mets' => 7.0, 'duration' => '90-120 min', 'sets' => '4-6', 'reps' => '1-5',
                    'recovery_time' => '3-5 min', 'charges' => '85-100 %', 'speed_intensity' => 'Lente - controlee',
                    'exercise_count' => '3 a 4', 'load_type' => 'charges lourdes',
                    'sleep_requirement' => '9h', 'hydration' => '1.00L',
                    'freshness_24h' => 0.30, 'freshness_48h' => 0.60, 'freshness_72h' => 0.95,
                    'rpe' => 9, 'load_ua' => 540, 'impact' => 3,
                    'daily_alert_threshold' => '600 u.a.', 'weekly_alert_threshold' => '1200 u.a.',
                    'elastic_recoil' => 'Diminue', 'cfa' => 'Moyen',
                    'supercomp_window' => '48h', 'gain_prediction' => 'Resistance aux chocs', 'injury_risk' => 'Faible',
                    'target_profiles' => ['La Panthere', "L'Araignee", 'Le Casseur', 'The Rock', 'Le Tank'],
                ],
            ],
            'Force sous-maximale' => [
                'display_name' => "L'Armure Fonctionnelle",
                'zone_color' => 'orange',
                'quality_method' => 'Force sous-maximale',
                'sort_order' => 2,
                'rule' => [
                    'mets' => 6.5, 'duration' => '75-100 min', 'sets' => '4-5', 'reps' => '4-8',
                    'recovery_time' => '2-3 min', 'charges' => '75-85 %', 'speed_intensity' => 'Controlee',
                    'exercise_count' => '4 a 5', 'load_type' => 'charges lourdes',
                    'sleep_requirement' => '8h30', 'hydration' => '1.00L',
                    'freshness_24h' => 0.50, 'freshness_48h' => 0.80, 'freshness_72h' => 1.00,
                    'rpe' => 8, 'load_ua' => 480, 'impact' => 2,
                    'daily_alert_threshold' => '550 u.a.', 'weekly_alert_threshold' => '1500 u.a.',
                    'elastic_recoil' => 'Diminue', 'cfa' => 'Bas',
                    'supercomp_window' => '48h', 'gain_prediction' => 'Puissance utile au duel', 'injury_risk' => 'Moyen',
                    'target_profiles' => ['Le Polyvalent', 'Le Pitbull', "L'Architecte"],
                ],
            ],
            'Force dynamique' => [
                'display_name' => 'Vitesse de Contraction',
                'zone_color' => 'red',
                'quality_method' => 'Force dynamique',
                'sort_order' => 3,
                'rule' => [
                    'mets' => 7.8, 'duration' => '75-100 min', 'sets' => '4-6', 'reps' => '3-6',
                    'recovery_time' => '2-3 min', 'charges' => '60-80 %', 'speed_intensity' => 'Rapide',
                    'exercise_count' => '4 a 6', 'load_type' => 'charges moderees',
                    'sleep_requirement' => '8h30', 'hydration' => '0.75L',
                    'freshness_24h' => 0.60, 'freshness_48h' => 0.90, 'freshness_72h' => 1.00,
                    'rpe' => 8, 'load_ua' => 400, 'impact' => 3,
                    'daily_alert_threshold' => '500 u.a.', 'weekly_alert_threshold' => '1400 u.a.',
                    'elastic_recoil' => 'Boost', 'cfa' => 'Moyen',
                    'supercomp_window' => '48-72h', 'gain_prediction' => 'Vitesse de pied / Vivacite', 'injury_risk' => 'Eleve',
                    'target_profiles' => ['Le Chat', 'Le Relanceur', 'La Gazelle', 'Le Renard'],
                ],
            ],
            'Force explosive' => [
                'display_name' => 'Le Premier Pas (Explo)',
                'zone_color' => 'red',
                'quality_method' => 'Force explosive',
                'sort_order' => 4,
                'rule' => [
                    'mets' => 8.5, 'duration' => '60-90 min', 'sets' => '3-5', 'reps' => '1-5',
                    'recovery_time' => '3-5 min', 'charges' => '30-60 %', 'speed_intensity' => 'Maximale',
                    'exercise_count' => '3 a 4', 'load_type' => 'charges legeres a moderees',
                    'sleep_requirement' => '9h', 'hydration' => '0.75L',
                    'freshness_24h' => 0.70, 'freshness_48h' => 0.95, 'freshness_72h' => 1.00,
                    'rpe' => 9, 'load_ua' => 360, 'impact' => 4,
                    'daily_alert_threshold' => '450 u.a.', 'weekly_alert_threshold' => '1200 u.a.',
                    'elastic_recoil' => 'Max', 'cfa' => 'Critique',
                    'supercomp_window' => '72h', 'gain_prediction' => 'Explosivite & Reaction', 'injury_risk' => 'Tres Eleve',
                    'target_profiles' => ['La Panthere', 'Le Chat', 'Le Sniper', 'Le Renard'],
                ],
            ],
            'Puissance musculaire' => [
                'display_name' => 'Impact & Frappe de Balle',
                'zone_color' => 'red',
                'quality_method' => 'Puissance musculaire',
                'sort_order' => 5,
                'rule' => [
                    'mets' => 8.2, 'duration' => '60-90 min', 'sets' => '3-5', 'reps' => '3-6',
                    'recovery_time' => '2-4 min', 'charges' => '40-70 %', 'speed_intensity' => 'Rapide / explosive',
                    'exercise_count' => '4 a 6', 'load_type' => 'charges moderees',
                    'sleep_requirement' => '9h', 'hydration' => '1.00L',
                    'freshness_24h' => 0.60, 'freshness_48h' => 0.85, 'freshness_72h' => 1.00,
                    'rpe' => 8, 'load_ua' => 400, 'impact' => 4,
                    'daily_alert_threshold' => '500 u.a.', 'weekly_alert_threshold' => '1400 u.a.',
                    'elastic_recoil' => 'Max', 'cfa' => 'Haut',
                    'supercomp_window' => '48h', 'gain_prediction' => 'Force de frappe / Impact', 'injury_risk' => 'Moyen',
                    'target_profiles' => ["L'Araignee", 'The Rock', 'Le Tank'],
                ],
            ],
            'Hypertrophie myofibrillaire' => [
                'display_name' => 'Densite Musculaire',
                'zone_color' => 'orange',
                'quality_method' => 'Hypertrophie Myo.',
                'sort_order' => 6,
                'rule' => [
                    'mets' => 6.2, 'duration' => '75-90 min', 'sets' => '4-6', 'reps' => '4-8',
                    'recovery_time' => '90-120 s', 'charges' => '75-85 %', 'speed_intensity' => 'Controlee',
                    'exercise_count' => '5 a 6', 'load_type' => 'charges lourdes',
                    'sleep_requirement' => '8h30', 'hydration' => '1.25L',
                    'freshness_24h' => 0.45, 'freshness_48h' => 0.75, 'freshness_72h' => 0.95,
                    'rpe' => 8, 'load_ua' => 480, 'impact' => 2,
                    'daily_alert_threshold' => '550 u.a.', 'weekly_alert_threshold' => '1600 u.a.',
                    'elastic_recoil' => 'Neutre', 'cfa' => 'Bas',
                    'supercomp_window' => '72h', 'gain_prediction' => 'Volume & Durete', 'injury_risk' => 'Faible',
                    'target_profiles' => ['Le Casseur', 'The Rock', 'Le Tank'],
                ],
            ],
            'Hypertrophie sarcoplasmique' => [
                'display_name' => 'Protection des Chocs',
                'zone_color' => 'yellow',
                'quality_method' => 'Hypertrophie Sarc.',
                'sort_order' => 7,
                'rule' => [
                    'mets' => 6.0, 'duration' => '60-90 min', 'sets' => '3-5', 'reps' => '8-15',
                    'recovery_time' => '45-90 s', 'charges' => '60-75 %', 'speed_intensity' => 'Continue',
                    'exercise_count' => '6 a 8', 'load_type' => 'charges moderees',
                    'sleep_requirement' => '8h30', 'hydration' => '1.50L',
                    'freshness_24h' => 0.55, 'freshness_48h' => 0.85, 'freshness_72h' => 1.00,
                    'rpe' => 7, 'load_ua' => 420, 'impact' => 2,
                    'daily_alert_threshold' => '500 u.a.', 'weekly_alert_threshold' => '1800 u.a.',
                    'elastic_recoil' => 'Lourdeur', 'cfa' => 'Bas',
                    'supercomp_window' => '24-36h', 'gain_prediction' => 'Encaissement / Solidite', 'injury_risk' => 'Tres Faible',
                    'target_profiles' => ['Le Polyvalent', 'Le Pitbull'],
                ],
            ],
            'Volume musculaire' => [
                'display_name' => 'Gros Gabarit',
                'zone_color' => 'yellow',
                'quality_method' => 'Volume Musculaire',
                'sort_order' => 8,
                'rule' => [
                    'mets' => 6.0, 'duration' => '60-90 min', 'sets' => '3-5', 'reps' => '6-12',
                    'recovery_time' => '60-90 s', 'charges' => '65-80 %', 'speed_intensity' => 'Controlee',
                    'exercise_count' => '6 a 8', 'load_type' => 'charges moderees',
                    'sleep_requirement' => '8h30', 'hydration' => '1.50L',
                    'freshness_24h' => 0.50, 'freshness_48h' => 0.80, 'freshness_72h' => 1.00,
                    'rpe' => 7, 'load_ua' => 525, 'impact' => 2,
                    'daily_alert_threshold' => '600 u.a.', 'weekly_alert_threshold' => '2000 u.a.',
                    'elastic_recoil' => 'Lourdeur', 'cfa' => 'Bas',
                    'supercomp_window' => '96h', 'gain_prediction' => 'Masse / Presence physique', 'injury_risk' => 'Moyen',
                    'target_profiles' => ['Le Casseur', 'Le Tank'],
                ],
            ],
            'Endurance de force' => [
                'display_name' => 'Repetition de Duels',
                'zone_color' => 'orange',
                'quality_method' => 'Endurance de Force',
                'sort_order' => 9,
                'rule' => [
                    'mets' => 5.5, 'duration' => '45-75 min', 'sets' => '2-4', 'reps' => '15-30',
                    'recovery_time' => '30-60 s', 'charges' => '30-60 %', 'speed_intensity' => 'Continue',
                    'exercise_count' => '7 a 9', 'load_type' => 'charges legeres',
                    'sleep_requirement' => '8h30', 'hydration' => '1.25L',
                    'freshness_24h' => 0.65, 'freshness_48h' => 0.95, 'freshness_72h' => 1.00,
                    'rpe' => 7, 'load_ua' => 350, 'impact' => 2,
                    'daily_alert_threshold' => '500 u.a.', 'weekly_alert_threshold' => '1800 u.a.',
                    'elastic_recoil' => 'Neutre', 'cfa' => 'Moyen',
                    'supercomp_window' => '72h', 'gain_prediction' => 'Endurance de Puissance', 'injury_risk' => 'Eleve',
                    'target_profiles' => ['Le Polyvalent', 'Le Pitbull', "L'Architecte"],
                ],
            ],
            'Endurance musculaire' => [
                'display_name' => 'Solidite Posturale',
                'zone_color' => 'green',
                'quality_method' => 'Endurance Muscu.',
                'sort_order' => 10,
                'rule' => [
                    'mets' => 5.0, 'duration' => '45-70 min', 'sets' => '2-4', 'reps' => '20-40',
                    'recovery_time' => '15-45 s', 'charges' => '20-50 %', 'speed_intensity' => 'Moderee',
                    'exercise_count' => '8 a 10', 'load_type' => 'charges legeres',
                    'sleep_requirement' => '8h30', 'hydration' => '1.25L',
                    'freshness_24h' => 0.75, 'freshness_48h' => 1.00, 'freshness_72h' => 1.00,
                    'rpe' => 5, 'load_ua' => 300, 'impact' => 2,
                    'daily_alert_threshold' => '550 u.a.', 'weekly_alert_threshold' => '2200 u.a.',
                    'elastic_recoil' => 'Neutre', 'cfa' => 'Moyen',
                    'supercomp_window' => '24h', 'gain_prediction' => 'Equilibre du buste', 'injury_risk' => 'Nul',
                    'target_profiles' => ['Le Controleur', "L'Architecte"],
                ],
            ],
            'Perte de poids' => [
                'display_name' => 'Affutage Poids/Puissance',
                'zone_color' => 'orange',
                'quality_method' => 'Perte de Poids',
                'sort_order' => 11,
                'rule' => [
                    'mets' => 7.5, 'duration' => '45-75 min', 'sets' => '2-4', 'reps' => '12-20',
                    'recovery_time' => '30-60 s', 'charges' => '40-70 %', 'speed_intensity' => 'Dynamique',
                    'exercise_count' => '8 a 12', 'load_type' => 'charges moderees',
                    'sleep_requirement' => '8h', 'hydration' => '1.50L',
                    'freshness_24h' => 0.60, 'freshness_48h' => 0.90, 'freshness_72h' => 1.00,
                    'rpe' => 7, 'load_ua' => 420, 'impact' => 3,
                    'daily_alert_threshold' => '550 u.a.', 'weekly_alert_threshold' => '2000 u.a.',
                    'elastic_recoil' => 'Neutre', 'cfa' => 'Haut',
                    'supercomp_window' => '36-48h', 'gain_prediction' => 'Rapport poids/puissance', 'injury_risk' => 'Moyen',
                    'target_profiles' => ['Le Tank', 'The Rock'],
                ],
            ],
            'Seche / definition musculaire' => [
                'display_name' => 'Definition du Muscle',
                'zone_color' => 'orange',
                'quality_method' => 'Seche / Definition',
                'sort_order' => 12,
                'rule' => [
                    'mets' => 7.3, 'duration' => '60-90 min', 'sets' => '3-5', 'reps' => '10-20',
                    'recovery_time' => '30-60 s', 'charges' => '50-70 %', 'speed_intensity' => 'Soutenue',
                    'exercise_count' => '7 a 9', 'load_type' => 'charges moderees',
                    'sleep_requirement' => '8h30', 'hydration' => '1.25L',
                    'freshness_24h' => 0.65, 'freshness_48h' => 0.95, 'freshness_72h' => 1.00,
                    'rpe' => 6, 'load_ua' => 360, 'impact' => 2,
                    'daily_alert_threshold' => '500 u.a.', 'weekly_alert_threshold' => '2000 u.a.',
                    'elastic_recoil' => 'Neutre', 'cfa' => 'Moyen',
                    'supercomp_window' => '48h', 'gain_prediction' => 'Qualite visuelle / Tonicite', 'injury_risk' => 'Faible',
                    'target_profiles' => ['Le Renard', 'Le Sniper', 'La Gazelle'],
                ],
            ],
            'Condition physique generale' => [
                'display_name' => 'Socle Athletique',
                'zone_color' => 'orange',
                'quality_method' => 'Condition (GPP)',
                'sort_order' => 13,
                'rule' => [
                    'mets' => 8.0, 'duration' => '45-75 min', 'sets' => '2-4', 'reps' => '10-20',
                    'recovery_time' => '30-60 s', 'charges' => '40-60 %', 'speed_intensity' => 'Dynamique',
                    'exercise_count' => '8 a 10', 'load_type' => 'charges moderees',
                    'sleep_requirement' => '8h', 'hydration' => '1.25L',
                    'freshness_24h' => 0.70, 'freshness_48h' => 0.95, 'freshness_72h' => 1.00,
                    'rpe' => 7, 'load_ua' => 420, 'impact' => 3,
                    'daily_alert_threshold' => '550 u.a.', 'weekly_alert_threshold' => '1800 u.a.',
                    'elastic_recoil' => 'Neutre', 'cfa' => 'Moyen',
                    'supercomp_window' => '48h', 'gain_prediction' => 'Base de force generale', 'injury_risk' => 'Faible',
                    'target_profiles' => ['La Panthere', 'La Pieuvre', 'Le Chat', "L'Araignee", 'Le Controleur', 'Le Casseur', 'Le Relanceur', 'Le Polyvalent', "L'Architecte", 'The Rock', 'Le Pitbull', 'La Gazelle', 'Le Magicien', 'Le Sniper', 'Le Tank', 'Le Renard'],
                ],
            ],
            'Remise en forme' => [
                'display_name' => 'Reprise Douce',
                'zone_color' => 'green',
                'quality_method' => 'Remise en Forme',
                'sort_order' => 14,
                'rule' => [
                    'mets' => 4.5, 'duration' => '30-60 min', 'sets' => '2-3', 'reps' => '10-20',
                    'recovery_time' => '45-90 s', 'charges' => '40-60 %', 'speed_intensity' => 'Confortable',
                    'exercise_count' => '5 a 7', 'load_type' => 'charges legeres',
                    'sleep_requirement' => '7h30', 'hydration' => '1.00L',
                    'freshness_24h' => 0.85, 'freshness_48h' => 1.00, 'freshness_72h' => 1.00,
                    'rpe' => 4, 'load_ua' => 180, 'impact' => 2,
                    'daily_alert_threshold' => '400 u.a.', 'weekly_alert_threshold' => '2500 u.a.',
                    'elastic_recoil' => 'Neutre', 'cfa' => 'Bas',
                    'supercomp_window' => '12h', 'gain_prediction' => 'Remise en route', 'injury_risk' => 'Nul',
                    'target_profiles' => ['La Panthere', 'La Pieuvre', 'Le Chat', "L'Araignee", 'Le Controleur', 'Le Casseur', 'Le Relanceur', 'Le Polyvalent', "L'Architecte", 'The Rock', 'Le Pitbull', 'La Gazelle', 'Le Magicien', 'Le Sniper', 'Le Tank', 'Le Renard'],
                ],
            ],
            'Prevention des blessures' => [
                'display_name' => 'Anti-Blessures',
                'zone_color' => 'blue',
                'quality_method' => 'Prevention',
                'sort_order' => 15,
                'rule' => [
                    'mets' => 3.0, 'duration' => '30-60 min', 'sets' => '2-4', 'reps' => '8-15',
                    'recovery_time' => '60-90 s', 'charges' => '30-50 %', 'speed_intensity' => 'Lente / controlee',
                    'exercise_count' => '5 a 6', 'load_type' => 'charges legeres',
                    'sleep_requirement' => '8h', 'hydration' => '0.75L',
                    'freshness_24h' => 0.90, 'freshness_48h' => 1.00, 'freshness_72h' => 1.00,
                    'rpe' => 3, 'load_ua' => 120, 'impact' => 1,
                    'daily_alert_threshold' => '300 u.a.', 'weekly_alert_threshold' => '3000 u.a.',
                    'elastic_recoil' => 'Protege', 'cfa' => 'Ultra-Bas',
                    'supercomp_window' => '12-24h', 'gain_prediction' => 'Reparation tissulaire', 'injury_risk' => 'Nul',
                    'target_profiles' => ['La Panthere', 'La Pieuvre', 'Le Chat', "L'Araignee", 'Le Controleur', 'Le Casseur'],
                ],
            ],
            // ID 16: Fix name from "Renforcement tendineux" to "Renforcement articulaire"
            'Renforcement tendineux' => [
                'new_name' => 'Renforcement articulaire',
                'display_name' => "Genoux d'Acier",
                'zone_color' => 'blue',
                'quality_method' => 'Renforcement articulaire',
                'sort_order' => 16,
                'rule' => [
                    'mets' => 3.5, 'duration' => '30-60 min', 'sets' => '3-5', 'reps' => '6-12',
                    'recovery_time' => '60-120 s', 'charges' => '40-70 %', 'speed_intensity' => 'Tres controlee',
                    'exercise_count' => '4 a 6', 'load_type' => 'charges moderees',
                    'sleep_requirement' => '8h', 'hydration' => '0.75L',
                    'freshness_24h' => 0.80, 'freshness_48h' => 0.95, 'freshness_72h' => 1.00,
                    'rpe' => 3, 'load_ua' => 135, 'impact' => 1,
                    'daily_alert_threshold' => '300 u.a.', 'weekly_alert_threshold' => '3000 u.a.',
                    'elastic_recoil' => 'Protege', 'cfa' => 'Ultra-Bas',
                    'supercomp_window' => '24h', 'gain_prediction' => 'Stabilite articulaire', 'injury_risk' => 'Nul',
                    'target_profiles' => ['La Pieuvre', 'Le Casseur'],
                ],
            ],
            'Reathlétisation' => [
                'display_name' => 'Retour au Terrain',
                'zone_color' => 'blue',
                'quality_method' => 'Reathlétisation',
                'sort_order' => 17,
                'rule' => [
                    'mets' => 3.8, 'duration' => '30-60 min', 'sets' => '2-4', 'reps' => '8-20',
                    'recovery_time' => '60-120 s', 'charges' => '20-50 %', 'speed_intensity' => 'Progressive',
                    'exercise_count' => '5 a 6', 'load_type' => 'charges legeres',
                    'sleep_requirement' => '8h30', 'hydration' => '0.75L',
                    'freshness_24h' => 0.85, 'freshness_48h' => 1.00, 'freshness_72h' => 1.00,
                    'rpe' => 4, 'load_ua' => 180, 'impact' => 1,
                    'daily_alert_threshold' => '350 u.a.', 'weekly_alert_threshold' => '2500 u.a.',
                    'elastic_recoil' => 'Reset', 'cfa' => 'Ultra-Bas',
                    'supercomp_window' => '36h', 'gain_prediction' => 'Reathlétisation', 'injury_risk' => 'Moyen',
                    'target_profiles' => ['La Panthere', 'La Pieuvre', 'Le Chat', "L'Araignee", 'Le Controleur', 'Le Casseur', 'Le Relanceur', 'Le Polyvalent', "L'Architecte", 'The Rock', 'Le Pitbull', 'La Gazelle', 'Le Magicien', 'Le Sniper', 'Le Tank', 'Le Renard'],
                ],
            ],
            'Coordination / proprioception' => [
                'display_name' => "Equilibre d'Appuis",
                'zone_color' => 'blue',
                'quality_method' => 'Coordination',
                'sort_order' => 18,
                'rule' => [
                    'mets' => 4.0, 'duration' => '30-45 min', 'sets' => '2-4', 'reps' => '6-12',
                    'recovery_time' => '45-90 s', 'charges' => 'Faible', 'speed_intensity' => 'Technique',
                    'exercise_count' => '6 a 8', 'load_type' => 'poids du corps',
                    'sleep_requirement' => '7h30', 'hydration' => '0.50L',
                    'freshness_24h' => 0.95, 'freshness_48h' => 1.00, 'freshness_72h' => 1.00,
                    'rpe' => 3, 'load_ua' => 90, 'impact' => 1,
                    'daily_alert_threshold' => '300 u.a.', 'weekly_alert_threshold' => '3000 u.a.',
                    'elastic_recoil' => 'Eveil', 'cfa' => 'Ultra-Bas',
                    'supercomp_window' => '24h', 'gain_prediction' => 'Precision des appuis', 'injury_risk' => 'Tres Faible',
                    'target_profiles' => ['Le Chat', "L'Architecte", 'Le Magicien'],
                ],
            ],
        ];

        foreach ($corrections as $themeName => $data) {
            $theme = WorkoutTheme::where('name', $themeName)->first();
            if (!$theme) {
                $this->command->warn("Theme not found: {$themeName}");
                continue;
            }

            // Update theme fields
            $updateData = [
                'display_name' => $data['display_name'],
                'zone_color' => $data['zone_color'],
                'quality_method' => $data['quality_method'],
                'sort_order' => $data['sort_order'],
            ];

            // Rename theme if needed
            if (isset($data['new_name'])) {
                $updateData['name'] = $data['new_name'];
            }

            $theme->update($updateData);

            // Update rule data
            if (isset($data['rule'])) {
                $rule = WorkoutThemeRule::where('workout_theme_id', $theme->id)->first();
                if ($rule) {
                    $rule->update($data['rule']);
                } else {
                    WorkoutThemeRule::create(array_merge(
                        ['workout_theme_id' => $theme->id],
                        $data['rule']
                    ));
                }
            }

            $this->command->info("  Fixed: {$themeName} -> {$data['display_name']} ({$data['zone_color']})");
        }
    }

    /**
     * Add the missing "Repetitions des efforts" gym theme (Excel row 39).
     */
    private function addMissingGymTheme(): void
    {
        $this->command->info('Adding missing gym theme: Repetitions des efforts...');

        $existing = WorkoutTheme::where('name', 'Repetitions des efforts')->first();
        if ($existing) {
            $this->command->info('  Already exists, skipping.');
            return;
        }

        $theme = WorkoutTheme::create([
            'name' => 'Repetitions des efforts',
            'type' => 'gym',
            'discipline' => null,
            'zone_color' => 'yellow',
            'quality_method' => 'Repetition Efforts',
            'display_name' => "Enchainement d'Actions",
            'sort_order' => 19,
        ]);

        WorkoutThemeRule::create([
            'workout_theme_id' => $theme->id,
            'exercise_count' => '6 a 8', 'sets' => '2-4', 'reps' => '25-50',
            'recovery_time' => '15-30 s', 'load_type' => 'charges legeres',
            'mets' => 5.8, 'duration' => '45-70 min',
            'charges' => '20-40 %', 'speed_intensity' => 'Soutenue',
            'sleep_requirement' => '8h30', 'hydration' => '1.00L',
            'freshness_24h' => 0.70, 'freshness_48h' => 1.00, 'freshness_72h' => 1.00,
            'rpe' => 8, 'load_ua' => 400, 'impact' => 3,
            'daily_alert_threshold' => '500 u.a.', 'weekly_alert_threshold' => '1500 u.a.',
            'elastic_recoil' => 'Boost', 'cfa' => 'Haut',
            'supercomp_window' => '48h', 'gain_prediction' => 'Capacite de repetition', 'injury_risk' => 'Moyen',
            'target_profiles' => ['Le Renard', 'Le Magicien', 'La Gazelle'],
        ]);

        // Add profile mappings
        $profiles = PlayerProfile::whereIn('name', ['Le Renard', 'Le Magicien', 'La Gazelle'])->get();
        foreach ($profiles as $profile) {
            $theme->playerProfiles()->attach($profile->id, ['percentage' => 20]);
        }

        $this->command->info("  Created theme ID {$theme->id} with rule and profile mappings.");
    }

    /**
     * Add missing cardio themes from Excel CARDIO EN SALLE sheet.
     * These 24 themes exist in the Excel but not in the DB.
     */
    private function addMissingCardioThemes(): void
    {
        $this->command->info('Adding missing cardio themes...');

        $missingCardio = [
            // Data from Excel CARDIO EN SALLE, Football rows 358-382
            ['SIT (Sprint Interval)', 'cardio', 'red', 'SIT', 'Puissance Max', 1, [
                'mets' => 19.5, 'rpe' => 10, 'sleep_requirement' => '9h', 'hydration' => '1.00L',
                'freshness_24h' => 0.30, 'freshness_48h' => 0.60, 'freshness_72h' => 0.95,
                'supercomp_window' => '72h', 'gain_prediction' => 'Puissance maximale', 'injury_risk' => 'Tres Eleve',
                'exercise_count' => '5-8', 'sets' => '5-8', 'reps' => '10-15 sec', 'recovery_time' => '2-3 min', 'load_type' => 'sprints',
                'duration' => '25-35 min', 'load_ua' => 200, 'impact' => 5,
            ]],
            ['Tempo run', 'cardio', 'green', 'Tempo Run', 'Rythme Stable', 2, [
                'mets' => 9.5, 'rpe' => 5, 'sleep_requirement' => '8h', 'hydration' => '1.00L',
                'freshness_24h' => 0.75, 'freshness_48h' => 1.00, 'freshness_72h' => 1.00,
                'supercomp_window' => '24h', 'gain_prediction' => 'Rythme de course', 'injury_risk' => 'Faible',
                'exercise_count' => '1', 'sets' => '1', 'reps' => 'Continu', 'recovery_time' => '-', 'load_type' => 'course continue',
                'duration' => '45-55 min', 'load_ua' => 250, 'impact' => 3,
            ]],
            ['Continuous variable', 'cardio', 'green', 'Continu Variable', 'Lecture du Jeu', 3, [
                'mets' => 10.0, 'rpe' => 5, 'sleep_requirement' => '8h', 'hydration' => '1.00L',
                'freshness_24h' => 0.70, 'freshness_48h' => 0.95, 'freshness_72h' => 1.00,
                'supercomp_window' => '24h', 'gain_prediction' => 'Adaptabilite cardiovasculaire', 'injury_risk' => 'Faible',
                'exercise_count' => '1', 'sets' => '1', 'reps' => 'Continu', 'recovery_time' => '-', 'load_type' => 'course variable',
                'duration' => '40-50 min', 'load_ua' => 250, 'impact' => 2,
            ]],
            ['Interval training long', 'cardio', 'yellow', 'Interval Training Long', 'Endurance Specifique', 4, [
                'mets' => 12.0, 'rpe' => 7, 'sleep_requirement' => '8h30', 'hydration' => '1.25L',
                'freshness_24h' => 0.55, 'freshness_48h' => 0.85, 'freshness_72h' => 1.00,
                'supercomp_window' => '48h', 'gain_prediction' => 'Endurance specifique', 'injury_risk' => 'Moyen',
                'exercise_count' => '4-6', 'sets' => '4-6', 'reps' => '4-6 min', 'recovery_time' => '1-2 min', 'load_type' => 'intervalles',
                'duration' => '40-50 min', 'load_ua' => 420, 'impact' => 3,
            ]],
            ['Pyramidal / escalier', 'cardio', 'green', 'Pyramidal', 'Adaptation Intensite', 5, [
                'mets' => 11.0, 'rpe' => 6, 'sleep_requirement' => '8h30', 'hydration' => '1.25L',
                'freshness_24h' => 0.65, 'freshness_48h' => 0.90, 'freshness_72h' => 1.00,
                'supercomp_window' => '36h', 'gain_prediction' => 'Adaptation multi-intensite', 'injury_risk' => 'Faible',
                'exercise_count' => '1', 'sets' => '1', 'reps' => 'Continu', 'recovery_time' => '-', 'load_type' => 'pyramidal',
                'duration' => '50-60 min', 'load_ua' => 360, 'impact' => 3,
            ]],
            ['Navette / shuttle run', 'cardio', 'red', 'Navette', 'Changements Direction', 6, [
                'mets' => 13.5, 'rpe' => 9, 'sleep_requirement' => '8h30', 'hydration' => '0.75L',
                'freshness_24h' => 0.40, 'freshness_48h' => 0.70, 'freshness_72h' => 0.95,
                'supercomp_window' => '48-72h', 'gain_prediction' => 'Agilite & changements direction', 'injury_risk' => 'Eleve',
                'exercise_count' => '8-12', 'sets' => '8-12', 'reps' => '30-45 sec', 'recovery_time' => '30-60 sec', 'load_type' => 'navettes',
                'duration' => '25-30 min', 'load_ua' => 450, 'impact' => 5,
            ]],
            ['Circuit cardio', 'cardio', 'green', 'Circuit Cardio', 'Global', 7, [
                'mets' => 10.5, 'rpe' => 5, 'sleep_requirement' => '8h', 'hydration' => '1.00L',
                'freshness_24h' => 0.70, 'freshness_48h' => 0.95, 'freshness_72h' => 1.00,
                'supercomp_window' => '24h', 'gain_prediction' => 'Condition generale', 'injury_risk' => 'Faible',
                'exercise_count' => '1', 'sets' => '1', 'reps' => 'Continu', 'recovery_time' => '-', 'load_type' => 'circuit',
                'duration' => '55-65 min', 'load_ua' => 250, 'impact' => 2,
            ]],
            ['Transferts', 'cardio', 'yellow', 'Transferts', 'Transfert Match', 8, [
                'mets' => 14.5, 'rpe' => 7, 'sleep_requirement' => '8h30', 'hydration' => '1.25L',
                'freshness_24h' => 0.50, 'freshness_48h' => 0.80, 'freshness_72h' => 1.00,
                'supercomp_window' => '48h', 'gain_prediction' => 'Transfert specifique terrain', 'injury_risk' => 'Moyen',
                'exercise_count' => '5-8', 'sets' => '5-8', 'reps' => '2-3 min', 'recovery_time' => '1-2 min', 'load_type' => 'transferts',
                'duration' => '35-45 min', 'load_ua' => 350, 'impact' => 3,
            ]],
            ['Over / Under', 'cardio', 'blue', 'Over / Under', 'Gestion Allure', 9, [
                'mets' => 9.0, 'rpe' => 4, 'sleep_requirement' => '8h30', 'hydration' => '0.75L',
                'freshness_24h' => 0.85, 'freshness_48h' => 1.00, 'freshness_72h' => 1.00,
                'supercomp_window' => '24h', 'gain_prediction' => 'Gestion seuil lactique', 'injury_risk' => 'Tres Faible',
                'exercise_count' => '1', 'sets' => '1', 'reps' => 'Continu', 'recovery_time' => '-', 'load_type' => 'paliers',
                'duration' => '50-60 min', 'load_ua' => 200, 'impact' => 2,
            ]],
            ['Broken tempo', 'cardio', 'orange', 'Broken Tempo', 'Efforts Fractionnes', 10, [
                'mets' => 15.0, 'rpe' => 8, 'sleep_requirement' => '8h', 'hydration' => '1.00L',
                'freshness_24h' => 0.45, 'freshness_48h' => 0.75, 'freshness_72h' => 1.00,
                'supercomp_window' => '48h', 'gain_prediction' => 'Resistance au fractionnement', 'injury_risk' => 'Moyen',
                'exercise_count' => '15-25', 'sets' => '15-25', 'reps' => '30-45 sec', 'recovery_time' => '30-45 sec', 'load_type' => 'broken tempo',
                'duration' => '30-40 min', 'load_ua' => 400, 'impact' => 3,
            ]],
            ['Progressif continu', 'cardio', 'yellow', 'Progressif', 'Montee en Regime', 11, [
                'mets' => 12.0, 'rpe' => 7, 'sleep_requirement' => '8h', 'hydration' => '1.00L',
                'freshness_24h' => 0.60, 'freshness_48h' => 0.90, 'freshness_72h' => 1.00,
                'supercomp_window' => '36h', 'gain_prediction' => 'Adaptation progressive', 'injury_risk' => 'Faible',
                'exercise_count' => '1', 'sets' => '1', 'reps' => 'Progressif', 'recovery_time' => '-', 'load_type' => 'progressif',
                'duration' => '35-45 min', 'load_ua' => 350, 'impact' => 3,
            ]],
            ['Back-to-back intervals', 'cardio', 'red', 'Back-to-back', 'Resistance Fatigue', 12, [
                'mets' => 13.5, 'rpe' => 9, 'sleep_requirement' => '8h30', 'hydration' => '0.75L',
                'freshness_24h' => 0.35, 'freshness_48h' => 0.65, 'freshness_72h' => 0.90,
                'supercomp_window' => '72h', 'gain_prediction' => 'Resistance a la fatigue', 'injury_risk' => 'Eleve',
                'exercise_count' => '8-12', 'sets' => '8-12', 'reps' => '1-2 min', 'recovery_time' => '30-45 sec', 'load_type' => 'back-to-back',
                'duration' => '35-45 min', 'load_ua' => 540, 'impact' => 4,
            ]],
            ['Norvegien (double seuil)', 'cardio', 'red', 'Norvegien Double', 'Tres Haut Niveau', 13, [
                'mets' => 16.0, 'rpe' => 10, 'sleep_requirement' => '9h', 'hydration' => '1.25L',
                'freshness_24h' => 0.30, 'freshness_48h' => 0.60, 'freshness_72h' => 0.95,
                'supercomp_window' => '72h', 'gain_prediction' => 'VO2max elite', 'injury_risk' => 'Tres Eleve',
                'exercise_count' => '10-15', 'sets' => '10-15', 'reps' => '15-20 sec', 'recovery_time' => '15-30 sec', 'load_type' => 'double seuil',
                'duration' => '20-30 min', 'load_ua' => 600, 'impact' => 4,
            ]],
            ['4x4 norvegien', 'cardio', 'red', '4x4 Norvegien', 'Capacite Max', 14, [
                'mets' => 17.0, 'rpe' => 9, 'sleep_requirement' => '8h30', 'hydration' => '1.00L',
                'freshness_24h' => 0.30, 'freshness_48h' => 0.55, 'freshness_72h' => 0.90,
                'supercomp_window' => '72h', 'gain_prediction' => 'Capacite aerobie maximale', 'injury_risk' => 'Eleve',
                'exercise_count' => '4-6', 'sets' => '4-6', 'reps' => '1-2 min', 'recovery_time' => '2-3 min', 'load_type' => '4x4',
                'duration' => '30-40 min', 'load_ua' => 450, 'impact' => 3,
            ]],
            ['LSD (Long Slow Distance)', 'cardio', 'green', 'LSD', 'Base Endurance', 15, [
                'mets' => 8.5, 'rpe' => 4, 'sleep_requirement' => '8h30', 'hydration' => '1.75L',
                'freshness_24h' => 0.75, 'freshness_48h' => 1.00, 'freshness_72h' => 1.00,
                'supercomp_window' => '24h', 'gain_prediction' => 'Endurance de base', 'injury_risk' => 'Tres Faible',
                'exercise_count' => '1', 'sets' => '1', 'reps' => 'Continu', 'recovery_time' => '-', 'load_type' => 'footing long',
                'duration' => '55-65 min', 'load_ua' => 360, 'impact' => 3,
            ]],
            ['Zone 2 training', 'cardio', 'blue', 'Zone 2', 'Fondations', 16, [
                'mets' => 7.5, 'rpe' => 3, 'sleep_requirement' => '7h30', 'hydration' => '0.75L',
                'freshness_24h' => 0.95, 'freshness_48h' => 1.00, 'freshness_72h' => 1.00,
                'supercomp_window' => '12-24h', 'gain_prediction' => 'Fondation aerobie', 'injury_risk' => 'Nul',
                'exercise_count' => '1', 'sets' => '1', 'reps' => 'Continu', 'recovery_time' => '-', 'load_type' => 'zone 2',
                'duration' => '60-70 min', 'load_ua' => 180, 'impact' => 2,
            ]],
            ['Cotes - puissance', 'cardio', 'red', 'Cotes Puissance', 'Force Cardio', 17, [
                'mets' => 13.0, 'rpe' => 9, 'sleep_requirement' => '8h30', 'hydration' => '1.00L',
                'freshness_24h' => 0.40, 'freshness_48h' => 0.70, 'freshness_72h' => 0.95,
                'supercomp_window' => '48-72h', 'gain_prediction' => 'Force de poussee cardio', 'injury_risk' => 'Eleve',
                'exercise_count' => '5-8', 'sets' => '5-8', 'reps' => '2-3 min', 'recovery_time' => '1-2 min', 'load_type' => 'cotes',
                'duration' => '35-45 min', 'load_ua' => 360, 'impact' => 4,
            ]],
            ['Cotes - endurance', 'cardio', 'orange', 'Cotes Endurance', 'Puissance Durable', 18, [
                'mets' => 14.0, 'rpe' => 8, 'sleep_requirement' => '8h30', 'hydration' => '1.50L',
                'freshness_24h' => 0.45, 'freshness_48h' => 0.75, 'freshness_72h' => 1.00,
                'supercomp_window' => '48h', 'gain_prediction' => 'Puissance durable', 'injury_risk' => 'Moyen',
                'exercise_count' => '3-5', 'sets' => '3-5', 'reps' => '5-7 min', 'recovery_time' => '2-3 min', 'load_type' => 'cotes longues',
                'duration' => '45-55 min', 'load_ua' => 400, 'impact' => 3,
            ]],
            ['Terrain varie', 'cardio', 'yellow', 'Terrain Varie', 'Adaptabilite', 19, [
                'mets' => 11.5, 'rpe' => 7, 'sleep_requirement' => '8h', 'hydration' => '1.25L',
                'freshness_24h' => 0.65, 'freshness_48h' => 0.90, 'freshness_72h' => 1.00,
                'supercomp_window' => '36h', 'gain_prediction' => 'Adaptabilite neuromusculaire', 'injury_risk' => 'Moyen',
                'exercise_count' => '1', 'sets' => '1', 'reps' => 'Continu', 'recovery_time' => '-', 'load_type' => 'terrain mixte',
                'duration' => '40-50 min', 'load_ua' => 350, 'impact' => 3,
            ]],
            ['Fartlek continu', 'cardio', 'green', 'Fartlek Continu', 'Endurance Libre', 20, [
                'mets' => 9.0, 'rpe' => 6, 'sleep_requirement' => '7h30', 'hydration' => '0.75L',
                'freshness_24h' => 0.80, 'freshness_48h' => 1.00, 'freshness_72h' => 1.00,
                'supercomp_window' => '24h', 'gain_prediction' => 'Endurance instinctive', 'injury_risk' => 'Tres Faible',
                'exercise_count' => '1', 'sets' => '1', 'reps' => 'Continu', 'recovery_time' => '-', 'load_type' => 'fartlek libre',
                'duration' => '50-60 min', 'load_ua' => 300, 'impact' => 2,
            ]],
            ['RHIE (Repeated High Intensity)', 'cardio', 'yellow', 'RHIE', 'Efforts Tres Repetes', 21, [
                'mets' => 14.5, 'rpe' => 7, 'sleep_requirement' => '8h', 'hydration' => '0.75L',
                'freshness_24h' => 0.50, 'freshness_48h' => 0.80, 'freshness_72h' => 1.00,
                'supercomp_window' => '48h', 'gain_prediction' => 'Capacite de repetition haute intensite', 'injury_risk' => 'Moyen',
                'exercise_count' => '20-40', 'sets' => '20-40', 'reps' => '15-20 sec', 'recovery_time' => '15-20 sec', 'load_type' => 'RHIE',
                'duration' => '25-35 min', 'load_ua' => 350, 'impact' => 4,
            ]],
            ['MetCon', 'cardio', 'green', 'MetCon', 'Global Match', 22, [
                'mets' => 12.0, 'rpe' => 6, 'sleep_requirement' => '8h30', 'hydration' => '1.25L',
                'freshness_24h' => 0.65, 'freshness_48h' => 0.90, 'freshness_72h' => 1.00,
                'supercomp_window' => '36h', 'gain_prediction' => 'Condition metabolique globale', 'injury_risk' => 'Faible',
                'exercise_count' => '2-3', 'sets' => '2-3', 'reps' => '15-20 min', 'recovery_time' => '2-4 min', 'load_type' => 'metabolique',
                'duration' => '45-60 min', 'load_ua' => 360, 'impact' => 3,
            ]],
            ['Cardio technique', 'cardio', 'yellow', 'Cardio Technique', 'Transfert Jeu', 23, [
                'mets' => 13.0, 'rpe' => 6, 'sleep_requirement' => '8h', 'hydration' => '1.00L',
                'freshness_24h' => 0.60, 'freshness_48h' => 0.85, 'freshness_72h' => 1.00,
                'supercomp_window' => '36h', 'gain_prediction' => 'Cardio specifique technique', 'injury_risk' => 'Faible',
                'exercise_count' => '5-8', 'sets' => '5-8', 'reps' => '3-4 min', 'recovery_time' => '1-2 min', 'load_type' => 'technique',
                'duration' => '35-45 min', 'load_ua' => 360, 'impact' => 2,
            ]],
            ['Cardio contraste', 'cardio', 'red', 'Cardio Contraste', 'Changement Rythme', 24, [
                'mets' => 13.5, 'rpe' => 9, 'sleep_requirement' => '8h', 'hydration' => '0.75L',
                'freshness_24h' => 0.40, 'freshness_48h' => 0.70, 'freshness_72h' => 0.95,
                'supercomp_window' => '48-72h', 'gain_prediction' => 'Resistance aux changements rythme', 'injury_risk' => 'Eleve',
                'exercise_count' => '10-15', 'sets' => '10-15', 'reps' => '45-60 sec', 'recovery_time' => '45-60 sec', 'load_type' => 'contraste',
                'duration' => '35-45 min', 'load_ua' => 450, 'impact' => 4,
            ]],
        ];

        $created = 0;
        foreach ($missingCardio as [$name, $type, $zone, $quality, $display, $sortOffset, $ruleData]) {
            if (WorkoutTheme::where('name', $name)->exists()) {
                $this->command->info("  Skipping (exists): {$name}");
                continue;
            }

            $theme = WorkoutTheme::create([
                'name' => $name,
                'type' => $type,
                'zone_color' => $zone,
                'quality_method' => $quality,
                'display_name' => $display,
                'sort_order' => 50 + $sortOffset, // After existing cardio sort orders
            ]);

            WorkoutThemeRule::create(array_merge(
                ['workout_theme_id' => $theme->id],
                $ruleData
            ));

            $created++;
        }

        $this->command->info("  Created {$created} new cardio themes.");
    }

    /**
     * Populate the discipline column for all themes.
     * Currently NULL = shared across all disciplines.
     * Per the Excel, gym themes are defined per-discipline but we keep them shared
     * since the display_name mapping handles per-discipline differentiation.
     */
    private function populateDisciplineColumn(): void
    {
        $this->command->info('Discipline column left as NULL (shared across all disciplines) by design.');
        // The Excel defines different display_names per discipline (football/padel/fitness),
        // but the current architecture uses a single shared theme with the football display_name.
        // Discipline-specific display_names would require a theme_translations table.
        // This is noted as a future enhancement.
    }

    /**
     * Fix unmapped gym themes that should have profile mappings.
     */
    private function fixUnmappedThemes(): void
    {
        $this->command->info('Fixing unmapped theme profile assignments...');

        // Renforcement articulaire -> La Pieuvre, Le Casseur
        $theme16 = WorkoutTheme::where('name', 'Renforcement articulaire')->first();
        if ($theme16) {
            $profiles = PlayerProfile::whereIn('name', ['La Pieuvre', 'Le Casseur'])->pluck('id');
            foreach ($profiles as $profileId) {
                if (!$theme16->playerProfiles()->where('player_profile_id', $profileId)->exists()) {
                    $theme16->playerProfiles()->attach($profileId, ['percentage' => 10]);
                }
            }
            $this->command->info("  Mapped Renforcement articulaire to La Pieuvre, Le Casseur");
        }

        // Reathlétisation -> All football profiles
        $theme17 = WorkoutTheme::where('name', 'Reathlétisation')->first();
        if ($theme17) {
            $allFootball = PlayerProfile::whereIn('group', ['GARDIEN', 'DEFENSEUR', 'MILIEU', 'ATTAQUANT'])->pluck('id');
            foreach ($allFootball as $profileId) {
                if (!$theme17->playerProfiles()->where('player_profile_id', $profileId)->exists()) {
                    $theme17->playerProfiles()->attach($profileId, ['percentage' => 5]);
                }
            }
            $this->command->info("  Mapped Reathlétisation to all football profiles");
        }
    }
}
