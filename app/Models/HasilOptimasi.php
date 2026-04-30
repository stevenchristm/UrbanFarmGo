<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilOptimasi extends Model
{
    protected $table = 'hasil_optimasi';
    protected $primaryKey = 'id_optimasi';
    protected $guarded = [];

    // Relasi: Hasil optimasi ini untuk lahan mana?
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class, 'id_lahan', 'id_lahan');
    }

    // Relasi: Hasil optimasi ini merekomendasikan tanaman apa?
    public function tanaman(): BelongsTo
    {
        return $this->belongsTo(KatalogTanaman::class, 'id_tanaman', 'id_tanaman');
    }
}