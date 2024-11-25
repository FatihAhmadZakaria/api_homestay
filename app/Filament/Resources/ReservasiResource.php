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
                Tables\Columns\TextColumn::make('produk.nama')->label('Nama Produk')->sortable(),
                Tables\Columns\TextColumn::make('tgl_mulai')->label('Tanggal Mulai')->sortable(),
                Tables\Columns\TextColumn::make('tgl_selesai')->label('Tanggal Selesai')->sortable(),
                Tables\Columns\TextColumn::make('total_harga')->label('Total Harga')->sortable(),
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
                Forms\Components\TextInput::make('id_user')
                    ->disabled()
                    ->label('ID User')
                    ->default(453120),

                Forms\Components\Select::make('tipe_produk')
                    ->label('Tipe Produk')
                    ->options([
                        'App\Models\Properti' => 'Properti',
                        'App\Models\Kendaraan' => 'Kendaraan',
                    ])
                    ->reactive()
                    ->required(),

                Forms\Components\Select::make('id_produk')
                    ->label('Produk')
                    ->required()
                    ->options(function (callable $get) {
                        $tipeProduk = $get('tipe_produk');
                        if ($tipeProduk === Properti::class) {
                            return Properti::pluck('nama_properti', 'id_properti');
                        } elseif ($tipeProduk === Kendaraan::class) {
                            return Kendaraan::pluck('nama_kendaraan', 'id_kendaraan');
                        }
                        return [];
                    })
                    ->hidden(fn (callable $get) => empty($get('tipe_produk'))),

                Forms\Components\DatePicker::make('tgl_mulai')
                    ->label('Tanggal Mulai')
                    ->required(),

                Forms\Components\DatePicker::make('tgl_selesai')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->reactive()  // Menjadikan field ini reaktif
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // Log ketika tanggal selesai diubah
                        Log::info('Tanggal Selesai Dipilih:', [
                            'tgl_selesai' => $state,
                        ]);
                
                        // Lakukan perhitungan harga atau logika lainnya jika diperlukan
                        $tglMulai = $get('tgl_mulai');
                        $tglSelesai = $state;
                        $jumlahPesan = $get('jumlah_pesan');
                        $idProduk = $get('id_produk');
                        $tipeProduk = $get('tipe_produk');
                
                        // Pastikan semua field yang diperlukan sudah ada
                        if ($tglMulai && $tglSelesai && $jumlahPesan && $idProduk) {
                            // Hitung durasi dalam hari
                            $durasi = now()->parse($tglSelesai)->diffInDays(now()->parse($tglMulai)) + 1;
                
                            // Ambil harga produk berdasarkan tipe
                            $hargaProduk = 0;
                            $produk = null;
                
                            // Cek tipe produk
                            if ($tipeProduk === 'App\Models\Properti') {
                                $produk = Properti::find($idProduk);
                            } elseif ($tipeProduk === 'App\Models\Kendaraan') {
                                $produk = Kendaraan::find($idProduk);
                            }
                
                            // Log untuk memverifikasi produk yang ditemukan
                            if ($produk) {
                                $hargaProduk = $produk->harga ?? 0;
                                Log::info('Harga Produk:', ['harga' => $hargaProduk]);
                            }
                
                            // Jika harga produk ditemukan dan valid
                            if ($hargaProduk > 0) {
                                // Hitung total harga
                                $set('total_harga', $durasi * $hargaProduk * $jumlahPesan);
                            } else {
                                $set('total_harga', null);
                                session()->flash('error', 'Produk tidak ditemukan atau harga tidak tersedia.');
                            }
                        }
                    }),
                

                Forms\Components\TextInput::make('jumlah_pesan')
                    ->label('Jumlah Pesanan')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('total_harga')
                    ->label('Total Harga')
                    ->numeric()
                    ->disabled()
                    ->default(0)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // Log awal untuk memastikan fungsi berjalan
                        Log::info('Total Harga - afterStateUpdated Dipanggil:', [
                            'state' => $state,
                            'tgl_mulai' => $get('tgl_mulai'),
                            'tgl_selesai' => $get('tgl_selesai'),
                            'jumlah_pesan' => $get('jumlah_pesan'),
                            'id_produk' => $get('id_produk'),
                            'tipe_produk' => $get('tipe_produk')
                        ]);
                
                        $tglMulai = $get('tgl_mulai');
                        $tglSelesai = $get('tgl_selesai');
                        $jumlahPesan = $get('jumlah_pesan');
                        $idProduk = $get('id_produk');
                        $tipeProduk = $get('tipe_produk');
                
                        // Pastikan semua field yang diperlukan sudah ada
                        if ($tglMulai && $tglSelesai && $jumlahPesan && $idProduk) {
                            // Hitung durasi dalam hari
                            $durasi = now()->parse($tglMulai)->diffInDays(now()->parse($tglSelesai)) + 1;
                
                            // Log durasi untuk memverifikasi
                            Log::info('Durasi dalam hari: ', ['durasi' => $durasi]);
                
                            // Cek apakah durasi valid (lebih dari 0)
                            if ($durasi <= 0) {
                                Log::warning('Durasi tidak valid (negatif atau nol): ', ['durasi' => $durasi]);
                                $set('total_harga', null); // Jangan hitung harga jika durasi tidak valid
                                return;  // Keluar dari fungsi jika durasi tidak valid
                            }
                
                            // Ambil harga produk berdasarkan tipe
                            $hargaProduk = 0;
                            $produk = null;
                
                            // Cek tipe produk
                            if ($tipeProduk === 'App\Models\Properti') {
                                $produk = Properti::find($idProduk);
                                Log::info('Produk Properti ditemukan:', ['produk' => $produk]);
                            } elseif ($tipeProduk === 'App\Models\Kendaraan') {
                                $produk = Kendaraan::find($idProduk);
                                Log::info('Produk Kendaraan ditemukan:', ['produk' => $produk]);
                            }
                
                            // Log untuk memverifikasi harga produk yang ditemukan
                            if ($produk) {
                                $hargaProduk = $produk->harga ?? 0;
                                Log::info('Harga Produk:', ['harga' => $hargaProduk]);
                            } else {
                                Log::warning('Produk tidak ditemukan dengan ID: ' . $idProduk);
                            }
                
                            // Jika harga produk ditemukan dan valid
                            if ($hargaProduk > 0) {
                                // Hitung total harga
                                $totalHarga = $hargaProduk * $jumlahPesan * $du;
                                Log::info('Total Harga Dihitung: ', ['total_harga' => $totalHarga]);
                
                                // Pastikan total harga tidak negatif
                                if ($totalHarga < 0) {
                                    Log::warning('Total Harga Negatif! Mengatur total_harga ke 0.');
                                    $set('total_harga', 0);
                                } else {
                                    // Set total harga
                                    $set('total_harga', $totalHarga);
                                }
                            } else {
                                $set('total_harga', null);
                                session()->flash('error', 'Produk tidak ditemukan atau harga tidak tersedia.');
                            }
                        } else {
                            // Log jika ada field yang tidak terisi
                            Log::warning('Field tidak lengkap, total_harga tidak dihitung');
                            $set('total_harga', null);
                        }
                    }),
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
