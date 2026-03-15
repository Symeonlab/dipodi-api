<?php

namespace App\Filament\Admin\Resources\PropheticRemedyResource\Pages;

use App\Filament\Admin\Resources\PropheticRemedyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropheticRemedy extends EditRecord
{
    protected static string $resource = PropheticRemedyResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
