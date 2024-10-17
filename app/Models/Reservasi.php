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
        'tipe_produk',
        'jumlah_pesan',
        'id_produk'
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
