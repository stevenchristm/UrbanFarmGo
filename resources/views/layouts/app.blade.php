<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - UrbanFarm Modern</title>
    
    <!-- Nature-Tech Design System -->
    <link rel="stylesheet" href="{{ asset('css/modern-farm.css') }}">
    
    <!-- Icons & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @yield('styles')
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-logo">
            <span>🌿</span>
            <h2>UrbanFarm</h2>
        </div>
        
        <nav class="menu-list">
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="{{ route('user.index') }}" class="menu-item {{ request()->is('user*') ? 'active' : '' }}">
                <i class="fas fa-user-friends"></i> Komunitas
            </a>
            <a href="{{ route('lahan.index') }}" class="menu-item {{ request()->is('lahan*') ? 'active' : '' }}">
                <i class="fas fa-map-marked-alt"></i> Area Lahan
            </a>
            <a href="{{ route('katalog.index') }}" class="menu-item {{ request()->is('katalog*') ? 'active' : '' }}">
                <i class="fas fa-book-open"></i> Edukasi Bibit
            </a>
            <a href="{{ route('jadwal.index') }}" class="menu-item {{ request()->is('semua-jadwal') || request()->is('jadwal*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Alur Kerja
            </a>
            <a href="{{ route('ai.index') }}" class="menu-item {{ request()->is('ai-assistant*') ? 'active' : '' }}">
                <i class="fas fa-robot"></i> Asisten AI
            </a>
        </nav>
        
        <div class="sidebar-footer" style="padding-top: 1.5rem; border-top: 1px solid var(--border-soft);">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item" style="background: none; border: none; width: 100%; color: var(--accent-red); cursor: pointer; justify-content: flex-start;">
                    <i class="fas fa-power-off"></i> Keluar Sesi
                </button>
            </form>
        </div>
    </aside>

    <main class="main-viewport animate-up">
        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>
