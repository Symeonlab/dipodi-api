<?php

namespace App\Filament\Admin\Resources\HealthAssessmentCategoryResource\Pages;

use App\Filament\Admin\Resources\HealthAssessmentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHealthAssessmentCategory extends EditRecord
{
    protected static string $resource = HealthAssessmentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
