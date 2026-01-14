<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OnboardingOption;

class OnboardingOptionSeeder extends Seeder
{
    public function run(): void
    {

        $options = [
            // From DisciplineSelectionView.swift
            ['type' => 'discipline', 'key' => 'FOOTBALL', 'name_en' => 'Football', 'name_fr' => 'Football', 'name_ar' => 'كرة القدم'],
            ['type' => 'discipline', 'key' => 'FUTSAL', 'name_en' => 'Futsal', 'name_fr' => 'Futsal', 'name_ar' => 'كرة الصالات'],
            ['type' => 'discipline', 'key' => 'FITNESS', 'name_en' => 'Fitness', 'name_fr' => 'Fitness', 'name_ar' => 'اللياقة البدنية'],

            // From FitnessLevelView.swift
            ['type' => 'level', 'key' => 'DÉBUTANT', 'name_en' => 'Beginner', 'name_fr' => 'Débutant', 'name_ar' => 'مبتدئ'],
            ['type' => 'level', 'key' => 'INTERMÉDIAIRE', 'name_en' => 'Intermediate', 'name_fr' => 'Intermédiaire', 'name_ar' => 'متوسط'],
            ['type' => 'level', 'key' => 'AVANCÉ', 'name_en' => 'Advanced', 'name_fr' => 'Avancé', 'name_ar' => 'متقدم'],

            // From GoalSelectionView.swift
            ['type' => 'goal', 'key' => 'goal.lose_weight', 'name_en' => 'Lose Weight', 'name_fr' => 'Perdre du Poids', 'name_ar' => 'خسارة الوزن'],
            ['type' => 'goal', 'key' => 'goal.gain_muscle', 'name_en' => 'Gain Muscle', 'name_fr' => 'Masse Musculaire', 'name_ar' => 'اكتساب العضلات'],
            ['type' => 'goal', 'key' => 'goal.maintain_shape', 'name_en' => 'Maintain Shape', 'name_fr' => 'Maintien de Forme', 'name_ar' => 'الحفاظ على الشكل'],

            // From PDF - Training Location
            ['type' => 'location', 'key' => 'SI MUSCULATION EN SALLE', 'name_en' => 'Gym (Weightlifting)', 'name_fr' => 'Musculation en Salle', 'name_ar' => 'صالة الألعاب (أثقال)'],
            ['type' => 'location', 'key' => 'SI CARDIO EN SALLE', 'name_en' => 'Gym (Cardio)', 'name_fr' => 'Cardio en Salle', 'name_ar' => 'صالة الألعاب (كارديو)'],
            ['type' => 'location', 'key' => 'SI MUSCULATION ET CARDIO EN SALLE', 'name_en' => 'Gym (Weights + Cardio)', 'name_fr' => 'Musculation et Cardio', 'name_ar' => 'صالة الألعاب (أثقال وكارديو)'],
            ['type' => 'location', 'key' => 'SI DEHORS', 'name_en' => 'Outdoors', 'name_fr' => 'Dehors', 'name_ar' => 'في الخارج'],
            ['type' => 'location', 'key' => 'SI MAISON', 'name_en' => 'At Home', 'name_fr' => 'Maison', 'name_ar' => 'في المنزل'],

            // From PDF - Injury Location
            ['type' => 'injury_location', 'key' => 'CHEVILLES', 'name_en' => 'Ankles', 'name_fr' => 'Chevilles', 'name_ar' => 'الكاحلين'],
            ['type' => 'injury_location', 'key' => 'GENOUX', 'name_en' => 'Knees', 'name_fr' => 'Genoux', 'name_ar' => 'الركبتين'],
            ['type' => 'injury_location', 'key' => 'HANCHES', 'name_en' => 'Hips', 'name_fr' => 'Hanches', 'name_ar' => 'الوركين'],
            ['type' => 'injury_location', 'key' => 'ADDUCTEURS', 'name_en' => 'Adductors', 'name_fr' => 'Adducteurs', 'name_ar' => 'العضلات المقربة'],
            // ... Add all other injury locations here ...

            // From PDF - Morphology
            ['type' => 'morphology', 'key' => 'ECTOMORPHE', 'name_en' => 'Ectomorph', 'name_fr' => 'Ectomorphe', 'name_ar' => 'إكتومورف'],
            ['type' => 'morphology', 'key' => 'MÉSOMORPHE', 'name_en' => 'Mesomorph', 'name_fr' => 'Mésomorphe', 'name_ar' => 'ميزومورف'],
            ['type' => 'morphology', 'key' => 'ENDOMORPHE', 'name_en' => 'Endomorph', 'name_fr' => 'Endomorphe', 'name_ar' => 'إندومورف'],

        ];

        foreach ($options as $option) {
            OnboardingOption::create($option);
        }
    }
}
