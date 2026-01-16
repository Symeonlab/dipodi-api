<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Exercise;
use App\Models\FoodItem;
use App\Models\NutritionAdvice;
use App\Models\OnboardingOption;
use App\Models\BonusWorkoutRule;
use App\Models\PlayerProfile;

class ContentStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalExercises = Exercise::count();
        $exercisesWithVideo = Exercise::whereNotNull('video_url')->count();
        $videoPercentage = $totalExercises > 0 ? round(($exercisesWithVideo / $totalExercises) * 100) : 0;

        $totalFoodItems = FoodItem::count();
        $breakfastItems = FoodItem::where('category', 'petitDejeuner')->count();
        $mainDishes = FoodItem::where('category', 'platPrincipal')->count();

        return [
            Stat::make(__('filament.dashboard.exercises'), $totalExercises)
                ->description("{$exercisesWithVideo} " . __('filament.dashboard.with_videos') . " ({$videoPercentage}%)")
                ->descriptionIcon('heroicon-m-play-circle')
                ->chart($this->getExercisesByCategoryData())
                ->color('info'),

            Stat::make(__('filament.dashboard.food_items'), $totalFoodItems)
                ->description("{$breakfastItems} " . __('filament.dashboard.breakfast') . ", {$mainDishes} " . __('filament.dashboard.main'))
                ->descriptionIcon('heroicon-m-cake')
                ->color('warning'),

            Stat::make(__('filament.dashboard.nutrition_advice'), NutritionAdvice::count())
                ->description(__('filament.dashboard.sport_prophetic'))
                ->descriptionIcon('heroicon-m-light-bulb')
                ->color('success'),

            Stat::make(__('filament.dashboard.player_profiles'), PlayerProfile::count())
                ->description('Tank, Magicien, Sentinelle...')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('purple'),
        ];
    }

    private function getExercisesByCategoryData(): array
    {
        return [
            Exercise::where('category', 'LIKE', 'KINE%')->count(),
            Exercise::where('category', 'LIKE', '%MAISON%')->count(),
            Exercise::where('category', 'LIKE', '%BONUS%')->count(),
            Exercise::where('category', 'LIKE', '%CARDIO%')->count(),
            Exercise::where('category', '=', 'MUSCULATION')->count(),
        ];
    }
}
