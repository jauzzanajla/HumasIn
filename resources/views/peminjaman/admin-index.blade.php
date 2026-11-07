@extends('layouts.app')

@section('title', ($isAdmin ?? false) ? 'Kelola Pinjaman | HumasIn' : 'Peminjaman Saya | HumasIn')
@section('page-title', ($isAdmin ?? false) ? 'Kelola Pinjaman' : 'Peminjaman Saya')
@section('page-description', ($isAdmin ?? false) ? 'Pantau dan proses pengajuan peminjaman aset serta ruangan' : 'Pantau status pengajuan peminjaman Anda')

@section('content')

@php
    use Carbon\Carbon;

    $isAdmin = $isAdmin ?? in_array(
        auth()->user()?->loadMissing('peran')->peran?->kode_peran,
        ['SUPER_ADMIN', 'ADMIN'],
        true
    );

    $tab = $tab ?? request()->query('tab', 'pengajuan');

    $tabs = [
        'pengajuan'    => 'Pengajuan',
        'dipinjam'     => 'Dipinjam',
        'dikembalikan' => 'Dikembalikan',
        'ditolak'      => 'Ditolak',
    ];

    $durasi = function (Carbon $a, Carbon $b) {
        $m = (int) abs($a->diffInMinutes($b));
        $hari = intdiv($m, 1440);
        $jam  = intdiv($m % 1440, 60);
        $mnt  = $m % 60;
        $out  = [];
        if ($hari) $out[] = $hari . ' hari';
        if ($jam)  $out[] = $jam . ' jam';
        if ($mnt && ! $hari) $out[] = $mnt . ' menit';
        return $out ? implode(' ', $out) : '—';
    };

    $linkWa = function (?string $hp) {
        $angka = preg_replace('/\D+/', '', (string) $hp);
        if ($angka === '') return null;
        if (str_starts_with($angka, '0')) $angka = '62' . substr($angka, 1);
        return 'https://wa.me/' . $angka;
    };
@endphp

<style>
    .pj-page {
        --pj-accent: #4E8BBC;
        --pj-accent-soft: rgba(78, 139, 188, .12);
        --pj-text: #1d1d1f;
        --pj-muted: #6e6e73;
        --pj-line: rgba(0, 0, 0, .07);
        --pj-shadow: 0 1px 3px rgba(0,0,0,.04), 0 10px 28px rgba(0,0,0,.05);

        width: 100%; color: var(--pj-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .pj-page *, .pj-page *::before, .pj-page *::after { box-sizing: border-box; }

    .pj-page .pj-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
    .pj-page .pj-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .pj-page .pj-sub { font-size: 13px; color: var(--pj-muted); margin: 0; }
    .pj-page .pj-count { display: inline-flex; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; background: var(--pj-accent-soft); color: #3b7aab; }

    .pj-page .pj-alert { margin-bottom: 18px; padding: 12px 16px; border-radius: 12px; font-size: 13px; line-height: 1.5; background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.18); color: #b42318; }

    /* Tab + filter */
    .pj-page .pj-bar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 22px; }
    .pj-page .pj-tabs { display: inline-flex; gap: 3px; padding: 4px; border-radius: 12px; background: rgba(120,120,128,.08); border: 1px solid var(--pj-line); }
    .pj-page .pj-tab { padding: 7px 18px; border-radius: 9px; font-size: 13px; font-weight: 600; color: var(--pj-muted); transition: all .15s ease; text-decoration: none; }
    .pj-page .pj-tab:hover { color: var(--pj-text); background: rgba(255,255,255,.6); }
    .pj-page .pj-tab.active { background: #fff; color: var(--pj-accent); box-shadow: 0 2px 6px rgba(0,0,0,.06); }
    .pj-page .pj-spacer { flex: 1; }
    .pj-page .pj-search { position: relative; width: 300px; max-width: 100%; }
    .pj-page .pj-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: #8e8e93; pointer-events: none; }
    .pj-page .pj-input, .pj-page .pj-select {
        height: 38px; border-radius: 10px; border: 1px solid var(--pj-line); background: #fff;
        color: var(--pj-text); font: inherit; font-size: 13px; outline: none; transition: all .15s ease;
    }
    .pj-page .pj-input { width: 100%; padding: 0 14px 0 34px; box-shadow: 0 1px 2px rgba(0,0,0,.02); }
    .pj-page .pj-input::placeholder { color: #8e8e93; }
    .pj-page .pj-select {
        padding: 0 32px 0 12px; appearance: none; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,.02);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center;
    }
    .pj-page .pj-input:focus, .pj-page .pj-select:focus { border-color: var(--pj-accent); box-shadow: 0 0 0 3px var(--pj-accent-soft); }

    /* Kartu Layout Diperbarui */
    .pj-page .pj-list { display: flex; flex-direction: column; gap: 16px; margin-bottom: 20px; }
    .pj-page .pj-card { display: grid; grid-template-columns: 220px 1fr; background: #fff; border: 1px solid var(--pj-line); border-radius: 16px; box-shadow: var(--pj-shadow); overflow: hidden; transition: border-color .15s ease; }
    .pj-page .pj-card:hover { border-color: rgba(78,139,188,.3); }

    /* Perbaikan area foto agar tidak kepotong & proporsional */
    .pj-page .pj-photo { position: relative; background: #f8f9fa; display: flex; align-items: center; justify-content: center; padding: 16px; border-right: 1px solid var(--pj-line); overflow: hidden; }
    .pj-page .pj-photo img { width: 100%; height: 160px; object-fit: contain; border-radius: 8px; display: block; }
    .pj-page .pj-photo-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; color: #b0b0b5; font-size: 11px; height: 100%; width: 100%; }
    .pj-page .pj-photo-empty svg { width: 32px; height: 32px; }

    .pj-page .pj-main { display: flex; flex-direction: column; min-width: 0; }
    .pj-page .pj-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; padding: 16px 20px 12px; border-bottom: 1px solid var(--pj-line); background: #fdfdfd; }
    .pj-page .pj-kind { display: inline-flex; padding: 2px 8px; border-radius: 6px; font-size: 10.5px; font-weight: 600; letter-spacing: .03em; margin-bottom: 4px; text-transform: uppercase; }
    .pj-page .pj-kind.aset { background: rgba(78,139,188,.12); color: #3b7aab; }
    .pj-page .pj-kind.ruangan { background: rgba(88,86,214,.1); color: #5856d6; }
    .pj-page .pj-name { font-size: 16px; font-weight: 700; letter-spacing: -.01em; margin: 0 0 2px; color: #111; }
    .pj-page .pj-facility { font-size: 12px; color: var(--pj-muted); }
    .pj-page .pj-badges { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; flex-shrink: 0; }
    .pj-page .pj-time { font-size: 11px; color: #8e8e93; }

    .pj-page .pj-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px 3px 7px; border-radius: 999px; font-size: 11.5px; font-weight: 600; white-space: nowrap; }
    .pj-page .pj-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .pj-page .b-amber { background: rgba(255,159,10,.15); color: #b26a00; }
    .pj-page .b-green { background: rgba(52,199,89,.12); color: #248a3d; }
    .pj-page .b-red   { background: rgba(255,59,48,.1); color: #c9342a; }
    .pj-page .b-blue  { background: rgba(78,139,188,.12); color: #3b7aab; }

    /* Grid Detail */
    .pj-page .pj-grid { display: grid; grid-template-columns: repeat(4, 1fr); border-bottom: 1px solid var(--pj-line); }
    .pj-page .pj-kv { padding: 12px 18px; border-right: 1px solid var(--pj-line); border-bottom: 1px solid var(--pj-line); min-width: 0; }
    .pj-page .pj-kv:nth-child(4n) { border-right: 0; }
    .pj-page .pj-kv.wide { grid-column: span 2; }
    .pj-page .pj-label { font-size: 10px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--pj-muted); margin-bottom: 3px; }
    .pj-page .pj-value { font-size: 12.5px; font-weight: 600; line-height: 1.4; word-break: break-word; font-variant-numeric: tabular-nums; color: #222; }
    .pj-page .pj-value small { display: block; font-size: 11px; font-weight: 400; color: var(--pj-muted); margin-top: 1px; }
    .pj-page .pj-wa { display: inline-flex; align-items: center; gap: 5px; color: #248a3d; }
    .pj-page .pj-wa:hover { text-decoration: underline; }
    .pj-page .pj-wa svg { width: 12px; height: 12px; }

    .pj-page .pj-note { padding: 12px 18px; font-size: 12.5px; line-height: 1.5; border-bottom: 1px solid var(--pj-line); color: #333; }
    .pj-page .pj-note .pj-label { margin-bottom: 2px; }
    .pj-page .pj-note.reject { background: rgba(255,59,48,.04); color: #b42318; }
    .pj-page .pj-note.reject .pj-label { color: #c9342a; }

    .pj-page .pj-foot { display: flex; justify-content: flex-end; align-items: center; gap: 8px; padding: 12px 18px; margin-top: auto; background: #fafafc; }
    .pj-page .pj-done { font-size: 11.5px; color: var(--pj-muted); margin-right: auto; }

    .pj-page .pj-btn { display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 14px; border-radius: 8px; border: 0; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s; }
    .pj-page .pj-btn-ok { background: var(--pj-accent); color: #fff; }
    .pj-page .pj-btn-ok:hover { filter: brightness(1.07); }
    .pj-page .pj-btn-no { background: rgba(255,59,48,.08); color: #c9342a; }
    .pj-page .pj-btn-no:hover { background: rgba(255,59,48,.15); }
    .pj-page .pj-btn-ghost { background: rgba(120,120,128,.08); color: #3a3a3c; }
    .pj-page .pj-btn-ghost:hover { background: rgba(120,120,128,.15); }
    .pj-page .pj-btn-danger { background: #ff3b30; color: #fff; }

    .pj-page .pj-empty { padding: 60px 20px; text-align: center; color: var(--pj-muted); font-size: 13px; background: #fff; border: 1px solid var(--pj-line); border-radius: 16px; box-shadow: var(--pj-shadow); }
    .pj-page .pj-empty svg { width: 36px; height: 36px; color: #c7c7cc; margin-bottom: 8px; }
    .pj-page .pj-empty strong { display: block; font-size: 14px; color: var(--pj-text); margin-bottom: 2px; }
    .pj-page .pj-empty .pj-btn { margin-top: 14px; height: 36px; }
    .pj-page .pj-total { font-size: 12px; color: var(--pj-muted); margin-top: 12px; }

    /* Modal */
    .pj-page .pj-modal { display: none; position: fixed; inset: 0; z-index: 99999; align-items: center; justify-content: center; padding: 20px; background: rgba(0,0,0,.32); -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px); }
    .pj-page .pj-modal-box { width: 400px; max-width: 100%; padding: 24px 22px 18px; background: rgba(255,255,255,.98); border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,.2); animation: pjPop .15s ease; }
    @keyframes pjPop { from { opacity: 0; transform: scale(.95); } to { opacity: 1; transform: scale(1); } }
    .pj-page .pj-modal-title { font-size: 16px; font-weight: 700; letter-spacing: -.01em; margin: 0 0 6px; }
    .pj-page .pj-modal-text { font-size: 12.5px; line-height: 1.5; color: #444; margin: 0 0 12px; }
    .pj-page .pj-textarea { width: 100%; min-height: 84px; padding: 10px 12px; border-radius: 8px; resize: vertical; border: 1px solid var(--pj-line); background: rgba(120,120,128,.04); font: inherit; font-size: 12.5px; outline: none; }
    .pj-page .pj-textarea:focus { background: #fff; border-color: var(--pj-accent); box-shadow: 0 0 0 3px var(--pj-accent-soft); }
    .pj-page .pj-modal-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 16px; }
    .pj-page .pj-modal-actions .pj-btn { height: 36px; }

    @media (max-width: 1000px) {
        .pj-page .pj-card { grid-template-columns: 1fr; }
        .pj-page .pj-photo { border-right: 0; border-bottom: 1px solid var(--pj-line); height: 160px; }
        .pj-page .pj-photo img { height: 100%; }
        .pj-page .pj-grid { grid-template-columns: repeat(2, 1fr); }
        .pj-page .pj-kv:nth-child(4n) { border-right: 1px solid var(--pj-line); }
        .pj-page .pj-kv:nth-child(2n) { border-right: 0; }
    }
    @media (max-width: 560px) { .pj-page .pj-search { width: 100%; } .pj-page .pj-top { flex-direction: column; } .pj-page .pj-badges { align-items: flex-start; flex-direction: row; flex-wrap: wrap; } }
</style>

<div class="pj-page">

    <div class="pj-head">
        
        <span class="pj-count" id="pjCount">{{ $peminjaman->count() }} peminjaman</span>
    </div>

    @if ($errors->any())
        <div class="pj-alert">@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
    @endif

    {{-- TAB + FILTER --}}
    <div class="pj-bar">
        <div class="pj-tabs">
            @foreach ($tabs as $key => $label)
                <a href="{{ route('peminjaman.kelola', ['tab' => $key]) }}" class="pj-tab {{ $tab === $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>

        <span class="pj-spacer"></span>

        <select id="pjJenis" class="pj-select">
            <option value="">Semua Jenis</option>
            <option value="aset">Aset</option>
            <option value="ruangan">Ruangan</option>
        </select>

        <label class="pj-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="text" id="pjCari" class="pj-input" placeholder="{{ $isAdmin ? 'Cari peminjam, unit, fasilitas…' : 'Cari fasilitas atau keperluan…' }}" autocomplete="off">
        </label>
    </div>

    {{-- DAFTAR --}}
    <div class="pj-list" id="pjList">
        @foreach ($peminjaman as $item)
            @php
                $adaRuangan = (bool) $item->ruangan_id;
                $fasilitas  = $item->aset ?? $item->ruangan;
                $namaFasilitas = $item->aset?->nama_aset ?? $item->ruangan?->nama_ruangan ?? '—';
                $jenis = $adaRuangan ? 'ruangan' : 'aset';

                $kar     = $item->user?->karyawan;
                $peminjam = $kar?->nama ?? $item->user?->name ?? '—';
                $unit    = $kar?->unit?->nama_unit ?? '—';
                $subunit = $kar?->subunitOrganisasi?->nama_subunit;

                $mulai   = $item->tanggal_mulai   ? Carbon::parse(Carbon::parse($item->tanggal_mulai)->format('Y-m-d') . ' ' . $item->jam_mulai) : null;
                $selesai = $item->tanggal_selesai ? Carbon::parse(Carbon::parse($item->tanggal_selesai)->format('Y-m-d') . ' ' . $item->jam_selesai) : null;

                $sudahKembali = $item->status_pengembalian === 'DIKEMBALIKAN';
                $terlambat = $item->status === 'DISETUJUI' && ! $sudahKembali && $selesai && $selesai->lt(now());

                if ($item->status === 'PENDING')     { $label = 'Menunggu Persetujuan'; $kelas = 'b-amber'; }
                elseif ($item->status === 'DITOLAK')  { $label = 'Ditolak';             $kelas = 'b-red'; }
                elseif ($sudahKembali)                { $label = 'Dikembalikan';        $kelas = 'b-blue'; }
                elseif ($item->status === 'DISETUJUI'){ $label = 'Sedang Dipinjam';     $kelas = 'b-green'; }
                else                                  { $label = $item->status;         $kelas = 'b-blue'; }

                $wa = $linkWa($item->no_hp);

                $cari = strtolower(implode(' ', [$namaFasilitas, $peminjam, $unit, $subunit, $item->keperluan, $item->no_hp]));
            @endphp

            <article class="pj-card" data-jenis="{{ $jenis }}" data-cari="{{ $cari }}">

                {{-- FOTO (Diperbaiki agar proporsional dan tidak terpotong) --}}
                <div class="pj-photo">
                    @if ($fasilitas?->foto)
                        <img src="{{ asset('storage/' . $fasilitas->foto) }}" alt="{{ $namaFasilitas }}">
                    @else
                        <div class="pj-photo-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                            <span>Tidak ada foto</span>
                        </div>
                    @endif
                </div>

                <div class="pj-main">

                    {{-- HEADER --}}
                    <div class="pj-top">
                        <div>
                            <span class="pj-kind {{ $jenis }}">{{ $adaRuangan ? 'Ruangan' : 'Aset' }}</span>
                            <h3 class="pj-name">{{ $namaFasilitas }}</h3>
                            <div class="pj-facility">
                                @if ($adaRuangan)
                                    {{ $item->ruangan?->lokasi ?: 'Lokasi belum diisi' }}
                                    @if ($item->ruangan?->kapasitas) · Kapasitas {{ $item->ruangan->kapasitas }} orang @endif
                                @else
                                    Kondisi: {{ match ($item->aset?->kondisi) { 'RUSAK_RINGAN' => 'Rusak Ringan', 'RUSAK_BERAT' => 'Rusak Berat', default => 'Baik' } }}
                                    @if ($item->aset) · Stok total {{ $item->aset->jumlah }} unit @endif
                                @endif
                            </div>
                        </div>

                        <div class="pj-badges">
                            <span class="pj-badge {{ $kelas }}">{{ $label }}</span>
                            @if ($terlambat) <span class="pj-badge b-red">Terlambat</span> @endif
                            <span class="pj-time">Diajukan {{ $item->created_at?->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    {{-- DETAIL FORM --}}
                    <div class="pj-grid">
                        @if ($isAdmin)
                            <div class="pj-kv">
                                <div class="pj-label">Peminjam</div>
                                <div class="pj-value">{{ $peminjam }}</div>
                            </div>
                            <div class="pj-kv">
                                <div class="pj-label">Unit / Subunit</div>
                                <div class="pj-value">{{ $unit }}@if ($subunit)<small>{{ $subunit }}</small>@endif</div>
                            </div>
                        @endif

                        <div class="pj-kv">
                            <div class="pj-label">No. HP / WhatsApp</div>
                            <div class="pj-value">
                                @if ($item->no_hp)
                                    @if ($wa)
                                        <a href="{{ $wa }}" target="_blank" rel="noopener" class="pj-wa">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/></svg>
                                            {{ $item->no_hp }}
                                        </a>
                                    @else
                                        {{ $item->no_hp }}
                                    @endif
                                @else
                                    —
                                @endif
                            </div>
                        </div>

                        <div class="pj-kv">
                            <div class="pj-label">Jumlah</div>
                            <div class="pj-value">{{ $adaRuangan ? '1 ruangan' : $item->jumlah . ' unit' }}</div>
                        </div>

                        <div class="pj-kv">
                            <div class="pj-label">Mulai</div>
                            <div class="pj-value">
                                {{ $mulai?->translatedFormat('d M Y') ?? '—' }}
                                <small>{{ $mulai?->format('H:i') }} WIB</small>
                            </div>
                        </div>

                        <div class="pj-kv">
                            <div class="pj-label">{{ $adaRuangan ? 'Selesai' : 'Pengembalian' }}</div>
                            <div class="pj-value">
                                {{ $selesai?->translatedFormat('d M Y') ?? '—' }}
                                <small>{{ $selesai?->format('H:i') }} WIB</small>
                            </div>
                        </div>

                        <div class="pj-kv">
                            <div class="pj-label">Durasi</div>
                            <div class="pj-value">{{ $mulai && $selesai ? $durasi($mulai, $selesai) : '—' }}</div>
                        </div>

                        @if (! $isAdmin)
                            <div class="pj-kv wide">
                                <div class="pj-label">Status Pengembalian</div>
                                <div class="pj-value">
                                    @if ($item->status !== 'DISETUJUI') — @elseif ($sudahKembali) Sudah dikembalikan @elseif ($terlambat) Melewati batas waktu @else Belum dikembalikan @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- KEPERLUAN --}}
                    <div class="pj-note">
                        <div class="pj-label">Keperluan</div>
                        {{ $item->keperluan ?: '—' }}
                    </div>

                    @if ($item->status === 'DITOLAK')
                        <div class="pj-note reject">
                            <div class="pj-label">Alasan Penolakan</div>
                            {{ $item->alasan_penolakan ?: 'Tidak ada alasan yang dicatat.' }}
                        </div>
                    @endif

                    {{-- AKSI --}}
                    @if ($isAdmin)
                        <div class="pj-foot">
                            @if ($item->status === 'PENDING')
                                <button type="button" class="pj-btn pj-btn-no btn-aksi"
                                    data-mode="tolak" data-action="{{ route('peminjaman.reject', $item->id) }}" data-nama="{{ $namaFasilitas }}" data-peminjam="{{ $peminjam }}">Tolak</button>
                                <button type="button" class="pj-btn pj-btn-ok btn-aksi"
                                    data-mode="setuju" data-action="{{ route('peminjaman.approve', $item->id) }}" data-nama="{{ $namaFasilitas }}" data-peminjam="{{ $peminjam }}">Setujui</button>
                            @elseif ($item->status === 'DISETUJUI' && ! $sudahKembali)
                                <button type="button" class="pj-btn pj-btn-ok btn-aksi"
                                    data-mode="kembali" data-action="{{ route('peminjaman.return', $item->id) }}" data-nama="{{ $namaFasilitas }}" data-peminjam="{{ $peminjam }}">Tandai Dikembalikan</button>
                            @else
                                <span class="pj-done">Selesai diproses</span>
                            @endif
                        </div>
                    @endif

                </div>
            </article>
        @endforeach

        <div class="pj-empty" id="pjEmpty" style="{{ $peminjaman->count() ? 'display:none' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="3"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
            <strong>Belum ada data peminjaman</strong>
            <span id="pjEmptyText">
                @if ($tab === 'pengajuan') Belum ada pengajuan yang menunggu persetujuan.
                @elseif ($tab === 'ditolak') Tidak ada pengajuan yang ditolak.
                @elseif ($tab === 'dipinjam') Tidak ada peminjaman yang sedang berlangsung.
                @else Belum ada peminjaman yang tercatat dikembalikan.
                @endif
            </span>
            @unless ($isAdmin)
                <div><a href="{{ route('peminjaman.index') }}" class="pj-btn pj-btn-ok">Buka Katalog</a></div>
            @endunless
        </div>
    </div>

    <div class="pj-total">Total data pada tab ini: {{ $peminjaman->count() }} peminjaman</div>

    {{-- MODAL AKSI --}}
    @if ($isAdmin)
        <div class="pj-modal" id="pjModal">
            <div class="pj-modal-box">
                <h3 class="pj-modal-title" id="pjModalTitle">—</h3>
                <p class="pj-modal-text" id="pjModalText">—</p>

                <form id="pjModalForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" id="pjModalStatus" value="">

                    <div id="pjAlasanWrap" style="display:none;">
                        <textarea name="alasan_penolakan" id="pjAlasan" class="pj-textarea" maxlength="255" placeholder="Tulis alasan penolakan… (wajib)"></textarea>
                    </div>

                    <div class="pj-modal-actions">
                        <button type="button" class="pj-btn pj-btn-ghost" id="pjModalBatal">Batal</button>
                        <button type="submit" class="pj-btn pj-btn-ok" id="pjModalSubmit">Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ---------- Filter & pencarian ---------- */
    const cards = document.querySelectorAll('#pjList .pj-card');
    const cari = document.getElementById('pjCari');
    const jenis = document.getElementById('pjJenis');
    const empty = document.getElementById('pjEmpty');
    const emptyText = document.getElementById('pjEmptyText');
    const count = document.getElementById('pjCount');
    const defaultEmpty = emptyText.textContent;

    function filter() {
        const q = cari.value.trim().toLowerCase();
        const j = jenis.value;
        let shown = 0;

        cards.forEach(function (c) {
            const ok = (!q || c.dataset.cari.indexOf(q) !== -1) && (!j || c.dataset.jenis === j);
            c.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });

        count.textContent = shown + ' peminjaman';

        if (cards.length) {
            empty.style.display = shown ? 'none' : '';
            emptyText.textContent = shown ? '' : 'Tidak ada data yang cocok dengan pencarian atau filter.';
        } else {
            emptyText.textContent = defaultEmpty;
        }
    }

    cari.addEventListener('input', filter);
    jenis.addEventListener('change', filter);

    /* ---------- Modal aksi (admin) ---------- */
    const modal = document.getElementById('pjModal');
    if (!modal) return;

    const form = document.getElementById('pjModalForm');
    const title = document.getElementById('pjModalTitle');
    const text = document.getElementById('pjModalText');
    const statusInput = document.getElementById('pjModalStatus');
    const alasanWrap = document.getElementById('pjAlasanWrap');
    const alasan = document.getElementById('pjAlasan');
    const submit = document.getElementById('pjModalSubmit');

    const MODE = {
        setuju:  { judul: 'Setujui pengajuan?',  teks: 'Setujui peminjaman {n} oleh {p}?', status: 'DISETUJUI', tombol: 'Setujui', kelas: 'pj-btn-ok', alasan: false },
        tolak:   { judul: 'Tolak pengajuan?',    teks: 'Tolak peminjaman {n} oleh {p}. Alasan penolakan wajib diisi.', status: 'DITOLAK', tombol: 'Tolak', kelas: 'pj-btn-danger', alasan: true },
        kembali: { judul: 'Catat pengembalian?',  teks: 'Catat bahwa {n} oleh {p} sudah dikembalikan?', status: '', tombol: 'Ya, sudah kembali', kelas: 'pj-btn-ok', alasan: false }
    };

    document.querySelectorAll('.btn-aksi').forEach(function (b) {
        b.addEventListener('click', function () {
            const m = MODE[b.dataset.mode];

            form.action = b.dataset.action;
            title.textContent = m.judul;
            text.textContent = m.teks.replace('{n}', b.dataset.nama).replace('{p}', b.dataset.peminjam);

            statusInput.value = m.status;
            statusInput.disabled = !m.status;

            alasanWrap.style.display = m.alasan ? 'block' : 'none';
            alasan.required = m.alasan;
            alasan.value = '';

            submit.textContent = m.tombol;
            submit.className = 'pj-btn ' + m.kelas;

            modal.style.display = 'flex';
            if (m.alasan) alasan.focus();
        });
    });

    function tutup() { modal.style.display = 'none'; }
    document.getElementById('pjModalBatal').addEventListener('click', tutup);
    modal.addEventListener('click', function (e) { if (e.target === modal) tutup(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') tutup(); });
});
</script>

@endsection