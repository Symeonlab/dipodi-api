<?php

namespace App\Filament\Admin\Resources\FeedbackSessionResource\Pages;

use App\Filament\Admin\Resources\FeedbackSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeedbackSessions extends ListRecords
{
    protected static string $resource = FeedbackSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            FeedbackSessionResource\Widgets\FeedbackStatsOverview::class,
        ];
    }
}
