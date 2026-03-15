<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkoutTheme;

class CardioThemeEnhancementSeeder extends Seeder
{
    /**
     * Enhance ALL cardio, home, and mobility themes with zone_color,
     * RPE, freshness, supercompensation, and training science data
     * from the DIPODDI PROGRAMME Excel document.
     *
     * Runs AFTER DipoddiCardioAndMappingsSeeder.
     */
    public function run(): void
    {
        $this->command->info('Enhancing CARDIO, HOME & MOBILITY themes...');

        $themes = $this->getThemeDefinitions();
        $updated = 0;
        $skipped = 0;

        foreach ($themes as $definition) {
            $theme = WorkoutTheme::where('name', $definition['name'])->first();

            if (!$theme) {
                $this->command->warn("  Theme not found: \"{$definition['name']}\" — skipping.");
                $skipped++;
                continue;
            }

            // Update theme metadata
            $theme->update([
                'zone_color'     => $definition['zone_color'],
                'quality_method' => $definition['quality_method'],
                'display_name'   => $definition['display_name'],
                'sort_order'     => $definition['sort_order'],
            ]);

            // Update the associated rule with training science data
            $rule = $theme->rules;
            if ($rule) {
                $rule->update($definition['rule']);
            }

            $updated++;
        }

        $this->command->info("Cardio enhancement complete: {$updated} themes updated, {$skipped} skipped.");
    }

    private function getThemeDefinitions(): array
    {
        return [
            // ===================================================================
            // RED ZONE (90-100%) — Maximum intensity, neuromuscular
            // ===================================================================

            [
                'name'           => 'Vitesse pure',
                'zone_color'     => 'red',
                'quality_method' => 'Vitesse Pure',
                'display_name'   => 'Foudre',
                'sort_order'     => 1,
                'rule' => [
                    'mets'                   => 11.0,
                    'duration'               => '25-35 min',
                    'rpe'                    => 10,
                    'freshness_24h'          => 0.20,
                    'freshness_48h'          => 0.55,
                    'freshness_72h'          => 0.90,
                    'sleep_requirement'      => '9h30',
                    'hydration'              => '1.00L',
                    'supercomp_window'       => '72h',
                    'gain_prediction'        => 'Vitesse maximale / Accélération',
                    'injury_risk'            => 'Très Élevé',
                ],
            ],
            [
                'name'           => 'Puissance explosive',
                'zone_color'     => 'red',
                'quality_method' => 'Puissance Explosive',
                'display_name'   => 'Détonation',
                'sort_order'     => 2,
                'rule' => [
                    'mets'                   => 10.5,
                    'duration'               => '30-40 min',
                    'rpe'                    => 10,
                    'freshness_24h'          => 0.20,
                    'freshness_48h'          => 0.50,
                    'freshness_72h'          => 0.85,
                    'sleep_requirement'      => '9h30',
                    'hydration'              => '1.25L',
                    'supercomp_window'       => '72h',
                    'gain_prediction'        => 'Puissance neuromusculaire',
                    'injury_risk'            => 'Très Élevé',
                ],
            ],
            [
                'name'           => 'Puissance anaérobie alactique',
                'zone_color'     => 'red',
                'quality_method' => 'Puiss. Anaéro. Alac.',
                'display_name'   => 'Démarrage Fulgurant',
                'sort_order'     => 3,
                'rule' => [
                    'mets'                   => 10.0,
                    'duration'               => '25-35 min',
                    'rpe'                    => 9,
                    'freshness_24h'          => 0.25,
                    'freshness_48h'          => 0.55,
                    'freshness_72h'          => 0.90,
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.00L',
                    'supercomp_window'       => '72h',
                    'gain_prediction'        => 'Actions maximales courtes',
                    'injury_risk'            => 'Élevé',
                ],
            ],
            [
                'name'           => 'Tabata',
                'zone_color'     => 'red',
                'quality_method' => 'Tabata',
                'display_name'   => 'Pic Maximal',
                'sort_order'     => 4,
                'rule' => [
                    'mets'                   => 11.0,
                    'duration'               => '15-25 min',
                    'rpe'                    => 10,
                    'freshness_24h'          => 0.25,
                    'freshness_48h'          => 0.60,
                    'freshness_72h'          => 0.95,
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.25L',
                    'supercomp_window'       => '48-72h',
                    'gain_prediction'        => 'VO2max / Capacité anaérobie',
                    'injury_risk'            => 'Élevé',
                ],
            ],

            // ===================================================================
            // ORANGE ZONE (80-90%) — High intensity, fatigue resistance
            // ===================================================================

            [
                'name'           => 'Vitesse répétée (RSA)',
                'zone_color'     => 'orange',
                'quality_method' => 'RSA',
                'display_name'   => 'Sprints Répétés',
                'sort_order'     => 5,
                'rule' => [
                    'mets'                   => 9.5,
                    'duration'               => '30-40 min',
                    'rpe'                    => 9,
                    'freshness_24h'          => 0.25,
                    'freshness_48h'          => 0.55,
                    'freshness_72h'          => 0.90,
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.25L',
                    'supercomp_window'       => '72h',
                    'gain_prediction'        => 'Sprints répétés match',
                    'injury_risk'            => 'Élevé',
                ],
            ],
            [
                'name'           => 'Capacité anaérobie alactique',
                'zone_color'     => 'orange',
                'quality_method' => 'Cap. Anaéro. Alac.',
                'display_name'   => 'Répétition Explosive',
                'sort_order'     => 6,
                'rule' => [
                    'mets'                   => 9.0,
                    'duration'               => '30-40 min',
                    'rpe'                    => 8,
                    'freshness_24h'          => 0.30,
                    'freshness_48h'          => 0.60,
                    'freshness_72h'          => 0.90,
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.00L',
                    'supercomp_window'       => '48-72h',
                    'gain_prediction'        => 'Répéter les actions explosives',
                    'injury_risk'            => 'Moyen',
                ],
            ],
            [
                'name'           => 'Puissance anaérobie lactique',
                'zone_color'     => 'orange',
                'quality_method' => 'Puiss. Anaéro. Lact.',
                'display_name'   => 'Résistance Intense',
                'sort_order'     => 7,
                'rule' => [
                    'mets'                   => 9.0,
                    'duration'               => '30-40 min',
                    'rpe'                    => 9,
                    'freshness_24h'          => 0.25,
                    'freshness_48h'          => 0.55,
                    'freshness_72h'          => 0.85,
                    'sleep_requirement'      => '9h30',
                    'hydration'              => '1.50L',
                    'supercomp_window'       => '72h',
                    'gain_prediction'        => 'Efforts intenses prolongés',
                    'injury_risk'            => 'Élevé',
                ],
            ],
            [
                'name'           => 'HIIT',
                'zone_color'     => 'orange',
                'quality_method' => 'HIIT',
                'display_name'   => 'Haute Intensité',
                'sort_order'     => 8,
                'rule' => [
                    'mets'                   => 9.5,
                    'duration'               => '25-35 min',
                    'rpe'                    => 9,
                    'freshness_24h'          => 0.30,
                    'freshness_48h'          => 0.60,
                    'freshness_72h'          => 0.90,
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.50L',
                    'supercomp_window'       => '48-72h',
                    'gain_prediction'        => 'VO2max / Oxydation graisses',
                    'injury_risk'            => 'Moyen',
                ],
            ],
            [
                'name'           => 'HIIT long',
                'zone_color'     => 'orange',
                'quality_method' => 'HIIT Long',
                'display_name'   => 'Intensité Prolongée',
                'sort_order'     => 9,
                'rule' => [
                    'mets'                   => 8.5,
                    'duration'               => '35-50 min',
                    'rpe'                    => 8,
                    'freshness_24h'          => 0.35,
                    'freshness_48h'          => 0.65,
                    'freshness_72h'          => 0.95,
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.50L',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Endurance haute intensité',
                    'injury_risk'            => 'Moyen',
                ],
            ],
            [
                'name'           => 'Intermittent très court (5/5)',
                'zone_color'     => 'orange',
                'quality_method' => 'Intermittent 5/5',
                'display_name'   => 'Réactivité Flash',
                'sort_order'     => 10,
                'rule' => [
                    'mets'                   => 9.0,
                    'duration'               => '20-30 min',
                    'rpe'                    => 8,
                    'freshness_24h'          => 0.30,
                    'freshness_48h'          => 0.65,
                    'freshness_72h'          => 0.95,
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.25L',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Réactivité + explosivité',
                    'injury_risk'            => 'Moyen',
                ],
            ],
            [
                'name'           => 'Endurance HIIT',
                'zone_color'     => 'orange',
                'quality_method' => 'Endurance HIIT',
                'display_name'   => 'Cardio Puissant',
                'sort_order'     => 11,
                'rule' => [
                    'mets'                   => 8.5,
                    'duration'               => '30-45 min',
                    'rpe'                    => 8,
                    'freshness_24h'          => 0.35,
                    'freshness_48h'          => 0.70,
                    'freshness_72h'          => 0.95,
                    'sleep_requirement'      => '8h30',
                    'hydration'              => '1.50L',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Haute intensité répétée',
                    'injury_risk'            => 'Moyen',
                ],
            ],

            // ===================================================================
            // YELLOW ZONE (70-80%) — Match rhythm, threshold
            // ===================================================================

            [
                'name'           => 'Fractionné',
                'zone_color'     => 'yellow',
                'quality_method' => 'Fractionné',
                'display_name'   => 'Rythme Match',
                'sort_order'     => 12,
                'rule' => [
                    'mets'                   => 8.0,
                    'duration'               => '35-50 min',
                    'rpe'                    => 7,
                    'freshness_24h'          => 0.40,
                    'freshness_48h'          => 0.70,
                    'freshness_72h'          => 0.95,
                    'sleep_requirement'      => '8h30',
                    'hydration'              => '1.25L',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Endurance intensive match',
                    'injury_risk'            => 'Moyen',
                ],
            ],
            [
                'name'           => 'Résistance lactique',
                'zone_color'     => 'yellow',
                'quality_method' => 'Résistance Lactique',
                'display_name'   => 'Tolérance Acide',
                'sort_order'     => 13,
                'rule' => [
                    'mets'                   => 8.5,
                    'duration'               => '25-40 min',
                    'rpe'                    => 8,
                    'freshness_24h'          => 0.30,
                    'freshness_48h'          => 0.60,
                    'freshness_72h'          => 0.90,
                    'sleep_requirement'      => '9h',
                    'hydration'              => '1.50L',
                    'supercomp_window'       => '72h',
                    'gain_prediction'        => 'Maintenir l\'intensité sous fatigue',
                    'injury_risk'            => 'Moyen',
                ],
            ],
            [
                'name'           => 'Capacité anaérobie lactique',
                'zone_color'     => 'yellow',
                'quality_method' => 'Cap. Anaéro. Lact.',
                'display_name'   => 'Endurance de Duels',
                'sort_order'     => 14,
                'rule' => [
                    'mets'                   => 8.0,
                    'duration'               => '35-45 min',
                    'rpe'                    => 7,
                    'freshness_24h'          => 0.35,
                    'freshness_48h'          => 0.65,
                    'freshness_72h'          => 0.95,
                    'sleep_requirement'      => '8h30',
                    'hydration'              => '1.25L',
                    'supercomp_window'       => '48-72h',
                    'gain_prediction'        => 'Enchaîner les duels',
                    'injury_risk'            => 'Moyen',
                ],
            ],
            [
                'name'           => 'Intermittent court (10/10 – 15/15)',
                'zone_color'     => 'yellow',
                'quality_method' => 'Intermittent Court',
                'display_name'   => 'Rythme Élevé',
                'sort_order'     => 15,
                'rule' => [
                    'mets'                   => 8.0,
                    'duration'               => '25-35 min',
                    'rpe'                    => 7,
                    'freshness_24h'          => 0.40,
                    'freshness_48h'          => 0.70,
                    'freshness_72h'          => 0.95,
                    'sleep_requirement'      => '8h30',
                    'hydration'              => '1.25L',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Rythme élevé continu',
                    'injury_risk'            => 'Faible',
                ],
            ],
            [
                'name'           => 'Intermittent moyen (20/20 – 30/30)',
                'zone_color'     => 'yellow',
                'quality_method' => 'Intermittent Moyen',
                'display_name'   => 'Transitions Match',
                'sort_order'     => 16,
                'rule' => [
                    'mets'                   => 7.5,
                    'duration'               => '30-40 min',
                    'rpe'                    => 7,
                    'freshness_24h'          => 0.45,
                    'freshness_48h'          => 0.75,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.25L',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Transitions fréquentes',
                    'injury_risk'            => 'Faible',
                ],
            ],
            [
                'name'           => 'Seuil anaérobie 2 (SV2)',
                'zone_color'     => 'yellow',
                'quality_method' => 'Seuil SV2',
                'display_name'   => 'Seuil Haut',
                'sort_order'     => 17,
                'rule' => [
                    'mets'                   => 7.5,
                    'duration'               => '35-50 min',
                    'rpe'                    => 7,
                    'freshness_24h'          => 0.40,
                    'freshness_48h'          => 0.70,
                    'freshness_72h'          => 0.95,
                    'sleep_requirement'      => '8h30',
                    'hydration'              => '1.25L',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Soutenir haute intensité',
                    'injury_risk'            => 'Faible',
                ],
            ],

            // ===================================================================
            // GREEN ZONE (60-70%) — Fundamental endurance, aerobic base
            // ===================================================================

            [
                'name'           => 'Puissance aérobie (VO₂max)',
                'zone_color'     => 'green',
                'quality_method' => 'VO2max',
                'display_name'   => 'Moteur Aérobie',
                'sort_order'     => 18,
                'rule' => [
                    'mets'                   => 7.0,
                    'duration'               => '35-50 min',
                    'rpe'                    => 6,
                    'freshness_24h'          => 0.50,
                    'freshness_48h'          => 0.80,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.25L',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Volume + intensité match',
                    'injury_risk'            => 'Faible',
                ],
            ],
            [
                'name'           => 'Intermittent long (45/45 – 1\'/1\')',
                'zone_color'     => 'green',
                'quality_method' => 'Intermittent Long',
                'display_name'   => 'Efforts Soutenus',
                'sort_order'     => 19,
                'rule' => [
                    'mets'                   => 6.5,
                    'duration'               => '30-45 min',
                    'rpe'                    => 6,
                    'freshness_24h'          => 0.55,
                    'freshness_48h'          => 0.80,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.00L',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Efforts soutenus',
                    'injury_risk'            => 'Faible',
                ],
            ],
            [
                'name'           => 'Fartlek court',
                'zone_color'     => 'green',
                'quality_method' => 'Fartlek Court',
                'display_name'   => 'Jeu Imprévisible',
                'sort_order'     => 20,
                'rule' => [
                    'mets'                   => 7.0,
                    'duration'               => '30-45 min',
                    'rpe'                    => 6,
                    'freshness_24h'          => 0.55,
                    'freshness_48h'          => 0.80,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.00L',
                    'supercomp_window'       => '36-48h',
                    'gain_prediction'        => 'Jeu imprévisible',
                    'injury_risk'            => 'Faible',
                ],
            ],
            [
                'name'           => 'Fartlek moyen',
                'zone_color'     => 'green',
                'quality_method' => 'Fartlek Moyen',
                'display_name'   => 'Adaptation Rythme',
                'sort_order'     => 21,
                'rule' => [
                    'mets'                   => 6.5,
                    'duration'               => '35-50 min',
                    'rpe'                    => 5,
                    'freshness_24h'          => 0.60,
                    'freshness_48h'          => 0.85,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.00L',
                    'supercomp_window'       => '36h',
                    'gain_prediction'        => 'Adaptation rythme',
                    'injury_risk'            => 'Très Faible',
                ],
            ],
            [
                'name'           => 'Seuil anaérobie 1 (SV1)',
                'zone_color'     => 'green',
                'quality_method' => 'Seuil SV1',
                'display_name'   => 'Base Tactique',
                'sort_order'     => 22,
                'rule' => [
                    'mets'                   => 6.0,
                    'duration'               => '40-60 min',
                    'rpe'                    => 5,
                    'freshness_24h'          => 0.60,
                    'freshness_48h'          => 0.85,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.00L',
                    'supercomp_window'       => '36h',
                    'gain_prediction'        => 'Base endurance tactique',
                    'injury_risk'            => 'Très Faible',
                ],
            ],
            [
                'name'           => 'Endurance hybride',
                'zone_color'     => 'green',
                'quality_method' => 'Endurance Hybride',
                'display_name'   => 'Puissance + Durée',
                'sort_order'     => 23,
                'rule' => [
                    'mets'                   => 6.5,
                    'duration'               => '40-55 min',
                    'rpe'                    => 6,
                    'freshness_24h'          => 0.55,
                    'freshness_48h'          => 0.80,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.25L',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Puissance + durée',
                    'injury_risk'            => 'Faible',
                ],
            ],

            // ===================================================================
            // BLUE ZONE (50-60%) — Recovery, oxygenation, base
            // ===================================================================

            [
                'name'           => 'Endurance aérobie',
                'zone_color'     => 'blue',
                'quality_method' => 'Endurance Aérobie',
                'display_name'   => 'Base Cardio',
                'sort_order'     => 24,
                'rule' => [
                    'mets'                   => 5.0,
                    'duration'               => '30-60 min',
                    'rpe'                    => 4,
                    'freshness_24h'          => 0.75,
                    'freshness_48h'          => 0.95,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '7h30',
                    'hydration'              => '0.75L',
                    'supercomp_window'       => '24h',
                    'gain_prediction'        => 'Base cardio vasculaire',
                    'injury_risk'            => 'Nul',
                ],
            ],
            [
                'name'           => 'Capacité aérobie',
                'zone_color'     => 'blue',
                'quality_method' => 'Capacité Aérobie',
                'display_name'   => 'Endurance Générale',
                'sort_order'     => 25,
                'rule' => [
                    'mets'                   => 5.0,
                    'duration'               => '40-60 min',
                    'rpe'                    => 4,
                    'freshness_24h'          => 0.70,
                    'freshness_48h'          => 0.90,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '7h30',
                    'hydration'              => '0.75L',
                    'supercomp_window'       => '24h',
                    'gain_prediction'        => 'Endurance générale',
                    'injury_risk'            => 'Nul',
                ],
            ],
            [
                'name'           => 'Fartlek long',
                'zone_color'     => 'blue',
                'quality_method' => 'Fartlek Long',
                'display_name'   => 'Volume & Variation',
                'sort_order'     => 26,
                'rule' => [
                    'mets'                   => 5.5,
                    'duration'               => '45-70 min',
                    'rpe'                    => 4,
                    'freshness_24h'          => 0.70,
                    'freshness_48h'          => 0.90,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.00L',
                    'supercomp_window'       => '24-36h',
                    'gain_prediction'        => 'Volume + variation',
                    'injury_risk'            => 'Très Faible',
                ],
            ],
            [
                'name'           => 'Endurance fondamentale – courte',
                'zone_color'     => 'blue',
                'quality_method' => 'Endurance Fond. Courte',
                'display_name'   => 'Base Courte',
                'sort_order'     => 27,
                'rule' => [
                    'mets'                   => 4.0,
                    'duration'               => '15-25 min',
                    'rpe'                    => 3,
                    'freshness_24h'          => 0.85,
                    'freshness_48h'          => 1.00,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '7h30',
                    'hydration'              => '0.50L',
                    'supercomp_window'       => '12h',
                    'gain_prediction'        => 'Base physiologique',
                    'injury_risk'            => 'Nul',
                ],
            ],
            [
                'name'           => 'Endurance fondamentale – moyenne',
                'zone_color'     => 'blue',
                'quality_method' => 'Endurance Fond. Moyenne',
                'display_name'   => 'Volume Match',
                'sort_order'     => 28,
                'rule' => [
                    'mets'                   => 4.5,
                    'duration'               => '30-45 min',
                    'rpe'                    => 3,
                    'freshness_24h'          => 0.80,
                    'freshness_48h'          => 0.95,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '7h30',
                    'hydration'              => '0.75L',
                    'supercomp_window'       => '12-24h',
                    'gain_prediction'        => 'Volume match',
                    'injury_risk'            => 'Nul',
                ],
            ],
            [
                'name'           => 'Endurance fondamentale – longue',
                'zone_color'     => 'blue',
                'quality_method' => 'Endurance Fond. Longue',
                'display_name'   => 'Gros Moteur',
                'sort_order'     => 29,
                'rule' => [
                    'mets'                   => 4.5,
                    'duration'               => '50-75 min',
                    'rpe'                    => 3,
                    'freshness_24h'          => 0.75,
                    'freshness_48h'          => 0.90,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.00L',
                    'supercomp_window'       => '24h',
                    'gain_prediction'        => 'Gros moteur cardio',
                    'injury_risk'            => 'Très Faible',
                ],
            ],
            [
                'name'           => 'Récupération active',
                'zone_color'     => 'blue',
                'quality_method' => 'Récupération Active',
                'display_name'   => 'Régénération',
                'sort_order'     => 30,
                'rule' => [
                    'mets'                   => 3.0,
                    'duration'               => '10-30 min',
                    'rpe'                    => 2,
                    'freshness_24h'          => 0.90,
                    'freshness_48h'          => 1.00,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '7h',
                    'hydration'              => '0.50L',
                    'supercomp_window'       => '12h',
                    'gain_prediction'        => 'Régénération cellulaire',
                    'injury_risk'            => 'Nul',
                ],
            ],

            // ===================================================================
            // HOME & MOBILITY THEMES
            // ===================================================================

            [
                'name'           => 'Circuit Maison',
                'zone_color'     => 'yellow',
                'quality_method' => 'Circuit Training',
                'display_name'   => 'Circuit Maison',
                'sort_order'     => 31,
                'rule' => [
                    'mets'                   => 6.5,
                    'duration'               => '30-45 min',
                    'rpe'                    => 6,
                    'freshness_24h'          => 0.55,
                    'freshness_48h'          => 0.80,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '8h',
                    'hydration'              => '1.00L',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Condition physique générale',
                    'injury_risk'            => 'Faible',
                ],
            ],
            [
                'name'           => 'HIIT Maison',
                'zone_color'     => 'orange',
                'quality_method' => 'HIIT Maison',
                'display_name'   => 'HIIT Maison',
                'sort_order'     => 32,
                'rule' => [
                    'mets'                   => 8.0,
                    'duration'               => '25-35 min',
                    'rpe'                    => 8,
                    'freshness_24h'          => 0.35,
                    'freshness_48h'          => 0.65,
                    'freshness_72h'          => 0.95,
                    'sleep_requirement'      => '8h30',
                    'hydration'              => '1.25L',
                    'supercomp_window'       => '48h',
                    'gain_prediction'        => 'Cardio + brûlage graisse',
                    'injury_risk'            => 'Faible',
                ],
            ],
            [
                'name'           => 'Mobilité & Récupération',
                'zone_color'     => 'blue',
                'quality_method' => 'Mobilité',
                'display_name'   => 'Mobilité & Récup',
                'sort_order'     => 33,
                'rule' => [
                    'mets'                   => 2.5,
                    'duration'               => '20-35 min',
                    'rpe'                    => 2,
                    'freshness_24h'          => 0.95,
                    'freshness_48h'          => 1.00,
                    'freshness_72h'          => 1.00,
                    'sleep_requirement'      => '7h',
                    'hydration'              => '0.50L',
                    'supercomp_window'       => '12h',
                    'gain_prediction'        => 'Souplesse & Régénération',
                    'injury_risk'            => 'Nul',
                ],
            ],
        ];
    }
}
