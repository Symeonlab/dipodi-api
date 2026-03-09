<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateUserProfileRequest;
use App\Http\Responses\ApiResponse;
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
        return ApiResponse::success($user);
    }

    /**
     * Update the authenticated user's profile from the onboarding flow.
     */
    public function update(UpdateUserProfileRequest $request)
    {
        $user = Auth::user();
        $validatedData = $request->validated();

        // Update the main User model (name/email)
        // Note: Use $request->input() instead of $validatedData since these may not be in validation rules
        if ($request->filled('name')) {
            $user->name = $request->input('name');
        }
        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }
        $user->save();

        // Update the UserProfile model
        $user->profile->update($validatedData);

        // --- THIS IS THE KEY ---
        // Once the profile is updated, mark onboarding as complete.
        $user->profile->update(['is_onboarding_complete' => true]);

        return ApiResponse::success(
            $user->load('profile'),
            'Profile updated successfully'
        );
    }
}
