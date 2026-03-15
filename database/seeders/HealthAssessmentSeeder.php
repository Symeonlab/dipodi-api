<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HealthAssessmentCategory;
use App\Models\HealthAssessmentQuestion;

class HealthAssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategories();
        $this->seedQuestions();
    }

    private function seedCategories(): void
    {
        $categories = [
            // Sport Performance Categories
            ['key' => 'energy_recovery', 'name_fr' => 'Énergie et Récupération Globale', 'name_en' => 'Energy and Overall Recovery', 'icon' => 'battery.100', 'sort_order' => 1],
            ['key' => 'injuries_muscles', 'name_fr' => 'Blessures et Mécanique Musculaire', 'name_en' => 'Injuries and Muscle Mechanics', 'icon' => 'bandage', 'sort_order' => 2],
            ['key' => 'traumatology', 'name_fr' => 'Traumatologie et Structure', 'name_en' => 'Traumatology and Structure', 'icon' => 'figure.wave', 'sort_order' => 3],
            ['key' => 'digestion_metabolism', 'name_fr' => 'Digestion et Métabolisme du Sportif', 'name_en' => 'Digestion and Athlete Metabolism', 'icon' => 'leaf', 'sort_order' => 4],
            ['key' => 'match_performance', 'name_fr' => 'Performance et Endurance en Match', 'name_en' => 'Match Performance and Endurance', 'icon' => 'sportscourt', 'sort_order' => 5, 'discipline' => 'football'],
            ['key' => 'hydration', 'name_fr' => 'Hydratation et Équilibre Minéral', 'name_en' => 'Hydration and Mineral Balance', 'icon' => 'drop', 'sort_order' => 6],
            ['key' => 'psychology', 'name_fr' => 'Psychologie et Récupération Nerveuse', 'name_en' => 'Psychology and Nervous Recovery', 'icon' => 'brain', 'sort_order' => 7],
            ['key' => 'high_level_trauma', 'name_fr' => 'Traumatologie du Sportif de Haut Niveau', 'name_en' => 'High-Level Athlete Traumatology', 'icon' => 'figure.run', 'sort_order' => 8, 'discipline' => 'football'],
            ['key' => 'recovery_metabolism', 'name_fr' => 'Récupération et Métabolisme', 'name_en' => 'Recovery and Metabolism', 'icon' => 'arrow.clockwise', 'sort_order' => 9],

            // Fitness Specific Categories
            ['key' => 'fitness_energy', 'name_fr' => 'Énergie et Performance Physique', 'name_en' => 'Energy and Physical Performance', 'icon' => 'bolt', 'sort_order' => 10, 'discipline' => 'fitness'],
            ['key' => 'silhouette', 'name_fr' => 'Silhouette et Composition Corporelle', 'name_en' => 'Silhouette and Body Composition', 'icon' => 'figure.stand', 'sort_order' => 11, 'discipline' => 'fitness'],
            ['key' => 'mental_recovery', 'name_fr' => 'Santé Mentale et Récupération', 'name_en' => 'Mental Health and Recovery', 'icon' => 'heart', 'sort_order' => 12, 'discipline' => 'fitness'],
            ['key' => 'internal_balance', 'name_fr' => 'Équilibre Interne et Inflammation', 'name_en' => 'Internal Balance and Inflammation', 'icon' => 'sparkles', 'sort_order' => 13, 'discipline' => 'fitness'],

            // Medical Categories (for all)
            ['key' => 'orl_respiratory', 'name_fr' => 'Système ORL et Respiratoire', 'name_en' => 'ENT and Respiratory System', 'icon' => 'lungs', 'sort_order' => 20],
            ['key' => 'nervous_psychic', 'name_fr' => 'Système Nerveux et Psychique', 'name_en' => 'Nervous and Psychic System', 'icon' => 'brain.head.profile', 'sort_order' => 21],
            ['key' => 'digestive', 'name_fr' => 'Système Digestif et Transit', 'name_en' => 'Digestive System and Transit', 'icon' => 'stomach', 'sort_order' => 22],
            ['key' => 'metabolic_blood', 'name_fr' => 'Système Métabolique et Sanguin', 'name_en' => 'Metabolic and Blood System', 'icon' => 'drop.fill', 'sort_order' => 23],
            ['key' => 'wellness', 'name_fr' => 'Bien-être Global et Santé', 'name_en' => 'Overall Wellness and Health', 'icon' => 'heart.circle', 'sort_order' => 24],
        ];

        foreach ($categories as $category) {
            HealthAssessmentCategory::create($category);
        }
    }

    private function seedQuestions(): void
    {
        $questions = [
            // =============================================
            // ÉNERGIE ET RÉCUPÉRATION GLOBALE
            // =============================================
            // Fatigue / Baisse d'énergie
            ['category' => 'energy_recovery', 'subcategory' => 'FATIGUE', 'question_fr' => 'Ressens-tu une fatigue dès le réveil malgré une nuit de sommeil complète ?', 'question_en' => 'Do you feel tired upon waking despite a full night\'s sleep?'],
            ['category' => 'energy_recovery', 'subcategory' => 'FATIGUE', 'question_fr' => 'Ton énergie chute-t-elle brutalement environ 20 minutes après avoir commencé ton cardio ?', 'question_en' => 'Does your energy drop sharply about 20 minutes after starting cardio?'],
            ['category' => 'energy_recovery', 'subcategory' => 'FATIGUE', 'question_fr' => 'As-tu besoin de stimulants (café, sucre) pour tenir ta séance de sport ?', 'question_en' => 'Do you need stimulants (coffee, sugar) to get through your workout?'],
            ['category' => 'energy_recovery', 'subcategory' => 'FATIGUE', 'question_fr' => 'Ta fatigue s\'accompagne-t-elle de vertiges ou d\'une vision floue ?', 'question_en' => 'Is your fatigue accompanied by dizziness or blurred vision?'],
            ['category' => 'energy_recovery', 'subcategory' => 'FATIGUE', 'question_fr' => 'Ressens-tu une flemme mentale qui t\'empêche de te motiver pour le sport ?', 'question_en' => 'Do you feel mental laziness that prevents you from motivating yourself for sports?'],
            ['category' => 'energy_recovery', 'subcategory' => 'FATIGUE', 'question_fr' => 'Ta récupération d\'énergie est-elle plus rapide après avoir consommé du miel ou des dattes ?', 'question_en' => 'Is your energy recovery faster after consuming honey or dates?'],

            // Surentraînement / Fatigue chronique
            ['category' => 'energy_recovery', 'subcategory' => 'SURTRAINING', 'question_fr' => 'Ton rythme cardiaque au repos est-il plus élevé que d\'habitude ces derniers temps ?', 'question_en' => 'Has your resting heart rate been higher than usual lately?'],
            ['category' => 'energy_recovery', 'subcategory' => 'SURTRAINING', 'question_fr' => 'Ressens-tu une lassitude psychologique face à l\'idée même de t\'entraîner ?', 'question_en' => 'Do you feel psychological weariness at the very idea of training?'],
            ['category' => 'energy_recovery', 'subcategory' => 'SURTRAINING', 'question_fr' => 'Es-tu devenu plus sensible aux infections (rhumes, maux de gorge) à cause du sport ?', 'question_en' => 'Have you become more susceptible to infections (colds, sore throats) due to sports?'],
            ['category' => 'energy_recovery', 'subcategory' => 'SURTRAINING', 'question_fr' => 'Ta progression stagne-t-elle ou tes performances régressent-elles malgré l\'effort ?', 'question_en' => 'Is your progress stagnating or are your performances declining despite effort?'],
            ['category' => 'energy_recovery', 'subcategory' => 'SURTRAINING', 'question_fr' => 'Souffres-tu d\'une perte d\'appétit ou, au contraire, de boulimie après tes séances ?', 'question_en' => 'Do you suffer from loss of appetite or, conversely, binge eating after your sessions?'],
            ['category' => 'energy_recovery', 'subcategory' => 'SURTRAINING', 'question_fr' => 'As-tu des douleurs diffuses qui ne disparaissent plus même après 3 jours de repos ?', 'question_en' => 'Do you have diffuse pain that doesn\'t disappear even after 3 days of rest?'],

            // Anémie / Déficit en fer
            ['category' => 'energy_recovery', 'subcategory' => 'ANEMIA', 'question_fr' => 'Tes essoufflements sont-ils anormaux même lors d\'une marche lente ?', 'question_en' => 'Is your shortness of breath abnormal even during slow walking?'],
            ['category' => 'energy_recovery', 'subcategory' => 'ANEMIA', 'question_fr' => 'Constates-tu une pâleur inhabituelle au niveau de tes gencives ou de l\'intérieur de tes paupières ?', 'question_en' => 'Do you notice unusual paleness in your gums or inside your eyelids?'],
            ['category' => 'energy_recovery', 'subcategory' => 'ANEMIA', 'question_fr' => 'Tes cheveux sont-ils cassants ou tes ongles présentent-ils des stries ?', 'question_en' => 'Is your hair brittle or do your nails have ridges?'],
            ['category' => 'energy_recovery', 'subcategory' => 'ANEMIA', 'question_fr' => 'Ressens-tu des maux de tête fréquents lors d\'efforts en altitude ou en côte ?', 'question_en' => 'Do you experience frequent headaches during altitude or uphill efforts?'],
            ['category' => 'energy_recovery', 'subcategory' => 'ANEMIA', 'question_fr' => 'As-tu souvent les mains et les pieds glacés, même en été ?', 'question_en' => 'Do you often have ice-cold hands and feet, even in summer?'],
            ['category' => 'energy_recovery', 'subcategory' => 'ANEMIA', 'question_fr' => 'Ta concentration diminue-t-elle rapidement durant la journée ?', 'question_en' => 'Does your concentration decrease rapidly during the day?'],

            // =============================================
            // BLESSURES ET MÉCANIQUE MUSCULAIRE
            // =============================================
            // Crampes / Spasmes / Déshydratation chronique
            ['category' => 'injuries_muscles', 'subcategory' => 'CRAMPES', 'question_fr' => 'Tes crampes surviennent-elles principalement pendant l\'effort ou quelques heures après ?', 'question_en' => 'Do your cramps occur mainly during exercise or a few hours after?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'CRAMPES', 'question_fr' => 'Ressens-tu des tressautements musculaires (paupières, mollets) au repos ?', 'question_en' => 'Do you feel muscle twitches (eyelids, calves) at rest?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'CRAMPES', 'question_fr' => 'Ta peau garde-t-elle le pli lorsque tu la pinces légèrement (signe de manque d\'eau) ?', 'question_en' => 'Does your skin keep the fold when you pinch it slightly (sign of dehydration)?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'CRAMPES', 'question_fr' => 'La couleur de tes urines est-elle foncée dès le matin ?', 'question_en' => 'Is your urine color dark in the morning?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'CRAMPES', 'question_fr' => 'Tes crampes sont-elles calmées immédiatement par l\'ingestion de sels minéraux ou d\'eau tiède ?', 'question_en' => 'Are your cramps immediately relieved by mineral salts or warm water?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'CRAMPES', 'question_fr' => 'Ressens-tu une soif intense que tu n\'arrives pas à étancher ?', 'question_en' => 'Do you feel intense thirst that you can\'t quench?'],

            // Blessures musculaires / Élongations / Déchirures
            ['category' => 'injuries_muscles', 'subcategory' => 'MUSCLE_INJURIES', 'question_fr' => 'As-tu ressenti une pointe vive comme une "aiguille" lors d\'une accélération ?', 'question_en' => 'Did you feel a sharp point like a "needle" during an acceleration?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'MUSCLE_INJURIES', 'question_fr' => 'Le muscle touché présente-t-il un hématome ou une zone de chaleur localisée ?', 'question_en' => 'Does the affected muscle show a bruise or localized heat area?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'MUSCLE_INJURIES', 'question_fr' => 'Ressens-tu une raideur protectrice qui t\'empêche d\'étirer le muscle ?', 'question_en' => 'Do you feel a protective stiffness that prevents you from stretching the muscle?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'MUSCLE_INJURIES', 'question_fr' => 'La douleur est-elle réapparue exactement au même endroit qu\'une ancienne blessure ?', 'question_en' => 'Has the pain reappeared in exactly the same place as an old injury?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'MUSCLE_INJURIES', 'question_fr' => 'Ton muscle semble-t-il "mou" ou sans tonus à l\'endroit de la lésion ?', 'question_en' => 'Does your muscle seem "soft" or without tone at the injury site?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'MUSCLE_INJURIES', 'question_fr' => 'La douleur t\'oblige-t-elle à boiter ou à modifier ta démarche ?', 'question_en' => 'Does the pain force you to limp or change your gait?'],

            // Tendinites et surcharge articulaire
            ['category' => 'injuries_muscles', 'subcategory' => 'TENDINITIS', 'question_fr' => 'La douleur au tendon est-elle plus forte à froid (le matin) qu\'après l\'échauffement ?', 'question_en' => 'Is tendon pain worse when cold (morning) than after warming up?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'TENDINITIS', 'question_fr' => 'Ressens-tu une douleur "exquise" (très précise) lorsque tu appuies sur le tendon ?', 'question_en' => 'Do you feel "exquisite" (very precise) pain when pressing on the tendon?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'TENDINITIS', 'question_fr' => 'Ton articulation grince-t-elle ou émet-elle des bruits de frottement ?', 'question_en' => 'Does your joint grind or make friction noises?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'TENDINITIS', 'question_fr' => 'La douleur irradie-t-elle le long du muscle rattaché au tendon ?', 'question_en' => 'Does the pain radiate along the muscle attached to the tendon?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'TENDINITIS', 'question_fr' => 'Ton tendon semble-t-il plus épais ou présente-t-il une petite bosse ?', 'question_en' => 'Does your tendon seem thicker or does it have a small bump?'],
            ['category' => 'injuries_muscles', 'subcategory' => 'TENDINITIS', 'question_fr' => 'La douleur persiste-t-elle la nuit après une grosse séance de cardio ?', 'question_en' => 'Does the pain persist at night after a big cardio session?'],

            // =============================================
            // TRAUMATOLOGIE ET STRUCTURE
            // =============================================
            // Blessures osseuses / Fractures / Ostéoporose
            ['category' => 'traumatology', 'subcategory' => 'BONE_INJURIES', 'question_fr' => 'Ressens-tu une douleur profonde dans l\'os qui ne ressemble pas à une douleur musculaire ?', 'question_en' => 'Do you feel deep pain in the bone that doesn\'t feel like muscle pain?'],
            ['category' => 'traumatology', 'subcategory' => 'BONE_INJURIES', 'question_fr' => 'As-tu des antécédents de fractures de fatigue liées à une pratique trop intense ?', 'question_en' => 'Do you have a history of stress fractures related to overly intense practice?'],
            ['category' => 'traumatology', 'subcategory' => 'BONE_INJURIES', 'question_fr' => 'La zone osseuse est-elle sensible à la simple pression du doigt ?', 'question_en' => 'Is the bone area sensitive to simple finger pressure?'],
            ['category' => 'traumatology', 'subcategory' => 'BONE_INJURIES', 'question_fr' => 'Ta consommation de produits riches en calcium et silice est-elle insuffisante ?', 'question_en' => 'Is your consumption of calcium and silica-rich products insufficient?'],
            ['category' => 'traumatology', 'subcategory' => 'BONE_INJURIES', 'question_fr' => 'Ressens-tu des élancements dans les os lors d\'une exposition au froid humide ?', 'question_en' => 'Do you feel throbbing in your bones when exposed to damp cold?'],
            ['category' => 'traumatology', 'subcategory' => 'BONE_INJURIES', 'question_fr' => 'Ton équilibre général est-il précaire (tendance à tomber ou trébucher) ?', 'question_en' => 'Is your general balance precarious (tendency to fall or trip)?'],

            // Ligaments / Ménisque / Entorse
            ['category' => 'traumatology', 'subcategory' => 'LIGAMENT_INJURIES', 'question_fr' => 'Ton articulation a-t-elle "tourné" ou s\'est-elle dérobée lors d\'un appui ?', 'question_en' => 'Did your joint "turn" or give way during support?'],
            ['category' => 'traumatology', 'subcategory' => 'LIGAMENT_INJURIES', 'question_fr' => 'Ressens-tu un blocage articulaire (impossibilité de tendre ou plier totalement) ?', 'question_en' => 'Do you feel joint locking (inability to fully extend or bend)?'],
            ['category' => 'traumatology', 'subcategory' => 'LIGAMENT_INJURIES', 'question_fr' => 'Ton articulation a-t-elle gonflé instantanément après le choc ?', 'question_en' => 'Did your joint swell instantly after the impact?'],
            ['category' => 'traumatology', 'subcategory' => 'LIGAMENT_INJURIES', 'question_fr' => 'Ressens-tu une instabilité comme si ton genou ou ta cheville ne te tenait plus ?', 'question_en' => 'Do you feel instability as if your knee or ankle no longer supports you?'],
            ['category' => 'traumatology', 'subcategory' => 'LIGAMENT_INJURIES', 'question_fr' => 'La douleur est-elle localisée sur les côtés de l\'articulation ?', 'question_en' => 'Is the pain located on the sides of the joint?'],
            ['category' => 'traumatology', 'subcategory' => 'LIGAMENT_INJURIES', 'question_fr' => 'As-tu des difficultés à monter ou descendre les escaliers ?', 'question_en' => 'Do you have difficulty going up or down stairs?'],

            // =============================================
            // DIGESTION ET MÉTABOLISME DU SPORTIF
            // =============================================
            // Inflammation intestinale / Diarrhée / Troubles de voyage
            ['category' => 'digestion_metabolism', 'subcategory' => 'INTESTINAL', 'question_fr' => 'Ton transit s\'accélère-t-il dès que tu commences à courir (diarrhée du coureur) ?', 'question_en' => 'Does your transit speed up as soon as you start running (runner\'s diarrhea)?'],
            ['category' => 'digestion_metabolism', 'subcategory' => 'INTESTINAL', 'question_fr' => 'Souffres-tu de spasmes intestinaux violents après avoir mangé du gluten ou des laitages ?', 'question_en' => 'Do you suffer from violent intestinal spasms after eating gluten or dairy?'],
            ['category' => 'digestion_metabolism', 'subcategory' => 'INTESTINAL', 'question_fr' => 'Ton ventre est-il gonflé de gaz douloureux qui t\'empêchent de respirer à fond ?', 'question_en' => 'Is your belly bloated with painful gas that prevents you from breathing deeply?'],
            ['category' => 'digestion_metabolism', 'subcategory' => 'INTESTINAL', 'question_fr' => 'As-tu remarqué du sang ou des glaires dans tes selles lors de périodes de stress ?', 'question_en' => 'Have you noticed blood or mucus in your stools during periods of stress?'],
            ['category' => 'digestion_metabolism', 'subcategory' => 'INTESTINAL', 'question_fr' => 'Ta digestion est-elle perturbée dès que tu changes d\'environnement ou d\'eau ?', 'question_en' => 'Is your digestion disturbed as soon as you change environment or water?'],
            ['category' => 'digestion_metabolism', 'subcategory' => 'INTESTINAL', 'question_fr' => 'Ressens-tu une brûlure rectale suite à des épisodes de diarrhées répétées ?', 'question_en' => 'Do you feel rectal burning following repeated diarrhea episodes?'],

            // Obésité / Prise de poids / Catabolisme
            ['category' => 'digestion_metabolism', 'subcategory' => 'WEIGHT', 'question_fr' => 'Ta prise de poids est-elle liée à une sédentarité ou à une alimentation émotionnelle ?', 'question_en' => 'Is your weight gain related to sedentary lifestyle or emotional eating?'],
            ['category' => 'digestion_metabolism', 'subcategory' => 'WEIGHT', 'question_fr' => 'Ressens-tu une perte de volume musculaire malgré tes efforts sportifs (fonte musculaire) ?', 'question_en' => 'Do you feel muscle volume loss despite your sports efforts (muscle wasting)?'],
            ['category' => 'digestion_metabolism', 'subcategory' => 'WEIGHT', 'question_fr' => 'Ton corps semble-t-il "flasque" malgré la perte de poids sur la balance ?', 'question_en' => 'Does your body seem "flabby" despite weight loss on the scale?'],
            ['category' => 'digestion_metabolism', 'subcategory' => 'WEIGHT', 'question_fr' => 'As-tu des fringales de protéines (viande, œufs) après ton cardio ?', 'question_en' => 'Do you have protein cravings (meat, eggs) after your cardio?'],
            ['category' => 'digestion_metabolism', 'subcategory' => 'WEIGHT', 'question_fr' => 'Ta peau manque-t-elle d\'élasticité lors de ta perte de poids ?', 'question_en' => 'Does your skin lack elasticity during your weight loss?'],
            ['category' => 'digestion_metabolism', 'subcategory' => 'WEIGHT', 'question_fr' => 'Ton poids stagne-t-il malgré un déficit calorique important ?', 'question_en' => 'Does your weight plateau despite a significant caloric deficit?'],

            // =============================================
            // PERFORMANCE ET ENDURANCE EN MATCH (Football)
            // =============================================
            // Manque d'énergie en match
            ['category' => 'match_performance', 'subcategory' => 'MATCH_ENERGY', 'question_fr' => 'Ressens-tu des "jambes lourdes" dès les premières minutes de l\'échauffement ?', 'question_en' => 'Do you feel "heavy legs" from the first minutes of warm-up?'],
            ['category' => 'match_performance', 'subcategory' => 'MATCH_ENERGY', 'question_fr' => 'Ton énergie chute-t-elle brusquement après un premier effort intense (sprint, duel) ?', 'question_en' => 'Does your energy drop sharply after a first intense effort (sprint, duel)?'],
            ['category' => 'match_performance', 'subcategory' => 'MATCH_ENERGY', 'question_fr' => 'As-tu des difficultés à rester concentré visuellement sur la balle ou l\'adversaire ?', 'question_en' => 'Do you have difficulty staying visually focused on the ball or opponent?'],
            ['category' => 'match_performance', 'subcategory' => 'MATCH_ENERGY', 'question_fr' => 'Ressens-tu un manque de "punch" ou d\'explosivité dans tes démarrages ?', 'question_en' => 'Do you feel a lack of "punch" or explosiveness in your starts?'],
            ['category' => 'match_performance', 'subcategory' => 'MATCH_ENERGY', 'question_fr' => 'Ton souffle devient-il court très rapidement, même sans intensité maximale ?', 'question_en' => 'Does your breath become short very quickly, even without maximum intensity?'],
            ['category' => 'match_performance', 'subcategory' => 'MATCH_ENERGY', 'question_fr' => 'Ta performance s\'améliore-t-elle si tu consommes des glucides rapides juste avant ?', 'question_en' => 'Does your performance improve if you consume fast carbs just before?'],

            // Baisse d'intensité en fin de match
            ['category' => 'match_performance', 'subcategory' => 'END_MATCH', 'question_fr' => 'Tes appuis deviennent-ils fuyants ou imprécis dans le dernier quart de l\'effort ?', 'question_en' => 'Do your footwork become slippery or imprecise in the last quarter of effort?'],
            ['category' => 'match_performance', 'subcategory' => 'END_MATCH', 'question_fr' => 'Ressens-tu une sensation de "brûlure" musculaire qui t\'oblige à ralentir ?', 'question_en' => 'Do you feel a muscle "burning" sensation that forces you to slow down?'],
            ['category' => 'match_performance', 'subcategory' => 'END_MATCH', 'question_fr' => 'Ta lucidité tactique diminue-t-elle (mauvais choix) à cause de la fatigue ?', 'question_en' => 'Does your tactical lucidity decrease (bad choices) due to fatigue?'],
            ['category' => 'match_performance', 'subcategory' => 'END_MATCH', 'question_fr' => 'As-tu du mal à maintenir ta posture (dos qui s\'affaisse) en fin de match ?', 'question_en' => 'Do you have trouble maintaining your posture (back sagging) at the end of the match?'],
            ['category' => 'match_performance', 'subcategory' => 'END_MATCH', 'question_fr' => 'Ton temps de réaction s\'allonge-t-il de manière visible sur la fin ?', 'question_en' => 'Does your reaction time noticeably lengthen towards the end?'],
            ['category' => 'match_performance', 'subcategory' => 'END_MATCH', 'question_fr' => 'Mets-tu plus de temps que tes coéquipiers à retrouver ton souffle après un sprint ?', 'question_en' => 'Do you take longer than your teammates to catch your breath after a sprint?'],

            // =============================================
            // HYDRATATION ET ÉQUILIBRE MINÉRAL
            // =============================================
            // Déshydratation (chaleur, indoor)
            ['category' => 'hydration', 'subcategory' => 'DEHYDRATION_ACUTE', 'question_fr' => 'Ressens-tu une sécheresse buccale intense qui t\'empêche de déglutir normalement ?', 'question_en' => 'Do you feel intense dry mouth that prevents you from swallowing normally?'],
            ['category' => 'hydration', 'subcategory' => 'DEHYDRATION_ACUTE', 'question_fr' => 'Ta peau devient-elle rouge et brûlante sans que tu ne transpires beaucoup ?', 'question_en' => 'Does your skin become red and hot without you sweating much?'],
            ['category' => 'hydration', 'subcategory' => 'DEHYDRATION_ACUTE', 'question_fr' => 'Ressens-tu des maux de tête pulsatiles pendant l\'effort en salle (indoor) ?', 'question_en' => 'Do you feel pulsating headaches during indoor exercise?'],
            ['category' => 'hydration', 'subcategory' => 'DEHYDRATION_ACUTE', 'question_fr' => 'Ta vision se trouble-t-elle légèrement lors de brusques changements de direction ?', 'question_en' => 'Does your vision blur slightly during sudden direction changes?'],
            ['category' => 'hydration', 'subcategory' => 'DEHYDRATION_ACUTE', 'question_fr' => 'Éprouves-tu une sensation de nausée liée à la chaleur ambiante ?', 'question_en' => 'Do you feel nauseous due to ambient heat?'],
            ['category' => 'hydration', 'subcategory' => 'DEHYDRATION_ACUTE', 'question_fr' => 'Ta performance chute-t-elle de plus de 50% sous une température élevée ?', 'question_en' => 'Does your performance drop by more than 50% in high temperatures?'],

            // Déshydratation chronique / Crampes récurrentes
            ['category' => 'hydration', 'subcategory' => 'DEHYDRATION_CHRONIC', 'question_fr' => 'Tes crampes surviennent-elles systématiquement après 60 minutes d\'effort ?', 'question_en' => 'Do your cramps systematically occur after 60 minutes of effort?'],
            ['category' => 'hydration', 'subcategory' => 'DEHYDRATION_CHRONIC', 'question_fr' => 'Constates-tu des traces de sel (marques blanches) sur tes vêtements après le match ?', 'question_en' => 'Do you notice salt traces (white marks) on your clothes after the match?'],
            ['category' => 'hydration', 'subcategory' => 'DEHYDRATION_CHRONIC', 'question_fr' => 'Tes urines restent-elles foncées même après avoir bu 1 litre d\'eau post-match ?', 'question_en' => 'Does your urine remain dark even after drinking 1 liter of water post-match?'],
            ['category' => 'hydration', 'subcategory' => 'DEHYDRATION_CHRONIC', 'question_fr' => 'Ressens-tu des spasmes musculaires au repos le soir après une compétition ?', 'question_en' => 'Do you feel muscle spasms at rest in the evening after a competition?'],
            ['category' => 'hydration', 'subcategory' => 'DEHYDRATION_CHRONIC', 'question_fr' => 'Tes muscles sont-ils anormalement raides le lendemain, même sans courbatures ?', 'question_en' => 'Are your muscles abnormally stiff the next day, even without soreness?'],
            ['category' => 'hydration', 'subcategory' => 'DEHYDRATION_CHRONIC', 'question_fr' => 'Bois-tu moins de 500ml d\'eau par heure durant tes matchs ?', 'question_en' => 'Do you drink less than 500ml of water per hour during your matches?'],

            // =============================================
            // PSYCHOLOGIE ET RÉCUPÉRATION NERVEUSE
            // =============================================
            // Fatigue nerveuse / Stress match
            ['category' => 'psychology', 'subcategory' => 'MATCH_STRESS', 'question_fr' => 'Ressens-tu une "peur au ventre" qui paralyse tes mouvements habituels ?', 'question_en' => 'Do you feel a "gut fear" that paralyzes your usual movements?'],
            ['category' => 'psychology', 'subcategory' => 'MATCH_STRESS', 'question_fr' => 'Tes mains tremblent-elles ou as-tu les mains moites avant le début ?', 'question_en' => 'Do your hands tremble or do you have sweaty palms before starting?'],
            ['category' => 'psychology', 'subcategory' => 'MATCH_STRESS', 'question_fr' => 'Ton sommeil est-il perturbé la veille du match par des scénarios de jeu ?', 'question_en' => 'Is your sleep disturbed the night before the match by game scenarios?'],
            ['category' => 'psychology', 'subcategory' => 'MATCH_STRESS', 'question_fr' => 'Te sens-tu irritable ou "à fleur de peau" avec tes partenaires ou l\'arbitre ?', 'question_en' => 'Do you feel irritable or "on edge" with your partners or the referee?'],
            ['category' => 'psychology', 'subcategory' => 'MATCH_STRESS', 'question_fr' => 'Ressens-tu une fatigue mentale immense (envie de dormir) juste après le match ?', 'question_en' => 'Do you feel immense mental fatigue (desire to sleep) right after the match?'],
            ['category' => 'psychology', 'subcategory' => 'MATCH_STRESS', 'question_fr' => 'Ton stress impacte-t-il ta digestion (diarrhée ou estomac noué) avant le match ?', 'question_en' => 'Does your stress impact your digestion (diarrhea or knotted stomach) before the match?'],

            // Troubles du sommeil (tournois)
            ['category' => 'psychology', 'subcategory' => 'SLEEP', 'question_fr' => 'As-tu du mal à t\'endormir après un match tardif à cause de l\'adrénaline ?', 'question_en' => 'Do you have trouble falling asleep after a late match due to adrenaline?'],
            ['category' => 'psychology', 'subcategory' => 'SLEEP', 'question_fr' => 'Ton sommeil est-il fractionné lorsque tu dors hors de chez toi (hôtel, tournoi) ?', 'question_en' => 'Is your sleep fragmented when you sleep away from home (hotel, tournament)?'],
            ['category' => 'psychology', 'subcategory' => 'SLEEP', 'question_fr' => 'Ressens-tu des impatiences dans les jambes (besoin de bouger) une fois au lit ?', 'question_en' => 'Do you feel restless legs (need to move) once in bed?'],
            ['category' => 'psychology', 'subcategory' => 'SLEEP', 'question_fr' => 'Te réveilles-tu avec une sensation de corps "courbaturé" avant même de bouger ?', 'question_en' => 'Do you wake up feeling "sore" before even moving?'],
            ['category' => 'psychology', 'subcategory' => 'SLEEP', 'question_fr' => 'Tes nuits sont-elles trop courtes pour te permettre d\'enchaîner deux matchs ?', 'question_en' => 'Are your nights too short to allow you to play two matches in a row?'],
            ['category' => 'psychology', 'subcategory' => 'SLEEP', 'question_fr' => 'Utilises-tu des aides (mélatonine, tisane) pour forcer le repos en compétition ?', 'question_en' => 'Do you use aids (melatonin, herbal tea) to force rest during competition?'],

            // =============================================
            // SYSTÈME ORL ET RESPIRATOIRE (Medical)
            // =============================================
            // Toux
            ['category' => 'orl_respiratory', 'subcategory' => 'TOUX', 'question_fr' => 'Ta toux est-elle déclenchée par la position allongée ou par l\'humidité ?', 'question_en' => 'Is your cough triggered by lying down or by humidity?'],
            ['category' => 'orl_respiratory', 'subcategory' => 'TOUX', 'question_fr' => 'Est-ce une toux sèche (irritation) ou grasse (encombrement) ?', 'question_en' => 'Is it a dry cough (irritation) or wet cough (congestion)?'],
            ['category' => 'orl_respiratory', 'subcategory' => 'TOUX', 'question_fr' => 'La toux s\'intensifie-t-elle la nuit ou au petit matin ?', 'question_en' => 'Does the cough intensify at night or in the early morning?'],
            ['category' => 'orl_respiratory', 'subcategory' => 'TOUX', 'question_fr' => 'Ressens-tu des sifflements dans la poitrine quand tu tousses ?', 'question_en' => 'Do you feel wheezing in your chest when you cough?'],
            ['category' => 'orl_respiratory', 'subcategory' => 'TOUX', 'question_fr' => 'La toux est-elle provoquée par un changement brusque de température ?', 'question_en' => 'Is the cough caused by a sudden temperature change?'],
            ['category' => 'orl_respiratory', 'subcategory' => 'TOUX', 'question_fr' => 'As-tu des quintes de toux après avoir mangé certains aliments ?', 'question_en' => 'Do you have coughing fits after eating certain foods?'],

            // Sinusite
            ['category' => 'orl_respiratory', 'subcategory' => 'SINUSITIS', 'question_fr' => 'Ta sinusite provoque-t-elle des douleurs derrière les yeux ou au front ?', 'question_en' => 'Does your sinusitis cause pain behind the eyes or in the forehead?'],
            ['category' => 'orl_respiratory', 'subcategory' => 'SINUSITIS', 'question_fr' => 'Ressens-tu une perte d\'odorat ou de goût durant les crises ?', 'question_en' => 'Do you experience loss of smell or taste during episodes?'],
            ['category' => 'orl_respiratory', 'subcategory' => 'SINUSITIS', 'question_fr' => 'Les douleurs augmentent-elles quand tu penches la tête en avant ?', 'question_en' => 'Does the pain increase when you tilt your head forward?'],
            ['category' => 'orl_respiratory', 'subcategory' => 'SINUSITIS', 'question_fr' => 'Tes sécrétions sont-elles claires ou épaisses et colorées ?', 'question_en' => 'Are your secretions clear or thick and colored?'],
            ['category' => 'orl_respiratory', 'subcategory' => 'SINUSITIS', 'question_fr' => 'Souffres-tu de maux de dents liés à cette pression sinusale ?', 'question_en' => 'Do you suffer from toothaches related to this sinus pressure?'],
            ['category' => 'orl_respiratory', 'subcategory' => 'SINUSITIS', 'question_fr' => 'Ta sinusite devient-elle chronique à chaque changement de saison ?', 'question_en' => 'Does your sinusitis become chronic with every change of season?'],

            // =============================================
            // SYSTÈME NERVEUX ET PSYCHIQUE (Medical)
            // =============================================
            // Migraine
            ['category' => 'nervous_psychic', 'subcategory' => 'MIGRAINE', 'question_fr' => 'Tes migraines sont-elles calmées par l\'obscurité et le silence absolu ?', 'question_en' => 'Are your migraines relieved by darkness and complete silence?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'MIGRAINE', 'question_fr' => 'La douleur est-elle précédée de troubles visuels (éclairs, taches) ?', 'question_en' => 'Is the pain preceded by visual disturbances (flashes, spots)?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'MIGRAINE', 'question_fr' => 'Ressens-tu des nausées ou des vomissements pendant la crise ?', 'question_en' => 'Do you experience nausea or vomiting during the episode?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'MIGRAINE', 'question_fr' => 'La douleur est-elle localisée sur une seule moitié du crâne ?', 'question_en' => 'Is the pain localized on one side of the head only?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'MIGRAINE', 'question_fr' => 'La migraine est-elle déclenchée par certains aliments (chocolat, fromage) ?', 'question_en' => 'Is the migraine triggered by certain foods (chocolate, cheese)?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'MIGRAINE', 'question_fr' => 'Est-ce que l\'effort physique aggrave immédiatement la douleur ?', 'question_en' => 'Does physical effort immediately worsen the pain?'],

            // Insomnies
            ['category' => 'nervous_psychic', 'subcategory' => 'INSOMNIA', 'question_fr' => 'Tes insomnies sont-elles dues à un flux de pensées que tu n\'arrives pas à stopper ?', 'question_en' => 'Are your insomnias due to a flow of thoughts you can\'t stop?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'INSOMNIA', 'question_fr' => 'Te réveilles-tu vers 3h ou 4h du matin sans pouvoir te rendormir ?', 'question_en' => 'Do you wake up around 3 or 4 am without being able to fall back asleep?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'INSOMNIA', 'question_fr' => 'Ressens-tu de la fatigue mais une incapacité physique à sombrer ?', 'question_en' => 'Do you feel tired but physically unable to fall asleep?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'INSOMNIA', 'question_fr' => 'Utilises-tu des écrans (téléphone, TV) juste avant de dormir ?', 'question_en' => 'Do you use screens (phone, TV) just before sleeping?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'INSOMNIA', 'question_fr' => 'Ton sommeil est-il agité (mouvements, rêves désagréables) ?', 'question_en' => 'Is your sleep restless (movements, unpleasant dreams)?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'INSOMNIA', 'question_fr' => 'Te sens-tu plus fatigué au réveil qu\'au moment du coucher ?', 'question_en' => 'Do you feel more tired when waking up than when going to bed?'],

            // Angoisse et Stress
            ['category' => 'nervous_psychic', 'subcategory' => 'ANXIETY', 'question_fr' => 'Ton angoisse se manifeste-t-elle par une oppression au niveau de la poitrine ?', 'question_en' => 'Does your anxiety manifest as tightness in the chest?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'ANXIETY', 'question_fr' => 'As-tu les mains moites ou des tremblements lors de pics de stress ?', 'question_en' => 'Do you have sweaty hands or trembling during stress peaks?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'ANXIETY', 'question_fr' => 'Ressens-tu une boule dans la gorge ou au creux de l\'estomac ?', 'question_en' => 'Do you feel a lump in your throat or in your stomach?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'ANXIETY', 'question_fr' => 'Ton rythme cardiaque s\'accélère-t-il sans raison apparente ?', 'question_en' => 'Does your heart rate accelerate for no apparent reason?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'ANXIETY', 'question_fr' => 'As-tu peur de perdre le contrôle ou de faire un malaise en public ?', 'question_en' => 'Are you afraid of losing control or fainting in public?'],
            ['category' => 'nervous_psychic', 'subcategory' => 'ANXIETY', 'question_fr' => 'Le stress impacte-t-il ta capacité à respirer profondément ?', 'question_en' => 'Does stress impact your ability to breathe deeply?'],

            // =============================================
            // SYSTÈME DIGESTIF ET TRANSIT (Medical)
            // =============================================
            // Ulcère
            ['category' => 'digestive', 'subcategory' => 'ULCER', 'question_fr' => 'Ton ulcère te fait-il plus mal quand tu as l\'estomac vide ou après manger ?', 'question_en' => 'Does your ulcer hurt more when your stomach is empty or after eating?'],
            ['category' => 'digestive', 'subcategory' => 'ULCER', 'question_fr' => 'La douleur ressemble-t-elle à une brûlure ou à une crampe forte ?', 'question_en' => 'Does the pain feel like burning or a strong cramp?'],
            ['category' => 'digestive', 'subcategory' => 'ULCER', 'question_fr' => 'Ressens-tu des aigreurs d\'estomac qui remontent dans l\'œsophage ?', 'question_en' => 'Do you feel stomach acid rising into the esophagus?'],
            ['category' => 'digestive', 'subcategory' => 'ULCER', 'question_fr' => 'La douleur te réveille-t-elle en pleine nuit ?', 'question_en' => 'Does the pain wake you up in the middle of the night?'],
            ['category' => 'digestive', 'subcategory' => 'ULCER', 'question_fr' => 'Es-tu souvent ballonné après avoir bu de l\'eau ?', 'question_en' => 'Are you often bloated after drinking water?'],
            ['category' => 'digestive', 'subcategory' => 'ULCER', 'question_fr' => 'As-tu remarqué une perte de poids inexpliquée récemment ?', 'question_en' => 'Have you noticed unexplained weight loss recently?'],

            // Constipation
            ['category' => 'digestive', 'subcategory' => 'CONSTIPATION', 'question_fr' => 'Ta constipation est-elle accompagnée de ballonnements très durs au toucher ?', 'question_en' => 'Is your constipation accompanied by very hard bloating to the touch?'],
            ['category' => 'digestive', 'subcategory' => 'CONSTIPATION', 'question_fr' => 'Vas-tu à la selle moins de trois fois par semaine ?', 'question_en' => 'Do you have bowel movements less than three times a week?'],
            ['category' => 'digestive', 'subcategory' => 'CONSTIPATION', 'question_fr' => 'Dois-tu faire des efforts de poussée excessifs ?', 'question_en' => 'Do you have to make excessive pushing efforts?'],
            ['category' => 'digestive', 'subcategory' => 'CONSTIPATION', 'question_fr' => 'Ressens-tu une sensation d\'évacuation incomplète ?', 'question_en' => 'Do you feel a sensation of incomplete evacuation?'],
            ['category' => 'digestive', 'subcategory' => 'CONSTIPATION', 'question_fr' => 'Ton transit est-il bloqué lors de tes déplacements ou voyages ?', 'question_en' => 'Is your transit blocked during your travels?'],
            ['category' => 'digestive', 'subcategory' => 'CONSTIPATION', 'question_fr' => 'Consommes-tu moins d\'un litre d\'eau par jour ?', 'question_en' => 'Do you consume less than one liter of water per day?'],

            // =============================================
            // BIEN-ÊTRE GLOBAL ET SANTÉ
            // =============================================
            ['category' => 'wellness', 'subcategory' => 'GENERAL', 'question_fr' => 'Te sens-tu en harmonie entre ton corps et tes aspirations spirituelles ?', 'question_en' => 'Do you feel in harmony between your body and your spiritual aspirations?'],
            ['category' => 'wellness', 'subcategory' => 'GENERAL', 'question_fr' => 'Ton immunité te semble-t-elle forte (résistance aux maladies saisonnières) ?', 'question_en' => 'Does your immunity seem strong (resistance to seasonal illnesses)?'],
            ['category' => 'wellness', 'subcategory' => 'GENERAL', 'question_fr' => 'Ta peau, tes cheveux et tes yeux te semblent-ils en bonne santé ?', 'question_en' => 'Do your skin, hair and eyes seem healthy?'],
            ['category' => 'wellness', 'subcategory' => 'GENERAL', 'question_fr' => 'Arrives-tu à maintenir un niveau d\'énergie stable du lever au coucher ?', 'question_en' => 'Can you maintain a stable energy level from wake to sleep?'],
            ['category' => 'wellness', 'subcategory' => 'GENERAL', 'question_fr' => 'Ressens-tu une sensation de légèreté et de fluidité dans tes mouvements ?', 'question_en' => 'Do you feel a sense of lightness and fluidity in your movements?'],
            ['category' => 'wellness', 'subcategory' => 'GENERAL', 'question_fr' => 'Ton appétit est-il régulé naturellement sans envies de grignotage compulsif ?', 'question_en' => 'Is your appetite naturally regulated without compulsive snacking urges?'],
        ];

        // Get category IDs
        $categoryIds = HealthAssessmentCategory::pluck('id', 'key')->toArray();

        $sortOrder = 1;
        foreach ($questions as $question) {
            HealthAssessmentQuestion::create([
                'category_id' => $categoryIds[$question['category']],
                'subcategory' => $question['subcategory'],
                'question_fr' => $question['question_fr'],
                'question_en' => $question['question_en'],
                'question_ar' => $question['question_ar'] ?? null,
                'answer_type' => $question['answer_type'] ?? 'yes_no',
                'sort_order' => $sortOrder++,
            ]);
        }

        $this->command->info('Created ' . count($questions) . ' health assessment questions across ' . count($categoryIds) . ' categories.');
    }
}
