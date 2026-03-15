<?php

namespace App\Filament\Admin\Widgets;

use App\Models\FoodItem;
use Filament\Widgets\ChartWidget;

class FoodCategoryChart extends ChartWidget
{
    protected static ?string $heading = null;
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 1;

    public function getHeading(): ?string
    {
        return __('filament.widgets.food_items_by_category');
    }

    protected function getData(): array
    {
        $categories = [
            __('filament.categories.petit_dejeuner') => FoodItem::where('category', 'petitDejeuner')->count(),
            __('filament.categories.plat_principal') => FoodItem::where('category', 'platPrincipal')->count(),
            __('filament.categories.accompagnement') => FoodItem::where('category', 'accompagnement')->count(),
            __('filament.categories.dessert') => FoodItem::where('category', 'dessert')->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => __('filament.dashboard.food_items'),
                    'data' => array_values($categories),
                    'backgroundColor' => [
                        '#F59E0B', // warning - Breakfast
                        '#10B981', // success - Main
                        '#3B82F6', // info - Side
                        '#EF4444', // danger - Dessert
                    ],
                ],
            ],
            'labels' => array_keys($categories),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
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
