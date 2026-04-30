<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kondisi_lingkungan', function (Blueprint $table) {
            $table->id('id_kondisi');
            // Menghubungkan ke tabel spaces kolom id_lahan
            $table->foreignId('id_lahan')->constrained('spaces', 'id_lahan')->onDelete('cascade');
            $table->float('intensitas_cahaya');
            $table->float('suhu');
            $table->float('kelembapan');
            $table->dateTime('waktu_cek');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('kondisi_lingkungan');
    }
};