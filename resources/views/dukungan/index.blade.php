@extends('layouts.app')

@section('title', 'Event & Kalender | HumasIn')
@section('page-title', 'Event & Kalender Kegiatan')
@section('page-description', 'Kalender agenda dan pengelolaan permohonan dukungan kegiatan Humas DJPK')

@section('content')

@php
    $bisaDelegasi = $bisaDelegasi ?? false;
    $bisaKelolaStatus = $bisaKelolaStatus ?? false;

    $statusMeta = [
        'PENDING'        => ['label' => 'Menunggu',      'class' => 'b-amber'],
        'DIIDELEGASIKAN' => ['label' => 'Didelegasikan', 'class' => 'b-blue'],
        'DIPROSES'       => ['label' => 'Diproses',      'class' => 'b-blue'],
        'SELESAI'        => ['label' => 'Selesai',       'class' => 'b-green'],
        'DITOLAK'        => ['label' => 'Ditolak',       'class' => 'b-red'],
    ];

    $kategoriMeta = [
        'hijau'  => ['label' => 'Hijau',  'desc' => 'Internal/Dirjen',    'color' => '#6EAE78'],
        'oranye' => ['label' => 'Oranye', 'desc' => 'Dihadiri Dirjen',    'color' => '#E8A33D'],
        'biru'   => ['label' => 'Biru',   'desc' => 'Eksternal',          'color' => '#5c88d9'],
        'coklat' => ['label' => 'Coklat', 'desc' => 'Eksternal & Dirjen', 'color' => '#9a6b4b'],
    ];

    $semuaPicId = $permintaanMasuk->merge($permintaanSelesai)
        ->pluck('admin_ids')->flatten()->filter()->unique()->values();
    $namaPic = $semuaPicId->isNotEmpty()
        ? \App\Models\User::whereIn('id', $semuaPicId)->pluck('name', 'id')
        : collect();

    $tabs = [
        'masuk'   => ['judul' => 'Pengajuan Terbaru', 'list' => $permintaanMasuk],
        'selesai' => ['judul' => 'Selesai',           'list' => $permintaanSelesai],
    ];

    $totalPengajuan = $permintaanMasuk->count() + $permintaanSelesai->count();
@endphp

<style>
    .ev-page {
        --ev-accent: #4E8BBC; --ev-accent-soft: rgba(78,139,188,.12);
        --ev-text: #1d1d1f; --ev-muted: #6e6e73; --ev-line: rgba(0,0,0,.07);
        --ev-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);
        max-width: 1280px; margin: 0 auto; color: var(--ev-text);
        display: flex; flex-direction: column; gap: 24px;
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .ev-page *, .ev-page *::before, .ev-page *::after { box-sizing: border-box; }
    .ev-page button:focus-visible, .ev-page a:focus-visible, .ev-page select:focus-visible { outline: 2px solid var(--ev-accent); outline-offset: 2px; }

    .ev-page .ev-alert { padding: 12px 16px; border-radius: 12px; font-size: 13px; line-height: 1.5; background: rgba(52,199,89,.1); border: 1px solid rgba(52,199,89,.22); color: #1f7a38; }
    .ev-page .ev-alert.error { background: rgba(255,59,48,.08); border-color: rgba(255,59,48,.22); color: #c9342a; }

    /* Header daftar */
    .ev-page .ev-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 16px; }
    .ev-page .ev-title { font-size: 18px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 4px; }
    .ev-page .ev-sub { font-size: 13px; color: var(--ev-muted); margin: 0; }

    .ev-page .ev-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 38px; padding: 0 18px; border-radius: 10px; border: 0; background: var(--ev-accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: filter .15s; }
    .ev-page .ev-btn:hover { filter: brightness(1.06); }
    .ev-page .ev-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .ev-page .ev-btn-ghost:hover { background: rgba(120,120,128,.18); filter: none; }

    /* Panel */
    .ev-page .ev-panel { background: #fff; border: 1px solid var(--ev-line); border-radius: 18px; box-shadow: var(--ev-shadow); overflow: hidden; }
    .ev-page .ev-toolbar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; padding: 16px 20px; border-bottom: 1px solid var(--ev-line); }
    .ev-page .ev-spacer { flex: 1; }
    .ev-page .ev-tabs { display: inline-flex; gap: 2px; padding: 3px; background: rgba(120,120,128,.12); border-radius: 11px; }
    .ev-page .ev-tab { display: inline-flex; align-items: center; gap: 8px; height: 32px; padding: 0 14px; border: 0; border-radius: 8px; background: transparent; color: var(--ev-muted); font: inherit; font-size: 12.5px; font-weight: 600; cursor: pointer; }
    .ev-page .ev-tab:hover { color: var(--ev-text); }
    .ev-page .ev-tab.active { background: #fff; color: var(--ev-text); box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 0 0 .5px rgba(0,0,0,.04); }
    .ev-page .ev-tab b { padding: 1px 7px; border-radius: 999px; background: rgba(120,120,128,.14); color: var(--ev-muted); font-size: 11px; }
    .ev-page .ev-tab.active b { background: var(--ev-accent-soft); color: #3b7aab; }

    .ev-page .ev-search { position: relative; width: 280px; max-width: 100%; }
    .ev-page .ev-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: #8e8e93; pointer-events: none; }
    .ev-page .ev-input { width: 100%; height: 38px; padding: 0 14px 0 34px; border-radius: 10px; border: 1px solid transparent; background: rgba(120,120,128,.1); font: inherit; font-size: 13px; color: var(--ev-text); outline: none; transition: background .15s, box-shadow .15s, border-color .15s; }
    .ev-page .ev-input:focus { background: #fff; border-color: var(--ev-accent); box-shadow: 0 0 0 4px var(--ev-accent-soft); }

    .ev-page .ev-pane { display: none; }
    .ev-page .ev-pane.active { display: block; }
    .ev-page .ev-wrap { overflow-x: auto; }
    .ev-page .ev-table { width: 100%; min-width: 860px; border-collapse: collapse; }
    .ev-page .ev-table thead th { padding: 12px 20px; text-align: left; background: #fafafc; font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--ev-muted); border-bottom: 1px solid var(--ev-line); white-space: nowrap; }
    .ev-page .ev-table tbody td { padding: 14px 20px; vertical-align: middle; border-bottom: 1px solid var(--ev-line); font-size: 13px; }
    .ev-page .ev-table tbody tr:last-child td { border-bottom: 0; }
    .ev-page .ev-table tbody tr { transition: background .15s; }
    .ev-page .ev-table tbody tr:hover { background: #f7f9fc; }
    .ev-page .ev-right { text-align: right !important; }

    .ev-page .ev-name { font-weight: 700; letter-spacing: -.01em; }
    .ev-page .ev-small { display: block; font-size: 11.5px; color: var(--ev-muted); margin-top: 3px; font-weight: 400; }
    .ev-page .ev-kat { display: inline-flex; align-items: center; gap: 6px; font-size: 11.5px; color: var(--ev-muted); margin-top: 3px; }
    .ev-page .ev-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .ev-page .ev-muted { color: #8e8e93; font-size: 12px; }

    .ev-page .ev-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .ev-page .ev-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .ev-page .b-amber { background: rgba(255,159,10,.16); color: #b26a00; }
    .ev-page .b-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .ev-page .b-red   { background: rgba(255,59,48,.12); color: #c9342a; }
    .ev-page .b-blue  { background: rgba(78,139,188,.14); color: #3b7aab; }

    .ev-page .ev-pic { display: inline-block; padding: 3px 10px; margin: 0 4px 4px 0; border-radius: 999px; background: var(--ev-accent-soft); color: #3b7aab; font-size: 11.5px; font-weight: 600; }

    /* Aksi */
    .ev-page .ev-actions { display: inline-flex; gap: 6px; justify-content: flex-end; }
    .ev-page .ev-act { display: inline-flex; align-items: center; gap: 6px; height: 32px; padding: 0 12px; border-radius: 9px; border: 0; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: filter .15s, background .15s; }
    .ev-page .ev-act svg { width: 14px; height: 14px; }
    .ev-page .ev-act-view { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .ev-page .ev-act-view:hover { background: rgba(120,120,128,.18); }
    .ev-page .ev-act-main { background: var(--ev-accent); color: #fff; }
    .ev-page .ev-act-main:hover { filter: brightness(1.06); }

    .ev-page .ev-empty { padding: 56px 20px; text-align: center; color: var(--ev-muted); font-size: 13px; }
    .ev-page .ev-empty svg { display: block; width: 38px; height: 38px; margin: 0 auto 10px; color: #c7c7cc; }
    .ev-page .ev-noresult { display: none; }

    /* Modal */
    .ev-page .ev-modal { display: none; position: fixed; inset: 0; z-index: 99999; align-items: center; justify-content: center; padding: 20px; background: rgba(0,0,0,.45); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); }
    .ev-page .ev-modal-box { width: 500px; max-width: 100%; max-height: 90vh; overflow-y: auto; background: #fff; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,.25); animation: evPop .18s ease-out; }
    @keyframes evPop { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    .ev-page .ev-modal-head { position: sticky; top: 0; z-index: 1; display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 16px 20px; border-bottom: 1px solid var(--ev-line); background: #fafafc; }
    .ev-page .ev-modal-title { font-size: 15px; font-weight: 700; margin: 0; }
    .ev-page .ev-close { width: 32px; height: 32px; border: 0; border-radius: 9px; background: transparent; font-size: 16px; line-height: 1; cursor: pointer; color: var(--ev-muted); }
    .ev-page .ev-close:hover { background: rgba(120,120,128,.12); color: var(--ev-text); }
    .ev-page .ev-modal-body { padding: 20px; }
    .ev-page .ev-hero { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 4px; }
    .ev-page .ev-row { display: flex; justify-content: space-between; gap: 16px; padding: 11px 0; border-bottom: 1px solid var(--ev-line); font-size: 13px; }
    .ev-page .ev-row.stack { flex-direction: column; gap: 8px; }
    .ev-page .ev-row.last { border-bottom: 0; }
    .ev-page .ev-row > span:first-child { font-weight: 600; color: var(--ev-muted); flex-shrink: 0; }
    .ev-page .ev-row > span:last-child { text-align: right; }
    .ev-page .ev-chips { display: flex; flex-wrap: wrap; gap: 6px; }
    .ev-page .ev-chip { background: var(--ev-accent-soft); color: #3b7aab; padding: 4px 11px; border-radius: 999px; font-size: 12px; font-weight: 600; }
    .ev-page .ev-modal-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 20px; }

    /* Form kelola */
    .ev-page .ev-field { margin-bottom: 16px; }
    .ev-page .ev-field > label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; }
    .ev-page .ev-control { width: 100%; height: 40px; padding: 0 14px; border-radius: 10px; border: 1px solid var(--ev-line); background: rgba(120,120,128,.08); color: var(--ev-text); font: inherit; font-size: 13px; outline: none; appearance: none; cursor: pointer; padding-right: 36px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; }
    .ev-page .ev-control:focus { background-color: #fff; border-color: var(--ev-accent); box-shadow: 0 0 0 4px var(--ev-accent-soft); }
    .ev-page .ev-hint { font-size: 11.5px; color: var(--ev-muted); margin-top: 6px; line-height: 1.5; }
    .ev-page .ev-note { padding: 12px 14px; background: rgba(120,120,128,.07); border: 1px solid var(--ev-line); border-radius: 12px; font-size: 12.5px; color: var(--ev-muted); line-height: 1.5; margin-bottom: 16px; }
    .ev-page .ev-piclist { background: rgba(120,120,128,.05); border: 1px solid var(--ev-line); border-radius: 12px; padding: 6px; max-height: 180px; overflow-y: auto; }
    .ev-page .ev-picitem { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 8px; font-size: 13px; cursor: pointer; }
    .ev-page .ev-picitem:hover { background: #fff; }
    .ev-page .ev-picitem[hidden] { display: none; }
    .ev-page .ev-picitem input { accent-color: var(--ev-accent); width: 15px; height: 15px; }
    .ev-page .ev-picstatus { color: #b26a00; font-size: 11.5px; }
    .ev-page .ev-picempty { padding: 12px; font-size: 12.5px; color: var(--ev-muted); text-align: center; }
    .ev-page .ev-picempty[hidden] { display: none; }

    @media (max-width: 560px) {
        .ev-page .ev-search { width: 100%; }
        .ev-page .ev-row { flex-direction: column; gap: 2px; }
        .ev-page .ev-row > span:last-child { text-align: left; }
    }
    @media (prefers-reduced-motion: reduce) { .ev-page .ev-modal-box { animation: none; } }
</style>

<div class="ev-page">

   

    {{-- 1. KALENDER --}}
    @include('dashboard._kalender')

    {{-- 2. DAFTAR PENGAJUAN --}}
    <div>
        <div class="ev-head">
            <div>
                <h3 class="ev-title">{{ $isAdmin ? 'Pengelolaan Permintaan Dukungan Kegiatan' : 'Permintaan Dukungan Kegiatan Saya' }}</h3>
                <p class="ev-sub">{{ $isAdmin ? 'Kelola dan pantau status permohonan event secara terstruktur.' : 'Pantau status permohonan dukungan kegiatan yang Anda ajukan.' }}</p>
            </div>

            @if (! $isAdmin)
                <a href="{{ route('dukungan.create') }}" class="ev-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                    Ajukan Dukungan
                </a>
            @endif
        </div>

        <div class="ev-panel">

            <div class="ev-toolbar">
                <div class="ev-tabs" role="tablist">
                    @foreach ($tabs as $key => $tab)
                        <button type="button" role="tab" class="ev-tab {{ $loop->first ? 'active' : '' }}"
                                data-tab="pane-{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            {{ $tab['judul'] }} <b>{{ $tab['list']->count() }}</b>
                        </button>
                    @endforeach
                </div>

                <span class="ev-spacer"></span>

                <label class="ev-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                    <input type="text" id="evCari" class="ev-input" placeholder="Cari event, lokasi, pemohon, PIC…" autocomplete="off">
                </label>
            </div>

            @foreach ($tabs as $key => $tab)
                @php $kolom = 5 + ($isAdmin ? 1 : 0); @endphp

                <div id="pane-{{ $key }}" class="ev-pane {{ $loop->first ? 'active' : '' }}" role="tabpanel">
                    <div class="ev-wrap">
                        <table class="ev-table">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Waktu & Lokasi</th>
                                    @if ($isAdmin) <th>Pemohon</th> @endif
                                    <th>{{ $key === 'selesai' ? 'PIC Bertugas' : 'PIC Ditugaskan' }}</th>
                                    <th>Status</th>
                                    <th class="ev-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tab['list'] as $item)
                                    @php
                                        $tanggal = \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->locale('id')->translatedFormat('d M Y');
                                        $jam = substr((string) $item->jam_pelaksanaan, 0, 5);

                                        $supportList = [];
                                        if ($item->support_operator) $supportList[] = 'Operator';
                                        if ($item->support_desain_grafis) $supportList[] = 'Desain Grafis' . ($item->detail_desain_grafis ? ': ' . $item->detail_desain_grafis : '');
                                        if ($item->support_editing) $supportList[] = 'Editing' . ($item->detail_editing_platform ? ' (' . $item->detail_editing_platform . ')' : '');
                                        if ($item->support_konten_web) $supportList[] = 'Konten Web';
                                        if ($item->support_merchandise) $supportList[] = 'Merchandise' . ($item->detail_merchandise ? ': ' . $item->detail_merchandise : '');
                                        if ($item->support_dokumentasi) $supportList[] = 'Dokumentasi';
                                        if ($item->support_tim_acara) $supportList[] = 'Tim Acara';

                                        $pics = collect($item->admin_ids ?? [])->map(fn ($id) => $namaPic[$id] ?? null)->filter()->values();
                                        $meta = $statusMeta[$item->status] ?? ['label' => $item->status, 'class' => 'b-red'];
                                        $kat = $kategoriMeta[$item->kategori_warna ?? 'hijau'] ?? $kategoriMeta['hijau'];
                                        $pemohon = $item->user->name ?? '-';

                                        $cari = strtolower(implode(' ', [$item->nama_event, $item->lokasi, $pemohon, $pics->implode(' '), $meta['label']]));
                                    @endphp

                                    <tr data-cari="{{ $cari }}">
                                        <td>
                                            <div class="ev-name">{{ $item->nama_event }}</div>
                                            <div class="ev-kat"><span class="ev-dot" style="background: {{ $kat['color'] }};"></span>{{ $kat['label'] }} · {{ $kat['desc'] }}</div>
                                        </td>
                                        <td>
                                            <strong>{{ $tanggal }}</strong>
                                            <span class="ev-small">{{ $jam ?: '-' }} &middot; {{ $item->lokasi ?: '-' }}</span>
                                        </td>
                                        @if ($isAdmin)
                                            <td>{{ $pemohon }}</td>
                                        @endif
                                        <td>
                                            @forelse ($pics as $nama)
                                                <span class="ev-pic">{{ $nama }}</span>
                                            @empty
                                                <span class="ev-muted">{{ $key === 'selesai' ? '-' : 'Belum ada PIC' }}</span>
                                            @endforelse
                                        </td>
                                        <td><span class="ev-badge {{ $meta['class'] }}">{{ $meta['label'] }}</span></td>
                                        <td class="ev-right">
                                            <div class="ev-actions">
                                                <button type="button" class="ev-act ev-act-view btn-detail"
                                                        data-nama="{{ $item->nama_event }}"
                                                        data-waktu="{{ $tanggal }}{{ $jam ? ', ' . $jam : '' }}"
                                                        data-lokasi="{{ $item->lokasi ?: '-' }}"
                                                        data-pemohon="{{ $pemohon }}"
                                                        data-pejabat="{{ $item->pejabat_hadir ?: '-' }}"
                                                        data-peserta="{{ $item->jumlah_peserta ?? '-' }}"
                                                        data-status="{{ $meta['label'] }}"
                                                        data-kategori="{{ $kat['label'] }} · {{ $kat['desc'] }}"
                                                        data-pic="{{ json_encode($pics->all()) }}"
                                                        data-support="{{ json_encode($supportList) }}">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    Detail
                                                </button>

                                                @if ($key === 'masuk' && $bisaKelolaStatus)
                                                    <button type="button" class="ev-act ev-act-main btn-kelola"
                                                            data-id="{{ $item->id }}"
                                                            data-nama="{{ $item->nama_event }}"
                                                            data-status="{{ $item->status }}"
                                                            data-kategori="{{ $item->kategori_warna ?? 'hijau' }}"
                                                            data-admin-ids="{{ json_encode(array_values($item->admin_ids ?? [])) }}">
                                                        Kelola
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($tab['list']->isEmpty())
                                    <tr>
                                        <td colspan="{{ $kolom }}" class="ev-empty">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
                                            @if ($key === 'selesai')
                                                Belum ada riwayat kegiatan yang selesai.
                                            @elseif ($isAdmin)
                                                Tidak ada permintaan masuk atau yang sedang diproses saat ini.
                                            @else
                                                Belum ada pengajuan. Klik "Ajukan Dukungan" untuk membuat permohonan.
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <div class="ev-empty ev-noresult">Tidak ada event yang cocok dengan pencarian.</div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    {{-- MODAL DETAIL --}}
    <div id="eventDetailModal" class="ev-modal" role="dialog" aria-modal="true" aria-labelledby="detailTitle">
        <div class="ev-modal-box">
            <div class="ev-modal-head">
                <h3 class="ev-modal-title" id="detailTitle">Detail Kegiatan</h3>
                <button type="button" class="ev-close" data-close="eventDetailModal" aria-label="Tutup">&#10005;</button>
            </div>
            <div class="ev-modal-body">
                <h4 class="ev-hero" id="mNama"></h4>
                <div class="ev-row"><span>Waktu</span><span id="mWaktu"></span></div>
                <div class="ev-row"><span>Lokasi</span><span id="mLokasi"></span></div>
                @if ($isAdmin)
                    <div class="ev-row"><span>Pemohon</span><span id="mPemohon"></span></div>
                @endif
                <div class="ev-row"><span>Pejabat Hadir</span><span id="mPejabat"></span></div>
                <div class="ev-row"><span>Jumlah Peserta</span><span id="mPeserta"></span></div>
                <div class="ev-row"><span>Status</span><span id="mStatus"></span></div>
                <div class="ev-row"><span>Kategori Warna</span><span id="mKategori"></span></div>
                <div class="ev-row stack"><span>PIC</span><div class="ev-chips" id="mPic"></div></div>
                <div class="ev-row stack last"><span>Jenis Dukungan</span><div class="ev-chips" id="mSupport"></div></div>

                <div class="ev-modal-actions">
                    <button type="button" class="ev-btn ev-btn-ghost" data-close="eventDetailModal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL KELOLA --}}
    @if ($bisaKelolaStatus)
        <div id="eventManageModal" class="ev-modal" role="dialog" aria-modal="true" aria-labelledby="manageTitle">
            <div class="ev-modal-box">
                <div class="ev-modal-head">
                    <h3 class="ev-modal-title" id="manageTitle">{{ $bisaDelegasi ? 'Kelola & Delegasi' : 'Kelola Pengajuan' }}</h3>
                    <button type="button" class="ev-close" data-close="eventManageModal" aria-label="Tutup">&#10005;</button>
                </div>
                <div class="ev-modal-body">
                    <h4 class="ev-hero" id="manageNama" style="margin-bottom:16px;"></h4>

                    <form id="manageForm" action="#" method="POST">
                        @csrf
                        @method('PATCH')

                        @if ($bisaDelegasi)
                            <div class="ev-field">
                                <label>Delegasi ke PIC</label>
                                <div class="ev-piclist">
                                    @foreach ($daftarAdmin as $adm)
                                        @php $statusAdm = $statusAdmin[$adm->id] ?? 'TERSEDIA'; @endphp
                                        <label class="ev-picitem" data-unavailable="{{ $statusAdm !== 'TERSEDIA' ? 1 : 0 }}">
                                            <input type="checkbox" name="admin_ids[]" value="{{ $adm->id }}">
                                            <span>{{ $adm->name }}</span>
                                            @if ($statusAdm !== 'TERSEDIA')
                                                <span class="ev-picstatus">({{ $statusAdm === 'SIBUK' ? 'Sibuk' : 'Tugas luar' }})</span>
                                            @endif
                                        </label>
                                    @endforeach
                                    <div class="ev-picempty" id="picEmpty" hidden>Tidak ada PIC yang tersedia.</div>
                                </div>
                                <div class="ev-hint">PIC yang sedang sibuk atau tugas luar hanya tampil jika sudah ditugaskan sebelumnya.</div>
                            </div>
                        @else
                            <div class="ev-note">Delegasi PIC hanya bisa dilakukan oleh Super Admin. PIC yang sudah ditugaskan tidak berubah.</div>
                        @endif

                        <div class="ev-field">
                            <label for="manageKategori">Kategori warna</label>
                            <select name="kategori_warna" id="manageKategori" class="ev-control" required>
                                @foreach ($kategoriMeta as $val => $k)
                                    <option value="{{ $val }}">{{ $k['label'] }} ({{ $k['desc'] }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="ev-field">
                            <label for="manageStatus">Status</label>
                            <select name="status" id="manageStatus" class="ev-control" required>
                                @foreach ($statusMeta as $val => $s)
                                    <option value="{{ $val }}">{{ $s['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="ev-modal-actions">
                            <button type="button" class="ev-btn ev-btn-ghost" data-close="eventManageModal">Batal</button>
                            <button type="submit" class="ev-btn">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>

<script>
(function () {
    const detailModal = document.getElementById('eventDetailModal');
    const manageModal = document.getElementById('eventManageModal');
    const updateUrl = @json(route('dukungan.updateStatus', '__ID__'));

    const open = function (el) { if (el) el.style.display = 'flex'; };
    const close = function (el) { if (el) el.style.display = 'none'; };
    const setText = function (id, v) { const el = document.getElementById(id); if (el) el.textContent = v; };

    /* ---------- Tab + pencarian ---------- */
    const cari = document.getElementById('evCari');

    function terapkanCari() {
        const q = cari.value.trim().toLowerCase();

        document.querySelectorAll('.ev-pane').forEach(function (pane) {
            const rows = pane.querySelectorAll('tbody tr[data-cari]');
            let tampil = 0;

            rows.forEach(function (tr) {
                const ok = !q || tr.dataset.cari.indexOf(q) !== -1;
                tr.style.display = ok ? '' : 'none';
                if (ok) tampil++;
            });

            const none = pane.querySelector('.ev-noresult');
            if (none) none.style.display = (rows.length && q && tampil === 0) ? 'block' : 'none';
        });
    }

    cari.addEventListener('input', terapkanCari);

    document.querySelectorAll('.ev-tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.ev-tab').forEach(function (b) {
                b.classList.remove('active');
                b.setAttribute('aria-selected', 'false');
            });
            document.querySelectorAll('.ev-pane').forEach(function (p) { p.classList.remove('active'); });

            btn.classList.add('active');
            btn.setAttribute('aria-selected', 'true');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });

    /* ---------- Detail ---------- */
    function isiChip(id, list, kosong) {
        const box = document.getElementById(id);
        box.innerHTML = '';
        if (!list.length) {
            const s = document.createElement('span');
            s.textContent = kosong;
            s.style.color = '#8e8e93';
            box.appendChild(s);
            return;
        }
        list.forEach(function (t) {
            const c = document.createElement('span');
            c.className = 'ev-chip';
            c.textContent = t;
            box.appendChild(c);
        });
    }

    document.querySelectorAll('.btn-detail').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const d = btn.dataset;

            setText('mNama', d.nama);
            setText('mWaktu', d.waktu);
            setText('mLokasi', d.lokasi);
            setText('mPemohon', d.pemohon);
            setText('mPejabat', d.pejabat);
            setText('mPeserta', d.peserta + (d.peserta !== '-' ? ' orang' : ''));
            setText('mStatus', d.status);
            setText('mKategori', d.kategori);

            let pic = [], support = [];
            try { pic = JSON.parse(d.pic || '[]'); } catch (e) {}
            try { support = JSON.parse(d.support || '[]'); } catch (e) {}

            isiChip('mPic', pic, 'Belum ada PIC');
            isiChip('mSupport', support, 'Tidak ada');

            open(detailModal);
        });
    });

    /* ---------- Kelola ---------- */
    document.querySelectorAll('.btn-kelola').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const d = btn.dataset;
            const assigned = JSON.parse(d.adminIds || '[]').map(String);

            document.getElementById('manageForm').action = updateUrl.replace('__ID__', d.id);
            setText('manageNama', d.nama);
            document.getElementById('manageStatus').value = d.status;
            document.getElementById('manageKategori').value = d.kategori;

            let tampil = 0;
            manageModal.querySelectorAll('.ev-picitem').forEach(function (label) {
                const cb = label.querySelector('input');
                const sudah = assigned.includes(cb.value);
                cb.checked = sudah;
                label.hidden = label.dataset.unavailable === '1' && !sudah;
                if (!label.hidden) tampil++;
            });
            const empty = document.getElementById('picEmpty');
            if (empty) empty.hidden = tampil > 0;

            open(manageModal);
        });
    });

    /* ---------- Tutup modal ---------- */
    document.querySelectorAll('[data-close]').forEach(function (btn) {
        btn.addEventListener('click', function () { close(document.getElementById(btn.dataset.close)); });
    });
    [detailModal, manageModal].forEach(function (m) {
        if (!m) return;
        m.addEventListener('click', function (e) { if (e.target === m) close(m); });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { close(detailModal); close(manageModal); }
    });
})();
</script>

@endsection