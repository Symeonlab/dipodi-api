<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Interest;

class InterestSeeder extends Seeder
{
    public function run(): void
    {

        // Data from CustomizeInterestView.swift
        $interests = [
            ['key' => 'NUTRITION', 'icon' => 'leaf.fill', 'name_en' => 'Nutrition', 'name_fr' => 'Nutrition', 'name_ar' => 'تغذية'],
            ['key' => 'WORKOUT', 'icon' => 'figure.walk', 'name_en' => 'Workout', 'name_fr' => 'Exercice', 'name_ar' => 'تمرين'],
            ['key' => 'MEDITATION', 'icon' => 'mind.head.profile', 'name_en' => 'Meditation', 'name_fr' => 'Méditation', 'name_ar' => 'تأمل'],
            ['key' => 'SPORTS', 'icon' => 'sportscourt.fill', 'name_en' => 'Sports', 'name_fr' => 'Sports', 'name_ar' => 'رياضات'],
            ['key' => 'SMOKE_FREE', 'icon' => 'nosign', 'name_en' => 'Smoke Free', 'name_fr' => 'Sans Fumée', 'name_ar' => 'بدون تدخين'],
            ['key' => 'SLEEP', 'icon' => 'powersleep', 'name_en' => 'Sleep', 'name_fr' => 'Sommeil', 'name_ar' => 'نوم'],
            ['key' => 'HEALTH', 'icon' => 'heart.fill', 'name_en' => 'Health', 'name_fr' => 'Santé', 'name_ar' => 'صحة'],
            ['key' => 'RUNNING', 'icon' => 'figure.run', 'name_en' => 'Running', 'name_fr' => 'Course', 'name_ar' => 'جري'],
            ['key' => 'YOGA', 'icon' => 'figure.yoga', 'name_en' => 'Yoga', 'name_fr' => 'Yoga', 'name_ar' => 'يوجا'],
        ];

        foreach ($interests as $interest) {
            Interest::create($interest);
        }
    }
}
