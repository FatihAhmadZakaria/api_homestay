<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImageableResource\Pages;
use App\Filament\Resources\ImageableResource\RelationManagers;
use App\Models\Imageable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ImageableResource extends Resource
{
    protected static ?string $model = Imageable::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = 'imageables'; // URL slug

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('img_path'),
                Tables\Columns\TextColumn::make('imagable_id'),
                Tables\Columns\TextColumn::make('imagable_type'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('img_path')->required(),
                Forms\Components\TextInput::make('imagable_id')->required(),
                Forms\Components\TextInput::make('imagable_type')->required(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImageables::route('/'),
            'create' => Pages\CreateImageable::route('/create'),
            'edit' => Pages\EditImageable::route('/{record}/edit'),
        ];
    }
}