<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('katalog_tanamans', function (Blueprint $table) {
            $table->id('id_tanaman');
            $table->string('nama_tanaman');
            $table->float('suhu_min'); 
            $table->float('suhu_max');
            $table->integer('cahaya_jam'); 
            $table->float('humidity_avg')->nullable();
            $table->float('rainfall_avg')->nullable();
            $table->string('cahaya_min')->nullable();
            $table->string('rentang_suhu')->nullable();
            $table->string('jarak_tanam_ideal')->nullable();
            $table->text('deskripsi_edukasi')->nullable();
            
            // Kolom Media (Foto & Video)
            $table->string('foto_tanaman')->nullable(); 
            $table->string('video_id', 50)->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('katalog_tanamans');
    }
};