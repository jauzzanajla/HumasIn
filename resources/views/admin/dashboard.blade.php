@extends('layouts.app')

@section('title', 'Dashboard Admin | HumasIn')
@section('page-title', 'Dashboard')
@section('page-description', 'Tindak lanjuti layanan Humas DJPK')

@section('content')

<style>
    .ad-page {
        --ad-accent: #4E8BBC;
        --ad-accent-soft: rgba(78, 139, 188, .12);
        --ad-text: #1d1d1f;
        --ad-muted: #6e6e73;
        --ad-line: rgba(0, 0, 0, .07);
        --ad-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 1280px; margin: 0 auto; color: var(--ad-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .ad-page *, .ad-page *::before, .ad-page *::after { box-sizing: border-box; }

    .ad-page .ad-head { margin-bottom: 24px; }
    .ad-page .ad-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .ad-page .ad-sub { font-size: 13px; color: var(--ad-muted); margin: 0; }

    .ad-page .ad-alert { display: flex; gap: 10px; margin-bottom: 18px; padding: 12px 16px; border-radius: 12px; font-size: 13px; background: rgba(52,199,89,.1); border: 1px solid rgba(52,199,89,.22); color: #1f7a38; }
    .ad-page .ad-alert.err { background: rgba(255,59,48,.08); border-color: rgba(255,59,48,.18); color: #b42318; }

    /* Statistik */
    .ad-page .ad-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
    .ad-page .ad-stat { display: flex; align-items: center; gap: 16px; padding: 20px; background: #fff; border: 1px solid var(--ad-line); border-radius: 16px; box-shadow: var(--ad-shadow); }
    .ad-page .ad-stat-icon { width: 44px; height: 44px; flex-shrink: 0; display: grid; place-items: center; border-radius: 12px; }
    .ad-page .ad-stat-icon svg { width: 22px; height: 22px; }
    .ad-page .tone-blue   { background: rgba(78,139,188,.14); color: #3b7aab; }
    .ad-page .tone-amber  { background: rgba(255,159,10,.15); color: #c77700; }
    .ad-page .tone-red    { background: rgba(255,59,48,.12); color: #c9342a; }
    .ad-page .tone-green  { background: rgba(52,199,89,.14); color: #248a3d; }
    .ad-page .ad-stat-label { font-size: 12px; font-weight: 500; color: var(--ad-muted); margin-bottom: 2px; }
    .ad-page .ad-stat-value { font-size: 28px; font-weight: 700; letter-spacing: -.03em; line-height: 1.1; font-variant-numeric: tabular-nums; }
    .ad-page .ad-stat-value small { font-size: 14px; font-weight: 500; color: var(--ad-muted); letter-spacing: 0; }

    /* Layout */
    .ad-page .ad-row { display: grid; gap: 20px; margin-bottom: 20px; align-items: start; }
    .ad-page .ad-row.r-2 { grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr); }
    .ad-page .ad-row.r-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }

    .ad-page .ad-card { background: #fff; border: 1px solid var(--ad-line); border-radius: 18px; box-shadow: var(--ad-shadow); overflow: hidden; }
    .ad-page .ad-card-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 18px 20px 14px; }
    .ad-page .ad-card-title { font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0; }
    .ad-page .ad-card-sub { font-size: 12px; color: var(--ad-muted); margin-top: 2px; }
    .ad-page .ad-link { font-size: 12px; font-weight: 600; color: #3b7aab; white-space: nowrap; }
    .ad-page .ad-link:hover { opacity: .7; }

    /* Tab */
    .ad-page .ad-tabs { display: inline-flex; gap: 2px; padding: 3px; margin: 0 20px 14px; border-radius: 11px; background: rgba(120,120,128,.12); }
    .ad-page .ad-tab { padding: 6px 14px; border: 0; border-radius: 8px; background: transparent; color: var(--ad-muted); font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; }
    .ad-page .ad-tab.active { background: #fff; color: var(--ad-text); box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 0 0 .5px rgba(0,0,0,.04); }
    .ad-page .ad-tab b { margin-left: 5px; padding: 1px 7px; border-radius: 999px; background: var(--ad-accent-soft); color: #3b7aab; font-size: 11px; }
    .ad-page .ad-pane { display: none; }
    .ad-page .ad-pane.active { display: block; }

    /* Item antrean */
    .ad-page .ad-list { padding: 0 8px 8px; }
    .ad-page .ad-item { display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 12px; transition: background .15s; }
    .ad-page .ad-item:hover { background: #f7f9fc; }
    .ad-page .ad-item-icon { width: 38px; height: 38px; flex-shrink: 0; display: grid; place-items: center; border-radius: 11px; }
    .ad-page .ad-item-icon svg { width: 18px; height: 18px; }
    .ad-page .ad-item-body { flex: 1; min-width: 0; }
    .ad-page .ad-item-title { font-size: 13px; font-weight: 600; letter-spacing: -.005em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ad-page .ad-item-meta { font-size: 11.5px; color: var(--ad-muted); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ad-page .ad-item-actions { display: flex; gap: 6px; flex-shrink: 0; }
    .ad-page .ad-item-actions form { margin: 0; }

    .ad-page .ad-btn { display: inline-flex; align-items: center; justify-content: center; height: 30px; padding: 0 12px; border-radius: 8px; border: 0; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s; }
    .ad-page .ad-btn-ok { background: var(--ad-accent); color: #fff; }
    .ad-page .ad-btn-ok:hover { filter: brightness(1.07); }
    .ad-page .ad-btn-no { background: rgba(255,59,48,.1); color: #c9342a; }
    .ad-page .ad-btn-no:hover { background: rgba(255,59,48,.18); }
    .ad-page .ad-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .ad-page .ad-btn-ghost:hover { background: rgba(120,120,128,.18); }

    .ad-page .ad-empty { padding: 40px 20px; text-align: center; color: var(--ad-muted); font-size: 13px; }
    .ad-page .ad-empty svg { width: 32px; height: 32px; color: #c7c7cc; margin-bottom: 8px; }

    .ad-page .ad-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px 3px 7px; border-radius: 999px; font-size: 11px; font-weight: 600; white-space: nowrap; }
    .ad-page .ad-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .ad-page .b-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .ad-page .b-amber { background: rgba(255,159,10,.16); color: #b26a00; }
    .ad-page .b-red   { background: rgba(255,59,48,.12); color: #c9342a; }

    /* Kalender
       Warna diambil langsung dari data (--tx = warna label, --bg = versi pudarnya),
       jadi selalu sama dengan halaman Event & Kalender. */
    .ad-page .ad-cal-bar { display: flex; align-items: center; gap: 8px; padding: 0 20px 14px; }
    .ad-page .ad-month { font-size: 15px; font-weight: 700; letter-spacing: -.01em; min-width: 140px; }
    .ad-page .ad-icon-btn { width: 30px; height: 30px; display: grid; place-items: center; border: 0; border-radius: 8px; cursor: pointer; background: rgba(120,120,128,.1); color: #3a3a3c; }
    .ad-page .ad-icon-btn:hover { background: rgba(120,120,128,.2); }
    .ad-page .ad-icon-btn svg { width: 14px; height: 14px; }
    .ad-page .ad-today { height: 30px; padding: 0 12px; border: 0; border-radius: 8px; background: var(--ad-accent-soft); color: #3b7aab; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; }
    .ad-page .ad-spacer { flex: 1; }

    .ad-page .ad-wd { display: grid; grid-template-columns: repeat(7, 1fr); background: #fafafc; border-top: 1px solid var(--ad-line); border-bottom: 1px solid var(--ad-line); }
    .ad-page .ad-wd div { padding: 8px 4px; text-align: center; font-size: 10.5px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--ad-muted); }
    .ad-page .ad-grid { display: grid; grid-template-columns: repeat(7, 1fr); }
    .ad-page .ad-cell { min-height: 84px; padding: 5px; border-right: 1px solid var(--ad-line); border-bottom: 1px solid var(--ad-line); cursor: pointer; transition: background .15s; overflow: hidden; }
    .ad-page .ad-cell:nth-child(7n) { border-right: 0; }
    .ad-page .ad-cell:nth-last-child(-n+7) { border-bottom: 0; }
    .ad-page .ad-cell:hover { background: #f7f9fc; }
    .ad-page .ad-cell.out { background: #fcfcfd; }
    .ad-page .ad-cell.out .ad-dn { color: #c0c0c6; }
    .ad-page .ad-cell.sel { background: rgba(78,139,188,.07); box-shadow: inset 0 0 0 1.5px var(--ad-accent); }
    .ad-page .ad-dn { display: inline-grid; place-items: center; min-width: 22px; height: 22px; padding: 0 5px; margin-bottom: 3px; border-radius: 999px; font-size: 11.5px; font-weight: 600; color: #3a3a3c; font-variant-numeric: tabular-nums; }
    .ad-page .ad-cell.today .ad-dn { background: var(--ad-accent); color: #fff; }
    .ad-page .ad-chip { display: block; margin-bottom: 2px; padding: 2px 5px; border-radius: 5px; border-left: 3px solid var(--tx); background: var(--bg); color: var(--ad-text); font-size: 10px; font-weight: 600; line-height: 1.3; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ad-page .ad-more { font-size: 10px; font-weight: 600; color: var(--ad-muted); padding: 0 4px; }

    .ad-page .ad-legend { display: flex; gap: 16px; flex-wrap: wrap; padding: 10px 20px; border-top: 1px solid var(--ad-line); background: #fafafc; font-size: 12px; color: var(--ad-muted); }
    .ad-page .ad-legend span { display: inline-flex; align-items: center; gap: 6px; }
    .ad-page .ad-legend i { width: 9px; height: 9px; border-radius: 3px; background: var(--tx); }

    .ad-page .ad-day { padding: 14px 20px; border-top: 1px solid var(--ad-line); }
    .ad-page .ad-day-title { font-size: 13px; font-weight: 700; margin-bottom: 8px; }
    .ad-page .ad-ev { padding: 10px 12px; border-radius: 10px; border-left: 3px solid var(--tx); background: var(--bg); margin-bottom: 6px; }
    .ad-page .ad-ev-name { font-size: 13px; font-weight: 700; color: var(--ad-text); }
    .ad-page .ad-ev-meta { font-size: 11.5px; color: #4a4a4f; margin-top: 2px; }
    .ad-page .ad-day-empty { font-size: 12px; color: var(--ad-muted); }

    /* Tim */
    .ad-page .ad-avatar { width: 34px; height: 34px; flex-shrink: 0; display: grid; place-items: center; border-radius: 50%; background: #C2E1FC; color: #2b5c8f; font-size: 12px; font-weight: 700; }

    /* Ringkasan */
    .ad-page .ad-kv { display: flex; justify-content: space-between; align-items: center; padding: 12px 20px; border-top: 1px solid var(--ad-line); font-size: 13px; }
    .ad-page .ad-kv span { color: var(--ad-muted); }
    .ad-page .ad-kv b { font-variant-numeric: tabular-nums; }

    /* Modal tolak */
    .ad-page .ad-modal { display: none; position: fixed; inset: 0; z-index: 99999; align-items: center; justify-content: center; padding: 20px; background: rgba(0,0,0,.32); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); }
    .ad-page .ad-modal-box { width: 420px; max-width: 100%; padding: 26px; background: rgba(255,255,255,.97); border-radius: 20px; box-shadow: 0 0 0 1px rgba(0,0,0,.06), 0 24px 60px rgba(0,0,0,.25); }
    .ad-page .ad-modal-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 6px; }
    .ad-page .ad-modal-text { font-size: 13px; color: var(--ad-muted); margin: 0 0 14px; }
    .ad-page .ad-textarea { width: 100%; min-height: 90px; padding: 12px 14px; border-radius: 10px; resize: vertical; border: 1px solid var(--ad-line); background: rgba(120,120,128,.08); font: inherit; font-size: 13px; outline: none; }
    .ad-page .ad-textarea:focus { background: #fff; border-color: var(--ad-accent); box-shadow: 0 0 0 4px var(--ad-accent-soft); }
    .ad-page .ad-modal-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 16px; }
    .ad-page .ad-modal-actions .ad-btn { height: 40px; }
    .ad-page .ad-btn-danger { background: #ff3b30; color: #fff; }

    @media (max-width: 1100px) {
        .ad-page .ad-stats { grid-template-columns: repeat(2, 1fr); }
        .ad-page .ad-row.r-2, .ad-page .ad-row.r-3 { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) { .ad-page .ad-stats { grid-template-columns: 1fr; } .ad-page .ad-cell { min-height: 64px; } }
</style>

@php
    $namaUser = auth()->user()?->karyawan?->nama ?? auth()->user()?->name ?? 'Admin';
    $perluTindakan = $totalPeminjamanMenunggu + $totalDukunganMenunggu;

    $namaItem = fn ($p) => $p->aset?->nama_aset ?? $p->ruangan?->nama_ruangan ?? 'Peminjaman';
    $jenisItem = fn ($p) => $p->ruangan_id ? 'Ruangan' : 'Aset';
    $waktuSelesai = fn ($p) => \Carbon\Carbon::parse($p->tanggal_selesai)->translatedFormat('d M') . ' ' . substr($p->jam_selesai, 0, 5);

    $icoBox  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg>';
    $icoRoom = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/><path d="M16 9h2a2 2 0 0 1 2 2v10M2 21h20M9 7h2M9 11h2M9 15h2"/></svg>';
    $icoCal  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="3"/><path d="M3 9h18M8 2v4M16 2v4"/></svg>';
@endphp

<div class="ad-page">

    <div class="ad-head">
        <h2 class="ad-title">Halo, {{ $namaUser }}</h2>
        <p class="ad-sub">{{ now()->translatedFormat('l, d F Y') }} · Ada {{ $perluTindakan }} hal yang menunggu tindakan Anda.</p>
    </div>

    @if (session('success')) <div class="ad-alert">{{ session('success') }}</div> @endif
    @if (session('error'))   <div class="ad-alert err">{{ session('error') }}</div> @endif
    @if ($errors->any())     <div class="ad-alert err">{{ $errors->first() }}</div> @endif

    {{-- STATISTIK --}}
    <div class="ad-stats">
        <div class="ad-stat">
            <div class="ad-stat-icon tone-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
            <div><div class="ad-stat-label">Perlu Tindakan</div><div class="ad-stat-value">{{ $perluTindakan }}</div></div>
        </div>
        <div class="ad-stat">
            <div class="ad-stat-icon {{ $terlambat->count() ? 'tone-red' : 'tone-green' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/></svg></div>
            <div><div class="ad-stat-label">Pengembalian Terlambat</div><div class="ad-stat-value">{{ $terlambat->count() }}</div></div>
        </div>
        <div class="ad-stat">
            <div class="ad-stat-icon tone-blue">{!! $icoCal !!}</div>
            <div><div class="ad-stat-label">Event 7 Hari Ke Depan</div><div class="ad-stat-value">{{ $eventMingguIni }}</div></div>
        </div>
        <div class="ad-stat">
            <div class="ad-stat-icon tone-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0M16 4.5a3.5 3.5 0 0 1 0 7M18 14.5a6.5 6.5 0 0 1 3.5 5.5"/></svg></div>
            <div><div class="ad-stat-label">Tim Tersedia</div><div class="ad-stat-value">{{ $timTersedia }}<small> / {{ $timStatus->count() }}</small></div></div>
        </div>
    </div>

    {{-- ANTREAN + KALENDER --}}
    <div class="ad-row r-2">

        {{-- ANTREAN TINDAKAN --}}
        <div class="ad-card">
            <div class="ad-card-head">
                <div>
                    <h3 class="ad-card-title">Antrean Tindakan</h3>
                    <div class="ad-card-sub">Pengajuan yang menunggu keputusan</div>
                </div>
            </div>

            <div class="ad-tabs">
                <button type="button" class="ad-tab active" data-pane="paneP">Peminjaman<b>{{ $totalPeminjamanMenunggu }}</b></button>
                <button type="button" class="ad-tab" data-pane="paneD">Dukungan Kegiatan<b>{{ $totalDukunganMenunggu }}</b></button>
            </div>

            <div class="ad-pane active" id="paneP">
                <div class="ad-list">
                    @forelse ($antreanPeminjaman as $p)
                        <div class="ad-item">
                            <div class="ad-item-icon tone-blue">{!! $p->ruangan_id ? $icoRoom : $icoBox !!}</div>
                            <div class="ad-item-body">
                                <div class="ad-item-title">{{ $namaItem($p) }}{{ !$p->ruangan_id && $p->jumlah > 1 ? ' · ' . $p->jumlah . ' unit' : '' }}</div>
                                <div class="ad-item-meta">
                                    {{ $p->user?->karyawan?->nama ?? $p->user?->name ?? '-' }} ·
                                    {{ \Carbon\Carbon::parse($p->tanggal_mulai)->translatedFormat('d M') }} {{ substr($p->jam_mulai, 0, 5) }}
                                </div>
                            </div>
                            <div class="ad-item-actions">
                                <form action="{{ route('peminjaman.approve', $p->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="DISETUJUI">
                                    <button type="submit" class="ad-btn ad-btn-ok">Setujui</button>
                                </form>
                                <button type="button" class="ad-btn ad-btn-no btn-tolak" data-id="{{ $p->id }}" data-nama="{{ $namaItem($p) }}">Tolak</button>
                            </div>
                        </div>
                    @empty
                        <div class="ad-empty">{!! str_replace('<svg ', '<svg style="width:32px;height:32px" ', $icoBox) !!}<div>Tidak ada pengajuan peminjaman yang menunggu.</div></div>
                    @endforelse
                </div>
                @if ($totalPeminjamanMenunggu > $antreanPeminjaman->count())
                    <div style="padding:0 20px 16px"><a href="{{ route('peminjaman.kelola') }}" class="ad-link">Lihat {{ $totalPeminjamanMenunggu - $antreanPeminjaman->count() }} lainnya →</a></div>
                @endif
            </div>

            <div class="ad-pane" id="paneD">
                <div class="ad-list">
                    @forelse ($antreanDukungan as $d)
                        <div class="ad-item">
                            <div class="ad-item-icon tone-amber">{!! $icoCal !!}</div>
                            <div class="ad-item-body">
                                <div class="ad-item-title">{{ $d->nama_event }}</div>
                                <div class="ad-item-meta">
                                    {{ $d->user?->name ?? '-' }} · {{ \Carbon\Carbon::parse($d->tanggal_pelaksanaan)->translatedFormat('d M Y') }}
                                </div>
                            </div>
                            <div class="ad-item-actions">
                                <a href="{{ route('dukungan.index') }}" class="ad-btn ad-btn-ghost">Tinjau</a>
                            </div>
                        </div>
                    @empty
                        <div class="ad-empty">{!! str_replace('<svg ', '<svg style="width:32px;height:32px" ', $icoCal) !!}<div>Tidak ada permohonan dukungan kegiatan yang menunggu.</div></div>
                    @endforelse
                </div>
                @if ($totalDukunganMenunggu > $antreanDukungan->count())
                    <div style="padding:0 20px 16px"><a href="{{ route('dukungan.index') }}" class="ad-link">Lihat {{ $totalDukunganMenunggu - $antreanDukungan->count() }} lainnya →</a></div>
                @endif
            </div>
        </div>

        {{-- KALENDER EVENT (data & warna sama dengan halaman Event/Kalender) --}}
        <div class="ad-card">
            <div class="ad-card-head">
                <div>
                    <h3 class="ad-card-title">Kalender Event</h3>
                    <div class="ad-card-sub">Klik tanggal untuk melihat kegiatan</div>
                </div>
                <a href="{{ route('event.index') }}" class="ad-link">Kelola →</a>
            </div>

            <div class="ad-cal-bar">
                <div class="ad-month" id="adMonth">—</div>
                <button type="button" class="ad-icon-btn" id="adPrev" aria-label="Bulan sebelumnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg></button>
                <button type="button" class="ad-icon-btn" id="adNext" aria-label="Bulan berikutnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg></button>
                <span class="ad-spacer"></span>
                <button type="button" class="ad-today" id="adToday">Hari ini</button>
            </div>

            <div class="ad-wd"><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div></div>
            <div class="ad-grid" id="adGrid"></div>

            {{-- Legenda: sama persis dengan pilihan warna di halaman Kalender --}}
            <div class="ad-legend">
                <span style="--tx: #6EAE78;"><i></i> Hijau (Internal)</span>
                <span style="--tx: #E5A044;"><i></i> Oranye (Penting)</span>
                <span style="--tx: #5C88D9;"><i></i> Biru (Eksternal)</span>
                <span style="--tx: #8B654B;"><i></i> Coklat (Khusus)</span>
            </div>

            <div class="ad-day">
                <div class="ad-day-title" id="adDayTitle">—</div>
                <div id="adDayList"><div class="ad-day-empty">Memuat agenda…</div></div>
            </div>
        </div>
    </div>

    {{-- BARIS BAWAH --}}
    <div class="ad-row r-3">

        {{-- PENGEMBALIAN --}}
        <div class="ad-card">
            <div class="ad-card-head">
                <div><h3 class="ad-card-title">Pengembalian</h3><div class="ad-card-sub">Terlambat dan jatuh tempo 24 jam</div></div>
                <a href="{{ route('peminjaman.kelola', ['tab' => 'dipinjam']) }}" class="ad-link">Semua →</a>
            </div>
            <div class="ad-list">
                @foreach ($terlambat as $p)
                    <div class="ad-item">
                        <div class="ad-item-body">
                            <div class="ad-item-title">{{ $namaItem($p) }}</div>
                            <div class="ad-item-meta">{{ $p->user?->karyawan?->nama ?? $p->user?->name ?? '-' }} · {{ $waktuSelesai($p) }}</div>
                        </div>
                        <span class="ad-badge b-red">Terlambat</span>
                    </div>
                @endforeach
                @foreach ($jatuhTempo as $p)
                    <div class="ad-item">
                        <div class="ad-item-body">
                            <div class="ad-item-title">{{ $namaItem($p) }}</div>
                            <div class="ad-item-meta">{{ $p->user?->karyawan?->nama ?? $p->user?->name ?? '-' }} · {{ $waktuSelesai($p) }}</div>
                        </div>
                        <span class="ad-badge b-amber">Segera</span>
                    </div>
                @endforeach
                @if ($terlambat->isEmpty() && $jatuhTempo->isEmpty())
                    <div class="ad-empty">Tidak ada pengembalian yang perlu dipantau.</div>
                @endif
            </div>
        </div>

        {{-- TIM --}}
        <div class="ad-card">
            <div class="ad-card-head">
                <div><h3 class="ad-card-title">Ketersediaan Tim</h3><div class="ad-card-sub">Status admin dan staf Humas</div></div>
            </div>
            <div class="ad-list">
                @forelse ($timStatus as $s)
                    @php
                        $nm = $s->user?->karyawan?->nama ?? $s->user?->name ?? 'Admin';
                        [$bc, $lb] = match ($s->status_ketersediaan) {
                            'TERSEDIA' => ['b-green', 'Tersedia'],
                            'SIBUK'    => ['b-amber', 'Sibuk'],
                            default    => ['b-red', 'Tugas Luar'],
                        };
                    @endphp
                    <div class="ad-item">
                        <div class="ad-avatar">{{ strtoupper(mb_substr($nm, 0, 1)) }}</div>
                        <div class="ad-item-body">
                            <div class="ad-item-title">{{ $nm }}</div>
                            <div class="ad-item-meta">{{ $s->deskripsi_pekerjaan ?: ($s->user?->karyawan?->unit?->nama_unit ?? '—') }}</div>
                        </div>
                        <span class="ad-badge {{ $bc }}">{{ $lb }}</span>
                    </div>
                @empty
                    <div class="ad-empty">Belum ada status tim yang tercatat.</div>
                @endforelse
            </div>
        </div>

        {{-- RINGKASAN --}}
        <div class="ad-card">
            <div class="ad-card-head">
                <div><h3 class="ad-card-title">Ringkasan Aset & Ruangan</h3><div class="ad-card-sub">Kondisi saat ini</div></div>
            </div>
            <div class="ad-kv"><span>Total jenis aset</span><b>{{ $ringkasan['aset_total'] }}</b></div>
            <div class="ad-kv"><span>Unit aset sedang dipinjam</span><b>{{ $ringkasan['aset_dipinjam'] }}</b></div>
            <div class="ad-kv"><span>Aset dalam perbaikan</span><b>{{ $ringkasan['aset_perbaikan'] }}</b></div>
            <div class="ad-kv"><span>Aset rusak berat</span><b style="color: {{ $ringkasan['aset_rusak'] ? '#c9342a' : 'inherit' }}">{{ $ringkasan['aset_rusak'] }}</b></div>
            <div class="ad-kv"><span>Total ruangan</span><b>{{ $ringkasan['ruangan_total'] }}</b></div>
            <div class="ad-kv"><span>Ruangan sedang dipakai</span><b>{{ $ringkasan['ruangan_dipakai'] }}</b></div>
        </div>
    </div>

    {{-- MODAL TOLAK --}}
    <div class="ad-modal" id="adModal">
        <div class="ad-modal-box">
            <h3 class="ad-modal-title">Tolak pengajuan?</h3>
            <p class="ad-modal-text">Pengajuan <strong id="adModalNama">-</strong>. Alasan penolakan wajib diisi.</p>
            <form id="adModalForm" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="DITOLAK">
                <textarea name="alasan_penolakan" class="ad-textarea" maxlength="255" required placeholder="Tulis alasan penolakan…"></textarea>
                <div class="ad-modal-actions">
                    <button type="button" class="ad-btn ad-btn-ghost" id="adModalBatal">Batal</button>
                    <button type="submit" class="ad-btn ad-btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
(function () {
    /* ---------- Tab antrean ---------- */
    document.querySelectorAll('.ad-tab').forEach(function (t) {
        t.addEventListener('click', function () {
            document.querySelectorAll('.ad-tab').forEach(function (x) { x.classList.remove('active'); });
            document.querySelectorAll('.ad-pane').forEach(function (x) { x.classList.remove('active'); });
            t.classList.add('active');
            document.getElementById(t.dataset.pane).classList.add('active');
        });
    });

    /* ---------- Modal tolak ---------- */
    const modal = document.getElementById('adModal');
    const form = document.getElementById('adModalForm');
    const URL_TOLAK = @json(route('peminjaman.reject', ['id' => '__ID__']));

    document.querySelectorAll('.btn-tolak').forEach(function (b) {
        b.addEventListener('click', function () {
            form.action = URL_TOLAK.replace('__ID__', b.dataset.id);
            document.getElementById('adModalNama').textContent = b.dataset.nama;
            form.querySelector('textarea').value = '';
            modal.style.display = 'flex';
        });
    });
    document.getElementById('adModalBatal').addEventListener('click', function () { modal.style.display = 'none'; });
    modal.addEventListener('click', function (e) { if (e.target === modal) modal.style.display = 'none'; });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') modal.style.display = 'none'; });

    /* ---------- Kalender (sumber data & warna sama dengan halaman Kalender) ---------- */
    const URL_EVENTS = @json(route('kalender.events.json'));
    const BULAN = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const HARI = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const WARNA_RESMI = ['#6EAE78', '#E5A044', '#5C88D9', '#8B654B'];
    const WARNA_DEFAULT = '#6EAE78';

    const pad = function (n) { return String(n).padStart(2, '0'); };
    const key = function (d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); };
    const fromKey = function (k) { const p = k.split('-'); return new Date(+p[0], +p[1] - 1, +p[2]); };
    const esc = function (t) { const d = document.createElement('div'); d.textContent = t == null ? '' : t; return d.innerHTML.replace(/"/g, '&quot;'); };

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
        document.getElementById('adMonth').textContent = BULAN[view.getMonth()] + ' ' + view.getFullYear();

        const offset = (view.getDay() + 6) % 7;
        const start = new Date(view); start.setDate(1 - offset);
        let html = '';

        for (let i = 0; i < 42; i++) {
            const d = new Date(start); d.setDate(start.getDate() + i);
            const k = key(d), evs = map[k] || [];
            const cls = ['ad-cell'];
            if (d.getMonth() !== view.getMonth()) cls.push('out');
            if (k === key(today)) cls.push('today');
            if (k === selected) cls.push('sel');

            const chips = evs.slice(0, 2).map(function (e) {
                return '<span class="ad-chip" style="' + gaya(e.color) + '" title="' + esc(e.title) + '">' + esc(e.title) + '</span>';
            }).join('');
            const more = evs.length > 2 ? '<div class="ad-more">+' + (evs.length - 2) + ' lagi</div>' : '';

            html += '<div class="' + cls.join(' ') + '" data-k="' + k + '"><span class="ad-dn">' + d.getDate() + '</span>' + chips + more + '</div>';
        }
        document.getElementById('adGrid').innerHTML = html;

        const d = fromKey(selected), evs = map[selected] || [];
        document.getElementById('adDayTitle').textContent =
            HARI[d.getDay()] + ', ' + d.getDate() + ' ' + BULAN[d.getMonth()] + ' ' + d.getFullYear();

        let isi;
        if (status === 'memuat') {
            isi = '<div class="ad-day-empty">Memuat agenda…</div>';
        } else if (status === 'gagal') {
            isi = '<div class="ad-day-empty">Agenda gagal dimuat. Muat ulang halaman untuk mencoba lagi.</div>';
        } else if (!evs.length) {
            isi = '<div class="ad-day-empty">Tidak ada kegiatan pada tanggal ini.</div>';
        } else {
            isi = evs.map(function (e) {
                const p = e.extendedProps || {};
                const jam = jamDari(e.start);
                const jenis = p.type === 'pengingat' ? 'Pengingat' : 'Dukungan kegiatan';
                const meta = [jenis, jam ? jam + ' WIB' : '', p.keterangan || ''].filter(Boolean).join(' · ');
                return '<div class="ad-ev" style="' + gaya(e.color) + '">' +
                    '<div class="ad-ev-name">' + esc(e.title) + '</div>' +
                    '<div class="ad-ev-meta">' + esc(meta) + '</div></div>';
            }).join('');
        }
        document.getElementById('adDayList').innerHTML = isi;
    }

    document.getElementById('adGrid').addEventListener('click', function (e) {
        const c = e.target.closest('.ad-cell');
        if (!c) return;
        selected = c.dataset.k;
        const d = fromKey(selected);
        if (d.getMonth() !== view.getMonth()) view = new Date(d.getFullYear(), d.getMonth(), 1);
        render();
    });
    document.getElementById('adPrev').addEventListener('click', function () { view = new Date(view.getFullYear(), view.getMonth() - 1, 1); render(); });
    document.getElementById('adNext').addEventListener('click', function () { view = new Date(view.getFullYear(), view.getMonth() + 1, 1); render(); });
    document.getElementById('adToday').addEventListener('click', function () { view = new Date(today.getFullYear(), today.getMonth(), 1); selected = key(today); render(); });

    render();

    fetch(URL_EVENTS, { headers: { 'Accept': 'application/json' } })
        .then(function (res) { if (!res.ok) throw new Error(res.status); return res.json(); })
        .then(function (data) { susun(Array.isArray(data) ? data : []); status = 'siap'; render(); })
        .catch(function () { status = 'gagal'; render(); });
})();
</script>

@endsection