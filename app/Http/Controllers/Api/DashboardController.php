<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\Post;
use Flowframe\Trend\Trend;

class DashboardController extends Controller
{
    /**
     * Get all dashboard metrics for the mobile app.
     */
    public function getMetrics(Request $request)
    {
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
        $latestProgress = UserProgress::where('user_id', $request->user()->id)
            ->latest('date')
            ->limit(5)
            ->get();

        return response()->json([
            'stats' => $stats,
            'chart' => $chart,
            'my_latest_progress' => $latestProgress,
        ]);
    }
}
