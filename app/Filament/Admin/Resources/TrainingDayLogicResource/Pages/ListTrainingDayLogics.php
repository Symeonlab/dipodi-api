<?php

namespace App\Filament\Admin\Resources\TrainingDayLogicResource\Pages;

use App\Filament\Admin\Resources\TrainingDayLogicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingDayLogics extends ListRecords
{
    protected static string $resource = TrainingDayLogicResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
