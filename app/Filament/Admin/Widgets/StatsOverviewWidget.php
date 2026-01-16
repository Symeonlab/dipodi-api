<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\Post;
use App\Models\WorkoutSession;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $newUsersThisWeek = User::where('created_at', '>=', now()->subWeek())->count();
        $newUsersLastWeek = User::whereBetween('created_at', [now()->subWeeks(2), now()->subWeek()])->count();
        $userGrowth = $newUsersLastWeek > 0 ? round((($newUsersThisWeek - $newUsersLastWeek) / $newUsersLastWeek) * 100) : 0;

        $totalProgress = UserProgress::count();
        $progressThisWeek = UserProgress::where('created_at', '>=', now()->subWeek())->count();

        return [
            Stat::make(__('filament.dashboard.total_users'), number_format($totalUsers))
                ->description($newUsersThisWeek . ' ' . __('filament.dashboard.new_this_week'))
                ->descriptionIcon($userGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart($this->getUserGrowthChart())
                ->color('success'),

            Stat::make(__('filament.dashboard.new_users_week'), $newUsersThisWeek)
                ->description($userGrowth >= 0 ? "+{$userGrowth}% " . __('filament.dashboard.vs_last_week') : "{$userGrowth}% " . __('filament.dashboard.vs_last_week'))
                ->descriptionIcon($userGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($userGrowth >= 0 ? 'success' : 'danger'),

            Stat::make(__('filament.dashboard.progress_logs'), number_format($totalProgress))
                ->description($progressThisWeek . ' ' . __('filament.dashboard.this_week'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),

            Stat::make(__('filament.dashboard.published_posts'), Post::where('is_published', true)->count())
                ->description(Post::where('is_published', false)->count() . ' ' . __('filament.dashboard.drafts'))
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
        ];
    }

    private function getUserGrowthChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $data[] = User::whereDate('created_at', now()->subDays($i))->count();
        }
        return $data;
    }
}
