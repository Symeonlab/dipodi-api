<?php

namespace App\Filament\Admin\Resources\FeedbackCategoryResource\Pages;

use App\Filament\Admin\Resources\FeedbackCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeedbackCategories extends ListRecords
{
    protected static string $resource = FeedbackCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
