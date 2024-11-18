<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    public $timestamps = false;
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_reservasi',
        'id_admin', // Nullable jika tidak selalu digunakan
        'tgl_pembayaran',
        'jumlah_dp',
        'jumlah_pelunasan',
        'payment_type',
        'snap_token',
        'status_pembayaran',
    ];

    const PAYMENT_TYPES = [
        'snap' => 'Snap Payment',
        'bank_transfer' => 'Bank Transfer',
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin');
    }
}
