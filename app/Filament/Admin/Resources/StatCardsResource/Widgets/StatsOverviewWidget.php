<?php
namespace App\Filament\Admin\Widgets;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\Post;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make(__('filament.widgets.total_users'), User::count())->color('success'),
            Stat::make(__('filament.widgets.new_users_7_days'), User::where('created_at', '>=', now()->subWeek())->count()),
            Stat::make(__('filament.widgets.total_progress_logs'), UserProgress::count())->color('warning'),
            Stat::make(__('filament.widgets.published_posts'), Post::where('is_published', true)->count())->color('info'),
        ];
    }
}
