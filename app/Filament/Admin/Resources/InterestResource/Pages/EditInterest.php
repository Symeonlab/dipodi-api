<?php

namespace App\Filament\Admin\Resources\InterestResource\Pages;

use App\Filament\Admin\Resources\InterestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInterest extends EditRecord
{
    protected static string $resource = InterestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
