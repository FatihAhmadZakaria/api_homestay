<?php

namespace App\Filament\Resources\PembatalanResource\Pages;

use App\Filament\Resources\PembatalanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembatalans extends ListRecords
{
    protected static string $resource = PembatalanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
