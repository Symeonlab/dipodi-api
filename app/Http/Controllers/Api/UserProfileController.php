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

        return ApiResponse::success(
            $user->load('profile'),
            'Profile updated successfully'
        );
    }
}
