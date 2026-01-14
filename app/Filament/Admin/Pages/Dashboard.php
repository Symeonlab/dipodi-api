<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

// --- THIS IS THE FIX ---
// We must import all the widgets from the 'Widgets' namespace
use App\Filament\Admin\Widgets\StatsOverviewWidget;
use App\Filament\Admin\Widgets\ContentStatsWidget; // <-- Add this import
use App\Filament\Admin\Widgets\NewUsersChart;
use App\Filament\Admin\Widgets\LatestUsersTable;
// --- END OF FIX ---

class Dashboard extends BaseDashboard
{
    /**
     * Get the widgets that are displayed on the dashboard.
     *
     * @return array<class-string<BaseWidget> | BaseWidget>
     */
    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            ContentStatsWidget::class, // <-- This line will now work
            NewUsersChart::class,
            LatestUsersTable::class,
        ];
    }
}
