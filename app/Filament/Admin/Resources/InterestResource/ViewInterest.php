<?php

namespace App\Filament\Admin\Resources\InterestResource\Pages;

use App\Filament\Admin\Resources\InterestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInterest extends ViewRecord
{
    protected static string $resource = InterestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Adds an "Edit" button to the top of the View page
            Actions\EditAction::make(),
        ];
    }
}
