@extends('layouts.app')

@section('title', 'Detail Karyawan | HumasIn')
@section('page-title', 'Detail Karyawan')
@section('page-description', 'Informasi lengkap karyawan')

@section('content')

<style>
    .kw-page {
        --kw-accent: #4E8BBC;
        --kw-accent-soft: rgba(78, 139, 188, .12);
        --kw-text: #1d1d1f;
        --kw-muted: #6e6e73;
        --kw-line: rgba(0, 0, 0, .07);
        --kw-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 960px; margin: 0 auto; color: var(--kw-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .kw-page *, .kw-page *::before, .kw-page *::after { box-sizing: border-box; }

    .kw-page .kw-back { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #3b7aab; transition: opacity .15s; }
    .kw-page .kw-back:hover { opacity: .7; }
    .kw-page .kw-back svg { width: 16px; height: 16px; }

    .kw-page .kw-card { background: #fff; border: 1px solid var(--kw-line); border-radius: 18px; box-shadow: var(--kw-shadow); overflow: hidden; margin-bottom: 32px; }

    .kw-page .kw-hero { display: flex; align-items: center; gap: 18px; padding: 28px 32px; border-bottom: 1px solid var(--kw-line); }
    .kw-page .kw-avatar { width: 64px; height: 64px; flex-shrink: 0; display: grid; place-items: center; border-radius: 50%; background: #C2E1FC; color: #2b5c8f; font-size: 22px; font-weight: 700; }
    .kw-page .kw-hero-text { flex: 1; min-width: 0; }
    .kw-page .kw-name { font-size: 22px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .kw-page .kw-role { font-size: 13px; color: var(--kw-muted); margin: 0; }

    .kw-page .kw-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .kw-page .kw-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .kw-page .kw-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .kw-page .kw-red   { background: rgba(255,59,48,.12); color: #c9342a; }
    .kw-page .kw-gray  { background: rgba(120,120,128,.14); color: #6e6e73; }
    .kw-page .kw-indigo { background: rgba(88,86,214,.12); color: #5856d6; }

    .kw-page .kw-body { padding: 28px 32px; }
    .kw-page .kw-section { font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--kw-muted); padding-bottom: 8px; border-bottom: 1px solid var(--kw-line); margin: 0 0 16px; }
    .kw-page .kw-section:not(:first-child) { margin-top: 28px; }

    .kw-page .kw-grid { display: grid; grid-template-columns: 1fr 1fr; border: 1px solid var(--kw-line); border-radius: 14px; overflow: hidden; }
    .kw-page .kw-kv { padding: 14px 18px; border-bottom: 1px solid var(--kw-line); min-width: 0; }
    .kw-page .kw-kv:nth-child(odd) { border-right: 1px solid var(--kw-line); }
    .kw-page .kw-kv:nth-last-child(-n+2):nth-child(odd),
    .kw-page .kw-kv:last-child { border-bottom: 0; }
    .kw-page .kw-kv:nth-last-child(2):nth-child(odd) { border-bottom: 0; }
    .kw-page .kw-label { font-size: 11px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--kw-muted); margin-bottom: 6px; }
    .kw-page .kw-value { font-size: 14px; font-weight: 600; line-height: 1.4; word-break: break-word; font-variant-numeric: tabular-nums; }
    .kw-page .kw-value.muted { color: var(--kw-muted); font-weight: 500; }

    .kw-page .kw-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 18px 32px; border-top: 1px solid var(--kw-line); background: #fafafc; }
    .kw-page .kw-btn, .kw-page .kw-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 20px; border-radius: 10px; border: 0;
        font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s;
    }
    .kw-page .kw-btn { background: var(--kw-accent); color: #fff; }
    .kw-page .kw-btn:hover { filter: brightness(1.06); }
    .kw-page .kw-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .kw-page .kw-btn-ghost:hover { background: rgba(120,120,128,.18); }

    @media (max-width: 700px) {
        .kw-page .kw-hero, .kw-page .kw-body, .kw-page .kw-foot { padding-left: 20px; padding-right: 20px; }
        .kw-page .kw-hero { flex-wrap: wrap; }
        .kw-page .kw-grid { grid-template-columns: 1fr; }
        .kw-page .kw-kv:nth-child(odd) { border-right: 0; }
        .kw-page .kw-kv { border-bottom: 1px solid var(--kw-line) !important; }
        .kw-page .kw-kv:last-child { border-bottom: 0 !important; }
    }
</style>

@php
    $akun = $karyawan->user;
    $peranAkun = $akun?->peran?->nama_peran;
@endphp

<div class="kw-page">

    <a href="{{ route('karyawan.index') }}" class="kw-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Karyawan
    </a>

    <div class="kw-card">

        {{-- HERO --}}
        <div class="kw-hero">
            <div class="kw-avatar">{{ strtoupper(mb_substr($karyawan->nama, 0, 1)) }}</div>

            <div class="kw-hero-text">
                <h2 class="kw-name">{{ $karyawan->nama }}</h2>
                <p class="kw-role">{{ $karyawan->jabatan ?? 'Jabatan belum diisi' }}</p>
            </div>

            @if ($karyawan->aktif)
                <span class="kw-badge kw-green">Aktif</span>
            @else
                <span class="kw-badge kw-red">Tidak Aktif</span>
            @endif
        </div>

        <div class="kw-body">

            {{-- DATA PRIBADI --}}
            <div class="kw-section">Data Pribadi</div>
            <div class="kw-grid">
                <div class="kw-kv">
                    <div class="kw-label">NIP</div>
                    <div class="kw-value">{{ $karyawan->nip }}</div>
                </div>
                <div class="kw-kv">
                    <div class="kw-label">Email</div>
                    <div class="kw-value">{{ $karyawan->email }}</div>
                </div>
                <div class="kw-kv">
                    <div class="kw-label">Jabatan</div>
                    <div class="kw-value {{ $karyawan->jabatan ? '' : 'muted' }}">{{ $karyawan->jabatan ?? 'Belum diisi' }}</div>
                </div>
                <div class="kw-kv">
                    <div class="kw-label">Status</div>
                    <div class="kw-value">{{ $karyawan->aktif ? 'Aktif' : 'Tidak Aktif' }}</div>
                </div>
            </div>

            {{-- PENEMPATAN --}}
            <div class="kw-section">Penempatan</div>
            <div class="kw-grid">
                <div class="kw-kv">
                    <div class="kw-label">Unit / Eselon II</div>
                    <div class="kw-value {{ $karyawan->unit ? '' : 'muted' }}">{{ $karyawan->unit->nama_unit ?? '-' }}</div>
                </div>
                <div class="kw-kv">
                    <div class="kw-label">Bagian / Subunit</div>
                    <div class="kw-value {{ $karyawan->subunitOrganisasi ? '' : 'muted' }}">{{ $karyawan->subunitOrganisasi->nama_subunit ?? '-' }}</div>
                </div>
            </div>

            {{-- AKUN --}}
            <div class="kw-section">Akun Sistem</div>
            <div class="kw-grid">
                <div class="kw-kv">
                    <div class="kw-label">Status Akun</div>
                    <div class="kw-value">
                        @if ($akun)
                            <span class="kw-badge kw-green">Sudah punya akun</span>
                        @else
                            <span class="kw-badge kw-gray">Belum punya akun</span>
                        @endif
                    </div>
                </div>
                <div class="kw-kv">
                    <div class="kw-label">Role</div>
                    <div class="kw-value">
                        @if ($peranAkun)
                            <span class="kw-badge kw-indigo">{{ $peranAkun }}</span>
                        @else
                            <span class="kw-value muted">-</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <div class="kw-foot">
            <a href="{{ route('karyawan.index') }}" class="kw-btn-ghost">Kembali</a>
            <a href="{{ route('karyawan.edit', $karyawan) }}" class="kw-btn">Edit Data</a>
        </div>

    </div>

</div>

@endsection