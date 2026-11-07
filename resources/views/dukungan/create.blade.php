@extends('layouts.app')

@section('title', 'Ajukan Dukungan Kegiatan | HumasIn')
@section('page-title', 'Ajukan Dukungan Kegiatan')
@section('page-description', 'Lengkapi detail acara dan kebutuhan support dari Humas DJPK')

@section('content')

<style>
    .dk-page {
        --dk-accent: #4E8BBC;
        --dk-accent-soft: rgba(78, 139, 188, .12);
        --dk-text: #1d1d1f;
        --dk-muted: #6e6e73;
        --dk-line: rgba(0, 0, 0, .07);
        --dk-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 960px; margin: 0 auto; color: var(--dk-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .dk-page *, .dk-page *::before, .dk-page *::after { box-sizing: border-box; }

    .dk-page .dk-back { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #3b7aab; transition: opacity .15s; }
    .dk-page .dk-back:hover { opacity: .7; }
    .dk-page .dk-back svg { width: 16px; height: 16px; }

    .dk-page .dk-card { background: #fff; border: 1px solid var(--dk-line); border-radius: 18px; box-shadow: var(--dk-shadow); overflow: hidden; margin-bottom: 32px; }
    .dk-page .dk-head { padding: 22px 32px; border-bottom: 1px solid var(--dk-line); }
    .dk-page .dk-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 3px; }
    .dk-page .dk-sub { font-size: 13px; color: var(--dk-muted); margin: 0; }
    .dk-page .dk-body { padding: 32px; }
    .dk-page .dk-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 18px 32px; border-top: 1px solid var(--dk-line); background: #fafafc; }

    .dk-page .dk-alert { margin-bottom: 24px; padding: 12px 16px; border-radius: 12px; background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.18); color: #b42318; font-size: 13px; line-height: 1.5; }
    .dk-page .dk-alert ul { margin: 0; padding-left: 16px; }

    .dk-page .dk-section { font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--dk-muted); padding-bottom: 8px; border-bottom: 1px solid var(--dk-line); margin: 0 0 18px; }
    .dk-page .dk-section:not(:first-child) { margin-top: 30px; }

    .dk-page .dk-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px 24px; }
    .dk-page .dk-span-2 { grid-column: 1 / -1; }
    .dk-page .dk-label { display: block; font-size: 12px; font-weight: 600; color: #3a3a3c; margin-bottom: 7px; }
    .dk-page .dk-req { color: #ff3b30; margin-left: 2px; }
    .dk-page .dk-opt { color: var(--dk-muted); font-weight: 500; margin-left: 4px; }

    .dk-page .dk-control {
        width: 100%; height: 40px; padding: 0 14px; border-radius: 10px; border: 1px solid var(--dk-line);
        background: rgba(120,120,128,.08); color: var(--dk-text); font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .dk-page .dk-control::placeholder { color: #8e8e93; }
    .dk-page .dk-control:focus { background: #fff; border-color: var(--dk-accent); box-shadow: 0 0 0 4px var(--dk-accent-soft); }

    /* Kartu support */
    .dk-page .dk-support { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .dk-page .dk-opt-card { border: 1px solid var(--dk-line); border-radius: 14px; background: rgba(120,120,128,.05); transition: background .15s, border-color .15s, box-shadow .15s; }
    .dk-page .dk-opt-card:hover { background: rgba(120,120,128,.09); }
    .dk-page .dk-opt-card.on { background: #fff; border-color: var(--dk-accent); box-shadow: 0 0 0 4px var(--dk-accent-soft); }
    .dk-page .dk-opt-label { display: flex; align-items: center; gap: 12px; padding: 14px 16px; cursor: pointer; }
    .dk-page .dk-opt-label input { position: absolute; opacity: 0; pointer-events: none; }
    .dk-page .dk-opt-icon { width: 36px; height: 36px; flex-shrink: 0; display: grid; place-items: center; border-radius: 10px; background: rgba(78,139,188,.14); color: #3b7aab; }
    .dk-page .dk-opt-icon svg { width: 18px; height: 18px; }
    .dk-page .dk-opt-text { flex: 1; min-width: 0; font-size: 13px; font-weight: 600; line-height: 1.35; }
    .dk-page .dk-opt-text small { display: block; font-size: 11.5px; font-weight: 400; color: var(--dk-muted); margin-top: 2px; }
    .dk-page .dk-check { width: 20px; height: 20px; flex-shrink: 0; border-radius: 50%; border: 1.5px solid #c7c7cc; display: grid; place-items: center; color: transparent; transition: all .15s; }
    .dk-page .dk-check svg { width: 12px; height: 12px; }
    .dk-page .dk-opt-card.on .dk-check { background: var(--dk-accent); border-color: var(--dk-accent); color: #fff; }
    .dk-page .dk-opt-label input:focus-visible ~ .dk-check { box-shadow: 0 0 0 4px var(--dk-accent-soft); }
    .dk-page .dk-detail { display: none; padding: 0 16px 16px; }
    .dk-page .dk-opt-card.on .dk-detail { display: block; }

    .dk-page .dk-btn, .dk-page .dk-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 40px; padding: 0 22px; border-radius: 10px; border: 0;
        font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s;
    }
    .dk-page .dk-btn { background: var(--dk-accent); color: #fff; }
    .dk-page .dk-btn:hover { filter: brightness(1.06); }
    .dk-page .dk-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .dk-page .dk-btn-ghost:hover { background: rgba(120,120,128,.18); }

    @media (max-width: 800px) {
        .dk-page .dk-grid, .dk-page .dk-support { grid-template-columns: 1fr; }
        .dk-page .dk-head, .dk-page .dk-body, .dk-page .dk-foot { padding-left: 20px; padding-right: 20px; }
    }
</style>

@php
    $ico = [
        'operator' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg>',
        'desain'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3a9 9 0 1 0 0 18c1.1 0 1.5-.8 1.2-1.6-.4-1 .2-2.4 1.6-2.4H17a4 4 0 0 0 4-4c0-5-4-10-9-10z"/><circle cx="7.5" cy="11" r="1"/><circle cx="12" cy="7.5" r="1"/><circle cx="16.5" cy="11" r="1"/></svg>',
        'editing'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m10 9 5 3-5 3z"/></svg>',
        'web'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/></svg>',
        'merch'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v9H4v-9M2 7h20v5H2zM12 22V7M12 7H7.5a2.5 2.5 0 1 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 1 0 0-5C13 2 12 7 12 7z"/></svg>',
        'dok'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h3l2-3h6l2 3h3v11H4z"/><circle cx="12" cy="13" r="3.5"/></svg>',
        'tim'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0M16 4.5a3.5 3.5 0 0 1 0 7M18 14.5a6.5 6.5 0 0 1 3.5 5.5"/></svg>',
        'tick'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>',
    ];
@endphp

<div class="dk-page">

   
    <form action="{{ route('dukungan.store') }}" method="POST" class="dk-card">
        @csrf

        <div class="dk-head">
            <h3 class="dk-title">Form Pengajuan Dukungan Kegiatan / Event</h3>
            <p class="dk-sub">Silakan lengkapi detail acara dan kebutuhan support yang diperlukan dari pihak Humas DJPK.</p>
        </div>

        <div class="dk-body">

            @if ($errors->any())
                <div class="dk-alert">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- INFORMASI EVENT --}}
            <div class="dk-section">Informasi Event</div>
            <div class="dk-grid">
                <div class="dk-span-2">
                    <label class="dk-label">Nama Event<span class="dk-req">*</span></label>
                    <input type="text" name="nama_event" class="dk-control" value="{{ old('nama_event') }}" placeholder="Contoh: Rapat Koordinasi Nasional DJPK" required>
                </div>
                <div>
                    <label class="dk-label">Tanggal Pelaksanaan<span class="dk-req">*</span></label>
                    <input type="date" name="tanggal_pelaksanaan" id="tanggal_pelaksanaan" class="dk-control" value="{{ old('tanggal_pelaksanaan') }}" onchange="autoFillDateInfo(this.value)" required>
                </div>
                <div>
                    <label class="dk-label">Jam Pelaksanaan<span class="dk-req">*</span></label>
                    <input type="time" name="jam_pelaksanaan" class="dk-control" value="{{ old('jam_pelaksanaan') }}" required>
                </div>
                <div class="dk-span-2">
                    <label class="dk-label">Lokasi<span class="dk-req">*</span></label>
                    <input type="text" name="lokasi" class="dk-control" value="{{ old('lokasi') }}" placeholder="Contoh: Aula Gedung A Lt. 2 / Hotel Borobudur" required>
                </div>
            </div>

            {{-- Field otomatis (hari, bulan, tahun) --}}
            <input type="hidden" name="hari" id="input_hari">
            <input type="hidden" name="bulan" id="input_bulan">
            <input type="hidden" name="tahun" id="input_tahun">

            {{-- DETAIL ACARA --}}
            <div class="dk-section">Detail Acara</div>
            <div class="dk-grid">
                <div>
                    <label class="dk-label">Pejabat yang Hadir<span class="dk-opt">Opsional</span></label>
                    <input type="text" name="pejabat_hadir" class="dk-control" value="{{ old('pejabat_hadir') }}" placeholder="Contoh: Pak Dirjen / Sesditjen">
                </div>
                <div>
                    <label class="dk-label">Jumlah Peserta<span class="dk-opt">Opsional</span></label>
                    <input type="number" name="jumlah_peserta" class="dk-control" value="{{ old('jumlah_peserta') }}" min="0" placeholder="Contoh: 50">
                </div>
            </div>

            {{-- SUPPORT --}}
            <div class="dk-section">Support yang Dibutuhkan</div>
            <div class="dk-support">

                <div class="dk-opt-card {{ old('support_operator') ? 'on' : '' }}">
                    <label class="dk-opt-label">
                        <input type="checkbox" name="support_operator" value="1" {{ old('support_operator') ? 'checked' : '' }}>
                        <span class="dk-opt-icon">{!! $ico['operator'] !!}</span>
                        <span class="dk-opt-text">Operator</span>
                        <span class="dk-check">{!! $ico['tick'] !!}</span>
                    </label>
                </div>

                <div class="dk-opt-card {{ old('support_desain_grafis') ? 'on' : '' }}">
                    <label class="dk-opt-label">
                        <input type="checkbox" name="support_desain_grafis" value="1" {{ old('support_desain_grafis') ? 'checked' : '' }}>
                        <span class="dk-opt-icon">{!! $ico['desain'] !!}</span>
                        <span class="dk-opt-text">Desain Grafis</span>
                        <span class="dk-check">{!! $ico['tick'] !!}</span>
                    </label>
                    <div class="dk-detail">
                        <input type="text" name="detail_desain_grafis" class="dk-control" value="{{ old('detail_desain_grafis') }}" placeholder="Logo, simbol, sertifikat, poster, flyer, feed Instagram, dll…">
                    </div>
                </div>

                <div class="dk-opt-card {{ old('support_editing') ? 'on' : '' }}">
                    <label class="dk-opt-label">
                        <input type="checkbox" name="support_editing" value="1" {{ old('support_editing') ? 'checked' : '' }}>
                        <span class="dk-opt-icon">{!! $ico['editing'] !!}</span>
                        <span class="dk-opt-text">Jasa Editing Video / Foto</span>
                        <span class="dk-check">{!! $ico['tick'] !!}</span>
                    </label>
                    <div class="dk-detail">
                        <input type="text" name="detail_editing_platform" class="dk-control" value="{{ old('detail_editing_platform') }}" placeholder="Platform publikasi (YouTube, X, Facebook, TikTok, Instagram)…">
                    </div>
                </div>

                <div class="dk-opt-card {{ old('support_konten_web') ? 'on' : '' }}">
                    <label class="dk-opt-label">
                        <input type="checkbox" name="support_konten_web" value="1" {{ old('support_konten_web') ? 'checked' : '' }}>
                        <span class="dk-opt-icon">{!! $ico['web'] !!}</span>
                        <span class="dk-opt-text">Konten Publikasi<small>Khusus Web Profil DJPK</small></span>
                        <span class="dk-check">{!! $ico['tick'] !!}</span>
                    </label>
                </div>

                <div class="dk-opt-card {{ old('support_merchandise') ? 'on' : '' }}">
                    <label class="dk-opt-label">
                        <input type="checkbox" name="support_merchandise" value="1" {{ old('support_merchandise') ? 'checked' : '' }}>
                        <span class="dk-opt-icon">{!! $ico['merch'] !!}</span>
                        <span class="dk-opt-text">Penyediaan Merchandise</span>
                        <span class="dk-check">{!! $ico['tick'] !!}</span>
                    </label>
                    <div class="dk-detail">
                        <input type="text" name="detail_merchandise" class="dk-control" value="{{ old('detail_merchandise') }}" placeholder="Sebutkan jenis merchandise-nya…">
                    </div>
                </div>

                <div class="dk-opt-card {{ old('support_dokumentasi') ? 'on' : '' }}">
                    <label class="dk-opt-label">
                        <input type="checkbox" name="support_dokumentasi" value="1" {{ old('support_dokumentasi') ? 'checked' : '' }}>
                        <span class="dk-opt-icon">{!! $ico['dok'] !!}</span>
                        <span class="dk-opt-text">Dokumentasi<small>Foto dan video onsite saat acara</small></span>
                        <span class="dk-check">{!! $ico['tick'] !!}</span>
                    </label>
                </div>

                <div class="dk-opt-card {{ old('support_tim_acara') ? 'on' : '' }}">
                    <label class="dk-opt-label">
                        <input type="checkbox" name="support_tim_acara" value="1" {{ old('support_tim_acara') ? 'checked' : '' }}>
                        <span class="dk-opt-icon">{!! $ico['tim'] !!}</span>
                        <span class="dk-opt-text">Tim Acara</span>
                        <span class="dk-check">{!! $ico['tick'] !!}</span>
                    </label>
                </div>

            </div>
        </div>

        <div class="dk-foot">
            <a href="{{ route('dukungan.index') }}" class="dk-btn-ghost">Batal</a>
            <button type="submit" class="dk-btn">Kirim Pengajuan Dukungan</button>
        </div>
    </form>

</div>

<script>
    function autoFillDateInfo(dateString) {
        if (!dateString) return;

        // Pakai komponen tanggal langsung supaya tidak bergeser karena zona waktu
        const p = dateString.split('-');
        const date = new Date(+p[0], +p[1] - 1, +p[2]);
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        document.getElementById('input_hari').value = days[date.getDay()];
        document.getElementById('input_bulan').value = months[date.getMonth()];
        document.getElementById('input_tahun').value = date.getFullYear();
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Isi hari/bulan/tahun saat halaman dimuat ulang (mis. validasi gagal)
        const tgl = document.getElementById('tanggal_pelaksanaan');
        if (tgl && tgl.value) autoFillDateInfo(tgl.value);

        // Kartu support: tandai aktif dan tampilkan kolom detail saat dicentang
        document.querySelectorAll('.dk-opt-card').forEach(function (card) {
            const cb = card.querySelector('input[type=checkbox]');
            cb.addEventListener('change', function () {
                card.classList.toggle('on', cb.checked);
            });
        });
    });
</script>

@endsection