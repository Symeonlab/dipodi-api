<?php

namespace App\Filament\Admin\Resources\FoodItemResource\Pages;

use App\Filament\Admin\Resources\FoodItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFoodItem extends CreateRecord
{
    protected static string $resource = FoodItemResource::class;
}
