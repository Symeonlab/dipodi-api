<?php

namespace App\Filament\Admin\Widgets;

use App\Models\UserGoal;
use App\Models\User;
use App\Models\Achievement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GoalProgressWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $usersWithGoals = UserGoal::distinct('user_id')->count('user_id');
        $activeGoals = UserGoal::where('status', 'active')->count();
        $completedGoals = UserGoal::where('status', 'completed')->count();
        $totalAchievementsEarned = \DB::table('user_achievements')->count();

        // Average progress across all active goals
        $avgProgress = UserGoal::where('status', 'active')->avg('current_progress_percentage') ?? 0;

        // Goals by type
        $weightLossGoals = UserGoal::where('goal_type', 'weight_loss')->count();
        $muscleGainGoals = UserGoal::where('goal_type', 'muscle_gain')->count();
        $maintainGoals = UserGoal::where('goal_type', 'maintain')->count();

        // Week-over-week goal completions
        $completedThisWeek = UserGoal::where('status', 'completed')
            ->where('completed_at', '>=', now()->subWeek())
            ->count();

        return [
            Stat::make(__('filament.widgets.active_goals'), $activeGoals)
                ->description(__('filament.widgets.users_with_goals', ['count' => $usersWithGoals]))
                ->descriptionIcon('heroicon-m-flag')
                ->color('primary')
                ->chart($this->getGoalTrendChart()),

            Stat::make(__('filament.widgets.average_progress'), round($avgProgress) . '%')
                ->description(__('filament.widgets.across_active_goals'))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($avgProgress >= 50 ? 'success' : 'warning'),

            Stat::make(__('filament.widgets.goals_completed'), $completedGoals)
                ->description(__('filament.widgets.this_week', ['count' => $completedThisWeek]))
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),

            Stat::make(__('filament.widgets.achievements_earned'), $totalAchievementsEarned)
                ->description(__('filament.widgets.total_available', ['total' => Achievement::count()]))
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }

    protected function getGoalTrendChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = UserGoal::whereDate('created_at', $date)->count();
        }
        return $data;
    }

    protected function getHeading(): ?string
    {
        return __('filament.widgets.goal_progress');
    }
}
