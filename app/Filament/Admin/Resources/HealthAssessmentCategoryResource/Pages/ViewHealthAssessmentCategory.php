<?php

namespace App\Filament\Admin\Resources\HealthAssessmentCategoryResource\Pages;

use App\Filament\Admin\Resources\HealthAssessmentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewHealthAssessmentCategory extends ViewRecord
{
    protected static string $resource = HealthAssessmentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
