<?php

namespace App\Filament\Admin\Resources\WorkoutThemeRuleResource\Pages;

use App\Filament\Admin\Resources\WorkoutThemeRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkoutThemeRule extends EditRecord
{
    protected static string $resource = WorkoutThemeRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
