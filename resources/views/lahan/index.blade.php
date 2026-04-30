@extends('layouts.app')

@section('title', 'Manajemen Lahan')

@section('styles')
<style>
    .table-container {
        overflow-x: auto;
    }
    
    .cyber-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    
    .cyber-table th {
        padding: 1rem;
        text-align: left;
        color: var(--text-muted);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .cyber-table tr {
        background: var(--bg-white);
        transition: var(--transition-standard);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
    }
    
    .cyber-table tr:hover {
        background: rgba(255, 255, 255, 0.95);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -5px rgba(0, 0, 0, 0.05);
    }
    
    .cyber-table td {
        padding: 1.5rem 1rem;
        border-top: 1px solid rgba(226, 232, 240, 0.8);
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    }
    
    .cyber-table td:first-child {
        border-left: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px 0 0 16px;
    }
    
    .cyber-table td:last-child {
        border-right: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 0 16px 16px 0;
    }

    .env-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--bg-white);
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.8rem;
        color: var(--primary-emerald);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
    }
</style>
@endsection

@section('content')
<div class="page-header animate-up">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>🌱 Manajemen Lahan Strategis</h1>
            <p>Monitor dan kelola parameter lingkungan lahan perkotaan Anda.</p>
        </div>
        <a href="{{ route('lahan.create') }}" class="cyber-btn btn-glow">
            <i class="fas fa-plus"></i> Tambah Lahan
        </a>
    </div>
</div>

@if(session('success'))
    <div class="glass-card animate-up" style="border-left: 5px solid var(--primary-emerald); margin-bottom: 2rem; padding: 1rem; background: rgba(16, 185, 129, 0.05);">
        <span style="color: var(--primary-emerald); font-weight: 600;">✅ {{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="glass-card animate-up" style="border-left: 4px solid var(--accent-red); margin-bottom: 2rem; padding: 1rem;">
        <span style="color: var(--accent-red)">⚠️ {{ session('error') }}</span>
    </div>
@endif

<div class="table-container animate-up" style="animation-delay: 0.1s;">
    <table class="cyber-table">
        <thead>
            <tr>
                <th>Identitas Lahan</th>
                <th>Luas Area</th>
                <th>Parameter Lingkungan</th>
                <th>Pemilik</th>
                <th style="text-align: center;">Kontrol Sistem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($spaces as $l)
            <tr>
                <td>
                    <div style="font-weight: 700; color: var(--text-slate);">{{ $l->nama_lahan }}</div>
                    <div style="font-size: 0.8rem; color: var(--primary-emerald); font-weight: 600;"><i class="fas fa-location-dot"></i> {{ $l->lokasi_lahan ?: 'Malang' }}</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted);">ID: #LHN-{{ $l->id_lahan }}</div>
                </td>
                <td>
                    <span style="font-weight: 600;">{{ $l->luas_lahan }}</span> <span style="color: var(--text-muted); font-size: 0.8rem;">m²</span>
                </td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <span class="env-badge" title="Suhu"><i class="fas fa-thermometer-half"></i> {{ $l->suhu_lahan }}°C</span>
                        <span class="env-badge" title="Cahaya"><i class="fas fa-sun"></i> {{ $l->cahaya_lahan }}h</span>
                    </div>
                </td>
                <td>
                    <span style="color: var(--accent-cyan); font-size: 0.9rem; font-weight: 500;">
                        <i class="fas fa-user-circle"></i> {{ Auth::user()->nama ?? Auth::user()->name }}
                    </span>
                </td>
                <td style="text-align: center;">
                    <div style="display: flex; justify-content: center; gap: 8px;">
                        <a href="{{ route('lahan.rekomendasi', $l->id_lahan) }}" class="cyber-btn" style="padding: 6px 12px; font-size: 0.75rem; background: var(--bg-soft); border: 1px solid var(--primary-emerald); color: var(--primary-emerald);">
                            <i class="fas fa-microchip"></i> AI Analitik
                        </a>
                        
                        <a href="{{ route('lahan.edit', $l->id_lahan) }}" class="cyber-btn cyber-btn-outline" style="padding: 6px 12px; font-size: 0.75rem;">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('lahan.destroy', $l->id_lahan) }}" method="POST" id="delete-form-{{ $l->id_lahan }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <input type="hidden" name="password_konfirmasi" id="pass-field-{{ $l->id_lahan }}">
                            <button type="button" class="cyber-btn" style="padding: 6px 12px; font-size: 0.75rem; background: rgba(239, 68, 68, 0.1); color: var(--accent-red); border: 1px solid rgba(239, 68, 68, 0.2);" onclick="confirmDeletePassword('{{ $l->id_lahan }}')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 4rem; color: var(--text-muted);">
                    <i class="fas fa-map-marked-alt" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                    Belum ada koordinat lahan terdaftar.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    function confirmDeletePassword(id) {
        let userPassword = prompt("PROSEDUR KEAMANAN: Masukkan password Anda untuk konfirmasi penghapusan:");
        if (userPassword) {
            const inputField = document.getElementById('pass-field-' + id);
            const form = document.getElementById('delete-form-' + id);
            if (inputField && form) {
                inputField.value = userPassword;
                form.submit();
            }
        }
    }
</script>
@endsection