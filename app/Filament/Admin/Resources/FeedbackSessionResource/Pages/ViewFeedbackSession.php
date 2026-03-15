<?php

namespace App\Filament\Admin\Resources\FeedbackSessionResource\Pages;

use App\Filament\Admin\Resources\FeedbackSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFeedbackSession extends ViewRecord
{
    protected static string $resource = FeedbackSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
