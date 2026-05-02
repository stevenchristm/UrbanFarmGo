@extends('layouts.app')

@section('title', 'Komunitas Petani')

@section('styles')
<style>
    /* Dynamic Mesh Background */
    .mesh-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: -1;
        overflow: hidden;
        background-color: #f8fafc;
        pointer-events: none;
    }
    .blob-1 {
        position: absolute; top: -15%; left: -10%; width: 55vw; height: 55vw;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, rgba(16, 185, 129, 0) 70%);
        filter: blur(80px); animation: float 25s infinite alternate ease-in-out;
    }
    .blob-2 {
        position: absolute; bottom: -20%; right: -10%; width: 65vw; height: 65vw;
        background: radial-gradient(circle, rgba(6, 95, 70, 0.12) 0%, rgba(6, 95, 70, 0) 70%);
        filter: blur(100px); animation: float 30s infinite alternate-reverse ease-in-out;
    }
    .blob-3 {
        position: absolute; top: 30%; left: 45%; transform: translate(-50%, -50%); width: 50vw; height: 50vw;
        background: radial-gradient(circle, rgba(14, 165, 233, 0.12) 0%, rgba(167, 243, 208, 0.1) 40%, rgba(167, 243, 208, 0) 70%);
        filter: blur(90px); animation: float 22s infinite alternate ease-in-out;
    }
    @keyframes float {
        0% { transform: translate(0, 0) scale(1) rotate(0deg); }
        50% { transform: translate(3%, 5%) scale(1.05) rotate(2deg); }
        100% { transform: translate(-3%, -2%) scale(0.95) rotate(-2deg); }
    }

    /* Glassmorphism Classes */
    .glass-card-premium {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1), 0 8px 30px rgba(16, 185, 129, 0.05);
        transition: all 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .glass-card-premium:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.5);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.2), 0 15px 40px rgba(16, 185, 129, 0.15);
        transform: translateY(-5px);
    }
    .glass-card-my-profile {
        background: rgba(255, 255, 255, 0.3);
        border: 1px solid rgba(16, 185, 129, 0.4);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.4), 0 10px 40px rgba(16, 185, 129, 0.15);
    }
    .glass-card-my-profile:hover {
        border-color: rgba(16, 185, 129, 0.6);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,0.5), 0 15px 40px rgba(16, 185, 129, 0.25);
    }

    /* Avatar Styling */
    .avatar-wrapper {
        position: relative;
        width: 96px;
        height: 96px;
        margin: 0 auto 1.5rem;
    }
    .avatar-glow {
        position: absolute;
        inset: -5px;
        background: linear-gradient(135deg, #10b981, #0ea5e9);
        border-radius: 50%;
        filter: blur(10px);
        opacity: 0.4;
        transition: opacity 0.5s ease;
    }
    .glass-card-premium:hover .avatar-glow { opacity: 0.7; }
    .avatar-inner {
        position: relative;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #10b981, #0d9488);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        border: 4px solid white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .glass-card-my-profile .avatar-inner {
        background: linear-gradient(135deg, #0ea5e9, #10b981);
    }

    /* Status Badge */
    .status-badge {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 800;
        border: 1px solid rgba(16, 185, 129, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Floating Me Tag */
    .me-tag {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        display: flex;
        align-items: center;
        gap: 4px;
        z-index: 10;
    }
</style>
@endsection

@section('content')
<!-- Mesh Background -->
<div class="mesh-bg">
    <div class="blob-1"></div>
    <div class="blob-2"></div>
    <div class="blob-3"></div>
</div>

<div class="relative z-10 flex flex-col gap-10 pb-16">
    <!-- Header -->
    <div class="flex flex-col justify-center items-start animate-slide-up">
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800 tracking-tight mb-3 flex items-center gap-4">
            <div class="p-3 bg-white/40 backdrop-blur-md rounded-2xl shadow-sm border border-white/50">
                <i data-lucide="users" class="w-8 h-8 md:w-10 md:h-10 text-emerald-600"></i>
            </div>
            Komunitas Petani Digital
        </h1>
        <p class="text-slate-500 font-medium text-lg max-w-2xl">Terhubung, belajar, dan berkolaborasi dengan jaringan agronomis modern yang menggunakan ekosistem UrbanFarm.</p>
    </div>

    <!-- Alert Notification -->
    @if(session('success'))
        <div class="animate-slide-up bg-emerald-50/80 backdrop-blur-md border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
            <div class="bg-emerald-100 text-emerald-600 p-1.5 rounded-full">
                <i data-lucide="check" class="w-5 h-5"></i>
            </div>
            <span class="font-bold tracking-wide">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8 animate-slide-up" style="animation-delay: 0.1s;">
        @forelse($users as $u)
            <div class="glass-card-premium rounded-[2rem] p-8 relative flex flex-col items-center group overflow-hidden {{ $u->id_user == Auth::id() ? 'glass-card-my-profile' : '' }}">
                
                @if($u->id_user == Auth::id())
                    <div class="me-tag">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i> Saya
                    </div>
                    <!-- Decorative Background for My Profile -->
                    <div class="absolute -top-20 -right-20 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
                @endif
                
                <!-- Avatar -->
                <div class="avatar-wrapper mt-4">
                    <div class="avatar-glow"></div>
                    <div class="avatar-inner">
                        {{ strtoupper(substr($u->nama, 0, 1)) }}
                    </div>
                </div>
                
                <!-- User Info -->
                <h3 class="text-xl font-extrabold text-slate-800 mb-1 group-hover:text-emerald-700 transition-colors">{{ $u->nama }}</h3>
                <p class="text-slate-500 font-medium text-sm mb-6">{{ $u->email }}</p>
                
                <div class="status-badge mb-2">
                    <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                    Petani Aktif
                </div>

                @if($u->id_user == Auth::id())
                    <div class="mt-8 w-full">
                        <a href="{{ route('user.edit', $u->id_user) }}" class="flex items-center justify-center gap-2 px-5 py-3 w-full bg-white/60 hover:bg-white backdrop-blur-md border border-slate-200 hover:border-emerald-300 text-slate-600 hover:text-emerald-700 rounded-xl transition-all duration-300 font-bold shadow-sm group/btn">
                            <i data-lucide="settings" class="w-4 h-4 transition-transform duration-500 group-hover/btn:rotate-90"></i>
                            <span>Pengaturan Akun</span>
                        </a>
                    </div>
                @endif
            </div>
        @empty
            <div class="glass-card-premium rounded-[2rem] col-span-full text-center p-16">
                <div class="w-20 h-20 bg-emerald-50/50 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-6 border border-emerald-100">
                    <i data-lucide="users-2" class="w-10 h-10 text-emerald-300"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-700 mb-2">Belum Ada Komunitas</h3>
                <p class="text-slate-500 font-medium">Jaringan ekspansi petani UrbanFarm masih kosong saat ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection