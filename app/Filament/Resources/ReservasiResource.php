<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservasiResource\Pages;
use App\Models\Reservasi;
use App\Models\Properti;
use App\Models\Kendaraan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Facades\Log;

class ReservasiResource extends Resource
{
    protected static ?string $model = Reservasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_user')->label('ID User')->sortable(),
                Tables\Columns\TextColumn::make('produk.nama')->label('Nama Produk')->sortable()->getStateUsing(function ($record) {
                    if ($record->tipe_produk === Properti::class) {
                        return Properti::find($record->id_produk)->nama_properti ?? '-';
                    } elseif ($record->tipe_produk === Kendaraan::class) {
                        return Kendaraan::find($record->id_produk)->nama_kendaraan ?? '-';
                    }
                    return '-';
                }),
                Tables\Columns\TextColumn::make('tgl_transaksi')->label('Tanggal Transaksi')->sortable(),
                Tables\Columns\TextColumn::make('tgl_mulai')->label('Tanggal Mulai')->sortable(),
                Tables\Columns\TextColumn::make('tgl_selesai')->label('Tanggal Selesai')->sortable(),
                Tables\Columns\TextColumn::make('total_harga')->label('Total Harga')->sortable()->getStateUsing(function ($record) {
                    $produk = $record->tipe_produk === Properti::class ? Properti::find($record->id_produk) : Kendaraan::find($record->id_produk);
                    if ($produk) {
                        $durasi = now()->parse($record->tgl_mulai)->diffInDays(now()->parse($record->tgl_selesai)) + 1;
                        $hargaProduk = $produk->harga ?? 0;
                        return $hargaProduk * $record->jumlah_pesan * $durasi;
                    }
                    return 0;
                }),
                Tables\Columns\TextColumn::make('jumlah_pesan')->label('Jumlah Pesanan')->sortable(),
                Tables\Columns\TextColumn::make('status_reservasi')->label('Status Reservasi')->sortable(),
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
                Forms\Components\TextInput::make('id_user')->disabled()->label('ID User')->default(453120),
                Forms\Components\Select::make('tipe_produk')->label('Tipe Produk')->options([
                    Properti::class => 'Properti',
                    Kendaraan::class => 'Kendaraan',
                ])->reactive()->required(),
                Forms\Components\Select::make('id_produk')->label('Produk')->required()->options(function (callable $get) {
                    $tipeProduk = $get('tipe_produk');
                    if ($tipeProduk === Properti::class) {
                        return Properti::pluck('nama_properti', 'id_properti');
                    } elseif ($tipeProduk === Kendaraan::class) {
                        return Kendaraan::pluck('nama_kendaraan', 'id_kendaraan');
                    }
                    return [];
                })->hidden(fn (callable $get) => empty($get('tipe_produk'))),
                Forms\Components\DatePicker::make('tgl_mulai')->label('Tanggal Mulai')->required(),
                Forms\Components\DatePicker::make('tgl_selesai')->label('Tanggal Selesai')->required(),
                Forms\Components\TextInput::make('jumlah_pesan')->label('Jumlah Pesanan')->numeric()->required()
                ->minValue(1)->maxValue(function (callable $get) {
                    return $get('tipe_produk') === Kendaraan::class ? 1 : null;
                }),
                Forms\Components\TextInput::make('status_reservasi')
                ->options([
                    'pending' => 'Pending',
                    'selesai' => 'Selesai',
                    'tercatat' => 'Catat',
                ])
                ->label('Status Reservasi')
                ->required(),
            ]);
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
