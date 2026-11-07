@extends('layouts.app')

@section('title', 'Publikasi Web Profil DJPK | HumasIn')
@section('page-title', 'Publikasi')
@section('page-description', 'Permintaan publikasi materi kegiatan ke website profil DJPK')

@section('content')

<style>
    .pb-page {
        --pb-accent: #4E8BBC; --pb-accent-soft: rgba(78,139,188,.12);
        --pb-text: #1d1d1f; --pb-muted: #6e6e73; --pb-line: rgba(0,0,0,.07);
        --pb-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);
        max-width: 1280px; margin: 0 auto; color: var(--pb-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .pb-page *, .pb-page *::before, .pb-page *::after { box-sizing: border-box; }

    .pb-page .pb-switch { display: inline-flex; gap: 2px; padding: 3px; margin-bottom: 20px; border-radius: 11px; background: rgba(120,120,128,.12); }
    .pb-page .pb-switch a { padding: 7px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; color: var(--pb-muted); text-decoration: none; }
    .pb-page .pb-switch a.active { background: #fff; color: var(--pb-text); box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 0 0 .5px rgba(0,0,0,.04); }

    .pb-page .pb-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
    .pb-page .pb-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .pb-page .pb-sub { font-size: 13px; color: var(--pb-muted); margin: 0; }
    .pb-page .pb-add { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 999px; color: #fff; font-size: 13px; font-weight: 600; text-decoration: none;
        background: linear-gradient(180deg, #5b98c8, #4E8BBC); box-shadow: 0 1px 2px rgba(0,0,0,.12), 0 6px 16px rgba(78,139,188,.28), inset 0 1px 0 rgba(255,255,255,.25); }
    .pb-page .pb-add:hover { filter: brightness(1.05); }

    .pb-page .pb-bar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
    .pb-page .pb-tabs { display: inline-flex; gap: 2px; padding: 3px; border-radius: 11px; background: rgba(120,120,128,.12); }
    .pb-page .pb-tab { padding: 7px 14px; border: 0; border-radius: 8px; background: transparent; font: inherit; font-size: 13px; font-weight: 600; color: var(--pb-muted); cursor: pointer; }
    .pb-page .pb-tab.active { background: #fff; color: var(--pb-text); box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 0 0 .5px rgba(0,0,0,.04); }
    .pb-page .pb-tab b { margin-left: 6px; padding: 1px 7px; border-radius: 999px; background: var(--pb-accent-soft); color: #3b7aab; font-size: 11px; }
    .pb-page .pb-spacer { flex: 1; }
    .pb-page .pb-search { position: relative; width: 300px; max-width: 100%; }
    .pb-page .pb-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: #8e8e93; pointer-events: none; }
    .pb-page .pb-input { width: 100%; height: 38px; padding: 0 14px 0 34px; border-radius: 10px; border: 1px solid transparent; background: rgba(120,120,128,.1); font: inherit; font-size: 13px; outline: none; }
    .pb-page .pb-input:focus { background: #fff; border-color: var(--pb-accent); box-shadow: 0 0 0 4px var(--pb-accent-soft); }

    .pb-page .pb-list { display: flex; flex-direction: column; gap: 16px; margin-bottom: 32px; }
    .pb-page .pb-card { display: grid; grid-template-columns: 200px 1fr; background: #fff; border: 1px solid var(--pb-line); border-radius: 16px; box-shadow: var(--pb-shadow); overflow: hidden; }
    .pb-page .pb-photo { position: relative; background: #f5f5f7; min-height: 100%; display: block; }
    .pb-page .pb-photo img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .pb-page .pb-photo-empty { position: absolute; inset: 0; display: grid; place-items: center; align-content: center; gap: 6px; color: #b0b0b5; font-size: 11px; }
    .pb-page .pb-photo-empty svg { width: 30px; height: 30px; }

    .pb-page .pb-main { display: flex; flex-direction: column; min-width: 0; justify-content: space-between; }
    .pb-page .pb-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; padding: 16px 20px 12px; }
    .pb-page .pb-name { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 4px; }
    .pb-page .pb-who { font-size: 12.5px; color: var(--pb-muted); line-height: 1.5; }
    .pb-page .pb-who b { color: var(--pb-text); font-weight: 600; }
    .pb-page .pb-badges { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; flex-shrink: 0; }
    .pb-page .pb-time { font-size: 11.5px; color: var(--pb-muted); }
    .pb-page .pb-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .pb-page .pb-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .pb-page .b-amber { background: rgba(255,159,10,.16); color: #b26a00; }
    .pb-page .b-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .pb-page .b-red   { background: rgba(255,59,48,.12); color: #c9342a; }

    .pb-page .pb-preview-desc { padding: 0 20px 12px; font-size: 13px; color: #48484a; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    .pb-page .pb-foot { display: flex; align-items: center; justify-content: flex-end; gap: 8px; padding: 12px 20px; border-top: 1px solid var(--pb-line); background: #fafafc; }
    .pb-page .pb-file { display: inline-flex; align-items: center; gap: 7px; margin-right: auto; font-size: 12px; font-weight: 600; color: #3b7aab; text-decoration: none; }
    .pb-page .pb-file svg { width: 14px; height: 14px; }
    .pb-page .pb-file.none { color: var(--pb-muted); font-weight: 500; }
    
    .pb-page .pb-btn { display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 14px; border-radius: 8px; border: 0; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; }
    .pb-page .pb-btn-ok { background: var(--pb-accent); color: #fff; } .pb-page .pb-btn-ok:hover { filter: brightness(1.07); }
    .pb-page .pb-btn-no { background: rgba(255,59,48,.1); color: #c9342a; } .pb-page .pb-btn-no:hover { background: rgba(255,59,48,.18); }
    .pb-page .pb-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; } .pb-page .pb-btn-ghost:hover { background: rgba(120,120,128,.18); }
    .pb-page .pb-btn-danger { background: #ff3b30; color: #fff; }

    .pb-page .pb-empty { padding: 72px 20px; text-align: center; color: var(--pb-muted); font-size: 13px; background: #fff; border: 1px solid var(--pb-line); border-radius: 16px; box-shadow: var(--pb-shadow); }
    .pb-page .pb-empty svg { width: 40px; height: 40px; color: #c7c7cc; margin-bottom: 10px; }
    .pb-page .pb-empty strong { display: block; font-size: 15px; color: var(--pb-text); margin-bottom: 4px; }

    /* Modal Action (Approve/Reject) */
    .pb-page .pb-modal { display: none; position: fixed; inset: 0; z-index: 99999; align-items: center; justify-content: center; padding: 20px; background: rgba(0,0,0,.32); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); }
    .pb-page .pb-modal-box { width: 420px; max-width: 100%; padding: 26px; background: rgba(255,255,255,.98); border-radius: 18px; box-shadow: 0 0 0 1px rgba(0,0,0,.06), 0 24px 60px rgba(0,0,0,.25); }
    .pb-page .pb-modal-title { font-size: 17px; font-weight: 700; margin: 0 0 8px; }
    .pb-page .pb-modal-text { font-size: 13px; line-height: 1.6; color: #3a3a3c; margin: 0 0 14px; }
    .pb-page .pb-textarea { width: 100%; min-height: 90px; padding: 12px; border-radius: 10px; resize: vertical; border: 1px solid var(--pb-line); background: rgba(120,120,128,.08); font: inherit; font-size: 13px; outline: none; }
    .pb-page .pb-textarea:focus { background: #fff; border-color: var(--pb-accent); box-shadow: 0 0 0 4px var(--pb-accent-soft); }
    .pb-page .pb-modal-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 16px; }

    /* Modal Detail Lengkap */
    .pb-page .pb-detail-box { width: 680px; max-width: 100%; max-height: 85vh; display: flex; flex-direction: column; background: #fff; border-radius: 20px; box-shadow: 0 24px 60px rgba(0,0,0,.25); overflow: hidden; }
    .pb-page .pb-detail-head { padding: 18px 24px; border-bottom: 1px solid var(--pb-line); display: flex; justify-content: space-between; align-items: center; background: #fafafc; }
    .pb-page .pb-detail-title { font-size: 16px; font-weight: 700; margin: 0; }
    .pb-page .pb-detail-close { background: transparent; border: 0; font-size: 20px; cursor: pointer; color: var(--pb-muted); }
    .pb-page .pb-detail-body { padding: 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 18px; font-size: 13px; }
    .pb-page .pb-detail-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; background: #f8f8fa; padding: 14px; border-radius: 12px; }
    .pb-page .pb-detail-item .lbl { font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--pb-muted); margin-bottom: 2px; }
    .pb-page .pb-detail-item .val { font-weight: 500; color: var(--pb-text); word-break: break-word; }

    @media (max-width: 900px) {
        .pb-page .pb-card { grid-template-columns: 1fr; }
        .pb-page .pb-photo { height: 180px; min-height: 0; }
        .pb-page .pb-detail-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 560px) { .pb-page .pb-search { width: 100%; } .pb-page .pb-top { flex-direction: column; } .pb-page .pb-badges { align-items: flex-start; } }
</style>

@php
    use Carbon\Carbon;
    $hitung = [
        'ALL'       => $publikasis->count(),
        'PENDING'   => $publikasis->where('status', 'PENDING')->count(),
        'DISETUJUI' => $publikasis->where('status', 'DISETUJUI')->count(),
        'DITOLAK'   => $publikasis->where('status', 'DITOLAK')->count(),
    ];
@endphp

<div class="pb-page">

    <div class="pb-switch">
        <a href="{{ route('publikasi.index') }}" class="active">Publikasi Web Profil DJPK</a>
        <a href="{{ route('opini.index') }}">Opini Eselon II</a>
    </div>

    <div class="pb-head">
        <div>
            <h2 class="pb-title">{{ $isAdmin ? 'Kelola Publikasi Website DJPK' : 'Publikasi Saya' }}</h2>
            <p class="pb-sub">Kelola dan ajukan materi publikasi kegiatan ke website profil DJPK.</p>
        </div>
        @unless ($isAdmin)
            <a href="{{ route('publikasi.create') }}" class="pb-add">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                Ajukan Publikasi Baru
            </a>
        @endunless
    </div>

    @if ($errors->any())
        <div style="margin-bottom:18px;padding:12px 16px;border-radius:12px;font-size:13px;background:rgba(255,59,48,.08);border:1px solid rgba(255,59,48,.18);color:#b42318;">
            @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    <div class="pb-bar">
        <div class="pb-tabs">
            <button type="button" class="pb-tab active" data-s="">Semua<b>{{ $hitung['ALL'] }}</b></button>
            <button type="button" class="pb-tab" data-s="PENDING">Menunggu<b>{{ $hitung['PENDING'] }}</b></button>
            <button type="button" class="pb-tab" data-s="DISETUJUI">Disetujui<b>{{ $hitung['DISETUJUI'] }}</b></button>
            <button type="button" class="pb-tab" data-s="DITOLAK">Ditolak<b>{{ $hitung['DITOLAK'] }}</b></button>
        </div>
        <span class="pb-spacer"></span>
        <label class="pb-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="text" id="pbCari" class="pb-input" placeholder="Cari kegiatan, pemohon, lokasi…" autocomplete="off">
        </label>
    </div>

    <div class="pb-list" id="pbList">
        @foreach ($publikasis as $item)
            @php
                $kar = $item->user?->karyawan;
                $pemohon = $kar?->nama ?? $item->user?->name ?? '—';
                $unit = $kar?->unit?->nama_unit;
                $subunit = $kar?->subunitOrganisasi?->nama_subunit;
                $tgl = $item->when_tanggal ? Carbon::parse($item->when_tanggal)->translatedFormat('d F Y') : '—';

                [$label, $kelas] = match ($item->status) {
                    'DISETUJUI' => ['Disetujui / Dipublikasikan', 'b-green'],
                    'DITOLAK'   => ['Ditolak', 'b-red'],
                    default     => ['Menunggu Review', 'b-amber'],
                };

                $cari = strtolower(implode(' ', [$item->nama_kegiatan, $pemohon, $unit, $subunit, $item->where_lokasi, $item->deskripsi]));
            @endphp

            <article class="pb-card" data-status="{{ $item->status }}" data-cari="{{ $cari }}">
                @if ($item->foto_kegiatan)
                    <a href="{{ asset('storage/' . $item->foto_kegiatan) }}" target="_blank" rel="noopener" class="pb-photo" title="Klik untuk memperbesar">
                        <img src="{{ asset('storage/' . $item->foto_kegiatan) }}" alt="{{ $item->nama_kegiatan }}">
                    </a>
                @else
                    <div class="pb-photo"><div class="pb-photo-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                        Tidak ada foto
                    </div></div>
                @endif

                <div class="pb-main">
                    <div>
                        <div class="pb-top">
                            <div>
                                <h3 class="pb-name">{{ $item->nama_kegiatan }}</h3>
                                <div class="pb-who">
                                    @if ($isAdmin)
                                        <b>{{ $pemohon }}</b>@if ($unit) · {{ $unit }}@endif @if ($subunit) · {{ $subunit }}@endif
                                    @else
                                        {{ $tgl }} · {{ $item->where_lokasi }}
                                    @endif
                                </div>
                            </div>
                            <div class="pb-badges">
                                <span class="pb-badge {{ $kelas }}">{{ $label }}</span>
                                <span class="pb-time">Diajukan {{ $item->created_at?->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        </div>

                        <div class="pb-preview-desc">
                            {{ $item->deskripsi }}
                        </div>
                    </div>

                    <div class="pb-foot">
                        @if ($item->file_pendukung)
                            <a href="{{ asset('storage/' . $item->file_pendukung) }}" target="_blank" rel="noopener" class="pb-file">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.5 12.5 21a5.5 5.5 0 0 1-7.8-7.8l9-9a3.7 3.7 0 0 1 5.2 5.2l-9 9a1.8 1.8 0 0 1-2.6-2.6l8.3-8.3"/></svg>
                                File pendukung
                            </a>
                        @else
                            <span class="pb-file none">Tanpa file pendukung</span>
                        @endif

                        <button type="button" class="pb-btn pb-btn-ghost btn-detail" 
                            data-nama="{{ $item->nama_kegiatan }}"
                            data-pemohon="{{ $pemohon }}"
                            data-unit="{{ $unit }} @if($subunit) · {{ $subunit }} @endif"
                            data-tgl="{{ $tgl }}"
                            data-lokasi="{{ $item->where_lokasi }}"
                            data-deskripsi="{{ $item->deskripsi }}"
                            data-what="{{ $item->what }}"
                            data-who="{{ $item->who }}"
                            data-why="{{ $item->why }}"
                            data-how="{{ $item->how }}"
                            data-status="{{ $label }}"
                            data-alasan="{{ $item->alasan_penolakan }}"
                            data-file="{{ $item->file_pendukung ? asset('storage/' . $item->file_pendukung) : '' }}">
                            Detail
                        </button>

                        @if ($isAdmin && $item->status === 'PENDING')
                            <button type="button" class="pb-btn pb-btn-no btn-aksi" data-mode="tolak" data-action="{{ route('publikasi.updateStatus', $item->id) }}" data-nama="{{ $item->nama_kegiatan }}">Tolak</button>
                            <button type="button" class="pb-btn pb-btn-ok btn-aksi" data-mode="setuju" data-action="{{ route('publikasi.updateStatus', $item->id) }}" data-nama="{{ $item->nama_kegiatan }}">Terbitkan</button>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach

        <div class="pb-empty" id="pbEmpty" style="{{ $publikasis->count() ? 'display:none' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="3"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
            <strong id="pbEmptyTitle">Belum ada pengajuan publikasi</strong>
            <span id="pbEmptyText">{{ $publikasis->count() ? '' : 'Belum ada data pengajuan publikasi website DJPK.' }}</span>
        </div>
    </div>

    {{-- MODAL DETAIL LENGKAP --}}
    <div class="pb-modal" id="modalDetail">
        <div class="pb-detail-box">
            <div class="pb-detail-head">
                <h3 class="pb-detail-title" id="detJudul">Detail Publikasi</h3>
                <button type="button" class="pb-detail-close" id="detTutup">&times;</button>
            </div>
            <div class="pb-detail-body">
                <div>
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;color:var(--pb-muted);margin-bottom:4px;">Pemohon & Instansi</div>
                    <div id="detPemohon" style="font-weight:600;font-size:14px;">—</div>
                </div>

                <div class="pb-detail-grid">
                    <div class="pb-detail-item"><div class="lbl">What · Apa</div><div class="val" id="detWhat">—</div></div>
                    <div class="pb-detail-item"><div class="lbl">Who · Siapa</div><div class="val" id="detWho">—</div></div>
                    <div class="pb-detail-item"><div class="lbl">When · Kapan</div><div class="val" id="detTgl">—</div></div>
                    <div class="pb-detail-item"><div class="lbl">Where · Di Mana</div><div class="val" id="detLokasi">—</div></div>
                    <div class="pb-detail-item"><div class="lbl">Why · Mengapa</div><div class="val" id="detWhy">—</div></div>
                    <div class="pb-detail-item"><div class="lbl">How · Bagaimana</div><div class="val" id="detHow">—</div></div>
                </div>

                <div>
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;color:var(--pb-muted);margin-bottom:4px;">Deskripsi / Narasi Kegiatan</div>
                    <div id="detDeskripsi" style="white-space:pre-line;line-height:1.6;">—</div>
                </div>

                <div id="detAlasanWrap" style="display:none;background:rgba(255,59,48,.06);padding:12px;border-radius:10px;border:1px solid rgba(255,59,48,.15);">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;color:#c9342a;margin-bottom:4px;">Alasan Penolakan</div>
                    <div id="detAlasan" style="color:#b42318;white-space:pre-line;">—</div>
                </div>

                <div id="detFileWrap" style="display:none;padding-top:4px;">
                    <a href="#" id="detFileLink" target="_blank" rel="noopener" class="pb-file" style="font-size:13px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.5 12.5 21a5.5 5.5 0 0 1-7.8-7.8l9-9a3.7 3.7 0 0 1 5.2 5.2l-9 9a1.8 1.8 0 0 1-2.6-2.6l8.3-8.3"/></svg>
                        Unduh File Pendukung
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($isAdmin)
        <div class="pb-modal" id="pbModal">
            <div class="pb-modal-box">
                <h3 class="pb-modal-title" id="pbModalTitle">—</h3>
                <p class="pb-modal-text" id="pbModalText">—</p>
                <form id="pbModalForm" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" id="pbModalStatus">
                    <div id="pbAlasanWrap" style="display:none;margin-bottom:12px;">
                        <textarea name="alasan_penolakan" id="pbAlasan" class="pb-textarea" placeholder="Tulis alasan penolakan… (wajib)"></textarea>
                    </div>
                    <div class="pb-modal-actions">
                        <button type="button" class="pb-btn pb-btn-ghost" id="pbBatal" style="height:38px;">Batal</button>
                        <button type="submit" class="pb-btn pb-btn-ok" id="pbSubmit" style="height:38px;">Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('#pbList .pb-card');
    const cari = document.getElementById('pbCari');
    const empty = document.getElementById('pbEmpty');
    const emptyTitle = document.getElementById('pbEmptyTitle');
    const emptyText = document.getElementById('pbEmptyText');
    let status = '';

    function filter() {
        const q = cari.value.trim().toLowerCase();
        let shown = 0;
        cards.forEach(function (c) {
            const ok = (!q || c.dataset.cari.indexOf(q) !== -1) && (!status || c.dataset.status === status);
            c.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });
        if (cards.length) {
            empty.style.display = shown ? 'none' : '';
            emptyTitle.textContent = 'Tidak ada data';
            emptyText.textContent = 'Tidak ada pengajuan yang cocok dengan filter atau pencarian.';
        }
    }

    cari.addEventListener('input', filter);
    document.querySelectorAll('.pb-tab').forEach(function (t) {
        t.addEventListener('click', function () {
            document.querySelectorAll('.pb-tab').forEach(function (x) { x.classList.remove('active'); });
            t.classList.add('active');
            status = t.dataset.s;
            filter();
        });
    });

    // Modal Detail
    const modalDetail = document.getElementById('modalDetail');
    document.querySelectorAll('.btn-detail').forEach(function (b) {
        b.addEventListener('click', function () {
            document.getElementById('detJudul').textContent = b.dataset.nama;
            document.getElementById('detPemohon').textContent = b.dataset.pemohon + (b.dataset.unit ? ' · ' + b.dataset.unit : '');
            document.getElementById('detWhat').textContent = b.dataset.what || '—';
            document.getElementById('detWho').textContent = b.dataset.who || '—';
            document.getElementById('detTgl').textContent = b.dataset.tgl || '—';
            document.getElementById('detLokasi').textContent = b.dataset.lokasi || '—';
            document.getElementById('detWhy').textContent = b.dataset.why || '—';
            document.getElementById('detHow').textContent = b.dataset.how || '—';
            document.getElementById('detDeskripsi').textContent = b.dataset.deskripsi || '—';

            const alasanWrap = document.getElementById('detAlasanWrap');
            if (b.dataset.alasan) {
                document.getElementById('detAlasan').textContent = b.dataset.alasan;
                alasanWrap.style.display = 'block';
            } else {
                alasanWrap.style.display = 'none';
            }

            const fileWrap = document.getElementById('detFileWrap');
            const fileLink = document.getElementById('detFileLink');
            if (b.dataset.file) {
                fileLink.href = b.dataset.file;
                fileWrap.style.display = 'block';
            } else {
                fileWrap.style.display = 'none';
            }

            modalDetail.style.display = 'flex';
        });
    });

    function tutupDetail() { modalDetail.style.display = 'none'; }
    document.getElementById('detTutup').addEventListener('click', tutupDetail);
    modalDetail.addEventListener('click', function (e) { if (e.target === modalDetail) tutupDetail(); });

    // Modal Action Approve/Reject
    const modal = document.getElementById('pbModal');
    if (modal) {
        const form = document.getElementById('pbModalForm');
        const MODE = {
            setuju: { judul: 'Terbitkan publikasi?', teks: 'Setujui dan publikasikan materi "{n}"?', status: 'DISETUJUI', tombol: 'Terbitkan', kelas: 'pb-btn-ok', alasan: false },
            tolak:  { judul: 'Tolak publikasi?', teks: 'Tolak materi "{n}". Alasan penolakan wajib diisi.', status: 'DITOLAK', tombol: 'Tolak', kelas: 'pb-btn-danger', alasan: true }
        };

        document.querySelectorAll('.btn-aksi').forEach(function (b) {
            b.addEventListener('click', function () {
                const m = MODE[b.dataset.mode];
                form.action = b.dataset.action;
                document.getElementById('pbModalTitle').textContent = m.judul;
                document.getElementById('pbModalText').textContent = m.teks.replace('{n}', b.dataset.nama);
                document.getElementById('pbModalStatus').value = m.status;
                document.getElementById('pbAlasanWrap').style.display = m.alasan ? 'block' : 'none';
                const a = document.getElementById('pbAlasan'); a.required = m.alasan; a.value = '';
                const s = document.getElementById('pbSubmit'); s.textContent = m.tombol; s.className = 'pb-btn ' + m.kelas;
                modal.style.display = 'flex';
                if (m.alasan) a.focus();
            });
        });

        function tutupModal() { modal.style.display = 'none'; }
        document.getElementById('pbBatal').addEventListener('click', tutupModal);
        modal.addEventListener('click', function (e) { if (e.target === modal) tutupModal(); });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (modalDetail) modalDetail.style.display = 'none';
            if (modal) modal.style.display = 'none';
        }
    });
});
</script>

@endsection