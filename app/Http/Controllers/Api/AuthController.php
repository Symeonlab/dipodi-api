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
use Illuminate\Validation\Rules\Password as PasswordRule;

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
        // Refresh to get database defaults like 'role'
        $user->refresh();

        $token = $user->createToken('dipodi-mobile')->plainTextToken;

        return ApiResponse::created([
            'user' => $user->load('profile'),
            'token' => $token,
        ], __('auth.registration_successful'));
    }

    /**
     * Log in a user via the API.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return ApiResponse::unauthorized(__('auth.invalid_credentials'));
        }

        $user = Auth::user();

        // Revoke all previous tokens for this device to prevent token accumulation
        $user->tokens()->where('name', 'dipodi-mobile')->delete();

        $token = $user->createToken('dipodi-mobile')->plainTextToken;

        return ApiResponse::success([
            'user' => $user->load('profile'),
            'token' => $token,
        ], __('auth.login_successful'));
    }

    /**
     * Log out the user (invalidates the token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::success(null, __('auth.logout_successful'));
    }

    /**
     * Log out from all devices (invalidates all tokens).
     */
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return ApiResponse::success(null, __('auth.logout_all_successful'));
    }

    /**
     * Send a password reset link to the user's email.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return ApiResponse::success(null, __('auth.reset_link_sent'));
        }

        return ApiResponse::error(__('auth.reset_link_failed'));
    }

    /**
     * Change the authenticated user's password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return ApiResponse::error(__('auth.current_password_incorrect'), 422);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Revoke all other tokens for security
        $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();

        return ApiResponse::success(null, __('auth.password_changed'));
    }

    /**
     * Get the authenticated user's info (auth status check).
     */
    public function me(Request $request)
    {
        return ApiResponse::success([
            'user' => $request->user()->load('profile'),
        ]);
    }
}
