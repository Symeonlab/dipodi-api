<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PlayerProfile;
use App\Models\WorkoutTheme;

class CardioProfileMappingSeeder extends Seeder
{
    /**
     * Seeds profile-to-cardio-theme mappings with appropriate percentages.
     *
     * The DIPODDI programme defines cardio themes per zone (blue/green/yellow/orange/red).
     * Each player profile should be mapped to cardio themes that match their
     * sport-specific demands:
     *
     * Football:
     *   - GARDIEN: explosive, short bursts → orange/red focus
     *   - DÉFENSEUR: stamina + power → green/yellow/orange mix
     *   - MILIEU: high endurance, box-to-box → green/yellow focus
     *   - ATTAQUANT: speed + explosiveness → orange/red focus
     *
     * Padel:
     *   - GAUCHE: power/smash → orange/red focus
     *   - DROITE: consistency/rallies → green/yellow focus
     *   - DEFENSE: stamina → green/yellow focus
     *   - PREVENTION/SANTE: recovery → blue/green focus
     *   - TIMING: mixed → yellow/orange focus
     *
     * Fitness:
     *   - Varies by profile goal (fat loss, power, endurance, wellness)
     *
     * Runs AFTER DipoddiCardioAndMappingsSeeder and CardioThemeEnhancementSeeder.
     */
    public function run(): void
    {
        $this->command->info('Seeding Cardio Profile-Theme mappings...');

        $profiles = PlayerProfile::all()->keyBy('name');
        $themes = WorkoutTheme::where('type', 'cardio')->get()->keyBy('name');

        $created = 0;
        $skipped = 0;

        foreach ($this->getMappings() as [$themeName, $profileName, $percentage]) {
            $theme = $themes->get($themeName);
            if (!$theme) {
                // Try fuzzy match
                $theme = WorkoutTheme::where('name', 'like', "%{$themeName}%")
                    ->where('type', 'cardio')
                    ->first();
            }
            if (!$theme) {
                $this->command->warn("  Cardio theme not found: {$themeName}");
                $skipped++;
                continue;
            }

            $profile = $profiles->get($profileName);
            if (!$profile) {
                $this->command->warn("  Profile not found: {$profileName}");
                $skipped++;
                continue;
            }

            // Avoid duplicate
            $exists = DB::table('player_profile_themes')
                ->where('player_profile_id', $profile->id)
                ->where('workout_theme_id', $theme->id)
                ->exists();

            if (!$exists) {
                DB::table('player_profile_themes')->insert([
                    'player_profile_id' => $profile->id,
                    'workout_theme_id' => $theme->id,
                    'percentage' => $percentage,
                ]);
                $created++;
            } else {
                $skipped++;
            }
        }

        $this->command->info("  Created {$created} cardio profile-theme mappings, {$skipped} skipped");
    }

    /**
     * Returns [theme_name, profile_name, percentage] tuples.
     *
     * Percentages are relative weights (not meant to sum to 100%).
     * Higher percentage = higher probability of being selected.
     */
    private function getMappings(): array
    {
        return [
            // ════════════════════════════════════════════════════════════════
            // FOOTBALL — GARDIEN (explosive, short bursts, reflexes)
            // Focus: red/orange zones for explosive power, some yellow for conditioning
            // ════════════════════════════════════════════════════════════════

            // La Panthère – explosive goalkeeper, spectacular saves
            ['Vitesse pure', 'La Panthère', 25],
            ['Puissance explosive', 'La Panthère', 20],
            ['HIIT', 'La Panthère', 20],
            ['Vitesse répétée (RSA)', 'La Panthère', 15],
            ['Intermittent court (10/10 – 15/15)', 'La Panthère', 10],
            ['Récupération active', 'La Panthère', 10],

            // La Pieuvre – agile, covers the net, reflexes
            ['HIIT', 'La Pieuvre', 20],
            ['Intermittent court (10/10 – 15/15)', 'La Pieuvre', 20],
            ['Intermittent très court (5/5)', 'La Pieuvre', 15],
            ['Vitesse répétée (RSA)', 'La Pieuvre', 15],
            ['Fractionné', 'La Pieuvre', 15],
            ['Récupération active', 'La Pieuvre', 15],

            // Le Chat – agile, quick reflexes, leaps
            ['Vitesse pure', 'Le Chat', 20],
            ['Puissance explosive', 'Le Chat', 20],
            ['Tabata', 'Le Chat', 15],
            ['HIIT', 'Le Chat', 15],
            ['Intermittent très court (5/5)', 'Le Chat', 15],
            ['Récupération active', 'Le Chat', 15],

            // L'Araignée – dominant in the air, imposing
            ['HIIT', "L'Araignée", 20],
            ['Puissance explosive', "L'Araignée", 20],
            ['Vitesse répétée (RSA)', "L'Araignée", 15],
            ['Intermittent court (10/10 – 15/15)', "L'Araignée", 15],
            ['Fractionné', "L'Araignée", 15],
            ['Récupération active', "L'Araignée", 15],

            // ════════════════════════════════════════════════════════════════
            // FOOTBALL — DÉFENSEUR (stamina + power, repeated efforts)
            // Focus: green/yellow/orange zones for mixed endurance + power
            // ════════════════════════════════════════════════════════════════

            // Le Contrôleur – controls tempo, positioning
            ['Endurance aérobie', 'Le Contrôleur', 20],
            ['Fartlek moyen', 'Le Contrôleur', 20],
            ['Intermittent long (45/45 – 1\'/1\')', 'Le Contrôleur', 15],
            ['Seuil anaérobie 1 (SV1)', 'Le Contrôleur', 15],
            ['Fractionné', 'Le Contrôleur', 15],
            ['Récupération active', 'Le Contrôleur', 15],

            // Le Casseur – powerful, physical, breaks plays
            ['HIIT', 'Le Casseur', 20],
            ['Vitesse répétée (RSA)', 'Le Casseur', 20],
            ['Intermittent moyen (20/20 – 30/30)', 'Le Casseur', 15],
            ['Fractionné', 'Le Casseur', 15],
            ['Puissance aérobie (VO₂max)', 'Le Casseur', 15],
            ['Récupération active', 'Le Casseur', 15],

            // Le Relanceur – launches attacks, stamina
            ['Fartlek court', 'Le Relanceur', 20],
            ['Endurance hybride', 'Le Relanceur', 20],
            ['Intermittent long (45/45 – 1\'/1\')', 'Le Relanceur', 15],
            ['Seuil anaérobie 2 (SV2)', 'Le Relanceur', 15],
            ['Puissance aérobie (VO₂max)', 'Le Relanceur', 15],
            ['Récupération active', 'Le Relanceur', 15],

            // Le Polyvalent – versatile, all-round
            ['HIIT', 'Le Polyvalent', 15],
            ['Fartlek moyen', 'Le Polyvalent', 15],
            ['Intermittent moyen (20/20 – 30/30)', 'Le Polyvalent', 15],
            ['Fractionné', 'Le Polyvalent', 15],
            ['Puissance aérobie (VO₂max)', 'Le Polyvalent', 15],
            ['Endurance aérobie', 'Le Polyvalent', 10],
            ['Récupération active', 'Le Polyvalent', 15],

            // ════════════════════════════════════════════════════════════════
            // FOOTBALL — MILIEU (high endurance, box-to-box)
            // Focus: green/yellow zones for sustained endurance, some orange
            // ════════════════════════════════════════════════════════════════

            // L'Architecte – builds play, high work-rate
            ['Puissance aérobie (VO₂max)', "L'Architecte", 20],
            ['Intermittent long (45/45 – 1\'/1\')', "L'Architecte", 20],
            ['Endurance hybride', "L'Architecte", 15],
            ['Seuil anaérobie 2 (SV2)', "L'Architecte", 15],
            ['Fartlek moyen', "L'Architecte", 15],
            ['Récupération active', "L'Architecte", 15],

            // The Rock – powerful midfielder, physical
            ['HIIT', 'The Rock', 20],
            ['Intermittent moyen (20/20 – 30/30)', 'The Rock', 20],
            ['Fractionné', 'The Rock', 15],
            ['Puissance aérobie (VO₂max)', 'The Rock', 15],
            ['Fartlek court', 'The Rock', 15],
            ['Récupération active', 'The Rock', 15],

            // Le Pitbull – aggressive, high pressing, intense
            ['HIIT', 'Le Pitbull', 20],
            ['Vitesse répétée (RSA)', 'Le Pitbull', 20],
            ['Intermittent court (10/10 – 15/15)', 'Le Pitbull', 15],
            ['Fractionné', 'Le Pitbull', 15],
            ['Intermittent très court (5/5)', 'Le Pitbull', 15],
            ['Récupération active', 'Le Pitbull', 15],

            // La Gazelle – fast, elegant, covers ground
            ['Fartlek court', 'La Gazelle', 20],
            ['Fartlek moyen', 'La Gazelle', 15],
            ['Intermittent long (45/45 – 1\'/1\')', 'La Gazelle', 15],
            ['Endurance hybride', 'La Gazelle', 15],
            ['Seuil anaérobie 1 (SV1)', 'La Gazelle', 15],
            ['Endurance aérobie', 'La Gazelle', 10],
            ['Récupération active', 'La Gazelle', 10],

            // ════════════════════════════════════════════════════════════════
            // FOOTBALL — ATTAQUANT (speed + explosiveness)
            // Focus: orange/red zones for speed and explosive capacity
            // ════════════════════════════════════════════════════════════════

            // Le Magicien – creative, dribbles, quick feet
            ['Intermittent très court (5/5)', 'Le Magicien', 20],
            ['Intermittent court (10/10 – 15/15)', 'Le Magicien', 20],
            ['HIIT', 'Le Magicien', 15],
            ['Fartlek court', 'Le Magicien', 15],
            ['Tabata', 'Le Magicien', 15],
            ['Récupération active', 'Le Magicien', 15],

            // Le Sniper – precision, finisher, quick transitions
            ['Vitesse pure', 'Le Sniper', 20],
            ['Vitesse répétée (RSA)', 'Le Sniper', 20],
            ['HIIT', 'Le Sniper', 15],
            ['Puissance explosive', 'Le Sniper', 15],
            ['Intermittent court (10/10 – 15/15)', 'Le Sniper', 15],
            ['Récupération active', 'Le Sniper', 15],

            // Le Tank – powerful, physical, aerial
            ['HIIT long', 'Le Tank', 20],
            ['Puissance anaérobie lactique', 'Le Tank', 15],
            ['Vitesse répétée (RSA)', 'Le Tank', 15],
            ['HIIT', 'Le Tank', 15],
            ['Endurance HIIT', 'Le Tank', 15],
            ['Récupération active', 'Le Tank', 20],

            // Le Renard – smart positioning, opportunistic
            ['Vitesse pure', 'Le Renard', 20],
            ['HIIT', 'Le Renard', 20],
            ['Intermittent court (10/10 – 15/15)', 'Le Renard', 15],
            ['Capacité anaérobie alactique', 'Le Renard', 15],
            ['Fractionné', 'Le Renard', 15],
            ['Récupération active', 'Le Renard', 15],

            // ════════════════════════════════════════════════════════════════
            // PADEL — GAUCHE (power, smash, explosive)
            // Focus: orange/red zones for explosive power
            // ════════════════════════════════════════════════════════════════

            // Le Smasheur – powerful overhead shots
            ['Vitesse pure', 'Le Smasheur', 20],
            ['Puissance explosive', 'Le Smasheur', 20],
            ['Tabata', 'Le Smasheur', 15],
            ['HIIT', 'Le Smasheur', 15],
            ['Intermittent très court (5/5)', 'Le Smasheur', 15],
            ['Récupération active', 'Le Smasheur', 15],

            // L'Aérien – aerial play, jumping
            ['Puissance explosive', "L'Aérien", 20],
            ['HIIT', "L'Aérien", 20],
            ['Vitesse répétée (RSA)', "L'Aérien", 15],
            ['Intermittent court (10/10 – 15/15)', "L'Aérien", 15],
            ['Puissance anaérobie alactique', "L'Aérien", 15],
            ['Récupération active', "L'Aérien", 15],

            // Le Joueur Lourd – powerful, physical
            ['HIIT long', 'Le Joueur Lourd', 20],
            ['Endurance HIIT', 'Le Joueur Lourd', 20],
            ['HIIT', 'Le Joueur Lourd', 15],
            ['Puissance anaérobie lactique', 'Le Joueur Lourd', 15],
            ['Récupération active', 'Le Joueur Lourd', 30],

            // ════════════════════════════════════════════════════════════════
            // PADEL — DROITE (consistency, rallies, endurance)
            // Focus: green/yellow zones for sustained endurance
            // ════════════════════════════════════════════════════════════════

            // Le Marathonien – long rallies, tireless
            ['Endurance aérobie', 'Le Marathonien', 20],
            ['Fartlek moyen', 'Le Marathonien', 20],
            ['Intermittent long (45/45 – 1\'/1\')', 'Le Marathonien', 15],
            ['Seuil anaérobie 1 (SV1)', 'Le Marathonien', 15],
            ['Endurance hybride', 'Le Marathonien', 15],
            ['Récupération active', 'Le Marathonien', 15],

            // Le Métronome – consistent, rhythmic
            ['Fartlek court', 'Le Métronome', 20],
            ['Intermittent moyen (20/20 – 30/30)', 'Le Métronome', 20],
            ['Fractionné', 'Le Métronome', 15],
            ['Puissance aérobie (VO₂max)', 'Le Métronome', 15],
            ['Endurance aérobie', 'Le Métronome', 15],
            ['Récupération active', 'Le Métronome', 15],

            // Le Stressé – needs calming, anxiety management
            ['Endurance aérobie', 'Le Stressé', 25],
            ['Fartlek long', 'Le Stressé', 20],
            ['Capacité aérobie', 'Le Stressé', 15],
            ['Seuil anaérobie 1 (SV1)', 'Le Stressé', 15],
            ['Récupération active', 'Le Stressé', 25],

            // ════════════════════════════════════════════════════════════════
            // PADEL — DEFENSE (stamina, repeated sprints)
            // Focus: green/yellow zones
            // ════════════════════════════════════════════════════════════════

            // Le Défenseur – stamina, lateral movement
            ['Intermittent moyen (20/20 – 30/30)', 'Le Défenseur', 20],
            ['Fartlek moyen', 'Le Défenseur', 20],
            ['Endurance hybride', 'Le Défenseur', 15],
            ['Fractionné', 'Le Défenseur', 15],
            ['Puissance aérobie (VO₂max)', 'Le Défenseur', 15],
            ['Récupération active', 'Le Défenseur', 15],

            // ════════════════════════════════════════════════════════════════
            // PADEL — PREVENTION & SANTE (recovery-focused)
            // Focus: blue/green zones
            // ════════════════════════════════════════════════════════════════

            // Le Fragile – injury-prone, needs careful progression
            ['Endurance aérobie', 'Le Fragile', 25],
            ['Récupération active', 'Le Fragile', 25],
            ['Capacité aérobie', 'Le Fragile', 20],
            ['Endurance fondamentale – courte', 'Le Fragile', 15],
            ['Fartlek long', 'Le Fragile', 15],

            // Le Vétéran – experienced, needs maintenance
            ['Endurance aérobie', 'Le Vétéran', 25],
            ['Récupération active', 'Le Vétéran', 25],
            ['Fartlek long', 'Le Vétéran', 15],
            ['Capacité aérobie', 'Le Vétéran', 15],
            ['Endurance fondamentale – moyenne', 'Le Vétéran', 10],
            ['Seuil anaérobie 1 (SV1)', 'Le Vétéran', 10],

            // ════════════════════════════════════════════════════════════════
            // PADEL — TIMING (reaction, rhythm)
            // Focus: yellow/orange zones
            // ════════════════════════════════════════════════════════════════

            // Le Matinal – disciplined, morning person
            ['Fractionné', 'Le Matinal', 20],
            ['Intermittent moyen (20/20 – 30/30)', 'Le Matinal', 20],
            ['HIIT', 'Le Matinal', 15],
            ['Fartlek court', 'Le Matinal', 15],
            ['Puissance aérobie (VO₂max)', 'Le Matinal', 15],
            ['Récupération active', 'Le Matinal', 15],

            // ════════════════════════════════════════════════════════════════
            // FITNESS — FEMME
            // Cardio themes matched to training goals
            // ════════════════════════════════════════════════════════════════

            // La Silhouette – refined shape, fat loss
            ['HIIT', 'La Silhouette', 25],
            ['Endurance HIIT', 'La Silhouette', 20],
            ['Fractionné', 'La Silhouette', 15],
            ['Endurance aérobie', 'La Silhouette', 15],
            ['Fartlek moyen', 'La Silhouette', 15],
            ['Récupération active', 'La Silhouette', 10],

            // La Tonique – dynamic, energetic
            ['HIIT', 'La Tonique', 20],
            ['Fartlek court', 'La Tonique', 20],
            ['Intermittent moyen (20/20 – 30/30)', 'La Tonique', 15],
            ['Endurance hybride', 'La Tonique', 15],
            ['Puissance aérobie (VO₂max)', 'La Tonique', 15],
            ['Récupération active', 'La Tonique', 15],

            // La Fine – light, fluidity, gentle
            ['Endurance aérobie', 'La Fine', 25],
            ['Fartlek long', 'La Fine', 20],
            ['Capacité aérobie', 'La Fine', 15],
            ['Endurance fondamentale – moyenne', 'La Fine', 15],
            ['Récupération active', 'La Fine', 25],

            // L'Athlète Puissante – strong, powerful
            ['HIIT', "L'Athlète Puissante", 20],
            ['Tabata', "L'Athlète Puissante", 20],
            ['Intermittent court (10/10 – 15/15)', "L'Athlète Puissante", 15],
            ['Vitesse répétée (RSA)', "L'Athlète Puissante", 15],
            ['Puissance aérobie (VO₂max)', "L'Athlète Puissante", 15],
            ['Récupération active', "L'Athlète Puissante", 15],

            // Bien-être – balanced, serenity
            ['Endurance aérobie', 'Bien-être', 25],
            ['Récupération active', 'Bien-être', 25],
            ['Fartlek long', 'Bien-être', 15],
            ['Capacité aérobie', 'Bien-être', 15],
            ['Endurance fondamentale – longue', 'Bien-être', 10],
            ['Seuil anaérobie 1 (SV1)', 'Bien-être', 10],

            // ════════════════════════════════════════════════════════════════
            // FITNESS — HOMME
            // Cardio themes matched to training goals
            // ════════════════════════════════════════════════════════════════

            // L'Athlétique – balanced, sporty
            ['Fartlek court', "L'Athlétique", 20],
            ['HIIT', "L'Athlétique", 15],
            ['Intermittent moyen (20/20 – 30/30)', "L'Athlétique", 15],
            ['Puissance aérobie (VO₂max)', "L'Athlétique", 15],
            ['Endurance hybride', "L'Athlétique", 15],
            ['Récupération active', "L'Athlétique", 20],

            // Le Massif – imposing build, muscular
            ['HIIT', 'Le Massif', 20],
            ['Vitesse répétée (RSA)', 'Le Massif', 20],
            ['Tabata', 'Le Massif', 15],
            ['Puissance anaérobie lactique', 'Le Massif', 15],
            ['Récupération active', 'Le Massif', 30],

            // Le Sec – lean, defined body
            ['HIIT long', 'Le Sec', 20],
            ['Endurance HIIT', 'Le Sec', 20],
            ['Fractionné', 'Le Sec', 15],
            ['Fartlek moyen', 'Le Sec', 15],
            ['Endurance aérobie', 'Le Sec', 15],
            ['Récupération active', 'Le Sec', 15],

            // Le Fonctionnel – practical, movement-based
            ['Fartlek moyen', 'Le Fonctionnel', 20],
            ['Intermittent moyen (20/20 – 30/30)', 'Le Fonctionnel', 20],
            ['Endurance hybride', 'Le Fonctionnel', 15],
            ['Puissance aérobie (VO₂max)', 'Le Fonctionnel', 15],
            ['Endurance aérobie', 'Le Fonctionnel', 15],
            ['Récupération active', 'Le Fonctionnel', 15],

            // Le Force Brute – robust, dominant
            ['HIIT', 'Le Force Brute', 20],
            ['Tabata', 'Le Force Brute', 20],
            ['Vitesse répétée (RSA)', 'Le Force Brute', 15],
            ['Puissance explosive', 'Le Force Brute', 15],
            ['Intermittent très court (5/5)', 'Le Force Brute', 15],
            ['Récupération active', 'Le Force Brute', 15],
        ];
    }
}
