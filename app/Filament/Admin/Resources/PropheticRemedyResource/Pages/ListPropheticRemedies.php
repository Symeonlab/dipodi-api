<?php

namespace App\Filament\Admin\Resources\PropheticRemedyResource\Pages;

use App\Filament\Admin\Resources\PropheticRemedyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropheticRemedies extends ListRecords
{
    protected static string $resource = PropheticRemedyResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
