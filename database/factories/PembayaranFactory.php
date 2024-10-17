<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembayaran>
 */
use App\Models\Pembayaran;
use App\Models\Reservasi;
use App\Models\Admin;
use Illuminate\Support\Str;

class PembayaranFactory extends Factory
{
    protected $model = Pembayaran::class;

    public function definition()
    {
        return [
            'id_reservasi' => Reservasi::factory(),  // Jika sudah ada data reservasi di database, bisa pakai random
            'id_admin' => Admin::factory(),          // Sama dengan admin, bisa pakai random
            'tgl_pembayaran' => $this->faker->date(),
            'jumlah_dp' => $this->faker->numberBetween(100000, 500000),
            'jumlah_pelunasan' => $this->faker->numberBetween(500000, 1000000),
            'payment_type' => $this->faker->randomElement(['credit_card', 'bank_transfer', 'cash']),
            'status_pembayaran' => $this->faker->randomElement(['pending', 'paid', 'cancelled']),
            'snap_token' => Str::random(16),  // Token acak
        ];
    }
}