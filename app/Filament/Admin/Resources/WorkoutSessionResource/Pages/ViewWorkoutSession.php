<?php

namespace App\Filament\Admin\Resources\WorkoutSessionResource\Pages;

use App\Filament\Admin\Resources\WorkoutSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkoutSession extends ViewRecord
{
    protected static string $resource = WorkoutSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // We add this just in case you want an "Edit" button on this page
            // Actions\EditAction::make(),
        ];
    }
}
