<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function store_test()
    {
        Article::create([
            'title' => 'Berhasil Akhirnya!',
            'content' => 'Saya sudah menghapus file yang double.',
            'author' => 'Siswa Binus'
        ]);

        return "Selamat! Masalah file double sudah teratasi.";
    }

    public function index()
    {
        return Article::all();
    }
}