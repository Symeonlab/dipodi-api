<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Handle social login from Google, Facebook, or Apple.
     * Validates the provider token, finds or creates the user, and returns a Sanctum token.
     */
    public function handleSocialLogin(Request $request, $provider)
    {
        // Validate provider is one of the allowed ones
        if (!in_array($provider, ['google', 'facebook', 'apple'])) {
            return ApiResponse::error('Unsupported authentication provider.', 422);
        }

        $request->validate(['token' => 'required|string']);

        try {
            // Get user info from Google/Facebook/Apple using the token from the mobile app
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($request->token);

            if (empty($socialUser)) {
                return ApiResponse::error('Invalid authentication token.', 401);
            }

            // Ensure we have an email — Apple Sign In can hide emails
            $email = $socialUser->getEmail();
            if (empty($email)) {
                return ApiResponse::error('An email address is required. Please allow email access in your provider settings.', 422);
            }

            // Find or create the user in your database
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $socialUser->getName() ?? explode('@', $email)[0],
                    'password' => Hash::make(Str::random(32)),
                ]
            );

            // Revoke previous social login tokens to prevent accumulation
            $user->tokens()->where('name', 'dipodi-social')->delete();

            // Create a Sanctum API token
            $token = $user->createToken('dipodi-social')->plainTextToken;

            return ApiResponse::success([
                'token' => $token,
                'user'  => $user->load('profile'),
            ], 'Login successful');

        } catch (\Exception $e) {
            // Log the real error internally but return a generic message
            Log::error('Social login failed', [
                'provider' => $provider,
                'error'    => $e->getMessage(),
            ]);

            return ApiResponse::error('Login failed. Please try again.', 401);
        }
    }
}
