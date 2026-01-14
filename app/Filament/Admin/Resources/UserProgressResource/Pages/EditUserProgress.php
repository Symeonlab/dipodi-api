<?php

namespace App\Filament\Admin\Resources\UserProgressResource\Pages;

use App\Filament\Admin\Resources\UserProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserProgress extends EditRecord
{
    protected static string $resource = UserProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
