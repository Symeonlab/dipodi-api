<?php

namespace App\Filament\Admin\Resources\WorkoutThemeResource\Pages;

use App\Filament\Admin\Resources\WorkoutThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkoutThemes extends ListRecords
{
    protected static string $resource = WorkoutThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
