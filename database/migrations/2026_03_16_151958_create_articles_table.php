<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Membuat tabel bernama 'articles' di MySQL Laragon
        Schema::create('articles', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis
            $table->string('title'); // Kolom Judul Artikel
            $table->text('content'); // Kolom Isi Artikel (Teks Panjang)
            $table->string('author'); // Kolom Nama Penulis
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus tabel jika migrasi di-reset
        Schema::dropIfExists('articles');
    }
};