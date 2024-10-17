<?php

namespace Database\Factories;

use App\Models\Reservasi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservasiFactory extends Factory
{
    protected $model = Reservasi::class;

    public function definition()
    {
        return [
            'id_user' => User::factory(), // Menghubungkan ke factory user
            'tgl_transaksi' => $this->faker->date(),
            'status_reservasi' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'tgl_mulai' => $this->faker->dateTimeBetween('+1 days', '+10 days'),
            'tgl_selesai' => $this->faker->dateTimeBetween('+11 days', '+20 days'),
            'total_harga' => $this->faker->numberBetween(100000, 1000000),
            'tipe_produk' => 'App\Models\Produk', // Sesuaikan jika menggunakan polymorphic relation
            'jumlah_pesan' => $this->faker->numberBetween(1, 5),
            'id_produk' => $this->faker->numberBetween(1, 100), // ID produk random antara 1 hingga 100
        ];
    }
}
