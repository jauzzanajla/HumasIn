@extends('layouts.app')

@section('title', 'Detail Unit | HumasIn')
@section('page-title', 'Detail Unit')
@section('page-description', 'Informasi unit dan daftar karyawan')

@section('content')

<style>
    .ut-page {
        --ut-accent: #4E8BBC;
        --ut-accent-soft: rgba(78, 139, 188, .12);
        --ut-text: #1d1d1f;
        --ut-muted: #6e6e73;
        --ut-line: rgba(0, 0, 0, .07);
        --ut-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 1280px; margin: 0 auto; color: var(--ut-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .ut-page *, .ut-page *::before, .ut-page *::after { box-sizing: border-box; }

    .ut-page .ut-back { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #3b7aab; transition: opacity .15s; }
    .ut-page .ut-back:hover { opacity: .7; }
    .ut-page .ut-back svg { width: 16px; height: 16px; }

    .ut-page .ut-head { margin-bottom: 24px; }
    .ut-page .ut-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .ut-page .ut-sub { font-size: 13px; color: var(--ut-muted); margin: 0; }

    /* Info unit */
    .ut-page .ut-info { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
    .ut-page .ut-stat {
        display: flex; align-items: center; gap: 16px; padding: 20px; background: #fff;
        border: 1px solid var(--ut-line); border-radius: 16px; box-shadow: var(--ut-shadow);
    }
    .ut-page .ut-stat-icon { width: 44px; height: 44px; flex-shrink: 0; display: grid; place-items: center; border-radius: 12px; }
    .ut-page .ut-stat-icon svg { width: 22px; height: 22px; }
    .ut-page .ut-tone-blue   { background: rgba(78,139,188,.14); color: #3b7aab; }
    .ut-page .ut-tone-indigo { background: rgba(88,86,214,.12); color: #5856d6; }
    .ut-page .ut-tone-green  { background: rgba(52,199,89,.14); color: #248a3d; }
    .ut-page .ut-tone-gray   { background: rgba(120,120,128,.14); color: #6e6e73; }
    .ut-page .ut-stat-label { font-size: 12px; font-weight: 500; color: var(--ut-muted); margin-bottom: 3px; }
    .ut-page .ut-stat-value { font-size: 16px; font-weight: 700; letter-spacing: -.01em; line-height: 1.3; }
    .ut-page .ut-stat-text { min-width: 0; }

    /* Panel */
    .ut-page .ut-panel { background: #fff; border: 1px solid var(--ut-line); border-radius: 18px; box-shadow: var(--ut-shadow); overflow: hidden; margin-bottom: 32px; }
    .ut-page .ut-panel-head { display: flex; justify-content: space-between; align-items: center; gap: 15px; padding: 18px 20px 4px; }
    .ut-page .ut-panel-title { font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0; }
    .ut-page .ut-panel-sub { font-size: 12px; color: var(--ut-muted); margin-top: 2px; }
    .ut-page .ut-count { display: inline-flex; padding: 3px 11px; border-radius: 999px; font-size: 12px; font-weight: 600; background: var(--ut-accent-soft); color: #3b7aab; white-space: nowrap; }

    .ut-page .ut-toolbar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; padding: 14px 20px 16px; border-bottom: 1px solid var(--ut-line); }
    .ut-page .ut-search { position: relative; flex: 1; min-width: 260px; }
    .ut-page .ut-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #8e8e93; pointer-events: none; }
    .ut-page .ut-input {
        width: 100%; height: 38px; padding: 0 14px 0 36px; border-radius: 10px; border: 1px solid transparent;
        background: rgba(120,120,128,.1); color: var(--ut-text); font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .ut-page .ut-input::placeholder { color: #8e8e93; }
    .ut-page .ut-input:focus { background: #fff; border-color: var(--ut-accent); box-shadow: 0 0 0 4px var(--ut-accent-soft); }

    .ut-page .ut-btn {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 18px; border-radius: 10px; border: 0;
        background: var(--ut-accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s;
    }
    .ut-page .ut-btn:hover { filter: brightness(1.06); }
    .ut-page .ut-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 16px; border-radius: 10px; border: 0;
        background: rgba(120,120,128,.1); color: #3a3a3c; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: background .15s;
    }
    .ut-page .ut-btn-ghost:hover { background: rgba(120,120,128,.18); }

    /* Tabel */
    .ut-page .ut-table-wrap { overflow-x: auto; }
    .ut-page .ut-table { width: 100%; min-width: 900px; border-collapse: collapse; }
    .ut-page .ut-table thead th {
        padding: 12px 20px; text-align: left; background: #fafafc; font-size: 11px; font-weight: 600;
        letter-spacing: .06em; text-transform: uppercase; color: var(--ut-muted); border-bottom: 1px solid var(--ut-line); white-space: nowrap;
    }
    .ut-page .ut-table tbody td { padding: 14px 20px; vertical-align: middle; border-bottom: 1px solid var(--ut-line); font-size: 13px; }
    .ut-page .ut-table tbody tr:last-child td { border-bottom: 0; }
    .ut-page .ut-table tbody tr { transition: background .15s; }
    .ut-page .ut-table tbody tr:hover { background: #f7f9fc; }
    .ut-page .ut-center { text-align: center !important; }
    .ut-page .ut-no { color: var(--ut-muted); font-variant-numeric: tabular-nums; }
    .ut-page .ut-mono { font-variant-numeric: tabular-nums; font-weight: 600; letter-spacing: .01em; }
    .ut-page .ut-muted { color: var(--ut-muted); }

    .ut-page .ut-person { display: flex; align-items: center; gap: 12px; }
    .ut-page .ut-avatar { width: 34px; height: 34px; flex-shrink: 0; display: grid; place-items: center; border-radius: 50%; background: #C2E1FC; color: #2b5c8f; font-size: 12px; font-weight: 700; }
    .ut-page .ut-name { font-weight: 600; letter-spacing: -.005em; }

    .ut-page .ut-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .ut-page .ut-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .ut-page .ut-badge-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .ut-page .ut-badge-red   { background: rgba(255,59,48,.12); color: #c9342a; }

    .ut-page .ut-actions { display: inline-flex; align-items: center; gap: 4px; }
    .ut-page .ut-act { display: inline-block; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; transition: background .15s; }
    .ut-page .ut-act-detail { color: #3a3a3c; } .ut-page .ut-act-detail:hover { background: rgba(120,120,128,.14); }
    .ut-page .ut-act-edit   { color: #3b7aab; } .ut-page .ut-act-edit:hover   { background: var(--ut-accent-soft); }

    .ut-page .ut-empty { padding: 64px 20px !important; text-align: center; color: var(--ut-muted); font-size: 13px; }
    .ut-page .ut-empty svg { width: 36px; height: 36px; margin-bottom: 10px; color: #c7c7cc; }

    @media (max-width: 900px) { .ut-page .ut-info { grid-template-columns: 1fr; } }
</style>

<div class="ut-page">

    <a href="{{ route('karyawan.index', ['tab' => 'unit']) }}" class="ut-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kembali ke daftar unit
    </a>

    {{-- HEADER --}}
    <div class="ut-head">
        <h2 class="ut-title">{{ $unit->nama_unit }}</h2>
        <p class="ut-sub">Kode Unit: {{ $unit->kode_unit }}</p>
    </div>

    {{-- INFORMASI UNIT --}}
    <div class="ut-info">
        <div class="ut-stat">
            <div class="ut-stat-icon ut-tone-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
            </div>
            <div class="ut-stat-text">
                <div class="ut-stat-label">Kode Unit</div>
                <div class="ut-stat-value">{{ $unit->kode_unit }}</div>
            </div>
        </div>

        <div class="ut-stat">
            <div class="ut-stat-icon ut-tone-indigo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/><path d="M16 9h2a2 2 0 0 1 2 2v10M2 21h20M9 7h2M9 11h2M9 15h2"/></svg>
            </div>
            <div class="ut-stat-text">
                <div class="ut-stat-label">Nama Unit</div>
                <div class="ut-stat-value">{{ $unit->nama_unit }}</div>
            </div>
        </div>

        <div class="ut-stat">
            <div class="ut-stat-icon {{ $unit->aktif ? 'ut-tone-green' : 'ut-tone-gray' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 3 3 5-6"/></svg>
            </div>
            <div class="ut-stat-text">
                <div class="ut-stat-label">Status</div>
                @if ($unit->aktif)
                    <span class="ut-badge ut-badge-green">Aktif</span>
                @else
                    <span class="ut-badge ut-badge-red">Tidak Aktif</span>
                @endif
            </div>
        </div>
    </div>

    {{-- DAFTAR KARYAWAN --}}
    <div class="ut-panel">

        <div class="ut-panel-head">
            <div>
                <h3 class="ut-panel-title">Daftar Karyawan</h3>
                <div class="ut-panel-sub">Karyawan yang terdaftar pada unit ini.</div>
            </div>
            <span class="ut-count">{{ $karyawan->count() }} karyawan</span>
        </div>

        <form method="GET" action="{{ route('karyawan.unit.detail', $unit) }}" class="ut-toolbar">
            <div class="ut-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input type="text" name="search" class="ut-input" value="{{ request('search') }}" placeholder="Cari NIP, nama, email, atau jabatan…">
            </div>

            <button type="submit" class="ut-btn">Cari</button>

            @if (request('search'))
                <a href="{{ route('karyawan.unit.detail', $unit) }}" class="ut-btn-ghost">Reset</a>
            @endif
        </form>

        <div class="ut-table-wrap">
            <table class="ut-table">
                <thead>
                    <tr>
                        <th style="width: 56px;">No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th class="ut-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($karyawan as $index => $item)
                        <tr>
                            <td class="ut-no">{{ $index + 1 }}</td>
                            <td class="ut-mono">{{ $item->nip }}</td>
                            <td>
                                <div class="ut-person">
                                    <div class="ut-avatar">{{ strtoupper(mb_substr($item->nama, 0, 1)) }}</div>
                                    <span class="ut-name">{{ $item->nama }}</span>
                                </div>
                            </td>
                            <td class="ut-muted">{{ $item->email }}</td>
                            <td>{{ $item->jabatan ?? '—' }}</td>
                            <td>
                                @if ($item->aktif)
                                    <span class="ut-badge ut-badge-green">Aktif</span>
                                @else
                                    <span class="ut-badge ut-badge-red">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="ut-center" style="white-space: nowrap;">
                                <div class="ut-actions">
                                    <a href="{{ route('karyawan.show', $item) }}" class="ut-act ut-act-detail">Detail</a>
                                    <a href="{{ route('karyawan.edit', $item) }}" class="ut-act ut-act-edit">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="ut-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0M16 4.5a3.5 3.5 0 0 1 0 7M18 14.5a6.5 6.5 0 0 1 3.5 5.5"/></svg>
                                <div>
                                    @if (request('search'))
                                        Tidak ada karyawan yang sesuai dengan pencarian.
                                    @else
                                        Belum ada karyawan pada unit ini.
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection