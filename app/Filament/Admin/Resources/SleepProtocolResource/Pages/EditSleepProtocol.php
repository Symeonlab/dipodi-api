<?php

namespace App\Filament\Admin\Resources\SleepProtocolResource\Pages;

use App\Filament\Admin\Resources\SleepProtocolResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSleepProtocol extends EditRecord
{
    protected static string $resource = SleepProtocolResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
