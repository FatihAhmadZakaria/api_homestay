<?php

namespace App\Http\Controllers;

use App\Models\Properti;
use Illuminate\Http\Request;

class PropertiController extends Controller
{
    public function index()
    {
        // Mengambil data properti dengan semua gambar terkait
        $properties = Properti::with('imageable')->get();

        // Menyusun data yang akan ditampilkan
        $data = $properties->map(function ($properti) {
            return [
                'nama' => $properti->nama_properti,
                'deskripsi' => $properti->deskripsi,
                'fitur' => $properti->fitur,
                'kapasitas' => $properti->kapasitas,
                'harga' => $properti->harga,
                'images' => $properti->imageable->map(function ($image) {
                    return basename($image->img_path); // Mengambil hanya nama file
                })->all(),
            ];
        });

        return response()->json($data);
    }
}
