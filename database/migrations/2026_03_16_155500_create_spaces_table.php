<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('spaces', function (Blueprint $table) {
            $table->id('id_lahan');
            $table->string('nama_lahan');
            $table->integer('luas_lahan');
            $table->integer('suhu_lahan');   // Tambahkan ini jika belum ada
            $table->integer('cahaya_lahan'); // Tambahkan ini jika belum ada
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('spaces');
    }
};