@extends('layouts.app')

@section('title', 'Dashboard | HumasIn')
@section('page-title', 'Dashboard')
@section('page-description', 'Akses layanan dan pantau pengajuan Anda')

@section('content')

<style>
    /* =========================================
       TOKEN & KERANGKA HALAMAN
    ========================================== */
    .kd-page {
        --kd-accent: #5c88d9;
        --kd-accent-soft: rgba(92, 136, 217, .12);
        --kd-line: #eceff4;
        --kd-muted: #8b93a1;
        --kd-text: #1f2a44;
        --kd-shadow: 0 2px 12px rgba(25, 35, 45, .05);

        display: flex;
        flex-direction: column;
        gap: 22px;
        color: var(--kd-text);
    }

    .kd-page .kd-stack { display: flex; flex-direction: column; gap: 20px; }

    /* Header */
    .kd-page .kd-title { margin: 0; font-size: 26px; font-weight: 800; letter-spacing: -.02em; }
    .kd-page .kd-sub { margin: 6px 0 0; font-size: 14px; color: var(--kd-muted); }

    /* Notifikasi */
    .kd-page .kd-alert { padding: 13px 16px; border-radius: 12px; border: 1px solid #bbf7d0; background: #ecfdf3; color: #166534; font-size: 14px; }
    .kd-page .kd-alert.err { border-color: #fecaca; background: #fef2f2; color: #991b1b; }

    /* =========================================
       IKON BERWARNA
    ========================================== */
    .kd-page .kd-q-icon { width: 46px; height: 46px; border-radius: 14px; display: grid; place-items: center; flex-shrink: 0; }
    .kd-page .kd-q-icon svg { width: 22px; height: 22px; }
    .kd-page .tone-blue   { background: #e8f0fe; color: #4f7fd6; }
    .kd-page .tone-indigo { background: #ede9fe; color: #6d5bd0; }
    .kd-page .tone-amber  { background: #fff4d6; color: #d98a0b; }
    .kd-page .tone-green  { background: #dcfce7; color: #16a34a; }
    .kd-page .tone-red    { background: #fee2e2; color: #dc2626; }

    /* =========================================
       PINTASAN
    ========================================== */
    .kd-page .kd-quick { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }

    .kd-page .kd-q {
        display: flex; align-items: center; gap: 14px;
        padding: 18px 20px;
        background: #fff; border: 1px solid var(--kd-line); border-radius: 16px;
        box-shadow: var(--kd-shadow);
        text-decoration: none; color: inherit;
        transition: transform .15s, box-shadow .15s, border-color .15s;
    }
    .kd-page .kd-q:hover { transform: translateY(-2px); border-color: #c9d6f2; box-shadow: 0 8px 20px rgba(92, 136, 217, .14); }
    .kd-page .kd-q-title { margin: 0; font-size: 15px; font-weight: 700; }
    .kd-page .kd-q-desc { margin: 3px 0 0; font-size: 12.5px; color: var(--kd-muted); }
    .kd-page .kd-q-arrow { margin-left: auto; width: 18px; height: 18px; color: #b6bcc9; flex-shrink: 0; transition: transform .15s, color .15s; }
    .kd-page .kd-q:hover .kd-q-arrow { transform: translateX(3px); color: var(--kd-accent); }

    /* =========================================
       STATISTIK
    ========================================== */
    .kd-page .kd-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }

    .kd-page .kd-stat {
        display: flex; align-items: center; gap: 14px;
        padding: 18px 20px;
        background: #fff; border: 1px solid var(--kd-line); border-radius: 16px;
        box-shadow: var(--kd-shadow);
    }
    .kd-page .kd-stat-label { font-size: 12.5px; font-weight: 600; color: var(--kd-muted); }
    .kd-page .kd-stat-value { font-size: 28px; font-weight: 800; line-height: 1.15; letter-spacing: -.02em; }

    /* =========================================
       BARIS & KARTU
    ========================================== */
    .kd-page .kd-row { display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr); gap: 20px; align-items: start; }
    .kd-page .kd-row.kd-row-alt { grid-template-columns: minmax(0, 1fr) minmax(0, 1.2fr); }

    .kd-page .kd-card { background: #fff; border: 1px solid var(--kd-line); border-radius: 18px; box-shadow: var(--kd-shadow); overflow: hidden; }
    .kd-page .kd-card-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 20px; border-bottom: 1px solid var(--kd-line); }
    .kd-page .kd-card-title { margin: 0; font-size: 15px; font-weight: 700; }
    .kd-page .kd-card-sub { margin-top: 3px; font-size: 12px; color: var(--kd-muted); }
    .kd-page .kd-link { font-size: 12.5px; font-weight: 700; color: var(--kd-accent); text-decoration: none; white-space: nowrap; }
    .kd-page .kd-link:hover { text-decoration: underline; }

    /* Daftar */
    .kd-page .kd-list { padding: 4px 20px 10px; }
    .kd-page .kd-item { display: flex; align-items: center; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--kd-line); }
    .kd-page .kd-item:last-child { border-bottom: 0; }
    .kd-page .kd-item-body { flex: 1; min-width: 0; }
    .kd-page .kd-item-title { font-size: 14px; font-weight: 600; word-break: break-word; }
    .kd-page .kd-item-meta { margin-top: 3px; font-size: 12px; color: var(--kd-muted); }
    .kd-page .kd-reason { margin-top: 8px; padding: 7px 10px; border-radius: 8px; background: #fef3f2; color: #b42318; font-size: 12px; line-height: 1.45; }
    .kd-page .kd-empty { padding: 30px 12px; text-align: center; font-size: 13px; color: var(--kd-muted); }

    /* Lencana status */
    .kd-page .kd-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 11px; border-radius: 999px; font-size: 11.5px; font-weight: 700; white-space: nowrap; flex-shrink: 0; background: #f1f3f6; color: #4b5563; }
    .kd-page .kd-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .kd-page .b-green, .kd-page .b-approved { background: #dcfce7; color: #15803d; }
    .kd-page .b-red, .kd-page .b-rejected { background: #fee2e2; color: #b91c1c; }
    .kd-page .b-amber, .kd-page .b-yellow, .kd-page .b-pending { background: #fef3c7; color: #92400e; }
    .kd-page .b-orange { background: #ffedd5; color: #c2410c; }
    .kd-page .b-blue, .kd-page .b-returned { background: #e0ecff; color: #2f5fb3; }
    .kd-page .b-indigo, .kd-page .b-purple { background: #ede9fe; color: #5b45c9; }
    .kd-page .b-gray, .kd-page .b-grey, .kd-page .b-slate { background: #f1f3f6; color: #4b5563; }

    /* Tanggal (event mendatang) */
    .kd-page .kd-date { width: 48px; height: 52px; border-radius: 12px; background: var(--kd-accent-soft); color: #3b6bc4; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0; line-height: 1.1; }
    .kd-page .kd-date b { font-size: 18px; font-weight: 800; }
    .kd-page .kd-date small { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }

    /* Jadwal ruangan minggu ini */
    .kd-page .kd-day { padding: 12px 20px 14px; border-bottom: 1px solid var(--kd-line); }
    .kd-page .kd-day:last-child { border-bottom: 0; }
    .kd-page .kd-day.today { background: linear-gradient(90deg, var(--kd-accent-soft), rgba(92, 136, 217, 0) 70%); box-shadow: inset 3px 0 0 var(--kd-accent); }
    .kd-page .kd-day-label { display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-size: 12px; font-weight: 700; color: #4b5563; }
    .kd-page .kd-today-tag { padding: 2px 8px; border-radius: 999px; background: var(--kd-accent); color: #fff; font-size: 10px; font-weight: 700; }
    .kd-page .kd-slot { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: 6px; padding: 8px 12px; border-radius: 10px; border-left: 3px solid var(--kd-accent); background: #eef3ff; font-size: 12.5px; }
    .kd-page .kd-slot span { font-weight: 700; font-variant-numeric: tabular-nums; color: #3b6bc4; white-space: nowrap; }
    .kd-page .kd-slot.pending { border-left-color: #f5a524; background: #fff7e0; }
    .kd-page .kd-slot.pending span { color: #b45309; }
    .kd-page .kd-free { padding: 6px 0 2px; font-size: 12.5px; color: var(--kd-muted); }

    /* =========================================
       KALENDER EVENT
       Warna diambil langsung dari data (--tx = warna label, --bg = versi pudarnya),
       jadi selalu sama dengan halaman Event & Kalender.
    ========================================== */
    .kd-page .kd-cal-bar { display: flex; align-items: center; gap: 8px; padding: 14px 20px; }
    .kd-page .kd-month { min-width: 150px; font-size: 15px; font-weight: 700; letter-spacing: -.01em; }
    .kd-page .kd-icon-btn { width: 30px; height: 30px; display: grid; place-items: center; border: 0; border-radius: 8px; cursor: pointer; background: rgba(120, 120, 128, .1); color: #3a3a3c; }
    .kd-page .kd-icon-btn:hover { background: rgba(120, 120, 128, .2); }
    .kd-page .kd-icon-btn svg { width: 14px; height: 14px; }
    .kd-page .kd-today-btn { height: 30px; padding: 0 12px; border: 0; border-radius: 8px; background: var(--kd-accent-soft); color: #3b6bc4; font: inherit; font-size: 12px; font-weight: 700; cursor: pointer; }
    .kd-page .kd-today-btn:hover { background: rgba(92, 136, 217, .2); }
    .kd-page .kd-spacer { flex: 1; }

    .kd-page .kd-wd { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); background: #fafbfd; border-top: 1px solid var(--kd-line); border-bottom: 1px solid var(--kd-line); }
    .kd-page .kd-wd div { padding: 8px 4px; text-align: center; font-size: 10.5px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--kd-muted); }

    .kd-page .kd-grid { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); }
    .kd-page .kd-cell { min-height: 84px; padding: 5px; border-right: 1px solid var(--kd-line); border-bottom: 1px solid var(--kd-line); cursor: pointer; overflow: hidden; transition: background .15s; }
    .kd-page .kd-cell:nth-child(7n) { border-right: 0; }
    .kd-page .kd-cell:nth-last-child(-n+7) { border-bottom: 0; }
    .kd-page .kd-cell:hover { background: #f7f9fc; }
    .kd-page .kd-cell:focus-visible { outline: 2px solid var(--kd-accent); outline-offset: -2px; }
    .kd-page .kd-cell.out { background: #fcfcfd; }
    .kd-page .kd-cell.out .kd-dn { color: #c0c3cc; }
    .kd-page .kd-cell.sel { background: var(--kd-accent-soft); box-shadow: inset 0 0 0 1.5px var(--kd-accent); }
    .kd-page .kd-dn { display: inline-grid; place-items: center; min-width: 22px; height: 22px; padding: 0 5px; margin-bottom: 3px; border-radius: 999px; font-size: 11.5px; font-weight: 600; color: #3a3a3c; font-variant-numeric: tabular-nums; }
    .kd-page .kd-cell.today .kd-dn { background: var(--kd-accent); color: #fff; }

    .kd-page .kd-chip { display: block; margin-bottom: 2px; padding: 2px 5px; border-radius: 5px; border-left: 3px solid var(--tx); background: var(--bg); color: var(--kd-text); font-size: 10px; font-weight: 600; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .kd-page .kd-more { padding: 0 4px; font-size: 10px; font-weight: 700; color: var(--kd-muted); }

    .kd-page .kd-legend { display: flex; flex-wrap: wrap; gap: 8px 16px; padding: 10px 20px; border-top: 1px solid var(--kd-line); background: #fafbfd; font-size: 12px; color: var(--kd-muted); }
    .kd-page .kd-legend span { display: inline-flex; align-items: center; gap: 6px; }
    .kd-page .kd-legend i { width: 9px; height: 9px; border-radius: 3px; background: var(--tx); }

    .kd-page .kd-day-box { padding: 14px 20px 18px; border-top: 1px solid var(--kd-line); }
    .kd-page .kd-day-title { margin-bottom: 10px; font-size: 13px; font-weight: 700; }
    .kd-page .kd-ev { margin-bottom: 6px; padding: 10px 12px; border-radius: 10px; border-left: 3px solid var(--tx); background: var(--bg); }
    .kd-page .kd-ev-name { font-size: 13px; font-weight: 700; color: var(--kd-text); word-break: break-word; }
    .kd-page .kd-ev-meta { margin-top: 2px; font-size: 11.5px; color: #4a4a4f; }
    .kd-page .kd-day-empty { font-size: 12.5px; color: var(--kd-muted); }

    /* =========================================
       RESPONSIF
    ========================================== */
    @media (max-width: 1100px) {
        .kd-page .kd-row,
        .kd-page .kd-row.kd-row-alt { grid-template-columns: minmax(0, 1fr); }
    }

    @media (max-width: 600px) {
        .kd-page .kd-title { font-size: 22px; }
        .kd-page .kd-cell { min-height: 64px; }
        .kd-page .kd-card-head,
        .kd-page .kd-cal-bar { padding-left: 16px; padding-right: 16px; }
        .kd-page .kd-list,
        .kd-page .kd-day,
        .kd-page .kd-day-box,
        .kd-page .kd-legend { padding-left: 16px; padding-right: 16px; }
    }
</style>

@php
    $nama = $user->karyawan?->nama ?? $user->name ?? 'Karyawan';
    $unit = $user->karyawan?->unit?->nama_unit;
@endphp

<div class="kd-page">

    <div class="kd-head">
        <h2 class="kd-title">Halo, {{ $nama }}</h2>
        <p class="kd-sub">{{ now()->translatedFormat('l, d F Y') }}@if($unit) · {{ $unit }}@endif</p>
    </div>

    @if (session('success')) <div class="kd-alert">{{ session('success') }}</div> @endif
    @if (session('error'))   <div class="kd-alert err">{{ session('error') }}</div> @endif

    {{-- PINTASAN --}}
    <div class="kd-quick">
        <a href="{{ route('peminjaman.aset') }}" class="kd-q">
            <div class="kd-q-icon tone-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg></div>
            <div><h3 class="kd-q-title">Pinjam Aset</h3><p class="kd-q-desc">Barang dan peralatan</p></div>
            <svg class="kd-q-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
        </a>
        <a href="{{ route('peminjaman.ruangan') }}" class="kd-q">
            <div class="kd-q-icon tone-indigo"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/><path d="M16 9h2a2 2 0 0 1 2 2v10M2 21h20M9 7h2M9 11h2M9 15h2"/></svg></div>
            <div><h3 class="kd-q-title">Pinjam Ruangan</h3><p class="kd-q-desc">Ruang rapat dan fasilitas</p></div>
            <svg class="kd-q-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
        </a>
        <a href="{{ route('dukungan.create') }}" class="kd-q">
            <div class="kd-q-icon tone-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="3"/><path d="M3 9h18M8 2v4M16 2v4"/></svg></div>
            <div><h3 class="kd-q-title">Dukungan Kegiatan</h3><p class="kd-q-desc">Ajukan bantuan Humas</p></div>
            <svg class="kd-q-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
        </a>
    </div>

    {{-- STATISTIK --}}
    <div class="kd-stats">
        <div class="kd-stat"><div class="kd-q-icon tone-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div><div><div class="kd-stat-label">Menunggu</div><div class="kd-stat-value">{{ $stat['menunggu'] }}</div></div></div>
        <div class="kd-stat"><div class="kd-q-icon tone-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 3 3 5-6"/></svg></div><div><div class="kd-stat-label">Sedang Dipinjam</div><div class="kd-stat-value">{{ $stat['dipinjam'] }}</div></div></div>
        <div class="kd-stat"><div class="kd-q-icon {{ $stat['terlambat'] ? 'tone-red' : 'tone-green' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/></svg></div><div><div class="kd-stat-label">Terlambat Kembali</div><div class="kd-stat-value">{{ $stat['terlambat'] }}</div></div></div>
        <div class="kd-stat"><div class="kd-q-icon tone-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m15 9-6 6M9 9l6 6"/></svg></div><div><div class="kd-stat-label">Ditolak</div><div class="kd-stat-value">{{ $stat['ditolak'] }}</div></div></div>
    </div>

    {{-- PENGAJUAN + JADWAL --}}
    <div class="kd-row">

        <div class="kd-card">
            <div class="kd-card-head">
                <div><h3 class="kd-card-title">Pengajuan Saya</h3><div class="kd-card-sub">Status pengajuan terbaru</div></div>
                <a href="{{ route('peminjaman.kelola') }}" class="kd-link">Semua →</a>
            </div>
            <div class="kd-list">
                @forelse ($pengajuan as $p)
                    <div class="kd-item" style="align-items: flex-start;">
                        <div class="kd-item-body">
                            <div class="kd-item-title">{{ $p['judul'] }}</div>
                            <div class="kd-item-meta">{{ $p['jenis'] }} · {{ $p['tanggal'] }}</div>
                            @if ($p['alasan'])
                                <div class="kd-reason">Alasan: {{ $p['alasan'] }}</div>
                            @endif
                        </div>
                        <span class="kd-badge {{ $p['kelas'] }}">{{ $p['label'] }}</span>
                    </div>
                @empty
                    <div class="kd-empty">Belum ada pengajuan. Mulai dari pintasan di atas.</div>
                @endforelse
            </div>
        </div>

        <div class="kd-card">
            <div class="kd-card-head">
                <div><h3 class="kd-card-title">Jadwal Ruangan Minggu Ini</h3><div class="kd-card-sub">Cek sebelum mengajukan</div></div>
                <a href="{{ route('peminjaman.index') }}" class="kd-link">Kalender →</a>
            </div>
            @foreach ($jadwalMinggu as $hari)
                <div class="kd-day {{ $hari['today'] ? 'today' : '' }}">
                    <div class="kd-day-label">{{ $hari['label'] }} @if($hari['today'])<span class="kd-today-tag">Hari ini</span>@endif</div>
                    @forelse ($hari['items'] as $s)
                        <div class="kd-slot {{ $s['pending'] ? 'pending' : '' }}">
                            <div><b>{{ $s['ruangan'] }}</b> · {{ $s['peminjam'] }}</div>
                            <span>{{ $s['jam'] }}</span>
                        </div>
                    @empty
                        <div class="kd-free">Semua ruangan kosong</div>
                    @endforelse
                </div>
            @endforeach
        </div>
    </div>

    {{-- PEMINJAMAN AKTIF + EVENT --}}
    <div class="kd-row kd-row-alt">

        <div class="kd-stack">

            <div class="kd-card">
                <div class="kd-card-head">
                    <div><h3 class="kd-card-title">Sedang Saya Pinjam</h3><div class="kd-card-sub">Tenggat pengembalian</div></div>
                </div>
                <div class="kd-list">
                    @forelse ($aktif as $a)
                        <div class="kd-item">
                            <div class="kd-item-body">
                                <div class="kd-item-title">{{ $a['nama'] }}{{ $a['jumlah'] && $a['jumlah'] > 1 ? ' · ' . $a['jumlah'] . ' unit' : '' }}</div>
                                <div class="kd-item-meta">{{ $a['jenis'] }} · Batas {{ $a['batas'] }}</div>
                            </div>
                            <span class="kd-badge {{ $a['terlambat'] ? 'b-red' : 'b-green' }}">{{ $a['terlambat'] ? 'Terlambat' : 'Aktif' }}</span>
                        </div>
                    @empty
                        <div class="kd-empty">Tidak ada barang atau ruangan yang sedang dipinjam.</div>
                    @endforelse
                </div>
            </div>

            <div class="kd-card">
                <div class="kd-card-head">
                    <div><h3 class="kd-card-title">Event Mendatang</h3><div class="kd-card-sub">Agenda terdekat</div></div>
                </div>
                <div class="kd-list">
                    @forelse ($eventMendatang as $e)
                        @php $tgl = \Carbon\Carbon::parse($e->tanggal_pelaksanaan); @endphp
                        <div class="kd-item">
                            <div class="kd-date"><b>{{ $tgl->format('d') }}</b><small>{{ $tgl->translatedFormat('M') }}</small></div>
                            <div class="kd-item-body">
                                <div class="kd-item-title">{{ $e->nama_event }}</div>
                                <div class="kd-item-meta">{{ $tgl->translatedFormat('l') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="kd-empty">Belum ada event mendatang.</div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- KALENDER EVENT (data & warna sama dengan halaman Event/Kalender) --}}
        <div class="kd-card">
            <div class="kd-card-head">
                <div><h3 class="kd-card-title">Kalender Event</h3><div class="kd-card-sub">Klik tanggal untuk melihat kegiatan</div></div>
                <a href="{{ route('event.index') }}" class="kd-link">Lihat →</a>
            </div>

            <div class="kd-cal-bar">
                <div class="kd-month" id="kdMonth">—</div>
                <button type="button" class="kd-icon-btn" id="kdPrev" aria-label="Bulan sebelumnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg></button>
                <button type="button" class="kd-icon-btn" id="kdNext" aria-label="Bulan berikutnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg></button>
                <span class="kd-spacer"></span>
                <button type="button" class="kd-today-btn" id="kdToday">Hari ini</button>
            </div>

            <div class="kd-wd"><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div></div>
            <div class="kd-grid" id="kdGrid"></div>

            {{-- Legenda: sama persis dengan pilihan warna di halaman Kalender --}}
            <div class="kd-legend">
                <span style="--tx: #6EAE78;"><i></i> Hijau (Internal)</span>
                <span style="--tx: #E5A044;"><i></i> Oranye (Penting)</span>
                <span style="--tx: #5C88D9;"><i></i> Biru (Eksternal)</span>
                <span style="--tx: #8B654B;"><i></i> Coklat (Khusus)</span>
            </div>

            <div class="kd-day-box">
                <div class="kd-day-title" id="kdDayTitle">—</div>
                <div id="kdDayList"><div class="kd-day-empty">Memuat agenda…</div></div>
            </div>
        </div>

    </div>

</div>

<script>
(function () {
    const URL_EVENTS = '{{ route("kalender.events.json") }}';
    const BULAN = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const HARI = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const WARNA_RESMI = ['#6EAE78', '#E5A044', '#5C88D9', '#8B654B'];
    const WARNA_DEFAULT = '#6EAE78';
    const ESC_MAP = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' };

    const pad = function (n) { return String(n).padStart(2, '0'); };
    const key = function (d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); };
    const fromKey = function (k) { const p = k.split('-'); return new Date(+p[0], +p[1] - 1, +p[2]); };
    const esc = function (t) {
        return String(t == null ? '' : t).replace(/[&<>"']/g, function (c) { return ESC_MAP[c]; });
    };

    // Warna dari server (kode hex) → dipakai apa adanya, sama dengan halaman Kalender
    const warna = function (c) {
        c = String(c || '').toUpperCase();
        return WARNA_RESMI.indexOf(c) > -1 ? c : WARNA_DEFAULT;
    };
    const gaya = function (c) { const w = warna(c); return '--tx:' + w + ';--bg:' + w + '26'; };

    const jamDari = function (s) {
        s = String(s || '');
        if (s.indexOf('T') === -1) return '';
        const j = s.slice(11, 16);
        return j === '00:00' ? '' : j;
    };

    const today = new Date();
    let view = new Date(today.getFullYear(), today.getMonth(), 1);
    let selected = key(today);
    let map = {};
    let status = 'memuat'; // memuat | siap | gagal

    function susun(events) {
        map = {};
        events.forEach(function (e) {
            const k = String(e.start || '').slice(0, 10);
            if (!k) return;
            (map[k] = map[k] || []).push(e);
        });
        Object.keys(map).forEach(function (k) {
            map[k].sort(function (a, b) { return String(a.start).localeCompare(String(b.start)); });
        });
    }

    function render() {
        document.getElementById('kdMonth').textContent = BULAN[view.getMonth()] + ' ' + view.getFullYear();

        const offset = (view.getDay() + 6) % 7;
        const start = new Date(view); start.setDate(1 - offset);
        let html = '';

        for (let i = 0; i < 42; i++) {
            const d = new Date(start); d.setDate(start.getDate() + i);
            const k = key(d), evs = map[k] || [];
            const cls = ['kd-cell'];
            if (d.getMonth() !== view.getMonth()) cls.push('out');
            if (k === key(today)) cls.push('today');
            if (k === selected) cls.push('sel');

            const chips = evs.slice(0, 2).map(function (e) {
                return '<span class="kd-chip" style="' + gaya(e.color) + '" title="' + esc(e.title) + '">' + esc(e.title) + '</span>';
            }).join('');
            const more = evs.length > 2 ? '<div class="kd-more">+' + (evs.length - 2) + ' lagi</div>' : '';

            html += '<div class="' + cls.join(' ') + '" data-k="' + k + '" tabindex="0" role="button" aria-label="' +
                d.getDate() + ' ' + BULAN[d.getMonth()] + (evs.length ? ', ' + evs.length + ' kegiatan' : '') + '">' +
                '<span class="kd-dn">' + d.getDate() + '</span>' + chips + more + '</div>';
        }
        document.getElementById('kdGrid').innerHTML = html;

        const d = fromKey(selected), evs = map[selected] || [];
        document.getElementById('kdDayTitle').textContent =
            HARI[d.getDay()] + ', ' + d.getDate() + ' ' + BULAN[d.getMonth()] + ' ' + d.getFullYear();

        let isi;
        if (status === 'memuat') {
            isi = '<div class="kd-day-empty">Memuat agenda…</div>';
        } else if (status === 'gagal') {
            isi = '<div class="kd-day-empty">Agenda gagal dimuat. Muat ulang halaman untuk mencoba lagi.</div>';
        } else if (!evs.length) {
            isi = '<div class="kd-day-empty">Tidak ada kegiatan pada tanggal ini.</div>';
        } else {
            isi = evs.map(function (e) {
                const p = e.extendedProps || {};
                const jam = jamDari(e.start);
                const jenis = p.type === 'pengingat' ? 'Pengingat' : 'Dukungan kegiatan';
                const meta = [jenis, jam ? jam + ' WIB' : '', p.keterangan || ''].filter(Boolean).join(' · ');
                return '<div class="kd-ev" style="' + gaya(e.color) + '">' +
                    '<div class="kd-ev-name">' + esc(e.title) + '</div>' +
                    '<div class="kd-ev-meta">' + esc(meta) + '</div></div>';
            }).join('');
        }
        document.getElementById('kdDayList').innerHTML = isi;
    }

    function pilihTanggal(sel) {
        selected = sel.dataset.k;
        const d = fromKey(selected);
        if (d.getMonth() !== view.getMonth()) view = new Date(d.getFullYear(), d.getMonth(), 1);
        render();
    }

    const grid = document.getElementById('kdGrid');

    grid.addEventListener('click', function (e) {
        const c = e.target.closest('.kd-cell');
        if (c) pilihTanggal(c);
    });

    grid.addEventListener('keydown', function (e) {
        if (e.key !== 'Enter' && e.key !== ' ') return;
        const c = e.target.closest('.kd-cell');
        if (!c) return;
        e.preventDefault();
        pilihTanggal(c);
        const lagi = grid.querySelector('.kd-cell[data-k="' + selected + '"]');
        if (lagi) lagi.focus();
    });

    document.getElementById('kdPrev').addEventListener('click', function () { view = new Date(view.getFullYear(), view.getMonth() - 1, 1); render(); });
    document.getElementById('kdNext').addEventListener('click', function () { view = new Date(view.getFullYear(), view.getMonth() + 1, 1); render(); });
    document.getElementById('kdToday').addEventListener('click', function () { view = new Date(today.getFullYear(), today.getMonth(), 1); selected = key(today); render(); });

    render();

    // Ambil data dari sumber yang sama dengan halaman Event & Kalender
    fetch(URL_EVENTS, { headers: { 'Accept': 'application/json' } })
        .then(function (res) { if (!res.ok) throw new Error(res.status); return res.json(); })
        .then(function (data) { susun(Array.isArray(data) ? data : []); status = 'siap'; render(); })
        .catch(function () { status = 'gagal'; render(); });
})();
</script>

@endsection