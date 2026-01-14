<?php

namespace App\Filament\Admin\Resources\NutritionAdviceResource\Pages;

use App\Filament\Admin\Resources\NutritionAdviceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNutritionAdvice extends ListRecords
{
    protected static string $resource = NutritionAdviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
