<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlayerProfile;
use App\Models\Exercise;
use App\Models\WorkoutTheme;
use App\Models\WorkoutThemeRule;
use Illuminate\Support\Facades\DB;

class DipoddiCardioAndMappingsSeeder extends Seeder
{
    /**
     * Seed additional CARDIO themes and profile-theme mappings from DIPODDI_PROGRAMME.xlsx
     */
    public function run(): void
    {
        $this->command->info('Starting DIPODDI Cardio & Mappings import...');

        $this->seedCardioThemes();
        $this->seedCardioExercises();
        $this->seedProfileThemeMappings();

        $this->command->info('DIPODDI Cardio & Mappings import completed!');
    }

    private function seedCardioThemes(): void
    {
        $this->command->info('Seeding additional CARDIO themes...');

        $cardioThemes = [
            // VITESSE & PUISSANCE
            ['name' => 'Vitesse pure', 'type' => 'cardio', 'sets' => '2–4', 'reps' => '3–6', 'effort' => '3–7 s', 'recovery' => '2–4 min', 'justification' => 'Réactivité, accélération instantanée'],
            ['name' => 'Vitesse répétée (RSA)', 'type' => 'cardio', 'sets' => '2–4', 'reps' => '5–10', 'effort' => '4–8 s', 'recovery' => '20–40 s', 'justification' => 'Sprints répétés match'],
            ['name' => 'Puissance explosive', 'type' => 'cardio', 'sets' => '3–5', 'reps' => '3–6', 'effort' => '1–5 s', 'recovery' => '2–4 min', 'justification' => 'Sauts, duels, frappes'],

            // ANAÉROBIE
            ['name' => 'Puissance anaérobie alactique', 'type' => 'cardio', 'sets' => '3–6', 'reps' => '3–6', 'effort' => '5–10 s', 'recovery' => '1–3 min', 'justification' => 'Actions maximales très courtes'],
            ['name' => 'Capacité anaérobie alactique', 'type' => 'cardio', 'sets' => '3–5', 'reps' => '6–10', 'effort' => '6–10 s', 'recovery' => '30–60 s', 'justification' => 'Répéter les actions explosives'],
            ['name' => 'Puissance anaérobie lactique', 'type' => 'cardio', 'sets' => '2–4', 'reps' => '4–8', 'effort' => '15–30 s', 'recovery' => '1–2 min', 'justification' => 'Efforts intenses prolongés'],
            ['name' => 'Capacité anaérobie lactique', 'type' => 'cardio', 'sets' => '2–3', 'reps' => '6–10', 'effort' => '30–60 s', 'recovery' => '2–4 min', 'justification' => 'Enchaîner les duels'],
            ['name' => 'Résistance lactique', 'type' => 'cardio', 'sets' => '2–4', 'reps' => '3–6', 'effort' => '20–45 s', 'recovery' => '1–3 min', 'justification' => 'Maintenir l\'intensité'],

            // AÉROBIE
            ['name' => 'Puissance aérobie (VO₂max)', 'type' => 'cardio', 'sets' => '2–4', 'reps' => '6–12', 'effort' => '15–30 s', 'recovery' => '15–30 s', 'justification' => 'Volume + intensité match'],
            ['name' => 'Capacité aérobie', 'type' => 'cardio', 'sets' => '1–3', 'reps' => '8–20', 'effort' => '30–90 s', 'recovery' => '30–90 s', 'justification' => 'Endurance générale'],

            // INTERMITTENT
            ['name' => 'Intermittent très court (5/5)', 'type' => 'cardio', 'sets' => '2–4', 'reps' => '10–20', 'effort' => '5 s', 'recovery' => '5 s', 'justification' => 'Réactivité + explosivité'],
            ['name' => 'Intermittent court (10/10 – 15/15)', 'type' => 'cardio', 'sets' => '2–4', 'reps' => '8–15', 'effort' => '10–15 s', 'recovery' => '10–15 s', 'justification' => 'Rythme élevé'],
            ['name' => 'Intermittent moyen (20/20 – 30/30)', 'type' => 'cardio', 'sets' => '2–3', 'reps' => '6–12', 'effort' => '20–30 s', 'recovery' => '20–30 s', 'justification' => 'Transitions fréquentes'],
            ['name' => 'Intermittent long (45/45 – 1\'/1\')', 'type' => 'cardio', 'sets' => '1–3', 'reps' => '4–8', 'effort' => '45–60 s', 'recovery' => '45–60 s', 'justification' => 'Efforts soutenus'],

            // HIIT & FARTLEK
            ['name' => 'Endurance HIIT', 'type' => 'cardio', 'sets' => '2–4', 'reps' => '6–15', 'effort' => '20–60 s', 'recovery' => '20–60 s', 'justification' => 'Haute intensité répétée'],
            ['name' => 'Fartlek court', 'type' => 'cardio', 'sets' => '1', 'reps' => 'Continu', 'effort' => '45 s - 1 min', 'recovery' => '15 s – 1 min', 'justification' => 'Jeu imprévisible'],
            ['name' => 'Fartlek moyen', 'type' => 'cardio', 'sets' => '1', 'reps' => 'Continu', 'effort' => '1 - 3 min', 'recovery' => '30 s – 2 min', 'justification' => 'Adaptation rythme'],
            ['name' => 'Fartlek long', 'type' => 'cardio', 'sets' => '1', 'reps' => 'Continu', 'effort' => '4 - 10 min', 'recovery' => '2 – 5 min', 'justification' => 'Volume + variation'],

            // SEUIL
            ['name' => 'Seuil anaérobie 2 (SV2)', 'type' => 'cardio', 'sets' => '1–2', 'reps' => '2–4', 'effort' => '4–8 min', 'recovery' => '2–4 min', 'justification' => 'Soutenir haute intensité'],
            ['name' => 'Seuil anaérobie 1 (SV1)', 'type' => 'cardio', 'sets' => '1–2', 'reps' => '1–3', 'effort' => '10–20 min', 'recovery' => '2–5 min', 'justification' => 'Base endurance tactique'],

            // ENDURANCE
            ['name' => 'Endurance hybride', 'type' => 'cardio', 'sets' => '2–4', 'reps' => '6–12', 'effort' => '30–90 s', 'recovery' => '30–90 s', 'justification' => 'Puissance + durée'],
            ['name' => 'Endurance fondamentale – courte', 'type' => 'cardio', 'sets' => '1', 'reps' => 'Continu', 'effort' => '15–25 min', 'recovery' => '—', 'justification' => 'Base physiologique'],
            ['name' => 'Endurance fondamentale – moyenne', 'type' => 'cardio', 'sets' => '1', 'reps' => 'Continu', 'effort' => '30–45 min', 'recovery' => '—', 'justification' => 'Volume match'],
            ['name' => 'Endurance fondamentale – longue', 'type' => 'cardio', 'sets' => '1', 'reps' => 'Continu', 'effort' => '50–75 min', 'recovery' => '—', 'justification' => 'Gros moteur'],
            ['name' => 'Récupération active', 'type' => 'cardio', 'sets' => '1', 'reps' => 'Continu', 'effort' => '10–30 min', 'recovery' => '—', 'justification' => 'Régénération'],

            // SPECIAL
            ['name' => 'HIIT long', 'type' => 'cardio', 'sets' => '2–4', 'reps' => '4–10', 'effort' => '1–4 min', 'recovery' => '1–3 min', 'justification' => 'Intensité prolongée'],
            ['name' => 'Tabata', 'type' => 'cardio', 'sets' => '1–3', 'reps' => '8', 'effort' => '20 s', 'recovery' => '10 s', 'justification' => 'Pic intensité'],
        ];

        $created = 0;
        foreach ($cardioThemes as $themeData) {
            // Check if theme already exists
            $existing = WorkoutTheme::where('name', $themeData['name'])->first();
            if ($existing) {
                continue;
            }

            $theme = WorkoutTheme::create([
                'name' => $themeData['name'],
                'type' => $themeData['type'],
            ]);

            WorkoutThemeRule::create([
                'workout_theme_id' => $theme->id,
                'exercise_count' => 1,
                'sets' => $themeData['sets'],
                'reps' => $themeData['reps'],
                'recovery_time' => $themeData['recovery'],
                'load_type' => $themeData['effort'],
            ]);

            $created++;
        }

        $this->command->info("  Created {$created} new cardio themes");
    }

    private function seedCardioExercises(): void
    {
        $this->command->info('Seeding CARDIO indoor exercises...');

        $cardioExercises = [
            // CARDIO EN SALLE (Indoor)
            ['name' => 'Tapis Footing Endurance', 'category' => 'CARDIO', 'sub_category' => 'TAPIS', 'video_url' => 'https://youtube.com/shorts/yQuD5T7MSIY', 'met_value' => 7.0],
            ['name' => 'Tapis Fractionné Court 5/5', 'category' => 'CARDIO', 'sub_category' => 'TAPIS', 'video_url' => 'https://youtube.com/shorts/1VFwn0chdOM', 'met_value' => 10.0],
            ['name' => 'Tapis Fractionné Long 2min/45s', 'category' => 'CARDIO', 'sub_category' => 'TAPIS', 'video_url' => 'https://youtube.com/shorts/1VFwn0chdOM', 'met_value' => 9.0],
            ['name' => 'Tapis Intensif Fartlek', 'category' => 'CARDIO', 'sub_category' => 'TAPIS', 'video_url' => 'https://youtube.com/shorts/KxiO7DfDuO4', 'met_value' => 11.0],
            ['name' => 'Sprint en Côte 7s', 'category' => 'CARDIO', 'sub_category' => 'SPRINT', 'video_url' => 'https://youtube.com/shorts/xW0n7BhD80A', 'met_value' => 14.0],
            ['name' => 'Vélo Endurance', 'category' => 'CARDIO', 'sub_category' => 'VÉLO', 'video_url' => 'https://youtube.com/shorts/IL2fF-2G1SA', 'met_value' => 6.0],
            ['name' => 'Vélo Fractionné 5/5', 'category' => 'CARDIO', 'sub_category' => 'VÉLO', 'video_url' => 'https://youtube.com/shorts/0G6vdBt6uys', 'met_value' => 10.0],
            ['name' => 'Elliptique Endurance', 'category' => 'CARDIO', 'sub_category' => 'ELLIPTIQUE', 'video_url' => 'https://youtube.com/shorts/HwZQ9Cz2Igo', 'met_value' => 7.0],
            ['name' => 'Elliptique Intensif', 'category' => 'CARDIO', 'sub_category' => 'ELLIPTIQUE', 'video_url' => 'https://youtube.com/shorts/60ktrN58nhg', 'met_value' => 9.0],
            ['name' => 'Rameur Endurance', 'category' => 'CARDIO', 'sub_category' => 'RAMEUR', 'video_url' => 'https://youtube.com/shorts/PqkRs9SLU8U', 'met_value' => 7.5],
        ];

        $created = 0;
        $updated = 0;
        foreach ($cardioExercises as $exercise) {
            // Check if exercise already exists by video_url (strip ?si= tracking params)
            $baseUrl = strtok($exercise['video_url'], '?');

            // First check by exact name (for exercises sharing the same video URL)
            $existingByName = Exercise::where('name', $exercise['name'])->first();
            if ($existingByName) {
                // Already exists with same name — update it
                $existingByName->update($exercise);
                $updated++;
                continue;
            }

            // Check by video URL match (to upgrade generic "Cardio exercice N" entries)
            $existingByUrl = Exercise::where('video_url', $exercise['video_url'])
                ->orWhere('video_url', 'LIKE', $baseUrl . '%')
                ->first();

            if ($existingByUrl && str_starts_with($existingByUrl->name, 'Cardio exercice')) {
                // Upgrade the generic exercise with the proper name, sub_category, and met_value
                $existingByUrl->update([
                    'name' => $exercise['name'],
                    'sub_category' => $exercise['sub_category'],
                    'video_url' => $exercise['video_url'],
                    'met_value' => $exercise['met_value'],
                ]);
                $updated++;
            } elseif ($existingByUrl) {
                // URL exists but already has a proper name — create as a new exercise
                // (e.g., two different exercises share the same demo video)
                Exercise::create($exercise);
                $created++;
            } else {
                // No match at all — create new
                Exercise::create($exercise);
                $created++;
            }
        }

        $this->command->info("  Updated {$updated} existing cardio exercises, created {$created} new ones");
    }

    private function seedProfileThemeMappings(): void
    {
        $this->command->info('Seeding Profile-Theme mappings...');

        // Get all profiles
        $profiles = PlayerProfile::all()->keyBy('name');
        $themes = WorkoutTheme::all()->keyBy('name');

        // Profile-Theme mappings with percentage weights
        // Format: theme_name => [profile_name => percentage]
        $mappings = [
            // GYM THEMES - FOOTBALL
            'Force maximale' => [
                'La Panthère' => 30, 'L\'Araignée' => 25, 'Le Casseur' => 20, 'The Rock' => 15, 'Le Tank' => 10,
                // Padel
                'Le Joueur Lourd' => 60, 'Le Smasheur' => 40,
            ],
            'Force sous-maximale' => [
                'Le Polyvalent' => 30, 'Le Pitbull' => 30, 'L\'Architecte' => 40,
                'Le Défenseur' => 50, 'Le Marathonien' => 50,
            ],
            'Force dynamique' => [
                'Le Chat' => 25, 'Le Relanceur' => 25, 'La Gazelle' => 25, 'Le Renard' => 25,
                'Le Métronome' => 50, 'Le Matinal' => 50,
            ],
            'Force explosive' => [
                'La Panthère' => 30, 'Le Chat' => 20, 'Le Sniper' => 25, 'Le Renard' => 25,
                'Le Smasheur' => 60, 'L\'Aérien' => 40,
            ],
            'Puissance musculaire' => [
                'L\'Araignée' => 30, 'The Rock' => 35, 'Le Tank' => 35,
                'Le Smasheur' => 50, 'Le Joueur Lourd' => 50,
            ],
            'Hypertrophie myofibrillaire' => [
                'Le Casseur' => 35, 'The Rock' => 35, 'Le Tank' => 30,
                'Le Joueur Lourd' => 60, 'Le Smasheur' => 40,
            ],
            'Hypertrophie sarcoplasmique' => [
                'Le Polyvalent' => 50, 'Le Pitbull' => 50,
                'Le Stressé' => 100,
            ],
            'Volume musculaire' => [
                'Le Casseur' => 50, 'Le Tank' => 50,
                'Le Joueur Lourd' => 100,
            ],
            'Endurance de force' => [
                'Le Polyvalent' => 30, 'Le Pitbull' => 35, 'L\'Architecte' => 35,
                'Le Défenseur' => 50, 'Le Marathonien' => 50,
            ],
            'Endurance musculaire' => [
                'Le Contrôleur' => 50, 'L\'Architecte' => 50,
                'Le Défenseur' => 50, 'Le Métronome' => 50,
            ],
            'Perte de poids' => [
                'Le Tank' => 50, 'The Rock' => 50,
                'Le Stressé' => 50, 'Le Joueur Lourd' => 50,
            ],
            'Sèche / définition musculaire' => [
                'Le Renard' => 33, 'Le Sniper' => 33, 'La Gazelle' => 34,
                'Le Stressé' => 50, 'Le Matinal' => 50,
            ],
            'Condition physique générale' => [
                // All profiles get this equally
                'La Panthère' => 5, 'La Pieuvre' => 5, 'Le Chat' => 5, 'L\'Araignée' => 5,
                'Le Contrôleur' => 5, 'Le Casseur' => 5, 'Le Relanceur' => 5, 'Le Polyvalent' => 5,
                'L\'Architecte' => 5, 'The Rock' => 5, 'Le Pitbull' => 5, 'La Gazelle' => 5,
                'Le Magicien' => 5, 'Le Sniper' => 5, 'Le Tank' => 5, 'Le Renard' => 5,
            ],
            'Prévention des blessures' => [
                'La Panthère' => 25, 'La Pieuvre' => 25, 'Le Chat' => 25, 'L\'Araignée' => 25,
                'Le Fragile' => 50, 'Le Vétéran' => 50,
            ],
            'Coordination / proprioception' => [
                'Le Chat' => 33, 'L\'Architecte' => 33, 'Le Magicien' => 34,
                'Le Métronome' => 50, 'Le Défenseur' => 50,
            ],

        ];

        // FITNESS profile-theme mappings as flat tuples [theme, profile, percentage]
        // Using tuples to avoid PHP duplicate key issues in associative arrays
        $fitnessMappings = [
            // FITNESS FEMME - La Silhouette (refined shape, visual balance)
            ['Perte de poids', 'La Silhouette', 35],
            ['Sèche / définition musculaire', 'La Silhouette', 30],
            ['Endurance musculaire', 'La Silhouette', 20],
            ['Condition physique générale', 'La Silhouette', 15],

            // FITNESS FEMME - La Tonique (dynamic, energetic, loves movement)
            ['Condition physique générale', 'La Tonique', 25],
            ['Endurance de force', 'La Tonique', 25],
            ['Perte de poids', 'La Tonique', 25],
            ['Hypertrophie sarcoplasmique', 'La Tonique', 25],

            // FITNESS FEMME - La Fine (light, delicate, fluidity and control)
            ['Endurance musculaire', 'La Fine', 30],
            ['Coordination / proprioception', 'La Fine', 30],
            ['Prévention des blessures', 'La Fine', 20],
            ['Remise en forme', 'La Fine', 20],

            // FITNESS FEMME - L'Athlète Puissante (strong, powerful, athletic)
            ['Force maximale', 'L\'Athlète Puissante', 30],
            ['Force explosive', 'L\'Athlète Puissante', 25],
            ['Puissance musculaire', 'L\'Athlète Puissante', 25],
            ['Hypertrophie myofibrillaire', 'L\'Athlète Puissante', 20],

            // FITNESS FEMME - Bien-être (balanced, serenity, body comfort)
            ['Remise en forme', 'Bien-être', 30],
            ['Prévention des blessures', 'Bien-être', 25],
            ['Coordination / proprioception', 'Bien-être', 25],
            ['Condition physique générale', 'Bien-être', 20],

            // FITNESS HOMME - L'Athlétique (balanced, sporty, power + mobility + endurance)
            ['Condition physique générale', 'L\'Athlétique', 25],
            ['Force dynamique', 'L\'Athlétique', 25],
            ['Endurance de force', 'L\'Athlétique', 25],
            ['Hypertrophie sarcoplasmique', 'L\'Athlétique', 25],

            // FITNESS HOMME - Le Massif (imposing build, muscular density)
            ['Hypertrophie myofibrillaire', 'Le Massif', 30],
            ['Volume musculaire', 'Le Massif', 30],
            ['Force maximale', 'Le Massif', 20],
            ['Force sous-maximale', 'Le Massif', 20],

            // FITNESS HOMME - Le Sec (lean, defined body)
            ['Sèche / définition musculaire', 'Le Sec', 35],
            ['Perte de poids', 'Le Sec', 25],
            ['Endurance musculaire', 'Le Sec', 20],
            ['Endurance de force', 'Le Sec', 20],

            // FITNESS HOMME - Le Fonctionnel (practical, movement-based, coordination)
            ['Coordination / proprioception', 'Le Fonctionnel', 30],
            ['Force dynamique', 'Le Fonctionnel', 25],
            ['Endurance de force', 'Le Fonctionnel', 25],
            ['Condition physique générale', 'Le Fonctionnel', 20],

            // FITNESS HOMME - Le Force Brute (robust, dominant, maximal strength)
            ['Force maximale', 'Le Force Brute', 35],
            ['Force sous-maximale', 'Le Force Brute', 25],
            ['Puissance musculaire', 'Le Force Brute', 25],
            ['Hypertrophie myofibrillaire', 'Le Force Brute', 15],
        ];

        // Helper closure to insert a single mapping
        $mappingsCreated = 0;
        $insertMapping = function (string $themeName, string $profileName, int $percentage) use ($themes, $profiles, &$mappingsCreated) {
            $theme = $themes->get($themeName);
            if (!$theme) {
                $theme = WorkoutTheme::where('name', 'like', "%{$themeName}%")->first();
            }
            if (!$theme) {
                $this->command->warn("  Theme not found: {$themeName}");
                return;
            }

            $profile = $profiles->get($profileName);
            if (!$profile) {
                $this->command->warn("  Profile not found: {$profileName}");
                return;
            }

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
                $mappingsCreated++;
            }
        };

        // Process football & padel mappings (associative array - no duplicate keys)
        foreach ($mappings as $themeName => $profileMappings) {
            foreach ($profileMappings as $profileName => $percentage) {
                $insertMapping($themeName, $profileName, $percentage);
            }
        }

        // Process fitness mappings (flat tuples to avoid PHP duplicate key issues)
        foreach ($fitnessMappings as [$themeName, $profileName, $percentage]) {
            $insertMapping($themeName, $profileName, $percentage);
        }

        $this->command->info("  Created {$mappingsCreated} profile-theme mappings");
    }
}
