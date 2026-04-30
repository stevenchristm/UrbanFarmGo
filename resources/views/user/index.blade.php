@extends('layouts.app')

@section('title', 'Komunitas Petani')

@section('styles')
<style>
    .petani-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); 
        gap: 2rem; 
        margin-top: 1rem; 
    }

    .petani-card {
        text-align: center;
        padding: 2.5rem 1.5rem;
        position: relative;
    }

    .my-card {
        border-color: var(--primary-emerald);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.1);
    }

    .avatar-sphere {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-emerald), var(--accent-cyan));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        font-weight: 700;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        border: 4px solid var(--bg-white);
    }

    .badge-status {
        background: rgba(16, 185, 129, 0.05);
        color: var(--primary-emerald);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        margin-top: 1rem;
        display: inline-block;
        border: 1px solid rgba(16, 185, 129, 0.1);
    }

    .me-tag {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 0.7rem;
        color: var(--primary-emerald);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>
@endsection

@section('content')
<div class="page-header animate-up">
    <h1>🌱 Komunitas Petani Digital</h1>
    <p>Terhubung dengan jaringan petani urban yang menggunakan teknologi UrbanFarm.</p>
</div>

@if(session('success'))
    <div class="glass-card animate-up" style="border-left: 5px solid var(--primary-emerald); margin-bottom: 2rem; padding: 1rem; background: rgba(16, 185, 129, 0.05);">
        <span style="color: var(--primary-emerald); font-weight: 600;">✅ {{ session('success') }}</span>
    </div>
@endif

<div class="petani-grid animate-up" style="animation-delay: 0.1s;">
    @forelse($users as $u)
        <div class="glass-card petani-card {{ $u->id_user == Auth::id() ? 'my-card' : '' }}">
            @if($u->id_user == Auth::id())
                <div class="me-tag"><i class="fas fa-user-check"></i> Saya</div>
            @endif
            
            <div class="avatar-sphere">
                {{ strtoupper(substr($u->nama, 0, 1)) }}
            </div>
            
            <h3 style="margin-bottom: 5px; color: var(--text-slate); font-weight: 700;">{{ $u->nama }}</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">{{ $u->email }}</p>
            
            <div class="badge-status">Petani Aktif</div>

            @if($u->id_user == Auth::id())
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('user.edit', $u->id_user) }}" class="cyber-btn cyber-btn-outline" style="padding: 6px 15px; font-size: 0.8rem;">
                        <i class="fas fa-cog"></i> Pengaturan
                    </a>
                </div>
            @endif
        </div>
    @empty
        <div class="glass-card animate-up" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
            <p style="color: var(--text-muted);">Belum ada ekspansi petani di jaringan Anda.</p>
        </div>
    @endforelse
</div>
@endsection