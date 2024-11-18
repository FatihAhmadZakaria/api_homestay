<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Objek extends Model
{
    protected $table = 'objek';
    protected $primaryKey = 'id_objek';
    public $timestamps = false;

    protected $fillable = [
        'nama_objek',
        'deskripsi',
        'link_maps'
    ];

    public function imageable()
    {
        return $this->morphMany(Imageable::class, 'imageable');
    }
}