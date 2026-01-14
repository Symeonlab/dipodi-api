<?php

namespace App\Filament\Admin\Resources\NutritionAdviceResource\Pages;

use App\Filament\Admin\Resources\NutritionAdviceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNutritionAdvice extends ViewRecord
{
    protected static string $resource = NutritionAdviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Allows you to jump to Edit mode from the View page
            Actions\EditAction::make(),
        ];
    }
}
