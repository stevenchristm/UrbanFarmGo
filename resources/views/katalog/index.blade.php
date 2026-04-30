@extends('layouts.app')

@section('title', 'Katalog Tanaman')

@section('styles')
<style>
    .plant-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }

    .plant-card {
        padding: 0 !important;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(10px);
        background: var(--bg-white);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .image-frame {
        width: 100%;
        height: 200px;
        background: var(--bg-soft);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    }

    .image-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .plant-card:hover .image-frame img {
        transform: scale(1.08);
    }

    .plant-content {
        padding: 1.8rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .badge-futuristic {
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 8px;
        background: var(--bg-soft);
        border: 1px solid var(--border-soft);
        color: var(--primary-emerald);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 700;
    }

    .video-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.9);
        backdrop-filter: blur(15px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .video-modal-inner {
        width: 90%;
        max-width: 900px;
        aspect-ratio: 16/9;
        background: black;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--primary-emerald);
        box-shadow: 0 0 50px rgba(16, 185, 129, 0.2);
    }

    /* AI Timeline Styles */
    .ai-timeline {
        position: relative;
        padding-left: 30px;
        margin-top: 20px;
    }
    .ai-timeline::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border-soft);
    }
    .ai-timeline-item {
        position: relative;
        margin-bottom: 25px;
    }
    .ai-timeline-icon {
        position: absolute;
        left: -30px;
        width: 24px;
        height: 24px;
        background: var(--bg-white);
        border: 2px solid var(--primary-emerald);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        z-index: 1;
    }
    .ai-timeline-content {
        background: rgba(248, 250, 252, 0.5);
        border: 1px solid var(--border-soft);
        border-radius: 12px;
        padding: 15px;
    }
    .ai-timeline-title {
        font-weight: 800;
        color: var(--text-slate);
        margin-bottom: 5px;
        display: flex;
        justify-content: space-between;
    }
    .ai-timeline-desc {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .ai-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(30, 41, 59, 0.6);
        backdrop-filter: blur(8px);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }
    .ai-modal-card {
        background: var(--bg-white);
        width: 90%;
        max-width: 500px;
        border-radius: 24px;
        padding: 2rem;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        position: relative;
    }
    .loader {
        border: 3px solid var(--bg-soft);
        border-top: 3px solid var(--primary-emerald);
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>
@endsection

@section('content')
<div class="page-header animate-up">
    <h1>📖 Eksplorasi Biodiversitas</h1>
    <p>Pelajari karakteristik tanaman pangan untuk mengoptimalkan hasil panen Anda.</p>
</div>

<div class="plant-grid animate-up" style="animation-delay: 0.1s;">
    @forelse($tanaman as $t)
    <div class="glass-card plant-card">
        <div class="image-frame">
            @if($t->foto_tanaman)
                <img src="{{ asset('assets/img/bibit/' . $t->foto_tanaman) }}" alt="{{ $t->nama_tanaman }}">
            @else
                <span style="font-size: 3rem;">🌱</span>
            @endif
        </div>

        <div class="plant-content">
            <h3 style="margin-bottom: 12px; color: var(--text-slate); font-weight: 700;">{{ $t->nama_tanaman }}</h3>
            
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1rem;">
                <i class="fas fa-sun"></i> {{ $t->cahaya_jam }}h • <i class="fas fa-calendar-check"></i> {{ $t->estimasi_hari_panen ?? 90 }} Hari
            </div>

            <div style="display: flex; gap: 8px; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <span class="badge-futuristic"><i class="fas fa-temperature-high"></i> {{ round($t->suhu_min) }}-{{ round($t->suhu_max) }}°C</span>
            </div>

            <div style="display: flex; gap: 8px; margin-top: auto;">
                @if($t->video_id)
                    <button onclick="playVideo('{{ $t->video_id }}')" class="cyber-btn cyber-btn-outline" style="flex: 1; justify-content: center; padding: 10px;" title="Tutorial Tanam">
                        <i class="fab fa-youtube"></i>
                    </button>
                @else
                    <button class="cyber-btn cyber-btn-outline" style="flex: 1; justify-content: center; padding: 10px; opacity: 0.5; cursor: not-allowed;" disabled title="Video Belum Tersedia">
                        <i class="fab fa-youtube"></i>
                    </button>
                @endif
                <button onclick="openAiModal('{{ $t->id_tanaman }}', '{{ $t->nama_tanaman }}')" class="cyber-btn" style="flex: 3; justify-content: center; padding: 10px; font-size: 0.8rem;">
                    <i class="fas fa-brain" style="margin-right: 5px;"></i> Analisis AI
                </button>
            </div>
        </div>
    </div>
    @empty
        <div class="glass-card animate-up" style="grid-column: 1 / -1; text-align: center; padding: 4rem;">
            <p style="color: var(--text-muted);">Database botani belum terisi.</p>
        </div>
    @endforelse
</div>

<div id="youtubeModal" class="video-overlay" onclick="closeVideo()">
    <div class="video-modal-inner" onclick="event.stopPropagation()">
        <iframe id="videoPlayer" src="" frameborder="0" allow="autoplay; encrypted-media" style="width: 100%; height: 100%;"></iframe>
    </div>
    <div style="position: absolute; top: 30px; right: 30px; font-size: 2rem; color: white; cursor: pointer;">
        <i class="fas fa-times"></i>
    </div>
</div>

<!-- AI Lifecycle Modal -->
<div id="aiModal" class="ai-modal-overlay" onclick="closeAiModal()">
    <div class="ai-modal-card" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <h2 style="font-size: 1.4rem; font-weight: 800; color: var(--text-slate);">
                <i class="fas fa-brain" style="color: var(--primary-emerald); margin-right: 8px;"></i> AI Lifecycle: <span id="aiPlantName"></span>
            </h2>
            <button onclick="closeAiModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:var(--text-muted);"><i class="fas fa-times"></i></button>
        </div>
        <div id="aiModalBody">
            <div class="loader"></div>
            <p style="text-align:center; color:var(--text-muted); font-size:0.9rem; font-weight:600;">Gemini AI sedang menyusun siklus realistis...</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modal = document.getElementById('youtubeModal');
    const player = document.getElementById('videoPlayer');

    function playVideo(videoId) {
        player.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
        modal.style.display = 'flex';
    }

    function closeVideo() {
        player.src = ""; 
        modal.style.display = 'none';
    }

    // AI Lifecycle Modal Logic
    function openAiModal(id, name) {
        document.getElementById('aiModal').style.display = 'flex';
        document.getElementById('aiPlantName').innerText = name;
        document.getElementById('aiModalBody').innerHTML = '<div class="loader"></div><p style="text-align:center; color:var(--text-muted); font-size:0.9rem; font-weight:600;">Gemini AI sedang menyusun siklus realistis...</p>';

        fetch(`/katalog/${id}/ai-lifecycle`)
            .then(res => res.json())
            .then(data => {
                if(data.error) {
                    document.getElementById('aiModalBody').innerHTML = `<p style="color:red; text-align:center; font-weight:600;">${data.error}</p>`;
                    return;
                }
                
                let html = `<p style="font-weight:800; color:var(--text-slate); margin-bottom: 15px; font-size:1.1rem;">Estimasi Panen: <span style="color:var(--primary-emerald);">${data.total_days} Hari</span></p>`;
                html += `<div class="ai-timeline">`;
                
                if (data.stages && data.stages.length > 0) {
                    data.stages.forEach(stage => {
                        let icon = '🌱'; // Default: seed/sprout
                        let phaseLower = stage.phase.toLowerCase();
                        if(phaseLower.includes('vegetatif') || phaseLower.includes('daun')) icon = '🌿';
                        if(phaseLower.includes('bunga') || phaseLower.includes('buah')) icon = '🌸';
                        if(phaseLower.includes('panen')) icon = '🧺';
                        
                        html += `
                        <div class="ai-timeline-item">
                            <div class="ai-timeline-icon">${icon}</div>
                            <div class="ai-timeline-content">
                                <div class="ai-timeline-title">
                                    <span>${stage.phase}</span>
                                    <span style="color:var(--primary-emerald);">${stage.days} Hari</span>
                                </div>
                                <div class="ai-timeline-desc"><i class="fas fa-info-circle" style="color:var(--accent-cyan); margin-right:4px;"></i> ${stage.action}</div>
                            </div>
                        </div>`;
                    });
                } else {
                    html += `<p style="color:var(--text-muted);">Data fase belum tersedia.</p>`;
                }
                
                html += `</div>`;
                document.getElementById('aiModalBody').innerHTML = html;
            })
            .catch(err => {
                document.getElementById('aiModalBody').innerHTML = `<p style="color:red; text-align:center; font-weight:600;">Gagal terhubung ke AI.</p>`;
            });
    }

    function closeAiModal() {
        document.getElementById('aiModal').style.display = 'none';
    }
</script>
@endsection