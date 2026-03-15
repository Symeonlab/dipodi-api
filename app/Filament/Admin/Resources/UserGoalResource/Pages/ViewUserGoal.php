<?php

namespace App\Filament\Admin\Resources\UserGoalResource\Pages;

use App\Filament\Admin\Resources\UserGoalResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUserGoal extends ViewRecord
{
    protected static string $resource = UserGoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
