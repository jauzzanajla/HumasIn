@extends('layouts.app')

@section('title', 'Ajukan Peminjaman Ruangan | HumasIn')
@section('page-title', 'Ajukan Peminjaman Ruangan')
@section('page-description', 'Isi detail peminjaman untuk ruangan yang dipilih')

@section('content')

<style>
    .pr-page {
        --pr-accent: #4E8BBC;
        --pr-accent-soft: rgba(78, 139, 188, .12);
        --pr-text: #1d1d1f;
        --pr-muted: #6e6e73;
        --pr-line: rgba(0, 0, 0, .07);
        --pr-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 1100px; margin: 0 auto; color: var(--pr-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .pr-page *, .pr-page *::before, .pr-page *::after { box-sizing: border-box; }

    .pr-page .pr-back { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #3b7aab; transition: opacity .15s; }
    .pr-page .pr-back:hover { opacity: .7; }
    .pr-page .pr-back svg { width: 16px; height: 16px; }

    .pr-page .pr-layout { display: grid; grid-template-columns: 340px 1fr; gap: 24px; align-items: start; margin-bottom: 32px; }
    .pr-page .pr-card { background: #fff; border: 1px solid var(--pr-line); border-radius: 20px; box-shadow: var(--pr-shadow); overflow: hidden; }

    /* Detail ruangan */
    .pr-page .pr-side { position: sticky; top: 96px; padding: 20px; }
    .pr-page .pr-photo, .pr-page .pr-photo-empty { width: 100%; aspect-ratio: 4 / 3; border-radius: 16px; margin-bottom: 18px; }
    .pr-page .pr-photo { object-fit: cover; display: block; border: 1px solid var(--pr-line); box-shadow: 0 4px 16px rgba(0,0,0,.08); }
    .pr-page .pr-photo-empty { display: grid; place-items: center; align-content: center; gap: 8px; background: rgba(120,120,128,.08); border: 1.5px dashed rgba(120,120,128,.3); color: #8e8e93; font-size: 12px; }
    .pr-page .pr-photo-empty svg { width: 30px; height: 30px; }
    .pr-page .pr-eyebrow { font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--pr-muted); margin-bottom: 4px; }
    .pr-page .pr-name { font-size: 20px; font-weight: 700; letter-spacing: -.02em; line-height: 1.25; margin: 0 0 8px; }
    .pr-page .pr-loc { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: var(--pr-muted); margin-bottom: 12px; }
    .pr-page .pr-loc svg { width: 14px; height: 14px; flex-shrink: 0; color: #8e8e93; }
    .pr-page .pr-desc { font-size: 13px; line-height: 1.6; color: var(--pr-muted); margin: 0 0 16px; white-space: pre-line; }

    .pr-page .pr-kv { border: 1px solid var(--pr-line); border-radius: 14px; overflow: hidden; }
    .pr-page .pr-kv-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 11px 14px; font-size: 13px; }
    .pr-page .pr-kv-row + .pr-kv-row { border-top: 1px solid var(--pr-line); }
    .pr-page .pr-kv-row span { color: var(--pr-muted); }
    .pr-page .pr-kv-row b { font-weight: 600; font-variant-numeric: tabular-nums; }

    .pr-page .pr-badge { display: inline-flex; align-items: center; gap: 6px; padding: 3px 10px 3px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; }
    .pr-page .pr-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .pr-page .pr-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .pr-page .pr-red   { background: rgba(255,59,48,.12); color: #c9342a; }

    /* Form */
    .pr-page .pr-head { padding: 22px 28px; border-bottom: 1px solid var(--pr-line); }
    .pr-page .pr-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 3px; }
    .pr-page .pr-sub { font-size: 13px; color: var(--pr-muted); margin: 0; }
    .pr-page .pr-body { padding: 28px; }
    .pr-page .pr-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 18px 28px; border-top: 1px solid var(--pr-line); background: #fafafc; }

    .pr-page .pr-alert { margin-bottom: 22px; padding: 12px 16px; border-radius: 12px; background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.18); color: #b42318; font-size: 13px; line-height: 1.5; }
    .pr-page .pr-alert ul { margin: 0; padding-left: 16px; }

    .pr-page .pr-section { font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--pr-muted); padding-bottom: 8px; border-bottom: 1px solid var(--pr-line); margin: 0 0 16px; }
    .pr-page .pr-section:not(:first-child) { margin-top: 26px; }
    .pr-page .pr-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px 20px; }
    .pr-page .pr-span-2 { grid-column: 1 / -1; }
    .pr-page .pr-label { display: block; font-size: 12px; font-weight: 600; color: #3a3a3c; margin-bottom: 7px; }
    .pr-page .pr-req { color: #ff3b30; margin-left: 2px; }
    .pr-page .pr-hint { display: block; font-size: 11px; color: var(--pr-muted); margin-top: 6px; }

    .pr-page .pr-note-box { display: flex; gap: 10px; align-items: flex-start; margin-bottom: 18px; padding: 12px 14px; border-radius: 12px; background: var(--pr-accent-soft); color: #2b5c8f; font-size: 12.5px; line-height: 1.5; }
    .pr-page .pr-note-box svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }

    .pr-page .pr-control {
        width: 100%; height: 40px; padding: 0 14px; border-radius: 10px; border: 1px solid var(--pr-line);
        background: rgba(120,120,128,.08); color: var(--pr-text); font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .pr-page .pr-control::placeholder { color: #8e8e93; }
    .pr-page .pr-control:focus { background: #fff; border-color: var(--pr-accent); box-shadow: 0 0 0 4px var(--pr-accent-soft); }
    .pr-page textarea.pr-control { height: auto; min-height: 96px; padding: 12px 14px; line-height: 1.5; resize: vertical; }

    .pr-page .pr-terms { border: 1px solid var(--pr-line); border-radius: 14px; background: #fafafc; padding: 16px; }
    .pr-page .pr-terms-text { max-height: 120px; overflow-y: auto; font-size: 12.5px; line-height: 1.65; color: #4a4a4f; margin-bottom: 14px; padding-right: 6px; }
    .pr-page .pr-terms-text strong { color: var(--pr-text); }
    .pr-page .pr-check { display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 500; line-height: 1.5; }
    .pr-page .pr-check input { margin-top: 3px; width: 16px; height: 16px; accent-color: #4E8BBC; flex-shrink: 0; }

    .pr-page .pr-btn, .pr-page .pr-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 40px; padding: 0 22px; border-radius: 10px; border: 0;
        font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s;
    }
    .pr-page .pr-btn { background: var(--pr-accent); color: #fff; }
    .pr-page .pr-btn:hover { filter: brightness(1.06); }
    .pr-page .pr-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .pr-page .pr-btn-ghost:hover { background: rgba(120,120,128,.18); }

    @media (max-width: 900px) {
        .pr-page .pr-layout { grid-template-columns: 1fr; }
        .pr-page .pr-side { position: static; }
    }
    @media (max-width: 600px) { .pr-page .pr-grid { grid-template-columns: 1fr; } }
</style>

@php
    $statusRuangan = $ruangan->status === 'TERSEDIA' ? ['Tersedia', 'pr-green'] : ['Tidak Tersedia', 'pr-red'];
    $satuan = strtolower($ruangan->satuan_durasi ?? 'jam');
@endphp

<div class="pr-page">

    

    <div class="pr-layout">

        {{-- DETAIL RUANGAN --}}
        <aside class="pr-card pr-side">
            @if ($ruangan->foto)
                <img src="{{ asset('storage/' . $ruangan->foto) }}" alt="{{ $ruangan->nama_ruangan }}" class="pr-photo">
            @else
                <div class="pr-photo-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    Tidak ada foto ruangan
                </div>
            @endif

            <div class="pr-eyebrow">Ruangan yang dipinjam</div>
            <h2 class="pr-name">{{ $ruangan->nama_ruangan }}</h2>

            <div class="pr-loc">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $ruangan->lokasi ?: 'Lokasi belum ditentukan' }}
            </div>

            <p class="pr-desc">{{ $ruangan->deskripsi ?: 'Tidak ada deskripsi.' }}</p>

            <div class="pr-kv">
                <div class="pr-kv-row"><span>Kapasitas</span><b>{{ $ruangan->kapasitas }} orang</b></div>
                <div class="pr-kv-row"><span>Kuota</span><b>{{ $ruangan->kuota }}</b></div>
                <div class="pr-kv-row"><span>Durasi maksimal</span><b>{{ $ruangan->durasi_maksimal }} {{ $satuan }}</b></div>
                <div class="pr-kv-row"><span>Status</span><span class="pr-badge {{ $statusRuangan[1] }}">{{ $statusRuangan[0] }}</span></div>
            </div>
        </aside>

        {{-- FORM --}}
        <form action="{{ route('peminjaman.ruangan.store', $ruangan->id) }}" method="POST" class="pr-card">
            @csrf

            <div class="pr-head">
                <h3 class="pr-title">Form Pengajuan Peminjaman</h3>
                <p class="pr-sub">Silakan isi detail peminjaman untuk {{ $ruangan->nama_ruangan }}.</p>
            </div>

            <div class="pr-body">

                @if ($errors->any())
                    <div class="pr-alert">
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <div class="pr-note-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <span>Durasi peminjaman maksimal {{ $ruangan->durasi_maksimal }} {{ $satuan }}. Jadwal yang bentrok dengan peminjaman lain akan ditolak sistem.</span>
                </div>

                <div class="pr-section">Jadwal</div>
                <div class="pr-grid">
                    <div>
                        <label class="pr-label">Tanggal Mulai<span class="pr-req">*</span></label>
                        <input type="date" name="tanggal_mulai" class="pr-control" value="{{ old('tanggal_mulai') }}" min="{{ now()->toDateString() }}" required>
                    </div>
                    <div>
                        <label class="pr-label">Jam Mulai<span class="pr-req">*</span></label>
                        <input type="time" name="jam_mulai" class="pr-control" value="{{ old('jam_mulai') }}" required>
                    </div>
                    <div>
                        <label class="pr-label">Tanggal Selesai<span class="pr-req">*</span></label>
                        <input type="date" name="tanggal_selesai" class="pr-control" value="{{ old('tanggal_selesai') }}" min="{{ now()->toDateString() }}" required>
                    </div>
                    <div>
                        <label class="pr-label">Jam Selesai<span class="pr-req">*</span></label>
                        <input type="time" name="jam_selesai" class="pr-control" value="{{ old('jam_selesai') }}" required>
                    </div>
                </div>

                <div class="pr-section">Detail Peminjaman</div>
                <div class="pr-grid">
                    <div class="pr-span-2">
                        <label class="pr-label">Keperluan Peminjaman<span class="pr-req">*</span></label>
                        <textarea name="keperluan" rows="3" class="pr-control" maxlength="255" placeholder="Contoh: Rapat koordinasi tim Humas DJPK…" required>{{ old('keperluan') }}</textarea>
                    </div>
                    <div>
                        <label class="pr-label">Nomor HP / WhatsApp<span class="pr-req">*</span></label>
                        <input type="text" name="no_hp" class="pr-control" value="{{ old('no_hp') }}" placeholder="Contoh: 081234567890" required>
                    </div>
                </div>

                <div class="pr-section">Syarat & Ketentuan</div>
                <div class="pr-terms">
                    <div class="pr-terms-text">
                        <strong>Syarat & Ketentuan Peminjaman Ruangan:</strong><br>
                        1. Peminjam bertanggung jawab atas kebersihan dan kerapian ruangan selama dan setelah digunakan.<br>
                        2. Ruangan wajib dikosongkan tepat waktu sesuai jadwal yang telah disetujui.<br>
                        3. Segala bentuk kerusakan fasilitas menjadi tanggung jawab pihak peminjam.<br>
                        4. Penggunaan ruangan harus sesuai dengan keperluan resmi yang diajukan.
                    </div>
                    <label class="pr-check">
                        <input type="checkbox" name="syarat_ketentuan" value="1" required>
                        <span>Saya telah membaca dan menyetujui Syarat & Ketentuan Peminjaman Ruangan yang berlaku.</span>
                    </label>
                </div>
            </div>

            <div class="pr-foot">
                <a href="{{ route('peminjaman.ruangan') }}" class="pr-btn-ghost">Batal</a>
                <button type="submit" class="pr-btn">Kirim Pengajuan</button>
            </div>
        </form>

    </div>
</div>

@endsection