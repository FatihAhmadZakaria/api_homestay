<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObjekResource\Pages;
use App\Models\Objek;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ObjekResource extends Resource
{
    protected static ?string $model = Objek::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_objek')->label('Nama Objek'),
                Tables\Columns\TextColumn::make('deskripsi')->label('Deskripsi'),
                Tables\Columns\TextColumn::make('link_maps')->label('Link Maps'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_objek')
                    ->label('Nama Objek')
                    ->required(),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->required()
                    ->maxLength(1000),

                Forms\Components\TextInput::make('link_maps')
                    ->label('Link Maps')
                    ->url()
                    ->nullable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListObjeks::route('/'),
            'create' => Pages\CreateObjek::route('/create'),
            'edit' => Pages\EditObjek::route('/{record}/edit'),
        ];
    }
}
