<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembatalanResource\Pages;
use App\Filament\Resources\PembatalanResource\RelationManagers;
use App\Models\Pembatalan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class PembatalanResource extends Resource
{
    protected static ?string $model = Pembatalan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_reservasi'),
                Tables\Columns\TextColumn::make('status_refund'),
                Tables\Columns\TextColumn::make('id_admin'),
            ]);
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                // Schema form
            ]);
    }

    // public static function canDelete(Model $record): bool
    // {
    //     // Logika untuk pengecekan jika record dapat dihapus
    //     return true;
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembatalans::route('/'),
            'create' => Pages\CreatePembatalan::route('/create'),
            'edit' => Pages\EditPembatalan::route('/{record}/edit'),
        ];
    }
}