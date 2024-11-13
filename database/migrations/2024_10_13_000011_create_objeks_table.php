<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('objek', function (Blueprint $table) {
            $table->id('id_objek');
            $table->string('nama_objek');
            $table->text('deskripsi');
            $table->string('link_maps');
        });

        DB::statement('ALTER TABLE objek AUTO_INCREMENT = 73130;');
    }

    public function down(): void
    {
        Schema::dropIfExists('objek');
    }
};