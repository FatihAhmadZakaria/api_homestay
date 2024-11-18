<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\Properti;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function storeReservasi(Request $request)
{
    // Hapus karakter selain angka untuk memastikan format ddmmyyyy
    $tglMulai = preg_replace('/[^0-9]/', '', $request->tgl_mulai);
    $tglSelesai = preg_replace('/[^0-9]/', '', $request->tgl_selesai);

    // Pastikan formatnya benar setelah dihapus tanda selain angka
    if (strlen($tglMulai) !== 8 || strlen($tglSelesai) !== 8) {
        return response()->json([
            'success' => false,
            'message' => 'Tanggal harus dalam format ddmmyyyy.'
        ], 400);
    }

    // Ubah format dari ddmmyyyy ke yyyy-mm-dd
    try {
        $tglMulai = Carbon::createFromFormat('dmY', $tglMulai)->format('Y-m-d');
        $tglSelesai = Carbon::createFromFormat('dmY', $tglSelesai)->format('Y-m-d');
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Format tanggal tidak valid.',
            'error' => $e->getMessage(),
        ], 400);
    }

    // Validasi tipe produk
    $validator = Validator::make($request->all(), [
        'id_user' => 'required|exists:users,id',
        'id_produk' => 'required', // Produk akan divalidasi lebih lanjut
        'tipe_produk' => 'required|in:properti,kendaraan', // Tipe produk
        'tgl_mulai' => 'required|date|after_or_equal:today',
        'tgl_selesai' => 'required|date|after:tgl_mulai',
        'jumlah_pesan' => 'required|integer|min:1',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 400);
    }

    // Validasi keberadaan produk berdasarkan tipe
    $produk = null;
    $hargaProduk = 0;
    if ($request->tipe_produk === 'properti') {
        $produk = Properti::find($request->id_produk);
        if ($produk) {
            $hargaProduk = $produk->harga;
        }
    } elseif ($request->tipe_produk === 'kendaraan') {
        $produk = Kendaraan::find($request->id_produk);
        if ($produk) {
            $hargaProduk = $produk->harga;
        }
    }

    if (!$produk) {
        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan.'
        ], 404);
    }

    // Menghitung total harga
    $totalHarga = $hargaProduk * $request->jumlah_pesan;

    // Simpan data reservasi
    $reservasi = Reservasi::create([
        'id_user' => $request->id_user,
        'id_produk' => $request->id_produk,
        'tgl_transaksi' => now(),
        'status_reservasi' => 'pending',
        'tgl_mulai' => $tglMulai, // Menggunakan format yang benar
        'tgl_selesai' => $tglSelesai, // Menggunakan format yang benar
        'jumlah_pesan' => $request->jumlah_pesan,
        'total_harga' => $totalHarga,
        'tipe_produk' => $request->tipe_produk === 'properti' ? Properti::class : Kendaraan::class,
    ]);

    $idReservasi = $reservasi->id_reservasi;

    return response()->json([
        'success' => true,
        'message' => 'Reservasi berhasil dibuat.',
        'id_reservasi' => $idReservasi
    ], 201);
}

}
