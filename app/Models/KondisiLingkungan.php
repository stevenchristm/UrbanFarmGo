<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KondisiLingkungan extends Model
{
    protected $table = 'kondisi_lingkungan';
    protected $primaryKey = 'id_kondisi';
    protected $guarded = [];

    // Relasi balik: Kondisi ini milik lahan mana?
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class, 'id_lahan', 'id_lahan');
    }
}