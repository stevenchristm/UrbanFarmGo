@extends('layouts.app')

@section('title', 'Daftar Lahan Baru')

@section('content')
<div class="page-header animate-up">
    <h1>🌱 Daftarkan Lahan Strategis</h1>
    <p>Masukkan parameter lingkungan spesifik lahan Anda untuk kalibrasi algoritma AI.</p>
</div>

<div class="glass-card animate-up" style="max-width: 600px; margin: 0 auto; padding: 3rem;">
    <form action="{{ route('lahan.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Nama Lahan / Area</label>
            <input type="text" name="nama_lahan" placeholder="Contoh: Balkon Depan / Atap Rumah" required 
            style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none; transition: var(--transition-standard);">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Lokasi Geografis (Kota)</label>
            <input type="text" name="lokasi_lahan" placeholder="Contoh: Malang / Jakarta Selatan" required 
            style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none; transition: var(--transition-standard);">
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;"><i class="fas fa-info-circle"></i> Digunakan untuk sinkronisasi data cuaca real-time.</p>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Luas Lahan (m²)</label>
            <input type="number" name="luas_lahan" placeholder="Misal: 5" required 
            style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none; transition: var(--transition-standard);">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 2.5rem;">
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Suhu Rata-rata (°C)</label>
                <input type="number" name="suhu_lahan" placeholder="Contoh: 28" required 
                style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none; transition: var(--transition-standard);">
            </div>
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Sinar Matahari (Jam)</label>
                <input type="number" name="cahaya_lahan" placeholder="Contoh: 6" required 
                style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none; transition: var(--transition-standard);">
            </div>
        </div>

        <button type="submit" class="cyber-btn" style="width: 100%; justify-content: center; padding: 1.2rem;">
            <i class="fas fa-microchip"></i> Simpan & Eksekusi Analisis
        </button>
        
        <a href="{{ route('lahan.index') }}" style="display: block; text-align: center; margin-top: 1.5rem; color: var(--text-muted); text-decoration: none; font-size: 0.9rem; font-weight: 600;">
            Batal dan Kembali
        </a>
    </form>
</div>
@endsection