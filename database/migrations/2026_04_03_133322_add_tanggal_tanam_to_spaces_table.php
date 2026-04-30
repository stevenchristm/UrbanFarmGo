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
        Schema::table('spaces', function (Blueprint $table) {
            // Hapus ->after('id') karena primary key Anda adalah 'id_lahan'
            $table->date('tanggal_tanam')->nullable(); 
        });
    }

    public function down()
    {
        Schema::table('spaces', function (Blueprint $table) {
            $table->dropColumn('tanggal_tanam');
        });
    }
};
