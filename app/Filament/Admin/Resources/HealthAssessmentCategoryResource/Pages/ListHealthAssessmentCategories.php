<?php

namespace App\Filament\Admin\Resources\HealthAssessmentCategoryResource\Pages;

use App\Filament\Admin\Resources\HealthAssessmentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHealthAssessmentCategories extends ListRecords
{
    protected static string $resource = HealthAssessmentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
