<?php

namespace App\Filament\Resources\ObjekResource\Pages;

use App\Filament\Resources\ObjekResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListObjeks extends ListRecords
{
    protected static string $resource = ObjekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
