<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        // Mengambil semua data promo beserta gambar terkait menggunakan eager loading
        $promos = Promo::with('imageable')->get();

        // Mengembalikan data dalam bentuk JSON
        return response()->json(
            $promos->map(function ($promo) {
                return [
                    'img' => $promo->imageable->map(function ($image) {
                        return [
                            'img_path' => $image->img_path,
                        ];
                    }),
                    'nama' => $promo->nama_promo,
                    'deskripsi' => $promo->deskripsi,
                ];
            })
        );
    }
}
