<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Properti;
use App\Models\Kendaraan;
use App\Models\Reservasi;
use Carbon\Carbon;
use Log;
use Validator;

class KetersediaanController extends Controller
{
    /**
     * Cek ketersediaan properti berdasarkan tanggal.
     */
    public function cekKetersediaan(Request $request, $idProperti)
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

    // Validasi input menggunakan Validator
    $validator = Validator::make($request->all(), [
        'tgl_mulai' => 'required|date|after_or_equal:today',
        'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
    ]);

    // Jika validasi gagal
    if ($validator->fails()) {
        Log::error('Validasi gagal', ['errors' => $validator->errors()]);
        return response()->json([
            'success' => false,
            'message' => 'Validasi input gagal.',
            'errors' => $validator->errors(),
        ], 400);
    }

    // Ambil data properti
    $properti = Properti::find($idProperti);

    if (!$properti) {
        Log::error('Properti tidak ditemukan', ['idProperti' => $idProperti]);
        return response()->json([
            'success' => false,
            'message' => 'Properti tidak ditemukan',
        ], 404);
    }

    // Total jumlah properti yang tersedia
    $totalJumlah = $properti->jumlah;

    // Ambil semua reservasi yang terkait properti dalam rentang waktu
    try {
        $reservasiAktif = Reservasi::where('id_produk', $idProperti)
            ->where('status_reservasi', 'tercatat')
            ->where(function ($query) use ($tglMulai, $tglSelesai) {
                $query->where(function ($query) use ($tglMulai, $tglSelesai) {
                    // Case 1: Reservation start or end date is within the range
                    $query->whereBetween('tgl_mulai', [$tglMulai, $tglSelesai])
                        ->orWhereBetween('tgl_selesai', [$tglMulai, $tglSelesai]);
                })
                ->orWhere(function ($subQuery) use ($tglMulai, $tglSelesai) {
                    // Case 2: Reservation encompasses the entire range
                    $subQuery->where('tgl_mulai', '<=', $tglMulai)
                            ->where('tgl_selesai', '>=', $tglSelesai);
                });
            })
            ->sum('jumlah_pesan');

        // Calculate property availability
        $ketersediaan = $totalJumlah - $reservasiAktif;

        return response()->json([
            'success' => true,
            'ketersediaan' => $ketersediaan,
        ], 200);

    } catch (\Exception $e) {
        Log::error('Error dalam pengambilan data reservasi', [
            'error_message' => $e->getMessage(),
            'idProperti' => $idProperti,
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat memproses data reservasi.',
        ], 500);
    }
}


public function cekKetersediaanKendaraan(Request $request, $idKendaraan)
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

    // Validasi input menggunakan Validator
    $validator = Validator::make($request->all(), [
        'tgl_mulai' => 'required|date|after_or_equal:today',
        'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
    ]);

    // Jika validasi gagal
    if ($validator->fails()) {
        Log::error('Validasi gagal', ['errors' => $validator->errors()]);
        return response()->json([
            'success' => false,
            'message' => 'Validasi input gagal.',
            'errors' => $validator->errors(),
        ], 400);
    }

    // Ambil data kendaraan
    $kendaraan = Kendaraan::find($idKendaraan);

    if (!$kendaraan) {
        Log::error('Kendaraan tidak ditemukan', ['idKendaraan' => $idKendaraan]);
        return response()->json([
            'success' => false,
            'message' => 'Kendaraan tidak ditemukan',
        ], 404);
    }

    // Total jumlah kendaraan yang tersedia
    $totalJumlah = 1; // Karena kendaraan itu unik, hanya ada 1 unit per kendaraan

    // Ambil semua reservasi yang terkait kendaraan dalam rentang waktu
    try {
        $reservasiAktif = Reservasi::where('id_produk', $idKendaraan)
            ->where('status_reservasi', 'tercatat')
            ->where(function ($query) use ($tglMulai, $tglSelesai) {
                $query->where(function ($query) use ($tglMulai, $tglSelesai) {
                    // Case 1: Reservation start or end date is within the range
                    $query->whereBetween('tgl_mulai', [$tglMulai, $tglSelesai])
                        ->orWhereBetween('tgl_selesai', [$tglMulai, $tglSelesai]);
                })
                ->orWhere(function ($subQuery) use ($tglMulai, $tglSelesai) {
                    // Case 2: Reservation encompasses the entire range
                    $subQuery->where('tgl_mulai', '<=', $tglMulai)
                            ->where('tgl_selesai', '>=', $tglSelesai);
                });
            })
            ->sum('jumlah_pesan');

        // Cek ketersediaan kendaraan
        if ($reservasiAktif >= $totalJumlah) {
            return response()->json([
                'success' => true,  // Ganti success ke true meskipun kendaraan tidak tersedia
                'ketersediaan' => 'Tidak Tersedia',
            ], 200);
        }

        // Jika kendaraan tersedia
        return response()->json([
            'success' => true,
            'ketersediaan' => 'Tersedia',
        ], 200);

    } catch (\Exception $e) {
        Log::error('Error dalam pengambilan data reservasi kendaraan', [
            'error_message' => $e->getMessage(),
            'idKendaraan' => $idKendaraan,
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat memproses data reservasi kendaraan.',
        ], 500);
    }
}

}
