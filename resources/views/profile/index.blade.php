@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-description', 'Informasi akun dan data diri Anda')

@section('content')

@php
    $karyawan = $user->karyawan;

    $namaUser = $karyawan->nama ?? $user->name ?? 'U';
    $inisial = mb_strtoupper(mb_substr(trim($namaUser), 0, 1));
    $jabatan = $karyawan->jabatan ?? null;
    $peran = $user->peran->nama_peran ?? 'User';
    $punyaFoto = (bool) $user->foto_profil;
@endphp

<style>
    .tp-page {
        --tp-accent: #4E8BBC;
        --tp-accent-soft: rgba(78, 139, 188, .12);
        --tp-text: #1d1d1f;
        --tp-muted: #6e6e73;
        --tp-line: rgba(0, 0, 0, .07);
        --tp-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 960px; margin: 0 auto; color: var(--tp-text);
        display: flex; flex-direction: column; gap: 16px;
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .tp-page *, .tp-page *::before, .tp-page *::after { box-sizing: border-box; }
    .tp-page button:focus-visible,
    .tp-page a:focus-visible { outline: 2px solid var(--tp-accent); outline-offset: 2px; }

    /* Alert */
    .tp-page .tp-alert {
        display: flex; gap: 10px; align-items: flex-start; padding: 12px 16px; border-radius: 12px;
        background: rgba(52,199,89,.1); border: 1px solid rgba(52,199,89,.22); color: #1f7a38; font-size: 13px; line-height: 1.5;
    }
    .tp-page .tp-alert.error { background: rgba(255,59,48,.08); border-color: rgba(255,59,48,.22); color: #c9342a; }
    .tp-page .tp-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px; }

    /* Kartu */
    .tp-page .tp-card { background: #fff; border: 1px solid var(--tp-line); border-radius: 18px; box-shadow: var(--tp-shadow); padding: 20px; }
    .tp-page .tp-card-head { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .tp-page .tp-card-icon { width: 36px; height: 36px; flex-shrink: 0; display: grid; place-items: center; border-radius: 10px; background: rgba(78,139,188,.14); color: #3b7aab; }
    .tp-page .tp-card-icon svg { width: 18px; height: 18px; }
    .tp-page .tp-card-title { font-size: 14px; font-weight: 700; margin: 0; }
    .tp-page .tp-card-desc { font-size: 12px; color: var(--tp-muted); margin: 2px 0 0; }

    /* Tombol */
    .tp-page .tp-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 40px; padding: 0 18px; border-radius: 10px; border: 0;
        background: var(--tp-accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: filter .15s;
    }
    .tp-page .tp-btn:hover { filter: brightness(1.06); }
    .tp-page .tp-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .tp-page .tp-btn-ghost:hover { background: rgba(120,120,128,.18); filter: none; }
    .tp-page .tp-btn-danger { background: #c9342a; }
    .tp-page .tp-btn:disabled { opacity: .7; cursor: wait; }

    .tp-page .tp-act {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 34px; padding: 0 14px; border-radius: 9px; border: 0;
        font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s; white-space: nowrap;
    }
    .tp-page .tp-act svg { width: 14px; height: 14px; }
    .tp-page .tp-act-main { background: var(--tp-accent); color: #fff; }
    .tp-page .tp-act-main:hover { filter: brightness(1.06); }
    .tp-page .tp-act-main:disabled { opacity: .7; cursor: wait; }
    .tp-page .tp-act-danger { background: rgba(255,59,48,.1); color: #c9342a; }
    .tp-page .tp-act-danger:hover { background: rgba(255,59,48,.16); }
    .tp-page .tp-act-danger:disabled { opacity: .45; cursor: not-allowed; background: rgba(255,59,48,.1); }

    /* Hero */
    .tp-page .pf-hero { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; flex-wrap: wrap; padding: 24px; }
    .tp-page .pf-hero form { flex: 1; min-width: 300px; margin: 0; }
    .tp-page .pf-hero-main { display: flex; align-items: flex-start; gap: 22px; }
    .tp-page .pf-avatar-img,
    .tp-page .pf-avatar-fallback { width: 96px; height: 96px; border-radius: 50%; flex-shrink: 0; border: 1px solid var(--tp-line); box-shadow: var(--tp-shadow); }
    .tp-page .pf-avatar-img { object-fit: cover; display: block; }
    .tp-page .pf-avatar-fallback { display: grid; place-items: center; background: var(--tp-accent-soft); color: #3b7aab; font-size: 34px; font-weight: 700; }
    .tp-page .pf-identity { min-width: 0; }
    .tp-page .pf-name { font-size: 20px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 2px; word-break: break-word; }
    .tp-page .pf-sub { font-size: 13px; color: var(--tp-muted); margin: 0 0 10px; }
    .tp-page .pf-pills { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 14px; }
    .tp-page .pf-pill { display: inline-flex; align-items: center; padding: 3px 11px; border-radius: 999px; font-size: 11px; font-weight: 600; }
    .tp-page .pf-pill-accent { background: var(--tp-accent-soft); color: #3b7aab; }
    .tp-page .pf-pill-ok { background: rgba(52,199,89,.14); color: #1f7a38; }
    .tp-page .pf-pill-off { background: rgba(255,59,48,.12); color: #c9342a; }
    .tp-page .pf-photo-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .tp-page .pf-note { margin-top: 10px; font-size: 12px; color: var(--tp-muted); }
    .tp-page .pf-error { margin-top: 8px; font-size: 12px; font-weight: 600; color: #c9342a; }
    .tp-page .pf-error[hidden] { display: none; }
    .tp-page .pf-hero-actions { display: flex; gap: 8px; flex-wrap: wrap; }

    /* Info */
    .tp-page .pf-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: start; }
    .tp-page .pf-list { margin: 0; }
    .tp-page .pf-row { display: grid; grid-template-columns: 130px 1fr; gap: 12px; padding: 13px 0; border-bottom: 1px solid var(--tp-line); }
    .tp-page .pf-row:last-child { border-bottom: none; padding-bottom: 0; }
    .tp-page .pf-row dt { font-size: 12px; font-weight: 600; color: var(--tp-muted); }
    .tp-page .pf-row dd { margin: 0; font-size: 13px; font-weight: 600; word-break: break-word; }

    /* Modal konfirmasi hapus */
    .tp-page .custom-modal-backdrop {
        position: fixed; inset: 0; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 20px;
        background: rgba(0,0,0,.45); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
        opacity: 0; visibility: hidden; transition: opacity .2s ease, visibility .2s ease;
    }
    .tp-page .custom-modal-backdrop.show { opacity: 1; visibility: visible; }
    .tp-page .custom-modal {
        width: 100%; max-width: 400px; padding: 24px; background: #fff; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,.25);
        transform: translateY(10px); transition: transform .2s ease;
    }
    .tp-page .custom-modal-backdrop.show .custom-modal { transform: translateY(0); }
    .tp-page .custom-modal-title { font-size: 16px; font-weight: 700; letter-spacing: -.01em; margin-bottom: 8px; }
    .tp-page .custom-modal-text { font-size: 13px; color: var(--tp-muted); line-height: 1.6; margin-bottom: 20px; }
    .tp-page .custom-modal-actions { display: flex; justify-content: flex-end; gap: 8px; }

    @media (max-width: 800px) {
        .tp-page .pf-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 560px) {
        .tp-page .pf-hero-main { flex-direction: column; }
        .tp-page .pf-row { grid-template-columns: 1fr; gap: 2px; }
        .tp-page .pf-hero-actions { width: 100%; }
        .tp-page .pf-hero-actions .tp-btn { flex: 1; }
    }
    @media (prefers-reduced-motion: reduce) {
        .tp-page .custom-modal-backdrop, .tp-page .custom-modal { transition: none; }
    }
</style>

<div class="tp-page">

   

    {{-- =========================================
         HERO: FOTO, IDENTITAS, AKSI
    ========================================= --}}
    <div class="tp-card pf-hero">

        <form id="photoForm" action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="file" name="foto_profil" id="foto_profil"
                   accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" hidden>

            <div class="pf-hero-main">

                @if ($punyaFoto)
                    <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto profil {{ $namaUser }}" class="pf-avatar-img">
                @else
                    <div class="pf-avatar-fallback" aria-hidden="true">{{ $inisial }}</div>
                @endif

                <div class="pf-identity">
                    <h2 class="pf-name">{{ $namaUser }}</h2>
                    @if ($jabatan)
                        <p class="pf-sub">{{ $jabatan }}</p>
                    @endif

                    <div class="pf-pills">
                        <span class="pf-pill pf-pill-accent">{{ $peran }}</span>
                        @if ($karyawan)
                            <span class="pf-pill {{ $karyawan->aktif ? 'pf-pill-ok' : 'pf-pill-off' }}">
                                {{ $karyawan->aktif ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        @endif
                    </div>

                    <div class="pf-photo-actions">
                        <button type="button" id="btnUnggah" class="tp-act tp-act-main">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 16V4M7 9l5-5 5 5"/><path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/></svg>
                            <span id="btnUnggahLabel">Unggah Foto</span>
                        </button>

                        {{-- Selalu tampil; nonaktif jika belum ada foto --}}
                        <button type="button" id="btnBukaModalHapus" class="tp-act tp-act-danger" {{ $punyaFoto ? '' : 'disabled' }}>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v5M14 11v5"/></svg>
                            Hapus
                        </button>
                    </div>

                    <div class="pf-note">Format JPG, JPEG, PNG, atau WEBP, ukuran maksimal 2 MB.</div>
                    <div class="pf-error" id="fotoClientError" role="alert" hidden></div>

                    @error('foto_profil')
                        <div class="pf-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </form>

        <div class="pf-hero-actions">
            <a href="{{ route('profile.edit') }}" class="tp-btn">Edit Data Diri</a>
            <a href="{{ route('profile.password') }}" class="tp-btn tp-btn-ghost">Ganti Password</a>
        </div>

        <form id="formHapusFoto" action="{{ route('profile.photo.destroy') }}" method="POST" hidden>
            @csrf
            @method('DELETE')
        </form>

    </div>


    {{-- =========================================
         INFORMASI AKUN
    ========================================= --}}
    <div class="pf-grid">

        {{-- Data pribadi --}}
        <div class="tp-card">
            <div class="tp-card-head">
                <div class="tp-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                </div>
                <div>
                    <h4 class="tp-card-title">Data Pribadi</h4>
                    <p class="tp-card-desc">Identitas dan kontak Anda.</p>
                </div>
            </div>

            <dl class="pf-list">
                <div class="pf-row">
                    <dt>NIP</dt>
                    <dd>{{ $karyawan->nip ?? '-' }}</dd>
                </div>
                <div class="pf-row">
                    <dt>Nama Lengkap</dt>
                    <dd>{{ $karyawan->nama ?? $user->name ?? '-' }}</dd>
                </div>
                <div class="pf-row">
                    <dt>Email</dt>
                    <dd>{{ $karyawan->email ?? $user->email ?? '-' }}</dd>
                </div>
                <div class="pf-row">
                    <dt>Jabatan</dt>
                    <dd>{{ $jabatan ?: '-' }}</dd>
                </div>
            </dl>
        </div>

        {{-- Organisasi & akun --}}
        <div class="tp-card">
            <div class="tp-card-head">
                <div class="tp-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V5a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v16"/><path d="M14 10h5a1 1 0 0 1 1 1v10"/><path d="M8 8h2M8 12h2M8 16h2M3 21h18"/></svg>
                </div>
                <div>
                    <h4 class="tp-card-title">Organisasi & Akun</h4>
                    <p class="tp-card-desc">Unit kerja dan hak akses sistem.</p>
                </div>
            </div>

            <dl class="pf-list">
                <div class="pf-row">
                    <dt>Unit / Eselon II</dt>
                    <dd>
                        @if ($karyawan && $karyawan->unit)
                            {{ $karyawan->unit->kode_unit }} &mdash; {{ $karyawan->unit->nama_unit }}
                        @else
                            -
                        @endif
                    </dd>
                </div>
                <div class="pf-row">
                    <dt>Subunit</dt>
                    <dd>{{ $karyawan->subunitOrganisasi->nama_subunit ?? '-' }}</dd>
                </div>
                <div class="pf-row">
                    <dt>Peran Sistem</dt>
                    <dd><span class="pf-pill pf-pill-accent">{{ $peran }}</span></dd>
                </div>
                <div class="pf-row">
                    <dt>Status Akun</dt>
                    <dd>
                        @if ($karyawan)
                            <span class="pf-pill {{ $karyawan->aktif ? 'pf-pill-ok' : 'pf-pill-off' }}">
                                {{ $karyawan->aktif ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        @else
                            -
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

    </div>


    {{-- =========================================
         MODAL KONFIRMASI HAPUS FOTO PROFIL
    ========================================= --}}
    <div id="modalHapusFoto" class="custom-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="modalHapusJudul">
        <div class="custom-modal">
            <div class="custom-modal-title" id="modalHapusJudul">Hapus foto profil?</div>
            <div class="custom-modal-text">
                Foto profil akan dihapus dan tampilan kembali ke inisial nama. Tindakan ini tidak bisa dibatalkan.
            </div>
            <div class="custom-modal-actions">
                <button type="button" id="btnBatalHapus" class="tp-btn tp-btn-ghost">Batal</button>
                <button type="button" id="btnKonfirmasiHapus" class="tp-btn tp-btn-danger">Ya, Hapus</button>
            </div>
        </div>
    </div>

</div>

<script>
(function () {
    /* ---------- Unggah foto ---------- */
    const input = document.getElementById('foto_profil');
    const tombol = document.getElementById('btnUnggah');
    const label = document.getElementById('btnUnggahLabel');
    const form = document.getElementById('photoForm');
    const errBox = document.getElementById('fotoClientError');

    function tampilkanError(pesan) {
        errBox.textContent = pesan;
        errBox.hidden = false;
        input.value = '';
    }

    if (input && tombol) {
        tombol.addEventListener('click', function () { input.click(); });

        input.addEventListener('change', function () {
            const file = input.files[0];
            errBox.hidden = true;
            if (!file) return;

            const diizinkan = ['image/jpeg', 'image/png', 'image/webp'];

            if (!diizinkan.includes(file.type)) {
                tampilkanError('Format foto harus JPG, JPEG, PNG, atau WEBP.');
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                tampilkanError('Ukuran foto maksimal 2 MB.');
                return;
            }

            tombol.disabled = true;
            label.textContent = 'Mengunggah...';
            form.submit();
        });
    }

    /* ---------- Konfirmasi hapus foto ---------- */
    const btnBukaModal = document.getElementById('btnBukaModalHapus');
    const modalHapus = document.getElementById('modalHapusFoto');
    const btnBatal = document.getElementById('btnBatalHapus');
    const btnKonfirmasi = document.getElementById('btnKonfirmasiHapus');
    const formHapus = document.getElementById('formHapusFoto');

    function tutupModal() { modalHapus.classList.remove('show'); }

    if (btnBukaModal && modalHapus) {
        btnBukaModal.addEventListener('click', function () {
            modalHapus.classList.add('show');
            btnBatal.focus();
        });

        btnBatal.addEventListener('click', tutupModal);

        modalHapus.addEventListener('click', function (e) {
            if (e.target === modalHapus) tutupModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') tutupModal();
        });

        btnKonfirmasi.addEventListener('click', function () {
            btnKonfirmasi.disabled = true;
            btnKonfirmasi.textContent = 'Menghapus...';
            formHapus.submit();
        });
    }
})();
</script>

@endsection