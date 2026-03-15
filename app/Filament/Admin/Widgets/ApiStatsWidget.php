<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\UserProgress;
use App\Models\Post;
use App\Models\WorkoutSession;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApiStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';
    protected static ?int $sort = 2;

    protected function getHeading(): ?string
    {
        return __('filament.widgets.api_stats');
    }

    protected function getStats(): array
    {
        // These stats match what the /dashboard-metrics API returns
        $totalUsers = User::count();
        $newUsersWeek = User::where('created_at', '>=', now()->subWeek())->count();
        $totalProgressLogs = UserProgress::count();
        $publishedPosts = Post::where('is_published', true)->count();
        $workoutSessions = WorkoutSession::count();

        // Calculate growth
        $lastWeekUsers = User::whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])->count();
        $userGrowth = $lastWeekUsers > 0 ? round((($newUsersWeek - $lastWeekUsers) / $lastWeekUsers) * 100) : 0;

        return [
            Stat::make(__('filament.dashboard.total_users'), $totalUsers)
                ->description(__('filament.widgets.new_this_week') . ": {$newUsersWeek}")
                ->descriptionIcon($userGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($userGrowth >= 0 ? 'success' : 'danger')
                ->chart($this->getUserChart()),

            Stat::make(__('filament.dashboard.progress_logs'), $totalProgressLogs)
                ->description(__('filament.widgets.total_workout_logs'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),

            Stat::make(__('filament.resources.workout_sessions'), $workoutSessions)
                ->description(__('filament.widgets.generated_via_api'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('purple'),

            Stat::make(__('filament.dashboard.published_posts'), $publishedPosts)
                ->description(__('filament.widgets.visible_in_app'))
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('warning'),
        ];
    }

    private function getUserChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = User::whereDate('created_at', $date)->count();
        }
        return $data;
    }
}
