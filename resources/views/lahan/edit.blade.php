@extends('layouts.app')

@section('title', 'Edit Data Lahan')

@section('content')
<div class="page-header animate-up">
    <h1>✏️ Penyesuaian Parameter Lahan</h1>
    <p>Perbarui kondisi lingkungan atau lokasi untuk kalibrasi ulang sistem AI.</p>
</div>

@if(session('error'))
    <div class="glass-card animate-up" style="border-left: 5px solid var(--accent-red); margin-bottom: 2rem; padding: 1.2rem; background: rgba(239, 68, 68, 0.05);">
        <span style="color: var(--accent-red); font-weight: 600;"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</span>
    </div>
@endif

<div class="glass-card animate-up" style="max-width: 600px; margin: 0 auto; padding: 3rem;">
    <form action="{{ route('lahan.update', $space->id_lahan) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Nama Lahan / Area</label>
            <input type="text" name="nama_lahan" value="{{ old('nama_lahan', $space->nama_lahan) }}" required 
            style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none; transition: var(--transition-standard);">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Lokasi Geografis (Kota)</label>
            <input type="text" name="lokasi_lahan" value="{{ old('lokasi_lahan', $space->lokasi_lahan) }}" placeholder="Contoh: Malang / Jakarta Selatan" required 
            style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none; transition: var(--transition-standard);">
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;"><i class="fas fa-info-circle"></i> Mempengaruhi data cuaca real-time pada penjadwalan.</p>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Luas Lahan (m²)</label>
            <input type="number" name="luas_lahan" value="{{ old('luas_lahan', $space->luas_lahan) }}" required 
            style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none; transition: var(--transition-standard);">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 2.5rem;">
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Suhu Rata-rata (°C)</label>
                <input type="number" name="suhu_lahan" value="{{ old('suhu_lahan', $space->suhu_lahan) }}" required 
                style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none; transition: var(--transition-standard);">
            </div>
            <div>
                <label style="display: block; font-size: 0.9rem; font-weight: 700; color: var(--text-slate); margin-bottom: 0.8rem;">Sinar Matahari (Jam)</label>
                <input type="number" name="cahaya_lahan" value="{{ old('cahaya_lahan', $space->cahaya_lahan) }}" required 
                style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid var(--border-soft); background: var(--bg-soft); font-family: inherit; font-size: 0.95rem; outline: none; transition: var(--transition-standard);">
            </div>
        </div>

        <div style="background: rgba(243, 156, 18, 0.05); border: 1px dashed #f39c12; padding: 1.5rem; border-radius: 15px; margin-bottom: 2.5rem;">
            <label style="display: block; font-size: 0.85rem; font-weight: 800; color: #d35400; margin-bottom: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">
                <i class="fas fa-shield-alt"></i> Konfirmasi Keamanan
            </label>
            <input type="password" name="password_konfirmasi" placeholder="Masukkan password Anda" required 
            style="width: 100%; padding: 14px; border-radius: 10px; border: 1px solid #f39c12; background: white; outline: none; font-size: 0.9rem;">
            <small style="color: #e67e22; display: block; margin-top: 10px; font-size: 0.75rem; font-weight: 500;">Wajib diisi untuk memvalidasi perubahan kritis pada lahan.</small>
        </div>

        <button type="submit" class="cyber-btn" style="width: 100%; justify-content: center; padding: 1.2rem; background: #f39c12; box-shadow: 0 10px 20px rgba(243, 156, 18, 0.2);">
            <i class="fas fa-save"></i> Perbarui Parameter Lahan
        </button>
        
        <a href="{{ route('lahan.index') }}" style="display: block; text-align: center; margin-top: 1.5rem; color: var(--text-muted); text-decoration: none; font-size: 0.9rem; font-weight: 600;">
            Batal dan Kembali
        </a>
    </form>
</div>
@endsection