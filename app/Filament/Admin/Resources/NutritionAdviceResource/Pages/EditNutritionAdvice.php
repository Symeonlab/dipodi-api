<?php

namespace App\Filament\Admin\Resources\NutritionAdviceResource\Pages;

use App\Filament\Admin\Resources\NutritionAdviceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNutritionAdvice extends EditRecord
{
    protected static string $resource = NutritionAdviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
