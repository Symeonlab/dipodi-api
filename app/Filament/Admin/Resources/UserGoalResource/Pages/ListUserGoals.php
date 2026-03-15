<?php

namespace App\Filament\Admin\Resources\UserGoalResource\Pages;

use App\Filament\Admin\Resources\UserGoalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserGoals extends ListRecords
{
    protected static string $resource = UserGoalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
