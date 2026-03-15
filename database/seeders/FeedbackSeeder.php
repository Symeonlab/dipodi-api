<?php

namespace Database\Seeders;

use App\Models\FeedbackCategory;
use App\Models\FeedbackQuestion;
use Illuminate\Database\Seeder;

/**
 * Seeder for feedback questions based on DIPODDI PROGRAMME FEED BACK sheet.
 *
 * Categories:
 * - Football: Goalkeeper, Defender, Midfielder, Attacker, After Match, Weekly (No Club)
 * - Fitness: Women, Men, Weekly
 * - Nutrition: Weight Loss, Muscle Gain, Maintain, Prophetic
 * - Injury: Fitness, Football
 * - Cognitive
 */
class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategories();
        $this->seedQuestions();
    }

    private function seedCategories(): void
    {
        $categories = [
            // Football Position-Specific
            [
                'key' => 'football_goalkeeper',
                'name_fr' => 'Gardien',
                'name_en' => 'Goalkeeper',
                'name_ar' => 'حارس المرمى',
                'icon' => 'hand.raised.fill',
                'discipline' => 'football',
                'position' => 'goalkeeper',
                'sort_order' => 1,
            ],
            [
                'key' => 'football_defender',
                'name_fr' => 'Défenseur',
                'name_en' => 'Defender',
                'name_ar' => 'مدافع',
                'icon' => 'shield.fill',
                'discipline' => 'football',
                'position' => 'defender',
                'sort_order' => 2,
            ],
            [
                'key' => 'football_midfielder',
                'name_fr' => 'Milieu',
                'name_en' => 'Midfielder',
                'name_ar' => 'لاعب وسط',
                'icon' => 'arrow.left.arrow.right',
                'discipline' => 'football',
                'position' => 'midfielder',
                'sort_order' => 3,
            ],
            [
                'key' => 'football_attacker',
                'name_fr' => 'Attaquant',
                'name_en' => 'Attacker',
                'name_ar' => 'مهاجم',
                'icon' => 'sportscourt.fill',
                'discipline' => 'football',
                'position' => 'attacker',
                'sort_order' => 4,
            ],
            [
                'key' => 'football_after_match',
                'name_fr' => 'Après Match',
                'name_en' => 'After Match',
                'name_ar' => 'بعد المباراة',
                'icon' => 'flag.checkered',
                'discipline' => 'football',
                'sort_order' => 5,
            ],
            [
                'key' => 'football_weekly_no_club',
                'name_fr' => 'Hebdomadaire (Sans Club)',
                'name_en' => 'Weekly (No Club)',
                'name_ar' => 'أسبوعي (بدون نادي)',
                'icon' => 'calendar.badge.clock',
                'discipline' => 'football',
                'sort_order' => 6,
            ],

            // Fitness
            [
                'key' => 'fitness_women',
                'name_fr' => 'Fitness Femmes',
                'name_en' => 'Fitness Women',
                'name_ar' => 'لياقة نسائية',
                'icon' => 'figure.cooldown',
                'discipline' => 'fitness',
                'sort_order' => 10,
            ],
            [
                'key' => 'fitness_men',
                'name_fr' => 'Fitness Hommes',
                'name_en' => 'Fitness Men',
                'name_ar' => 'لياقة رجالية',
                'icon' => 'figure.strengthtraining.traditional',
                'discipline' => 'fitness',
                'sort_order' => 11,
            ],
            [
                'key' => 'fitness_weekly',
                'name_fr' => 'Fitness Hebdomadaire',
                'name_en' => 'Weekly Fitness',
                'name_ar' => 'لياقة أسبوعية',
                'icon' => 'chart.bar.fill',
                'discipline' => 'fitness',
                'sort_order' => 12,
            ],

            // Nutrition
            [
                'key' => 'nutrition_weight_loss',
                'name_fr' => 'Nutrition - Perte de Poids',
                'name_en' => 'Nutrition - Weight Loss',
                'name_ar' => 'تغذية - خسارة الوزن',
                'icon' => 'scalemass.fill',
                'goal' => 'weight_loss',
                'sort_order' => 20,
            ],
            [
                'key' => 'nutrition_muscle_gain',
                'name_fr' => 'Nutrition - Prise de Masse',
                'name_en' => 'Nutrition - Muscle Gain',
                'name_ar' => 'تغذية - بناء العضلات',
                'icon' => 'bolt.fill',
                'goal' => 'muscle_gain',
                'sort_order' => 21,
            ],
            [
                'key' => 'nutrition_maintain',
                'name_fr' => 'Nutrition - Maintien',
                'name_en' => 'Nutrition - Maintain',
                'name_ar' => 'تغذية - الحفاظ',
                'icon' => 'heart.fill',
                'goal' => 'maintain',
                'sort_order' => 22,
            ],
            [
                'key' => 'nutrition_prophetic',
                'name_fr' => 'Médecine Prophétique',
                'name_en' => 'Prophetic Nutrition',
                'name_ar' => 'الطب النبوي',
                'icon' => 'leaf.fill',
                'sort_order' => 23,
            ],

            // Injury Recovery
            [
                'key' => 'injury_fitness',
                'name_fr' => 'Récupération Blessure (Fitness)',
                'name_en' => 'Injury Recovery (Fitness)',
                'name_ar' => 'التعافي من الإصابة (لياقة)',
                'icon' => 'bandage.fill',
                'discipline' => 'fitness',
                'requires_injury' => true,
                'sort_order' => 30,
            ],
            [
                'key' => 'injury_football',
                'name_fr' => 'Récupération Blessure (Football)',
                'name_en' => 'Injury Recovery (Football)',
                'name_ar' => 'التعافي من الإصابة (كرة قدم)',
                'icon' => 'bandage.fill',
                'discipline' => 'football',
                'requires_injury' => true,
                'sort_order' => 31,
            ],

            // Post-Workout Feedback (auto-triggered after workout completion)
            [
                'key' => 'post_workout',
                'name_fr' => 'Retour Post-Entraînement',
                'name_en' => 'Post-Workout Feedback',
                'name_ar' => 'تقييم ما بعد التمرين',
                'icon' => 'star.fill',
                'sort_order' => 0,
            ],

            // Cognitive
            [
                'key' => 'cognitive',
                'name_fr' => 'Mental & Cognitif',
                'name_en' => 'Mental & Cognitive',
                'name_ar' => 'الذهني والمعرفي',
                'icon' => 'brain.head.profile',
                'sort_order' => 40,
            ],
        ];

        foreach ($categories as $category) {
            FeedbackCategory::updateOrCreate(
                ['key' => $category['key']],
                $category
            );
        }

        $this->command->info('Seeded ' . count($categories) . ' feedback categories.');
    }

    private function seedQuestions(): void
    {
        $questions = [
            // ==========================================
            // FOOTBALL GOALKEEPER QUESTIONS
            // ==========================================
            'football_goalkeeper' => [
                ['question_fr' => 'Ta poussée a-t-elle permis d\'atteindre la lucarne ?', 'question_en' => 'Did your push allow you to reach the top corner?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta jambe d\'appui a-t-elle tremblé à l\'impact ?', 'question_en' => 'Did your support leg shake on impact?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'As-tu ressenti une lourdeur au 2ème saut ?', 'question_en' => 'Did you feel heaviness on the 2nd jump?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta capacité à "voler" est-elle intacte ?', 'question_en' => 'Is your ability to "fly" intact?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ton temps de suspension était-il suffisant ?', 'question_en' => 'Was your suspension time sufficient?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ton plongeon était-il fluide ?', 'question_en' => 'Was your dive fluid?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta réception après plongeon était-elle souple ?', 'question_en' => 'Was your reception after diving soft?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu eu peur de te blesser lors d\'un plongeon ?', 'question_en' => 'Were you afraid of getting injured during a dive?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta capacité de replacement était-elle rapide ?', 'question_en' => 'Was your repositioning ability quick?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ton jeu au pied était-il précis ?', 'question_en' => 'Was your footwork precise?', 'answer_type' => 'scale'],
            ],

            // ==========================================
            // FOOTBALL DEFENDER QUESTIONS
            // ==========================================
            'football_defender' => [
                ['question_fr' => 'Ta gestion de la distance avec l\'attaquant ?', 'question_en' => 'Your distance management with the attacker?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta coordination avec ton gardien de but ?', 'question_en' => 'Your coordination with your goalkeeper?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta capacité à "serrer" l\'attaquant au contact ?', 'question_en' => 'Your ability to "press" the attacker in contact?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta lecture du jeu était-elle anticipée ?', 'question_en' => 'Was your game reading anticipated?', 'answer_type' => 'scale'],
                ['question_fr' => 'Tes duels aériens étaient-ils gagnés ?', 'question_en' => 'Were your aerial duels won?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta relance était-elle propre ?', 'question_en' => 'Was your distribution clean?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta couverture de tes coéquipiers était-elle efficace ?', 'question_en' => 'Was your covering of teammates effective?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta communication avec ta ligne défensive ?', 'question_en' => 'Your communication with your defensive line?', 'answer_type' => 'scale'],
            ],

            // ==========================================
            // FOOTBALL MIDFIELDER QUESTIONS
            // ==========================================
            'football_midfielder' => [
                ['question_fr' => 'Ta capacité à conserver le ballon sous pression ?', 'question_en' => 'Your ability to keep the ball under pressure?', 'answer_type' => 'scale'],
                ['question_fr' => 'Tes passes vers l\'avant étaient-elles précises ?', 'question_en' => 'Were your forward passes accurate?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta vision du jeu était-elle claire ?', 'question_en' => 'Was your vision of the game clear?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ton pressing était-il efficace ?', 'question_en' => 'Was your pressing effective?', 'answer_type' => 'scale'],
                ['question_fr' => 'Tes courses de démarquage étaient-elles bien timées ?', 'question_en' => 'Were your runs well-timed?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta capacité à casser les lignes adverses ?', 'question_en' => 'Your ability to break opposition lines?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ton équilibre entre attaque et défense ?', 'question_en' => 'Your balance between attack and defense?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta gestion du tempo du match ?', 'question_en' => 'Your management of the match tempo?', 'answer_type' => 'scale'],
            ],

            // ==========================================
            // FOOTBALL ATTACKER QUESTIONS
            // ==========================================
            'football_attacker' => [
                ['question_fr' => 'Ta finition devant le but était-elle clinique ?', 'question_en' => 'Was your finishing clinical?', 'answer_type' => 'scale'],
                ['question_fr' => 'Tes appels de balle étaient-ils efficaces ?', 'question_en' => 'Were your runs effective?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta capacité à éliminer ton adversaire direct ?', 'question_en' => 'Your ability to beat your direct opponent?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ton jeu dos au but était-il maîtrisé ?', 'question_en' => 'Was your back-to-goal play controlled?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta prise de décision dans le dernier tiers ?', 'question_en' => 'Your decision-making in the final third?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta capacité à créer des occasions ?', 'question_en' => 'Your ability to create chances?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ton pressing sur les défenseurs adverses ?', 'question_en' => 'Your pressing on opposing defenders?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta coordination avec tes partenaires offensifs ?', 'question_en' => 'Your coordination with offensive partners?', 'answer_type' => 'scale'],
            ],

            // ==========================================
            // FOOTBALL AFTER MATCH QUESTIONS
            // ==========================================
            'football_after_match' => [
                ['question_fr' => 'As-tu eu peur de te blesser ?', 'question_en' => 'Were you afraid of getting injured?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ton niveau d\'énergie pour la suite de la journée est-il correct ?', 'question_en' => 'Is your energy level for the rest of the day okay?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu fini la séance sur une note positive ?', 'question_en' => 'Did you finish the session on a positive note?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta concentration était-elle maintenue jusqu\'à la fin ?', 'question_en' => 'Was your concentration maintained until the end?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu ressenti de la fatigue excessive ?', 'question_en' => 'Did you feel excessive fatigue?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta récupération post-match est-elle initiée ?', 'question_en' => 'Is your post-match recovery initiated?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ton hydratation pendant le match était-elle suffisante ?', 'question_en' => 'Was your hydration during the match sufficient?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta satisfaction générale par rapport à ta performance ?', 'question_en' => 'Your overall satisfaction with your performance?', 'answer_type' => 'scale'],
            ],

            // ==========================================
            // FITNESS WEEKLY QUESTIONS
            // ==========================================
            'fitness_weekly' => [
                ['question_fr' => 'Ta régularité d\'entraînement cette semaine ?', 'question_en' => 'Your training regularity this week?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta progression par rapport à la semaine dernière ?', 'question_en' => 'Your progress compared to last week?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta motivation est-elle restée constante ?', 'question_en' => 'Has your motivation remained constant?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu ressenti des douleurs musculaires excessives ?', 'question_en' => 'Did you feel excessive muscle soreness?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ton sommeil était-il récupérateur ?', 'question_en' => 'Was your sleep restorative?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ton alimentation était-elle en accord avec tes objectifs ?', 'question_en' => 'Was your nutrition aligned with your goals?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta gestion du stress cette semaine ?', 'question_en' => 'Your stress management this week?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu atteint tes objectifs hebdomadaires ?', 'question_en' => 'Did you achieve your weekly goals?', 'answer_type' => 'yes_no'],
            ],

            // ==========================================
            // NUTRITION WEIGHT LOSS QUESTIONS
            // ==========================================
            'nutrition_weight_loss' => [
                ['question_fr' => 'Ton envie de grignoter était-elle liée à une fatigue physique ?', 'question_en' => 'Was your urge to snack related to physical fatigue?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'As-tu réussi à ne pas grignoter pendant que tu cuisinais ?', 'question_en' => 'Did you manage not to snack while cooking?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta résistance aux commentaires des autres sur ta consommation ?', 'question_en' => 'Your resistance to others\' comments about your consumption?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu bu assez d\'eau aujourd\'hui ?', 'question_en' => 'Did you drink enough water today?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta sensation de satiété après les repas ?', 'question_en' => 'Your feeling of fullness after meals?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu respecté tes heures de repas ?', 'question_en' => 'Did you respect your meal times?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ton contrôle des portions était-il correct ?', 'question_en' => 'Was your portion control correct?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu évité les aliments transformés ?', 'question_en' => 'Did you avoid processed foods?', 'answer_type' => 'yes_no'],
            ],

            // ==========================================
            // NUTRITION MUSCLE GAIN QUESTIONS
            // ==========================================
            'nutrition_muscle_gain' => [
                ['question_fr' => 'Ton apport en protéines était-il suffisant ?', 'question_en' => 'Was your protein intake sufficient?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu mangé dans les 30 minutes après l\'entraînement ?', 'question_en' => 'Did you eat within 30 minutes after training?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Tes repas étaient-ils bien répartis dans la journée ?', 'question_en' => 'Were your meals well distributed throughout the day?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ton apport calorique était-il en surplus ?', 'question_en' => 'Was your caloric intake in surplus?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'As-tu consommé des glucides complexes ?', 'question_en' => 'Did you consume complex carbohydrates?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta récupération musculaire est-elle satisfaisante ?', 'question_en' => 'Is your muscle recovery satisfactory?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu ressenti une bonne congestion musculaire ?', 'question_en' => 'Did you feel good muscle pump?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ton hydratation était-elle optimale ?', 'question_en' => 'Was your hydration optimal?', 'answer_type' => 'scale'],
            ],

            // ==========================================
            // NUTRITION PROPHETIC QUESTIONS
            // ==========================================
            'nutrition_prophetic' => [
                ['question_fr' => 'As-tu consommé des dattes aujourd\'hui ?', 'question_en' => 'Did you consume dates today?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'As-tu utilisé du miel comme édulcorant ?', 'question_en' => 'Did you use honey as a sweetener?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'As-tu inclus de l\'huile d\'olive dans ton alimentation ?', 'question_en' => 'Did you include olive oil in your diet?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'As-tu consommé de la nigelle (habba sawda) ?', 'question_en' => 'Did you consume black seed (nigella)?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'As-tu mangé avec modération ?', 'question_en' => 'Did you eat in moderation?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'As-tu laissé un tiers de ton estomac vide ?', 'question_en' => 'Did you leave a third of your stomach empty?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'As-tu bu de l\'eau assis ?', 'question_en' => 'Did you drink water while sitting?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'As-tu rompu le jeûne avec des dattes (si applicable) ?', 'question_en' => 'Did you break fast with dates (if applicable)?', 'answer_type' => 'yes_no'],
            ],

            // ==========================================
            // COGNITIVE QUESTIONS
            // ==========================================
            'cognitive' => [
                ['question_fr' => 'Ressens-tu une clarté soudaine sur une décision difficile ?', 'question_en' => 'Do you feel sudden clarity on a difficult decision?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ton feedback technique personnel est-il encourageant ?', 'question_en' => 'Is your personal technical feedback encouraging?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta résistance mentale face à un exercice difficile ?', 'question_en' => 'Your mental resistance when facing a difficult exercise?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ta capacité à rester concentré pendant l\'entraînement ?', 'question_en' => 'Your ability to stay focused during training?', 'answer_type' => 'scale'],
                ['question_fr' => 'Ton niveau de stress est-il gérable ?', 'question_en' => 'Is your stress level manageable?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu pratiqué la visualisation positive ?', 'question_en' => 'Did you practice positive visualization?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta confiance en toi est-elle renforcée ?', 'question_en' => 'Is your self-confidence strengthened?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu su gérer les pensées négatives ?', 'question_en' => 'Did you manage negative thoughts?', 'answer_type' => 'yes_no'],
            ],

            // ==========================================
            // POST-WORKOUT FEEDBACK QUESTIONS
            // ==========================================
            'post_workout' => [
                [
                    'question_fr' => 'Comment évalues-tu la difficulté de cet entraînement ?',
                    'question_en' => 'How would you rate the difficulty of this workout?',
                    'question_ar' => 'كيف تقيّم صعوبة هذا التمرين؟',
                    'answer_type' => 'scale',
                ],
                [
                    'question_fr' => 'Quel est ton niveau d\'énergie après la séance ?',
                    'question_en' => 'What is your energy level after the session?',
                    'question_ar' => 'ما مستوى طاقتك بعد الجلسة؟',
                    'answer_type' => 'scale',
                ],
                [
                    'question_fr' => 'As-tu apprécié cet entraînement ?',
                    'question_en' => 'Did you enjoy this workout?',
                    'question_ar' => 'هل استمتعت بهذا التمرين؟',
                    'answer_type' => 'scale',
                ],
                [
                    'question_fr' => 'As-tu complété toutes les séries ?',
                    'question_en' => 'Did you complete all sets?',
                    'question_ar' => 'هل أكملت جميع المجموعات؟',
                    'answer_type' => 'yes_no',
                ],
                [
                    'question_fr' => 'Ressens-tu des douleurs musculaires ?',
                    'question_en' => 'Do you feel muscle soreness?',
                    'question_ar' => 'هل تشعر بألم في العضلات؟',
                    'answer_type' => 'scale',
                ],
                [
                    'question_fr' => 'Les exercices étaient-ils adaptés à ton niveau ?',
                    'question_en' => 'Were the exercises suited to your level?',
                    'question_ar' => 'هل كانت التمارين مناسبة لمستواك؟',
                    'answer_type' => 'yes_no',
                ],
                [
                    'question_fr' => 'Ta concentration était-elle maintenue tout au long de la séance ?',
                    'question_en' => 'Was your focus maintained throughout the session?',
                    'question_ar' => 'هل حافظت على تركيزك طوال الجلسة؟',
                    'answer_type' => 'scale',
                ],
                [
                    'question_fr' => 'Souhaites-tu un ajustement pour la prochaine séance ?',
                    'question_en' => 'Would you like an adjustment for the next session?',
                    'question_ar' => 'هل تريد تعديلاً للجلسة القادمة؟',
                    'answer_type' => 'multi',
                    'answer_options' => [
                        'increase_intensity' => 'Augmenter l\'intensité / Increase intensity',
                        'decrease_intensity' => 'Diminuer l\'intensité / Decrease intensity',
                        'more_rest' => 'Plus de repos / More rest',
                        'fewer_exercises' => 'Moins d\'exercices / Fewer exercises',
                        'more_variety' => 'Plus de variété / More variety',
                        'keep_same' => 'Garder la même chose / Keep same',
                    ],
                ],
                [
                    'question_fr' => 'As-tu des commentaires supplémentaires ?',
                    'question_en' => 'Do you have any additional comments?',
                    'question_ar' => 'هل لديك تعليقات إضافية؟',
                    'answer_type' => 'text',
                ],
            ],

            // ==========================================
            // INJURY RECOVERY QUESTIONS (FOOTBALL)
            // ==========================================
            'injury_football' => [
                ['question_fr' => 'Ta douleur a-t-elle diminué par rapport à hier ?', 'question_en' => 'Has your pain decreased compared to yesterday?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ton amplitude de mouvement s\'améliore-t-elle ?', 'question_en' => 'Is your range of motion improving?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu suivi ton protocole de rééducation ?', 'question_en' => 'Did you follow your rehabilitation protocol?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta confiance dans la zone blessée ?', 'question_en' => 'Your confidence in the injured area?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu ressenti un gonflement aujourd\'hui ?', 'question_en' => 'Did you feel swelling today?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ton sommeil est-il perturbé par la douleur ?', 'question_en' => 'Is your sleep disturbed by pain?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta patience face à la récupération ?', 'question_en' => 'Your patience with the recovery?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu communiqué avec ton kiné/médecin ?', 'question_en' => 'Did you communicate with your physio/doctor?', 'answer_type' => 'yes_no'],
            ],

            // ==========================================
            // INJURY RECOVERY QUESTIONS (FITNESS)
            // ==========================================
            'injury_fitness' => [
                ['question_fr' => 'Ta douleur a-t-elle diminué par rapport à hier ?', 'question_en' => 'Has your pain decreased compared to yesterday?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'As-tu adapté tes exercices à ta blessure ?', 'question_en' => 'Did you adapt your exercises to your injury?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ton amplitude de mouvement s\'améliore-t-elle ?', 'question_en' => 'Is your range of motion improving?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu évité les mouvements aggravants ?', 'question_en' => 'Did you avoid aggravating movements?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta récupération active était-elle suffisante ?', 'question_en' => 'Was your active recovery sufficient?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu appliqué de la glace/chaleur selon les besoins ?', 'question_en' => 'Did you apply ice/heat as needed?', 'answer_type' => 'yes_no'],
                ['question_fr' => 'Ta frustration face aux limitations est-elle gérée ?', 'question_en' => 'Is your frustration with limitations managed?', 'answer_type' => 'scale'],
                ['question_fr' => 'As-tu respecté les temps de repos prescrits ?', 'question_en' => 'Did you respect the prescribed rest times?', 'answer_type' => 'yes_no'],
            ],
        ];

        $totalCount = 0;

        foreach ($questions as $categoryKey => $categoryQuestions) {
            $category = FeedbackCategory::where('key', $categoryKey)->first();

            if (!$category) {
                $this->command->warn("Category not found: {$categoryKey}");
                continue;
            }

            $sortOrder = 1;
            foreach ($categoryQuestions as $questionData) {
                FeedbackQuestion::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'question_fr' => $questionData['question_fr'],
                    ],
                    [
                        'question_en' => $questionData['question_en'] ?? null,
                        'question_ar' => $questionData['question_ar'] ?? null,
                        'answer_type' => $questionData['answer_type'],
                        'answer_options' => $questionData['answer_options'] ?? null,
                        'sort_order' => $sortOrder++,
                        'is_active' => true,
                    ]
                );
                $totalCount++;
            }
        }

        $this->command->info("Seeded {$totalCount} feedback questions.");
    }
}
