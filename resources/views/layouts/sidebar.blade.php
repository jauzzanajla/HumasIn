<aside class="sidebar">
    <style>
        .sidebar {
            position: fixed;
            top: 12px;
            left: 12px;
            width: 250px;
            height: calc(100vh - 24px);
            background: #ffffff;
            border-radius: 28px;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            font-family: 'Poppins', 'Inter', sans-serif;
            box-shadow: 0 10px 40px rgba(47, 109, 240, .10), 0 1px 3px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .sidebar-brand {
            padding: 30px 30px 14px;
            flex-shrink: 0;
        }

        .brand-name {
            text-decoration: none;
            font-size: 21px;
            font-weight: 700;
            letter-spacing: -.5px;
            color: #1c2a78;
        }

        .brand-name span { color: #c2185b; }

        .sidebar-content {
            flex: 1;
            padding: 8px 16px 20px;
            overflow-y: auto;
        }

        .sidebar-group { margin-bottom: 18px; }

        .sidebar-label {
            padding: 0 14px;
            margin-bottom: 8px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: #6b7a90;
        }

        .sidebar-link,
        .sidebar-dropdown-toggle {
            display: flex;
            align-items: center;
            width: 100%;
            min-height: 44px;
            padding: 0 16px;
            margin-bottom: 4px;
            border-radius: 999px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            background: transparent;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: background .2s ease, color .2s ease, box-shadow .2s ease;
        }

        .sidebar-link:hover,
        .sidebar-dropdown-toggle:hover {
            background: #f0f5ff;
            color: #2f6df0;
        }

        /* Item aktif: pill biru solid */
        .sidebar-link.active {
            background: #2f6df0;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 6px 16px rgba(47, 109, 240, .32);
        }

        .sidebar-link.active:hover { background: #2a62dc; color: #ffffff; }

        /* Dropdown yang sedang terbuka di halaman aktif: tint biru */
        .sidebar-dropdown-toggle { justify-content: space-between; }
        .sidebar-dropdown-toggle.active { background: #e8f0ff; color: #2f6df0; font-weight: 700; }

        .sidebar-arrow {
            display: grid;
            place-items: center;
            width: 16px;
            height: 16px;
            color: #6b7a90;
            transition: transform .2s ease;
        }
        .sidebar-arrow svg { width: 14px; height: 14px; }
        .sidebar-dropdown-toggle.active .sidebar-arrow { color: #2f6df0; }
        .sidebar-dropdown.open .sidebar-arrow { transform: rotate(180deg); }

        .sidebar-dropdown-menu {
            display: none;
            flex-direction: column;
            margin: 4px 0 6px 22px;
            padding-left: 12px;
            border-left: 2px solid #e1ebff;
        }
        .sidebar-dropdown.open .sidebar-dropdown-menu { display: flex; }

        .sidebar-dropdown-menu a {
            display: flex;
            align-items: center;
            width: 100%;
            min-height: 38px;
            padding: 0 14px;
            margin-bottom: 2px;
            border-radius: 999px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            color: #556274;
            transition: background .2s ease, color .2s ease;
        }
        .sidebar-dropdown-menu a:hover { background: #f0f5ff; color: #2f6df0; }
        .sidebar-dropdown-menu a.active { background: #e8f0ff; color: #2f6df0; font-weight: 700; }

        .sidebar-content::-webkit-scrollbar { width: 4px; }
        .sidebar-content::-webkit-scrollbar-thumb { background: #d5e3ff; border-radius: 10px; }

        @media (max-width: 900px) {
            .sidebar { width: 220px; }
        }
        .sidebar-badge { margin-left: auto; min-width: 20px; height: 20px; padding: 0 6px; display: inline-grid; place-items: center; border-radius: 999px; background: #ff3b30; color: #fff; font-size: 11px; font-weight: 700; }
        .sidebar-link.active .sidebar-badge { background: #fff; color: #2f6df0; }
    </style>

    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-name">
            Humas<span>In</span>
        </a>
    </div>

    <div class="sidebar-content">
        {{-- UTAMA --}}
        <div class="sidebar-group">
            <div class="sidebar-label">UTAMA</div>

            @php
                $dashboardActive = request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('karyawan.dashboard');
                $chevron = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>';
            @endphp
            <a href="{{ auth()->user()?->peran?->kode_peran === 'ADMIN' ? route('admin.dashboard') : (auth()->user()?->peran?->kode_peran === 'KARYAWAN' ? route('karyawan.dashboard') : route('dashboard')) }}" class="sidebar-link {{ $dashboardActive ? 'active' : '' }}">
                Dashboard
            </a>

            @php $belumDibaca = auth()->user()?->notifikasiBelumDibaca()->count() ?? 0; @endphp
            <a href="{{ route('notifikasi.index') }}" class="sidebar-link {{ request()->routeIs('notifikasi.*') ? 'active' : '' }}">
                Notifikasi
                @if ($belumDibaca > 0)
                    <span class="sidebar-badge">{{ $belumDibaca > 99 ? '99+' : $belumDibaca }}</span>
                @endif
            </a>

            {{-- Publikasi --}}
            @php
                $publikasiActive = request()->routeIs('publikasi.*');
                $opiniActive = request()->routeIs('opini.*');
                $publikasiDropdownOpen = $publikasiActive || $opiniActive;
            @endphp
            <div class="sidebar-dropdown {{ $publikasiDropdownOpen ? 'open' : '' }}">
                <button type="button" class="sidebar-dropdown-toggle {{ $publikasiDropdownOpen ? 'active' : '' }}" onclick="toggleSidebarDropdown(this)" aria-expanded="{{ $publikasiDropdownOpen ? 'true' : 'false' }}">
                    <span>Publikasi</span>
                    <span class="sidebar-arrow">{!! $chevron !!}</span>
                </button>
                <div class="sidebar-dropdown-menu">
                    <a href="{{ route('publikasi.index') }}" class="{{ $publikasiActive ? 'active' : '' }}">Publikasi Web Profil DJPK</a>
                    <a href="{{ route('opini.index') }}" class="{{ $opiniActive ? 'active' : '' }}">Opini</a>
                </div>
            </div>

            {{-- Dokumentasi Kegiatan --}}
            <a href="{{ route('dokumentasi.index') }}" class="sidebar-link {{ request()->routeIs('dokumentasi.*') ? 'active' : '' }}">
                Dokumentasi Kegiatan
            </a>

            {{-- Peminjaman --}}
            @php
                $katalogActive = request()->routeIs('peminjaman.index') || request()->routeIs('peminjaman.aset*') || request()->routeIs('peminjaman.ruangan*');
                $kelolaActive = request()->routeIs('peminjaman.kelola');
                $peminjamanOpen = $katalogActive || $kelolaActive;
            @endphp
            <div class="sidebar-dropdown {{ $peminjamanOpen ? 'open' : '' }}">
                <button type="button" class="sidebar-dropdown-toggle {{ $peminjamanOpen ? 'active' : '' }}" onclick="toggleSidebarDropdown(this)" aria-expanded="{{ $peminjamanOpen ? 'true' : 'false' }}">
                    <span>Peminjaman</span>
                    <span class="sidebar-arrow">{!! $chevron !!}</span>
                </button>
                <div class="sidebar-dropdown-menu">
                    <a href="{{ route('peminjaman.index') }}" class="{{ $katalogActive ? 'active' : '' }}">Katalog</a>
                    <a href="{{ route('peminjaman.kelola') }}" class="{{ $kelolaActive ? 'active' : '' }}">Kelola Pinjaman</a>
                </div>
            </div>

            {{-- Event & Kalender --}}
            <a href="{{ route('event.index') }}" class="sidebar-link {{ request()->routeIs('event.*') ? 'active' : '' }}">
                Event & Kalender
            </a>
        </div>

        {{-- HUMAS --}}
        <div class="sidebar-group">
            <div class="sidebar-label">HUMAS</div>
            <a href="{{ route('template.index') }}" class="sidebar-link {{ request()->routeIs('template.*') ? 'active' : '' }}">
                Template Desain
            </a>
        </div>

        {{-- MANAJEMEN --}}
        @php
            $sidebarUser = auth()->user()?->loadMissing('peran');
            $role = $sidebarUser?->peran?->kode_peran;
            $isSuperAdmin = $role === 'SUPER_ADMIN';
            $isAdmin = $role === 'ADMIN';

            $managementOpen = request()->routeIs('ruangan.*') || request()->routeIs('aset.*') || request()->routeIs('admin.status.*') || request()->routeIs('karyawan.*') || request()->routeIs('users.*') || request()->routeIs('unit.*') || request()->routeIs('peran.*');
        @endphp

        @if($isSuperAdmin || $isAdmin)
            <div class="sidebar-group">
                <div class="sidebar-label">MANAJEMEN</div>
                <div class="sidebar-dropdown {{ $managementOpen ? 'open' : '' }}">
                    <button type="button" class="sidebar-dropdown-toggle {{ $managementOpen ? 'active' : '' }}" onclick="toggleSidebarDropdown(this)" aria-expanded="{{ $managementOpen ? 'true' : 'false' }}">
                        <span>Data & Manajemen</span>
                        <span class="sidebar-arrow">{!! $chevron !!}</span>
                    </button>
                    <div class="sidebar-dropdown-menu">
                        <a href="{{ route('ruangan.index') }}" class="{{ request()->routeIs('ruangan.*') ? 'active' : '' }}">Kelola Ruangan</a>
                        <a href="{{ route('aset.index') }}" class="{{ request()->routeIs('aset.*') ? 'active' : '' }}">Kelola Aset</a>
                        <a href="{{ route('admin.status.index') }}" class="{{ request()->routeIs('admin.status.*') ? 'active' : '' }}">Status & Penugasan Admin</a>

                        @if($isSuperAdmin)
                            <a href="{{ route('karyawan.index') }}" class="{{ request()->routeIs('karyawan.*') ? 'active' : '' }}">Karyawan</a>
                            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">Role</a>
                            <a href="{{ route('peran.index') }}" class="{{ request()->routeIs('peran.*') ? 'active' : '' }}">Panduan User</a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

   
       {{-- MONITORING --}}
        @php
            $sidebarUser = auth()->user()?->loadMissing('peran');
            $role = $sidebarUser?->peran?->kode_peran;
            $isAdminOrSuper = in_array($role, ['ADMIN', 'SUPER_ADMIN'], true);
        @endphp

      @if($isAdminOrSuper)
    <div class="sidebar-group">
        <div class="sidebar-label">MONITORING</div>
        <a href="{{ route('monitoring.rekap') }}" class="sidebar-link {{ request()->routeIs('monitoring.*') ? 'active' : '' }}">
            Rekap
        </a>
    </div>
@endif
    </div>
</aside>

<script>
    function toggleSidebarDropdown(button) {
        const dropdown = button.closest('.sidebar-dropdown');
        if (!dropdown) return;
        const isOpen = dropdown.classList.contains('open');
        dropdown.classList.toggle('open', !isOpen);
        button.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
    }
</script>