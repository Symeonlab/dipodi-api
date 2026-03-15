<?php

return [
    // Navigation
    'nav' => [
        'admin_guide' => 'Admin Guide',
        'help_documentation' => 'Help & Documentation',
    ],

    // Stats
    'stats' => [
        'exercises' => 'Exercises',
        'nutrition_advice' => 'Nutrition Advice',
        'player_profiles' => 'Player Profiles',
        'workout_themes' => 'Workout Themes',
    ],

    // Categories
    'categories' => [
        'musculation' => 'Musculation',
        'cardio' => 'Cardio',
        'kine' => 'Kine',
        'profiles' => 'Profiles',
        'themes' => 'Themes',
        'nutrition' => 'Nutrition',
        'advice' => 'Advice',
    ],

    // Guide Page
    'guide' => [
        'title' => 'DIPODI Admin Guide',
        'welcome' => 'Welcome to the DIPODI Admin Guide',
        'description' => 'Step-by-step instructions for managing exercises, nutrition advice, player profiles, and more.',
        'steps' => 'Steps',
        'tips' => 'Tips',
        'quick_links' => 'Quick Links',
        'workflow_title' => 'Content Workflow',

        // Quick Links
        'add_exercise' => 'Add Exercise',
        'add_nutrition' => 'Add Nutrition Advice',
        'add_profile' => 'Add Player Profile',
        'view_exercises' => 'View All Exercises',

        // Workflow Steps
        'workflow_step1_title' => 'Add Exercises',
        'workflow_step1_desc' => 'Create exercises with videos, categories, and MET values for workout generation.',
        'workflow_step2_title' => 'Configure Profiles',
        'workflow_step2_desc' => 'Set up player profiles and link them to workout themes with percentage weights.',
        'workflow_step3_title' => 'Add Nutrition',
        'workflow_step3_desc' => 'Create nutrition advice for health conditions with multilingual support.',

        // Category Reference
        'categories_reference' => 'Category Reference',
        'exercise_categories' => 'Exercise Categories',
        'player_groups' => 'Player Groups',
        'fitness_groups' => 'Fitness Groups',

        // Exercise Categories
        'cat_musculation' => 'Gym exercises (Arms, Back, Shoulders)',
        'cat_bonus' => 'Quick workouts (Abs, Push-ups, Planks)',
        'cat_maison' => 'Home workouts',
        'cat_kine' => 'Rehabilitation exercises',
        'cat_cardio' => 'Cardiovascular exercises',

        // Player Groups
        'group_gardien' => 'Goalkeeper',
        'group_defenseur' => 'Defender',
        'group_milieu' => 'Midfielder',
        'group_attaquant' => 'Forward',
        'group_fitness_femme' => 'Women\'s Fitness',
        'group_fitness_homme' => 'Men\'s Fitness',
        'group_padel' => 'Padel Player',
    ],

    // Guide Sections
    'sections' => [
        // Exercises Section
        'exercises' => [
            'title' => 'Adding New Exercises',
            'step1' => 'Navigate to **Content Management > Exercises** in the sidebar',
            'step2' => 'Click the **"Create"** button in the top right corner',
            'step3' => 'Fill in the exercise details:',
            'step4' => '  - **Name**: Give a descriptive name (e.g., "Bicep Curl", "Diamond Push-ups")',
            'step5' => '  - **Category**: Select from MUSCULATION, BONUS, MAISON, KINE RENFORCEMENT, KINE MOBILITÉ, or CARDIO',
            'step6' => '  - **Sub-Category**: Select the appropriate body part or exercise type',
            'step7' => '  - **Video URL**: Paste the YouTube Shorts URL (format: https://youtube.com/shorts/xxxxx)',
            'step8' => '  - **MET Value**: Enter the metabolic equivalent (3-12 typical range)',
            'step9' => 'Click **"Create"** to save the exercise',
            'tip1' => 'Use YouTube Shorts URLs for best mobile experience',
            'tip2' => 'MET values: 3-4 (light), 5-7 (moderate), 8+ (intense)',
            'tip3' => 'You can bulk update categories using the checkbox selection',
        ],

        // Nutrition Section
        'nutrition' => [
            'title' => 'Adding Nutrition Advice',
            'step1' => 'Navigate to **Nutrition & Health > Nutrition Advice** in the sidebar',
            'step2' => 'Click **"Create"** to add new advice',
            'step3' => 'Enter the condition details:',
            'step4' => '  - **Condition Name**: The health condition (e.g., "Diabetes", "Muscle Fatigue")',
            'step5' => '  - **Foods to Avoid**: Add each food item and press Enter',
            'step6' => '  - **Foods to Eat**: Add recommended foods the same way',
            'step7' => 'Add **Prophetic Medicine Advice** in all three languages:',
            'step8' => '  - **French (FR)**: Primary advice text',
            'step9' => '  - **English (EN)**: English translation',
            'step10' => '  - **Arabic (AR)**: Arabic translation (right-to-left supported)',
            'tip1' => 'All three language translations should be provided for multilingual support',
            'tip2' => 'Use the TagsInput field - just type and press Enter for each food item',
            'tip3' => 'Export existing entries to use as templates for new translations',
        ],

        // Profiles Section
        'profiles' => [
            'title' => 'Managing Player Profiles',
            'step1' => 'Go to **Workout Logic > Player Profiles**',
            'step2' => 'Click **"Create"** for a new profile',
            'step3' => 'Fill in the profile information:',
            'step4' => '  - **Profile Name**: The profile identifier (e.g., "The Panther", "The Magician")',
            'step5' => '  - **Profile Group**: Select the player position or fitness type',
            'step6' => '  - **Description**: Add a detailed description of this profile type',
            'step7' => 'Optionally link **Workout Themes** with percentage weights',
            'step8' => 'Save the profile',
            'tip1' => 'Profile groups: GARDIEN, DÉFENSEUR, MILIEU, ATTAQUANT for football',
            'tip2' => 'FITNESS_FEMME and FITNESS_HOMME for fitness-focused profiles',
            'tip3' => 'PADEL for padel-specific profiles',
        ],

        // Import Section
        'import' => [
            'title' => 'Bulk Import via Seeder',
            'step1' => 'Prepare your data in the **DipoddiProgrammeSeeder.php** file',
            'step2' => 'Add exercises to the `$exercises` array following the existing format:',
            'step3' => 'Run the seeder via terminal:',
            'step4' => 'Verify the data in the admin panel',
            'tip1' => 'The seeder clears existing data before importing - use with caution',
            'tip2' => 'Always backup your database before running the seeder',
            'tip3' => 'For adding to existing data, use the admin interface instead',
        ],

        // Themes Section
        'themes' => [
            'title' => 'Managing Workout Themes',
            'step1' => 'Navigate to **Workout Logic > Workout Theme Rules**',
            'step2' => 'View existing themes and their exercise configurations',
            'step3' => 'To modify a theme, click **Edit**',
            'step4' => 'Adjust the parameters:',
            'step5' => '  - Exercise counts per category',
            'step6' => '  - Duration and intensity settings',
            'step7' => '  - Target muscle groups',
            'step8' => 'Save your changes',
            'tip1' => 'Themes are linked to Player Profiles via percentage weights',
            'tip2' => 'Ensure themes have appropriate exercise counts for all categories used',
        ],

        // Reference Section
        'reference' => [
            'title' => 'Data Categories Reference',
            'exercise_categories' => '**Exercise Categories:**',
            'cat_musculation' => '  - **MUSCULATION**: Gym exercises (Arms, Back, Shoulders, Chest, Legs)',
            'cat_bonus' => '  - **BONUS**: Quick workouts (Abs, Push-ups, Planks)',
            'cat_maison' => '  - **MAISON**: Home workouts (Weight Loss, Strengthening)',
            'cat_kine_renforcement' => '  - **KINE RENFORCEMENT**: Rehabilitation strengthening',
            'cat_kine_mobilite' => '  - **KINE MOBILITÉ**: Mobility exercises',
            'cat_cardio' => '  - **CARDIO**: Cardiovascular exercises',
            'player_groups' => '**Player Groups:**',
            'group_football' => '  - Football: GARDIEN, DÉFENSEUR, MILIEU, ATTAQUANT',
            'group_fitness' => '  - Fitness: FITNESS_FEMME, FITNESS_HOMME',
            'group_other' => '  - Other: PADEL',
            'tip1' => 'Each category has specific sub-categories for better organization',
            'tip2' => 'Use consistent naming conventions when adding new data',
        ],
    ],
];
