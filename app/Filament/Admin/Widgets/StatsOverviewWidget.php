<?php
namespace App\Filament\Admin\Widgets; // Note the correct namespace
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
            Stat::make('Total Users', User::count())->color('success'),
            Stat::make('New Users (7 Days)', User::where('created_at', '>=', now()->subWeek())->count()),
            Stat::make('Total Progress Logs', UserProgress::count())->color('warning'),
            Stat::make('Published Posts', Post::where('is_published', true)->count())->color('info'),
        ];
    }
}
