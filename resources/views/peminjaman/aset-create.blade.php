@extends('layouts.app')

@section('title', 'Ajukan Peminjaman Aset | HumasIn')
@section('page-title', 'Ajukan Peminjaman Aset')
@section('page-description', 'Isi detail peminjaman untuk aset yang dipilih')

@section('content')

<style>
    .pa-page {
        --pa-accent: #4E8BBC;
        --pa-accent-soft: rgba(78, 139, 188, .12);
        --pa-text: #1d1d1f;
        --pa-muted: #6e6e73;
        --pa-line: rgba(0, 0, 0, .07);
        --pa-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 1100px; margin: 0 auto; color: var(--pa-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .pa-page *, .pa-page *::before, .pa-page *::after { box-sizing: border-box; }

    .pa-page .pa-back { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #3b7aab; transition: opacity .15s; }
    .pa-page .pa-back:hover { opacity: .7; }
    .pa-page .pa-back svg { width: 16px; height: 16px; }

    .pa-page .pa-layout { display: grid; grid-template-columns: 340px 1fr; gap: 24px; align-items: start; margin-bottom: 32px; }

    .pa-page .pa-card { background: #fff; border: 1px solid var(--pa-line); border-radius: 20px; box-shadow: var(--pa-shadow); overflow: hidden; }

    /* Detail aset */
    .pa-page .pa-side { position: sticky; top: 96px; padding: 20px; }
    .pa-page .pa-photo, .pa-page .pa-photo-empty { width: 100%; aspect-ratio: 4 / 3; border-radius: 16px; margin-bottom: 18px; }
    .pa-page .pa-photo { object-fit: cover; display: block; border: 1px solid var(--pa-line); box-shadow: 0 4px 16px rgba(0,0,0,.08); }
    .pa-page .pa-photo-empty { display: grid; place-items: center; align-content: center; gap: 8px; background: rgba(120,120,128,.08); border: 1.5px dashed rgba(120,120,128,.3); color: #8e8e93; font-size: 12px; }
    .pa-page .pa-photo-empty svg { width: 30px; height: 30px; }
    .pa-page .pa-eyebrow { font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--pa-muted); margin-bottom: 4px; }
    .pa-page .pa-name { font-size: 20px; font-weight: 700; letter-spacing: -.02em; line-height: 1.25; margin: 0 0 12px; }
    .pa-page .pa-desc { font-size: 13px; line-height: 1.6; color: var(--pa-muted); margin: 0 0 16px; white-space: pre-line; }

    .pa-page .pa-kv { border: 1px solid var(--pa-line); border-radius: 14px; overflow: hidden; }
    .pa-page .pa-kv-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 11px 14px; font-size: 13px; }
    .pa-page .pa-kv-row + .pa-kv-row { border-top: 1px solid var(--pa-line); }
    .pa-page .pa-kv-row span { color: var(--pa-muted); }
    .pa-page .pa-kv-row b { font-weight: 600; font-variant-numeric: tabular-nums; }

    .pa-page .pa-badge { display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px 3px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; }
    .pa-page .pa-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .pa-page .pa-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .pa-page .pa-amber { background: rgba(255,159,10,.16); color: #b26a00; }
    .pa-page .pa-red   { background: rgba(255,59,48,.12); color: #c9342a; }

    /* Form */
    .pa-page .pa-head { padding: 22px 28px; border-bottom: 1px solid var(--pa-line); }
    .pa-page .pa-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 3px; }
    .pa-page .pa-sub { font-size: 13px; color: var(--pa-muted); margin: 0; }
    .pa-page .pa-body { padding: 28px; }
    .pa-page .pa-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 18px 28px; border-top: 1px solid var(--pa-line); background: #fafafc; }

    .pa-page .pa-alert { display: flex; gap: 10px; margin-bottom: 22px; padding: 12px 16px; border-radius: 12px; background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.18); color: #b42318; font-size: 13px; line-height: 1.5; }
    .pa-page .pa-alert ul { margin: 0; padding-left: 16px; }

    .pa-page .pa-section { font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--pa-muted); padding-bottom: 8px; border-bottom: 1px solid var(--pa-line); margin: 0 0 16px; }
    .pa-page .pa-section:not(:first-child) { margin-top: 26px; }
    .pa-page .pa-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px 20px; }
    .pa-page .pa-span-2 { grid-column: 1 / -1; }
    .pa-page .pa-label { display: block; font-size: 12px; font-weight: 600; color: #3a3a3c; margin-bottom: 7px; }
    .pa-page .pa-req { color: #ff3b30; margin-left: 2px; }
    .pa-page .pa-hint { display: block; font-size: 11px; color: var(--pa-muted); margin-top: 6px; }

    .pa-page .pa-control {
        width: 100%; height: 40px; padding: 0 14px; border-radius: 10px; border: 1px solid var(--pa-line);
        background: rgba(120,120,128,.08); color: var(--pa-text); font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .pa-page .pa-control::placeholder { color: #8e8e93; }
    .pa-page .pa-control:focus { background: #fff; border-color: var(--pa-accent); box-shadow: 0 0 0 4px var(--pa-accent-soft); }
    .pa-page textarea.pa-control { height: auto; min-height: 96px; padding: 12px 14px; line-height: 1.5; resize: vertical; }

    /* Syarat */
    .pa-page .pa-terms { border: 1px solid var(--pa-line); border-radius: 14px; background: #fafafc; padding: 16px; }
    .pa-page .pa-terms-text { max-height: 120px; overflow-y: auto; font-size: 12.5px; line-height: 1.65; color: #4a4a4f; margin-bottom: 14px; padding-right: 6px; }
    .pa-page .pa-terms-text strong { color: var(--pa-text); }
    .pa-page .pa-check { display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 500; line-height: 1.5; }
    .pa-page .pa-check input { margin-top: 3px; width: 16px; height: 16px; accent-color: #4E8BBC; flex-shrink: 0; }

    .pa-page .pa-btn, .pa-page .pa-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 40px; padding: 0 22px; border-radius: 10px; border: 0;
        font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s;
    }
    .pa-page .pa-btn { background: var(--pa-accent); color: #fff; }
    .pa-page .pa-btn:hover { filter: brightness(1.06); }
    .pa-page .pa-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .pa-page .pa-btn-ghost:hover { background: rgba(120,120,128,.18); }

    @media (max-width: 900px) {
        .pa-page .pa-layout { grid-template-columns: 1fr; }
        .pa-page .pa-side { position: static; }
    }
    @media (max-width: 600px) { .pa-page .pa-grid { grid-template-columns: 1fr; } }
</style>

@php
    $kondisiLabel = match ($aset->kondisi) {
        'RUSAK_RINGAN' => ['Rusak Ringan', 'pa-amber'],
        'RUSAK_BERAT'  => ['Rusak Berat', 'pa-red'],
        default        => ['Baik', 'pa-green'],
    };
    $statusLabel = match ($aset->status) {
        'PERBAIKAN'      => ['Perbaikan', 'pa-amber'],
        'TIDAK_TERSEDIA' => ['Tidak Tersedia', 'pa-red'],
        default          => ['Tersedia', 'pa-green'],
    };
@endphp

<div class="pa-page">

   

    <div class="pa-layout">

        {{-- DETAIL ASET --}}
        <aside class="pa-card pa-side">
            @if ($aset->foto)
                <img src="{{ asset('storage/' . $aset->foto) }}" alt="{{ $aset->nama_aset }}" class="pa-photo">
            @else
                <div class="pa-photo-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    Tidak ada foto
                </div>
            @endif

            <div class="pa-eyebrow">Aset yang dipinjam</div>
            <h2 class="pa-name">{{ $aset->nama_aset }}</h2>
            <p class="pa-desc">{{ $aset->deskripsi ?: 'Tidak ada deskripsi.' }}</p>

            <div class="pa-kv">
                <div class="pa-kv-row"><span>Stok</span><b>{{ $aset->jumlah }} unit</b></div>
                <div class="pa-kv-row"><span>Kondisi</span><span class="pa-badge {{ $kondisiLabel[1] }}">{{ $kondisiLabel[0] }}</span></div>
                <div class="pa-kv-row"><span>Status</span><span class="pa-badge {{ $statusLabel[1] }}">{{ $statusLabel[0] }}</span></div>
            </div>
        </aside>

        {{-- FORM --}}
        <form action="{{ route('peminjaman.aset.store', $aset->id) }}" method="POST" class="pa-card">
            @csrf

            <div class="pa-head">
                <h3 class="pa-title">Form Pengajuan Peminjaman</h3>
                <p class="pa-sub">Silakan isi detail peminjaman untuk aset {{ $aset->nama_aset }}.</p>
            </div>

            <div class="pa-body">

                @if ($errors->any())
                    <div class="pa-alert">
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <div class="pa-section">Jadwal</div>
                <div class="pa-grid">
                    <div>
                        <label class="pa-label">Tanggal Mulai<span class="pa-req">*</span></label>
                        <input type="date" name="tanggal_mulai" class="pa-control" value="{{ old('tanggal_mulai') }}" min="{{ now()->toDateString() }}" required>
                    </div>
                    <div>
                        <label class="pa-label">Jam Mulai<span class="pa-req">*</span></label>
                        <input type="time" name="jam_mulai" class="pa-control" value="{{ old('jam_mulai') }}" required>
                    </div>
                    <div>
                        <label class="pa-label">Tanggal Pengembalian<span class="pa-req">*</span></label>
                        <input type="date" name="tanggal_selesai" class="pa-control" value="{{ old('tanggal_selesai') }}" min="{{ now()->toDateString() }}" required>
                    </div>
                    <div>
                        <label class="pa-label">Jam Pengembalian<span class="pa-req">*</span></label>
                        <input type="time" name="jam_selesai" class="pa-control" value="{{ old('jam_selesai') }}" required>
                    </div>
                </div>

                <div class="pa-section">Detail Peminjaman</div>
                <div class="pa-grid">
                    <div>
                        <label class="pa-label">Jumlah yang Dipinjam<span class="pa-req">*</span></label>
                        <input type="number" name="jumlah_pinjam" class="pa-control" value="{{ old('jumlah_pinjam', 1) }}" min="1" max="{{ $aset->jumlah }}" required>
                        <small class="pa-hint">Maksimal {{ $aset->jumlah }} unit.</small>
                    </div>
                    <div>
                        <label class="pa-label">Nomor HP / WhatsApp<span class="pa-req">*</span></label>
                        <input type="text" name="no_hp" class="pa-control" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" required>
                    </div>
                    <div class="pa-span-2">
                        <label class="pa-label">Keperluan Peminjaman<span class="pa-req">*</span></label>
                        <textarea name="keperluan" rows="3" class="pa-control" maxlength="255" placeholder="Contoh: Keperluan liputan acara Humas DJPK…" required>{{ old('keperluan') }}</textarea>
                    </div>
                </div>

                <div class="pa-section">Syarat & Ketentuan</div>
                <div class="pa-terms">
                    <div class="pa-terms-text">
                        <strong>Syarat & Ketentuan Peminjaman Aset:</strong><br>
                        1. Peminjam bertanggung jawab penuh atas keutuhan dan keamanan aset selama masa peminjaman.<br>
                        2. Aset wajib dikembalikan tepat waktu sesuai jadwal yang telah disetujui.<br>
                        3. Segala bentuk kerusakan atau kehilangan aset menjadi tanggung jawab pihak peminjam.<br>
                        4. Penggunaan aset harus sesuai dengan keperluan resmi yang diajukan.
                    </div>
                    <label class="pa-check">
                        <input type="checkbox" name="syarat_ketentuan" value="1" required>
                        <span>Saya telah membaca dan menyetujui Syarat & Ketentuan Peminjaman Aset yang berlaku.</span>
                    </label>
                </div>
            </div>

            <div class="pa-foot">
                <a href="{{ route('peminjaman.aset') }}" class="pa-btn-ghost">Batal</a>
                <button type="submit" class="pa-btn">Kirim Pengajuan</button>
            </div>
        </form>

    </div>
</div>

@endsection