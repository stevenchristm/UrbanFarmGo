@extends('layouts.app')

@section('title', 'Alur Kerja Produksi')

@section('styles')
<style>
    .command-center { display: flex; flex-direction: column; gap: 4rem; }
    
    /* Lifecycle Header */
    .lifecycle-container {
        background: var(--bg-white);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 32px;
        padding: 3rem;
        box-shadow: var(--shadow-soft);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        transition: var(--transition-standard);
    }
    .lifecycle-container:hover {
        box-shadow: var(--shadow-hover);
        border-color: rgba(16, 185, 129, 0.1);
    }

    .progress-track-wrapper {
        margin: 2rem 0;
        position: relative;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1rem;
    }

    .progress-bar-glass {
        height: 12px;
        background: var(--bg-soft);
        border-radius: 50px;
        overflow: hidden;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-emerald), var(--secondary-sage));
        border-radius: 50px;
        transition: width 1s ease-in-out;
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
    }

    /* Phase Milestones */
    .phase-milestones {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    .milestone-item {
        text-align: center;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        position: relative;
    }

    .milestone-item.active { color: var(--primary-emerald); text-shadow: 0 0 10px rgba(16, 185, 129, 0.2); }

    /* Modern Task Timeline */
    .agronomy-timeline {
        padding-left: 2rem;
        border-left: 3px dashed var(--border-soft);
        margin-top: 3rem;
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
    }

    .task-card-premium {
        background: var(--bg-white);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 24px;
        padding: 2.2rem;
        transition: var(--transition-standard);
        position: relative;
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 2rem;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .task-card-premium:hover {
        transform: translateX(8px);
        border-color: rgba(16, 185, 129, 0.2);
        box-shadow: var(--shadow-hover);
    }

    .category-icon-box {
        width: 65px;
        height: 65px;
        background: var(--bg-white);
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02), 0 4px 10px rgba(0,0,0,0.02);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .task-card-premium.overdue { border-color: rgba(239, 68, 68, 0.3); background: #fffafa; }
    .task-card-premium.completed { opacity: 0.75; transform: none; background: #fafafa; border-color: transparent; }

    .tool-tag {
        font-size: 0.7rem;
        background: var(--bg-soft);
        color: var(--text-slate);
        padding: 5px 12px;
        border-radius: 8px;
        font-weight: 700;
        border: 1px solid var(--border-soft);
    }

    .fase-ribbon {
        position: absolute;
        top: 0;
        left: 3rem;
        background: var(--primary-emerald);
        color: white;
        padding: 6px 20px;
        border-radius: 0 0 15px 15px;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.2);
    }

    /* Overdue Badge */
    .overdue-alert {
        color: var(--accent-red);
        font-weight: 900;
        font-size: 0.65rem;
        background: rgba(239, 68, 68, 0.1);
        padding: 4px 10px;
        border-radius: 6px;
        margin-left: 10px;
    }
</style>
@endsection

@section('content')
<div class="page-header animate-up">
    <h1>🧬 Pusat Kendali Agronomi</h1>
    <p>Algoritma UrbanFarm Master Agronomist telah mensinkronisasi profil pertumbuhan Anda dengan cuaca mikro saat ini.</p>
</div>

@if($semuaJadwal->isEmpty())
    <div class="glass-card animate-up" style="text-align: center; padding: 6rem 1rem;">
        <i class="fas fa-microchip" style="font-size: 4rem; color: var(--border-soft); margin-bottom: 2rem;"></i>
        <h2>Belum Ada Ekosistem Aktif</h2>
        <p>Inisialisasi siklus tanam Anda di dashboard untuk memulai pemantauan presisi.</p>
        <a href="{{ route('dashboard') }}" class="cyber-btn" style="margin-top: 2rem;">Inisialisasi Sekarang</a>
    </div>
@else
    <div class="command-center">
        @foreach($semuaJadwal as $j)
        <section class="lifecycle-container animate-up">
            <!-- Header Section -->
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="font-size: 2.2rem; font-weight: 800; color: var(--text-slate); letter-spacing: -1px;">{{ $j->nama_tanaman }}</h2>
                    <p style="color: var(--text-muted); font-size: 1rem; font-weight: 500;">
                        <i class="fas fa-location-dot" style="color: var(--primary-emerald); margin-right: 6px;"></i> {{ $j->nama_lahan }}
                    </p>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Masa Panen</div>
                    <div style="font-size: 1.8rem; font-weight: 900; color: var(--text-slate);">{{ $j->totalHariPanen - $j->hariKe }} <span style="font-size: 1rem; color: var(--text-muted);">HARI LAGI</span></div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="progress-track-wrapper">
                <div class="progress-info">
                    <span style="font-size: 0.9rem; font-weight: 800; color: var(--primary-emerald);">HARI KE {{ $j->hariKe }}</span>
                    <span style="font-size: 0.9rem; font-weight: 800; color: var(--text-muted);">{{ $j->progresPersen }}% COMPLETE</span>
                </div>
                <div class="progress-bar-glass">
                    <div class="progress-fill" style="width: {{ $j->progresPersen }}%"></div>
                </div>
                <div class="phase-milestones">
                    <div class="milestone-item {{ $j->progresPersen >= 5 ? 'active' : '' }}">PENYEMAIAN</div>
                    <div class="milestone-item {{ $j->progresPersen >= 25 ? 'active' : '' }}">VEGETATIF</div>
                    <div class="milestone-item {{ $j->progresPersen >= 60 ? 'active' : '' }}">GENERATIF</div>
                    <div class="milestone-item {{ $j->progresPersen >= 95 ? 'active' : '' }}">PANEN</div>
                </div>
            </div>

            <!-- Timeline Tasks -->
            <div class="agronomy-timeline">
                @foreach($j->daftar_tugas_hari_ini as $t)
                @php
                    $icon = '🌱';
                    $color = '#10b981';
                    $cat = $t['category'] ?? '';
                    if(strpos($cat, 'Penyiraman') !== false) { $icon = '💧'; $color = '#3b82f6'; }
                    elseif(strpos($cat, 'Pemupukan') !== false) { $icon = '🧪'; $color = '#8b5cf6'; }
                    elseif(strpos($cat, 'Hama') !== false || strpos($cat, 'Protection') !== false) { $icon = '🛡️'; $color = '#ef4444'; }
                @endphp
                
                <div class="task-card-premium {{ $t['is_done'] ? 'completed' : ($t['is_overdue'] ? 'overdue' : '') }}">
                    @if(isset($t['fase']))
                        <div class="fase-ribbon">FASE: {{ strtoupper($t['fase']) }}</div>
                    @endif

                    <div class="category-icon-box" style="color: {{ $color }}; background: {{ $color }}10;">
                        {!! $icon !!}
                    </div>

                    <div>
                        <div style="display: flex; align-items: center; margin-bottom: 0.5rem; margin-top: 0.5rem;">
                            <span class="time-label" style="margin-bottom: 0;">{{ $t['time'] }} WIB</span>
                            @if($t['is_overdue'] && !$t['is_done'])
                                <span class="overdue-alert">EXPIRED</span>
                            @endif
                        </div>
                        <h3 class="task-title">{{ $t['name'] }}</h3>
                        <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.2rem;">
                            {{ $t['desc'] ?? ($t['description'] ?? 'Parameter sistem sedang mengkalibrasi instruksi perawatan harian.') }}
                        </p>

                        @if(!empty($t['alat_bahan']) && is_array($t['alat_bahan']))
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @foreach($t['alat_bahan'] as $tools)
                                <span class="tool-tag">{{ $tools }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div style="text-align: right;">
                        @if($t['is_done'])
                            <div style="width: 45px; height: 45px; border-radius: 50%; background: var(--primary-emerald); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin: 0 auto;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div style="font-size: 0.7rem; font-weight: 800; color: var(--primary-emerald); margin-top: 8px; text-transform: uppercase;">Selesai</div>
                        @elseif($t['is_future'])
                            <div style="color: var(--text-muted); font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Belum Waktunya</div>
                        @else
                            <button onclick="finishTask({{ $j->id }}, {{ $t['step'] }}, this)" class="cyber-btn" style="padding: 12px 20px; font-size: 0.85rem; border-radius: 12px;">
                                Konfirmasi
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Footer Action -->
            <div style="margin-top: 3rem; display: flex; justify-content: flex-end; gap: 1rem;">
                 <form action="{{ route('jadwal.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hentikan siklus agronomis ini? Seluruh data riwayat akan dihapus.')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 0.85rem; font-weight: 700; text-decoration: underline;">
                        Hentikan Produksi
                    </button>
                </form>
            </div>
        </section>
        @endforeach
    </div>
@endif
@endsection

@section('scripts')
<script>
function finishTask(id, step, btn) {
    if(!confirm("Anda yakin tugas ini telah selesai?")) return;

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/complete-task/${id}`, {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ step: step })
    })
    .then(response => {
        if (!response.ok) throw new Error('Query error');
        return response.json();
    })
    .then(data => {
        setTimeout(() => { location.reload(); }, 300);
    })
    .catch(error => {
        alert("Gagal sinkronisasi.");
        btn.disabled = false;
        btn.innerHTML = 'Konfirmasi';
    });
}
</script>
@endsection