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
        'id_admin',
        'tgl_pembayaran',
        'jumlah_dp',
        'jumlah_pelunasan',
        'payment_type',
        'snap_token',
        'status_pembayaran'
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
