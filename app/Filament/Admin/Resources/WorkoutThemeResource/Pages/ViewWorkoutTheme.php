<?php

namespace App\Filament\Admin\Resources\WorkoutThemeResource\Pages;

use App\Filament\Admin\Resources\WorkoutThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkoutTheme extends ViewRecord
{
    protected static string $resource = WorkoutThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
