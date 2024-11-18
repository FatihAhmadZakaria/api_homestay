<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Kendaraan extends Model
{
    protected $table = 'kendaraan';
    protected $primaryKey = 'id_kendaraan';
    public $timestamps = false;

    protected $fillable = [
        'nama_kendaraan',
        'plat_nomor',
        'tipe_kendaraan',
        'harga',
        'deskripsi'
    ];

    public function imageable()
    {
        return $this->morphMany(Imageable::class, 'imageable');
    }

    public function reservasi()
    {
        return $this->morphMany(Reservasi::class, 'produk');
    }
}
