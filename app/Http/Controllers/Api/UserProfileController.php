<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function show(Request $request)
    {
        $user = Auth::user()->load('profile');
        return response()->json($user);
    }

    /**
     * Update the authenticated user's profile from the onboarding flow.
     * This contains all the validation "rules".
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // All rules based on OnboardingData.swift and your PDF
        $validatedData = $request->validate([
            // User model
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,

            // Sport Profile
            'discipline' => 'nullable|string',
            'position' => 'nullable|string',
            'in_club' => 'nullable|boolean',
            'match_day' => 'nullable|string',
            'training_days' => 'nullable|array',
            'training_focus' => 'nullable|string',
            'level' => 'nullable|string',
            'has_injury' => 'nullable|boolean',
            'injury_location' => 'nullable|string',
            'training_location' => 'nullable|string',
            'gym_preferences' => 'nullable|array',
            'cardio_preferences' => 'nullable|array',
            'outdoor_preferences' => 'nullable|array',
            'home_preferences' => 'nullable|array',

            // Personal Info
            'gender' => 'nullable|string',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:30|max:300',
            'age' => 'nullable|integer|min:16|max:100',
            'country' => 'nullable|string',
            'region' => 'nullable|string',
            'pro_level' => 'nullable|string',

            // Nutrition Profile
            'ideal_weight' => 'nullable|numeric',
            'birth_date' => 'nullable|date',
            'activity_level' => 'nullable|string',
            'goal' => 'nullable|string',
            'morphology' => 'nullable|string',
            'hormonal_issues' => 'nullable|string',
            'is_vegetarian' => 'nullable|boolean',
            'meals_per_day' => 'nullable|string',
            'breakfast_preferences' => 'nullable|array',
            'bad_habits' => 'nullable|array',
            'snacking_habits' => 'nullable|string',
            'vegetable_consumption' => 'nullable|string',
            'fish_consumption' => 'nullable|string',
            'meat_consumption' => 'nullable|string',
            'dairy_consumption' => 'nullable|string',
            'sugary_food_consumption' => 'nullable|string',
            'cereal_consumption' => 'nullable|string',
            'starchy_food_consumption' => 'nullable|string',
            'sugary_drink_consumption' => 'nullable|string',
            'egg_consumption' => 'nullable|string',
            'fruit_consumption' => 'nullable|string',
            'takes_medication' => 'nullable|boolean',
            'has_diabetes' => 'nullable|boolean',
            'family_history' => 'nullable|array',
            'medical_history' => 'nullable|array',
        ]);

        // Update the main User model (name/email)
        if ($request->has('name')) {
            $user->name = $validatedData['name'];
        }
        if ($request->has('email')) {
            $user->email = $validatedData['email'];
        }
        $user->save();

        // Update the UserProfile model
        $user->profile->update($validatedData);

        // --- THIS IS THE KEY ---
        // Once the profile is updated, mark onboarding as complete.
        $user->profile->update(['is_onboarding_complete' => true]);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->load('profile'),
        ]);
    }
}
