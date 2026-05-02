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

        <div class="flex flex-col sm:flex-row items-center gap-4 mt-8">
            <!-- Secondary Button: Batal -->
            <a href="{{ route('lahan.index') }}" class="w-full sm:w-auto flex justify-center items-center gap-2 px-6 py-3 bg-white/20 hover:bg-emerald-50/50 backdrop-blur-md border border-emerald-500/30 hover:border-emerald-300 text-emerald-700 font-semibold rounded-xl shadow-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                <span>Batal dan Kembali</span>
            </a>
            
            <!-- Primary Button: Simpan -->
            <button type="submit" class="w-full sm:w-auto flex justify-center items-center gap-2 px-8 py-3 bg-emerald-500 hover:bg-emerald-400 text-white font-semibold rounded-xl shadow-[0_4px_15px_rgba(16,185,129,0.3)] hover:shadow-[0_4px_25px_rgba(16,185,129,0.5)] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                <i data-lucide="save" class="w-5 h-5"></i>
                <span>Simpan & Eksekusi Analisis</span>
            </button>
        </div>
    </form>
</div>
@endsection