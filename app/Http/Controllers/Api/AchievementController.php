<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AchievementController extends Controller
{
    /**
     * Get all available achievements.
     */
    public function index(Request $request): JsonResponse
    {
        $achievements = Achievement::orderBy('category')
            ->orderBy('points', 'desc')
            ->get()
            ->map(function ($achievement) use ($request) {
                $userAchievement = $request->user()
                    ->achievements()
                    ->where('achievement_id', $achievement->id)
                    ->first();

                return [
                    'id' => $achievement->id,
                    'key' => $achievement->key,
                    'name' => $achievement->name,
                    'description' => $achievement->description,
                    'icon' => $achievement->icon,
                    'points' => $achievement->points,
                    'category' => $achievement->category,
                    'earned' => $userAchievement !== null,
                    'earned_at' => $userAchievement?->pivot?->earned_at,
                ];
            });

        $grouped = $achievements->groupBy('category');

        return ApiResponse::success([
            'achievements' => $achievements,
            'by_category' => $grouped,
            'total_points' => $achievements->where('earned', true)->sum('points'),
            'total_earned' => $achievements->where('earned', true)->count(),
            'total_available' => $achievements->count(),
        ]);
    }

    /**
     * Get user's earned achievements.
     */
    public function earned(Request $request): JsonResponse
    {
        $achievements = $request->user()
            ->achievements()
            ->orderByPivot('earned_at', 'desc')
            ->get()
            ->map(function ($achievement) {
                return [
                    'id' => $achievement->id,
                    'key' => $achievement->key,
                    'name' => $achievement->name,
                    'description' => $achievement->description,
                    'icon' => $achievement->icon,
                    'points' => $achievement->points,
                    'category' => $achievement->category,
                    'earned_at' => $achievement->pivot->earned_at,
                ];
            });

        return ApiResponse::success([
            'achievements' => $achievements,
            'total_points' => $achievements->sum('points'),
            'count' => $achievements->count(),
        ]);
    }

    /**
     * Get achievement details.
     */
    public function show(Request $request, Achievement $achievement): JsonResponse
    {
        $userAchievement = $request->user()
            ->achievements()
            ->where('achievement_id', $achievement->id)
            ->first();

        $earnedByCount = $achievement->users()->count();

        return ApiResponse::success([
            'id' => $achievement->id,
            'key' => $achievement->key,
            'name' => $achievement->name,
            'description' => $achievement->description,
            'icon' => $achievement->icon,
            'points' => $achievement->points,
            'category' => $achievement->category,
            'earned' => $userAchievement !== null,
            'earned_at' => $userAchievement?->pivot?->earned_at,
            'earned_by_count' => $earnedByCount,
        ]);
    }

    /**
     * Get achievement stats for leaderboard.
     */
    public function leaderboard(Request $request): JsonResponse
    {
        $limit = min($request->input('limit', 10), 50);

        $topUsers = \DB::table('user_achievements')
            ->join('achievements', 'user_achievements.achievement_id', '=', 'achievements.id')
            ->join('users', 'user_achievements.user_id', '=', 'users.id')
            ->select(
                'users.id',
                'users.name',
                \DB::raw('SUM(achievements.points) as total_points'),
                \DB::raw('COUNT(user_achievements.id) as achievement_count')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_points')
            ->limit($limit)
            ->get();

        $currentUser = $request->user();
        $userPoints = $currentUser->achievements()->sum('points');
        $userRank = \DB::table('user_achievements')
            ->join('achievements', 'user_achievements.achievement_id', '=', 'achievements.id')
            ->select('user_id', \DB::raw('SUM(achievements.points) as total'))
            ->groupBy('user_id')
            ->having('total', '>', $userPoints)
            ->count() + 1;

        return ApiResponse::success([
            'leaderboard' => $topUsers,
            'current_user' => [
                'rank' => $userRank,
                'total_points' => $userPoints,
                'achievement_count' => $currentUser->achievements()->count(),
            ],
        ]);
    }
}
