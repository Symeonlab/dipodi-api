<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Interest;
use App\Models\OnboardingOption;
use App\Models\PlayerProfile;

class OnboardingController extends Controller
{
    /**
     * Get all the dynamic data needed for the onboarding flow.
     * Uses model accessors that resolve locale via app()->getLocale().
     */
    public function getOnboardingData()
    {
        // 1. Get all standard options (disciplines, levels, goals, etc.)
        // Model accessor resolves locale automatically
        $options = OnboardingOption::all()
            ->map(fn ($option) => [
                'type' => $option->type,
                'key' => $option->key,
                'name' => $option->name,
            ])
            ->groupBy('type');

        // 2. Get player profiles
        $options['player_profiles'] = PlayerProfile::all()
            ->map(fn ($profile) => [
                'key' => $profile->name,
                'name' => $profile->name,
                'group' => $profile->group,
            ])
            ->groupBy('group');

        // 3. Get interests — model accessor resolves locale automatically
        $options['interests'] = Interest::all()
            ->map(fn ($interest) => [
                'key' => $interest->key,
                'name' => $interest->name,
                'icon' => $interest->icon,
            ]);

        return ApiResponse::success($options);
    }
}
