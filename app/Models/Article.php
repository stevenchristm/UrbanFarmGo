<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // Menentukan nama tabel (opsional, tapi bagus untuk kejelasan)
    protected $table = 'articles';

    // Mendaftarkan kolom yang boleh diisi secara massal
    protected $fillable = ['title', 'content', 'author'];
}