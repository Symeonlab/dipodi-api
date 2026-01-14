<?php

namespace App\Filament\Admin\Resources\InterestResource\Pages;

use App\Filament\Admin\Resources\InterestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInterests extends ListRecords
{
    protected static string $resource = InterestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
