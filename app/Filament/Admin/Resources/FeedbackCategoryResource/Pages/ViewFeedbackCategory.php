<?php

namespace App\Filament\Admin\Resources\FeedbackCategoryResource\Pages;

use App\Filament\Admin\Resources\FeedbackCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFeedbackCategory extends ViewRecord
{
    protected static string $resource = FeedbackCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
