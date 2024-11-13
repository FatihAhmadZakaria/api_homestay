<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id('id_kendaraan');
            $table->string('nama_kendaraan');
            $table->string('plat_nomor')->unique();
            $table->string('tipe_kendaraan');
            $table->integer('harga');
            $table->text('deskripsi');
        });

        DB::statement('ALTER TABLE kendaraan AUTO_INCREMENT = 124120;');
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};