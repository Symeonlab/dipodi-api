<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\BonusWorkoutRule;
use App\Models\OnboardingOption;
use App\Models\WorkoutThemeRule;

class WorkoutNutritionWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $bonusRules = BonusWorkoutRule::count();
        $onboardingOptions = OnboardingOption::count();
        $onboardingTypes = OnboardingOption::distinct('type')->count('type');
        $themeRules = WorkoutThemeRule::count();

        return [
            Stat::make(__('filament.resources.bonus_rules'), $bonusRules)
                ->description(__('filament.widgets.bonus_rules_desc'))
                ->descriptionIcon('heroicon-m-fire')
                ->color('danger'),

            Stat::make(__('filament.resources.theme_rules'), $themeRules)
                ->description(__('filament.widgets.theme_rules_desc'))
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('gray'),

            Stat::make(__('filament.resources.onboarding_options'), $onboardingOptions)
                ->description("{$onboardingTypes} " . __('filament.widgets.different_types'))
                ->descriptionIcon('heroicon-m-queue-list')
                ->color('purple'),

            Stat::make(__('filament.widgets.exercise_categories'), 6)
                ->description(__('filament.widgets.exercise_categories_desc'))
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('info'),
        ];
    }

    protected function getHeading(): ?string
    {
        return __('filament.widgets.workout_nutrition');
    }
}
