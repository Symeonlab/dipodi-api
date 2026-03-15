<?php

namespace App\Filament\Admin\Resources\PushNotificationResource\Pages;

use App\Filament\Admin\Resources\PushNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPushNotification extends ViewRecord
{
    protected static string $resource = PushNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
