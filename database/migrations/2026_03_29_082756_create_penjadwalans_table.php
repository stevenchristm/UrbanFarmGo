<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('penjadwalans', function (Blueprint $table) {
            $table->id(); 
            
            // Buat kolom user_id
            $table->unsignedBigInteger('user_id'); 
            
            // Hubungkan secara manual ke kolom 'id_user' di tabel 'users'
            $table->foreign('user_id')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            $table->string('nama_lahan');
            $table->string('nama_tanaman');
            $table->date('tanggal_tanam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjadwalans');
    }
};
