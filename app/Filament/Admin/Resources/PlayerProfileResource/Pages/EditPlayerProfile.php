<?php

namespace App\Filament\Admin\Resources\PlayerProfileResource\Pages;

use App\Filament\Admin\Resources\PlayerProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlayerProfile extends EditRecord
{
    protected static string $resource = PlayerProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
