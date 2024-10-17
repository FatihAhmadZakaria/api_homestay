<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id('id_k');
            $table->string('nama_k');
            $table->string('plat_nomor')->unique();
            $table->string('tipe_kendaraan');
            $table->integer('harga');
            $table->text('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};