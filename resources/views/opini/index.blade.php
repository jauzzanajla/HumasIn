@extends('layouts.app')

@section('title', 'Opini Eselon II | HumasIn')
@section('page-title', 'Publikasi')
@section('page-description', 'Pengajuan naskah opini dari unit Eselon II')

@section('content')

<style>
    .op-page {
        --op-accent: #4E8BBC; --op-accent-soft: rgba(78,139,188,.12);
        --op-text: #1d1d1f; --op-muted: #6e6e73; --op-line: rgba(0,0,0,.07);
        --op-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);
        max-width: 1280px; margin: 0 auto; color: var(--op-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .op-page *, .op-page *::before, .op-page *::after { box-sizing: border-box; }

    .op-page .op-switch { display: inline-flex; gap: 2px; padding: 3px; margin-bottom: 20px; border-radius: 11px; background: rgba(120,120,128,.12); }
    .op-page .op-switch a { padding: 7px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; color: var(--op-muted); }
    .op-page .op-switch a.active { background: #fff; color: var(--op-text); box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 0 0 .5px rgba(0,0,0,.04); }

    .op-page .op-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
    .op-page .op-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .op-page .op-sub { font-size: 13px; color: var(--op-muted); margin: 0; }
    .op-page .op-add { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 999px; color: #fff; font-size: 13px; font-weight: 600;
        background: linear-gradient(180deg, #5b98c8, #4E8BBC); box-shadow: 0 1px 2px rgba(0,0,0,.12), 0 6px 16px rgba(78,139,188,.28), inset 0 1px 0 rgba(255,255,255,.25); }
    .op-page .op-add:hover { filter: brightness(1.05); }

    .op-page .op-bar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
    .op-page .op-tabs { display: inline-flex; gap: 2px; padding: 3px; border-radius: 11px; background: rgba(120,120,128,.12); }
    .op-page .op-tab { padding: 7px 14px; border: 0; border-radius: 8px; background: transparent; font: inherit; font-size: 13px; font-weight: 600; color: var(--op-muted); cursor: pointer; }
    .op-page .op-tab.active { background: #fff; color: var(--op-text); box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 0 0 .5px rgba(0,0,0,.04); }
    .op-page .op-tab b { margin-left: 6px; padding: 1px 7px; border-radius: 999px; background: var(--op-accent-soft); color: #3b7aab; font-size: 11px; }
    .op-page .op-spacer { flex: 1; }
    .op-page .op-search { position: relative; width: 300px; max-width: 100%; }
    .op-page .op-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: #8e8e93; pointer-events: none; }
    .op-page .op-input { width: 100%; height: 38px; padding: 0 14px 0 34px; border-radius: 10px; border: 1px solid transparent; background: rgba(120,120,128,.1); font: inherit; font-size: 13px; outline: none; }
    .op-page .op-input:focus { background: #fff; border-color: var(--op-accent); box-shadow: 0 0 0 4px var(--op-accent-soft); }

    .op-page .op-list { display: flex; flex-direction: column; gap: 16px; margin-bottom: 32px; }
    .op-page .op-card { display: grid; grid-template-columns: 200px 1fr; background: #fff; border: 1px solid var(--op-line); border-radius: 20px; box-shadow: var(--op-shadow); overflow: hidden; }
    .op-page .op-photo { position: relative; background: #f5f5f7; min-height: 100%; display: block; }
    .op-page .op-photo img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .op-page .op-photo-empty { position: absolute; inset: 0; display: grid; place-items: center; align-content: center; gap: 6px; color: #b0b0b5; font-size: 11px; text-align: center; padding: 10px; }
    .op-page .op-photo-empty svg { width: 34px; height: 34px; }

    .op-page .op-main { display: flex; flex-direction: column; min-width: 0; }
    .op-page .op-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; padding: 18px 22px 12px; }
    .op-page .op-name { font-size: 18px; font-weight: 700; letter-spacing: -.015em; line-height: 1.3; margin: 0 0 4px; }
    .op-page .op-by { font-size: 12.5px; color: var(--op-muted); }
    .op-page .op-by b { color: var(--op-text); font-weight: 600; }
    .op-page .op-badges { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; flex-shrink: 0; }
    .op-page .op-time { font-size: 11.5px; color: var(--op-muted); }
    .op-page .op-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .op-page .op-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .op-page .b-amber { background: rgba(255,159,10,.16); color: #b26a00; }
    .op-page .b-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .op-page .b-red   { background: rgba(255,59,48,.12); color: #c9342a; }

    .op-page .op-grid { display: grid; grid-template-columns: repeat(3, 1fr); border-top: 1px solid var(--op-line); }
    .op-page .op-kv { padding: 12px 22px; border-right: 1px solid var(--op-line); min-width: 0; }
    .op-page .op-kv:last-child { border-right: 0; }
    .op-page .op-label { font-size: 10.5px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--op-muted); margin-bottom: 4px; }
    .op-page .op-value { font-size: 13px; font-weight: 500; line-height: 1.5; word-break: break-word; }
    .op-page .op-block { padding: 12px 22px; border-top: 1px solid var(--op-line); font-size: 13px; line-height: 1.65; white-space: pre-line; }
    .op-page .op-reject { background: rgba(255,59,48,.06); color: #b42318; }
    .op-page .op-reject .op-label { color: #c9342a; }

    .op-page .op-foot { display: flex; align-items: center; justify-content: flex-end; gap: 8px; padding: 13px 22px; margin-top: auto; border-top: 1px solid var(--op-line); background: #fafafc; }
    .op-page .op-file { display: inline-flex; align-items: center; gap: 7px; margin-right: auto; font-size: 12.5px; font-weight: 600; color: #3b7aab; }
    .op-page .op-file svg { width: 15px; height: 15px; }
    .op-page .op-file.none { color: var(--op-muted); font-weight: 500; }
    .op-page .op-btn { display: inline-flex; align-items: center; justify-content: center; height: 34px; padding: 0 16px; border-radius: 9px; border: 0; font: inherit; font-size: 12.5px; font-weight: 600; cursor: pointer; }
    .op-page .op-btn-ok { background: var(--op-accent); color: #fff; } .op-page .op-btn-ok:hover { filter: brightness(1.07); }
    .op-page .op-btn-no { background: rgba(255,59,48,.1); color: #c9342a; } .op-page .op-btn-no:hover { background: rgba(255,59,48,.18); }
    .op-page .op-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; } .op-page .op-btn-ghost:hover { background: rgba(120,120,128,.18); }
    .op-page .op-btn-danger { background: #ff3b30; color: #fff; }

    .op-page .op-empty { padding: 72px 20px; text-align: center; color: var(--op-muted); font-size: 13px; background: #fff; border: 1px solid var(--op-line); border-radius: 20px; box-shadow: var(--op-shadow); }
    .op-page .op-empty svg { width: 40px; height: 40px; color: #c7c7cc; margin-bottom: 10px; }
    .op-page .op-empty strong { display: block; font-size: 15px; color: var(--op-text); margin-bottom: 4px; }

    .op-page .op-modal { display: none; position: fixed; inset: 0; z-index: 99999; align-items: center; justify-content: center; padding: 20px; background: rgba(0,0,0,.32); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); }
    .op-page .op-modal-box { width: 420px; max-width: 100%; padding: 28px 26px 22px; background: rgba(255,255,255,.97); border-radius: 20px; box-shadow: 0 0 0 1px rgba(0,0,0,.06), 0 24px 60px rgba(0,0,0,.25); }
    .op-page .op-modal-title { font-size: 17px; font-weight: 700; margin: 0 0 8px; }
    .op-page .op-modal-text { font-size: 13px; line-height: 1.6; color: #3a3a3c; margin: 0 0 14px; }
    .op-page .op-textarea { width: 100%; min-height: 92px; padding: 12px 14px; border-radius: 10px; resize: vertical; border: 1px solid var(--op-line); background: rgba(120,120,128,.08); font: inherit; font-size: 13px; outline: none; }
    .op-page .op-textarea:focus { background: #fff; border-color: var(--op-accent); box-shadow: 0 0 0 4px var(--op-accent-soft); }
    .op-page .op-modal-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 18px; }
    .op-page .op-modal-actions .op-btn { height: 40px; }

    @media (max-width: 1000px) {
        .op-page .op-card { grid-template-columns: 1fr; }
        .op-page .op-photo { height: 200px; min-height: 0; }
        .op-page .op-grid { grid-template-columns: 1fr; }
        .op-page .op-kv { border-right: 0; border-bottom: 1px solid var(--op-line); }
        .op-page .op-kv:last-child { border-bottom: 0; }
    }
    @media (max-width: 560px) { .op-page .op-search { width: 100%; } .op-page .op-top { flex-direction: column; } .op-page .op-badges { align-items: flex-start; } }
</style>

@php
    $hitung = [
        'ALL'       => $opinis->count(),
        'PENDING'   => $opinis->where('status', 'PENDING')->count(),
        'DISETUJUI' => $opinis->where('status', 'DISETUJUI')->count(),
        'DITOLAK'   => $opinis->where('status', 'DITOLAK')->count(),
    ];
@endphp

<div class="op-page">

    <div class="op-switch">
        <a href="{{ route('publikasi.index') }}">Publikasi Web Profil DJPK</a>
        <a href="{{ route('opini.index') }}" class="active">Opini Eselon II</a>
    </div>

    <div class="op-head">
        <div>
            <h2 class="op-title">{{ $isAdmin ? 'Kelola Opini Eselon II' : 'Opini Saya' }}</h2>
            <p class="op-sub">Daftar pengajuan naskah opini dari unit Eselon II.</p>
        </div>
        @unless ($isAdmin)
            <a href="{{ route('opini.create') }}" class="op-add">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                Kirim Opini Baru
            </a>
        @endunless
    </div>

    @if ($errors->any())
        <div style="margin-bottom:18px;padding:12px 16px;border-radius:12px;font-size:13px;background:rgba(255,59,48,.08);border:1px solid rgba(255,59,48,.18);color:#b42318;">
            @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
    @endif

    <div class="op-bar">
        <div class="op-tabs">
            <button type="button" class="op-tab active" data-s="">Semua<b>{{ $hitung['ALL'] }}</b></button>
            <button type="button" class="op-tab" data-s="PENDING">Menunggu<b>{{ $hitung['PENDING'] }}</b></button>
            <button type="button" class="op-tab" data-s="DISETUJUI">Disetujui<b>{{ $hitung['DISETUJUI'] }}</b></button>
            <button type="button" class="op-tab" data-s="DITOLAK">Ditolak<b>{{ $hitung['DITOLAK'] }}</b></button>
        </div>
        <span class="op-spacer"></span>
        <label class="op-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="text" id="opCari" class="op-input" placeholder="Cari judul, penulis, pemohon, unit…" autocomplete="off">
        </label>
    </div>

    <div class="op-list" id="opList">
        @foreach ($opinis as $item)
            @php
                $kar = $item->user?->karyawan;
                $pemohon = $kar?->nama ?? $item->user?->name ?? '—';
                $unit = $kar?->unit?->nama_unit;
                $subunit = $kar?->subunitOrganisasi?->nama_subunit;

                [$label, $kelas] = match ($item->status) {
                    'DISETUJUI' => ['Disetujui / Terbit', 'b-green'],
                    'DITOLAK'   => ['Ditolak', 'b-red'],
                    default     => ['Menunggu Review', 'b-amber'],
                };

                $cari = strtolower(implode(' ', [$item->judul_opini, $item->penulis, $pemohon, $unit, $subunit, $item->ringkasan]));
            @endphp

            <article class="op-card" data-status="{{ $item->status }}" data-cari="{{ $cari }}">
                @if ($item->foto_penulis)
                    <a href="{{ asset('storage/' . $item->foto_penulis) }}" target="_blank" rel="noopener" class="op-photo" title="Klik untuk memperbesar">
                        <img src="{{ asset('storage/' . $item->foto_penulis) }}" alt="{{ $item->penulis }}">
                    </a>
                @else
                    <div class="op-photo"><div class="op-photo-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                        Foto penulis tidak ada
                    </div></div>
                @endif

                <div class="op-main">
                    <div class="op-top">
                        <div>
                            <h3 class="op-name">{{ $item->judul_opini }}</h3>
                            <div class="op-by">Ditulis oleh <b>{{ $item->penulis }}</b></div>
                        </div>
                        <div class="op-badges">
                            <span class="op-badge {{ $kelas }}">{{ $label }}</span>
                            <span class="op-time">Diajukan {{ $item->created_at?->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <div class="op-grid">
                        <div class="op-kv"><div class="op-label">Pemohon</div><div class="op-value">{{ $pemohon }}</div></div>
                        <div class="op-kv"><div class="op-label">Unit / Direktorat</div><div class="op-value">{{ $unit ?? '—' }}</div></div>
                        <div class="op-kv"><div class="op-label">Subunit</div><div class="op-value">{{ $subunit ?? '—' }}</div></div>
                    </div>

                    <div class="op-block">
                        <div class="op-label">Ringkasan / Sinopsis</div>
                        {{ $item->ringkasan }}
                    </div>

                    @if ($item->status === 'DITOLAK')
                        <div class="op-block op-reject">
                            <div class="op-label">Alasan Penolakan</div>
                            {{ $item->alasan_penolakan ?: 'Tidak ada alasan yang dicatat.' }}
                        </div>
                    @endif

                    <div class="op-foot">
                        @if ($item->file_naskah)
                            <a href="{{ asset('storage/' . $item->file_naskah) }}" target="_blank" rel="noopener" class="op-file">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M9 13h6M9 17h6"/></svg>
                                Unduh naskah lengkap (PDF / DOCX)
                            </a>
                        @else
                            <span class="op-file none">Tidak ada file naskah</span>
                        @endif

                        @if ($isAdmin && $item->status === 'PENDING')
                            <button type="button" class="op-btn op-btn-no btn-aksi" data-mode="tolak" data-action="{{ route('opini.updateStatus', $item->id) }}" data-nama="{{ $item->judul_opini }}">Tolak</button>
                            <button type="button" class="op-btn op-btn-ok btn-aksi" data-mode="setuju" data-action="{{ route('opini.updateStatus', $item->id) }}" data-nama="{{ $item->judul_opini }}">Terbitkan</button>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach

        <div class="op-empty" id="opEmpty" style="{{ $opinis->count() ? 'display:none' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="3"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
            <strong id="opEmptyTitle">Belum ada pengajuan opini</strong>
            <span id="opEmptyText">{{ $opinis->count() ? '' : 'Belum ada data pengajuan opini Eselon II.' }}</span>
        </div>
    </div>

    @if ($isAdmin)
        <div class="op-modal" id="opModal">
            <div class="op-modal-box">
                <h3 class="op-modal-title" id="opModalTitle">—</h3>
                <p class="op-modal-text" id="opModalText">—</p>
                <form id="opModalForm" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" id="opModalStatus">
                    <div id="opAlasanWrap" style="display:none;">
                        <textarea name="alasan_penolakan" id="opAlasan" class="op-textarea" placeholder="Tulis alasan penolakan… (wajib)"></textarea>
                    </div>
                    <div class="op-modal-actions">
                        <button type="button" class="op-btn op-btn-ghost" id="opBatal">Batal</button>
                        <button type="submit" class="op-btn op-btn-ok" id="opSubmit">Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('#opList .op-card');
    const cari = document.getElementById('opCari');
    const empty = document.getElementById('opEmpty');
    const emptyTitle = document.getElementById('opEmptyTitle');
    const emptyText = document.getElementById('opEmptyText');
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
            emptyText.textContent = 'Tidak ada opini yang cocok dengan filter atau pencarian.';
        }
    }

    cari.addEventListener('input', filter);
    document.querySelectorAll('.op-tab').forEach(function (t) {
        t.addEventListener('click', function () {
            document.querySelectorAll('.op-tab').forEach(function (x) { x.classList.remove('active'); });
            t.classList.add('active');
            status = t.dataset.s;
            filter();
        });
    });

    const modal = document.getElementById('opModal');
    if (!modal) return;
    const form = document.getElementById('opModalForm');
    const MODE = {
        setuju: { judul: 'Terbitkan opini?', teks: 'Setujui dan terbitkan opini "{n}"?', status: 'DISETUJUI', tombol: 'Terbitkan', kelas: 'op-btn-ok', alasan: false },
        tolak:  { judul: 'Tolak opini?', teks: 'Tolak opini "{n}". Alasan penolakan wajib diisi.', status: 'DITOLAK', tombol: 'Tolak', kelas: 'op-btn-danger', alasan: true }
    };

    document.querySelectorAll('.btn-aksi').forEach(function (b) {
        b.addEventListener('click', function () {
            const m = MODE[b.dataset.mode];
            form.action = b.dataset.action;
            document.getElementById('opModalTitle').textContent = m.judul;
            document.getElementById('opModalText').textContent = m.teks.replace('{n}', b.dataset.nama);
            document.getElementById('opModalStatus').value = m.status;
            document.getElementById('opAlasanWrap').style.display = m.alasan ? 'block' : 'none';
            const a = document.getElementById('opAlasan'); a.required = m.alasan; a.value = '';
            const s = document.getElementById('opSubmit'); s.textContent = m.tombol; s.className = 'op-btn ' + m.kelas;
            modal.style.display = 'flex';
            if (m.alasan) a.focus();
        });
    });

    function tutup() { modal.style.display = 'none'; }
    document.getElementById('opBatal').addEventListener('click', tutup);
    modal.addEventListener('click', function (e) { if (e.target === modal) tutup(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') tutup(); });
});
</script>

@endsection