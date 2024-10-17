<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Properti extends Model
{
    protected $table = 'properti';

    protected $fillable = [
        'nama_properti',
        'kapasitas',
        'fitur',
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
