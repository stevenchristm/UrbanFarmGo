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
        Schema::create('penjadwalan_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penjadwalan_id');
            $table->integer('hari_ke');
            $table->string('kegiatan');
            $table->text('deskripsi');
            $table->string('kategori'); // e.g., Penyiraman, Pemupukan, Hama, dll.
            $table->timestamps();

            $table->foreign('penjadwalan_id')
                ->references('id')
                ->on('penjadwalans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjadwalan_details');
    }
};
