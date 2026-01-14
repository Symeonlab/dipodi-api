<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PlayerProfile;
use App\Models\OnboardingOption;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get dynamic options to build profiles
        $positions = PlayerProfile::all()->pluck('name');
        $levels = OnboardingOption::where('type', 'level')->pluck('key');
        $goals = OnboardingOption::where('type', 'goal')->pluck('key');
        $locations = OnboardingOption::where('type', 'location')->pluck('key');
        $disciplines = OnboardingOption::where('type', 'discipline')->pluck('key');

        // 1. --- CREATE YOUR ADMIN ACCOUNT ---
        $adminUser = User::create([
            'name' => 'Admin Dipodi',
            'email' => 'labadarios@gmail.com',
            'password' => Hash::make('Nerson007'),
            'role' => 'admin', // Set the role to 'admin'
        ]);

        // Give the admin a complete profile
        $adminUser->profile->update([
            'is_onboarding_complete' => true,
            'age' => 30,
            'weight' => 80,
            'height' => 180,
            'gender' => 'HOMME',
            'goal' => $goals->random(),
            'level' => $levels->random(),
            'discipline' => $disciplines->random(),
            'position' => $positions->random(),
            'training_location' => $locations->random(),
        ]);

        // 2. --- CREATE TEST USERS (ROLE: 'user') ---
        User::factory(9)->create([
            'role' => 'user',
        ])->each(function ($user) use ($positions, $levels, $goals, $locations, $disciplines) {
            $user->profile->update([
                'is_onboarding_complete' => true,
                'age' => rand(18, 35),
                'weight' => rand(65, 90),
                'height' => rand(160, 190),
                'gender' => ['HOMME', 'FEMME'][rand(0, 1)],
                'goal' => $goals->random(),
                'level' => $levels->random(),
                'discipline' => $disciplines->random(),
                'position' => $positions->random(),
                'training_location' => $locations->random(),
                'medical_history' => json_encode(fake()->randomElement([['DIABÈTE'], ['FATIGUE'], []])),
            ]);
        });

        // 3. --- CREATE TEST COACH (ROLE: 'coach') ---
        $coachUser = User::factory()->create([
            'name' => 'Test Coach',
            'email' => 'coach@dipodi.com',
            'role' => 'coach',
        ]);
        $coachUser->profile->update([
            'is_onboarding_complete' => true,
            'age' => 40,
            'weight' => 85,
            'height' => 185,
            'gender' => 'HOMME',
            'goal' => $goals->random(),
            'level' => 'AVANCÉ',
            'discipline' => 'FITNESS',
            'position' => 'FITNESS',
            'training_location' => $locations->random(),
        ]);
    }
}
