<?php

return [
    // Navigation
    'nav' => [
        'admin_guide' => 'Guide Admin',
        'help_documentation' => 'Aide & Documentation',
    ],

    // Stats
    'stats' => [
        'exercises' => 'Exercices',
        'nutrition_advice' => 'Conseils Nutrition',
        'player_profiles' => 'Profils Joueurs',
        'workout_themes' => 'Thèmes Entraînement',
    ],

    // Categories
    'categories' => [
        'musculation' => 'Musculation',
        'cardio' => 'Cardio',
        'kine' => 'Kiné',
        'profiles' => 'Profils',
        'themes' => 'Thèmes',
        'nutrition' => 'Nutrition',
        'advice' => 'Conseils',
    ],

    // Guide Page
    'guide' => [
        'title' => 'Guide Admin DIPODI',
        'welcome' => 'Bienvenue dans le Guide Admin DIPODI',
        'description' => 'Instructions étape par étape pour gérer les exercices, conseils nutritionnels, profils joueurs et plus encore.',
        'steps' => 'Étapes',
        'tips' => 'Conseils',
        'quick_links' => 'Liens Rapides',
        'workflow_title' => 'Flux de Travail du Contenu',

        // Quick Links
        'add_exercise' => 'Ajouter Exercice',
        'add_nutrition' => 'Ajouter Conseil Nutrition',
        'add_profile' => 'Ajouter Profil Joueur',
        'view_exercises' => 'Voir Tous les Exercices',

        // Workflow Steps
        'workflow_step1_title' => 'Ajouter Exercices',
        'workflow_step1_desc' => 'Créez des exercices avec vidéos, catégories et valeurs MET pour la génération d\'entraînement.',
        'workflow_step2_title' => 'Configurer Profils',
        'workflow_step2_desc' => 'Configurez les profils joueurs et liez-les aux thèmes d\'entraînement avec des pourcentages.',
        'workflow_step3_title' => 'Ajouter Nutrition',
        'workflow_step3_desc' => 'Créez des conseils nutritionnels pour les conditions de santé avec support multilingue.',

        // Category Reference
        'categories_reference' => 'Référence des Catégories',
        'exercise_categories' => 'Catégories d\'Exercices',
        'player_groups' => 'Groupes de Joueurs',
        'fitness_groups' => 'Groupes Fitness',

        // Exercise Categories
        'cat_musculation' => 'Exercices de salle (Bras, Dos, Épaules)',
        'cat_bonus' => 'Entraînements rapides (Abdos, Pompes, Gainage)',
        'cat_maison' => 'Entraînements à domicile',
        'cat_kine' => 'Exercices de rééducation',
        'cat_cardio' => 'Exercices cardiovasculaires',

        // Player Groups
        'group_gardien' => 'Gardien de but',
        'group_defenseur' => 'Défenseur',
        'group_milieu' => 'Milieu de terrain',
        'group_attaquant' => 'Attaquant',
        'group_fitness_femme' => 'Fitness Femme',
        'group_fitness_homme' => 'Fitness Homme',
        'group_padel' => 'Joueur de Padel',
    ],

    // Guide Sections
    'sections' => [
        // Exercises Section
        'exercises' => [
            'title' => 'Ajouter de Nouveaux Exercices',
            'step1' => 'Naviguez vers **Gestion du Contenu > Exercices** dans la barre latérale',
            'step2' => 'Cliquez sur le bouton **"Créer"** en haut à droite',
            'step3' => 'Remplissez les détails de l\'exercice :',
            'step4' => '  - **Nom** : Donnez un nom descriptif (ex. "Curl biceps", "Pompes diamant")',
            'step5' => '  - **Catégorie** : Sélectionnez parmi MUSCULATION, BONUS, MAISON, KINE RENFORCEMENT, KINE MOBILITÉ ou CARDIO',
            'step6' => '  - **Sous-Catégorie** : Sélectionnez la partie du corps ou le type d\'exercice approprié',
            'step7' => '  - **URL Vidéo** : Collez l\'URL YouTube Shorts (format : https://youtube.com/shorts/xxxxx)',
            'step8' => '  - **Valeur MET** : Entrez l\'équivalent métabolique (plage typique 3-12)',
            'step9' => 'Cliquez sur **"Créer"** pour sauvegarder l\'exercice',
            'tip1' => 'Utilisez les URLs YouTube Shorts pour une meilleure expérience mobile',
            'tip2' => 'Valeurs MET : 3-4 (léger), 5-7 (modéré), 8+ (intense)',
            'tip3' => 'Vous pouvez mettre à jour en masse les catégories en utilisant la sélection par cases à cocher',
        ],

        // Nutrition Section
        'nutrition' => [
            'title' => 'Ajouter des Conseils Nutritionnels',
            'step1' => 'Naviguez vers **Nutrition & Santé > Conseils Nutrition** dans la barre latérale',
            'step2' => 'Cliquez sur **"Créer"** pour ajouter un nouveau conseil',
            'step3' => 'Entrez les détails de la condition :',
            'step4' => '  - **Nom de la Condition** : La condition de santé (ex. "Diabète", "Fatigue musculaire")',
            'step5' => '  - **Aliments à Éviter** : Ajoutez chaque aliment et appuyez sur Entrée',
            'step6' => '  - **Aliments à Consommer** : Ajoutez les aliments recommandés de la même manière',
            'step7' => 'Ajoutez les **Conseils de Médecine Prophétique** dans les trois langues :',
            'step8' => '  - **Français (FR)** : Texte principal du conseil',
            'step9' => '  - **Anglais (EN)** : Traduction anglaise',
            'step10' => '  - **Arabe (AR)** : Traduction arabe (support droite-à-gauche)',
            'tip1' => 'Les trois traductions linguistiques doivent être fournies pour le support multilingue',
            'tip2' => 'Utilisez le champ TagsInput - tapez simplement et appuyez sur Entrée pour chaque aliment',
            'tip3' => 'Exportez les entrées existantes pour les utiliser comme modèles pour les nouvelles traductions',
        ],

        // Profiles Section
        'profiles' => [
            'title' => 'Gestion des Profils Joueurs',
            'step1' => 'Allez dans **Logique Entraînement > Profils Joueurs**',
            'step2' => 'Cliquez sur **"Créer"** pour un nouveau profil',
            'step3' => 'Remplissez les informations du profil :',
            'step4' => '  - **Nom du Profil** : L\'identifiant du profil (ex. "La Panthère", "Le Magicien")',
            'step5' => '  - **Groupe de Profil** : Sélectionnez la position du joueur ou le type de fitness',
            'step6' => '  - **Description** : Ajoutez une description détaillée de ce type de profil',
            'step7' => 'Liez optionnellement les **Thèmes d\'Entraînement** avec des poids en pourcentage',
            'step8' => 'Sauvegardez le profil',
            'tip1' => 'Groupes de profil : GARDIEN, DÉFENSEUR, MILIEU, ATTAQUANT pour le football',
            'tip2' => 'FITNESS_FEMME et FITNESS_HOMME pour les profils axés fitness',
            'tip3' => 'PADEL pour les profils spécifiques au padel',
        ],

        // Import Section
        'import' => [
            'title' => 'Import en Masse via Seeder',
            'step1' => 'Préparez vos données dans le fichier **DipoddiProgrammeSeeder.php**',
            'step2' => 'Ajoutez les exercices au tableau `$exercises` en suivant le format existant :',
            'step3' => 'Exécutez le seeder via le terminal :',
            'step4' => 'Vérifiez les données dans le panneau d\'administration',
            'tip1' => 'Le seeder efface les données existantes avant l\'importation - utilisez avec prudence',
            'tip2' => 'Sauvegardez toujours votre base de données avant d\'exécuter le seeder',
            'tip3' => 'Pour ajouter aux données existantes, utilisez plutôt l\'interface d\'administration',
        ],

        // Themes Section
        'themes' => [
            'title' => 'Gestion des Thèmes d\'Entraînement',
            'step1' => 'Naviguez vers **Logique Entraînement > Règles des Thèmes d\'Entraînement**',
            'step2' => 'Consultez les thèmes existants et leurs configurations d\'exercices',
            'step3' => 'Pour modifier un thème, cliquez sur **Modifier**',
            'step4' => 'Ajustez les paramètres :',
            'step5' => '  - Nombre d\'exercices par catégorie',
            'step6' => '  - Paramètres de durée et d\'intensité',
            'step7' => '  - Groupes musculaires ciblés',
            'step8' => 'Sauvegardez vos modifications',
            'tip1' => 'Les thèmes sont liés aux Profils Joueurs via des poids en pourcentage',
            'tip2' => 'Assurez-vous que les thèmes ont des nombres d\'exercices appropriés pour toutes les catégories utilisées',
        ],

        // Reference Section
        'reference' => [
            'title' => 'Référence des Catégories de Données',
            'exercise_categories' => '**Catégories d\'Exercices :**',
            'cat_musculation' => '  - **MUSCULATION** : Exercices de salle (Bras, Dos, Épaules, Pectoraux, Quadriceps)',
            'cat_bonus' => '  - **BONUS** : Entraînements rapides (Abdos, Pompes, Gainage)',
            'cat_maison' => '  - **MAISON** : Entraînements à domicile (Perte de Poids, Renforcement)',
            'cat_kine_renforcement' => '  - **KINE RENFORCEMENT** : Renforcement en rééducation',
            'cat_kine_mobilite' => '  - **KINE MOBILITÉ** : Exercices de mobilité',
            'cat_cardio' => '  - **CARDIO** : Exercices cardiovasculaires',
            'player_groups' => '**Groupes de Joueurs :**',
            'group_football' => '  - Football : GARDIEN, DÉFENSEUR, MILIEU, ATTAQUANT',
            'group_fitness' => '  - Fitness : FITNESS_FEMME, FITNESS_HOMME',
            'group_other' => '  - Autre : PADEL',
            'tip1' => 'Chaque catégorie a des sous-catégories spécifiques pour une meilleure organisation',
            'tip2' => 'Utilisez des conventions de nommage cohérentes lors de l\'ajout de nouvelles données',
        ],
    ],
];
