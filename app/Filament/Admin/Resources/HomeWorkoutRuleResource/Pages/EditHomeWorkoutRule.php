<?php

namespace App\Filament\Admin\Resources\HomeWorkoutRuleResource\Pages;

use App\Filament\Admin\Resources\HomeWorkoutRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHomeWorkoutRule extends EditRecord
{
    protected static string $resource = HomeWorkoutRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
