<?php

namespace App\Filament\Resources\ObjekResource\Pages;

use App\Filament\Resources\ObjekResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditObjek extends EditRecord
{
    protected static string $resource = ObjekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
