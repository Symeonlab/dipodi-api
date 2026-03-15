<?php

namespace App\Filament\Admin\Resources\IntensityZoneResource\Pages;

use App\Filament\Admin\Resources\IntensityZoneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIntensityZone extends EditRecord
{
    protected static string $resource = IntensityZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
