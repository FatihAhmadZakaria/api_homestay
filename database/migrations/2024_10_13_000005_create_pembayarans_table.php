<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_reservasi')->constrained('reservasi','id_reservasi')->onDelete('cascade');
            $table->foreignId('id_admin')->constrained('admin', 'id')->onDelete('cascade');
            $table->date('tgl_pembayaran');
            $table->integer('jumlah_dp');
            $table->integer('jumlah_pelunasan');
            $table->string('payment_type');
            $table->string('status_pembayaran');
            $table->string('snap_token');
        });

        DB::statement('ALTER TABLE pembayaran AUTO_INCREMENT = 1270;');
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};