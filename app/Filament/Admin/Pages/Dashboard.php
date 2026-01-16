<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Admin\Widgets\QuickActionsWidget;
use App\Filament\Admin\Widgets\StatsOverviewWidget;
use App\Filament\Admin\Widgets\ContentStatsWidget;
use App\Filament\Admin\Widgets\WorkoutNutritionWidget;
use App\Filament\Admin\Widgets\ExerciseCategoryChart;
use App\Filament\Admin\Widgets\FoodCategoryChart;
use App\Filament\Admin\Widgets\NewUsersChart;
use App\Filament\Admin\Widgets\LatestUsersTable;
use App\Filament\Admin\Widgets\UserActivityWidget;
use App\Filament\Admin\Widgets\OnboardingStatsWidget;
use App\Filament\Admin\Widgets\GoalProgressWidget;
use App\Filament\Admin\Widgets\AchievementsWidget;
use App\Filament\Admin\Widgets\ApiStatsWidget;

class Dashboard extends BaseDashboard
{
    public function getTitle(): string
    {
        return __('filament.dashboard.title');
    }

    public function getWidgets(): array
    {
        return [
            QuickActionsWidget::class,
            GoalProgressWidget::class,
            StatsOverviewWidget::class,
            ApiStatsWidget::class,
            ContentStatsWidget::class,
            WorkoutNutritionWidget::class,
            OnboardingStatsWidget::class,
            ExerciseCategoryChart::class,
            FoodCategoryChart::class,
            NewUsersChart::class,
            LatestUsersTable::class,
            UserActivityWidget::class,
            AchievementsWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }
}
