<?php

namespace App\Http\Controllers;

use App\Models\Properti;
use App\Models\Kendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Menyimpan data Properti atau Kendaraan beserta gambar.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'type' => 'required|string|in:properti,kendaraan',
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Cek tipe produk
        if ($request->type === 'properti') {
            // Membuat Properti
            $product = Properti::create([
                'nama_properti' => $request->nama,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
            ]);
        } elseif ($request->type === 'kendaraan') {
            // Membuat Kendaraan
            $product = Kendaraan::create([
                'nama_k' => $request->nama,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
            ]);
        }

        // Meng-upload dan menyimpan gambar ke storage lokal dan database
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('public/images'); // menyimpan gambar ke folder storage/app/public/images
            $imgPath = Storage::url($path); // mendapatkan URL publik dari gambar

            // Menyimpan informasi gambar ke tabel imageable
            $product->images()->create([
                'img_path' => $imgPath,
            ]);
        }

        return response()->json([
            'message' => 'Data produk beserta gambar berhasil disimpan.',
            'product' => $product,
        ], 201);
    }
}
