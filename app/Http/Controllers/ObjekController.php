<?php

namespace App\Http\Controllers;

use App\Models\Objek;
use Illuminate\Http\JsonResponse;

class ObjekController extends Controller
{
    /**
     * Mengambil daftar objek beserta gambar-gambarnya.
     *
     * @return JsonResponse
     */
    public function getObjekList(): JsonResponse
    {
        // Mengambil semua objek beserta relasi imageable untuk mendapatkan gambar
        $objekList = Objek::with('imageable')->get();

        // Memetakan hasil data agar sesuai dengan struktur DataListRekomendasi
        $dataList = $objekList->map(function ($objek) {
            return [
                'img' => $objek->imageable->map(function ($image) {
                        return [
                            'img_path' => $image->img_path,
                        ];
                    }),
                'nama' => $objek->nama_objek,
                'deskripsi' => $objek->deskripsi,
                'link' => $objek->link_maps,
            ];
        });

        // Mengembalikan respons JSON dengan struktur yang sesuai
        return response()->json($dataList);
    }
}
