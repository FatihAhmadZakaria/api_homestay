<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index()
    {
        // Mengambil data kendaraan dengan semua gambar terkait
        $kendaraan = Kendaraan::with('imageable')->get();

        // Menyusun data yang akan ditampilkan
        $data = $kendaraan->map(function ($kendaraan) {
            return [
                'id' => $kendaraan->id_kendaraan,
                'img' => $kendaraan->imageable->map(function ($image) {
                    return [
                        'img_path' => $image->img_path, // Gambar yang terkait
                    ];
                }),
                'nama' => $kendaraan->nama_kendaraan,
                'harga' => $kendaraan->harga,
                'deskrip' => $kendaraan->deskripsi,
            ];
        });

        return response()->json($data);
    }
}
