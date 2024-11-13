<?php

namespace App\Filament\Resources\PropertiResource\Pages;

use App\Filament\Resources\PropertiResource;
use App\Models\Imageable;
use App\Models\Properti;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateProperti extends CreateRecord
{
    protected static string $resource = PropertiResource::class;

    // Override method untuk menangani proses pembuatan record
    protected function handleRecordCreation(array $data): Properti
    {
        try {
            // Simpan data ke tabel 'properti'
            $properti = Properti::create([
                'nama_properti' => $data['nama_properti'],
                'kapasitas' => $data['kapasitas'],
                'fitur' => $data['fitur'],
                'harga' => $data['harga'],
                'deskripsi' => $data['deskripsi'],
                'jumlah' => $data['jumlah'],
            ]);
            return $properti; // Kembalikan objek Properti
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();
            throw $e; // Lempar error jika ada masalah
        }
    }
}
