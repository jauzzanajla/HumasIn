<!DOCTYPE html>
<html lang="id">

<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HumasIn')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #20242b;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button, input, select, textarea {
            font-family: inherit;
        }

        /* =========================
            APP LAYOUT
        ========================= */
        .app {
            display: flex;
            min-height: 100vh;
        }

        .main {
            margin-left: 250px;
            width: calc(100% - 250px);
            min-height: 100vh;
            background: #fafbfd;
        }

        /* =========================
            TOPBAR
        ========================= */
        .topbar {
            height: 76px;
            background: #ffffff;
            border-bottom: 1px solid #eaf0f8;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 200;
            box-shadow: 0 4px 20px rgba(196, 225, 252, 0.15);
        }

        .page-heading h1 {
            font-size: 19px;
            font-weight: 700;
            letter-spacing: -0.3px;
            color: #1e293b;
        }

        .page-heading p {
            font-size: 11px;
            color: #8a99ad;
            margin-top: 4px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        /* =========================
            NOTIFICATION DROPDOWN (Mac Style)
        ========================= */
        .notification-dropdown {
            position: relative;
        }

        .notification-btn {
            width: 40px;
            height: 40px;
            border: 1px solid #d5ebfc;
            background: #ffffff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4e8bbc;
            cursor: pointer;
            position: relative;
            transition: 0.2s ease;
            box-shadow: 0 2px 8px rgba(196, 225, 252, 0.3);
        }

        .notification-btn:hover {
            background: #f0f7ff;
            border-color: #96CBFC;
        }

        .notification-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 7px;
            height: 7px;
            background: #FF99BE;
            border: 2px solid #ffffff;
            border-radius: 50%;
        }

        .notification-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 320px;
            background: #ffffff;
            border: 1px solid #d5ebfc;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(78, 139, 188, 0.15);
            display: none;
            z-index: 9999;
            overflow: hidden;
        }

        .notification-menu.show {
            display: block;
        }

        .notif-header {
            padding: 14px 18px;
            background: #fafbfc;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notif-title {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }

        .notif-body {
            max-height: 280px;
            overflow-y: auto;
        }

        .notif-item {
            display: block;
            padding: 12px 18px;
            border-bottom: 1px solid #f1f5f9;
            text-decoration: none;
            transition: background 0.1s;
        }

        .notif-item:hover {
            background: #f8fafc;
        }

        .notif-item-title {
            font-size: 12px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .notif-item-desc {
            font-size: 11px;
            color: #64748b;
            line-height: 1.4;
        }

        .notif-item-time {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* =========================
            PROFILE NAVBAR DROPDOWN
        ========================= */
        .profile-dropdown {
            position: relative;
        }

        .profile-trigger {
            border: none;
            background: transparent;
            display: flex;
            align-items: center;
            gap: 9px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 12px;
            transition: .2s;
        }

        .profile-trigger:hover {
            background: #f4f9ff;
        }

        .top-profile-avatar,
        .top-profile-photo {
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }

        .top-profile-avatar {
            background: #C2E1FC;
            color: #2b5c8f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .top-profile-photo {
            object-fit: cover;
            border: 1px solid #96CBFC;
        }

        .top-profile-info {
            text-align: left;
            min-width: 105px;
        }

        .top-profile-name {
            font-size: 12px;
            font-weight: 600;
            color: #20242b;
            white-space: nowrap;
        }

        .top-profile-role {
            font-size: 10px;
            color: #8a99ad;
            margin-top: 2px;
            white-space: nowrap;
        }

        .profile-arrow {
            font-size: 11px;
            color: #8a99ad;
            margin-left: 2px;
        }

        .profile-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 250px;
            background: #ffffff;
            border: 1px solid #d5ebfc;
            border-radius: 14px;
            box-shadow: 0 12px 35px rgba(78, 139, 188, 0.12);
            padding: 8px;
            display: none;
            z-index: 9999;
        }

        .profile-menu.show {
            display: block;
        }

        .profile-menu-header {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px;
        }

        .profile-menu-avatar,
        .profile-menu-photo {
            width: 42px;
            height: 42px;
            border-radius: 50%;
        }

        .profile-menu-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #C2E1FC;
            color: #2b5c8f;
            font-size: 13px;
            font-weight: 700;
        }

        .profile-menu-photo {
            object-fit: cover;
        }

        .profile-menu-name {
            font-size: 12px;
            font-weight: 600;
            color: #20242b;
        }

        .profile-menu-role {
            font-size: 10px;
            color: #8a99ad;
            margin-top: 3px;
        }

        .profile-menu-divider {
            height: 1px;
            background: #eaf0f8;
            margin: 6px 0;
        }

        .profile-menu-item {
            display: block;
            padding: 10px;
            border-radius: 8px;
            font-size: 12px;
            color: #555b64;
            transition: .2s;
        }

        .profile-menu-item:hover {
            background: #f4f9ff;
            color: #4e8bbc;
        }

        .profile-menu form {
            margin: 0;
        }

        .profile-menu-logout {
            width: 100%;
            border: none;
            background: transparent;
            text-align: left;
            padding: 10px;
            border-radius: 8px;
            font-family: inherit;
            font-size: 12px;
            color: #FF99BE;
            cursor: pointer;
            transition: .2s;
            font-weight: 600;
        }

        .profile-menu-logout:hover {
            background: #fff0f5;
            color: #e06c97;
        }

        /* =========================
            CONTENT WRAPPER
        ========================= */
        .content {
            padding: 30px 32px;
        }

        /* =========================
            GLOBAL COMPONENTS
        ========================= */
        .card {
            background: #ffffff;
            border: 1px solid #eaf0f8;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(196, 225, 252, 0.08);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 12px;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .alert-success {
            background: #f0f7ff;
            border-color: #C2E1FC;
            color: #2b5c8f;
        }

        .alert-danger {
            background: #fff0f5;
            border-color: #FFC2D9;
            color: #a84266;
        }

        /* =========================
            RESPONSIVE MEDIA QUERIES
        ========================= */
        @media(max-width: 900px) {
            .main {
                margin-left: 220px;
                width: calc(100% - 220px);
            }
            .content { padding: 22px; }
            .topbar { padding: 0 22px; }
            .top-profile-info { display: none; }
        }

        @media(max-width: 700px) {
            .main {
                margin-left: 0;
                width: 100%;
            }
            .content { padding: 18px; }
            .topbar { padding: 0 18px; }
        }
    </style>

    @stack('styles')
</head>

<body>
<div class="app">
    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    {{-- MAIN --}}
    <main class="main">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="page-heading">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <p>@yield('page-description', 'Kelola aktivitas Humas DJPK')</p>
            </div>

            <div class="topbar-right">
                {{-- NOTIFICATION DROPDOWN --}}
                <div class="notification-dropdown">
                    <button class="notification-btn" type="button" title="Notifikasi" onclick="toggleNotificationDropdown()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 8C18 5.79086 16.2091 4 14 4H10C7.79086 4 6 5.79086 6 8V12.5C6 14.1569 5.34315 15.6569 4.17157 16.8284L3 18H21L19.8284 16.8284C18.6569 15.6569 18 14.1569 18 12.5V8Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 21H15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                        </svg>
                        
                        @auth
                            @if(auth()->user()->notifikasiBelumDibaca()->count() > 0)
                                <span class="notification-dot"></span>
                            @endif
                        @endauth
                    </button>

                    <div id="notificationDropdown" class="notification-menu">
                        <div class="notif-header">
                            <span class="notif-title">Notifikasi</span>
                            @auth
                                @if(auth()->user()->notifikasiBelumDibaca()->count() > 0)
                                    <form action="{{ route('notifikasi.bacaSemua') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" style="background: none; border: none; font-size: 11px; color: #4e8bbc; cursor: pointer; font-weight: 500;">Tandai dibaca</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                        <div class="notif-body">
                            @auth
                                @forelse(auth()->user()->notifikasi as $notif)
                                    <a href="{{ route('notifikasi.baca', $notif->id) }}" class="notif-item" style="background: {{ $notif->dibaca_pada ? '#ffffff' : '#f8fafc' }};">
                                        <div class="notif-item-title">{{ $notif->judul }}</div>
                                        <div class="notif-item-desc">{{ $notif->pesan }}</div>
                                        <div class="notif-item-time">{{ $notif->created_at->diffForHumans() }}</div>
                                    </a>
                                @empty
                                    <div style="padding: 24px; text-align: center; color: #8a99ad; font-size: 12px;">
                                        Belum ada notifikasi.
                                    </div>
                                @endforelse
                            @else
                                <div style="padding: 24px; text-align: center; color: #8a99ad; font-size: 12px;">
                                    Silakan masuk terlebih dahulu.
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>

                {{-- PROFILE --}}
                @auth
                    @php
                        $topUser = auth()->user()->loadMissing(['karyawan', 'peran']);
                        $initial = strtoupper(substr($topUser->name ?? 'U', 0, 1));
                    @endphp

                    <div class="profile-dropdown">
                        <button type="button" class="profile-trigger" onclick="toggleProfileDropdown()">
                            @if($topUser->foto_profil)
                                <img src="{{ asset('storage/' . $topUser->foto_profil) }}" class="top-profile-photo" alt="Foto profil">
                            @else
                                <div class="top-profile-avatar">{{ $initial }}</div>
                            @endif

                            <div class="top-profile-info">
                                <div class="top-profile-name">{{ $topUser->name }}</div>
                                <div class="top-profile-role">{{ $topUser->peran->nama_peran ?? 'User' }}</div>
                            </div>

                            <span class="profile-arrow">▾</span>
                        </button>

                        <div id="profileDropdown" class="profile-menu">
                            <div class="profile-menu-header">
                                @if($topUser->foto_profil)
                                    <img src="{{ asset('storage/' . $topUser->foto_profil) }}" class="profile-menu-photo" alt="Foto profil">
                                @else
                                    <div class="profile-menu-avatar">{{ $initial }}</div>
                                @endif
                                <div>
                                    <div class="profile-menu-name">{{ $topUser->name }}</div>
                                    <div class="profile-menu-role">{{ $topUser->peran->nama_peran ?? 'User' }}</div>
                                </div>
                            </div>

                            <div class="profile-menu-divider"></div>

                            <a href="{{ route('profile.index') }}" class="profile-menu-item">Profil Saya</a>

                            <div class="profile-menu-divider"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="profile-menu-logout">Keluar</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </header>

        {{-- CONTENT --}}
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </section>
    </main>
</div>

<script>
    function toggleNotificationDropdown() {
        const notifDropdown = document.getElementById('notificationDropdown');
        const profileDropdown = document.getElementById('profileDropdown');
        
        // Tutup dropdown profil jika sedang terbuka
        if (profileDropdown) profileDropdown.classList.remove('show');
        
        if (notifDropdown) {
            notifDropdown.classList.toggle('show');
        }
    }

    function toggleProfileDropdown() {
        const profileDropdown = document.getElementById('profileDropdown');
        const notifDropdown = document.getElementById('notificationDropdown');
        
        // Tutup dropdown notifikasi jika sedang terbuka
        if (notifDropdown) notifDropdown.classList.remove('show');
        
        if (profileDropdown) {
            profileDropdown.classList.toggle('show');
        }
    }

    // Tutup dropdown saat klik di luar area
    document.addEventListener('click', function(event) {
        const notifContainer = document.querySelector('.notification-dropdown');
        const notifDropdown = document.getElementById('notificationDropdown');
        if (notifContainer && notifDropdown && !notifContainer.contains(event.target)) {
            notifDropdown.classList.remove('show');
        }

        const profileContainer = document.querySelector('.profile-dropdown');
        const profileDropdown = document.getElementById('profileDropdown');
        if (profileContainer && profileDropdown && !profileContainer.contains(event.target)) {
            profileDropdown.classList.remove('show');
        }
    });

    // Tutup dropdown dengan tombol Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const notifDropdown = document.getElementById('notificationDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            if (notifDropdown) notifDropdown.classList.remove('show');
            if (profileDropdown) profileDropdown.classList.remove('show');
        }
    });
</script>

@stack('scripts')
</body>
</html>