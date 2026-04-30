<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    use HasFactory;

    protected $table = 'spaces';
    protected $primaryKey = 'id_lahan';

    // TAMBAHKAN id_user DI SINI
    protected $fillable = [
        'nama_lahan',
        'lokasi_lahan',
        'luas_lahan',
        'suhu_lahan',
        'cahaya_lahan',
        'id_user', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}