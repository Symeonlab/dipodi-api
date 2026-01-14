<?php

namespace App\Filament\Admin\Resources\UserProgressResource\Pages;

use App\Filament\Admin\Resources\UserProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord; // <-- This should be ViewRecord

class ViewUserProgress extends ViewRecord // <-- The class name must be ViewUserProgress
{
    protected static string $resource = UserProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
