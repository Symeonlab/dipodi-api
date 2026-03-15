<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkoutTheme;
use App\Models\WorkoutThemeRule;

class DipoddiProgrammeEnhancementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder runs AFTER DipoddiProgrammeSeeder and updates existing
     * workout themes and their rules with enhanced data from the
     * DIPODDI PROGRAMME Excel document (GYM themes section).
     *
     * It sets discipline to null (shared) since these themes are used
     * across all disciplines (football, padel, fitness_women, fitness_men)
     * with the same parameters.
     */
    public function run(): void
    {
        $this->command->info('Starting DIPODDI Programme Enhancement (GYM themes)...');

        $themes = $this->getThemeDefinitions();

        $updated = 0;
        $skipped = 0;

        foreach ($themes as $index => $definition) {
            $theme = WorkoutTheme::where('name', $definition['name'])->first();

            if (!$theme) {
                $this->command->warn("Theme not found: \"{$definition['name']}\" — skipping.");
                $skipped++;
                continue;
            }

            // Update the theme record
            $theme->update([
                'discipline'     => null, // shared across all disciplines
                'zone_color'     => $definition['zone_color'],
                'quality_method' => $definition['quality_method'],
                'display_name'   => $definition['display_name'],
                'sort_order'     => $index + 1,
            ]);

            // Find or create the associated rule and update it
            $rule = $theme->rules;

            if ($rule) {
                $rule->update($definition['rule']);
            } else {
                $theme->rules()->create($definition['rule']);
            }

            $updated++;
            $this->command->info("  Updated theme: \"{$definition['name']}\" (sort_order: " . ($index + 1) . ")");
        }

        $this->command->info("Enhancement complete: {$updated} themes updated, {$skipped} skipped.");
    }

    /**
     * Get the full theme definitions from the DIPODDI PROGRAMME Excel.
     * All 18 GYM themes with their parameters and target profiles.
     *
     * @return array<int, array{name: string, zone_color: string, quality_method: string, display_name: string, rule: array}>
     */
    private function getThemeDefinitions(): array
    {
        return [
            // 1. Force maximale
            [
                'name'           => 'Force maximale',
                'zone_color'     => 'red',
                'quality_method' => 'Force maximale',
                'display_name'   => 'Le Blindage',
                'rule' => [
                    'mets'                   => 7.0,
                    'duration'               => '90-120 min',
                    'charges'                => '85-100 %',
                    'speed_intensity'        => 'Lente-contrôlée',
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.00L',
                    'freshness_24h'          => 0.3,
                    'freshness_48h'          => 0.6,
                    'freshness_72h'          => 0.95,
                    'rpe'                    => 9,
                    'load_ua'                => 540,
                    'impact'                 => 3,
                    'daily_alert_threshold'  => '600 u.a.',
                    'weekly_alert_threshold' => '1200 u.a.',
                    'elastic_recoil'         => 'Diminue',
                    'cfa'                    => 'Moyen',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Résistance aux chocs',
                    'injury_risk'            => 'Faible',
                    'target_profiles'        => ['La Panthère', 'L\'Araignée', 'Le Casseur', 'The Rock', 'Le Tank'],
                ],
            ],

            // 2. Force sous-maximale
            [
                'name'           => 'Force sous-maximale',
                'zone_color'     => 'red',
                'quality_method' => 'Force sous-maximale',
                'display_name'   => 'Le Premier Pas',
                'rule' => [
                    'mets'                   => 8.0,
                    'duration'               => '75-100 min',
                    'charges'                => '80-92 %',
                    'speed_intensity'        => 'Explosive',
                    'sleep_requirement'      => '9h30',
                    'hydration'              => '1.25L',
                    'freshness_24h'          => 0.25,
                    'freshness_48h'          => 0.55,
                    'freshness_72h'          => 0.9,
                    'rpe'                    => 9,
                    'load_ua'                => 675,
                    'impact'                 => 4,
                    'daily_alert_threshold'  => '700 u.a.',
                    'weekly_alert_threshold' => '1400 u.a.',
                    'elastic_recoil'         => 'Augmente',
                    'cfa'                    => 'Élevé',
                    'supercomp_window'       => '72h',
                    'gain_prediction'        => 'Explosivité & Réaction',
                    'injury_risk'            => 'Très Élevé',
                    'target_profiles'        => ['La Panthère', 'Le Chat', 'Le Casseur', 'Le Pitbull', 'La Gazelle', 'Le Magicien', 'Le Renard'],
                ],
            ],

            // 3. Force dynamique
            [
                'name'           => 'Force dynamique',
                'zone_color'     => 'orange',
                'quality_method' => 'Force dynamique',
                'display_name'   => 'Vitesse de Contraction',
                'rule' => [
                    'mets'                   => 7.5,
                    'duration'               => '80-100 min',
                    'charges'                => '60-80 %',
                    'speed_intensity'        => 'Rapide-explosive',
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.25L',
                    'freshness_24h'          => 0.3,
                    'freshness_48h'          => 0.65,
                    'freshness_72h'          => 0.95,
                    'rpe'                    => 8,
                    'load_ua'                => 560,
                    'impact'                 => 3,
                    'daily_alert_threshold'  => '600 u.a.',
                    'weekly_alert_threshold' => '1200 u.a.',
                    'elastic_recoil'         => 'Augmente',
                    'cfa'                    => 'Moyen',
                    'supercomp_window'       => '48-72h',
                    'gain_prediction'        => 'Vitesse de pied / Vivacité',
                    'injury_risk'            => 'Élevé',
                    'target_profiles'        => ['Le Chat', 'La Gazelle', 'Le Magicien', 'Le Renard', 'Le Sniper'],
                ],
            ],

            // 4. Force explosive
            [
                'name'           => 'Force explosive',
                'zone_color'     => 'red',
                'quality_method' => 'Force explosive',
                'display_name'   => 'Répétition de Duels',
                'rule' => [
                    'mets'                   => 8.5,
                    'duration'               => '75-90 min',
                    'charges'                => '30-60 %',
                    'speed_intensity'        => 'Maximale',
                    'sleep_requirement'      => '9h30',
                    'hydration'              => '1.50L',
                    'freshness_24h'          => 0.35,
                    'freshness_48h'          => 0.6,
                    'freshness_72h'          => 0.9,
                    'rpe'                    => 9,
                    'load_ua'                => 630,
                    'impact'                 => 4,
                    'daily_alert_threshold'  => '700 u.a.',
                    'weekly_alert_threshold' => '1400 u.a.',
                    'elastic_recoil'         => 'Augmente',
                    'cfa'                    => 'Élevé',
                    'supercomp_window'       => '72h',
                    'gain_prediction'        => 'Endurance de Puissance',
                    'injury_risk'            => 'Élevé',
                    'target_profiles'        => ['La Panthère', 'Le Casseur', 'Le Pitbull', 'Le Tank', 'Le Renard'],
                ],
            ],

            // 5. Puissance musculaire
            [
                'name'           => 'Puissance musculaire',
                'zone_color'     => 'orange',
                'quality_method' => 'Puissance musculaire',
                'display_name'   => 'Impact & Frappe',
                'rule' => [
                    'mets'                   => 7.0,
                    'duration'               => '80-100 min',
                    'charges'                => '40-70 %',
                    'speed_intensity'        => 'Contrôlée-explosive',
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.25L',
                    'freshness_24h'          => 0.4,
                    'freshness_48h'          => 0.7,
                    'freshness_72h'          => 0.95,
                    'rpe'                    => 8,
                    'load_ua'                => 480,
                    'impact'                 => 3,
                    'daily_alert_threshold'  => '550 u.a.',
                    'weekly_alert_threshold' => '1100 u.a.',
                    'elastic_recoil'         => 'Augmente',
                    'cfa'                    => 'Moyen',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Force de frappe / Impact',
                    'injury_risk'            => 'Moyen',
                    'target_profiles'        => ['La Panthère', 'Le Casseur', 'The Rock', 'Le Tank', 'Le Sniper'],
                ],
            ],

            // 6. Hypertrophie myofibrillaire
            [
                'name'           => 'Hypertrophie myofibrillaire',
                'zone_color'     => 'orange',
                'quality_method' => 'Hypertrophie Myo.',
                'display_name'   => 'L\'Armure Fonctionnelle',
                'rule' => [
                    'mets'                   => 6.5,
                    'duration'               => '80-100 min',
                    'charges'                => '75-85 %',
                    'speed_intensity'        => 'Contrôlée',
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.25L',
                    'freshness_24h'          => 0.45,
                    'freshness_48h'          => 0.7,
                    'freshness_72h'          => 0.95,
                    'rpe'                    => 8,
                    'load_ua'                => 520,
                    'impact'                 => 3,
                    'daily_alert_threshold'  => '600 u.a.',
                    'weekly_alert_threshold' => '1200 u.a.',
                    'elastic_recoil'         => 'Neutre',
                    'cfa'                    => 'Moyen',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Puissance utile au duel',
                    'injury_risk'            => 'Moyen',
                    'target_profiles'        => ['Le Casseur', 'The Rock', 'Le Tank', 'Le Polyvalent'],
                ],
            ],

            // 7. Hypertrophie sarcoplasmique
            [
                'name'           => 'Hypertrophie sarcoplasmique',
                'zone_color'     => 'yellow',
                'quality_method' => 'Hypertrophie Sarc.',
                'display_name'   => 'Gros Gabarit',
                'rule' => [
                    'mets'                   => 6.0,
                    'duration'               => '85-110 min',
                    'charges'                => '60-75 %',
                    'speed_intensity'        => 'Moyenne',
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.50L',
                    'freshness_24h'          => 0.45,
                    'freshness_48h'          => 0.65,
                    'freshness_72h'          => 0.95,
                    'rpe'                    => 7,
                    'load_ua'                => 455,
                    'impact'                 => 2,
                    'daily_alert_threshold'  => '500 u.a.',
                    'weekly_alert_threshold' => '1000 u.a.',
                    'elastic_recoil'         => 'Lourdeur',
                    'cfa'                    => 'Bas',
                    'supercomp_window'       => '96h',
                    'gain_prediction'        => 'Masse / Présence physique',
                    'injury_risk'            => 'Moyen',
                    'target_profiles'        => ['Le Tank', 'The Rock', 'Le Casseur'],
                ],
            ],

            // 8. Volume musculaire
            [
                'name'           => 'Volume musculaire',
                'zone_color'     => 'yellow',
                'quality_method' => 'Volume Musculaire',
                'display_name'   => 'Densité Musculaire',
                'rule' => [
                    'mets'                   => 6.0,
                    'duration'               => '80-100 min',
                    'charges'                => '65-80 %',
                    'speed_intensity'        => 'Contrôlée',
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.50L',
                    'freshness_24h'          => 0.5,
                    'freshness_48h'          => 0.7,
                    'freshness_72h'          => 1.0,
                    'rpe'                    => 7,
                    'load_ua'                => 490,
                    'impact'                 => 2,
                    'daily_alert_threshold'  => '550 u.a.',
                    'weekly_alert_threshold' => '1100 u.a.',
                    'elastic_recoil'         => 'Lent',
                    'cfa'                    => 'Bas',
                    'supercomp_window'       => '72h',
                    'gain_prediction'        => 'Volume & Dureté',
                    'injury_risk'            => 'Faible',
                    'target_profiles'        => ['Le Tank', 'Le Casseur', 'The Rock', 'Le Polyvalent'],
                ],
            ],

            // 9. Endurance de force
            [
                'name'           => 'Endurance de force',
                'zone_color'     => 'orange',
                'quality_method' => 'Endurance de Force',
                'display_name'   => 'Enchaînement d\'Actions',
                'rule' => [
                    'mets'                   => 5.5,
                    'duration'               => '70-90 min',
                    'charges'                => '40-60 %',
                    'speed_intensity'        => 'Continue',
                    'sleep_requirement'      => '8h30',
                    'hydration'              => '1.25L',
                    'freshness_24h'          => 0.4,
                    'freshness_48h'          => 0.75,
                    'freshness_72h'          => 1.0,
                    'rpe'                    => 8,
                    'load_ua'                => 400,
                    'impact'                 => 2,
                    'daily_alert_threshold'  => '500 u.a.',
                    'weekly_alert_threshold' => '1000 u.a.',
                    'elastic_recoil'         => 'Neutre',
                    'cfa'                    => 'Moyen',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Capacité de répétition',
                    'injury_risk'            => 'Moyen',
                    'target_profiles'        => ['Le Pitbull', 'La Gazelle', 'Le Polyvalent', 'Le Renard'],
                ],
            ],

            // 10. Endurance musculaire
            [
                'name'           => 'Endurance musculaire',
                'zone_color'     => 'green',
                'quality_method' => 'Endurance Muscu.',
                'display_name'   => 'Socle Athlétique',
                'rule' => [
                    'mets'                   => 5.0,
                    'duration'               => '60-80 min',
                    'charges'                => '20-50 %',
                    'speed_intensity'        => 'Fluide',
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.00L',
                    'freshness_24h'          => 0.55,
                    'freshness_48h'          => 0.8,
                    'freshness_72h'          => 1.0,
                    'rpe'                    => 5,
                    'load_ua'                => 300,
                    'impact'                 => 1,
                    'daily_alert_threshold'  => '400 u.a.',
                    'weekly_alert_threshold' => '800 u.a.',
                    'elastic_recoil'         => 'Neutre',
                    'cfa'                    => 'Bas',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Base de force générale',
                    'injury_risk'            => 'Faible',
                    'target_profiles'        => ['L\'Architecte', 'Le Contrôleur', 'Le Relanceur', 'La Pieuvre'],
                ],
            ],

            // 11. Perte de poids
            [
                'name'           => 'Perte de poids',
                'zone_color'     => 'orange',
                'quality_method' => 'Perte de Poids',
                'display_name'   => 'Affûtage Poids/Puissance',
                'rule' => [
                    'mets'                   => 7.5,
                    'duration'               => '55-75 min',
                    'charges'                => '40-60 %',
                    'speed_intensity'        => 'Continue',
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.50L',
                    'freshness_24h'          => 0.6,
                    'freshness_48h'          => 0.9,
                    'freshness_72h'          => 1.0,
                    'rpe'                    => 8,
                    'load_ua'                => 480,
                    'impact'                 => 3,
                    'daily_alert_threshold'  => '600 u.a.',
                    'weekly_alert_threshold' => '1200 u.a.',
                    'elastic_recoil'         => 'Neutre',
                    'cfa'                    => 'Haut',
                    'supercomp_window'       => '36-48h',
                    'gain_prediction'        => 'Rapport poids/puissance',
                    'injury_risk'            => 'Moyen',
                    'target_profiles'        => ['La Gazelle', 'Le Renard', 'Le Magicien', 'Le Chat'],
                ],
            ],

            // 12. Sèche / définition musculaire
            [
                'name'           => 'Sèche / définition musculaire',
                'zone_color'     => 'orange',
                'quality_method' => 'Sèche / Définition',
                'display_name'   => 'Définition du Muscle',
                'rule' => [
                    'mets'                   => 7.3,
                    'duration'               => '60-75 min',
                    'charges'                => '50-65 %',
                    'speed_intensity'        => 'Contrôlée',
                    'sleep_requirement'      => '8h30',
                    'hydration'              => '1.25L',
                    'freshness_24h'          => 0.65,
                    'freshness_48h'          => 0.85,
                    'freshness_72h'          => 1.0,
                    'rpe'                    => 7,
                    'load_ua'                => 420,
                    'impact'                 => 2,
                    'daily_alert_threshold'  => '550 u.a.',
                    'weekly_alert_threshold' => '1100 u.a.',
                    'elastic_recoil'         => 'Athlétique',
                    'cfa'                    => 'Moyen',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Qualité visuelle / Tonicité',
                    'injury_risk'            => 'Faible',
                    'target_profiles'        => ['Le Sniper', 'Le Magicien', 'La Gazelle', 'Le Relanceur'],
                ],
            ],

            // 13. Condition physique générale
            [
                'name'           => 'Condition physique générale',
                'zone_color'     => 'orange',
                'quality_method' => 'Condition (GPP)',
                'display_name'   => 'Forme Physique',
                'rule' => [
                    'mets'                   => 8.0,
                    'duration'               => '50-70 min',
                    'charges'                => 'Charges libres',
                    'speed_intensity'        => 'Fonctionnelle',
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.25L',
                    'freshness_24h'          => 0.7,
                    'freshness_48h'          => 0.95,
                    'freshness_72h'          => 1.0,
                    'rpe'                    => 7,
                    'load_ua'                => 420,
                    'impact'                 => 3,
                    'daily_alert_threshold'  => '550 u.a.',
                    'weekly_alert_threshold' => '1100 u.a.',
                    'elastic_recoil'         => 'Neutre',
                    'cfa'                    => 'Moyen',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Polyvalence physique',
                    'injury_risk'            => 'Moyen',
                    'target_profiles'        => ['Le Polyvalent', 'La Gazelle', 'Le Pitbull', 'Le Relanceur'],
                ],
            ],

            // 14. Remise en forme
            [
                'name'           => 'Remise en forme',
                'zone_color'     => 'green',
                'quality_method' => 'Remise en Forme',
                'display_name'   => 'Reprise Douce',
                'rule' => [
                    'mets'                   => 4.5,
                    'duration'               => '45-60 min',
                    'charges'                => 'Léger',
                    'speed_intensity'        => 'Lente',
                    'sleep_requirement'      => '7h30',
                    'hydration'              => '1.00L',
                    'freshness_24h'          => 0.85,
                    'freshness_48h'          => 1.0,
                    'freshness_72h'          => 1.0,
                    'rpe'                    => 4,
                    'load_ua'                => 200,
                    'impact'                 => 1,
                    'daily_alert_threshold'  => '400 u.a.',
                    'weekly_alert_threshold' => '800 u.a.',
                    'elastic_recoil'         => 'Reset',
                    'cfa'                    => 'Zéro',
                    'supercomp_window'       => '12h',
                    'gain_prediction'        => 'Remise en route',
                    'injury_risk'            => 'Nul',
                    'target_profiles'        => ['L\'Araignée', 'Le Contrôleur', 'L\'Architecte', 'Le Relanceur'],
                ],
            ],

            // 15. Prévention des blessures
            [
                'name'           => 'Prévention des blessures',
                'zone_color'     => 'blue',
                'quality_method' => 'Prévention',
                'display_name'   => 'Anti-Blessures',
                'rule' => [
                    'mets'                   => 3.0,
                    'duration'               => '35-50 min',
                    'charges'                => 'Léger',
                    'speed_intensity'        => 'Très contrôlée',
                    'sleep_requirement'      => '8h',
                    'hydration'              => '0.75L',
                    'freshness_24h'          => 0.9,
                    'freshness_48h'          => 1.0,
                    'freshness_72h'          => 1.0,
                    'rpe'                    => 3,
                    'load_ua'                => 120,
                    'impact'                 => 1,
                    'daily_alert_threshold'  => '300 u.a.',
                    'weekly_alert_threshold' => '600 u.a.',
                    'elastic_recoil'         => 'Intégrité',
                    'cfa'                    => 'Zéro',
                    'supercomp_window'       => '12-24h',
                    'gain_prediction'        => 'Réparation tissulaire',
                    'injury_risk'            => 'Nul',
                    'target_profiles'        => ['La Pieuvre', 'Le Contrôleur', 'L\'Architecte', 'Le Relanceur'],
                ],
            ],

            // 16. Renforcement tendineux
            [
                'name'           => 'Renforcement tendineux',
                'zone_color'     => 'blue',
                'quality_method' => 'Renfort Tendineux',
                'display_name'   => 'Genoux d\'Acier',
                'rule' => [
                    'mets'                   => 3.5,
                    'duration'               => '50-65 min',
                    'charges'                => '65-85 %',
                    'speed_intensity'        => 'Excentrique lente',
                    'sleep_requirement'      => '8h',
                    'hydration'              => '0.75L',
                    'freshness_24h'          => 0.85,
                    'freshness_48h'          => 0.95,
                    'freshness_72h'          => 1.0,
                    'rpe'                    => 3,
                    'load_ua'                => 135,
                    'impact'                 => 1,
                    'daily_alert_threshold'  => '300 u.a.',
                    'weekly_alert_threshold' => '600 u.a.',
                    'elastic_recoil'         => 'Intégrité',
                    'cfa'                    => 'Zéro',
                    'supercomp_window'       => '24h',
                    'gain_prediction'        => 'Stabilité articulaire',
                    'injury_risk'            => 'Nul',
                    'target_profiles'        => ['La Pieuvre', 'Le Casseur', 'The Rock'],
                ],
            ],

            // 17. Réathlétisation
            [
                'name'           => 'Réathlétisation',
                'zone_color'     => 'blue',
                'quality_method' => 'Réathlétisation',
                'display_name'   => 'Retour au Terrain',
                'rule' => [
                    'mets'                   => 3.8,
                    'duration'               => '40-60 min',
                    'charges'                => 'Progressif',
                    'speed_intensity'        => 'Lente',
                    'sleep_requirement'      => '8h30',
                    'hydration'              => '0.75L',
                    'freshness_24h'          => 0.75,
                    'freshness_48h'          => 0.9,
                    'freshness_72h'          => 1.0,
                    'rpe'                    => 4,
                    'load_ua'                => 180,
                    'impact'                 => 1,
                    'daily_alert_threshold'  => '350 u.a.',
                    'weekly_alert_threshold' => '700 u.a.',
                    'elastic_recoil'         => 'Reset',
                    'cfa'                    => 'Zéro',
                    'supercomp_window'       => '36h',
                    'gain_prediction'        => 'Réathlétisation',
                    'injury_risk'            => 'Moyen',
                    'target_profiles'        => ['Le Polyvalent', 'Le Relanceur', 'L\'Architecte'],
                ],
            ],

            // 18. Coordination / proprioception
            [
                'name'           => 'Coordination / proprioception',
                'zone_color'     => 'blue',
                'quality_method' => 'Coordination',
                'display_name'   => 'Équilibre d\'Appuis',
                'rule' => [
                    'mets'                   => 4.0,
                    'duration'               => '35-50 min',
                    'charges'                => 'Poids du corps',
                    'speed_intensity'        => 'Fluide',
                    'sleep_requirement'      => '7h30',
                    'hydration'              => '0.50L',
                    'freshness_24h'          => 0.8,
                    'freshness_48h'          => 0.95,
                    'freshness_72h'          => 1.0,
                    'rpe'                    => 3,
                    'load_ua'                => 90,
                    'impact'                 => 1,
                    'daily_alert_threshold'  => '300 u.a.',
                    'weekly_alert_threshold' => '600 u.a.',
                    'elastic_recoil'         => 'Précision',
                    'cfa'                    => 'Zéro',
                    'supercomp_window'       => '24h',
                    'gain_prediction'        => 'Précision des appuis',
                    'injury_risk'            => 'Très Faible',
                    'target_profiles'        => ['Le Chat', 'Le Magicien', 'La Gazelle', 'Le Renard'],
                ],
            ],
        ];
    }
}
