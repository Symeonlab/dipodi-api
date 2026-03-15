<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlayerProfile;
use App\Models\Exercise;
use App\Models\WorkoutTheme;
use App\Models\WorkoutThemeRule;
use App\Models\NutritionAdvice;
use App\Models\BonusWorkoutRule;
use Illuminate\Support\Facades\DB;

class DipoddiProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder imports all data from the DIPODDI_PROGRAMME.xlsx file
     */
    public function run(): void
    {
        $this->command->info('Starting DIPODDI Programme import...');

        // Clear existing data (optional - comment out if you want to merge)
        $this->clearExistingData();

        // Import data
        $this->seedPlayerProfiles();
        $this->seedWorkoutThemes();
        $this->seedExercises();
        $this->seedNutritionAdvice();
        $this->seedBonusWorkoutRules();

        $this->command->info('DIPODDI Programme import completed successfully!');
    }

    private function clearExistingData(): void
    {
        $this->command->info('Clearing existing data...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('player_profile_themes')->truncate();
        WorkoutThemeRule::truncate();
        WorkoutTheme::truncate();
        Exercise::truncate();
        PlayerProfile::truncate();
        NutritionAdvice::truncate();
        BonusWorkoutRule::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedPlayerProfiles(): void
    {
        $this->command->info('Seeding Player Profiles...');

        $profiles = [
            // FOOTBALL - GARDIEN
            ['name' => 'La Panthère', 'group' => 'GARDIEN', 'description' => 'Explosif, capable de parades spectaculaires sur sa ligne.'],
            ['name' => 'La Pieuvre', 'group' => 'GARDIEN', 'description' => 'Envergure immense, bouche tous les angles en 1vs1.'],
            ['name' => 'Le Chat', 'group' => 'GARDIEN', 'description' => 'Agilité extrême, retombe toujours sur ses pattes, rapide au sol.'],
            ['name' => 'L\'Araignée', 'group' => 'GARDIEN', 'description' => 'Lecture du jeu, intercepte les centres, semble être partout.'],

            // FOOTBALL - DÉFENSEUR
            ['name' => 'Le Contrôleur', 'group' => 'DÉFENSEUR', 'description' => 'Calme, dirige la ligne, placement impeccable.'],
            ['name' => 'Le Casseur', 'group' => 'DÉFENSEUR', 'description' => 'Impact physique total, gagne tous ses duels, "nettoie" la zone.'],
            ['name' => 'Le Relanceur', 'group' => 'DÉFENSEUR', 'description' => 'Technique propre, première passe qui casse les lignes.'],
            ['name' => 'Le Polyvalent', 'group' => 'DÉFENSEUR', 'description' => 'Capable de jouer axe ou côté, solide partout sans faille.'],

            // FOOTBALL - MILIEU
            ['name' => 'L\'Architecte', 'group' => 'MILIEU', 'description' => 'Le meneur de jeu, vision 360°, dicte le tempo.'],
            ['name' => 'The Rock', 'group' => 'MILIEU', 'description' => 'Sentinelle devant la défense, inamovible, protège l\'axe.'],
            ['name' => 'Le Pitbull', 'group' => 'MILIEU', 'description' => 'Harcèle le porteur, gros volume de course, ne lâche rien.'],
            ['name' => 'La Gazelle', 'group' => 'MILIEU', 'description' => 'Box-to-box, casse les lignes par sa course et sa vitesse.'],

            // FOOTBALL - ATTAQUANT
            ['name' => 'Le Magicien', 'group' => 'ATTAQUANT', 'description' => 'Dribbleur, imprévisible, crée le danger à partir de rien.'],
            ['name' => 'Le Sniper', 'group' => 'ATTAQUANT', 'description' => 'Frappe clinique, redoutable hors de la surface ou sur CPA.'],
            ['name' => 'Le Tank', 'group' => 'ATTAQUANT', 'description' => 'Pivot physique, protège sa balle, pèse sur la défense.'],
            ['name' => 'Le Renard', 'group' => 'ATTAQUANT', 'description' => 'Toujours au bon endroit, sens du but dans la surface.'],

            // FITNESS FEMME
            ['name' => 'La Silhouette', 'group' => 'FITNESS_FEMME', 'description' => 'Élégante et harmonieuse, elle recherche une ligne affinée, des formes redessinées et un corps visuellement équilibré.'],
            ['name' => 'La Tonique', 'group' => 'FITNESS_FEMME', 'description' => 'Dynamique et énergique, elle aime bouger, transpirer et se sentir active.'],
            ['name' => 'La Fine', 'group' => 'FITNESS_FEMME', 'description' => 'Légère et délicate, elle privilégie la finesse, la fluidité et le contrôle du mouvement.'],
            ['name' => 'L\'Athlète Puissante', 'group' => 'FITNESS_FEMME', 'description' => 'Forte et déterminée, elle affiche un style athlétique marqué, puissant et performant.'],
            ['name' => 'Bien-être', 'group' => 'FITNESS_FEMME', 'description' => 'Apaisée et équilibrée, elle recherche avant tout le confort corporel, la sérénité.'],

            // FITNESS HOMME
            ['name' => 'L\'Athlétique', 'group' => 'FITNESS_HOMME', 'description' => 'Silhouette équilibrée et sportive, combinant puissance, mobilité et endurance.'],
            ['name' => 'Le Massif', 'group' => 'FITNESS_HOMME', 'description' => 'Gabarit imposant et volumineux, privilégiant la densité musculaire.'],
            ['name' => 'Le Sec', 'group' => 'FITNESS_HOMME', 'description' => 'Corps affûté, dessiné et léger, mettant en avant la définition musculaire.'],
            ['name' => 'Le Fonctionnel', 'group' => 'FITNESS_HOMME', 'description' => 'Style utilitaire et efficace, axé sur le mouvement et la coordination.'],
            ['name' => 'Le Force Brute', 'group' => 'FITNESS_HOMME', 'description' => 'Physique robuste et dominant, orienté vers la force maximale et l\'impact.'],

            // PADEL
            ['name' => 'Le Métronome', 'group' => 'PADEL_DROITE', 'description' => 'Tenir un point long intense • Lucidité en fin de match • Coffre pour longs matchs.'],
            ['name' => 'Le Marathonien', 'group' => 'PADEL_DROITE', 'description' => 'Endurance de tournoi • Moteur cardio d\'élite • Récupération entre points.'],
            ['name' => 'Le Stressé', 'group' => 'PADEL_DROITE', 'description' => 'Lucidité sous fatigue • Rythme cardiaque stable • Vision du jeu.'],
            ['name' => 'Le Smasheur', 'group' => 'PADEL_GAUCHE', 'description' => 'Puissance de smash max • Enchaînement de smashs • Explosion & Mental.'],
            ['name' => 'L\'Aérien', 'group' => 'PADEL_GAUCHE', 'description' => 'Premier pas explosif • Hauteur de smash • Vitesse de pieds au filet.'],
            ['name' => 'Le Joueur Lourd', 'group' => 'PADEL_GAUCHE', 'description' => 'Démarrage foudroyant • Force de démarrage • Variation de l\'effort.'],
            ['name' => 'Le Défenseur', 'group' => 'PADEL_DEFENSE', 'description' => 'Sorties de vitre rapides • Solidité jambes/fessiers • Accélération en défense.'],
            ['name' => 'Le Fragile', 'group' => 'PADEL_PREVENTION', 'description' => 'Élimination de l\'acide • Santé tendons & cœur • Résilience articulaire.'],
            ['name' => 'Le Vétéran', 'group' => 'PADEL_SANTE', 'description' => 'Qualité de frappe fatigué • Santé tendons & cœur • Gainage cardio-vasculaire.'],
            ['name' => 'Le Matinal', 'group' => 'PADEL_TIMING', 'description' => 'Réactivité constante • Enchaînement d\'actions • Réveil musculaire.'],
        ];

        foreach ($profiles as $profile) {
            PlayerProfile::create($profile);
        }

        $this->command->info('  Created ' . count($profiles) . ' player profiles');
    }

    private function seedWorkoutThemes(): void
    {
        $this->command->info('Seeding Workout Themes...');

        $themes = [
            // GYM THEMES
            ['name' => 'Force maximale', 'type' => 'gym', 'sets' => '4–6', 'reps' => '1–5', 'recovery_time' => '3–5 min', 'load_type' => '85–100 %', 'exercise_count' => 5],
            ['name' => 'Force sous-maximale', 'type' => 'gym', 'sets' => '4–5', 'reps' => '4–8', 'recovery_time' => '2–3 min', 'load_type' => '75–85 %', 'exercise_count' => 5],
            ['name' => 'Force dynamique', 'type' => 'gym', 'sets' => '4–6', 'reps' => '3–6', 'recovery_time' => '2–3 min', 'load_type' => '60–80 %', 'exercise_count' => 5],
            ['name' => 'Force explosive', 'type' => 'gym', 'sets' => '3–5', 'reps' => '1–5', 'recovery_time' => '3–5 min', 'load_type' => '30–60 %', 'exercise_count' => 4],
            ['name' => 'Puissance musculaire', 'type' => 'gym', 'sets' => '3–5', 'reps' => '3–6', 'recovery_time' => '2–4 min', 'load_type' => '40–70 %', 'exercise_count' => 5],
            ['name' => 'Hypertrophie myofibrillaire', 'type' => 'gym', 'sets' => '4–6', 'reps' => '4–8', 'recovery_time' => '90–120 s', 'load_type' => '75–85 %', 'exercise_count' => 6],
            ['name' => 'Hypertrophie sarcoplasmique', 'type' => 'gym', 'sets' => '3–5', 'reps' => '8–15', 'recovery_time' => '45–90 s', 'load_type' => '60–75 %', 'exercise_count' => 6],
            ['name' => 'Volume musculaire', 'type' => 'gym', 'sets' => '3–5', 'reps' => '6–12', 'recovery_time' => '60–90 s', 'load_type' => '65–80 %', 'exercise_count' => 6],
            ['name' => 'Endurance de force', 'type' => 'gym', 'sets' => '2–4', 'reps' => '15–30', 'recovery_time' => '30–60 s', 'load_type' => '30–60 %', 'exercise_count' => 5],
            ['name' => 'Endurance musculaire', 'type' => 'gym', 'sets' => '2–4', 'reps' => '20–40', 'recovery_time' => '15–45 s', 'load_type' => '20–50 %', 'exercise_count' => 5],
            ['name' => 'Perte de poids', 'type' => 'gym', 'sets' => '2–4', 'reps' => '12–20', 'recovery_time' => '30–60 s', 'load_type' => '40–70 %', 'exercise_count' => 6],
            ['name' => 'Sèche / définition musculaire', 'type' => 'gym', 'sets' => '3–5', 'reps' => '10–20', 'recovery_time' => '30–60 s', 'load_type' => '50–70 %', 'exercise_count' => 6],
            ['name' => 'Condition physique générale', 'type' => 'gym', 'sets' => '2–4', 'reps' => '10–20', 'recovery_time' => '30–60 s', 'load_type' => '40–60 %', 'exercise_count' => 5],
            ['name' => 'Remise en forme', 'type' => 'gym', 'sets' => '2–3', 'reps' => '10–20', 'recovery_time' => '45–90 s', 'load_type' => '40–60 %', 'exercise_count' => 4],
            ['name' => 'Prévention des blessures', 'type' => 'gym', 'sets' => '2–4', 'reps' => '8–15', 'recovery_time' => '60–90 s', 'load_type' => '30–50 %', 'exercise_count' => 4],
            ['name' => 'Renforcement tendineux', 'type' => 'gym', 'sets' => '3–5', 'reps' => '6–12', 'recovery_time' => '60–120 s', 'load_type' => '40–70 %', 'exercise_count' => 4],
            ['name' => 'Réathlétisation', 'type' => 'gym', 'sets' => '2–4', 'reps' => '8–20', 'recovery_time' => '60–120 s', 'load_type' => '20–50 %', 'exercise_count' => 4],
            ['name' => 'Coordination / proprioception', 'type' => 'gym', 'sets' => '2–4', 'reps' => '6–12', 'recovery_time' => '45–90 s', 'load_type' => 'Léger', 'exercise_count' => 4],

            // CARDIO THEMES
            ['name' => 'Endurance aérobie', 'type' => 'cardio', 'sets' => '1', 'reps' => '30–60 min', 'recovery_time' => 'Continue', 'load_type' => '60–75% FCmax', 'exercise_count' => 1],
            ['name' => 'HIIT', 'type' => 'cardio', 'sets' => '8–12', 'reps' => '30s effort', 'recovery_time' => '30s repos', 'load_type' => '85–95% FCmax', 'exercise_count' => 3],
            ['name' => 'Fractionné', 'type' => 'cardio', 'sets' => '6–10', 'reps' => '1–3 min', 'recovery_time' => '1–2 min', 'load_type' => '80–90% FCmax', 'exercise_count' => 2],

            // HOME THEMES
            ['name' => 'Circuit Maison', 'type' => 'home', 'sets' => '5', 'reps' => '45s', 'recovery_time' => '10s exo / 45s tour', 'load_type' => 'Poids du corps', 'exercise_count' => 4],
            ['name' => 'HIIT Maison', 'type' => 'home', 'sets' => '5', 'reps' => '50s', 'recovery_time' => '10s exo / 1 min tour', 'load_type' => 'Poids du corps', 'exercise_count' => 5],

            // MOBILITY THEMES
            ['name' => 'Mobilité & Récupération', 'type' => 'mobility', 'sets' => '5', 'reps' => '30s', 'recovery_time' => '20s', 'load_type' => 'Aucune', 'exercise_count' => 5],
        ];

        foreach ($themes as $themeData) {
            $exerciseCount = $themeData['exercise_count'];
            unset($themeData['exercise_count']);

            $theme = WorkoutTheme::create([
                'name' => $themeData['name'],
                'type' => $themeData['type'],
            ]);

            WorkoutThemeRule::create([
                'workout_theme_id' => $theme->id,
                'exercise_count' => $exerciseCount,
                'sets' => $themeData['sets'],
                'reps' => $themeData['reps'],
                'recovery_time' => $themeData['recovery_time'],
                'load_type' => $themeData['load_type'],
            ]);
        }

        $this->command->info('  Created ' . count($themes) . ' workout themes with rules');
    }

    private function seedExercises(): void
    {
        $this->command->info('Seeding Exercises...');

        $exercises = [
            // MUSCULATION - BRAS
            ['name' => 'Curl biceps', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/bwKQtIsbDlc?si=KQIvG9trqVT1vseE', 'met_value' => 5.0],
            ['name' => 'Extension triceps', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/3ORL_3I_yO8?si=t1nakbIGbHw_TGe2', 'met_value' => 5.0],
            ['name' => 'Curl marteau', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/4W1Vxinj7Cc?si=5VnMU3DKbOkJQHBj', 'met_value' => 5.0],
            ['name' => 'Dips triceps', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/KpACz0Quf2Y?si=9QF9hienzxyVgt63', 'met_value' => 6.0],
            ['name' => 'Curl concentré', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/5PMPmzX-pj0?si=iPzgleRrYVwpGcf-', 'met_value' => 4.5],

            // MUSCULATION - ÉPAULES
            ['name' => 'Développé épaules', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/JSbZ3CkeAQg?si=j_CLdEn6r_IO2DZB', 'met_value' => 5.5],
            ['name' => 'Élévations latérales', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/YsNHINdI2s4?si=AL_OceXXo2LXOUs-', 'met_value' => 4.0],
            ['name' => 'Face pull', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/HJZ7A0VQs6o?si=n0QsQhJKVUDCnXGj', 'met_value' => 4.0],
            ['name' => 'Élévations frontales', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/xgIQYtq4Tio?si=fnYwf_Ss6nm-3obj', 'met_value' => 4.0],
            ['name' => 'Rowing menton', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/gJdp6Ri_dp8?si=obdM19KxOIQpr6zk', 'met_value' => 5.0],

            // MUSCULATION - DOS
            ['name' => 'Tirage vertical', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/kgtw5d42998?si=yEozIFunhLvc7lw6', 'met_value' => 5.5],
            ['name' => 'Rowing barre', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/HrkT2FNWrrg?si=MqNf57hwkb8F2fUD', 'met_value' => 6.0],
            ['name' => 'Tirage horizontal', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/sUGBt0fY77s?si=f6SyzXO5avMU8sO3', 'met_value' => 5.0],
            ['name' => 'Pull-over', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/1-YFlMEmDEs?si=r7nr63gEjMAzGigA', 'met_value' => 4.5],
            ['name' => 'Rowing haltère', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/QaWpgYmByTM?si=HZNcZNnbABLS6vKv', 'met_value' => 5.5],

            // MUSCULATION - PECTORAUX
            ['name' => 'Développé couché', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/xoXojBVTN_8?si=NbGiin3dXBwWBuMG', 'met_value' => 6.0],
            ['name' => 'Développé incliné', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/H_2XGGh-n8s?si=qc9KdSDGumSCmm7D', 'met_value' => 6.0],
            ['name' => 'Écarté haltères', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/Ze81cO6uzDM?si=Yhk-VcxzJOCwI-r0', 'met_value' => 4.5],
            ['name' => 'Cable crossover', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/kvx-XkAp2QM?si=m1CpXBxVRNlsUt0U', 'met_value' => 4.0],
            ['name' => 'Pompes', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/17c7vOMesd4?si=31hrf8vmsCPGEFtv', 'met_value' => 5.5],

            // MUSCULATION - QUADRICEPS
            ['name' => 'Squat', 'category' => 'MUSCULATION', 'sub_category' => 'QUADRICEPS', 'video_url' => 'https://youtube.com/shorts/7TopI0LAcOc?si=C9IRL5YFwMZKLSv-', 'met_value' => 7.0],
            ['name' => 'Presse à cuisses', 'category' => 'MUSCULATION', 'sub_category' => 'QUADRICEPS', 'video_url' => 'https://youtube.com/shorts/f198IoNb6pU?si=otHeQTO2cFaGECKK', 'met_value' => 6.5],
            ['name' => 'Leg extension', 'category' => 'MUSCULATION', 'sub_category' => 'QUADRICEPS', 'video_url' => 'https://youtube.com/shorts/V5zGzu5SFG0?si=V6KR2qr3YRGwD6US', 'met_value' => 5.0],
            ['name' => 'Fentes', 'category' => 'MUSCULATION', 'sub_category' => 'QUADRICEPS', 'video_url' => 'https://youtube.com/shorts/Z4o9rnutvdA?si=NhOgbxpOPs_FW0ll', 'met_value' => 6.0],
            ['name' => 'Hack squat', 'category' => 'MUSCULATION', 'sub_category' => 'QUADRICEPS', 'video_url' => 'https://youtube.com/shorts/CASNofbIq5o?si=tZdgpovzS2wD54bu', 'met_value' => 6.5],

            // MUSCULATION - JAMBES
            ['name' => 'Sissy squat', 'category' => 'MUSCULATION', 'sub_category' => 'JAMBES', 'video_url' => 'https://youtube.com/shorts/SXpvJG8dz2o?si=jRmT2kHN9v1wX8aB', 'met_value' => 6.0],
            ['name' => 'Leg curl allongé', 'category' => 'MUSCULATION', 'sub_category' => 'JAMBES', 'video_url' => 'https://youtube.com/shorts/udV65CGZFzA?si=kLp3mN7vQ2xR9cDf', 'met_value' => 5.5],
            ['name' => 'Soulevé de terre jambes tendues', 'category' => 'MUSCULATION', 'sub_category' => 'JAMBES', 'video_url' => 'https://youtube.com/shorts/LdoaQhFjnus?si=hGt4wK8nP6yS3bEg', 'met_value' => 7.0],
            ['name' => 'Squat bulgare', 'category' => 'MUSCULATION', 'sub_category' => 'JAMBES', 'video_url' => 'https://youtube.com/shorts/WWU_MVyg1Xs?si=rVu5xJ9mT3aL7cHi', 'met_value' => 6.5],
            ['name' => 'Mollets debout machine', 'category' => 'MUSCULATION', 'sub_category' => 'JAMBES', 'video_url' => 'https://youtube.com/shorts/sCUaH2SAspQ?si=pWn6yM8kQ4bR2dFj', 'met_value' => 5.0],
            ['name' => 'Goblet squat', 'category' => 'MUSCULATION', 'sub_category' => 'JAMBES', 'video_url' => 'https://youtube.com/shorts/FUs8XyVFQKY?si=tXo7zN9lS5cU3eGk', 'met_value' => 6.5],
            ['name' => 'Squat sumo', 'category' => 'MUSCULATION', 'sub_category' => 'JAMBES', 'video_url' => 'https://youtube.com/shorts/sqvrPCMDvHM?si=uYp8aO0mT6dV4fHl', 'met_value' => 6.5],
            ['name' => 'Step-up haltères', 'category' => 'MUSCULATION', 'sub_category' => 'JAMBES', 'video_url' => 'https://youtube.com/shorts/0oQ98CvcAko?si=vZq9bP1nU7eW5gIm', 'met_value' => 6.0],
            ['name' => 'Hip thrust', 'category' => 'MUSCULATION', 'sub_category' => 'JAMBES', 'video_url' => 'https://youtube.com/shorts/hCafqJ2lJA8?si=wAr0cQ2oV8fX6hJn', 'met_value' => 6.5],
            ['name' => 'Adducteur machine', 'category' => 'MUSCULATION', 'sub_category' => 'JAMBES', 'video_url' => 'https://youtube.com/shorts/O4odcNAMYVE?si=xBs1dR3pW9gY7iKo', 'met_value' => 5.0],
            ['name' => 'Abducteur machine', 'category' => 'MUSCULATION', 'sub_category' => 'JAMBES', 'video_url' => 'https://youtube.com/shorts/qWqtUctNBQ4?si=yCt2eS4qX0hZ8jLp', 'met_value' => 5.0],
            ['name' => 'Presse unilatérale', 'category' => 'MUSCULATION', 'sub_category' => 'JAMBES', 'video_url' => 'https://youtube.com/shorts/HojeFBRoA4U?si=zDu3fT5rY1iA9kMq', 'met_value' => 6.5],

            // KINE MOBILITÉ - CHEVILLES
            ['name' => 'Mobilité chevilles rotation', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'CHEVILLES', 'video_url' => 'https://youtube.com/shorts/avXpC0-grZ8?si=4zt1Z7NjeDuf60zU', 'met_value' => 2.5],
            ['name' => 'Mobilité chevilles flexion', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'CHEVILLES', 'video_url' => 'https://youtube.com/shorts/vvIH4O4BltY?si=FaFzB-jaDDEBg_Dh', 'met_value' => 2.5],

            // KINE MOBILITÉ - GENOUX
            ['name' => 'Mobilité genoux flexion', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'GENOUX', 'video_url' => 'https://youtube.com/shorts/dAOZ1DIwS_k?si=cdJzITl3IBdS_SnJ', 'met_value' => 2.5],
            ['name' => 'Mobilité genoux rotation', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'GENOUX', 'video_url' => 'https://youtube.com/shorts/Z9fhJGCC5yo?si=rc_vKxAlqyHMLZQ3', 'met_value' => 2.5],

            // KINE MOBILITÉ - HANCHES
            ['name' => 'Mobilité hanches ouverture', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'HANCHES', 'video_url' => 'https://youtube.com/shorts/Hem1BU0Hxiw?si=tWM1ZucLrHcHJLlx', 'met_value' => 3.0],
            ['name' => 'Mobilité hanches rotation', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'HANCHES', 'video_url' => 'https://youtube.com/shorts/LrKRE0t6uuo?si=v0PmL5FYdRmvJZRi', 'met_value' => 3.0],
            ['name' => 'Mobilité hanches extension', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'HANCHES', 'video_url' => 'https://youtube.com/shorts/lzgAXKHsT-A?si=j8HksjG9BjI0mCf6', 'met_value' => 3.0],

            // KINE MOBILITÉ - PIEDS
            ['name' => 'Mobilité pieds voûte', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'PIEDS', 'video_url' => 'https://youtube.com/shorts/2xsX7cNsMyk?si=Fg-BLBsR9k9bWoly', 'met_value' => 2.0],
            ['name' => 'Mobilité pieds orteils', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'PIEDS', 'video_url' => 'https://youtube.com/shorts/MFgpE3FBsuo?si=iBG0WY6PtEpPN2ls', 'met_value' => 2.0],

            // KINE RENFORCEMENT - ADDUCTEURS
            ['name' => 'Renforcement adducteurs', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'ADDUCTEURS', 'video_url' => 'https://youtube.com/shorts/JUlDFRTNA6w?si=p0M_4LqKKPDmlUyX', 'met_value' => 4.0],
            ['name' => 'Adducteurs latéraux', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'ADDUCTEURS', 'video_url' => 'https://youtube.com/shorts/HuS1WuH7M7s?si=ASOkotzT5aHG2mSi', 'met_value' => 4.0],

            // KINE RENFORCEMENT - FESSIERS
            ['name' => 'Bridge fessiers', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'FESSIERS', 'video_url' => 'https://youtube.com/shorts/SdZKcrOCiJ4?si=0Og5vOUcWycuPudU', 'met_value' => 4.5],
            ['name' => 'Kickback fessiers', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'FESSIERS', 'video_url' => 'https://youtube.com/shorts/_YutftKthMc?si=8IALrSOE0raI7WCs', 'met_value' => 4.5],
            ['name' => 'Clamshell', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'FESSIERS', 'video_url' => 'https://youtube.com/shorts/eprrreviA-Y?si=47coyzdLcFJ95p9t', 'met_value' => 3.5],

            // BONUS - ABDOS
            ['name' => 'Crunch classique', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/FHjjTqxcB8w?si=ho-kzZxZJkkvKA_E', 'met_value' => 4.0],
            ['name' => 'Crunch oblique', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/-C79TR7Rvdc?si=x0mvP1oLXY4IG48C', 'met_value' => 4.5],
            ['name' => 'Mountain climbers', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/0pSJ6mfzHJE?si=HQMQFSk5rkiyXAk8', 'met_value' => 8.0],
            ['name' => 'Relevé de jambes', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/1mrDGRnlwmE?si=pgjVWF22U8ZzttST', 'met_value' => 4.5],

            // BONUS - POMPES
            ['name' => 'Pompes classiques', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/dtPHZ0Zd-jE?si=PPrk73x9fTFXU834', 'met_value' => 5.5],
            ['name' => 'Pompes diamant', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/egyQnsDcVUU?si=iGbDziTxvBcd-_5A', 'met_value' => 6.0],
            ['name' => 'Pompes larges', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/lUg2kVNNrig?si=ushyJhxx-s4pg3NW', 'met_value' => 5.5],
            ['name' => 'Pompes inclinées', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/oaWppeQ8xRQ?si=zbLWEX_DGBVI3I9s', 'met_value' => 5.0],

            // BONUS - GAINAGE
            ['name' => 'Planche classique', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/pMZFuFkykmA?si=omypoRLFfAUuHPg1', 'met_value' => 3.5],
            ['name' => 'Planche latérale', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/0knDxost67c?si=-jCE6y2XuHf2zpyM', 'met_value' => 4.0],
            ['name' => 'Planche dynamique', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/0oXYdSIgzCg?si=9UKQw5L5unSAIPkN', 'met_value' => 4.5],
            ['name' => 'Superman', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/2Hscb3ZaJCA?si=LSVNeTld3FU3z9Tj', 'met_value' => 3.5],

            // MAISON - PERTE DE POIDS
            ['name' => 'Burpees', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/0agTTLqEBDo?si=T85-f6-0nUFrmmsf', 'met_value' => 10.0],
            ['name' => 'Jumping jacks', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/1-_dvwTVd6g?si=JO91dax3i6ncCiAU', 'met_value' => 8.0],
            ['name' => 'High knees', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/8ige7jhJuOc?si=VcShB6rnuq2ELsS6', 'met_value' => 8.5],
            ['name' => 'Squat jumps', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/94lMAmmpuFM?si=xqHlI7d1XcOaB3A1', 'met_value' => 9.0],

            // MAISON - RENFORCEMENT
            ['name' => 'Squat bodyweight', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/4mZiR6juYvQ?si=MGO-CE2OAElk1F3H', 'met_value' => 5.0],
            ['name' => 'Fentes alternées', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/7iOwd-vcM1Y?si=X28H5MAiemABMgpr', 'met_value' => 5.5],
            ['name' => 'Glute bridge', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/9lD41hGN33U?si=HZr_6g20UjIIC2Xa', 'met_value' => 4.0],
            ['name' => 'Pompes sur genoux', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/A2QgyQccmkc?si=WKNoW_8Fv_ZCUJEZ', 'met_value' => 4.5],

            // CARDIO
            ['name' => 'Course endurance', 'category' => 'CARDIO', 'sub_category' => 'ENDURANCE', 'video_url' => null, 'met_value' => 8.0],
            ['name' => 'Vélo elliptique', 'category' => 'CARDIO', 'sub_category' => 'ENDURANCE', 'video_url' => null, 'met_value' => 7.0],
            ['name' => 'Rameur', 'category' => 'CARDIO', 'sub_category' => 'ENDURANCE', 'video_url' => null, 'met_value' => 7.5],
            ['name' => 'Corde à sauter', 'category' => 'CARDIO', 'sub_category' => 'HIIT', 'video_url' => null, 'met_value' => 11.0],
            ['name' => 'Sprint', 'category' => 'CARDIO', 'sub_category' => 'HIIT', 'video_url' => null, 'met_value' => 12.0],

            // ADDITIONAL EXERCISES FROM EXCEL (372 exercises)
            ['name' => 'Bras exercice 1', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/FYAxHurQMc8?si=Mk3k82e9hejLVSwA', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 1', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/DFNKfMe6dcA?si=k73jNYRlKt_bEF-6', 'met_value' => 5.5],
            ['name' => 'Dos exercice 1', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/KpzFeoMhPto?si=7YJ3mQwABbwQYv_M', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 1', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/p5AufLpj53c?si=4GCPD9DHFvpSW4yF', 'met_value' => 5.5],
            ['name' => 'Bras exercice 2', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/k52Y6HiUMnw?si=XvqE3-sg9paMmBC8', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 2', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/fHCc5uO07lA?si=M53H6_CXGOlb1rbE', 'met_value' => 5.5],
            ['name' => 'Dos exercice 2', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/_eMGcaQ5rzs?si=HcLynWmsomXhPySe', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 2', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/k4MpQYpo2b4?si=W2neTHWaP8OoTvIr', 'met_value' => 5.5],
            ['name' => 'Bras exercice 3', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/a-hQ4YvwO3c?si=taC0tgD6wnjeVVWU', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 3', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/ebwsDi4DuCM?si=COgZoIjcGpGX_Kfb', 'met_value' => 5.5],
            ['name' => 'Dos exercice 3', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/lmasox6N8VA?si=O28YiKP5G6eaY2ls', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 3', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/tRA7T9nVHLE?si=FO0jBWrtfCiZNmkU', 'met_value' => 5.5],
            ['name' => 'Bras exercice 4', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/Qx3RyJ8Ffeg?si=O4tPljNV60xR6ClR', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 4', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/n5FPulqnuzE?si=tttKVO1ZpGOGEkK4', 'met_value' => 5.5],
            ['name' => 'Dos exercice 4', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/MvueY8B2q4o?si=8y3VfHqaSqPm0zbQ', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 4', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/uJD0cFKLg8g?si=a2uz78hSLv296Xdx', 'met_value' => 5.5],
            ['name' => 'Bras exercice 5', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/1OryNw0bGPA?si=8iUrt_deF-nl3oyp', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 5', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/f9Pkth7EP6g?si=u0Qt3DAC6YZP1Ru4', 'met_value' => 5.5],
            ['name' => 'Dos exercice 5', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/THoeE7EVLLM?si=L0H-Ry6y4cGYn45B', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 5', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/vEzVopa4-kE?si=t1fw-foQLhiISvKL', 'met_value' => 5.5],
            ['name' => 'Bras exercice 6', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/3TkYPAT6YiU?si=t33S-lp-qkRd2YM2', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 6', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/au_SOwOCWg4?si=ShUvywxojR2_4ENS', 'met_value' => 5.5],
            ['name' => 'Dos exercice 6', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/fsBMsR0lrfk?si=0WbgQSaN5qee52JC', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 6', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/3iyM4LpgkOY?si=IFuCWVwW5Xjbd1-J', 'met_value' => 5.5],
            ['name' => 'Bras exercice 7', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/IV4TqOD7TSg?si=PFf0NB2DnxmtX0jT', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 7', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/WX7Z7fZ4094?si=nwheHiZaoq0ABYuH', 'met_value' => 5.5],
            ['name' => 'Dos exercice 7', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/JRVZ06ZqofU?si=4NJyjR7lhuaE1eV4', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 7', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/u3-lb2YLVD4?si=C4a2oc9DLoB5jrlv', 'met_value' => 5.5],
            ['name' => 'Bras exercice 8', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/RLmjB3G6G-A?si=c2NnhwX1ImCw5yGO', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 8', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/erBXdVu6ONo?si=IYxt0jJBvJC891s7', 'met_value' => 5.5],
            ['name' => 'Dos exercice 8', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/xJCJVw8rP1A?si=yGOU5Vj9cQF4c7Nd', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 8', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/LUZRKX-Pw_w?si=KZR8FH91mqJWYMap', 'met_value' => 5.5],
            ['name' => 'Bras exercice 9', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/EqgF5tl4lsU?si=lJ3jDCZQr-USmpDr', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 9', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/lgnUP3dz1P0?si=mQEszp2oEtNqcLpG', 'met_value' => 5.5],
            ['name' => 'Dos exercice 9', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/4Sv7HB0ZzIY?si=4q8fiR6Sdjldu9kq', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 9', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/xnb6ug1Br2k?si=jpUe-TXZP4Sr8K_m', 'met_value' => 5.5],
            ['name' => 'Bras exercice 10', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/Q_Ek61tAKZs?si=G2TurvnYFzzf8cwr', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 10', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/w7QO6pOHrbk?si=j5-vLV4u-K6YWC2L', 'met_value' => 5.5],
            ['name' => 'Dos exercice 10', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/IG3rs3XoYvY?si=0PZ1JH08j0vUHD0F', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 10', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/vsEIWPRnFkw?si=4V9uXiuxpovg02Mu', 'met_value' => 5.5],
            ['name' => 'Bras exercice 11', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/HvA7CaJwh4w?si=qeewIr2tPmTSh5AK', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 11', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/gZouEVOnZcg?si=Pu2_tgEE-TX3utBT', 'met_value' => 5.5],
            ['name' => 'Dos exercice 11', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/EfacqHYrKOs?si=JZd-uLDY82WWIdHf', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 11', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/DYWP4yR9iR8?si=aa6erjWSQwxaPC_c', 'met_value' => 5.5],
            ['name' => 'Bras exercice 12', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/C4i700_0vCQ?si=zc4MwCv419QpyYNi', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 12', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/uzun1E5bu8M?si=uKlA4pkZjA_hYCwd', 'met_value' => 5.5],
            ['name' => 'Dos exercice 12', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/L_a8GC45PHY?si=Cqh2n61EWfqC6Gma', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 12', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/wLDF0HaiIF8?si=wfkWDj9XBy3XSjWC', 'met_value' => 5.5],
            ['name' => 'Bras exercice 13', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/S-ctXxcCbqY?si=Bd_hcgyH4yjK9qgC', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 13', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/KqchQZJHdhA?si=Ka7UJskCGI3F6d7f', 'met_value' => 5.5],
            ['name' => 'Dos exercice 13', 'category' => 'MUSCULATION', 'sub_category' => 'DOS', 'video_url' => 'https://youtube.com/shorts/LqbtL9DpJmU?si=B7-fRQfN-1A-j1Gg', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 13', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/Sty3Vee4yek?si=p1vdSfXeOfpyoxx2', 'met_value' => 5.5],
            ['name' => 'Bras exercice 14', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/tqXI1di-Scs?si=CbRODSGJtrTxj427', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 14', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/3xux5bpIY5o?si=E2YOltaO7vj8RWV6', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 14', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/b7S7hVrDUu0?si=ILwbaAacF8mp7n0p', 'met_value' => 5.5],
            ['name' => 'Bras exercice 15', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/2aozd3ce3ew?si=uSsgrj29VtWR9wbH', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 15', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/RQCuI8n9c34?si=yke6qvsE_1Dy2Os2', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 15', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/N0qMQIR4W6E?si=J6u0dDJ4_ArNxTtO', 'met_value' => 5.5],
            ['name' => 'Bras exercice 16', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/NT8lw0rbm0g?si=EcgfCwNKIizq4SwD', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 16', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/r1VuoHW2bpI?si=nzp417QB7lmESe80', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 16', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/Raq9icmnwn8?si=Iq68_4cJcIBaSz5l', 'met_value' => 5.5],
            ['name' => 'Bras exercice 17', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/qra5gCFCebA?si=XP3o0C_UCXpcnyHv', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 17', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/1MxqyqJcN2Y?si=MX9FGsKudnzwq4no', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 17', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/ryB1eLke_e8?si=lFG7rIPfdH44pVbW', 'met_value' => 5.5],
            ['name' => 'Bras exercice 18', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/shPqDLH1QPM?si=ps5yMtm9kJHtyXgP', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 18', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/68nhsTdg_Fg?si=3TkYUB9q6TgZWo5x', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 18', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/efg0MlRq3Ig?si=huspMoAmq95qfYYg', 'met_value' => 5.5],
            ['name' => 'Bras exercice 19', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/ohR_uESI6YY?si=88fO7Q8VigKVZiCN', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 19', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/PQUIzw2Taxs?si=IXpcLLuKOHswKFVQ', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 19', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/mUbY2tVEmt0?si=YKYfxzUNxcMzQpro', 'met_value' => 5.5],
            ['name' => 'Bras exercice 20', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/uwEsWGuyJ8c?si=4NXbGHKg2U30PcLh', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 20', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/Ke1etRLIUNw?si=x-gFyMkGlDYAI9Yh', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 20', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/tHompI9e5-c?si=SuuRjwLiJkg1pSDa', 'met_value' => 5.5],
            ['name' => 'Bras exercice 21', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/9QUpKxQgGUI?si=DpKWakFp7tRftaWr', 'met_value' => 5.5],
            ['name' => 'Épaules exercice 21', 'category' => 'MUSCULATION', 'sub_category' => 'ÉPAULES', 'video_url' => 'https://youtube.com/shorts/guXsPqtxG8A?si=hyBedN1oT5uUa9ro', 'met_value' => 5.5],
            ['name' => 'Pectoraux exercice 21', 'category' => 'MUSCULATION', 'sub_category' => 'PECTORAUX', 'video_url' => 'https://youtube.com/shorts/0N6WU8-Ko4E?si=cipItlxymwcdYL3x', 'met_value' => 5.5],
            ['name' => 'Bras exercice 22', 'category' => 'MUSCULATION', 'sub_category' => 'BRAS', 'video_url' => 'https://youtube.com/shorts/AL_RrGhqo8M?si=fp0zbc6c5p7ByoEM', 'met_value' => 5.5],
            ['name' => 'Abdos variante 1', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/55D_CHEx7oI?si=vPs1bbObAOBzr_sp', 'met_value' => 4.0],
            ['name' => 'Pompes variante 1', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/pO1RcFF0LJ0?si=YueUk5WEpj0QVRF5', 'met_value' => 5.5],
            ['name' => 'Gainage variante 1', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/7J6ekvM5ic0?si=_9wu14MTAZM0cDkw', 'met_value' => 3.5],
            ['name' => 'Abdos variante 2', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/5Rl3YZ3Vm-8?si=_GTNVXpMt-NhOe5s', 'met_value' => 4.0],
            ['name' => 'Pompes variante 2', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/s5Q_6GcVV0U?si=ZgvgEbhu4meMMNIt', 'met_value' => 5.5],
            ['name' => 'Gainage variante 2', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/7Zo2wnNBZD0?si=f2b7loqI9cDBQLS-', 'met_value' => 3.5],
            ['name' => 'Abdos variante 3', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/8YCCNcJQrjw?si=cC_BLnsyGnQ4JtuJ', 'met_value' => 4.0],
            ['name' => 'Pompes variante 3', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/tckP3mUPnag?si=8xtw-gyFKJC9uwmY', 'met_value' => 5.5],
            ['name' => 'Gainage variante 3', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/9KfQAGQWB-0?si=Emm7OXNYkK_kRKtt', 'met_value' => 3.5],
            ['name' => 'Abdos variante 4', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/8vdQ2WGokxs?si=b9E6a-skAext3sX5', 'met_value' => 4.0],
            ['name' => 'Pompes variante 4', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/vfkz3PKAPPE?si=ag3xt0htO4GATGIg', 'met_value' => 5.5],
            ['name' => 'Gainage variante 4', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/Cx9DJ4-FPIg?si=NmUXQ9a-SzGvtQy8', 'met_value' => 3.5],
            ['name' => 'Abdos variante 5', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/A4u79ge-bDI?si=GA0yCrDlyGudY7l1', 'met_value' => 4.0],
            ['name' => 'Pompes variante 5', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/wSmLXqmTgbQ?si=TFouvbyovUrszjWa', 'met_value' => 5.5],
            ['name' => 'Gainage variante 5', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/DMKlmNtCXOg?si=6gmncBFQBLnsN5im', 'met_value' => 3.5],
            ['name' => 'Abdos variante 6', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/AeymKJ5KNa0?si=MJk5RTPPyG44cZkV', 'met_value' => 4.0],
            ['name' => 'Pompes variante 6', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/CAr_ugYl40U?si=NplKfEwfCHHROVpx', 'met_value' => 5.5],
            ['name' => 'Gainage variante 6', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/FNzuRYM-NVA?si=3--jx1_SEgeKBrTM', 'met_value' => 3.5],
            ['name' => 'Abdos variante 7', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/BCnRS8YIG5s?si=m_rtDchq-wfJICmC', 'met_value' => 4.0],
            ['name' => 'Pompes variante 7', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/F8J5wpXtQgM?si=txruNj1U6pbxEnwu', 'met_value' => 5.5],
            ['name' => 'Gainage variante 7', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/HTRA7P4yTls?si=iOgdOTNc2MaPsS5Z', 'met_value' => 3.5],
            ['name' => 'Abdos variante 8', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/DmWpsSJRoUg?si=JlyI3zxsFLFIBDp2', 'met_value' => 4.0],
            ['name' => 'Pompes variante 8', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/MRpkhhSwto8?si=UrSUoz0nm-9vw44N', 'met_value' => 5.5],
            ['name' => 'Gainage variante 8', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/IULIkw7tlWo?si=LNzc6Rz-VW03n3SK', 'met_value' => 3.5],
            ['name' => 'Abdos variante 9', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/EzXEg8vsXF0?si=ciUlpcu3XEoQ2Cut', 'met_value' => 4.0],
            ['name' => 'Pompes variante 9', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/OZV5x6EdOec?si=DCo0AQmfXUHsfYOP', 'met_value' => 5.5],
            ['name' => 'Gainage variante 9', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/JZnLjft0DfI?si=7V8G_cnkKzqXN6bS', 'met_value' => 3.5],
            ['name' => 'Abdos variante 10', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/Kn0xgs_xKeQ?si=IuKmzsqcCQpJW0yk', 'met_value' => 4.0],
            ['name' => 'Pompes variante 10', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/UHPyledUqzA?si=bWULqwqzL9aK9SPj', 'met_value' => 5.5],
            ['name' => 'Gainage variante 10', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/PwOFsR8pVE0?si=YZHCfbqWg_lL5CwF', 'met_value' => 3.5],
            ['name' => 'Abdos variante 11', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/M1WaAJMNGRo?si=jIP9suAjYMtrUI0A', 'met_value' => 4.0],
            ['name' => 'Pompes variante 11', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/VJdyNIk3fxs?si=8rgKJMFrQ38UXQ9s', 'met_value' => 5.5],
            ['name' => 'Gainage variante 11', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/V_16CKCoXi8?si=7Sd3zCAn4KyoPuBi', 'met_value' => 3.5],
            ['name' => 'Abdos variante 12', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/MhzCOvdFk6k?si=uddmlurCKtqqVMfU', 'met_value' => 4.0],
            ['name' => 'Pompes variante 12', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/WkHojcyM0Ks?si=tG75HSYQrP8QuR3J', 'met_value' => 5.5],
            ['name' => 'Gainage variante 12', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/X1aqllvkXYs?si=JllOknPgU0Q0xEvQ', 'met_value' => 3.5],
            ['name' => 'Abdos variante 13', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/Ms2-saauSB8?si=nqpHds8QrqSN4f6v', 'met_value' => 4.0],
            ['name' => 'Pompes variante 13', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/X6EsXzkbuGk?si=ffMZmajxh7_gK4od', 'met_value' => 5.5],
            ['name' => 'Gainage variante 13', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/YS38MiAFOLs?si=EBD22dtV6fGEao2I', 'met_value' => 3.5],
            ['name' => 'Abdos variante 14', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/OgLG8TYScwo?si=mKOrODUtEibprMm4', 'met_value' => 4.0],
            ['name' => 'Pompes variante 14', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/YaiqzdRurO8?si=Z6YUDuNPVDmjhRi0', 'met_value' => 5.5],
            ['name' => 'Gainage variante 14', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/bBq2AZcvSQQ?si=Iv1kgiBS4THlJ4gq', 'met_value' => 3.5],
            ['name' => 'Abdos variante 15', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/Qa8_jvhjdco?si=IedVP0O3MivMNyD1', 'met_value' => 4.0],
            ['name' => 'Pompes variante 15', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/1UXYPbKSvG4?si=YcNL4zkJr4CUtMXN', 'met_value' => 5.5],
            ['name' => 'Gainage variante 15', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/bXtaueKw5pw?si=pqPQOuwLcUL78dPz', 'met_value' => 3.5],
            ['name' => 'Abdos variante 16', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/RtNJaY1jhus?si=JOSNl6JSJOZLfXO5', 'met_value' => 4.0],
            ['name' => 'Pompes variante 16', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/B1cysxnPvo8?si=YNLNlivZf0_I5zS9', 'met_value' => 5.5],
            ['name' => 'Gainage variante 16', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/cT8FDM5s_ys?si=aExYRk93feYrUbXV', 'met_value' => 3.5],
            ['name' => 'Abdos variante 17', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/VEflbldS2W8?si=SzEFBa8FGzC-719L', 'met_value' => 4.0],
            ['name' => 'Pompes variante 17', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/XvbqpamfnrQ?si=c834IJLnHOFTHMiC', 'met_value' => 5.5],
            ['name' => 'Gainage variante 17', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/ff0PdTxT9q4?si=QXpc4qr5sOMTN8Zp', 'met_value' => 3.5],
            ['name' => 'Abdos variante 18', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/WFBanM956Q8?si=s7i_daRn4gz3SlZ4', 'met_value' => 4.0],
            ['name' => 'Pompes variante 18', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/ZRjQbKkrlvI?si=Nnm2SRq5FsETdePw', 'met_value' => 5.5],
            ['name' => 'Gainage variante 18', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/kiP-mKbvuFQ?si=qObyb9VM1ieVQMfk', 'met_value' => 3.5],
            ['name' => 'Abdos variante 19', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/aDW4jV--tlU?si=HNuB6tLa78QGGHhk', 'met_value' => 4.0],
            ['name' => 'Pompes variante 19', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/ZiuSABKUTTc?si=Y0y-dSDnGiVHJsmQ', 'met_value' => 5.5],
            ['name' => 'Gainage variante 19', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/l3zgH4Jf5Xc?si=Sbd7Xm-Kb6OFDRNz', 'met_value' => 3.5],
            ['name' => 'Abdos variante 20', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/aHS_y-7DBSQ?si=iIke3UFpJGx81NGI', 'met_value' => 4.0],
            ['name' => 'Pompes variante 20', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/_YLAj1Tylak?si=HatYySgvqWwweYhx', 'met_value' => 5.5],
            ['name' => 'Gainage variante 20', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/m_27_EBCALw?si=8kgXY8VI_mk2c5d8', 'met_value' => 3.5],
            ['name' => 'Abdos variante 21', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/apWeP7KQzss?si=NquEIxQsdpmVv7Lh', 'met_value' => 4.0],
            ['name' => 'Pompes variante 21', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/bq9TRYyk-bw?si=9sM9KzoS4Hj4y0ax', 'met_value' => 5.5],
            ['name' => 'Gainage variante 21', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/pFDCTzfUK_Q?si=hixBDDmASfYoVsQz', 'met_value' => 3.5],
            ['name' => 'Abdos variante 22', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/cD13kQFwamQ?si=G5-ro2UB-PRYaSxs', 'met_value' => 4.0],
            ['name' => 'Pompes variante 22', 'category' => 'BONUS', 'sub_category' => 'POMPES', 'video_url' => 'https://youtube.com/shorts/c2kpt2hWsi0?si=cB_cY0hZ2gUqviw4', 'met_value' => 5.5],
            ['name' => 'Gainage variante 22', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/qRa5Edo9Z-U?si=mwJBVPeofA5xKe5C', 'met_value' => 3.5],
            ['name' => 'Abdos variante 23', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/c_1TrVgeIoo?si=RdFefvpvlnnLrWYZ', 'met_value' => 4.0],
            ['name' => 'Gainage variante 23', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/rqmY4Kkh-9U?si=ihWgwHel64ny8BwU', 'met_value' => 3.5],
            ['name' => 'Abdos variante 24', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/dIx_9-1S9OM?si=MZSXH5PK7vsbzEs8', 'met_value' => 4.0],
            ['name' => 'Gainage variante 24', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/sBCyP_iGJG4?si=dTFKx9WVBhf0z6pV', 'met_value' => 3.5],
            ['name' => 'Abdos variante 25', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/dxjoSvJIt9c?si=vuMkgcIa88D_D1p1', 'met_value' => 4.0],
            ['name' => 'Gainage variante 25', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/txJ3T9Sj0Ck?si=-P6j0_sEY1NSAas8', 'met_value' => 3.5],
            ['name' => 'Abdos variante 26', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/e3vL9NTBOD8?si=8kLiKCuUE4jJ2Slp', 'met_value' => 4.0],
            ['name' => 'Gainage variante 26', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/v9keA20vANA?si=36FJwwbyXIQXu4pe', 'met_value' => 3.5],
            ['name' => 'Abdos variante 27', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/eKT4xJt_luw?si=1-H3A8GDn55Sp7KE', 'met_value' => 4.0],
            ['name' => 'Gainage variante 27', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/v9pQzSq_QA8?si=ac5LGSeldNeRSJ4_', 'met_value' => 3.5],
            ['name' => 'Abdos variante 28', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/fby-Kv5V-cs?si=Ni2N4y9mlu7m_p6F', 'met_value' => 4.0],
            ['name' => 'Gainage variante 28', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/w42JqLa-ZDQ?si=1le8cgXGWU7lbF-k', 'met_value' => 3.5],
            ['name' => 'Abdos variante 29', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/jCxkLJ7dQQs?si=XfZDOsIL2jAbwZzj', 'met_value' => 4.0],
            ['name' => 'Gainage variante 29', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/wZ85ALFy7kU?si=o5sskF_KqgJg8ldE', 'met_value' => 3.5],
            ['name' => 'Abdos variante 30', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/lBaXb6G3qdY?si=l_c5BgqwGm4TQRul', 'met_value' => 4.0],
            ['name' => 'Gainage variante 30', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/xZlGJc_nxmU?si=r9pIpN19UPYqQntS', 'met_value' => 3.5],
            ['name' => 'Abdos variante 31', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/lmptWZg6rQE?si=LPqFexUDGwb7qW3V', 'met_value' => 4.0],
            ['name' => 'Gainage variante 31', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/zEI7gHU_Sg8?si=4gI9EhvKMjOJ_p-m', 'met_value' => 3.5],
            ['name' => 'Abdos variante 32', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/n_zj6m61Rtw?si=ijOgeog2SO8K3MkA', 'met_value' => 4.0],
            ['name' => 'Gainage variante 32', 'category' => 'BONUS', 'sub_category' => 'GAINAGE', 'video_url' => 'https://youtube.com/shorts/zHFwLnFmxmU?si=mJxNeXj2I_LXI1TV', 'met_value' => 3.5],
            ['name' => 'Abdos variante 33', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/nwQ4b2gqMBE?si=Ua-pLo79rj_rpqMA', 'met_value' => 4.0],
            ['name' => 'Abdos variante 34', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/oAAtI1FPRNU?si=hUVwDc_o2_iFxoOm', 'met_value' => 4.0],
            ['name' => 'Abdos variante 35', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/oHgV-9U9CbY?si=siWNu0tdA0bBuQhM', 'met_value' => 4.0],
            ['name' => 'Abdos variante 36', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/oO8e3NJ34DA?si=FensVwKBLaIlAtLa', 'met_value' => 4.0],
            ['name' => 'Abdos variante 37', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/oRBiCQG2Cb8?si=fnt5iGZcpBkc90f3', 'met_value' => 4.0],
            ['name' => 'Abdos variante 38', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/qYzVKdWulns?si=5EkaN0GPcZp_Gfy1', 'met_value' => 4.0],
            ['name' => 'Abdos variante 39', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/sO2fm7K2LFY?si=USGOHvf9S10DghoW', 'met_value' => 4.0],
            ['name' => 'Abdos variante 40', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/tyvCo_zhxuo?si=4ysBE-y4Vo5ZpFpr', 'met_value' => 4.0],
            ['name' => 'Abdos variante 41', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/ugV6LCh96bY?si=pEQgSTWBgZDX-kbG', 'met_value' => 4.0],
            ['name' => 'Abdos variante 42', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/uoa3D_F9Gek?si=Q3lUfb6ogBhSfmjD', 'met_value' => 4.0],
            ['name' => 'Abdos variante 43', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/xfY8f_DZBFE?si=v77kM53vr4Z1MmtR', 'met_value' => 4.0],
            ['name' => 'Abdos variante 44', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/yx0sg34-2qY?si=wTckcYSv1rtFfndq', 'met_value' => 4.0],
            ['name' => 'Abdos variante 45', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/0uoMY7xJRxg?si=COZla940lavTuNYq', 'met_value' => 4.0],
            ['name' => 'Abdos variante 46', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/2_j0jNDsybE?si=Qx0DmjEm94yTCRT_', 'met_value' => 4.0],
            ['name' => 'Abdos variante 47', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/AQ9-CsvQCNI?si=e4KJqTyUmlo5rcG2', 'met_value' => 4.0],
            ['name' => 'Abdos variante 48', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/BKIkR5OTrT8?si=pwxpE0ga7mXnGMre', 'met_value' => 4.0],
            ['name' => 'Abdos variante 49', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/FnhGMHMiUF4?si=7swGQ4ZI5uZv_YXG', 'met_value' => 4.0],
            ['name' => 'Abdos variante 50', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/KE48i0UnNuU?si=d2vskNGI-GB4owAk', 'met_value' => 4.0],
            ['name' => 'Abdos variante 51', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/MDM0ZBNDGRM?si=gjb2eLq3ep78z7ql', 'met_value' => 4.0],
            ['name' => 'Abdos variante 52', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/O0vrS2TMXKM?si=HI5mKthBxI7M6onC', 'met_value' => 4.0],
            ['name' => 'Abdos variante 53', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/OeRyyZPi0f0?si=B9XyGoE_ndcTtHQF', 'met_value' => 4.0],
            ['name' => 'Abdos variante 54', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/OmPOUsIgAAI?si=yPrqr5Z4l9J7_cn9', 'met_value' => 4.0],
            ['name' => 'Abdos variante 55', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/ShC9vHtQ1VE?si=6Ril-Vhcjp_1Kmhz', 'met_value' => 4.0],
            ['name' => 'Abdos variante 56', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/Tm4QIjr0W4w?si=iL4apvMqHKeXszt8', 'met_value' => 4.0],
            ['name' => 'Abdos variante 57', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/Vqc9S60EMjo?si=RRAlnvfYeys-Gje1', 'met_value' => 4.0],
            ['name' => 'Abdos variante 58', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/aVjJJut9n4g?si=AY33rIOhlvy96p5S', 'met_value' => 4.0],
            ['name' => 'Abdos variante 59', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/dUurkUxIu_k?si=FJDrP3ClEqw-Yxrw', 'met_value' => 4.0],
            ['name' => 'Abdos variante 60', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/eeR0xBPcIA4?si=s9Xrgua0-UYE1sKp', 'met_value' => 4.0],
            ['name' => 'Abdos variante 61', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/eph63VK4UIk?si=tBtB7xdCdpuN0qYR', 'met_value' => 4.0],
            ['name' => 'Abdos variante 62', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/kyUvs-A8BC4?si=PRpHyy7SP2jaxYBK', 'met_value' => 4.0],
            ['name' => 'Abdos variante 63', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/oQc-zksDnAQ?si=rEn4IlW2OQVClsHt', 'met_value' => 4.0],
            ['name' => 'Abdos variante 64', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/oyE9EAWJyOc?si=e1lzdg-5DZ5AtXOd', 'met_value' => 4.0],
            ['name' => 'Abdos variante 65', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/pbFwUVX3-lU?si=E4VQhY4oY8OCmhin', 'met_value' => 4.0],
            ['name' => 'Abdos variante 66', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/qyONW6UbHjU?si=w1Q9VrVlfmaBBKon', 'met_value' => 4.0],
            ['name' => 'Abdos variante 67', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/tpsdzlN5GZs?si=3TEP4kqlQGr3Zrpg', 'met_value' => 4.0],
            ['name' => 'Abdos variante 68', 'category' => 'BONUS', 'sub_category' => 'ABDOS', 'video_url' => 'https://youtube.com/shorts/uuZj_7KleGc?si=l34y5sZD3LhTCivw', 'met_value' => 4.0],
            ['name' => 'Cardio maison 1', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/9P2h17GJqlg?si=wb--UzYuaDVeaI0v', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 1', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/EXJhlEGDOtM?si=GFeC481FGlmcwQWR', 'met_value' => 5.0],
            ['name' => 'Cardio maison 2', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/A-zAPau66ac?si=PoEISGE7-xt13b8I', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 2', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/FQunMsCY2S4?si=SI3TNlMwZaXPQfOt', 'met_value' => 5.0],
            ['name' => 'Cardio maison 3', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/D231oAExvbo?si=9Fq-qRW3FcejXMgP', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 3', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/Lt9IM1Btxs8?si=s15B8-5ZdlXD_jYv', 'met_value' => 5.0],
            ['name' => 'Cardio maison 4', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/DAj3ReIVqj4?si=aOQiqDs9-yr32Ecg', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 4', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/VTCVcS0ZYPU?si=p-CoR6XMp78D6Mg7', 'met_value' => 5.0],
            ['name' => 'Cardio maison 5', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/H2oDhb5h6FY?si=mfJC98p_Pr1flg5D', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 5', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/Wz_XlGy2r8I?si=FV9nXDf2GLHRXYbq', 'met_value' => 5.0],
            ['name' => 'Cardio maison 6', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/HyNQX-mZOgM?si=DNp1WqgLhsg9g1SL', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 6', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/jPH87b3jEH4?si=ujz8mJs_feOLMLb8', 'met_value' => 5.0],
            ['name' => 'Cardio maison 7', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/Hz4QAamSytE?si=IeprP10MSe_9CLDh', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 7', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/kEIZymTabJo?si=HoTkWUs08T1-Rr7O', 'met_value' => 5.0],
            ['name' => 'Cardio maison 8', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/I_mpQjwhm10?si=B0p3hFL3Nz8fiokm', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 8', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/sAYigNyptcE?si=dmPPN8vWGDPKAx4B', 'met_value' => 5.0],
            ['name' => 'Cardio maison 9', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/In16LZOKH64?si=EM80Q1CWO8tEjTbj', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 9', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/xfmRurcAx-s?si=owEgGMDmy4mmwU3u', 'met_value' => 5.0],
            ['name' => 'Cardio maison 10', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/K86XtCL2DsI?si=8IIDc94E7iIejHIR', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 10', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/y9q5Lg-Tsvw?si=GpixBHPdsI87N2EG', 'met_value' => 5.0],
            ['name' => 'Cardio maison 11', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/KxcOh0eDv4Y?si=5fk094YTmbuv0Ogq', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 11', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/GULC7LkCYN0?si=i3Zgqa0M7-Tq3R1h', 'met_value' => 5.0],
            ['name' => 'Cardio maison 12', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/LWi75HPvz3Y?si=QI-cDa9MZ5xXYs8x', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 12', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/IfUVcGOb4yg?si=mZHTPjnFgqPlDROA', 'met_value' => 5.0],
            ['name' => 'Cardio maison 13', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/LWyOnYcY-b4?si=L0BVZmhMN7vo22g7', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 13', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/SKJy-sRZzZw?si=xS9VFS4-rwGTBLDk', 'met_value' => 5.0],
            ['name' => 'Cardio maison 14', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/ObkAeR5jviY?si=n8q_kwuqglTsJeJA', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 14', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/XwppXLYfLTo?si=56rRntJw0hw4aky7', 'met_value' => 5.0],
            ['name' => 'Cardio maison 15', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/Q0gHHIOF5qs?si=3GXJxM7wrL_QGgC2', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 15', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/dL5L6TOA6Z8?si=ZbpPmgoNZOoV5763', 'met_value' => 5.0],
            ['name' => 'Cardio maison 16', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/QvWDu-caqZ0?si=PHIwhbNqfmh1HFhP', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 16', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/esHt_ozPaes?si=Kdwr5uL-wIM2dvS7', 'met_value' => 5.0],
            ['name' => 'Cardio maison 17', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/S4CkPRvMuSQ?si=CCKExNFnNqLeq5WX', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 17', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/jQsuxabTsSg?si=k2paOo5UgqutmwJV', 'met_value' => 5.0],
            ['name' => 'Cardio maison 18', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/TY1jbTBewpw?si=6ZmVhHyDfSfA5e8r', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 18', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/oSQ-SzXXXJw?si=CWDtx9fu36gIkoP-', 'met_value' => 5.0],
            ['name' => 'Cardio maison 19', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/VGUQouIknj4?si=VWjvarSN48UmNHoU', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 19', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/HWHVw1jKPQA?si=uzmZ92Pmf0D12K05', 'met_value' => 5.0],
            ['name' => 'Cardio maison 20', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/XX94L3sMPfc?si=Jy9eqRdVQpUySFxd', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 20', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/Mjj8g1pqmwY?si=SFyU6P4y63-hjx-C', 'met_value' => 5.0],
            ['name' => 'Cardio maison 21', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/YWpOhfveyOA?si=N4MD4my0BKAnP9Zv', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 21', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/3AtNuJcOtgA?si=H7PNzvMDERbqFShQ', 'met_value' => 5.0],
            ['name' => 'Cardio maison 22', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/_iPXls5g968?si=_ckfooDC4UnE8vsH', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 22', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/3LBmCMbReps?si=jLYygPI43127JozO', 'met_value' => 5.0],
            ['name' => 'Cardio maison 23', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/a4m2ly2z0AM?si=XXsaQ3s7QU-TWNmM', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 23', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/5ANwnOhZNXU?si=NGitEEOUZWFLSVkI', 'met_value' => 5.0],
            ['name' => 'Cardio maison 24', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/aqqGoG8AQ6U?si=pMqZPtDvU6WfO2rI', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 24', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/DHmtBGQKUEI?si=l-B_bMwruyeBP5PX', 'met_value' => 5.0],
            ['name' => 'Cardio maison 25', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/bRMBSum63Cg?si=XPQhI0aVK_C1MMaZ', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 25', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/Y09Q93d0Ddk?si=BPmDOVyeXGY1EWw4', 'met_value' => 5.0],
            ['name' => 'Cardio maison 26', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/cvdLWUv2TAU?si=mCDlbh2aN5NGipKl', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 26', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/cZxg5ii2tPI?si=bbEKiYS5WdoPrOre', 'met_value' => 5.0],
            ['name' => 'Cardio maison 27', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/efcAyXO_ruE?si=oA22650AVvz7Hqwk', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 27', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/hUwO4QDv7tY?si=1_SsSi3eIkdqbAG7', 'met_value' => 5.0],
            ['name' => 'Cardio maison 28', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/hckp15sC3fo?si=GJD4dZ7DZIElcDhI', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 28', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/kw3uUWC1aaU?si=w7IcX_ovXxSvIc7x', 'met_value' => 5.0],
            ['name' => 'Cardio maison 29', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/ibiPv340Bc8?si=yjTcEFKP3JZUsbsu', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 29', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/onRM9ys0FHI?si=VF9p6FVyb9Yb46Ki', 'met_value' => 5.0],
            ['name' => 'Cardio maison 30', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/j-XaHL3MZp4?si=lXk7tNyw745Vq_ob', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 30', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/u3R0fmHQkvs?si=pW6_nCgbWiE6CaSI', 'met_value' => 5.0],
            ['name' => 'Cardio maison 31', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/j90R2w2S_Ic?si=XsrxvEwhOg4cK4s3', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 31', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/u5B6ntasNtk?si=qq1qddk3bH9WeYqI', 'met_value' => 5.0],
            ['name' => 'Cardio maison 32', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/jyYi_SzP868?si=vFbEEUwM_fCUZzTU', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 32', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/4b73iXRSr8s?si=zy7p94FAjXoQZZEf', 'met_value' => 5.0],
            ['name' => 'Cardio maison 33', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/m1-yQOy87Dg?si=88C9K5w4OU-IsWvE', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 33', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/dFriMN_O7ns?si=2jOA9nLlExAC_mv7', 'met_value' => 5.0],
            ['name' => 'Cardio maison 34', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/mTbuFt8JUf4?si=o57O3TMkaX33VOnd', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 34', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/-2OVfN5SWI0?si=n6GoDPKXesMUZn-M', 'met_value' => 5.0],
            ['name' => 'Cardio maison 35', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/moCoaOAnXm8?si=MqqYq6PLqYABT9ef', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 35', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/2PeZgd-dDQA?si=lr-c73iMOb-09tRC', 'met_value' => 5.0],
            ['name' => 'Cardio maison 36', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/pFhk9fCburg?si=iIX3owhwJ5XQZLyF', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 36', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/7jhoYRjW4W8?si=4-hjpetqzflpaoyV', 'met_value' => 5.0],
            ['name' => 'Cardio maison 37', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/pVdimwL-Q-E?si=6Ea9S-y2aykZtijR', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 37', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/9dPReYSPDEo?si=PfKjpmti2uz4Thay', 'met_value' => 5.0],
            ['name' => 'Cardio maison 38', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/q4OaYt9paYE?si=faeM7FfivzYKVCpj', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 38', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/FhNhP56iF_s?si=zxXNtWTB4JMXF7mk', 'met_value' => 5.0],
            ['name' => 'Cardio maison 39', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/rL6YSzeJLpA?si=CgEgiy669otI9SoM', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 39', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/MoD4WPEUmLQ?si=1NfXeTEbD8VlqPcJ', 'met_value' => 5.0],
            ['name' => 'Cardio maison 40', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/r_E7F2CxskI?si=MXR9NvL8KyZ1idXn', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 40', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/NCII6SGPMQo?si=H0DF5bUA5GkVtoFb', 'met_value' => 5.0],
            ['name' => 'Cardio maison 41', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/sRdUS6P0Xq8?si=kR_UMNes6ER2n7XF', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 41', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/RxBivpr678U?si=eW5AmdDBTorEkXkq', 'met_value' => 5.0],
            ['name' => 'Cardio maison 42', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/syUylhYZ3Ow?si=bOr0KL3Ogo7UQSOJ', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 42', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/T3MSRFltTfk?si=kMQZPRkslEhrkxKJ', 'met_value' => 5.0],
            ['name' => 'Cardio maison 43', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/ts64BaBgL18?si=oo1mx79JEoUTM1QL', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 43', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/UOIPYVxh-XU?si=Eu5LeUfDBcLYalDM', 'met_value' => 5.0],
            ['name' => 'Cardio maison 44', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/wgb_eFMivR0?si=gzEKlRRBj0y2aht5', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 44', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/V24bj9s2iFE?si=yJ50nnGjwswF3_FM', 'met_value' => 5.0],
            ['name' => 'Cardio maison 45', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/xmiJbgFUFX0?si=Wa6hEJ_v4m3YmoO6', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 45', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/X1CY9w4ZhR0?si=Q15omAIW_7KuSAkr', 'met_value' => 5.0],
            ['name' => 'Cardio maison 46', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/yfYFlqHZ1i8?si=c9Tyhm2EwZ0O6-Xb', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 46', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/_Vcrq4FuS18?si=tRrUymSWnYb6-A_K', 'met_value' => 5.0],
            ['name' => 'Cardio maison 47', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/1bOtpKjmYFA?si=FWmid5yTN4n2o1_x', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 47', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/jdkxTgOreTw?si=SsXV8QdBlaGw5Zht', 'met_value' => 5.0],
            ['name' => 'Cardio maison 48', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/AQffsv8l0fw?si=tChlXOBh9S1zmXiT', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 48', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/psNY9JBuGA8?si=YmyKYaEVeqhYg7vs', 'met_value' => 5.0],
            ['name' => 'Cardio maison 49', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/Doa0MuXwz1Y?si=EQcLa1ImLX1YyyJl', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 49', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/rfstIp6U0EQ?si=-guGlWjYEyGChzNX', 'met_value' => 5.0],
            ['name' => 'Cardio maison 50', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/HNwUQUMTtCI?si=6WzNVWRHLlSUMVNK', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 50', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/vnoFLq_Kr_c?si=J9UjS4QtqT5y6VUS', 'met_value' => 5.0],
            ['name' => 'Cardio maison 51', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/MRFEC7P72yc?si=s8PWUilMeEdsxT-g', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 51', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/2PVXepKrot4?si=R0hl4Qd4WnOXymwc', 'met_value' => 5.0],
            ['name' => 'Cardio maison 52', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/NEc2KFrEons?si=YIPAsK97SFG1zFU_', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 52', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/BY6L8Au4jXk?si=TMwVL08x1JWOjWHa', 'met_value' => 5.0],
            ['name' => 'Cardio maison 53', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/YWZ48FX5pbY?si=NxhVfGAxKOFl7l8a', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 53', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/C0zVzX_vDFM?si=3Kg4Cz3vZVY_z3xB', 'met_value' => 5.0],
            ['name' => 'Cardio maison 54', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/g-scz7j6qeQ?si=qBE1LUgmc9oqA1LF', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 54', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/KCkIY7y8RKQ?si=dXdoUP736_PtF4JE', 'met_value' => 5.0],
            ['name' => 'Cardio maison 55', 'category' => 'MAISON', 'sub_category' => 'PERTE DE POIDS', 'video_url' => 'https://youtube.com/shorts/j8Q1FytUvaM?si=Mc7Euu60703oj2k_', 'met_value' => 8.0],
            ['name' => 'Renforcement maison 55', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/R888LJ_r1ck?si=-YfnBXGyWBrbRyB8', 'met_value' => 5.0],
            ['name' => 'Renforcement maison 56', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/UINRkR9L_Go?si=KYYE4XphbXflBQrX', 'met_value' => 5.0],
            ['name' => 'Renforcement maison 57', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/inlGW14Y2vI?si=JQV0qwFbLvq0bCwq', 'met_value' => 5.0],
            ['name' => 'Renforcement maison 58', 'category' => 'MAISON', 'sub_category' => 'RENFORCEMENT', 'video_url' => 'https://youtube.com/shorts/zjAWS7dJjA0?si=VE_iOU5e2M2iPRaU', 'met_value' => 5.0],
            ['name' => 'Mobilité ishios jambiers 1', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'ISHIOS JAMBIERS', 'video_url' => 'https://youtube.com/shorts/ydpzy7IIFOM?si=22wthTIPWdCUwhzy', 'met_value' => 2.5],
            ['name' => 'Renforcement chevilles 1', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'CHEVILLES', 'video_url' => 'https://youtube.com/shorts/mma9QnLC0kc?si=onXdPvGf9cyAUqmN', 'met_value' => 4.0],
            ['name' => 'Renforcement moyen fessiers 1', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'MOYEN FESSIERS', 'video_url' => 'https://youtube.com/shorts/z_tqjn-LV24?si=6Dn8ZiFVZcEiK3As', 'met_value' => 4.0],
            ['name' => 'Renforcement pieds 1', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'PIEDS', 'video_url' => 'https://youtube.com/shorts/kQjdeoz7fSY?si=Pvvc5XHnQbYzgaTG', 'met_value' => 4.0],
            ['name' => 'Renforcement quadriceps 1', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'QUADRICEPS', 'video_url' => 'https://youtube.com/shorts/05HR5YAvGDc?si=qUpGdv1_GX-7roVd', 'met_value' => 4.0],
            ['name' => 'Renforcement psoas 1', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'PSOAS', 'video_url' => 'https://youtube.com/shorts/kdokuhYUhvI?si=phE1j4BrXoEdjvrO', 'met_value' => 4.0],
            ['name' => 'Renforcement mollets 1', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'MOLLETS', 'video_url' => 'https://youtube.com/shorts/3EB7ZAB5AWo?si=bQwB2rFmyLNhedIA', 'met_value' => 4.0],
            ['name' => 'Mobilité ishios jambiers 2', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'ISHIOS JAMBIERS', 'video_url' => 'https://youtube.com/shorts/Pdy2NVmSfko?si=AGNdfKfcaZcffczg', 'met_value' => 2.5],
            ['name' => 'Renforcement chevilles 2', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'CHEVILLES', 'video_url' => 'https://youtube.com/shorts/_ONChb1TqNE?si=hPlgmHDkqvh2Q5N5', 'met_value' => 4.0],
            ['name' => 'Renforcement pieds 2', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'PIEDS', 'video_url' => 'https://youtube.com/shorts/zIm7uiXVcdo?si=6ltw6L1JhJHB6qhn', 'met_value' => 4.0],
            ['name' => 'Renforcement quadriceps 2', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'QUADRICEPS', 'video_url' => 'https://youtube.com/shorts/IKmo4cBFEk4?si=xyuXIFAWx8MuXBaD', 'met_value' => 4.0],
            ['name' => 'Renforcement psoas 2', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'PSOAS', 'video_url' => 'https://youtube.com/shorts/5vlR3j5ULog?si=sU8YbO7_17djAGRQ', 'met_value' => 4.0],
            ['name' => 'Renforcement mollets 2', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'MOLLETS', 'video_url' => 'https://youtube.com/shorts/L0_QOx5h7CY?si=GBw67rUYMh-5vcpg', 'met_value' => 4.0],
            ['name' => 'Mobilité chevilles 1', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'CHEVILLES', 'video_url' => 'https://youtube.com/shorts/kGDXD0mt6RU?si=QwLMJaCGTFkJep_E', 'met_value' => 2.5],
            ['name' => 'Mobilité genoux 1', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'GENOUX', 'video_url' => 'https://youtube.com/shorts/P9q9s73dhcY?si=5HDjmzl-bvyq2aNB', 'met_value' => 2.5],
            ['name' => 'Mobilité pieds 1', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'PIEDS', 'video_url' => 'https://youtube.com/shorts/ALnYLAPsD1w?si=6CEAcOMuz2k3FuXh', 'met_value' => 2.5],
            ['name' => 'Mobilité ishios jambiers 3', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'ISHIOS JAMBIERS', 'video_url' => 'https://youtube.com/shorts/AVluREI0nkU?si=4af1ajbjjyzvb-XX', 'met_value' => 2.5],
            ['name' => 'Renforcement adducteurs 1', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'ADDUCTEURS', 'video_url' => 'https://youtube.com/shorts/0Y1d_xR0HKY?si=BiT5U8RZCsqV4rkm', 'met_value' => 4.0],
            ['name' => 'Renforcement chevilles 3', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'CHEVILLES', 'video_url' => 'https://youtube.com/shorts/taAgzIx25pA?si=yLzCoQGugLEZlQls', 'met_value' => 4.0],
            ['name' => 'Renforcement moyen fessiers 2', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'MOYEN FESSIERS', 'video_url' => 'https://youtube.com/shorts/WWp79ePdI4g?si=R3HArwJKs1SfhBMG', 'met_value' => 4.0],
            ['name' => 'Renforcement pieds 3', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'PIEDS', 'video_url' => 'https://youtube.com/shorts/SY--0iWYaC8?si=ITDyUzkQeUroJ0ps', 'met_value' => 4.0],
            ['name' => 'Renforcement quadriceps 3', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'QUADRICEPS', 'video_url' => 'https://youtube.com/shorts/bziCwpR4n8w?si=JjOiz1oLiVwtG1AG', 'met_value' => 4.0],
            ['name' => 'Renforcement psoas 3', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'PSOAS', 'video_url' => 'https://youtube.com/shorts/uvgma7UsCKM?si=_3DOD4Sl2mghSPTx', 'met_value' => 4.0],
            ['name' => 'Renforcement mollets 3', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'MOLLETS', 'video_url' => 'https://youtube.com/shorts/JRd9zoOL_dY?si=iFoZ3-tLrTn_IIwy', 'met_value' => 4.0],
            ['name' => 'Mobilité chevilles 2', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'CHEVILLES', 'video_url' => 'https://youtube.com/shorts/AXj80wM2dnY?si=3ad3H97_IdP509uB', 'met_value' => 2.5],
            ['name' => 'Mobilité hanches 1', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'HANCHES', 'video_url' => 'https://youtube.com/shorts/p6Em-gZgvuU?si=9vYRqLpYMNA_dPrQ', 'met_value' => 2.5],
            ['name' => 'Mobilité pieds 2', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'PIEDS', 'video_url' => 'https://youtube.com/shorts/kMO8D8eb7Xo?si=eRjlCSpNx8FEDkem', 'met_value' => 2.5],
            ['name' => 'Mobilité ishios jambiers 4', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'ISHIOS JAMBIERS', 'video_url' => 'https://youtube.com/shorts/yWUxQQP_UVI?si=xRGLDyCsyLdpZa15', 'met_value' => 2.5],
            ['name' => 'Renforcement adducteurs 2', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'ADDUCTEURS', 'video_url' => 'https://youtube.com/shorts/8JuNUeqNg7w?si=szgaMuqcYqPOTW8p', 'met_value' => 4.0],
            ['name' => 'Renforcement chevilles 4', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'CHEVILLES', 'video_url' => 'https://youtube.com/shorts/tPFFfJ093ns?si=DXjehanIZ2Hh4ZA3', 'met_value' => 4.0],
            ['name' => 'Renforcement moyen fessiers 3', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'MOYEN FESSIERS', 'video_url' => 'https://youtube.com/shorts/Ecwp7jL_8u4?si=gTSSGCkQpgr5Wag1', 'met_value' => 4.0],
            ['name' => 'Renforcement pieds 4', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'PIEDS', 'video_url' => 'https://youtube.com/shorts/_onHMkRo5kc?si=2ixd4llddddkgZmR', 'met_value' => 4.0],
            ['name' => 'Renforcement quadriceps 4', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'QUADRICEPS', 'video_url' => 'https://youtube.com/shorts/GzrMAtMgvFQ?si=mWjwHcJT0tB4d2TP', 'met_value' => 4.0],
            ['name' => 'Renforcement psoas 4', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'PSOAS', 'video_url' => 'https://youtube.com/shorts/RmMbh-yXGUQ?si=HR5Y5HxFFpgkxcNe', 'met_value' => 4.0],
            ['name' => 'Renforcement mollets 4', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'MOLLETS', 'video_url' => 'https://youtube.com/shorts/h3csfW9JrdA?si=7JtXoOusaMtIpzVg', 'met_value' => 4.0],
            ['name' => 'Renforcement fessiers 1', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'FESSIERS', 'video_url' => 'https://youtube.com/shorts/wGrw4sHYzqo?si=1v2ytFKXyQa5-1u1', 'met_value' => 4.0],
            ['name' => 'Mobilité chevilles 3', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'CHEVILLES', 'video_url' => 'https://youtube.com/shorts/IbxH9o3TqUA?si=_sGfxKJCqtg6pUBC', 'met_value' => 2.5],
            ['name' => 'Mobilité ishios jambiers 5', 'category' => 'KINE MOBILITÉ', 'sub_category' => 'ISHIOS JAMBIERS', 'video_url' => 'https://youtube.com/shorts/Hg9bUiEmnUc?si=PLyixXaOrMC8xgHj', 'met_value' => 2.5],
            ['name' => 'Renforcement adducteurs 3', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'ADDUCTEURS', 'video_url' => 'https://youtube.com/shorts/pHusO6546sc?si=y-duF5B-kC33_Zir', 'met_value' => 4.0],
            ['name' => 'Renforcement chevilles 5', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'CHEVILLES', 'video_url' => 'https://youtube.com/shorts/vaTGg6ByiPg?si=bCfedubbTwTVdWvH', 'met_value' => 4.0],
            ['name' => 'Renforcement moyen fessiers 4', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'MOYEN FESSIERS', 'video_url' => 'https://youtube.com/shorts/_8JfY3ofVnE?si=K-UmRwFO0BVjAx7c', 'met_value' => 4.0],
            ['name' => 'Renforcement pieds 5', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'PIEDS', 'video_url' => 'https://youtube.com/shorts/lY1YjXsd5s4?si=c4fAEwVghKzGOllS', 'met_value' => 4.0],
            ['name' => 'Renforcement quadriceps 5', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'QUADRICEPS', 'video_url' => 'https://youtube.com/shorts/zdrJ4GKFQ6c?si=fUecFqoCyN__IFYq', 'met_value' => 4.0],
            ['name' => 'Renforcement psoas 5', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'PSOAS', 'video_url' => 'https://youtube.com/shorts/nznd4lI3KEA?si=8Kn7ZwCENvytEp7B', 'met_value' => 4.0],
            ['name' => 'Renforcement mollets 5', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'MOLLETS', 'video_url' => 'https://youtube.com/shorts/ZM6c015fDl0?si=Yixah63kr4-iqPFO', 'met_value' => 4.0],
            ['name' => 'Renforcement fessiers 2', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'FESSIERS', 'video_url' => 'https://youtube.com/shorts/VxSiapVUcvQ?si=pu9uXihHne4sc_OW', 'met_value' => 4.0],
            ['name' => 'Renforcement pieds 6', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'PIEDS', 'video_url' => 'https://youtube.com/shorts/rgu8MNT89ME?si=SmmuZPR_PfGEX3PY', 'met_value' => 4.0],
            ['name' => 'Renforcement quadriceps 6', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'QUADRICEPS', 'video_url' => 'https://youtube.com/shorts/TCdMMQmJOK8?si=gY1UmIqNXqbbbDDo', 'met_value' => 4.0],
            ['name' => 'Renforcement psoas 6', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'PSOAS', 'video_url' => 'https://youtube.com/shorts/KrKj07QJbx0?si=3LB0YL4KyV5JmS-X', 'met_value' => 4.0],
            ['name' => 'Renforcement fessiers 3', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'FESSIERS', 'video_url' => 'https://youtube.com/shorts/3nzCdHztpqM?si=lbuQSX_ZOgE2yhqJ', 'met_value' => 4.0],
            ['name' => 'Renforcement fessiers 4', 'category' => 'KINE RENFORCEMENT', 'sub_category' => 'FESSIERS', 'video_url' => 'https://youtube.com/shorts/ejXv68SlgJ0?si=RaNT5eZCIJ1vtWEd', 'met_value' => 4.0],
            ['name' => 'Cardio exercice 1', 'category' => 'CARDIO', 'sub_category' => 'CARDIO', 'video_url' => 'https://youtube.com/shorts/yQuD5T7MSIY?si=_q8MkPirWqn9khC_', 'met_value' => 8.0],
            ['name' => 'Cardio exercice 2', 'category' => 'CARDIO', 'sub_category' => 'CARDIO', 'video_url' => 'https://youtube.com/shorts/1VFwn0chdOM?si=eT83mW-rSSwXjXvy', 'met_value' => 8.0],
            ['name' => 'Cardio exercice 3', 'category' => 'CARDIO', 'sub_category' => 'CARDIO', 'video_url' => 'https://youtube.com/shorts/KxiO7DfDuO4?si=eHkyxQJtrW_ufMgc', 'met_value' => 8.0],
            ['name' => 'Cardio exercice 4', 'category' => 'CARDIO', 'sub_category' => 'CARDIO', 'video_url' => 'https://youtube.com/shorts/xW0n7BhD80A?si=StHKpUnCY-Sobhhu', 'met_value' => 8.0],
            ['name' => 'Cardio exercice 5', 'category' => 'CARDIO', 'sub_category' => 'CARDIO', 'video_url' => 'https://youtube.com/shorts/IL2fF-2G1SA?si=qbo96dxP9mnaue2l', 'met_value' => 8.0],
            ['name' => 'Cardio exercice 6', 'category' => 'CARDIO', 'sub_category' => 'CARDIO', 'video_url' => 'https://youtube.com/shorts/0G6vdBt6uys?si=gopLv8wkx1yxMkPo', 'met_value' => 8.0],
            ['name' => 'Cardio exercice 7', 'category' => 'CARDIO', 'sub_category' => 'CARDIO', 'video_url' => 'https://youtube.com/shorts/HwZQ9Cz2Igo?si=AjyYtfiimU-7udg1', 'met_value' => 8.0],
            ['name' => 'Cardio exercice 8', 'category' => 'CARDIO', 'sub_category' => 'CARDIO', 'video_url' => 'https://youtube.com/shorts/60ktrN58nhg?si=W1pe7Fz3NY4AnLrO', 'met_value' => 8.0],
            ['name' => 'Cardio exercice 9', 'category' => 'CARDIO', 'sub_category' => 'CARDIO', 'video_url' => 'https://youtube.com/shorts/PqkRs9SLU8U?si=ZImTMweVlCawyPML', 'met_value' => 8.0],
            ['name' => 'Marche tapis', 'category' => 'CARDIO', 'sub_category' => 'MARCHE', 'video_url' => 'https://youtube.com/shorts/OEnjPm4zPvE?si=aBc1dEf2gHi3jKl4', 'met_value' => 4.0],
            ['name' => 'Marche en côte tapis', 'category' => 'CARDIO', 'sub_category' => 'MARCHE EN CÔTE', 'video_url' => 'https://youtube.com/shorts/d1fzhB1glsc?si=mNo5pQr6sTu7vWx8', 'met_value' => 5.5],
        ];

        foreach ($exercises as $exercise) {
            Exercise::create($exercise);
        }

        $this->command->info('  Created ' . count($exercises) . ' exercises');
    }

    private function seedNutritionAdvice(): void
    {
        $this->command->info('Seeding Nutrition Advice...');

        $advices = [
            [
                'condition_name' => 'Fatigue / baisse d\'énergie',
                'foods_to_eat' => ['Banane', 'Dattes', 'Orange', 'Épinards', 'Betterave', 'Lentilles', 'Avoine', 'Riz', 'Bœuf', 'Poulet', 'Sardine'],
                'foods_to_avoid' => ['Sucres rapides', 'Alcool', 'Café en excès'],
                'prophetic_advice_fr' => 'Vitamines B1, B6, B12, C. Minéraux: Fer, Magnésium. Tisane: Ginseng. Huile d\'olive. Miel de fleurs.',
                'prophetic_advice_en' => 'Vitamins B1, B6, B12, C. Minerals: Iron, Magnesium. Tisane: Ginseng. Olive oil. Flower honey.',
                'prophetic_advice_ar' => 'فيتامينات B1، B6، B12، C. المعادن: الحديد والمغنيسيوم. شاي الجينسنغ. زيت الزيتون. عسل الزهور.'
            ],
            [
                'condition_name' => 'Crampes musculaires / spasmes',
                'foods_to_eat' => ['Banane', 'Abricots secs', 'Brocoli', 'Épinards', 'Haricots rouges', 'Riz complet', 'Saumon'],
                'foods_to_avoid' => ['Alcool', 'Café', 'Sel en excès'],
                'prophetic_advice_fr' => 'Vitamine B6. Minéraux: Magnésium, Sodium, Potassium. Tisane: Ortie. Huile d\'amande. Miel de tilleul. Eau riche en magnésium.',
                'prophetic_advice_en' => 'Vitamin B6. Minerals: Magnesium, Sodium, Potassium. Tisane: Nettle. Almond oil. Linden honey. Magnesium-rich water.',
                'prophetic_advice_ar' => 'فيتامين B6. المعادن: المغنيسيوم، الصوديوم، البوتاسيوم. شاي القراص. زيت اللوز. عسل الزيزفون.'
            ],
            [
                'condition_name' => 'Blessures musculaires / élongations',
                'foods_to_eat' => ['Ananas', 'Fruits rouges', 'Curcuma', 'Ail', 'Quinoa', 'Dinde', 'Œufs', 'Thon', 'Saumon'],
                'foods_to_avoid' => ['Aliments inflammatoires', 'Sucres raffinés', 'Huiles hydrogénées'],
                'prophetic_advice_fr' => 'Vitamines C, E. Minéral: Zinc. Tisane: Gingembre. Huile d\'olive. Curcuma + poivre noir.',
                'prophetic_advice_en' => 'Vitamins C, E. Mineral: Zinc. Tisane: Ginger. Olive oil. Turmeric + black pepper.',
                'prophetic_advice_ar' => 'فيتامينات C، E. معدن: الزنك. شاي الزنجبيل. زيت الزيتون. الكركم + الفلفل الأسود.'
            ],
            [
                'condition_name' => 'Entorses / inflammation articulaire',
                'foods_to_eat' => ['Cerise', 'Grenade', 'Chou', 'Brocoli', 'Maquereau'],
                'foods_to_avoid' => ['Viandes rouges', 'Produits laitiers en excès', 'Aliments transformés'],
                'prophetic_advice_fr' => 'Vitamines D, K, C. Minéraux: Cuivre, Zinc. Tisane: Harpagophytum. Huile de colza. Gelée royale.',
                'prophetic_advice_en' => 'Vitamins D, K, C. Minerals: Copper, Zinc. Tisane: Harpagophytum. Rapeseed oil. Royal jelly.',
                'prophetic_advice_ar' => 'فيتامينات D، K، C. المعادن: النحاس، الزنك. شاي الهارباغوفيتوم. زيت بذور اللفت. غذاء ملكات النحل.'
            ],
            [
                'condition_name' => 'Tendinites',
                'foods_to_eat' => ['Ananas', 'Épinards', 'Sardine'],
                'foods_to_avoid' => ['Sucres', 'Alcool', 'Graisses saturées'],
                'prophetic_advice_fr' => 'Vitamine C. Minéral: Silicium. Tisane: Prêle. Huile de noix. Collagène.',
                'prophetic_advice_en' => 'Vitamin C. Mineral: Silicon. Tisane: Horsetail. Walnut oil. Collagen.',
                'prophetic_advice_ar' => 'فيتامين C. معدن: السيليكون. شاي ذيل الحصان. زيت الجوز. الكولاجين.'
            ],
            [
                'condition_name' => 'Récupération post-match',
                'foods_to_eat' => ['Banane', 'Raisin', 'Betterave', 'Riz blanc', 'Poulet', 'Œufs', 'Saumon'],
                'foods_to_avoid' => ['Alcool', 'Aliments gras', 'Sucres rapides en excès'],
                'prophetic_advice_fr' => 'Vitamines B, C. Minéral: Magnésium. Tisane: Rooibos. Huile d\'olive. Miel d\'acacia. Lait fermenté.',
                'prophetic_advice_en' => 'Vitamins B, C. Mineral: Magnesium. Tisane: Rooibos. Olive oil. Acacia honey. Fermented milk.',
                'prophetic_advice_ar' => 'فيتامينات B، C. معدن: المغنيسيوم. شاي رويبوس. زيت الزيتون. عسل السنط. الحليب المخمر.'
            ],
            [
                'condition_name' => 'Perte musculaire / catabolisme',
                'foods_to_eat' => ['Lentilles', 'Quinoa', 'Bœuf', 'Œufs', 'Thon'],
                'foods_to_avoid' => ['Jeûne prolongé', 'Déficit calorique excessif'],
                'prophetic_advice_fr' => 'Vitamines B6, D. Minéral: Zinc. Huile d\'olive. Collagène.',
                'prophetic_advice_en' => 'Vitamins B6, D. Mineral: Zinc. Olive oil. Collagen.',
                'prophetic_advice_ar' => 'فيتامينات B6، D. معدن: الزنك. زيت الزيتون. الكولاجين.'
            ],
            [
                'condition_name' => 'Stress / troubles du sommeil',
                'foods_to_eat' => ['Cerise', 'Banane', 'Avoine'],
                'foods_to_avoid' => ['Caféine après 14h', 'Alcool', 'Écrans avant le coucher'],
                'prophetic_advice_fr' => 'Vitamine B6. Minéral: Magnésium. Tisane: Tilleul. Huile de noix. Miel de tilleul. Chocolat noir.',
                'prophetic_advice_en' => 'Vitamin B6. Mineral: Magnesium. Tisane: Linden. Walnut oil. Linden honey. Dark chocolate.',
                'prophetic_advice_ar' => 'فيتامين B6. معدن: المغنيسيوم. شاي الزيزفون. زيت الجوز. عسل الزيزفون. الشوكولاتة الداكنة.'
            ],
            [
                'condition_name' => 'Déshydratation',
                'foods_to_eat' => ['Pastèque', 'Concombre'],
                'foods_to_avoid' => ['Alcool', 'Café en excès', 'Sel en excès'],
                'prophetic_advice_fr' => 'Minéraux: Sodium, Potassium. Miel de fleurs. Eau bicarbonatée.',
                'prophetic_advice_en' => 'Minerals: Sodium, Potassium. Flower honey. Bicarbonate water.',
                'prophetic_advice_ar' => 'المعادن: الصوديوم، البوتاسيوم. عسل الزهور. الماء الغني بالبيكاربونات.'
            ],
            [
                'condition_name' => 'Obésité / prise de poids excessive',
                'foods_to_eat' => ['Pomme', 'Légumes verts', 'Lentilles', 'Quinoa', 'Viande maigre', 'Poisson blanc'],
                'foods_to_avoid' => ['Sucres raffinés', 'Graisses saturées', 'Alcool', 'Sodas'],
                'prophetic_advice_fr' => 'Tisane: Thé vert. Huile d\'olive. Épices (cannelle, curcuma).',
                'prophetic_advice_en' => 'Tisane: Green tea. Olive oil. Spices (cinnamon, turmeric).',
                'prophetic_advice_ar' => 'شاي: الشاي الأخضر. زيت الزيتون. التوابل (القرفة، الكركم).'
            ],
            [
                'condition_name' => 'Anémie / déficit en fer',
                'foods_to_eat' => ['Orange', 'Épinards', 'Lentilles', 'Quinoa', 'Foie', 'Boudin'],
                'foods_to_avoid' => ['Thé avec les repas', 'Café avec les repas'],
                'prophetic_advice_fr' => 'Vitamines C, B12. Minéral: Fer. Toujours associer vitamine C avec les sources de fer.',
                'prophetic_advice_en' => 'Vitamins C, B12. Mineral: Iron. Always combine vitamin C with iron sources.',
                'prophetic_advice_ar' => 'فيتامينات C، B12. معدن: الحديد. دائماً اجمع فيتامين C مع مصادر الحديد.'
            ],
            [
                'condition_name' => 'Diabète / résistance à l\'insuline',
                'foods_to_eat' => ['Légumes verts', 'Légumineuses', 'Céréales complètes', 'Poisson'],
                'foods_to_avoid' => ['Sucres rapides', 'Pain blanc', 'Riz blanc', 'Jus de fruits'],
                'prophetic_advice_fr' => 'Privilégiez les aliments à index glycémique bas. Nigelle (Habba Sauda) peut aider à réguler la glycémie.',
                'prophetic_advice_en' => 'Favor low glycemic index foods. Black seed (Habba Sauda) may help regulate blood sugar.',
                'prophetic_advice_ar' => 'فضل الأطعمة ذات المؤشر الجلايسيمي المنخفض. الحبة السوداء قد تساعد في تنظيم سكر الدم.'
            ],
            // Additional conditions from DIPODDI PROGRAMME.xlsx
            [
                'condition_name' => 'Toux',
                'foods_to_eat' => ['Miel', 'Citron', 'Gingembre', 'Thym', 'Oignon'],
                'foods_to_avoid' => ['Produits laitiers', 'Aliments froids', 'Sucres'],
                'prophetic_advice_fr' => 'Nigelle (Habba Sauda): Bronchodilatateur et anti-inflammatoire. Orge (Talbina): Farine d\'orge cuite dans du lait avec miel. Miel de Thym/Eucalyptus: 1 càs 3x/jour.',
                'prophetic_advice_en' => 'Black seed (Habba Sauda): Bronchodilator and anti-inflammatory. Barley (Talbina): Barley flour cooked in milk with honey. Thyme/Eucalyptus honey: 1 tbsp 3x/day.',
                'prophetic_advice_ar' => 'الحبة السوداء: موسع للشعب الهوائية ومضاد للالتهابات. التلبينة: دقيق الشعير مطبوخ في الحليب مع العسل. عسل الزعتر: ملعقة كبيرة 3 مرات يوميًا.'
            ],
            [
                'condition_name' => 'Pharyngite',
                'foods_to_eat' => ['Miel', 'Citron', 'Propolis', 'Thym'],
                'foods_to_avoid' => ['Aliments acides', 'Épices fortes', 'Alcool'],
                'prophetic_advice_fr' => 'Gargarisme au miel de Thym dilué. Propolis en spray. Infusion de thym + miel.',
                'prophetic_advice_en' => 'Thyme honey gargle diluted. Propolis spray. Thyme infusion + honey.',
                'prophetic_advice_ar' => 'غرغرة بعسل الزعتر المخفف. رذاذ البروبوليس. منقوع الزعتر مع العسل.'
            ],
            [
                'condition_name' => 'Migraine',
                'foods_to_eat' => ['Gingembre', 'Menthe', 'Café (modéré)', 'Eau'],
                'foods_to_avoid' => ['Alcool', 'Fromages vieillis', 'Chocolat', 'Nitrates'],
                'prophetic_advice_fr' => 'Huile de nigelle en massage sur les tempes. Hijama sur les points de tête. Infusion de menthe poivrée.',
                'prophetic_advice_en' => 'Black seed oil massage on temples. Hijama on head points. Peppermint infusion.',
                'prophetic_advice_ar' => 'تدليك زيت الحبة السوداء على الصدغين. الحجامة على نقاط الرأس. منقوع النعناع.'
            ],
            [
                'condition_name' => 'Dépression',
                'foods_to_eat' => ['Poisson gras', 'Noix', 'Chocolat noir', 'Banane', 'Avoine'],
                'foods_to_avoid' => ['Alcool', 'Sucres raffinés', 'Aliments transformés'],
                'prophetic_advice_fr' => 'Nigelle: Équilibrant de l\'humeur. Miel + pollen: Énergie naturelle. Tisane de safran.',
                'prophetic_advice_en' => 'Black seed: Mood balancer. Honey + pollen: Natural energy. Saffron tea.',
                'prophetic_advice_ar' => 'الحبة السوداء: موازن للمزاج. العسل مع حبوب اللقاح: طاقة طبيعية. شاي الزعفران.'
            ],
            [
                'condition_name' => 'Insomnies',
                'foods_to_eat' => ['Lait chaud', 'Miel', 'Cerise', 'Banane', 'Amandes'],
                'foods_to_avoid' => ['Caféine', 'Alcool', 'Repas lourds le soir'],
                'prophetic_advice_fr' => 'Lait chaud + miel avant le coucher. Tisane de tilleul ou camomille. Récitation des sourates protectrices.',
                'prophetic_advice_en' => 'Warm milk + honey before bed. Linden or chamomile tea. Recitation of protective surahs.',
                'prophetic_advice_ar' => 'حليب دافئ مع العسل قبل النوم. شاي الزيزفون أو البابونج. قراءة السور الحافظة.'
            ],
            [
                'condition_name' => 'Constipation',
                'foods_to_eat' => ['Figues', 'Pruneaux', 'Eau', 'Fibres', 'Huile d\'olive'],
                'foods_to_avoid' => ['Aliments raffinés', 'Riz blanc', 'Banane non mûre'],
                'prophetic_advice_fr' => 'Figues sèches trempées. Huile d\'olive à jeun. Miel + eau tiède le matin.',
                'prophetic_advice_en' => 'Soaked dried figs. Olive oil on empty stomach. Honey + warm water in morning.',
                'prophetic_advice_ar' => 'تين مجفف منقوع. زيت الزيتون على معدة فارغة. عسل مع ماء دافئ صباحًا.'
            ],
            [
                'condition_name' => 'Diarrhée',
                'foods_to_eat' => ['Riz blanc', 'Banane', 'Pomme cuite', 'Carottes cuites'],
                'foods_to_avoid' => ['Produits laitiers', 'Fibres', 'Aliments gras', 'Café'],
                'prophetic_advice_fr' => 'Eau de riz. Miel de Sidr. Talbina (bouillie d\'orge).',
                'prophetic_advice_en' => 'Rice water. Sidr honey. Talbina (barley porridge).',
                'prophetic_advice_ar' => 'ماء الأرز. عسل السدر. التلبينة.'
            ],
            [
                'condition_name' => 'Cholestérol',
                'foods_to_eat' => ['Avoine', 'Noix', 'Poisson gras', 'Huile d\'olive', 'Légumineuses'],
                'foods_to_avoid' => ['Graisses saturées', 'Viandes grasses', 'Fritures'],
                'prophetic_advice_fr' => 'Nigelle quotidienne. Huile d\'olive vierge. Ail cru (1 gousse/jour).',
                'prophetic_advice_en' => 'Daily black seed. Virgin olive oil. Raw garlic (1 clove/day).',
                'prophetic_advice_ar' => 'الحبة السوداء يوميًا. زيت الزيتون البكر. ثوم نيء (فص واحد يوميًا).'
            ],
            [
                'condition_name' => 'Hypertension',
                'foods_to_eat' => ['Banane', 'Épinards', 'Betterave', 'Ail', 'Céleri'],
                'foods_to_avoid' => ['Sel', 'Alcool', 'Caféine en excès', 'Aliments transformés'],
                'prophetic_advice_fr' => 'Nigelle: Régulateur tensionnel. Ail: Vasodilatateur naturel. Hibiscus (Karkadé): Infusion.',
                'prophetic_advice_en' => 'Black seed: Blood pressure regulator. Garlic: Natural vasodilator. Hibiscus (Karkadé): Infusion.',
                'prophetic_advice_ar' => 'الحبة السوداء: منظم لضغط الدم. الثوم: موسع طبيعي للأوعية. الكركديه: منقوع.'
            ],
            [
                'condition_name' => 'Rhumatismes',
                'foods_to_eat' => ['Poisson gras', 'Curcuma', 'Gingembre', 'Fruits rouges'],
                'foods_to_avoid' => ['Viande rouge', 'Sucres', 'Aliments transformés'],
                'prophetic_advice_fr' => 'Hijama sur les articulations. Huile de nigelle en massage. Miel de Sidr.',
                'prophetic_advice_en' => 'Hijama on joints. Black seed oil massage. Sidr honey.',
                'prophetic_advice_ar' => 'الحجامة على المفاصل. تدليك بزيت الحبة السوداء. عسل السدر.'
            ],
            [
                'condition_name' => 'Grippe',
                'foods_to_eat' => ['Bouillon de poulet', 'Ail', 'Gingembre', 'Miel', 'Citron'],
                'foods_to_avoid' => ['Produits laitiers', 'Sucres', 'Aliments froids'],
                'prophetic_advice_fr' => 'Nigelle + miel: Immunostimulant. Talbina: Réconfortant. Eau de Zamzam si disponible.',
                'prophetic_advice_en' => 'Black seed + honey: Immune booster. Talbina: Comforting. Zamzam water if available.',
                'prophetic_advice_ar' => 'الحبة السوداء مع العسل: منشط للمناعة. التلبينة: مريحة. ماء زمزم إن توفر.'
            ],
            [
                'condition_name' => 'Nausées',
                'foods_to_eat' => ['Gingembre', 'Menthe', 'Citron', 'Crackers'],
                'foods_to_avoid' => ['Aliments gras', 'Odeurs fortes', 'Café'],
                'prophetic_advice_fr' => 'Gingembre frais ou en infusion. Menthe fraîche. Miel dilué.',
                'prophetic_advice_en' => 'Fresh ginger or infusion. Fresh mint. Diluted honey.',
                'prophetic_advice_ar' => 'زنجبيل طازج أو منقوع. نعناع طازج. عسل مخفف.'
            ],
            [
                'condition_name' => 'Gastro-entérite',
                'foods_to_eat' => ['Riz blanc', 'Pomme cuite', 'Banane', 'Bouillon'],
                'foods_to_avoid' => ['Produits laitiers', 'Fibres', 'Aliments gras', 'Épices'],
                'prophetic_advice_fr' => 'Eau de riz. Miel de Sidr. Réhydratation: eau + sel + miel.',
                'prophetic_advice_en' => 'Rice water. Sidr honey. Rehydration: water + salt + honey.',
                'prophetic_advice_ar' => 'ماء الأرز. عسل السدر. إعادة الترطيب: ماء + ملح + عسل.'
            ],
            [
                'condition_name' => 'Sciatique',
                'foods_to_eat' => ['Curcuma', 'Gingembre', 'Poisson gras', 'Légumes verts'],
                'foods_to_avoid' => ['Aliments inflammatoires', 'Alcool', 'Sucres'],
                'prophetic_advice_fr' => 'Hijama sur le trajet du nerf. Huile de nigelle chaude en massage. Miel + curcuma.',
                'prophetic_advice_en' => 'Hijama on nerve path. Warm black seed oil massage. Honey + turmeric.',
                'prophetic_advice_ar' => 'الحجامة على مسار العصب. تدليك بزيت الحبة السوداء الدافئ. عسل + كركم.'
            ],
            [
                'condition_name' => 'Lumbago',
                'foods_to_eat' => ['Curcuma', 'Gingembre', 'Oméga-3', 'Magnésium'],
                'foods_to_avoid' => ['Aliments inflammatoires', 'Position assise prolongée'],
                'prophetic_advice_fr' => 'Hijama lombaire. Huile de nigelle en massage. Chaleur locale.',
                'prophetic_advice_en' => 'Lumbar Hijama. Black seed oil massage. Local heat.',
                'prophetic_advice_ar' => 'حجامة قطنية. تدليك بزيت الحبة السوداء. حرارة موضعية.'
            ],
            [
                'condition_name' => 'Hémorroïdes',
                'foods_to_eat' => ['Fibres', 'Eau', 'Pruneaux', 'Légumes verts'],
                'foods_to_avoid' => ['Épices fortes', 'Alcool', 'Aliments raffinés'],
                'prophetic_advice_fr' => 'Huile de nigelle en application locale. Bain de siège à l\'eau tiède. Figues sèches.',
                'prophetic_advice_en' => 'Black seed oil local application. Warm sitz bath. Dried figs.',
                'prophetic_advice_ar' => 'تطبيق موضعي لزيت الحبة السوداء. حمام مقعدي بالماء الدافئ. تين مجفف.'
            ],
            [
                'condition_name' => 'Calculs rénaux',
                'foods_to_eat' => ['Eau (2-3L/jour)', 'Citron', 'Persil', 'Pastèque'],
                'foods_to_avoid' => ['Sel', 'Oxalates (épinards, rhubarbe)', 'Protéines animales en excès'],
                'prophetic_advice_fr' => 'Eau abondante. Huile d\'olive + jus de citron. Graine de nigelle broyée.',
                'prophetic_advice_en' => 'Plenty of water. Olive oil + lemon juice. Ground black seed.',
                'prophetic_advice_ar' => 'ماء بكثرة. زيت الزيتون + عصير الليمون. حبة سوداء مطحونة.'
            ],
            [
                'condition_name' => 'Ostéoporose',
                'foods_to_eat' => ['Produits laitiers', 'Sardines', 'Légumes verts', 'Amandes'],
                'foods_to_avoid' => ['Alcool', 'Caféine en excès', 'Sel', 'Sodas'],
                'prophetic_advice_fr' => 'Dattes + lait: Source de calcium. Sésame (tahini). Exposition solaire modérée.',
                'prophetic_advice_en' => 'Dates + milk: Calcium source. Sesame (tahini). Moderate sun exposure.',
                'prophetic_advice_ar' => 'التمر مع الحليب: مصدر للكالسيوم. السمسم (الطحينة). التعرض المعتدل للشمس.'
            ],
            [
                'condition_name' => 'Sinusite',
                'foods_to_eat' => ['Ail', 'Oignon', 'Gingembre', 'Épices chaudes'],
                'foods_to_avoid' => ['Produits laitiers', 'Aliments froids', 'Sucres'],
                'prophetic_advice_fr' => 'Inhalation de vapeur avec thym. Huile de nigelle en gouttes nasales. Miel de thym.',
                'prophetic_advice_en' => 'Steam inhalation with thyme. Black seed oil nasal drops. Thyme honey.',
                'prophetic_advice_ar' => 'استنشاق البخار مع الزعتر. قطرات زيت الحبة السوداء للأنف. عسل الزعتر.'
            ],
            [
                'condition_name' => 'Acouphènes',
                'foods_to_eat' => ['Ginkgo biloba', 'Zinc', 'Magnésium', 'Oméga-3'],
                'foods_to_avoid' => ['Caféine', 'Sel', 'Alcool'],
                'prophetic_advice_fr' => 'Huile d\'olive tiède dans l\'oreille. Hijama derrière les oreilles. Repos auditif.',
                'prophetic_advice_en' => 'Warm olive oil in ear. Hijama behind ears. Auditory rest.',
                'prophetic_advice_ar' => 'زيت زيتون دافئ في الأذن. حجامة خلف الأذنين. راحة سمعية.'
            ],
            [
                'condition_name' => 'Ulcère gastrique',
                'foods_to_eat' => ['Chou', 'Miel', 'Banane', 'Riz', 'Pomme de terre'],
                'foods_to_avoid' => ['Épices', 'Café', 'Alcool', 'Aliments acides'],
                'prophetic_advice_fr' => 'Miel de Sidr à jeun. Jus de chou frais. Nigelle + miel.',
                'prophetic_advice_en' => 'Sidr honey on empty stomach. Fresh cabbage juice. Black seed + honey.',
                'prophetic_advice_ar' => 'عسل السدر على معدة فارغة. عصير الملفوف الطازج. حبة سوداء + عسل.'
            ],
            [
                'condition_name' => 'Triglycérides élevés',
                'foods_to_eat' => ['Poisson gras', 'Noix', 'Huile d\'olive', 'Fibres'],
                'foods_to_avoid' => ['Sucres', 'Alcool', 'Glucides raffinés'],
                'prophetic_advice_fr' => 'Nigelle quotidienne. Vinaigre de cidre dilué. Huile d\'olive vierge.',
                'prophetic_advice_en' => 'Daily black seed. Diluted apple cider vinegar. Virgin olive oil.',
                'prophetic_advice_ar' => 'الحبة السوداء يوميًا. خل التفاح المخفف. زيت الزيتون البكر.'
            ],
            [
                'condition_name' => 'Angoisse et stress',
                'foods_to_eat' => ['Camomille', 'Valériane', 'Magnésium', 'Chocolat noir'],
                'foods_to_avoid' => ['Caféine', 'Alcool', 'Sucres raffinés'],
                'prophetic_advice_fr' => 'Nigelle: Calmant naturel. Miel + eau de rose. Dhikr et relaxation.',
                'prophetic_advice_en' => 'Black seed: Natural calmer. Honey + rose water. Dhikr and relaxation.',
                'prophetic_advice_ar' => 'الحبة السوداء: مهدئ طبيعي. عسل + ماء الورد. الذكر والاسترخاء.'
            ],
            [
                'condition_name' => 'Douleurs dentaires',
                'foods_to_eat' => ['Clou de girofle', 'Ail', 'Miel'],
                'foods_to_avoid' => ['Sucres', 'Aliments très chauds ou froids'],
                'prophetic_advice_fr' => 'Clou de girofle: Anesthésiant naturel. Huile de nigelle en bain de bouche. Siwak.',
                'prophetic_advice_en' => 'Clove: Natural anesthetic. Black seed oil mouthwash. Siwak.',
                'prophetic_advice_ar' => 'القرنفل: مخدر طبيعي. مضمضة بزيت الحبة السوداء. السواك.'
            ],
            [
                'condition_name' => 'Problèmes de digestion',
                'foods_to_eat' => ['Fenouil', 'Menthe', 'Gingembre', 'Cumin'],
                'foods_to_avoid' => ['Aliments gras', 'Repas copieux', 'Manger vite'],
                'prophetic_advice_fr' => 'Commencer par quelques dattes. Manger en 3 temps (1/3 nourriture, 1/3 eau, 1/3 vide). Marcher après le repas.',
                'prophetic_advice_en' => 'Start with a few dates. Eat in 3 parts (1/3 food, 1/3 water, 1/3 empty). Walk after meal.',
                'prophetic_advice_ar' => 'البدء ببضع تمرات. تناول الطعام على ثلاثة أقسام. المشي بعد الوجبة.'
            ],
            [
                'condition_name' => 'Hématomes',
                'foods_to_eat' => ['Ananas', 'Papaye', 'Vitamine K', 'Arnica'],
                'foods_to_avoid' => ['Alcool', 'Aspirine'],
                'prophetic_advice_fr' => 'Miel en application locale. Huile de nigelle. Compresse froide.',
                'prophetic_advice_en' => 'Honey local application. Black seed oil. Cold compress.',
                'prophetic_advice_ar' => 'تطبيق العسل موضعيًا. زيت الحبة السوداء. كمادة باردة.'
            ],
            // Sport-specific conditions
            [
                'condition_name' => 'Baisse d\'intensité fin de match',
                'foods_to_eat' => ['Dattes', 'Banane', 'Miel', 'Boisson isotonique'],
                'foods_to_avoid' => ['Sucres simples en excès'],
                'prophetic_advice_fr' => 'Dattes: Énergie rapide et naturelle. Miel dilué. Eau de coco.',
                'prophetic_advice_en' => 'Dates: Quick natural energy. Diluted honey. Coconut water.',
                'prophetic_advice_ar' => 'التمر: طاقة سريعة وطبيعية. عسل مخفف. ماء جوز الهند.'
            ],
            [
                'condition_name' => 'Courbatures / récupération fitness',
                'foods_to_eat' => ['Cerise', 'Betterave', 'Protéines', 'Magnésium'],
                'foods_to_avoid' => ['Alcool', 'Sucres'],
                'prophetic_advice_fr' => 'Bain chaud avec sel d\'Epsom. Massage à l\'huile d\'olive. Miel + curcuma.',
                'prophetic_advice_en' => 'Hot bath with Epsom salt. Olive oil massage. Honey + turmeric.',
                'prophetic_advice_ar' => 'حمام ساخن مع ملح إبسوم. تدليك بزيت الزيتون. عسل + كركم.'
            ],
            [
                'condition_name' => 'Surentraînement / fatigue chronique',
                'foods_to_eat' => ['Miel', 'Gelée royale', 'Pollen', 'Adaptogènes'],
                'foods_to_avoid' => ['Stimulants', 'Entraînement intense'],
                'prophetic_advice_fr' => 'Repos obligatoire. Miel de Sidr + pollen. Nigelle pour l\'immunité.',
                'prophetic_advice_en' => 'Mandatory rest. Sidr honey + pollen. Black seed for immunity.',
                'prophetic_advice_ar' => 'راحة إجبارية. عسل السدر + حبوب اللقاح. الحبة السوداء للمناعة.'
            ],
            [
                'condition_name' => 'Prise de muscle (fitness)',
                'foods_to_eat' => ['Protéines', 'Glucides complexes', 'Créatine naturelle'],
                'foods_to_avoid' => ['Alcool', 'Déficit calorique'],
                'prophetic_advice_fr' => 'Dattes + lait après l\'entraînement. Miel pour l\'énergie. Viande maigre.',
                'prophetic_advice_en' => 'Dates + milk post-workout. Honey for energy. Lean meat.',
                'prophetic_advice_ar' => 'التمر مع الحليب بعد التمرين. العسل للطاقة. اللحم الخالي من الدهون.'
            ],
            [
                'condition_name' => 'Sèche / définition musculaire',
                'foods_to_eat' => ['Protéines maigres', 'Légumes verts', 'Eau'],
                'foods_to_avoid' => ['Sucres', 'Graisses saturées', 'Sodium en excès'],
                'prophetic_advice_fr' => 'Jeûne intermittent (Sunnah). Thé vert. Vinaigre de cidre dilué.',
                'prophetic_advice_en' => 'Intermittent fasting (Sunnah). Green tea. Diluted apple cider vinegar.',
                'prophetic_advice_ar' => 'الصيام المتقطع (سنة). الشاي الأخضر. خل التفاح المخفف.'
            ],
            [
                'condition_name' => 'Articulations sensibles',
                'foods_to_eat' => ['Collagène', 'Vitamine C', 'Oméga-3', 'Curcuma'],
                'foods_to_avoid' => ['Aliments inflammatoires', 'Alcool'],
                'prophetic_advice_fr' => 'Bouillon d\'os. Huile de nigelle. Hijama préventive.',
                'prophetic_advice_en' => 'Bone broth. Black seed oil. Preventive Hijama.',
                'prophetic_advice_ar' => 'مرق العظام. زيت الحبة السوداء. حجامة وقائية.'
            ],
            [
                'condition_name' => 'Genoux / chevilles sollicités',
                'foods_to_eat' => ['Collagène', 'Silicium', 'Vitamine C', 'Glucosamine'],
                'foods_to_avoid' => ['Surpoids', 'Impacts répétés'],
                'prophetic_advice_fr' => 'Massage à l\'huile d\'olive. Prêle en infusion. Repos actif.',
                'prophetic_advice_en' => 'Olive oil massage. Horsetail infusion. Active rest.',
                'prophetic_advice_ar' => 'تدليك بزيت الزيتون. منقوع ذنب الخيل. راحة نشطة.'
            ],
            [
                'condition_name' => 'Blessures ligamentaires / ménisque',
                'foods_to_eat' => ['Collagène', 'Vitamine C', 'Protéines'],
                'foods_to_avoid' => ['Aliments inflammatoires'],
                'prophetic_advice_fr' => 'Repos et immobilisation initiale. Huile de nigelle. Miel de Sidr.',
                'prophetic_advice_en' => 'Rest and initial immobilization. Black seed oil. Sidr honey.',
                'prophetic_advice_ar' => 'الراحة والتثبيت الأولي. زيت الحبة السوداء. عسل السدر.'
            ],
            [
                'condition_name' => 'Blessures osseuses / fractures',
                'foods_to_eat' => ['Calcium', 'Vitamine D', 'Protéines', 'Vitamine K'],
                'foods_to_avoid' => ['Alcool', 'Caféine', 'Sel en excès'],
                'prophetic_advice_fr' => 'Dattes + lait: Os solides. Huile de poisson. Exposition solaire.',
                'prophetic_advice_en' => 'Dates + milk: Strong bones. Fish oil. Sun exposure.',
                'prophetic_advice_ar' => 'التمر مع الحليب: عظام قوية. زيت السمك. التعرض للشمس.'
            ],
            // ============================================================
            // FOOTBALL-SPECIFIC missing conditions from NUTRITION SPORT
            // ============================================================
            [
                'condition_name' => 'Déshydratation aiguë',
                'foods_to_eat' => ['Pastèque', 'Concombre'],
                'foods_to_avoid' => ['Alcool', 'Café en excès', 'Sel en excès'],
                'prophetic_advice_fr' => 'Minéraux: Sodium, Potassium. Miel de fleurs. Eau bicarbonatée.',
                'prophetic_advice_en' => 'Minerals: Sodium, Potassium. Flower honey. Bicarbonate water.',
                'prophetic_advice_ar' => 'المعادن: الصوديوم، البوتاسيوم. عسل الزهور. الماء الغني بالبيكاربونات.'
            ],
            [
                'condition_name' => 'Déshydratation chronique / crampes',
                'foods_to_eat' => ['Banane', 'Épinards'],
                'foods_to_avoid' => ['Alcool', 'Café en excès', 'Boissons sucrées'],
                'prophetic_advice_fr' => 'Vitamine B6. Minéraux: Magnésium, Sodium. Tisane: Ortie. Sel non raffiné.',
                'prophetic_advice_en' => 'Vitamin B6. Minerals: Magnesium, Sodium. Tisane: Nettle. Unrefined salt.',
                'prophetic_advice_ar' => 'فيتامين B6. المعادن: المغنيسيوم، الصوديوم. شاي القراص. ملح غير مكرر.'
            ],
            [
                'condition_name' => 'Troubles digestifs (voyages)',
                'foods_to_eat' => ['Banane', 'Carotte', 'Lentilles corail', 'Riz blanc', 'Poulet', 'Cabillaud'],
                'foods_to_avoid' => ['Aliments épicés', 'Aliments gras', 'Produits laitiers non pasteurisés'],
                'prophetic_advice_fr' => 'Vitamine B1. Minéral: Zinc. Tisane: Fenouil. Gingembre.',
                'prophetic_advice_en' => 'Vitamin B1. Mineral: Zinc. Tisane: Fennel. Ginger.',
                'prophetic_advice_ar' => 'فيتامين B1. معدن: الزنك. شاي الشمر. الزنجبيل.'
            ],
            [
                'condition_name' => 'Inflammation intestinale / diarrhée',
                'foods_to_eat' => ['Pomme cuite', 'Courgette', 'Riz blanc', 'Dinde'],
                'foods_to_avoid' => ['Fibres dures', 'Produits laitiers', 'Aliments gras', 'Café'],
                'prophetic_advice_fr' => 'Vitamines A, C. Minéral: Zinc. Tisane: Camomille. Bouillon.',
                'prophetic_advice_en' => 'Vitamins A, C. Mineral: Zinc. Tisane: Chamomile. Broth.',
                'prophetic_advice_ar' => 'فيتامينات A، C. معدن: الزنك. شاي البابونج. المرق.'
            ],
            [
                'condition_name' => 'Surcharge articulaire chronique',
                'foods_to_eat' => ['Fruits rouges', 'Curcuma', 'Maquereau'],
                'foods_to_avoid' => ['Aliments inflammatoires', 'Sucres raffinés', 'Alcool'],
                'prophetic_advice_fr' => 'Vitamine D. Minéral: Zinc. Tisane: Harpagophytum. Huile d\'olive. Curcuma.',
                'prophetic_advice_en' => 'Vitamin D. Mineral: Zinc. Tisane: Harpagophytum. Olive oil. Turmeric.',
                'prophetic_advice_ar' => 'فيتامين D. معدن: الزنك. شاي الهارباغوفيتوم. زيت الزيتون. الكركم.'
            ],
            [
                'condition_name' => 'Récupération globale / santé',
                'foods_to_eat' => ['Fruits variés', 'Légumes variés', 'Légumineuses', 'Céréales complètes', 'Protéines variées', 'Poissons gras'],
                'foods_to_avoid' => ['Aliments transformés', 'Sucres raffinés', 'Graisses saturées'],
                'prophetic_advice_fr' => 'Multivitamines. Oligo-éléments. Tisanes douces. Huiles variées. Miel de fleurs. Épices.',
                'prophetic_advice_en' => 'Multivitamins. Trace elements. Gentle tisanes. Various oils. Flower honey. Spices.',
                'prophetic_advice_ar' => 'فيتامينات متعددة. عناصر دقيقة. شاي أعشاب خفيف. زيوت متنوعة. عسل الزهور. التوابل.'
            ],
            // ============================================================
            // FITNESS-SPECIFIC missing conditions from NUTRITION SPORT
            // ============================================================
            [
                'condition_name' => 'Manque d\'énergie / fatigue',
                'foods_to_eat' => ['Banane', 'Dattes', 'Épinards', 'Betterave', 'Lentilles', 'Avoine', 'Riz', 'Poulet', 'Œufs', 'Sardine'],
                'foods_to_avoid' => ['Sucres rapides', 'Alcool', 'Café en excès'],
                'prophetic_advice_fr' => 'Vitamines B, C. Minéraux: Fer, Magnésium. Tisane: Ginseng. Huile d\'olive. Miel de fleurs. Sel non raffiné.',
                'prophetic_advice_en' => 'Vitamins B, C. Minerals: Iron, Magnesium. Tisane: Ginseng. Olive oil. Flower honey. Unrefined salt.',
                'prophetic_advice_ar' => 'فيتامينات B، C. المعادن: الحديد والمغنيسيوم. شاي الجينسنغ. زيت الزيتون. عسل الزهور. ملح غير مكرر.'
            ],
            [
                'condition_name' => 'Perte de poids / minceur',
                'foods_to_eat' => ['Pomme', 'Fruits rouges', 'Courgette', 'Brocoli', 'Lentilles', 'Quinoa', 'Viande maigre', 'Poisson blanc'],
                'foods_to_avoid' => ['Sucres raffinés', 'Graisses saturées', 'Alcool', 'Sodas'],
                'prophetic_advice_fr' => 'Tisane: Thé vert. Huile d\'olive. Épices (cannelle, curcuma).',
                'prophetic_advice_en' => 'Tisane: Green tea. Olive oil. Spices (cinnamon, turmeric).',
                'prophetic_advice_ar' => 'شاي: الشاي الأخضر. زيت الزيتون. التوابل (القرفة، الكركم).'
            ],
            [
                'condition_name' => 'Perte de tonicité / relâchement',
                'foods_to_eat' => ['Agrumes', 'Chou', 'Brocoli', 'Quinoa', 'Œufs', 'Sardine'],
                'foods_to_avoid' => ['Sucres raffinés', 'Aliments transformés', 'Alcool'],
                'prophetic_advice_fr' => 'Vitamines C, E. Minéral: Zinc. Tisane: Ortie. Huile de noix. Collagène.',
                'prophetic_advice_en' => 'Vitamins C, E. Mineral: Zinc. Tisane: Nettle. Walnut oil. Collagen.',
                'prophetic_advice_ar' => 'فيتامينات C، E. معدن: الزنك. شاي القراص. زيت الجوز. الكولاجين.'
            ],
            [
                'condition_name' => 'Crampes / spasmes',
                'foods_to_eat' => ['Banane', 'Épinards', 'Haricots rouges', 'Riz complet', 'Saumon'],
                'foods_to_avoid' => ['Alcool', 'Café en excès', 'Sel en excès'],
                'prophetic_advice_fr' => 'Vitamine B6. Minéraux: Magnésium, Potassium. Tisane: Ortie. Huile d\'amande. Eau riche en magnésium.',
                'prophetic_advice_en' => 'Vitamin B6. Minerals: Magnesium, Potassium. Tisane: Nettle. Almond oil. Magnesium-rich water.',
                'prophetic_advice_ar' => 'فيتامين B6. المعادن: المغنيسيوم، البوتاسيوم. شاي القراص. زيت اللوز. ماء غني بالمغنيسيوم.'
            ],
            [
                'condition_name' => 'Stress / fatigue mentale',
                'foods_to_eat' => ['Banane', 'Avoine', 'Chocolat noir'],
                'foods_to_avoid' => ['Caféine en excès', 'Alcool', 'Sucres raffinés'],
                'prophetic_advice_fr' => 'Vitamine B6. Minéral: Magnésium. Tisane: Tilleul. Huile de noix. Miel de tilleul. Chocolat noir.',
                'prophetic_advice_en' => 'Vitamin B6. Mineral: Magnesium. Tisane: Linden. Walnut oil. Linden honey. Dark chocolate.',
                'prophetic_advice_ar' => 'فيتامين B6. معدن: المغنيسيوم. شاي الزيزفون. زيت الجوز. عسل الزيزفون. الشوكولاتة الداكنة.'
            ],
            [
                'condition_name' => 'Troubles du sommeil',
                'foods_to_eat' => ['Cerise', 'Flocons d\'avoine', 'Lait chaud'],
                'foods_to_avoid' => ['Caféine après 14h', 'Alcool', 'Écrans avant le coucher'],
                'prophetic_advice_fr' => 'Vitamine B6. Minéral: Magnésium. Tisane: Camomille. Huile de noix. Miel de tilleul. Lait chaud.',
                'prophetic_advice_en' => 'Vitamin B6. Mineral: Magnesium. Tisane: Chamomile. Walnut oil. Linden honey. Warm milk.',
                'prophetic_advice_ar' => 'فيتامين B6. معدن: المغنيسيوم. شاي البابونج. زيت الجوز. عسل الزيزفون. حليب دافئ.'
            ],
            [
                'condition_name' => 'Ballonnements / digestion difficile',
                'foods_to_eat' => ['Banane', 'Courgette', 'Lentilles corail', 'Riz blanc', 'Poulet', 'Cabillaud'],
                'foods_to_avoid' => ['Aliments gras', 'Boissons gazeuses', 'Légumes crus en excès'],
                'prophetic_advice_fr' => 'Vitamine B1. Minéral: Zinc. Tisane: Fenouil. Gingembre.',
                'prophetic_advice_en' => 'Vitamin B1. Mineral: Zinc. Tisane: Fennel. Ginger.',
                'prophetic_advice_ar' => 'فيتامين B1. معدن: الزنك. شاي الشمر. الزنجبيل.'
            ],
            [
                'condition_name' => 'Inflammation chronique',
                'foods_to_eat' => ['Fruits rouges', 'Curcuma', 'Maquereau'],
                'foods_to_avoid' => ['Aliments transformés', 'Sucres raffinés', 'Graisses saturées'],
                'prophetic_advice_fr' => 'Vitamines D, C. Minéral: Zinc. Tisane: Harpagophytum. Huile d\'olive. Curcuma + poivre noir.',
                'prophetic_advice_en' => 'Vitamins D, C. Mineral: Zinc. Tisane: Harpagophytum. Olive oil. Turmeric + black pepper.',
                'prophetic_advice_ar' => 'فيتامينات D، C. معدن: الزنك. شاي الهارباغوفيتوم. زيت الزيتون. كركم + فلفل أسود.'
            ],
            [
                'condition_name' => 'Déshydratation / rétention',
                'foods_to_eat' => ['Pastèque', 'Concombre'],
                'foods_to_avoid' => ['Sel en excès', 'Alcool', 'Boissons sucrées'],
                'prophetic_advice_fr' => 'Minéraux: Sodium, Potassium. Eau minérale.',
                'prophetic_advice_en' => 'Minerals: Sodium, Potassium. Mineral water.',
                'prophetic_advice_ar' => 'المعادن: الصوديوم، البوتاسيوم. ماء معدني.'
            ],
            [
                'condition_name' => 'Reprise du sport / remise en forme',
                'foods_to_eat' => ['Fruits variés', 'Légumes variés', 'Légumineuses', 'Céréales complètes', 'Protéines variées', 'Poissons'],
                'foods_to_avoid' => ['Aliments transformés', 'Sucres raffinés', 'Graisses saturées'],
                'prophetic_advice_fr' => 'Multivitamines. Oligo-éléments. Tisanes douces. Huiles variées. Miel de fleurs. Épices.',
                'prophetic_advice_en' => 'Multivitamins. Trace elements. Gentle tisanes. Various oils. Flower honey. Spices.',
                'prophetic_advice_ar' => 'فيتامينات متعددة. عناصر دقيقة. شاي أعشاب خفيف. زيوت متنوعة. عسل الزهور. التوابل.'
            ],
            [
                'condition_name' => 'Bien-être global / santé',
                'foods_to_eat' => ['Fruits de saison', 'Légumes variés', 'Légumineuses', 'Céréales complètes', 'Œufs', 'Viandes', 'Poissons gras'],
                'foods_to_avoid' => ['Aliments transformés', 'Sucres raffinés', 'Excès de sel'],
                'prophetic_advice_fr' => 'Vitamines A, B, C, D. Minéraux: Magnésium, Zinc. Infusions. Huiles variées. Épices.',
                'prophetic_advice_en' => 'Vitamins A, B, C, D. Minerals: Magnesium, Zinc. Infusions. Various oils. Spices.',
                'prophetic_advice_ar' => 'فيتامينات A، B، C، D. المعادن: المغنيسيوم، الزنك. منقوعات. زيوت متنوعة. التوابل.'
            ],
            // ============================================================
            // PADEL-SPECIFIC missing conditions from NUTRITION SPORT
            // ============================================================
            [
                'condition_name' => 'Manque d\'énergie en match',
                'foods_to_eat' => ['Banane', 'Dattes', 'Betterave', 'Lentilles', 'Riz', 'Avoine', 'Poulet', 'Sardine'],
                'foods_to_avoid' => ['Sucres rapides', 'Repas lourds avant le match'],
                'prophetic_advice_fr' => 'Vitamines B1, B6, C. Minéraux: Fer, Magnésium. Tisane: Maté léger. Huile d\'olive. Miel de fleurs. Sel non raffiné.',
                'prophetic_advice_en' => 'Vitamins B1, B6, C. Minerals: Iron, Magnesium. Tisane: Light mate. Olive oil. Flower honey. Unrefined salt.',
                'prophetic_advice_ar' => 'فيتامينات B1، B6، C. المعادن: الحديد والمغنيسيوم. شاي المتة الخفيف. زيت الزيتون. عسل الزهور. ملح غير مكرر.'
            ],
            [
                'condition_name' => 'Crampes musculaires',
                'foods_to_eat' => ['Banane', 'Abricots secs', 'Brocoli', 'Haricots rouges', 'Riz complet', 'Saumon'],
                'foods_to_avoid' => ['Alcool', 'Café en excès', 'Sel en excès'],
                'prophetic_advice_fr' => 'Vitamine B6. Minéraux: Magnésium, Potassium, Sodium. Tisane: Ortie. Huile d\'amande. Eau riche en magnésium.',
                'prophetic_advice_en' => 'Vitamin B6. Minerals: Magnesium, Potassium, Sodium. Tisane: Nettle. Almond oil. Magnesium-rich water.',
                'prophetic_advice_ar' => 'فيتامين B6. المعادن: المغنيسيوم، البوتاسيوم، الصوديوم. شاي القراص. زيت اللوز. ماء غني بالمغنيسيوم.'
            ],
            [
                'condition_name' => 'Déshydratation (chaleur, indoor)',
                'foods_to_eat' => ['Pastèque', 'Concombre'],
                'foods_to_avoid' => ['Alcool', 'Café en excès', 'Boissons sucrées'],
                'prophetic_advice_fr' => 'Minéraux: Sodium, Potassium. Miel de fleurs. Boisson électrolyte.',
                'prophetic_advice_en' => 'Minerals: Sodium, Potassium. Flower honey. Electrolyte drink.',
                'prophetic_advice_ar' => 'المعادن: الصوديوم، البوتاسيوم. عسل الزهور. مشروب إلكتروليت.'
            ],
            [
                'condition_name' => 'Déshydratation chronique / crampes récurrentes',
                'foods_to_eat' => ['Banane', 'Épinards'],
                'foods_to_avoid' => ['Alcool', 'Café en excès', 'Boissons sucrées'],
                'prophetic_advice_fr' => 'Vitamine B6. Minéraux: Magnésium, Sodium. Tisane: Ortie. Sel non raffiné.',
                'prophetic_advice_en' => 'Vitamin B6. Minerals: Magnesium, Sodium. Tisane: Nettle. Unrefined salt.',
                'prophetic_advice_ar' => 'فيتامين B6. المعادن: المغنيسيوم، الصوديوم. شاي القراص. ملح غير مكرر.'
            ],
            [
                'condition_name' => 'Fatigue nerveuse / stress match',
                'foods_to_eat' => ['Banane', 'Avoine', 'Chocolat noir'],
                'foods_to_avoid' => ['Caféine en excès', 'Alcool', 'Sucres raffinés'],
                'prophetic_advice_fr' => 'Vitamine B6. Minéral: Magnésium. Tisane: Tilleul. Huile de noix. Miel de tilleul. Chocolat noir.',
                'prophetic_advice_en' => 'Vitamin B6. Mineral: Magnesium. Tisane: Linden. Walnut oil. Linden honey. Dark chocolate.',
                'prophetic_advice_ar' => 'فيتامين B6. معدن: المغنيسيوم. شاي الزيزفون. زيت الجوز. عسل الزيزفون. الشوكولاتة الداكنة.'
            ],
            [
                'condition_name' => 'Troubles du sommeil (tournois)',
                'foods_to_eat' => ['Cerise', 'Flocons d\'avoine', 'Lait chaud'],
                'foods_to_avoid' => ['Caféine après 14h', 'Écrans avant le coucher', 'Repas lourds le soir'],
                'prophetic_advice_fr' => 'Vitamine B6. Minéral: Magnésium. Tisane: Camomille. Huile de noix. Miel de tilleul. Lait chaud.',
                'prophetic_advice_en' => 'Vitamin B6. Mineral: Magnesium. Tisane: Chamomile. Walnut oil. Linden honey. Warm milk.',
                'prophetic_advice_ar' => 'فيتامين B6. معدن: المغنيسيوم. شاي البابونج. زيت الجوز. عسل الزيزفون. حليب دافئ.'
            ],
            [
                'condition_name' => 'Douleurs épaules / coudes (tendinites)',
                'foods_to_eat' => ['Ananas', 'Épinards', 'Sardine'],
                'foods_to_avoid' => ['Sucres raffinés', 'Alcool', 'Graisses saturées'],
                'prophetic_advice_fr' => 'Vitamine C. Minéraux: Silicium, Zinc. Tisane: Prêle. Huile de noix. Collagène.',
                'prophetic_advice_en' => 'Vitamin C. Minerals: Silicon, Zinc. Tisane: Horsetail. Walnut oil. Collagen.',
                'prophetic_advice_ar' => 'فيتامين C. المعادن: السيليكون، الزنك. شاي ذيل الحصان. زيت الجوز. الكولاجين.'
            ],
            [
                'condition_name' => 'Inflammation articulaire chronique',
                'foods_to_eat' => ['Fruits rouges', 'Curcuma', 'Maquereau'],
                'foods_to_avoid' => ['Aliments transformés', 'Sucres raffinés', 'Graisses saturées'],
                'prophetic_advice_fr' => 'Vitamines D, C. Minéral: Zinc. Tisane: Harpagophytum. Huile d\'olive. Curcuma + poivre noir.',
                'prophetic_advice_en' => 'Vitamins D, C. Mineral: Zinc. Tisane: Harpagophytum. Olive oil. Turmeric + black pepper.',
                'prophetic_advice_ar' => 'فيتامينات D، C. معدن: الزنك. شاي الهارباغوفيتوم. زيت الزيتون. كركم + فلفل أسود.'
            ],
            [
                'condition_name' => 'Prise de poids hors saison',
                'foods_to_eat' => ['Pomme', 'Légumes verts', 'Lentilles', 'Quinoa', 'Viande maigre', 'Poisson blanc'],
                'foods_to_avoid' => ['Sucres raffinés', 'Graisses saturées', 'Alcool', 'Sodas'],
                'prophetic_advice_fr' => 'Tisane: Thé vert. Huile d\'olive. Épices (cannelle, curcuma).',
                'prophetic_advice_en' => 'Tisane: Green tea. Olive oil. Spices (cinnamon, turmeric).',
                'prophetic_advice_ar' => 'شاي: الشاي الأخضر. زيت الزيتون. التوابل (القرفة، الكركم).'
            ],
            [
                'condition_name' => 'Digestion difficile (matchs rapprochés)',
                'foods_to_eat' => ['Banane', 'Courgette', 'Lentilles corail', 'Riz blanc', 'Poulet', 'Cabillaud'],
                'foods_to_avoid' => ['Aliments gras', 'Repas copieux', 'Boissons gazeuses'],
                'prophetic_advice_fr' => 'Vitamine B1. Minéral: Zinc. Tisane: Fenouil. Gingembre.',
                'prophetic_advice_en' => 'Vitamin B1. Mineral: Zinc. Tisane: Fennel. Ginger.',
                'prophetic_advice_ar' => 'فيتامين B1. معدن: الزنك. شاي الشمر. الزنجبيل.'
            ],
        ];

        foreach ($advices as $advice) {
            NutritionAdvice::create($advice);
        }

        $this->command->info('  Created ' . count($advices) . ' nutrition advice entries');
    }

    private function seedBonusWorkoutRules(): void
    {
        $this->command->info('Seeding Bonus Workout Rules...');

        $rules = [
            // DÉBUTANT
            ['level' => 'DÉBUTANT', 'type' => 'ABDOS', 'sets' => '3', 'reps' => '12 répétitions', 'recovery' => '45 sec'],
            ['level' => 'DÉBUTANT', 'type' => 'POMPES', 'sets' => '3', 'reps' => '10 répétitions', 'recovery' => '45 sec'],
            ['level' => 'DÉBUTANT', 'type' => 'GAINAGE', 'sets' => '3', 'reps' => '20 sec', 'recovery' => '45 sec'],

            // INTERMÉDIAIRE
            ['level' => 'INTERMÉDIAIRE', 'type' => 'ABDOS', 'sets' => '4', 'reps' => '30 répétitions', 'recovery' => '30 sec'],
            ['level' => 'INTERMÉDIAIRE', 'type' => 'POMPES', 'sets' => '4', 'reps' => '20 répétitions', 'recovery' => '30 sec'],
            ['level' => 'INTERMÉDIAIRE', 'type' => 'GAINAGE', 'sets' => '4', 'reps' => '45 sec', 'recovery' => '30 sec'],

            // AVANCÉ
            ['level' => 'AVANCÉ', 'type' => 'ABDOS', 'sets' => '5', 'reps' => '40 répétitions', 'recovery' => '20 sec'],
            ['level' => 'AVANCÉ', 'type' => 'POMPES', 'sets' => '5', 'reps' => '30 répétitions', 'recovery' => '20 sec'],
            ['level' => 'AVANCÉ', 'type' => 'GAINAGE', 'sets' => '5', 'reps' => '1 min', 'recovery' => '20 sec'],
        ];

        foreach ($rules as $rule) {
            BonusWorkoutRule::create($rule);
        }

        $this->command->info('  Created ' . count($rules) . ' bonus workout rules');
    }
}
