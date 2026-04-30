@extends('layouts.app')

@section('title', 'Tambah Bibit Edukasi')

@section('content')
<div class="page-header animate-up">
    <h1>🌱 Daftarkan Varietas Bibit Baru</h1>
    <p>Perkaya database edukasi UrbanFarm untuk algoritma Master Agronomist.</p>
</div>

<div class="glass-card animate-up" style="max-width: 700px; margin: 0 auto; padding: 3rem;">
    <form action="{{ route('katalog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Nama Tanaman / Varietas</label>
            <input type="text" name="nama_tanaman" placeholder="Contoh: Selada Keriting, Tomat Cherry" required 
            style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Suhu Min (°C)</label>
                <input type="number" name="suhu_min" placeholder="20" required 
                style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); outline: none;">
            </div>
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Suhu Max (°C)</label>
                <input type="number" name="suhu_max" placeholder="30" required 
                style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); outline: none;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Cahaya (Jam/Hari)</label>
                <input type="number" name="cahaya_jam" placeholder="6" required 
                style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); outline: none;">
            </div>
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Estimasi Panen (Hari)</label>
                <input type="number" name="estimasi_hari_panen" placeholder="90" required 
                style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); outline: none;">
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Deskripsi Edukasi</label>
            <textarea name="deskripsi_edukasi" placeholder="Berikan tips singkat untuk menanam varietas ini..." rows="4"
            style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none;"></textarea>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Foto Bibit</label>
            <div style="border: 2px dashed var(--border-soft); padding: 2rem; border-radius: 15px; text-align: center; background: var(--bg-soft);">
                <input type="file" name="gambar_tanaman" accept="image/*">
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 10px;">Gunakan foto jernih dengan rasio 1:1 untuk tampilan optimal.</p>
            </div>
        </div>

        <div style="background: rgba(243, 156, 18, 0.05); border: 1px solid #f39c12; padding: 1.5rem; border-radius: 15px; margin-bottom: 2.5rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #d35400; margin-bottom: 0.5rem; text-transform: uppercase;">Materi Edukasi Video (YouTube ID)</label>
            <input type="text" name="video_id" placeholder="Contoh: dQw4w9WgXcQ (Hanya ID-nya saja)" required 
            style="width: 100%; padding: 14px; border-radius: 10px; border: 1px solid #f39c12; background: white; outline: none;">
        </div>

        <button type="submit" class="cyber-btn" style="width: 100%; justify-content: center; padding: 1.2rem;">
            <i class="fas fa-save"></i> Daftarkan Bibit Unggul
        </button>
        
        <a href="{{ route('katalog.index') }}" style="display: block; text-align: center; margin-top: 1.5rem; color: var(--text-muted); text-decoration: none; font-size: 0.9rem; font-weight: 600;">
            Batal dan Kembali
        </a>
    </form>
</div>
@endsection