@extends('layouts.app')

@section('title', 'Beranda')

@section('styles')
<style>

    .mini-card-agronomy {
        background: var(--bg-white);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 20px;
        padding: 1.5rem;
        transition: var(--transition-standard);
        display: grid;
        grid-template-columns: auto 1fr auto; gap: 1.2rem;
        align-items: center;
        margin-bottom: 1.2rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }
    .mini-card-agronomy:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: rgba(16, 185, 129, 0.2);
    }
    .mini-card-agronomy::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: var(--primary-emerald);
        opacity: 0;
        transition: var(--transition-standard);
    }
    .mini-card-agronomy:hover::before {
        opacity: 1;
    }
    .progress-bar-mini { height: 8px; background: var(--bg-soft); border-radius: 10px; margin-top: 12px; overflow: hidden; box-shadow: inset 0 1px 3px rgba(0,0,0,0.05); }
    .progress-fill-mini { height: 100%; background: linear-gradient(90deg, var(--primary-emerald), var(--secondary-sage)); border-radius: 10px; transition: width 1s ease-in-out; }
    .phase-tag { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; background: rgba(16, 185, 129, 0.1); color: var(--primary-emerald); padding: 4px 10px; border-radius: 8px; }
    
    #katalog-list-content { transition: opacity 0.4s ease-in-out; }
    .fade-out { opacity: 0; }
    .fade-in { opacity: 1; }

    .catalog-item-modern {
        display: flex; align-items: center; gap: 16px; padding: 1.2rem; border-radius: 20px; background: var(--bg-white); border: 1px solid rgba(255,255,255,0.8); transition: var(--transition-standard);
        position: relative;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .catalog-item-modern:hover { border-color: rgba(16,185,129,0.2); transform: translateY(-3px); box-shadow: var(--shadow-soft); }

    .match-badge-mini {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--primary-emerald), #0ea5e9);
        color: white;
        box-shadow: 0 4px 10px rgba(16,185,129,0.2);
    }
    .premium-station-capsule {
        display: inline-flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        padding: 18px 36px;
        border-radius: 40px;
        border: 1px solid rgba(255, 255, 255, 1);
        box-shadow: 
            0 20px 40px -10px rgba(16, 185, 129, 0.15), 
            0 10px 20px -5px rgba(0, 0, 0, 0.05),
            inset 0 2px 10px rgba(255, 255, 255, 1);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.4s ease;
        gap: 35px;
    }
    .premium-station-capsule:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 
            0 30px 50px -15px rgba(16, 185, 129, 0.25), 
            0 15px 25px -5px rgba(0, 0, 0, 0.05),
            inset 0 2px 10px rgba(255, 255, 255, 1);
    }
    .station-block {
        display: flex;
        align-items: center;
        gap: 20px;
        text-align: left;
    }
    .station-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .station-data {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .station-data-label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 1.5px;
        margin-bottom: 4px;
    }
    .station-data-value {
        font-size: 1.8rem;
        font-weight: 900;
        color: var(--text-slate);
        line-height: 1.1;
        letter-spacing: -1px;
    }
    .station-data-sub {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--primary-emerald);
        margin-top: 4px;
    }
    .station-divider-premium {
        width: 2px;
        height: 60px;
        background: linear-gradient(to bottom, rgba(16, 185, 129, 0), rgba(16, 185, 129, 0.25), rgba(16, 185, 129, 0));
    }

    /* Custom scrollbar for inner containers */
    .custom-scroll::-webkit-scrollbar { width: 6px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb { background: rgba(226, 232, 240, 0.8); border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

    /* Circular Progress Bar */
    .circular-progress {
        position: relative;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: conic-gradient(var(--primary-emerald) calc(var(--progress) * 1%), #e2e8f0 0);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.1);
    }
    .circular-progress::after {
        content: '';
        position: absolute;
        width: 53px;
        height: 53px;
        background: #f8fafc;
        border-radius: 50%;
    }
    .circular-value {
        position: relative;
        z-index: 1;
        font-weight: 800;
        font-size: 0.8rem;
        color: var(--text-slate);
    }
</style>
@endsection

@section('content')
<div class="page-header animate-up">
    <div style="display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="font-size: 2.2rem; font-weight: 800; letter-spacing: -1px;">Halo, {{ Auth::user()->nama ?? Auth::user()->name }}! 🌿</h1>
            <p style="font-size: 1.05rem; font-weight: 500;">Pusat kendali ekosistem urban Anda aktif dan sinkron.</p>
        </div>
        <div style="text-align: right;">
            <div class="premium-station-capsule">
                <!-- Waktu Lokal Block -->
                <div class="station-block">
                    <div class="station-icon-wrapper">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <filter id="ceramic-shadow-clock" x="-20%" y="-20%" width="140%" height="140%">
                                    <feDropShadow dx="2" dy="5" stdDeviation="4" flood-color="#059669" flood-opacity="0.25"/>
                                </filter>
                                <radialGradient id="ceramic-grad-clock" cx="35%" cy="30%" r="70%">
                                    <stop offset="0%" stop-color="#ffffff"/>
                                    <stop offset="100%" stop-color="#ecfdf5"/>
                                </radialGradient>
                                <linearGradient id="emerald-hand" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#10b981"/>
                                    <stop offset="100%" stop-color="#047857"/>
                                </linearGradient>
                            </defs>
                            <circle cx="24" cy="24" r="20" fill="url(#ceramic-grad-clock)" filter="url(#ceramic-shadow-clock)"/>
                            <circle cx="24" cy="24" r="20" fill="none" stroke="#ffffff" stroke-width="2"/>
                            <circle cx="24" cy="24" r="16" fill="none" stroke="#d1fae5" stroke-width="1" stroke-dasharray="2 4"/>
                            <rect x="22.5" y="12" width="3" height="13" rx="1.5" fill="url(#emerald-hand)"/>
                            <rect x="22.5" y="22.5" width="10" height="3" rx="1.5" fill="url(#emerald-hand)" transform="rotate(35 24 24)"/>
                            <circle cx="24" cy="24" r="4" fill="#047857"/>
                            <circle cx="24" cy="24" r="1.5" fill="#ffffff"/>
                        </svg>
                    </div>
                    <div class="station-data">
                        <div class="station-data-label">Waktu Lokal</div>
                        <div class="station-data-value"><span id="clock">{{ date('H:i') }}</span></div>
                        <div class="station-data-sub">{{ date('d M Y') }}</div>
                    </div>
                </div>
                
                <div class="station-divider-premium"></div>
                
                <!-- Kondisi Mikro Block -->
                <div class="station-block">
                    <div class="station-icon-wrapper">
                        <svg width="56" height="48" viewBox="0 0 56 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <filter id="sun-glow-weather" x="-30%" y="-30%" width="160%" height="160%">
                                    <feDropShadow dx="0" dy="3" stdDeviation="6" flood-color="#f59e0b" flood-opacity="0.4"/>
                                </filter>
                                <radialGradient id="sun-grad" cx="30%" cy="30%" r="70%">
                                    <stop offset="0%" stop-color="#fde68a"/>
                                    <stop offset="50%" stop-color="#f59e0b"/>
                                    <stop offset="100%" stop-color="#d97706"/>
                                </radialGradient>
                                <filter id="cloud-shadow-weather" x="-20%" y="-20%" width="140%" height="140%">
                                    <feDropShadow dx="3" dy="6" stdDeviation="5" flood-color="#0f172a" flood-opacity="0.15"/>
                                </filter>
                                <linearGradient id="cloud-grad" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" stop-color="#ffffff"/>
                                    <stop offset="100%" stop-color="#f1f5f9"/>
                                </linearGradient>
                            </defs>
                            <circle cx="34" cy="18" r="14" fill="url(#sun-grad)" filter="url(#sun-glow-weather)"/>
                            <path d="M16 24C11.5817 24 8 27.5817 8 32C8 36.4183 11.5817 40 16 40H38C43.5228 40 48 35.5228 48 30C48 24.4772 43.5228 20 38 20C37.5173 20 37.0426 20.0343 36.5789 20.0996C34.9604 15.7118 30.6457 12.5 25.5 12.5C18.5964 12.5 13 18.0964 13 25C13 25 16 24 16 24Z" fill="url(#cloud-grad)" filter="url(#cloud-shadow-weather)"/>
                        </svg>
                    </div>
                    <div class="station-data">
                        <div class="station-data-label">Kondisi Mikro</div>
                        <div class="station-data-value" style="color: #d97706;">28°C</div>
                        <div class="station-data-sub" style="color: var(--text-muted);">Malang, ID</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="stats-grid animate-up">
    <div class="glass-card stat-item" style="border-bottom: 4px solid var(--primary-emerald);">
        <h3>Lahan Terkelola</h3>
        <div class="value">{{ $totalLahan }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Area Aktif</div>
    </div>
    <div class="glass-card stat-item" style="border-bottom: 4px solid var(--accent-cyan);">
        <h3>Vigor Tanaman</h3>
        <div class="value">{{ $totalTanaman }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Jenis Terdaftar</div>
    </div>
    <div class="glass-card stat-item" style="border-bottom: 4px solid var(--accent-orange);">
        <h3>Luas Ekosistem</h3>
        <div class="value">{{ number_format($totalLuas) }} m²</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Kapasitas Produksi</div>
    </div>
    <div class="glass-card stat-item" style="border-bottom: 4px solid var(--accent-purple);">
        <h3>Komunitas Urban</h3>
        <div class="value">{{ $totalUser }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Petani Aktif</div>
    </div>
</div>

<div class="dashboard-grid" style="display: grid; grid-template-columns: 1.8fr 1.2fr; gap: 2.5rem; margin-top: 2rem;">
    <!-- LEFT COLUMN: Agenda & Chart -->
    <div style="display: flex; flex-direction: column; gap: 2.5rem;">
        <!-- Agenda Card -->
        <div class="glass-card animate-up" style="padding: 2.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-slate);">
                    <i class="fas fa-microchip" style="color: var(--primary-emerald); margin-right: 12px;"></i> Fokus Agronomi Hari Ini
                </h2>
                <button onclick="openModalTanam()" class="cyber-btn" style="padding: 10px 20px; font-size: 0.8rem;">
                    <i class="fas fa-plus"></i> Tanam Baru
                </button>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                @forelse($jadwalUser as $j)
                    @php
                        $tugasAktif = collect($j->daftar_tugas_hari_ini)->first();
                        $icon = '🌱'; $color = '#10b981';
                        if($tugasAktif) {
                            $cat = $tugasAktif['category'] ?? '';
                            if(strpos($cat, 'Penyiraman') !== false) { $icon = '💧'; $color = '#3b82f6'; }
                            elseif(strpos($cat, 'Pemupukan') !== false) { $icon = '🧪'; $color = '#8b5cf6'; }
                        }
                    @endphp
                    <div class="mini-card-agronomy">
                        <div style="width: 55px; height: 55px; border-radius: 15px; background: {{ $color }}10; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: {{ $color }};">{!! $icon !!}</div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                                @php $tanamanId = $katalogRaw->get($j->nama_tanaman)->id_tanaman ?? 0; @endphp
                                <span class="phase-tag ai-phase-text" data-id="{{ $tanamanId }}" data-day="{{ $j->hariKe }}"><i class="fas fa-spinner fa-spin"></i> Cek Fase AI...</span>
                                <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-muted);">{{ $tugasAktif['time'] ?? '08:00' }} WIB</span>
                            </div>
                            <div style="font-weight: 800; color: var(--text-slate); font-size: 1.05rem;">{{ $tugasAktif['name'] ?? 'Monitor Rutin' }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">{{ $j->nama_tanaman }} • {{ $j->nama_lahan }}</div>
                            <div class="progress-bar-mini" title="Lifecycle Progress: {{ $j->progresPersen }}%"><div class="progress-fill-mini" style="width: {{ $j->progresPersen }}%"></div></div>
                        </div>
                        <div><a href="{{ route('jadwal.index') }}" class="cyber-btn cyber-btn-outline" style="padding: 8px 15px; font-size: 0.75rem; border-radius: 10px;">Detail</a></div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 4rem 1rem; background: var(--bg-soft); border-radius: 20px; border: 2px dashed var(--border-soft);">
                        <img src="https://img.icons8.com/clouds/200/smiling-sun.png" style="width: 100px; margin-bottom: 1rem; opacity: 0.6;">
                        <p style="font-weight: 700; color: var(--text-muted);">Ekosistem Anda dalam kondisi istirahat.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Production Status List (Replaces Chart) -->
        <div class="glass-card animate-up" style="padding: 2.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-slate); margin: 0;">
                    <i class="fas fa-layer-group" style="color: var(--accent-cyan); margin-right: 12px;"></i> Status Produksi Aktif
                </h2>
                <a href="{{ route('jadwal.index') }}" style="font-size: 0.85rem; font-weight: 700; color: var(--primary-emerald); text-decoration: none;">
                    Detail Lengkap <i class="fas fa-external-link-alt" style="margin-left: 5px;"></i>
                </a>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; align-items: start;">
                <!-- Left: List -->
                <div class="custom-scroll" style="display: flex; flex-direction: column; gap: 1.2rem; max-height: 450px; overflow-y: auto; padding-right: 10px;">
                @forelse($jadwalUser as $j)
                <div style="background: rgba(248, 250, 252, 0.5); border: 1px solid var(--border-soft); border-radius: 20px; padding: 1.5rem; transition: var(--transition-standard);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.2rem;">
                        <div>
                            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-slate); margin-bottom: 4px;">{{ $j->nama_tanaman }}</h3>
                            <p style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; margin: 0;">
                                <i class="fas fa-map-marker-alt" style="margin-right: 4px;"></i> {{ $j->nama_lahan }}
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span style="font-size: 0.7rem; font-weight: 800; background: var(--bg-soft); color: var(--text-slate); padding: 4px 10px; border-radius: 6px;">
                                HARI KE {{ $j->hariKe ?? 1 }}
                            </span>
                        </div>
                    </div>

                    <!-- Lifecycle Circular Progress -->
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 1.5rem; background: var(--bg-white); padding: 10px; border-radius: 15px; border: 1px solid rgba(16, 185, 129, 0.1);">
                        <div class="circular-progress" style="--progress: {{ $j->progresPersen }};">
                            <div class="circular-value">{{ $j->progresPersen }}%</div>
                        </div>
                        <div>
                            @php $tanamanId = $katalogRaw->get($j->nama_tanaman)->id_tanaman ?? 0; @endphp
                            <div style="font-size: 0.85rem; font-weight: 800; color: var(--text-slate); margin-bottom: 2px;">
                                Hari {{ $j->hariKe ?? 1 }} dari {{ $j->totalHariPanen }}
                            </div>
                            <div class="ai-phase-text" data-id="{{ $tanamanId }}" data-day="{{ $j->hariKe }}" style="font-size: 0.75rem; font-weight: 700; color: var(--primary-emerald);">
                                <i class="fas fa-spinner fa-spin"></i> Menghitung Fase dengan AI...
                            </div>
                        </div>
                    </div>

                    <!-- Task Status -->
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 10px; border-top: 1px dashed #cbd5e1;">
                        <span style="font-size: 0.75rem; font-weight: 700; color: var(--text-slate);">
                            <i class="fas fa-check-circle" style="color: var(--primary-emerald); margin-right: 6px;"></i> Progres Alur Kerja Hari Ini
                        </span>
                        <span style="font-size: 0.75rem; font-weight: 800; color: var(--text-slate);">
                            {{ $j->tugas_selesai_count ?? 0 }} / {{ $j->tugas_total_count ?? 3 }} Selesai
                        </span>
                    </div>
                </div>
                @empty
                    <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                        <p style="font-size: 0.9rem; font-weight: 600;">Belum ada tanaman aktif.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Right: Chart -->
                <div style="background: rgba(248, 250, 252, 0.3); border-radius: 25px; padding: 1.5rem; position: relative; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; border: 1px dashed var(--border-soft);">
                    <div style="font-size: 0.75rem; font-weight: 900; color: var(--primary-emerald); text-transform: uppercase; margin-bottom: 1rem; letter-spacing: 1px;">Komposisi Varietas</div>
                    <div style="width: 100%; height: 280px; position: relative;">
                        <canvas id="distributionChart"></canvas>
                    </div>
                    <div style="margin-top: 1rem; font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-align: center;">
                        Total Varietas Terkelola: {{ $jadwalUser->unique('nama_tanaman')->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Katalog -->
    <div style="display: flex; flex-direction: column; gap: 2.5rem;">
        <div class="glass-card animate-up" style="padding: 2rem; min-height: 500px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.2rem; font-weight: 800; color: var(--text-slate); margin: 0;" id="katalog-title">
                    <i class="fas fa-flask" style="color: var(--primary-emerald); margin-right: 10px;"></i> Katalog Unggulan
                </h2>
                <a href="{{ route('katalog.index') }}" class="cyber-btn cyber-btn-outline" style="padding: 6px 12px; font-size: 0.65rem; border-radius: 8px; text-decoration: none;">
                    <i class="fas fa-book-open"></i> Lihat Semua Katalog
                </a>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem;" id="katalog-list-content">
                @foreach($bibitDefault as $t)
                <div class="catalog-item-modern" style="padding: 1.2rem;">
                    <div style="width: 50px; height: 50px; border-radius: 12px; overflow: hidden; background: #fff; display: flex; align-items: center; justify-content: center;">
                        @if($t->foto_tanaman && file_exists(public_path('assets/img/bibit/'.$t->foto_tanaman)))
                            <img src="{{ asset('assets/img/bibit/'.$t->foto_tanaman) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span style="font-size: 1.5rem;">🌱</span>
                        @endif
                    </div>
                    <div>
                        <div style="font-weight: 800; font-size: 1rem; color: var(--text-slate);">{{ $t->nama_tanaman }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">Siklus: {{ $t->estimasi_hari_panen ?? 90 }} Hari</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal Tanam Modern -->
<div id="modalTanam" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(8px); align-items: center; justify-content: center;">
    <div class="glass-card animate-up" style="max-width: 480px; width: 90%; padding: 3rem; border-color: var(--primary-emerald); border-width: 2px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2 style="font-size: 1.6rem; font-weight: 900; color: var(--text-slate); letter-spacing: -1px;">🌿 Inisialisasi Tanam</h2>
            <button onclick="closeModalTanam()" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.5rem;"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('simpan.tanam') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.8rem; letter-spacing: 1px;">Lokasi Lahan</label>
                <select name="lahan" required style="width: 100%; border: 1px solid var(--border-soft); background: var(--bg-soft); color: var(--text-slate); padding: 15px; border-radius: 14px; font-family: inherit; font-weight: 600; outline: none;">
                    @foreach($labels as $l) <option value="{{ $l }}">{{ $l }}</option> @endforeach
                </select>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.8rem; letter-spacing: 1px;">Varietas Tanaman</label>
                <select name="tanaman" required style="width: 100%; border: 1px solid var(--border-soft); background: var(--bg-soft); color: var(--text-slate); padding: 15px; border-radius: 14px; font-family: inherit; font-weight: 600; outline: none;">
                    @foreach($semuaKatalog as $k) <option value="{{ $k->nama_tanaman }}">{{ $k->nama_tanaman }}</option> @endforeach
                </select>
            </div>
            <div style="margin-bottom: 3rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.8rem; letter-spacing: 1px;">Waktu Operasional</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required style="width: 100%; border: 1px solid var(--border-soft); background: var(--bg-soft); color: var(--text-slate); padding: 15px; border-radius: 14px; font-family: inherit; font-weight: 600; outline: none; color-scheme: light;">
            </div>
            <button type="submit" class="cyber-btn" style="width: 100%; justify-content: center; padding: 1.2rem; font-size: 1rem;">Mulai Blueprint Tanam</button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function openModalTanam() { document.getElementById('modalTanam').style.display = 'flex'; }
    function closeModalTanam() { document.getElementById('modalTanam').style.display = 'none'; }
    window.onclick = function(event) { if (event.target.id === 'modalTanam') closeModalTanam(); }

    const defaultKatalog = {!! json_encode($bibitDefault) !!};
    const recommendations = {!! json_encode($rekomendasiLahan) !!};
    const recommendationDetails = {!! json_encode($rekomendasiDetails) !!};
    const assetPath = "{{ asset('assets/img/bibit') }}/";

    function renderKatalogItems(plants, isRecom = false) {
        const container = document.getElementById('katalog-list-content');
        const title = document.getElementById('katalog-title');
        
        container.classList.add('fade-out');
        
        setTimeout(() => {
            title.innerHTML = isRecom 
                ? '<i class="fas fa-magic" style="color: #f39c12; margin-right: 10px;"></i> Rekomendasi Lahan'
                : '<i class="fas fa-flask" style="color: var(--primary-emerald); margin-right: 10px;"></i> Katalog Unggulan';

            if (!plants || (Array.isArray(plants) && plants.length === 0)) {
                container.innerHTML = '<p style="text-align: center; color: var(--text-muted); padding: 1rem;">Tidak ada varietas yang cocok.</p>';
            } else {
                const list = Array.isArray(plants) ? plants : [plants];
                container.innerHTML = list.map(t => {
                   if(!t) return '';
                   return `
                    <div class="catalog-item-modern" style="padding: 1.2rem;">
                        ${isRecom ? `<div class="match-badge-mini">MATCH ${t.skor_kecocokan}%</div>` : ''}
                        <div style="width: 50px; height: 50px; border-radius: 12px; overflow: hidden; background: #fff; display: flex; align-items: center; justify-content: center;">
                            ${t.foto_tanaman ? `<img src="${assetPath}${t.foto_tanaman}" style="width: 100%; height: 100%; object-fit: cover;">` : '<span style="font-size: 1.5rem;">🌱</span>'}
                        </div>
                        <div>
                            <div style="font-weight: 800; font-size: 1rem; color: var(--text-slate);">${t.nama_tanaman}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">Siklus: ${t.estimasi_hari_panen || 90} Hari</div>
                        </div>
                    </div>
                `;}).join('');
            }
            container.classList.remove('fade-out');
            container.classList.add('fade-in');
        }, 200);
    }

</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctxD = document.getElementById('distributionChart').getContext('2d');
        const labelsD = {!! json_encode($plantLabels) !!};
        const countsD = {!! json_encode($plantCounts) !!};
        
        new Chart(ctxD, {
            type: 'doughnut',
            data: {
                labels: labelsD,
                datasets: [{
                    data: countsD,
                    backgroundColor: [
                        '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444', 
                        '#06b6d4', '#ec4899', '#84cc16', '#6366f1', '#f97316'
                    ],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: { weight: '800', family: 'Inter', size: 10 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.95)',
                        titleFont: { size: 13, weight: '800' },
                        bodyFont: { size: 12, weight: '600' },
                        padding: 12
                    }
                }
            }
        });
    });

    // AI Phase Fetcher
    document.addEventListener('DOMContentLoaded', function() {
        const phaseElements = document.querySelectorAll('.ai-phase-text');
        const cache = {}; // Cache to avoid duplicate API calls for same plant

        phaseElements.forEach(el => {
            const id = el.getAttribute('data-id');
            const day = parseInt(el.getAttribute('data-day'));
            
            if (!id || id == 0) {
                el.innerHTML = "Fase Aktif";
                return;
            }

            if (cache[id]) {
                processPhase(cache[id], el, day);
            } else {
                fetch(`/katalog/${id}/ai-lifecycle`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.error) {
                            cache[id] = data;
                            processPhase(data, el, day);
                        } else {
                            el.innerHTML = "Gagal memuat fase";
                        }
                    })
                    .catch(() => {
                        el.innerHTML = "Fase Aktif";
                    });
            }
        });

        function processPhase(data, el, currentDay) {
            let currentPhase = "Fase Aktif";
            let daysAccumulated = 0;
            
            if (data.stages && data.stages.length > 0) {
                for (let stage of data.stages) {
                    daysAccumulated += stage.days;
                    if (currentDay <= daysAccumulated) {
                        currentPhase = stage.phase;
                        break;
                    }
                }
                // If it exceeds, use the last phase
                if (currentDay > daysAccumulated) {
                    currentPhase = data.stages[data.stages.length - 1].phase;
                }
            }
            el.innerHTML = `<i class="fas fa-leaf" style="margin-right: 4px;"></i> Tahap ${currentPhase}`;
        }
    });
</script>
@endsection
