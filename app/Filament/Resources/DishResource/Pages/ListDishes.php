<?php

namespace App\Filament\Resources\DishResource\Pages;

use App\Filament\Resources\DishResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDishes extends ListRecords
{
    protected static string $resource = DishResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
