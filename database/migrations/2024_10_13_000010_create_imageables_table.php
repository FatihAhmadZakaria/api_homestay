<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imageable', function (Blueprint $table) {
            $table->id('id_img');
            $table->nullableMorphs('imageable');  // Membuat imageable_id dan imageable_type
            $table->string('img_path');
        });

        DB::statement('ALTER TABLE imageable AUTO_INCREMENT = 13490;');
    }

    public function down(): void
    {
        Schema::dropIfExists('imageable');
    }
};