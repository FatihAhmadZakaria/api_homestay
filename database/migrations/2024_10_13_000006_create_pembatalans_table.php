<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembatalan', function (Blueprint $table) {
            $table->id('id_pembatalan');
            $table->foreignId('id_reservasi')->constrained('reservasi','id_reservasi')->onDelete('cascade');
            $table->foreignId('id_admin')->constrained('admin', 'id')->onDelete('cascade');
            $table->date('tgl_pembatalan');
            $table->text('alasan_pembatalan');
            $table->integer('jumlah_refund');
            $table->string('status_refund');
        });

        DB::statement('ALTER TABLE pembatalan AUTO_INCREMENT = 13470;');
    }

    public function down(): void
    {
        Schema::dropIfExists('pembatalan');
    }
};