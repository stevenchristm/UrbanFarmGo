<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPerawatan extends Model
{
    use HasFactory;

    // Tentukan kolom yang boleh diisi secara massal
    protected $fillable = [
        'penjadwalan_id', 
        'step', 
        'tanggal_selesai'
    ];
}