<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imageable', function (Blueprint $table) {
            $table->id('id_img');
            $table->integer('id_sub_img');
            $table->string('img_path');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imageable');
    }
};