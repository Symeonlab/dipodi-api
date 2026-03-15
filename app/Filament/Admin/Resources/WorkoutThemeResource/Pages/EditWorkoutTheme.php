<?php

namespace App\Filament\Admin\Resources\WorkoutThemeResource\Pages;

use App\Filament\Admin\Resources\WorkoutThemeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkoutTheme extends EditRecord
{
    protected static string $resource = WorkoutThemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
