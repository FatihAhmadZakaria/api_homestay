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
        'status_reservasi' => 'pending',
        'tgl_transaksi' => null,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reservasi) {
            $reservasi->total_harga = $reservasi->calculateTotalHarga();
        });

        static::updating(function ($reservasi) {
            $reservasi->total_harga = $reservasi->calculateTotalHarga();
        });

        static::creating(function ($reservasi) {
            if (!$reservasi->tgl_transaksi) {
                $reservasi->tgl_transaksi = now();
            }
        });
    }

    public function calculateTotalHarga()
    {
        $produk = $this->tipe_produk === Properti::class ? Properti::find($this->id_produk) : Kendaraan::find($this->id_produk);
        if ($produk) {
            $durasi = now()->parse($this->tgl_mulai)->diffInDays(now()->parse($this->tgl_selesai)) + 1;
            $hargaProduk = $produk->harga ?? 0;
            return $hargaProduk * $this->jumlah_pesan * $durasi;
        }
        return 0;
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
