<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Imageable extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika berbeda dari konvensi Laravel
    protected $table = 'imageable';
    protected $primaryKey = 'id_img';
    public $timestamps = false;

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'imageable_id',
        'imageable_type',
        'img_path',
    ];

    /**
     * Definisi relasi morphTo untuk mendukung relasi polymorphic
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}
