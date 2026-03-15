<?php

namespace App\Filament\Admin\Resources\UserGoalResource\Pages;

use App\Filament\Admin\Resources\UserGoalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserGoal extends EditRecord
{
    protected static string $resource = UserGoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
