<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properti', function (Blueprint $table) {
            $table->id('id_properti');
            $table->string('nama_properti');
            $table->integer('kapasitas');
            $table->text('deskripsi');
            $table->integer('harga');
            $table->integer('jumlah');
            $table->string('fltur');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properti');
    }
};