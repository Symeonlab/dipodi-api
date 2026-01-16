<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    /**
     * Register a new user via the API.
     */
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // The User model's 'boot' method automatically creates a profile.
        // 'is_onboarding_complete' is 'false' by default.

        $token = $user->createToken('api-token')->plainTextToken;

        return ApiResponse::created([
            'user' => $user->load('profile'),
            'token' => $token,
        ], 'Registration successful');
    }

    /**
     * Log in a user via the API.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return ApiResponse::unauthorized('Invalid credentials');
        }

        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return ApiResponse::success([
            'user' => $user->load('profile'),
            'token' => $token,
        ], 'Login successful');
    }

    /**
     * Log out the user (invalidates the token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::success(null, 'Logged out successfully');
    }

    /**
     * Send a password reset link to the user's email.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return ApiResponse::success(null, 'Password reset link sent.');
        }

        return ApiResponse::error('Unable to send reset link.');
    }
}
