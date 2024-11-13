<?php

namespace App\Filament\Resources\ImageableResource\Pages;

use App\Filament\Resources\ImageableResource;
use App\Models\Imageable;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CreateImageable extends CreateRecord
{
    protected static string $resource = ImageableResource::class;
}
