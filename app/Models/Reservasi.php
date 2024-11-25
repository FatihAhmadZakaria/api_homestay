<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'reservasi';
    protected $primaryKey = 'id_reservasi';

    protected $fillable = [
        'id_user',
        'tgl_transaksi',
        'status_reservasi',
        'tgl_mulai',
        'tgl_selesai',
        'total_harga',
        'tipe_produk', // properti/kendaraan
        'jumlah_pesan',
        'id_produk'
    ];

    protected $attributes = [
        'id_user' => 453120,
        'status_reservasi' => 'pending'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->tgl_transaksi) {
                $model->tgl_transaksi = now();
            }

            // Hitung total harga jika belum ada
            if (!$model->total_harga) {
                $durasi = now()->parse($model->tgl_selesai)->diffInDays(now()->parse($model->tgl_mulai)) + 1;
                $hargaProduk = 0;

                if ($model->tipe_produk === 'properti') {
                    $produk = Properti::find($model->id_produk);
                    $hargaProduk = $produk?->harga ?? 0;
                } elseif ($model->tipe_produk === 'kendaraan') {
                    $produk = Kendaraan::find($model->id_produk);
                    $hargaProduk = $produk?->harga ?? 0;
                }

                $model->total_harga = $durasi * $hargaProduk * $model->jumlah_pesan;
            }
        });
    }

    const TIPE_PRODUK = [
        'properti' => 'Properti',
        'kendaraan' => 'Kendaraan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_reservasi');
    }

    public function pembatalan()
    {
        return $this->hasOne(Pembatalan::class, 'id_reservasi');
    }

    public function produk()
    {
        return $this->morphTo();
    }
}
