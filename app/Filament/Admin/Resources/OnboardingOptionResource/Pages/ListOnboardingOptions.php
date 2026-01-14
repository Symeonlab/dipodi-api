<?php

namespace App\Filament\Admin\Resources\OnboardingOptionResource\Pages;

use App\Filament\Admin\Resources\OnboardingOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOnboardingOptions extends ListRecords
{
    protected static string $resource = OnboardingOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
