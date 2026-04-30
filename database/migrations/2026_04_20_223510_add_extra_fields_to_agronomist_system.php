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
        // Update penjadwalan_details
        Schema::table('penjadwalan_details', function (Blueprint $table) {
            $table->string('fase')->nullable()->after('hari_ke');
            $table->text('alat_bahan')->nullable()->after('deskripsi');
        });

        // Update katalog_tanamans
        Schema::table('katalog_tanamans', function (Blueprint $table) {
            $table->integer('estimasi_hari_panen')->default(90)->after('nama_tanaman');
        });

        // Update spaces
        Schema::table('spaces', function (Blueprint $table) {
            $table->string('lokasi_lahan')->default('Malang')->after('nama_lahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjadwalan_details', function (Blueprint $table) {
            $table->dropColumn(['fase', 'alat_bahan']);
        });

        Schema::table('katalog_tanamans', function (Blueprint $table) {
            $table->dropColumn('estimasi_hari_panen');
        });

        Schema::table('spaces', function (Blueprint $table) {
            $table->dropColumn('lokasi_lahan');
        });
    }
};
