<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LogProgressRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Services\Workout\WorkoutPlanGenerator;
use App\Models\WorkoutSession;

class WorkoutPlanController extends Controller
{
    /**
     * Cache duration in seconds (30 minutes).
     */
    private const CACHE_TTL = 1800;

    /**
     * Generates a new weekly plan for the user.
     */
    public function generate(Request $request)
    {
        $user = Auth::user()->load('profile');

        if (empty($user->profile->position) || empty($user->profile->training_location)) {
            return ApiResponse::error('Please complete your player profile (position, training location) to generate a plan.');
        }

        $generator = new WorkoutPlanGenerator($user);
        $generator->generateAndSaveWeeklyPlan();

        // Invalidate the weekly plan cache after generating new plan
        self::invalidateWeeklyPlanCache($user->id);

        return ApiResponse::success(null, 'Weekly plan generated successfully.');
    }

    /**
     * Gets all workout sessions for the user.
     * Results are cached for 30 minutes.
     */
    public function getWeeklyPlan(Request $request)
    {
        $userId = $request->user()->id;
        $cacheKey = "workout_plan:user_{$userId}";

        // Check for force refresh parameter
        if ($request->boolean('refresh')) {
            Cache::forget($cacheKey);
        }

        $sessions = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userId) {
            return WorkoutSession::where('user_id', $userId)
                ->with('exercises')
                ->get();
        });

        return ApiResponse::success($sessions);
    }

    /**
     * Logs a user's completed workout (from Workout.swift).
     */
    public function logProgress(LogProgressRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        $progress = $user->progressLogs()->updateOrCreate(
            ['date' => $validated['date']], // Find by date
            $validated // Update with all new data
        );

        // Invalidate progress cache
        Cache::forget("user_progress:user_{$user->id}");

        return ApiResponse::created($progress, 'Progress logged successfully');
    }

    /**
     * Gets all progress logs for the authenticated user.
     * Results are cached for 30 minutes.
     */
    public function getProgress(Request $request)
    {
        $userId = $request->user()->id;
        $cacheKey = "user_progress:user_{$userId}";

        // Check for force refresh parameter
        if ($request->boolean('refresh')) {
            Cache::forget($cacheKey);
        }

        $logs = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($userId) {
            return \App\Models\UserProgress::where('user_id', $userId)
                ->orderBy('date', 'desc')
                ->get();
        });

        return ApiResponse::success($logs);
    }

    /**
     * Invalidate the user's weekly plan cache.
     */
    public static function invalidateWeeklyPlanCache(int $userId): void
    {
        Cache::forget("workout_plan:user_{$userId}");
    }
}
