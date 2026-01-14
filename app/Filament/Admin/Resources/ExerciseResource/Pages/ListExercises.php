<?php

namespace App\Filament\Admin\Resources\ExerciseResource\Pages;

use App\Filament\Admin\Resources\ExerciseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExercises extends ListRecords
{
    protected static string $resource = ExerciseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
