<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Exercise;
use Filament\Widgets\ChartWidget;

class ExerciseCategoryChart extends ChartWidget
{
    protected static ?string $heading = null;
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;

    public function getHeading(): ?string
    {
        return __('filament.widgets.exercises_by_category');
    }

    protected function getData(): array
    {
        $categories = [
            'KINE RENFORCEMENT' => Exercise::where('category', 'KINE RENFORCEMENT')->count(),
            'KINE MOBILITÉ' => Exercise::where('category', 'KINE MOBILITÉ')->count(),
            'MAISON' => Exercise::where('category', 'LIKE', '%MAISON%')->count(),
            'BONUS' => Exercise::where('category', 'LIKE', '%BONUS%')->count(),
            'CARDIO' => Exercise::where('category', 'LIKE', '%CARDIO%')->count(),
            'MUSCULATION' => Exercise::where('category', 'MUSCULATION')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => __('filament.dashboard.exercises'),
                    'data' => array_values($categories),
                    'backgroundColor' => [
                        '#10B981', // success - KINE RENFORCEMENT
                        '#3B82F6', // info - KINE MOBILITÉ
                        '#F59E0B', // warning - MAISON
                        '#EF4444', // danger - BONUS
                        '#8B5CF6', // purple - CARDIO
                        '#6B7280', // gray - MUSCULATION
                    ],
                ],
            ],
            'labels' => [
                __('filament.categories.kine_renforcement'),
                __('filament.categories.kine_mobilite'),
                __('filament.categories.maison'),
                __('filament.categories.bonus'),
                __('filament.categories.cardio'),
                __('filament.categories.musculation'),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
