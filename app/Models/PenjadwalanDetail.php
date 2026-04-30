<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjadwalanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjadwalan_id',
        'hari_ke',
        'fase',
        'kegiatan',
        'deskripsi',
        'alat_bahan',
        'kategori'
    ];

    protected $casts = [
        'alat_bahan' => 'array'
    ];

    public function penjadwalan()
    {
        return $this->belongsTo(Penjadwalan::class, 'penjadwalan_id');
    }
}
