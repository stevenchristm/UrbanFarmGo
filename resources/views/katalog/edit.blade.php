@extends('layouts.app')

@section('title', 'Edit Katalog Tanaman')

@section('content')
<div class="page-header animate-up">
    <h1>✏️ Penyesuaian Varietas Bibit</h1>
    <p>Perbarui parameter botani untuk akurasi penjadwalan Master Agronomist.</p>
</div>

<div class="glass-card animate-up" style="max-width: 700px; margin: 0 auto; padding: 3rem;">
    <form action="{{ route('katalog.update', $tanaman->id_tanaman) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 2rem;">
            <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Nama Tanaman / Varietas</label>
            <input type="text" name="nama_tanaman" value="{{ old('nama_tanaman', $tanaman->nama_tanaman) }}" required 
            style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Suhu Min (°C)</label>
                <input type="number" name="suhu_min" value="{{ old('suhu_min', $tanaman->suhu_min) }}" required 
                style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); outline: none;">
            </div>
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Suhu Max (°C)</label>
                <input type="number" name="suhu_max" value="{{ old('suhu_max', $tanaman->suhu_max) }}" required 
                style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); outline: none;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Cahaya (Jam/Hari)</label>
                <input type="number" name="cahaya_jam" value="{{ old('cahaya_jam', $tanaman->cahaya_jam) }}" required 
                style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); outline: none;">
            </div>
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Estimasi Panen (Hari)</label>
                <input type="number" name="estimasi_hari_panen" value="{{ old('estimasi_hari_panen', $tanaman->estimasi_hari_panen) }}" required 
                style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); outline: none;">
            </div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Deskripsi Edukasi</label>
            <textarea name="deskripsi_edukasi" rows="4"
            style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none;">{{ old('deskripsi_edukasi', $tanaman->deskripsi_edukasi) }}</textarea>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Foto Bibit Saat Ini</label>
            <div style="border: 1px solid var(--border-soft); padding: 1.5rem; border-radius: 15px; display: flex; align-items: center; gap: 20px; background: var(--bg-soft);">
                @if($tanaman->foto_tanaman)
                    <img src="{{ asset('assets/img/bibit/' . $tanaman->foto_tanaman) }}" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover;">
                @else
                    <div style="width: 80px; height: 80px; border-radius: 12px; background: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🌱</div>
                @endif
                <div>
                    <input type="file" name="gambar_tanaman" accept="image/*">
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;">Upload baru untuk mengganti foto lama.</p>
                </div>
            </div>
        </div>

        <div style="background: rgba(243, 156, 18, 0.05); border: 1px solid #f39c12; padding: 1.5rem; border-radius: 15px; margin-bottom: 2.5rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #d35400; margin-bottom: 0.5rem; text-transform: uppercase;">Materi Edukasi Video (YouTube ID)</label>
            <input type="text" name="video_id" value="{{ old('video_id', $tanaman->video_id) }}" placeholder="Contoh: dQw4w9WgXcQ" required 
            style="width: 100%; padding: 14px; border-radius: 10px; border: 1px solid #f39c12; background: white; outline: none;">
        </div>

        <button type="submit" class="cyber-btn" style="width: 100%; justify-content: center; padding: 1.2rem; background: #f39c12; box-shadow: 0 10px 20px rgba(243, 156, 18, 0.2);">
            <i class="fas fa-save"></i> Perbarui Data Bibit
        </button>
        
        <a href="{{ route('katalog.index') }}" style="display: block; text-align: center; margin-top: 1.5rem; color: var(--text-muted); text-decoration: none; font-size: 0.9rem; font-weight: 600;">
            Batal dan Kembali
        </a>
    </form>
</div>
@endsection