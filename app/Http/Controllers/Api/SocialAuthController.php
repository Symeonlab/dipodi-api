<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function handleSocialLogin(Request $request, $provider)
    {
        $request->validate(['token' => 'required|string']);
        try {
            // Get user info from Google/Facebook/Apple using the token from the mobile app
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($request->token);
            if (empty($socialUser)) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            // Find or create the user in your database
            $user = User::updateOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'password' => Hash::make(Str::random(24)),
                ]
            );

            // Create a Sanctum API token
            $token = $user->createToken('social-login-token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user->load('profile'), // Send back user and profile
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed: ' . $e->getMessage()], 401);
        }
    }
}
