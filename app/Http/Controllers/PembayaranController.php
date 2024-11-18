<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Reservasi; // Pastikan model Reservasi di-import
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;


class PembayaranController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    // Membuat transaksi pembayaran
    public function createPayment(Request $request, $reservasiId)
    {
        // Cari reservasi berdasarkan ID
        $reservasi = Reservasi::find($reservasiId);

        if (!$reservasi) {
            return response()->json([
                'success' => false,
                'message' => 'Reservasi tidak ditemukan.',
            ], 404);
        }
        $jumlahDp = $request->input('jumlah_dp', 0);

        // Menyiapkan data untuk transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $reservasi->id_reservasi, // Pastikan order_id unik
                'gross_amount' => $jumlahDp,
            ],
            'customer_details' => [
                'first_name' => $reservasi->user->nama_depan,
                'last_name' => $reservasi->user->nama_belakang,
                'email' => $reservasi->user->email,
                'phone' => $reservasi->user->phone,
            ],
        ];

        try {
            // Menghasilkan Snap token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Menyimpan data pembayaran ke database
            $pembayaran = Pembayaran::create([
                'id_reservasi' => $reservasi->id_reservasi,
                'id_admin' => 41230,
                'tgl_pembayaran' => now(),
                'jumlah_dp' => $jumlahDp, // Sesuaikan dengan jumlah DP jika ada
                'jumlah_pelunasan' => $reservasi->total_harga - $jumlahDp,
                'payment_type' => 'snap',
                'snap_token' => $snapToken,
                'status_pembayaran' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dibuat.',
                'snap_token' => $snapToken,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Gagal membuat pembayaran: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Fungsi untuk menangani notifikasi dari Midtrans (callback)
    public function notificationHandler(Request $request)
    {
        $payload = $request->all();
        Log::info('Incoming Midtrans Notification', ['payload' => $payload]);

        // Validasi payload
        if (!isset($payload['order_id'], $payload['transaction_status'], $payload['signature_key'], $payload['gross_amount'])) {
            return response()->json(['success' => false, 'message' => 'Invalid payload'], 400);
        }

        $orderId = str_replace('ORDER-', '', $payload['order_id']);
        $transactionStatus = $payload['transaction_status'];
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];
        $reqSignature = $payload['signature_key'];

        // Verifikasi signature
        $signature = hash('sha512', $payload['order_id'] . $statusCode . $grossAmount . config('midtrans.server_key'));
        if ($signature !== $reqSignature) {
            Log::warning('Invalid signature', ['order_id' => $orderId]);
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 401);
        }

        // Cari pembayaran berdasarkan order_id
        $order = Pembayaran::where('id_reservasi', $orderId)->first();
        if (!$order) {
            Log::warning('Order not found', ['order_id' => $orderId]);
            return response()->json(['success' => false, 'message' => 'Order tidak ditemukan'], 404);
        }

        // Perbarui status pembayaran
        if ($transactionStatus == 'settlement') {
            $order->status_pembayaran = 'DP';
        } elseif ($transactionStatus == 'expire') {
            $order->status_pembayaran = 'expired';
        } elseif ($transactionStatus == 'pending') {
            $order->status_pembayaran = 'pending';
        } else {
            $order->status_pembayaran = 'other'; // Default untuk status yang tidak dikenal
        }

        $order->save();
        Log::info('Order updated successfully', ['id_reservasi' => $order->id_reservasi, 'status' => $order->status_pembayaran]);

        return response()->json(['message' => 'Success']);
    }

}
