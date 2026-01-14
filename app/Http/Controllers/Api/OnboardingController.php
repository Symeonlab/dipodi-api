<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use App\Models\OnboardingOption;
use App\Models\PlayerProfile;
use Illuminate\Support\Facades\Schema; // <-- THIS IS THE FIX

class OnboardingController extends Controller
{
    /**
     * Get all the dynamic data needed for the onboarding flow.
     */
    public function getOnboardingData()
    {
        // Get the translated name based on the app's 'Accept-Language' header (en, fr, ar)
        $locale = app()->getLocale();

        // --- FIX FOR 'name_en' ---
        // Check if the 'onboarding_options' table has the translated column
        $nameColumn = "name_{$locale}";
        if (!Schema::hasColumn('onboarding_options', $nameColumn)) {
            $nameColumn = 'name_en';
        }

        // 1. Get all standard options (disciplines, levels, goals, etc.)
        // and group them by their 'type'
        $options = OnboardingOption::all()
            ->map(fn ($option) => [
                'type' => $option->type,
                'key' => $option->key,
                'name' => $option->{$nameColumn}, // Select the correct language
            ])
            ->groupBy('type');

        // 2. Get player profiles (which are also "options")
        $options['player_profiles'] = PlayerProfile::all()
            ->map(fn ($profile) => [
                'key' => $profile->name,
                'name' => $profile->name, // These are not translated in the seeder
                'group' => $profile->group,
            ])
            ->groupBy('group');

        // 3. Get interests (Nutrition, Workout, etc.)
        // Check if the 'interests' table has the translated column
        $interestNameColumn = "name_{$locale}";
        if (!Schema::hasColumn('interests', $interestNameColumn)) {
            $interestNameColumn = 'name_en';
        }

        $options['interests'] = Interest::all()
            ->map(fn ($interest) => [
                'key' => $interest->key,
                'name' => $interest->{$interestNameColumn},
                'icon' => $interest->icon,
            ]);

        // Return everything as a single JSON object
        return response()->json($options);
    }
}
