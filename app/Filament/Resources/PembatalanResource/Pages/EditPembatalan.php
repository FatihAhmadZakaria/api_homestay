<?php

namespace App\Filament\Resources\PembatalanResource\Pages;

use App\Filament\Resources\PembatalanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembatalan extends EditRecord
{
    protected static string $resource = PembatalanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
