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
                'id' => $properti->id_properti,
                'img' => $properti->imageable->map(function ($image) {
                        return [
                            'img_path' => $image->img_path,
                        ];
                    }),
                'nama' => $properti->nama_properti,
                'deskrip' => $properti->deskripsi,
                'fitur' => $properti->fitur,
                'kapasitas' => $properti->kapasitas,
                'harga' => $properti->harga,
            ];
        });

        return response()->json($data);
    }
}
