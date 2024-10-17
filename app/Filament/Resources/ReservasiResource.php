<?php

namespace App\Filament\Resources;


use App\Filament\Resources\ReservasiResource\Pages;
use App\Filament\Resources\ReservasiResource\RelationManagers;
use App\Models\Reservasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class ReservasiResource extends Resource
{
    protected static ?string $model = Reservasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_user'),
                Tables\Columns\TextColumn::make('tgl_mulai'),
                Tables\Columns\TextColumn::make('tgl_selesai'),
                Tables\Columns\TextColumn::make('total_harga'),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // form schema untuk update reservasi
            ]);
    }

    public static function canCreate(): bool
    {
        return false; // Disable create
    }

    public static function canDelete(Model $record): bool
    {
        // Logika untuk pengecekan jika record dapat dihapus
        return true;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservasis::route('/'),
            'create' => Pages\CreateReservasi::route('/create'),
            'edit' => Pages\EditReservasi::route('/{record}/edit'),
        ];
    }
}