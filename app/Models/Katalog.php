<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Katalog extends Model
{
    // Gunakan nama tabel jamak sesuai hasil migrasi tadi
    protected $table = 'katalog_tanamans'; 

    protected $fillable = ['nama_tanaman', 'cahaya_min', 'rentang_suhu', 'jarak_tanam_ideal'];
}