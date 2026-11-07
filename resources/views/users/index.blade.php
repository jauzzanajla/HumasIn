@extends('layouts.app')

@section('title', 'Akun User | HumasIn')
@section('page-title', 'Akun User')
@section('page-description', 'Kelola akun pengguna dan hak akses ke HumasIn')

@section('content')

<style>
    .us-page {
        --us-accent: #4E8BBC;
        --us-accent-soft: rgba(78, 139, 188, .12);
        --us-text: #1d1d1f;
        --us-muted: #6e6e73;
        --us-line: rgba(0, 0, 0, .07);
        --us-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 1280px; margin: 0 auto; color: var(--us-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .us-page *, .us-page *::before, .us-page *::after { box-sizing: border-box; }

    /* Header */
    .us-page .us-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; margin-bottom: 28px; }
    .us-page .us-eyebrow { font-size: 11px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; color: #3b7aab; margin-bottom: 6px; }
    .us-page .us-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .us-page .us-sub { font-size: 13px; color: var(--us-muted); margin: 0; }

    .us-page .us-btn-primary {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 999px; border: 0;
        background: linear-gradient(180deg, #5b98c8, #4E8BBC); color: #fff; font: inherit; font-size: 13px; font-weight: 600;
        box-shadow: 0 1px 2px rgba(0,0,0,.12), 0 6px 16px rgba(78,139,188,.28), inset 0 1px 0 rgba(255,255,255,.25);
        transition: transform .15s ease, filter .15s ease; cursor: pointer;
    }
    .us-page .us-btn-primary:hover { filter: brightness(1.05); transform: translateY(-1px); }

    /* Alert */
    .us-page .us-alert {
        display: flex; gap: 10px; align-items: flex-start; margin-bottom: 18px; padding: 12px 16px; border-radius: 12px;
        font-size: 13px; line-height: 1.5;
        background: rgba(52,199,89,.1); border: 1px solid rgba(52,199,89,.22); color: #1f7a38;
    }
    .us-page .us-alert svg { flex-shrink: 0; margin-top: 2px; }
    .us-page .us-alert.error { background: rgba(255,59,48,.08); border-color: rgba(255,59,48,.18); color: #b42318; }

    /* Panel */
    .us-page .us-panel { background: #fff; border: 1px solid var(--us-line); border-radius: 18px; box-shadow: var(--us-shadow); overflow: hidden; margin-bottom: 32px; }
    .us-page .us-panel-head { display: flex; justify-content: space-between; align-items: center; gap: 15px; padding: 18px 20px 4px; }
    .us-page .us-panel-title { font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0; }
    .us-page .us-panel-sub { font-size: 12px; color: var(--us-muted); margin-top: 2px; }
    .us-page .us-count { display: inline-flex; align-items: center; padding: 3px 11px; border-radius: 999px; font-size: 12px; font-weight: 600; background: var(--us-accent-soft); color: #3b7aab; white-space: nowrap; }

    .us-page .us-toolbar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; padding: 14px 20px 16px; border-bottom: 1px solid var(--us-line); }
    .us-page .us-search { position: relative; flex: 1; min-width: 260px; }
    .us-page .us-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #8e8e93; pointer-events: none; }
    .us-page .us-input, .us-page .us-select {
        height: 38px; border-radius: 10px; border: 1px solid transparent; background: rgba(120,120,128,.1);
        color: var(--us-text); font: inherit; font-size: 13px; outline: none; transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .us-page .us-input { width: 100%; padding: 0 14px 0 36px; }
    .us-page .us-input::placeholder { color: #8e8e93; }
    .us-page .us-select {
        width: 180px; padding: 0 32px 0 12px; appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center;
    }
    .us-page .us-input:focus, .us-page .us-select:focus { background-color: #fff; border-color: var(--us-accent); box-shadow: 0 0 0 4px var(--us-accent-soft); }

    .us-page .us-btn {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 18px; border-radius: 10px; border: 0;
        background: var(--us-accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s;
    }
    .us-page .us-btn:hover { filter: brightness(1.06); }
    .us-page .us-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 16px; border-radius: 10px; border: 0;
        background: rgba(120,120,128,.1); color: #3a3a3c; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: background .15s;
    }
    .us-page .us-btn-ghost:hover { background: rgba(120,120,128,.18); }

    /* Tabel */
    .us-page .us-table-wrap { overflow-x: auto; }
    .us-page .us-table { width: 100%; min-width: 880px; border-collapse: collapse; }
    .us-page .us-table thead th {
        padding: 12px 20px; text-align: left; background: #fafafc; font-size: 11px; font-weight: 600;
        letter-spacing: .06em; text-transform: uppercase; color: var(--us-muted); border-bottom: 1px solid var(--us-line); white-space: nowrap;
    }
    .us-page .us-table tbody td { padding: 14px 20px; vertical-align: middle; border-bottom: 1px solid var(--us-line); font-size: 13px; white-space: nowrap; }
    .us-page .us-table tbody tr:last-child td { border-bottom: 0; }
    .us-page .us-table tbody tr { transition: background .15s; }
    .us-page .us-table tbody tr:hover { background: #f7f9fc; }
    .us-page .us-center { text-align: center !important; }
    .us-page .us-mono { font-variant-numeric: tabular-nums; font-weight: 500; color: #3a3a3c; }
    .us-page .us-muted { color: var(--us-muted); }

    .us-page .us-person { display: flex; align-items: center; gap: 12px; }
    .us-page .us-avatar { width: 36px; height: 36px; flex-shrink: 0; display: grid; place-items: center; border-radius: 50%; background: #C2E1FC; color: #2b5c8f; font-size: 12px; font-weight: 700; }
    .us-page .us-person-name { font-weight: 600; letter-spacing: -.005em; }
    .us-page .us-person-email { font-size: 11px; color: var(--us-muted); margin-top: 1px; }

    /* Role */
    .us-page .us-role { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; background: rgba(120,120,128,.12); color: #3a3a3c; }
    .us-page .us-role.admin    { background: rgba(88,86,214,.12); color: #5856d6; }
    .us-page .us-role.tu       { background: rgba(255,159,10,.16); color: #b26a00; }
    .us-page .us-role.karyawan { background: rgba(78,139,188,.14); color: #3b7aab; }

    /* Status */
    .us-page .us-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .us-page .us-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .us-page .us-badge-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .us-page .us-badge-gray  { background: rgba(120,120,128,.14); color: #6e6e73; }

    /* Aksi */
    .us-page .us-actions { display: inline-flex; align-items: center; gap: 4px; }
    .us-page .us-actions form { margin: 0; display: inline; }
    .us-page .us-act { display: inline-block; padding: 6px 12px; border-radius: 8px; border: 0; background: transparent; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; transition: background .15s; }
    .us-page .us-act-edit { color: #3b7aab; } .us-page .us-act-edit:hover { background: var(--us-accent-soft); }
    .us-page .us-act-del  { color: #c9342a; } .us-page .us-act-del:hover  { background: rgba(255,59,48,.12); }

    /* Empty */
    .us-page .us-empty { padding: 64px 20px !important; text-align: center; color: var(--us-muted); font-size: 13px; }
    .us-page .us-empty svg { width: 36px; height: 36px; margin-bottom: 10px; color: #c7c7cc; }
    .us-page .us-empty strong { display: block; font-size: 15px; color: var(--us-text); margin-bottom: 4px; }

    .us-page .us-foot { display: flex; justify-content: flex-end; padding: 14px 20px; border-top: 1px solid var(--us-line); background: #fafafc; }
    .us-page .us-foot svg { width: 15px; height: 15px; }

    @media (max-width: 700px) {
        .us-page .us-head { flex-direction: column; align-items: flex-start; }
        .us-page .us-select { width: 100%; }
    }
</style>

<div class="us-page">

    {{-- HEADER --}}
    <div class="us-head">
        <div>
            <div class="us-eyebrow"></div>
            <h1 class="us-title"></h1>
            <p class="us-sub"></p>
        </div>

        <a href="{{ route('users.create') }}" class="us-btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Akun
        </a>
    </div>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="us-alert">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m8 12.5 3 3 5-6"/></svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="us-alert error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="us-alert error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
            <div>{{ $errors->first() }}</div>
        </div>
    @endif

    {{-- PANEL --}}
    <div class="us-panel">

        <div class="us-panel-head">
            <div>
                <h2 class="us-panel-title">Daftar Akun</h2>
                <div class="us-panel-sub">Informasi pengguna dan hak akses</div>
            </div>
            <span class="us-count">{{ $users->total() }} akun</span>
        </div>

        <form action="{{ route('users.index') }}" method="GET" class="us-toolbar">
            <div class="us-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input type="text" name="search" class="us-input" placeholder="Cari nama, NIP, email, atau role…" value="{{ request('search') }}">
            </div>

            <select name="role" class="us-select">
                <option value="">Semua Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected((string) request('role') === (string) $role->id)>
                        {{ $role->nama_peran }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="us-btn">Cari</button>

            @if (request()->filled('search') || request()->filled('role'))
                <a href="{{ route('users.index') }}" class="us-btn-ghost">Reset</a>
            @endif
        </form>

        <div class="us-table-wrap">
            <table class="us-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>NIP</th>
                        <th>Unit Kerja</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="us-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        @php
                            $nama = $user->karyawan?->nama ?? $user->name ?? '-';

                            $inisial = collect(explode(' ', trim($nama)))
                                ->filter()
                                ->take(2)
                                ->map(fn ($kata) => mb_strtoupper(mb_substr($kata, 0, 1)))
                                ->implode('');

                            $kelasRole = match ($user->peran?->kode_peran) {
                                'ADMIN'        => 'admin',
                                'TU_ESELON_II' => 'tu',
                                'KARYAWAN'     => 'karyawan',
                                default        => '',
                            };

                            $aktif = (bool) ($user->karyawan?->aktif ?? false);
                        @endphp

                        <tr>
                            <td>
                                <div class="us-person">
                                    <div class="us-avatar">{{ $inisial ?: 'U' }}</div>
                                    <div>
                                        <div class="us-person-name">{{ $nama }}</div>
                                        <div class="us-person-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="us-mono">{{ $user->karyawan?->nip ?? '—' }}</td>

                            <td class="us-muted">{{ $user->karyawan?->unit?->nama_unit ?? '—' }}</td>

                            <td><span class="us-role {{ $kelasRole }}">{{ $user->peran?->nama_peran ?? '—' }}</span></td>

                            <td>
                                <span class="us-badge {{ $aktif ? 'us-badge-green' : 'us-badge-gray' }}">
                                    {{ $aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td class="us-center">
                                <div class="us-actions">
                                    <a href="{{ route('users.edit', $user) }}" class="us-act us-act-edit">Edit</a>

                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="delete-user-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="us-act us-act-del">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        @php $adaFilter = request()->filled('search') || request()->filled('role'); @endphp
                        <tr>
                            <td colspan="6" class="us-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                                <strong>{{ $adaFilter ? 'Akun tidak ditemukan' : 'Belum ada akun' }}</strong>
                                {{ $adaFilter ? 'Ubah kata kunci atau filter untuk mencari akun.' : 'Belum ada akun pengguna yang tersedia.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="us-foot">{{ $users->links() }}</div>
        @endif

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-user-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!confirm('Yakin ingin menghapus akun ini?')) {
                event.preventDefault();
            }
        });
    });
});
</script>

@endsection