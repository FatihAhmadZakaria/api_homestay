<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo', function (Blueprint $table) {
            $table->id('id_promo');
            $table->string('nama_promo');
            $table->text('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo');
    }
};