<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObjekResource\Pages;
use App\Filament\Resources\ObjekResource\RelationManagers;
use App\Models\Objek;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObjekResource extends Resource
{
    protected static ?string $model = Objek::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_objek'),
                Tables\Columns\TextColumn::make('dekripsi'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             // form schema untuk objek
    //         ]);
    // }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListObjeks::route('/'),
            'create' => Pages\CreateObjek::route('/create'),
            'edit' => Pages\EditObjek::route('/{record}/edit'),
        ];
    }
}