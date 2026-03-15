<?php

namespace App\Filament\Admin\Widgets;

use App\Models\WorkoutTheme;
use Filament\Widgets\Widget;

class ZoneLegendWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.zone-legend';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    public function getZones(): array
    {
        $themesByZone = WorkoutTheme::selectRaw('zone_color, count(*) as count')
            ->whereNotNull('zone_color')
            ->groupBy('zone_color')
            ->pluck('count', 'zone_color')
            ->toArray();

        return [
            [
                'color' => 'blue',
                'label' => __('filament.widgets.zone_recovery'),
                'range' => '50-60%',
                'rpe' => 'RPE 1-2',
                'bg' => 'bg-blue-500',
                'count' => $themesByZone['blue'] ?? 0,
            ],
            [
                'color' => 'green',
                'label' => __('filament.widgets.zone_endurance'),
                'range' => '60-70%',
                'rpe' => 'RPE 3-4',
                'bg' => 'bg-green-500',
                'count' => $themesByZone['green'] ?? 0,
            ],
            [
                'color' => 'yellow',
                'label' => __('filament.widgets.zone_match_rhythm'),
                'range' => '70-80%',
                'rpe' => 'RPE 5-6',
                'bg' => 'bg-yellow-500',
                'count' => $themesByZone['yellow'] ?? 0,
            ],
            [
                'color' => 'orange',
                'label' => __('filament.widgets.zone_high_intensity'),
                'range' => '80-90%',
                'rpe' => 'RPE 7-8',
                'bg' => 'bg-orange-500',
                'count' => $themesByZone['orange'] ?? 0,
            ],
            [
                'color' => 'red',
                'label' => __('filament.widgets.zone_maximum'),
                'range' => '90-100%',
                'rpe' => 'RPE 9-10',
                'bg' => 'bg-red-500',
                'count' => $themesByZone['red'] ?? 0,
            ],
        ];
    }
}
