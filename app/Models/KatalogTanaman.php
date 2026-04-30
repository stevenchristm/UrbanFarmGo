<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KatalogTanaman extends Model
{
    use HasFactory;

    protected $table = 'katalog_tanamans';
    protected $primaryKey = 'id_tanaman';

    protected $fillable = [
        'nama_tanaman', 'estimasi_hari_panen', 'suhu_min', 'suhu_max', 'cahaya_jam', 
        'humidity_avg', 'rainfall_avg', 'cahaya_min', 
        'rentang_suhu', 'jarak_tanam_ideal', 'deskripsi_edukasi', 
        'foto_tanaman', 'video_id'
    ];
}