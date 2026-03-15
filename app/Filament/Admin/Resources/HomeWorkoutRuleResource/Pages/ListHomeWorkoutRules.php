<?php

namespace App\Filament\Admin\Resources\HomeWorkoutRuleResource\Pages;

use App\Filament\Admin\Resources\HomeWorkoutRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomeWorkoutRules extends ListRecords
{
    protected static string $resource = HomeWorkoutRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
