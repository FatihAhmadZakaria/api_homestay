<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembatalan extends Model
{
    protected $table = 'pembatalan';
    protected $primaryKey = 'id_pembatalan';
    public $timestamps = false;

    protected $fillable = [
        'id_reservasi',
        'id_admin',
        'tgl_pembatalan',
        'alasan_pembatalan',
        'status_refund',
        'jumlah_refund'
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