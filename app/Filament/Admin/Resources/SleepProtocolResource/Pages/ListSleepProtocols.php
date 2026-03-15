<?php

namespace App\Filament\Admin\Resources\SleepProtocolResource\Pages;

use App\Filament\Admin\Resources\SleepProtocolResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSleepProtocols extends ListRecords
{
    protected static string $resource = SleepProtocolResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
