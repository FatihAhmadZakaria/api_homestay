<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    public function run()
    {
        // Membuat 10 record pembayaran menggunakan factory
        Pembayaran::factory()->count(10)->create();
    }
}
