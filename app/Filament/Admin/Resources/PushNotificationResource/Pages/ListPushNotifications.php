<?php

namespace App\Filament\Admin\Resources\PushNotificationResource\Pages;

use App\Filament\Admin\Resources\PushNotificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPushNotifications extends ListRecords
{
    protected static string $resource = PushNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
