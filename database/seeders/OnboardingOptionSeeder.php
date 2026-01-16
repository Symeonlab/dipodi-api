<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OnboardingOption;

class OnboardingOptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            // =============================================
            // DISCIPLINES
            // =============================================
            ['type' => 'discipline', 'key' => 'FOOTBALL', 'name_en' => 'Football', 'name_fr' => 'Football', 'name_ar' => 'كرة القدم'],
            ['type' => 'discipline', 'key' => 'FUTSAL', 'name_en' => 'Futsal', 'name_fr' => 'Futsal', 'name_ar' => 'كرة الصالات'],
            ['type' => 'discipline', 'key' => 'FITNESS', 'name_en' => 'Fitness', 'name_fr' => 'Fitness', 'name_ar' => 'اللياقة البدنية'],
            ['type' => 'discipline', 'key' => 'PADEL', 'name_en' => 'Padel', 'name_fr' => 'Padel', 'name_ar' => 'بادل'],

            // =============================================
            // FITNESS LEVELS
            // =============================================
            ['type' => 'level', 'key' => 'DÉBUTANT', 'name_en' => 'Beginner', 'name_fr' => 'Débutant', 'name_ar' => 'مبتدئ'],
            ['type' => 'level', 'key' => 'INTERMÉDIAIRE', 'name_en' => 'Intermediate', 'name_fr' => 'Intermédiaire', 'name_ar' => 'متوسط'],
            ['type' => 'level', 'key' => 'AVANCÉ', 'name_en' => 'Advanced', 'name_fr' => 'Avancé', 'name_ar' => 'متقدم'],

            // =============================================
            // GOALS
            // =============================================
            ['type' => 'goal', 'key' => 'PERDRE_DU_POIDS', 'name_en' => 'Lose Weight', 'name_fr' => 'Perdre du Poids', 'name_ar' => 'خسارة الوزن'],
            ['type' => 'goal', 'key' => 'MASSE_MUSCULAIRE', 'name_en' => 'Gain Muscle', 'name_fr' => 'Masse Musculaire', 'name_ar' => 'اكتساب العضلات'],
            ['type' => 'goal', 'key' => 'MAINTIEN_DE_FORME', 'name_en' => 'Maintain Shape', 'name_fr' => 'Maintien de Forme', 'name_ar' => 'الحفاظ على الشكل'],

            // =============================================
            // TRAINING LOCATIONS
            // =============================================
            ['type' => 'location', 'key' => 'MUSCULATION_EN_SALLE', 'name_en' => 'Gym (Weightlifting)', 'name_fr' => 'Musculation en Salle', 'name_ar' => 'صالة الألعاب (أثقال)'],
            ['type' => 'location', 'key' => 'CARDIO_EN_SALLE', 'name_en' => 'Gym (Cardio)', 'name_fr' => 'Cardio en Salle', 'name_ar' => 'صالة الألعاب (كارديو)'],
            ['type' => 'location', 'key' => 'MUSCULATION_ET_CARDIO_EN_SALLE', 'name_en' => 'Gym (Weights + Cardio)', 'name_fr' => 'Musculation et Cardio', 'name_ar' => 'صالة الألعاب (أثقال وكارديو)'],
            ['type' => 'location', 'key' => 'DEHORS', 'name_en' => 'Outdoors', 'name_fr' => 'Dehors', 'name_ar' => 'في الخارج'],
            ['type' => 'location', 'key' => 'MAISON', 'name_en' => 'At Home', 'name_fr' => 'Maison', 'name_ar' => 'في المنزل'],

            // =============================================
            // FOOTBALL POSITIONS
            // =============================================
            // Gardiens
            ['type' => 'football_position', 'key' => 'GARDIEN_PANTHERE', 'name_en' => 'Panther Goalkeeper', 'name_fr' => 'Panthère - Explosif, parades spectaculaires', 'name_ar' => 'حارس النمر'],
            ['type' => 'football_position', 'key' => 'GARDIEN_PIEUVRE', 'name_en' => 'Octopus Goalkeeper', 'name_fr' => 'Pieuvre - Envergure immense, bouche tous les angles', 'name_ar' => 'حارس الأخطبوط'],
            ['type' => 'football_position', 'key' => 'GARDIEN_ARAIGNEE', 'name_en' => 'Spider Goalkeeper', 'name_fr' => 'Araignée - Lecture du jeu, intercepte les centres', 'name_ar' => 'حارس العنكبوت'],
            ['type' => 'football_position', 'key' => 'GARDIEN_CHAT', 'name_en' => 'Cat Goalkeeper', 'name_fr' => 'Chat - Agilité extrême, rapide au sol', 'name_ar' => 'حارس القط'],

            // Défenseurs
            ['type' => 'football_position', 'key' => 'DEFENSEUR_CASSEUR', 'name_en' => 'Destroyer Defender', 'name_fr' => 'Casseur - Impact physique total, gagne tous ses duels', 'name_ar' => 'مدافع المدمر'],
            ['type' => 'football_position', 'key' => 'DEFENSEUR_CONTROLEUR', 'name_en' => 'Controller Defender', 'name_fr' => 'Contrôleur - Calme, dirige la ligne, placement impeccable', 'name_ar' => 'مدافع المتحكم'],
            ['type' => 'football_position', 'key' => 'DEFENSEUR_POLYVALENT', 'name_en' => 'Versatile Defender', 'name_fr' => 'Polyvalent - Capable de jouer axe ou côté', 'name_ar' => 'مدافع متعدد المهام'],
            ['type' => 'football_position', 'key' => 'DEFENSEUR_RELANCEUR', 'name_en' => 'Playmaker Defender', 'name_fr' => 'Relanceur - Technique propre, première passe', 'name_ar' => 'مدافع صانع اللعب'],

            // Milieux
            ['type' => 'football_position', 'key' => 'MILIEU_ARCHITECTE', 'name_en' => 'Architect Midfielder', 'name_fr' => 'Architecte - Meneur de jeu, vision 360°', 'name_ar' => 'لاعب وسط المهندس'],
            ['type' => 'football_position', 'key' => 'MILIEU_GAZELLE', 'name_en' => 'Gazelle Midfielder', 'name_fr' => 'Gazelle - Box-to-box, casse les lignes par sa course', 'name_ar' => 'لاعب وسط الغزالة'],
            ['type' => 'football_position', 'key' => 'MILIEU_PITBULL', 'name_en' => 'Pitbull Midfielder', 'name_fr' => 'Pitbull - Harcèle le porteur, gros volume de course', 'name_ar' => 'لاعب وسط البيتبول'],
            ['type' => 'football_position', 'key' => 'MILIEU_THE_ROCK', 'name_en' => 'The Rock Midfielder', 'name_fr' => 'The Rock - Sentinelle devant la défense, inamovible', 'name_ar' => 'لاعب وسط الصخرة'],

            // Attaquants
            ['type' => 'football_position', 'key' => 'ATTAQUANT_MAGICIEN', 'name_en' => 'Magician Forward', 'name_fr' => 'Magicien - Dribbleur, imprévisible', 'name_ar' => 'مهاجم الساحر'],
            ['type' => 'football_position', 'key' => 'ATTAQUANT_RENARD', 'name_en' => 'Fox Forward', 'name_fr' => 'Renard - Toujours au bon endroit, sens du but', 'name_ar' => 'مهاجم الثعلب'],
            ['type' => 'football_position', 'key' => 'ATTAQUANT_SNIPER', 'name_en' => 'Sniper Forward', 'name_fr' => 'Sniper - Frappe clinique, redoutable', 'name_ar' => 'مهاجم القناص'],
            ['type' => 'football_position', 'key' => 'ATTAQUANT_TANK', 'name_en' => 'Tank Forward', 'name_fr' => 'Tank - Pivot physique, protège sa balle', 'name_ar' => 'مهاجم الدبابة'],

            // =============================================
            // FITNESS PROFILES - WOMEN
            // =============================================
            ['type' => 'fitness_profile_female', 'key' => 'LA_SILHOUETTE', 'name_en' => 'The Silhouette', 'name_fr' => 'La Silhouette - Élégante et harmonieuse, ligne affinée', 'name_ar' => 'الصورة الظلية'],
            ['type' => 'fitness_profile_female', 'key' => 'LA_TONIQUE', 'name_en' => 'The Tonic', 'name_fr' => 'La Tonique - Dynamique et énergique, style sportif', 'name_ar' => 'المنشطة'],
            ['type' => 'fitness_profile_female', 'key' => 'LA_FINE', 'name_en' => 'The Slim', 'name_fr' => 'La Fine - Légère et délicate, esthétique élancée', 'name_ar' => 'الرشيقة'],
            ['type' => 'fitness_profile_female', 'key' => 'L_ATHLETE_PUISSANTE', 'name_en' => 'The Powerful Athlete', 'name_fr' => "L'Athlète Puissante - Forte et déterminée", 'name_ar' => 'الرياضية القوية'],
            ['type' => 'fitness_profile_female', 'key' => 'BIEN_ETRE', 'name_en' => 'Wellness', 'name_fr' => 'Bien-être - Apaisée et équilibrée, confort corporel', 'name_ar' => 'الرفاهية'],

            // =============================================
            // FITNESS PROFILES - MEN
            // =============================================
            ['type' => 'fitness_profile_male', 'key' => 'L_ATHLETIQUE', 'name_en' => 'The Athletic', 'name_fr' => "L'Athlétique - Silhouette équilibrée et sportive", 'name_ar' => 'الرياضي'],
            ['type' => 'fitness_profile_male', 'key' => 'LE_MASSIF', 'name_en' => 'The Massive', 'name_fr' => 'Le Massif - Gabarit imposant, densité musculaire', 'name_ar' => 'الضخم'],
            ['type' => 'fitness_profile_male', 'key' => 'LE_SEC', 'name_en' => 'The Lean', 'name_fr' => 'Le Sec - Corps affûté, définition musculaire', 'name_ar' => 'النحيف'],
            ['type' => 'fitness_profile_male', 'key' => 'LE_FONCTIONNEL', 'name_en' => 'The Functional', 'name_fr' => 'Le Fonctionnel - Style utilitaire, coordination', 'name_ar' => 'الوظيفي'],
            ['type' => 'fitness_profile_male', 'key' => 'LE_FORCE_BRUTE', 'name_en' => 'The Raw Power', 'name_fr' => 'Le Force Brute - Physique robuste, force maximale', 'name_ar' => 'القوة الخام'],

            // =============================================
            // PADEL PROFILES
            // =============================================
            ['type' => 'padel_position', 'key' => 'DROITE', 'name_en' => 'Right Side', 'name_fr' => 'Droite', 'name_ar' => 'اليمين'],
            ['type' => 'padel_position', 'key' => 'GAUCHE', 'name_en' => 'Left Side', 'name_fr' => 'Gauche', 'name_ar' => 'اليسار'],

            // =============================================
            // PADEL PLAYER TYPES
            // =============================================
            ['type' => 'padel_profile', 'key' => 'LE_DEFENSEUR', 'name_en' => 'The Defender', 'name_fr' => 'Le Défenseur - Sorties de vitre rapides, solidité', 'name_ar' => 'المدافع'],
            ['type' => 'padel_profile', 'key' => 'LE_FRAGILE', 'name_en' => 'The Fragile', 'name_fr' => 'Le Fragile - Élimination de l\'acide, santé tendons', 'name_ar' => 'الهش'],
            ['type' => 'padel_profile', 'key' => 'LE_VETERAN', 'name_en' => 'The Veteran', 'name_fr' => 'Le Vétéran - Qualité de frappe fatigué, expérience', 'name_ar' => 'المخضرم'],
            ['type' => 'padel_profile', 'key' => 'LE_MATINAL', 'name_en' => 'The Early Bird', 'name_fr' => 'Le Matinal - Réactivité constante, réveil musculaire', 'name_ar' => 'الصباحي'],
            ['type' => 'padel_profile', 'key' => 'LE_JOUEUR_LOURD', 'name_en' => 'The Heavy Player', 'name_fr' => 'Le Joueur Lourd - Accélération en défense, duels agressifs', 'name_ar' => 'اللاعب الثقيل'],

            // =============================================
            // INJURY LOCATIONS
            // =============================================
            ['type' => 'injury_location', 'key' => 'CHEVILLES', 'name_en' => 'Ankles', 'name_fr' => 'Chevilles', 'name_ar' => 'الكاحلين'],
            ['type' => 'injury_location', 'key' => 'GENOUX', 'name_en' => 'Knees', 'name_fr' => 'Genoux', 'name_ar' => 'الركبتين'],
            ['type' => 'injury_location', 'key' => 'HANCHES', 'name_en' => 'Hips', 'name_fr' => 'Hanches', 'name_ar' => 'الوركين'],
            ['type' => 'injury_location', 'key' => 'PIEDS', 'name_en' => 'Feet', 'name_fr' => 'Pieds', 'name_ar' => 'القدمين'],
            ['type' => 'injury_location', 'key' => 'ADDUCTEURS', 'name_en' => 'Adductors', 'name_fr' => 'Adducteurs', 'name_ar' => 'العضلات المقربة'],
            ['type' => 'injury_location', 'key' => 'FESSIERS', 'name_en' => 'Glutes', 'name_fr' => 'Fessiers', 'name_ar' => 'عضلات الأرداف'],
            ['type' => 'injury_location', 'key' => 'ISCHIOS_JAMBIERS', 'name_en' => 'Hamstrings', 'name_fr' => 'Ischios Jambiers', 'name_ar' => 'أوتار الركبة'],
            ['type' => 'injury_location', 'key' => 'MOLLETS', 'name_en' => 'Calves', 'name_fr' => 'Mollets', 'name_ar' => 'عضلات الساق'],
            ['type' => 'injury_location', 'key' => 'MOYEN_FESSIERS', 'name_en' => 'Gluteus Medius', 'name_fr' => 'Moyen Fessiers', 'name_ar' => 'الأرداف الوسطى'],
            ['type' => 'injury_location', 'key' => 'PSOAS_FLECHISSEURS', 'name_en' => 'Hip Flexors', 'name_fr' => 'Psoas Fléchisseurs', 'name_ar' => 'عضلات الورك'],
            ['type' => 'injury_location', 'key' => 'QUADRICEPS', 'name_en' => 'Quadriceps', 'name_fr' => 'Quadriceps', 'name_ar' => 'عضلات الفخذ الأمامية'],

            // =============================================
            // MORPHOLOGY
            // =============================================
            ['type' => 'morphology', 'key' => 'ECTOMORPHE', 'name_en' => 'Ectomorph', 'name_fr' => 'Ectomorphe', 'name_ar' => 'إكتومورف'],
            ['type' => 'morphology', 'key' => 'MESOMORPHE', 'name_en' => 'Mesomorph', 'name_fr' => 'Mésomorphe', 'name_ar' => 'ميزومورف'],
            ['type' => 'morphology', 'key' => 'ENDOMORPHE', 'name_en' => 'Endomorph', 'name_fr' => 'Endomorphe', 'name_ar' => 'إندومورف'],

            // =============================================
            // ACTIVITY LEVEL
            // =============================================
            ['type' => 'activity_level', 'key' => 'SEDENTAIRE', 'name_en' => 'Sedentary', 'name_fr' => 'Travail de bureau, peu ou pas d\'exercice', 'name_ar' => 'خامل'],
            ['type' => 'activity_level', 'key' => 'LEGER', 'name_en' => 'Light Exercise', 'name_fr' => 'Exercice léger (1 à 3 fois par semaine)', 'name_ar' => 'تمارين خفيفة'],
            ['type' => 'activity_level', 'key' => 'MODERE', 'name_en' => 'Moderate Exercise', 'name_fr' => 'Exercice modéré (3 à 5 fois par semaine)', 'name_ar' => 'تمارين معتدلة'],
            ['type' => 'activity_level', 'key' => 'INTENSE', 'name_en' => 'Intense Exercise', 'name_fr' => 'Exercice intense (6 à 7 fois par semaine)', 'name_ar' => 'تمارين مكثفة'],
            ['type' => 'activity_level', 'key' => 'TRES_ACTIF', 'name_en' => 'Very Active', 'name_fr' => 'Travail physique très dur ou entraînement d\'athlète', 'name_ar' => 'نشيط جدا'],

            // =============================================
            // SPORTIF STATUS
            // =============================================
            ['type' => 'sportif_status', 'key' => 'AMATEUR', 'name_en' => 'Amateur', 'name_fr' => 'Amateur', 'name_ar' => 'هاوي'],
            ['type' => 'sportif_status', 'key' => 'CENTRE_FORMATION', 'name_en' => 'Training Center', 'name_fr' => 'Centre De Formation', 'name_ar' => 'مركز تدريب'],
            ['type' => 'sportif_status', 'key' => 'SEMI_PRO', 'name_en' => 'Semi-Pro', 'name_fr' => 'Semi-Pro', 'name_ar' => 'شبه محترف'],
            ['type' => 'sportif_status', 'key' => 'PRO', 'name_en' => 'Professional', 'name_fr' => 'Pro', 'name_ar' => 'محترف'],

            // =============================================
            // TRAINING DAYS
            // =============================================
            ['type' => 'training_day', 'key' => 'LUNDI', 'name_en' => 'Monday', 'name_fr' => 'Lundi', 'name_ar' => 'الإثنين'],
            ['type' => 'training_day', 'key' => 'MARDI', 'name_en' => 'Tuesday', 'name_fr' => 'Mardi', 'name_ar' => 'الثلاثاء'],
            ['type' => 'training_day', 'key' => 'MERCREDI', 'name_en' => 'Wednesday', 'name_fr' => 'Mercredi', 'name_ar' => 'الأربعاء'],
            ['type' => 'training_day', 'key' => 'JEUDI', 'name_en' => 'Thursday', 'name_fr' => 'Jeudi', 'name_ar' => 'الخميس'],
            ['type' => 'training_day', 'key' => 'VENDREDI', 'name_en' => 'Friday', 'name_fr' => 'Vendredi', 'name_ar' => 'الجمعة'],
            ['type' => 'training_day', 'key' => 'SAMEDI', 'name_en' => 'Saturday', 'name_fr' => 'Samedi', 'name_ar' => 'السبت'],
            ['type' => 'training_day', 'key' => 'DIMANCHE', 'name_en' => 'Sunday', 'name_fr' => 'Dimanche', 'name_ar' => 'الأحد'],

            // =============================================
            // GENDER
            // =============================================
            ['type' => 'gender', 'key' => 'HOMME', 'name_en' => 'Male', 'name_fr' => 'Homme', 'name_ar' => 'ذكر'],
            ['type' => 'gender', 'key' => 'FEMME', 'name_en' => 'Female', 'name_fr' => 'Femme', 'name_ar' => 'أنثى'],

            // =============================================
            // BREAKFAST PREFERENCES
            // =============================================
            ['type' => 'breakfast_preference', 'key' => 'PAIN', 'name_en' => 'Bread', 'name_fr' => 'Pains', 'name_ar' => 'خبز'],
            ['type' => 'breakfast_preference', 'key' => 'CONFITURE', 'name_en' => 'Jam', 'name_fr' => 'Confiture', 'name_ar' => 'مربى'],
            ['type' => 'breakfast_preference', 'key' => 'BEURRE', 'name_en' => 'Butter', 'name_fr' => 'Beurre', 'name_ar' => 'زبدة'],
            ['type' => 'breakfast_preference', 'key' => 'LAIT', 'name_en' => 'Milk', 'name_fr' => 'Lait', 'name_ar' => 'حليب'],
            ['type' => 'breakfast_preference', 'key' => 'CAFE', 'name_en' => 'Coffee', 'name_fr' => 'Café', 'name_ar' => 'قهوة'],
            ['type' => 'breakfast_preference', 'key' => 'CHOCOLAT_CHAUD_THE', 'name_en' => 'Hot Chocolate / Tea', 'name_fr' => 'Chocolat Chaud / Thé', 'name_ar' => 'شوكولاتة ساخنة / شاي'],
            ['type' => 'breakfast_preference', 'key' => 'JUS_CITRON_FRUITS', 'name_en' => 'Lemon / Fruit Juice', 'name_fr' => 'Jus de Citron / Fruits', 'name_ar' => 'عصير ليمون / فواكه'],
            ['type' => 'breakfast_preference', 'key' => 'OEUFS', 'name_en' => 'Eggs', 'name_fr' => 'Œufs', 'name_ar' => 'بيض'],
            ['type' => 'breakfast_preference', 'key' => 'SKIP_BREAKFAST', 'name_en' => 'Skip Breakfast', 'name_fr' => 'Je saute ce repas', 'name_ar' => 'أتخطى هذه الوجبة'],

            // =============================================
            // BAD HABITS
            // =============================================
            ['type' => 'bad_habit', 'key' => 'MANGE_TARD', 'name_en' => 'Eat Late at Night', 'name_fr' => 'Je mange tard le soir', 'name_ar' => 'آكل متأخرا في الليل'],
            ['type' => 'bad_habit', 'key' => 'PAS_ASSEZ_SOMMEIL', 'name_en' => 'Not Enough Sleep', 'name_fr' => 'Je ne dors pas assez', 'name_ar' => 'لا أنام بما يكفي'],
            ['type' => 'bad_habit', 'key' => 'SUCRERIES', 'name_en' => 'Love Sweets', 'name_fr' => "J'aime trop les sucreries", 'name_ar' => 'أحب الحلويات كثيرا'],
            ['type' => 'bad_habit', 'key' => 'SEL', 'name_en' => 'Too Much Salt', 'name_fr' => 'Je consomme beaucoup de sel', 'name_ar' => 'أستهلك الكثير من الملح'],
            ['type' => 'bad_habit', 'key' => 'SODAS', 'name_en' => 'Drink Sodas', 'name_fr' => 'Je bois des sodas', 'name_ar' => 'أشرب المشروبات الغازية'],

            // =============================================
            // SNACKING FREQUENCY
            // =============================================
            ['type' => 'snacking', 'key' => 'TOUS_LES_JOURS', 'name_en' => 'Every Day', 'name_fr' => 'Tous les jours', 'name_ar' => 'كل يوم'],
            ['type' => 'snacking', 'key' => 'EN_PERIODE_DE_STRESS', 'name_en' => 'During Stress', 'name_fr' => 'En période de stress', 'name_ar' => 'خلال فترات التوتر'],
            ['type' => 'snacking', 'key' => 'AU_TRAVAIL', 'name_en' => 'At Work', 'name_fr' => 'Au travail', 'name_ar' => 'في العمل'],
            ['type' => 'snacking', 'key' => 'EN_PERIODE_D_ENNUI', 'name_en' => 'When Bored', 'name_fr' => "En période d'ennui", 'name_ar' => 'عند الملل'],
            ['type' => 'snacking', 'key' => 'RAREMENT', 'name_en' => 'Rarely', 'name_fr' => 'Rarement', 'name_ar' => 'نادرا'],
            ['type' => 'snacking', 'key' => 'JAMAIS', 'name_en' => 'Never', 'name_fr' => 'Jamais', 'name_ar' => 'أبدا'],

            // =============================================
            // FOOD CONSUMPTION FREQUENCY
            // =============================================
            ['type' => 'food_frequency', 'key' => 'LEGUMES_TOUS_LES_JOURS', 'name_en' => 'Vegetables Every Day', 'name_fr' => 'Légumes tous les jours', 'name_ar' => 'خضروات كل يوم'],
            ['type' => 'food_frequency', 'key' => 'LEGUMES_1_2_SEMAINE', 'name_en' => 'Vegetables 1-2/week', 'name_fr' => 'Légumes 1/2 par semaine', 'name_ar' => 'خضروات 1-2 في الأسبوع'],
            ['type' => 'food_frequency', 'key' => 'LEGUMES_JAMAIS', 'name_en' => 'Never Vegetables', 'name_fr' => 'Légumes jamais', 'name_ar' => 'لا خضروات أبدا'],
            ['type' => 'food_frequency', 'key' => 'POISSON_TOUS_LES_JOURS', 'name_en' => 'Fish Every Day', 'name_fr' => 'Poisson tous les jours', 'name_ar' => 'سمك كل يوم'],
            ['type' => 'food_frequency', 'key' => 'POISSON_1_2_SEMAINE', 'name_en' => 'Fish 1-2/week', 'name_fr' => 'Poisson 1/2 par semaine', 'name_ar' => 'سمك 1-2 في الأسبوع'],
            ['type' => 'food_frequency', 'key' => 'POISSON_JAMAIS', 'name_en' => 'Never Fish', 'name_fr' => 'Poisson jamais', 'name_ar' => 'لا سمك أبدا'],
            ['type' => 'food_frequency', 'key' => 'VIANDE_TOUS_LES_JOURS', 'name_en' => 'Meat Every Day', 'name_fr' => 'Viande tous les jours', 'name_ar' => 'لحم كل يوم'],
            ['type' => 'food_frequency', 'key' => 'VIANDE_1_2_SEMAINE', 'name_en' => 'Meat 1-2/week', 'name_fr' => 'Viande 1/2 par semaine', 'name_ar' => 'لحم 1-2 في الأسبوع'],
            ['type' => 'food_frequency', 'key' => 'VIANDE_JAMAIS', 'name_en' => 'Never Meat', 'name_fr' => 'Viande jamais', 'name_ar' => 'لا لحم أبدا'],
            ['type' => 'food_frequency', 'key' => 'PRODUITS_LAITIERS_TOUS_LES_JOURS', 'name_en' => 'Dairy Every Day', 'name_fr' => 'Produits laitiers tous les jours', 'name_ar' => 'منتجات ألبان كل يوم'],
            ['type' => 'food_frequency', 'key' => 'PRODUITS_LAITIERS_1_2_SEMAINE', 'name_en' => 'Dairy 1-2/week', 'name_fr' => 'Produits laitiers 1/2 par semaine', 'name_ar' => 'منتجات ألبان 1-2 في الأسبوع'],
            ['type' => 'food_frequency', 'key' => 'PRODUITS_LAITIERS_JAMAIS', 'name_en' => 'Never Dairy', 'name_fr' => 'Produits laitiers jamais', 'name_ar' => 'لا منتجات ألبان أبدا'],

            // =============================================
            // MEALS PER DAY
            // =============================================
            ['type' => 'meals_per_day', 'key' => '1_REPAS', 'name_en' => '1 Meal', 'name_fr' => '1 (Matin / Midi / Soir)', 'name_ar' => 'وجبة واحدة'],
            ['type' => 'meals_per_day', 'key' => '2_REPAS', 'name_en' => '2 Meals', 'name_fr' => '2 (Matin / Midi / Soir)', 'name_ar' => 'وجبتان'],
            ['type' => 'meals_per_day', 'key' => '3_REPAS', 'name_en' => '3 Meals', 'name_fr' => '3 (Matin / Midi / Soir)', 'name_ar' => '3 وجبات'],

            // =============================================
            // MUSCULATION OBJECTIVES
            // =============================================
            // Force & Solidité
            ['type' => 'musculation_objective', 'key' => 'FORCE_MAXIMALE', 'name_en' => 'Maximum Strength', 'name_fr' => 'Force Maximale - Capacité à soulever une charge lourde', 'name_ar' => 'القوة القصوى'],
            ['type' => 'musculation_objective', 'key' => 'FORCE_SOUS_MAX', 'name_en' => 'Sub-Maximum Strength', 'name_fr' => 'Force Sous-Max - Travail avec des charges contrôlées', 'name_ar' => 'القوة دون القصوى'],
            ['type' => 'musculation_objective', 'key' => 'RENFORT_TENDINEUX', 'name_en' => 'Tendon Reinforcement', 'name_fr' => 'Renfort Tendineux - Protéger les articulations', 'name_ar' => 'تقوية الأوتار'],
            ['type' => 'musculation_objective', 'key' => 'PREVENTION', 'name_en' => 'Prevention', 'name_fr' => 'Prévention - Exercices ciblés pour éviter les douleurs', 'name_ar' => 'الوقاية'],

            // Tonicité & Explosivité
            ['type' => 'musculation_objective', 'key' => 'FORCE_EXPLOSIVE', 'name_en' => 'Explosive Strength', 'name_fr' => 'Force Explosive - Capacité à réagir vite', 'name_ar' => 'القوة المتفجرة'],
            ['type' => 'musculation_objective', 'key' => 'FORCE_DYNAMIQUE', 'name_en' => 'Dynamic Strength', 'name_fr' => 'Force Dynamique - Mouvements rapides et fluides', 'name_ar' => 'القوة الديناميكية'],
            ['type' => 'musculation_objective', 'key' => 'PUISSANCE_MUSCULAIRE', 'name_en' => 'Muscle Power', 'name_fr' => 'Puissance Musculaire - Mélange de force et de vitesse', 'name_ar' => 'قوة العضلات'],
            ['type' => 'musculation_objective', 'key' => 'COORDINATION', 'name_en' => 'Coordination', 'name_fr' => 'Coordination - Maîtrise des mouvements complexes', 'name_ar' => 'التنسيق'],

            // Galbe & Densité
            ['type' => 'musculation_objective', 'key' => 'HYPERTROPHIE_MYO', 'name_en' => 'Myofibrillar Hypertrophy', 'name_fr' => 'Hypertrophie Myo - Création de muscle ferme', 'name_ar' => 'التضخم العضلي الليفي'],
            ['type' => 'musculation_objective', 'key' => 'HYPERTROPHIE_SARC', 'name_en' => 'Sarcoplasmic Hypertrophy', 'name_fr' => 'Hypertrophie Sarc - Augmentation du volume', 'name_ar' => 'التضخم الساركوبلازمي'],
            ['type' => 'musculation_objective', 'key' => 'VOLUME_MUSCULAIRE', 'name_en' => 'Muscle Volume', 'name_fr' => 'Volume Musculaire - Gain de masse global', 'name_ar' => 'حجم العضلات'],

            // Métabolisme & Définition
            ['type' => 'musculation_objective', 'key' => 'PERTE_DE_POIDS', 'name_en' => 'Weight Loss', 'name_fr' => 'Perte de Poids - Réduction globale de la masse', 'name_ar' => 'فقدان الوزن'],
            ['type' => 'musculation_objective', 'key' => 'SECHE_DEFINITION', 'name_en' => 'Cut / Definition', 'name_fr' => 'Sèche / Définition - Éliminer le gras superficiel', 'name_ar' => 'التنشيف والتحديد'],
            ['type' => 'musculation_objective', 'key' => 'ENDURANCE_DE_FORCE', 'name_en' => 'Strength Endurance', 'name_fr' => 'Endurance de Force - Cardio et brûler les réserves', 'name_ar' => 'تحمل القوة'],
            ['type' => 'musculation_objective', 'key' => 'REPETITION_EFFORTS', 'name_en' => 'Repeated Efforts', 'name_fr' => 'Répétition Efforts - Enchaîner les exercices', 'name_ar' => 'تكرار الجهود'],

            // Bien-être & Récupération
            ['type' => 'musculation_objective', 'key' => 'REMISE_EN_FORME', 'name_en' => 'Get Back in Shape', 'name_fr' => 'Remise en Forme - Reprise progressive', 'name_ar' => 'استعادة اللياقة'],
            ['type' => 'musculation_objective', 'key' => 'CONDITION', 'name_en' => 'General Conditioning', 'name_fr' => 'Condition - Base de santé physique globale', 'name_ar' => 'اللياقة العامة'],
            ['type' => 'musculation_objective', 'key' => 'ENDURANCE_MUSCULAIRE', 'name_en' => 'Muscular Endurance', 'name_fr' => 'Endurance Musculaire - Tenir un effort long', 'name_ar' => 'التحمل العضلي'],
            ['type' => 'musculation_objective', 'key' => 'REATHLETISATION', 'name_en' => 'Re-athleticization', 'name_fr' => 'Réathlétisation - Retour au sport après blessure', 'name_ar' => 'إعادة التأهيل الرياضي'],

            // =============================================
            // DIETARY PREFERENCES
            // =============================================
            ['type' => 'dietary', 'key' => 'VEGETARIEN', 'name_en' => 'Vegetarian', 'name_fr' => 'Végétarien', 'name_ar' => 'نباتي'],
            ['type' => 'dietary', 'key' => 'NON_VEGETARIEN', 'name_en' => 'Non-Vegetarian', 'name_fr' => 'Non Végétarien', 'name_ar' => 'غير نباتي'],

            // =============================================
            // HORMONAL ISSUES (Women)
            // =============================================
            ['type' => 'hormonal', 'key' => 'TROUBLES_HORMONAUX_OUI', 'name_en' => 'Has Hormonal Issues', 'name_fr' => 'Troubles hormonaux - Oui', 'name_ar' => 'مشاكل هرمونية - نعم'],
            ['type' => 'hormonal', 'key' => 'TROUBLES_HORMONAUX_NON', 'name_en' => 'No Hormonal Issues', 'name_fr' => 'Troubles hormonaux - Non', 'name_ar' => 'مشاكل هرمونية - لا'],
            ['type' => 'hormonal', 'key' => 'TROUBLES_HORMONAUX_NSP', 'name_en' => 'Unknown', 'name_fr' => 'Troubles hormonaux - Je ne sais pas', 'name_ar' => 'مشاكل هرمونية - لا أعرف'],
        ];

        foreach ($options as $option) {
            OnboardingOption::create($option);
        }
    }
}
