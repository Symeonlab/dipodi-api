<?php

namespace App\Filament\Admin\Resources\ChronotypeResource\Pages;

use App\Filament\Admin\Resources\ChronotypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChronotype extends EditRecord
{
    protected static string $resource = ChronotypeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
