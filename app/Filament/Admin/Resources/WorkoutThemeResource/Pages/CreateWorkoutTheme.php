<?php

namespace App\Filament\Admin\Resources\WorkoutThemeResource\Pages;

use App\Filament\Admin\Resources\WorkoutThemeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkoutTheme extends CreateRecord
{
    protected static string $resource = WorkoutThemeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
