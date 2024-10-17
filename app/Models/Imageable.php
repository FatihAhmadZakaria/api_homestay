<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Imageable extends Model
{
    protected $table = 'imageable';

    protected $fillable = [
        'img_path',
        'imageable_id',
        'imageable_type'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }
}