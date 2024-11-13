<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo', function (Blueprint $table) {
            $table->id('id_promo');
            $table->string('nama_promo');
            $table->text('deskripsi');
        });
        DB::statement('ALTER TABLE promo AUTO_INCREMENT = 12030;');
    }

    public function down(): void
    {
        Schema::dropIfExists('promo');
    }
};