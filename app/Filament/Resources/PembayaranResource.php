<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Filament\Resources\PembayaranResource\RelationManagers;
use App\Models\Pembayaran;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected function getTableQuery(): Builder
    {
        return Pembayaran::with('admin'); // Eager load admin saat mengambil data pembayaran
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tgl_pembayaran')
                    ->label('Tanggal Pembayaran')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('admin.name')
                    ->label('Nama Admin')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jumlah_dp')
                    ->label('Jumlah DP')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('jumlah_pelunasan')
                    ->label('Jumlah Pelunasan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payment_type')
                    ->label('Jenis Pembayaran')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                // DatePicker::make('tgl_pembayaran')
                //     ->label('Tanggal Pembayaran')
                //     ->required(),
                // TextInput::make('jumlah_dp')
                //     ->label('Jumlah DP')
                //     ->numeric()
                //     ->required(),
                // TextInput::make('jumlah_pelunasan')
                //     ->label('Jumlah Pelunasan')
                //     ->numeric()
                //     ->required(),
                // Select::make('id_admin')
                //     ->label('Nama Admin')
                //     ->relationship('admin', 'nama') // Asumsi kolom `nama` ada di tabel `admin`
                //     ->required(),
                // TextInput::make('payment_type')
                //     ->label('Jenis Pembayaran')
                //     ->required(),
                // TextInput::make('status_pembayaran')
                //     ->label('Status Pembayaran')
                //     ->required(),
                // TextInput::make('snap_token')
                //     ->label('Snap Token'),
            ]);
    }

    public static function canDelete(Model $record): bool
    {
        // Logika untuk pengecekan jika record dapat dihapus
        return true;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}