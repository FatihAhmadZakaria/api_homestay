<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class RiwayatController extends Controller
{
    public function index($id_user)
    {
        if (!$id_user) {
            return response()->json([
                'message' => 'ID pengguna tidak ditemukan.',
            ], 400);
        }

        // Query builder untuk mendapatkan data
        $reservasi = DB::table('reservasi as r')
            ->leftJoin('properti as p', function ($join) {
                $join->on('r.id_produk', '=', 'p.id_properti')
                    ->where('r.tipe_produk', '=', 'App\\Models\\Properti');
            })
            ->leftJoin('kendaraan as k', function ($join) {
                $join->on('r.id_produk', '=', 'k.id_kendaraan')
                    ->where('r.tipe_produk', '=', 'App\\Models\\Kendaraan');
            })
            ->leftJoin('pembayaran as pb', 'r.id_reservasi', '=', 'pb.id_reservasi')
            ->select(
                DB::raw("
                    CASE
                        WHEN r.tipe_produk = 'App\\\\Models\\\\Properti' THEN p.nama_properti
                        WHEN r.tipe_produk = 'App\\\\Models\\\\Kendaraan' THEN k.nama_kendaraan
                        ELSE 'Produk Tidak Ditemukan'
                    END AS nama_produk
                "),
                DB::raw("
                    CASE
                        WHEN r.tipe_produk = 'App\\\\Models\\\\Properti' THEN 'Properti'
                        WHEN r.tipe_produk = 'App\\\\Models\\\\Kendaraan' THEN 'Kendaraan'
                        ELSE 'Tipe Tidak Dikenal'
                    END AS jenis
                "),
                DB::raw("COALESCE(pb.status_pembayaran, 'Belum Dibayar') AS status"),
                'r.total_harga AS total',
                'r.tgl_mulai',
                'r.tgl_selesai',
                'r.tgl_transaksi'
            )
            ->where('r.id_user', $id_user)
            ->get();

        return response()->json([
            'data' => $reservasi,
            'message' => 'Data transaksi berhasil diambil.',
        ]);
    }
}
