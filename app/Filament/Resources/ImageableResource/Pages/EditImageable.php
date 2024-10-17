<?php

namespace App\Filament\Resources\ImageableResource\Pages;

use App\Filament\Resources\ImageableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImageable extends EditRecord
{
    protected static string $resource = ImageableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
