<?php

namespace App\Filament\Admin\Resources\BonusWorkoutRuleResource\Pages;

use App\Filament\Admin\Resources\BonusWorkoutRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBonusWorkoutRule extends ViewRecord
{
    protected static string $resource = BonusWorkoutRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
