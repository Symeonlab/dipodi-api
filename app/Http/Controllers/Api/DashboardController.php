<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\UserGoal;
use App\Models\Post;
use Flowframe\Trend\Trend;

class DashboardController extends Controller
{
    /**
     * Get all dashboard metrics for the mobile app.
     */
    public function getMetrics(Request $request)
    {
        $user = $request->user();
        $locale = $request->header('Accept-Language', 'en');
        $locale = in_array($locale, ['en', 'fr', 'ar']) ? $locale : 'en';

        // 1. Get Stats Overview
        $stats = [
            'total_users' => User::count(),
            'new_users_week' => User::where('created_at', '>=', now()->subWeek())->count(),
            'total_progress_logs' => UserProgress::count(),
            'published_posts' => Post::where('is_published', true)->count(),
        ];

        // 2. Get Chart Data
        $chartData = Trend::model(User::class)
            ->between(start: now()->subMonth(), end: now())
            ->perDay()
            ->count();

        $chart = [
            'labels' => $chartData->map(fn ($value) => $value->date),
            'data' => $chartData->map(fn ($value) => $value->aggregate),
        ];

        // 3. Get Latest User Activity (for the logged-in user)
        $latestProgress = UserProgress::where('user_id', $user->id)
            ->latest('date')
            ->limit(5)
            ->get();

        // 4. Get Active Goal
        $activeGoal = UserGoal::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        $goalData = null;
        if ($activeGoal) {
            $goalData = [
                'id' => $activeGoal->id,
                'goal_type' => $activeGoal->goal_type,
                'progress' => round($activeGoal->current_progress_percentage, 1),
                'is_on_track' => $activeGoal->isOnTrack(),
                'weeks_completed' => $activeGoal->weeks_completed,
                'total_weeks' => $activeGoal->total_weeks,
                'target_date' => $activeGoal->target_date?->format('Y-m-d'),
            ];
        }

        // 5. Get Achievement Summary
        $totalAchievements = $user->achievements()->count();
        $totalPoints = $user->achievements()->sum('points');
        $recentAchievements = $user->achievements()
            ->orderByPivot('earned_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($achievement) use ($locale) {
                $nameField = "name_{$locale}";
                return [
                    'id' => $achievement->id,
                    'name' => $achievement->{$nameField} ?? $achievement->name_en,
                    'icon' => $achievement->icon,
                    'points' => $achievement->points,
                    'earned_at' => $achievement->pivot->earned_at,
                ];
            });

        // 6. Get Latest Posts
        $latestPosts = Post::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($post) use ($locale) {
                $titleField = "title_{$locale}";
                return [
                    'id' => $post->id,
                    'title' => $post->{$titleField} ?? $post->title_en,
                    'slug' => $post->slug,
                    'featured_image' => $post->featured_image,
                    'published_at' => $post->created_at->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'chart' => $chart,
            'my_latest_progress' => $latestProgress,
            'active_goal' => $goalData,
            'achievements' => [
                'total_earned' => $totalAchievements,
                'total_points' => $totalPoints,
                'recent' => $recentAchievements,
            ],
            'latest_posts' => $latestPosts,
        ]);
    }
}
