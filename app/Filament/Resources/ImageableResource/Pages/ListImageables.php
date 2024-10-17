<?php

namespace App\Filament\Resources\ImageableResource\Pages;

use App\Filament\Resources\ImageableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImageables extends ListRecords
{
    protected static string $resource = ImageableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
