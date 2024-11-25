<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Pembatalan;
use App\Models\Reservasi;
use Carbon\Carbon;

class PembatalanController extends Controller
{
    public function createPembatalan(Request $request)
    {
        $request->validate([
            'id_reservasi' => 'required|exists:pembayaran,id_reservasi',
            'alasan_pembatalan' => 'required|string|max:255',
        ]);

        $idReservasi = $request->id_reservasi;
        $alasanPembatalan = $request->alasan_pembatalan;

        // Ambil data pembayaran berdasarkan ID reservasi
        $pembayaran = Pembayaran::where('id_reservasi', $idReservasi)->first();

        if (!$pembayaran) {
            return response()->json(['success' => false, 'message' => 'Data pembayaran tidak ditemukan'], 404);
        }

        // Ambil data reservasi berdasarkan ID reservasi
        $reservasi = Reservasi::where('id_reservasi', $idReservasi)->first();

        if (!$reservasi) {
            return response()->json(['success' => false, 'message' => 'Data reservasi tidak ditemukan'], 404);
        }

        // Tentukan jumlah refund dan status refund
        $jumlahRefund = 0;
        $statusRefund = 'tidak ada refund';

        if ($pembayaran->jumlah_dp > 100000) {
            $jumlahRefund = $pembayaran->jumlah_dp;
            $statusRefund = 'pending';
        }

        // Perbarui status pembayaran menjadi "dibatalkan"
        $pembayaran->status_pembayaran = 'dibatalkan';
        $pembayaran->save();

        // Perbarui status reservasi menjadi "dibatalkan"
        $reservasi->status_reservasi = 'dibatalkan';
        $reservasi->save();

        // Buat pembatalan baru
        $pembatalan = Pembatalan::create([
            'id_reservasi' => $idReservasi,
            'id_admin' => 41230, // ID admin tetap atau null jika tidak ada
            'tgl_pembatalan' => Carbon::now(),
            'alasan_pembatalan' => $alasanPembatalan,
            'status_refund' => $statusRefund,
            'jumlah_refund' => $jumlahRefund,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembatalan berhasil dibuat',
            'jumlah_refund' => $jumlahRefund // Pastikan hanya mengirim angka
        ]);
    }

}
