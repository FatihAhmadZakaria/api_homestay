<?php

namespace App\Filament\Resources\PropertiResource\Pages;

use App\Filament\Resources\PropertiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertis extends ListRecords
{
    protected static string $resource = PropertiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
