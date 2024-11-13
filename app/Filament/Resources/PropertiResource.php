<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertiResource\Pages;
use App\Filament\Resources\PropertiResource\RelationManagers;
use App\Models\Properti;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropertiResource extends Resource
{
    protected static ?string $model = Properti::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_properti'),
                Tables\Columns\TextColumn::make('kapasitas'),
                Tables\Columns\TextColumn::make('harga'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('nama_properti')
                ->required(),
            Forms\Components\TextInput::make('kapasitas')
                ->numeric()
                ->required(),
            Forms\Components\Textarea::make('fitur')
                ->required(),
            Forms\Components\TextInput::make('harga')
                ->numeric()
                ->required(),
            Forms\Components\Textarea::make('deskripsi')
                ->required(),
            Forms\Components\TextInput::make('jumlah')
                ->numeric()
                ->required(),
        ]);
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPropertis::route('/'),
            'create' => Pages\CreateProperti::route('/create'),
            'edit' => Pages\EditProperti::route('/{record}/edit'),
        ];
    }
}