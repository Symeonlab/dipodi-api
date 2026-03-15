<?php

namespace App\Filament\Admin\Resources\FeedbackCategoryResource\Pages;

use App\Filament\Admin\Resources\FeedbackCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeedbackCategory extends EditRecord
{
    protected static string $resource = FeedbackCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
