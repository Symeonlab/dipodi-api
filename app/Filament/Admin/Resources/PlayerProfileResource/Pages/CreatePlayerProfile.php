<?php

namespace App\Filament\Admin\Resources\PlayerProfileResource\Pages;

use App\Filament\Admin\Resources\PlayerProfileResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlayerProfile extends CreateRecord
{
    protected static string $resource = PlayerProfileResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
