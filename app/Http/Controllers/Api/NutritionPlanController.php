<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\Nutrition\NutritionPlanGenerator;

class NutritionPlanController extends Controller
{
    /**
     * Cache duration in seconds (1 hour).
     */
    private const CACHE_TTL = 3600;

    /**
     * Generate and return the user's personalized nutrition plan.
     * Results are cached for 1 hour per user to reduce computation.
     */
    public function generate(Request $request)
    {
        $user = $request->user();
        $locale = app()->getLocale();

        // Generate a unique cache key based on user ID, profile hash, and locale
        $profileHash = md5(json_encode([
            'weight' => $user->profile->weight,
            'height' => $user->profile->height,
            'age' => $user->profile->age,
            'gender' => $user->profile->gender,
            'goal' => $user->profile->goal,
            'activity_level' => $user->profile->activity_level,
            'is_vegetarian' => $user->profile->is_vegetarian,
            'breakfast_preferences' => $user->profile->breakfast_preferences,
            'medical_history' => $user->profile->medical_history,
            'family_history' => $user->profile->family_history,
        ]));

        $cacheKey = "nutrition_plan:user_{$user->id}:profile_{$profileHash}:locale_{$locale}";

        // Check for force refresh parameter
        if ($request->boolean('refresh')) {
            Cache::forget($cacheKey);
        }

        $plan = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
            $generator = new NutritionPlanGenerator($user);
            return $generator->generatePlan();
        });

        return ApiResponse::success($plan);
    }

    /**
     * Invalidate the user's nutrition plan cache.
     * Call this when user profile is updated.
     */
    public static function invalidateCache(int $userId): void
    {
        // Use pattern matching to delete all cache keys for this user
        $pattern = "nutrition_plan:user_{$userId}:*";
        Cache::forget($pattern);
    }
}
