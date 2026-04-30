<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjadwalan extends Model
{
    use HasFactory;
    
    /**
     * Properti dinamis yang digunakan di controller
     */
    public $hariKe;
    public $daftar_tugas_hari_ini;

    // Tambahkan baris di bawah ini
    protected $fillable = [
        'user_id',
        'nama_lahan',
        'nama_tanaman',
        'tanggal_tanam',
        'durasi_panen'
    ];

    public function details()
    {
        return $this->hasMany(PenjadwalanDetail::class, 'penjadwalan_id');
    }

    public function space()
    {
        // Sesuaikan 'id_space' dengan nama kolom foreign key di tabel penjadwalans Anda
        return $this->belongsTo(Space::class, 'id_space');
    }
}