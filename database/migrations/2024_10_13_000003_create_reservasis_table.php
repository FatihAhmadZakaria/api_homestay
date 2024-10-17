<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id('id_reservasi');
            $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('id_produk');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->integer('total_harga');
            $table->integer('jumlah_pesan');
            $table->string('status_reservasi');
            $table->dateTime('tgl_transaksi');
            $table->string('tipe_produk');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasi');
    }
};