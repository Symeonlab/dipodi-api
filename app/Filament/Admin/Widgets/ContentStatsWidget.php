<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Exercise;
use App\Models\PlayerProfile;
use App\Models\NutritionAdvice;
use App\Models\WorkoutTheme;

class ContentStatsWidget extends BaseWidget
{
    // Show this widget after the user stats
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Exercises', Exercise::count())
                ->description('All Kine, Gym, and Home exercises')
                ->descriptionIcon('heroicon-m-sparkles')
                ->color('info'),
            Stat::make('Kine Exercises', Exercise::where('category', 'LIKE', 'KINE%')->count())
                ->description('Mobility & Renforcement exercises')
                ->descriptionIcon('heroicon-m-heart')
                ->color('info'),
            Stat::make('Player Profiles', PlayerProfile::count())
                ->description('All dynamic player types (Tank, Magicien...)')
                ->descriptionIcon('heroicon-m-identification')
                ->color('warning'),
            Stat::make('Nutrition Tips', NutritionAdvice::count())
                ->description('All advice for medical conditions')
                ->descriptionIcon('heroicon-m-light-bulb')
                ->color('success'),
        ];
    }
}
