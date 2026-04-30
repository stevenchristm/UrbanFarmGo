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
        Schema::table('penjadwalans', function (Blueprint $table) {
            $table->integer('durasi_panen')->default(90);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjadwalans', function (Blueprint $table) {
            $table->dropColumn('durasi_panen');
        });
    }
};
