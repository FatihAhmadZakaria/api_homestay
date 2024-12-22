<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KendaraanResource\Pages;
use App\Models\Kendaraan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KendaraanResource extends Resource
{
    protected static ?string $model = Kendaraan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kendaraan')->label('Nama Kendaraan'),
                Tables\Columns\TextColumn::make('plat_nomor')->label('Plat Nomor'),
                Tables\Columns\TextColumn::make('tipe_kendaraan')->label('Tipe Kendaraan'),
                Tables\Columns\TextColumn::make('harga')->label('Harga'),
                Tables\Columns\TextColumn::make('deskripsi')->label('Deskripsi'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kendaraan')
                    ->label('Nama Kendaraan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('plat_nomor')
                    ->label('Plat Nomor')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('tipe_kendaraan')
                    ->label('Tipe Kendaraan')
                    ->options([
                        'Matic' => 'Matic',
                        'Bebek' => 'Bebek',
                        'Manual' => 'Manual',
                        // tambahkan opsi lain sesuai kebutuhan
                    ])
                    ->required(),

                Forms\Components\TextInput::make('harga')
                    ->label('Harga')
                    ->numeric()
                    ->required(),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->maxLength(500),
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKendaraans::route('/'),
            'create' => Pages\CreateKendaraan::route('/create'),
            'edit' => Pages\EditKendaraan::route('/{record}/edit'),
        ];
    }
}
