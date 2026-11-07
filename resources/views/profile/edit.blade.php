@extends('layouts.app')

@section('title', 'Edit Data Diri')
@section('page-title', 'Edit Data Diri')
@section('page-description', 'Perbarui informasi pribadi akun')

@section('content')

@php
    $karyawan = $user->karyawan;
@endphp

<style>
    .tp-page {
        --tp-accent: #4E8BBC;
        --tp-accent-soft: rgba(78, 139, 188, .12);
        --tp-text: #1d1d1f;
        --tp-muted: #6e6e73;
        --tp-line: rgba(0, 0, 0, .07);
        --tp-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 700px; margin: 0 auto; color: var(--tp-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .tp-page *, .tp-page *::before, .tp-page *::after { box-sizing: border-box; }
    .tp-page button:focus-visible,
    .tp-page a:focus-visible { outline: 2px solid var(--tp-accent); outline-offset: 2px; }

    .tp-page .tp-card { background: #fff; border: 1px solid var(--tp-line); border-radius: 18px; box-shadow: var(--tp-shadow); padding: 24px; }
    .tp-page .tp-card-head { display: flex; align-items: center; gap: 12px; margin-bottom: 22px; }
    .tp-page .tp-card-icon { width: 36px; height: 36px; flex-shrink: 0; display: grid; place-items: center; border-radius: 10px; background: rgba(78,139,188,.14); color: #3b7aab; }
    .tp-page .tp-card-icon svg { width: 18px; height: 18px; }
    .tp-page .tp-card-title { font-size: 14px; font-weight: 700; margin: 0; }
    .tp-page .tp-card-desc { font-size: 12px; color: var(--tp-muted); margin: 2px 0 0; }

    /* Field */
    .tp-page .tp-field { margin-bottom: 18px; }
    .tp-page .tp-label { display: flex; align-items: baseline; gap: 6px; font-size: 12px; font-weight: 600; margin-bottom: 6px; }
    .tp-page .tp-label small { font-size: 11px; font-weight: 500; color: var(--tp-muted); }
    .tp-page .tp-control {
        width: 100%; height: 40px; padding: 0 14px; border-radius: 10px;
        border: 1px solid var(--tp-line); background: rgba(120,120,128,.08); color: var(--tp-text);
        font: inherit; font-size: 13px; outline: none; transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .tp-page .tp-control::placeholder { color: #8e8e93; }
    .tp-page .tp-control:focus { background: #fff; border-color: var(--tp-accent); box-shadow: 0 0 0 4px var(--tp-accent-soft); }
    .tp-page .tp-control:disabled { background: rgba(120,120,128,.05); color: var(--tp-muted); cursor: not-allowed; }
    .tp-page .tp-control.is-invalid { border-color: rgba(255,59,48,.5); background: rgba(255,59,48,.04); }
    .tp-page .tp-control.is-invalid:focus { border-color: #c9342a; box-shadow: 0 0 0 4px rgba(255,59,48,.12); }
    .tp-page .tp-hint { display: flex; align-items: center; gap: 5px; font-size: 11px; color: var(--tp-muted); margin-top: 6px; }
    .tp-page .tp-hint svg { width: 12px; height: 12px; flex-shrink: 0; }
    .tp-page .tp-error { font-size: 12px; font-weight: 600; color: #c9342a; margin-top: 6px; }

    /* Aksi */
    .tp-page .tp-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--tp-line); }
    .tp-page .tp-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 40px; padding: 0 18px; border-radius: 10px; border: 0;
        background: var(--tp-accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: filter .15s;
    }
    .tp-page .tp-btn:hover { filter: brightness(1.06); }
    .tp-page .tp-btn:disabled { opacity: .7; cursor: wait; }
    .tp-page .tp-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .tp-page .tp-btn-ghost:hover { background: rgba(120,120,128,.18); filter: none; }

    @media (max-width: 480px) {
        .tp-page .tp-actions { flex-direction: column-reverse; }
        .tp-page .tp-btn { width: 100%; }
    }
</style>

<div class="tp-page">

    <div class="tp-card">

        <div class="tp-card-head">
            <div class="tp-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
            </div>
            <div>
                <h4 class="tp-card-title">Data Diri</h4>
                <p class="tp-card-desc">Perbarui nama, email, dan jabatan Anda.</p>
            </div>
        </div>

        <form id="editForm" action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- NIP (read-only) --}}
            <div class="tp-field">
                <label class="tp-label" for="nip">NIP</label>
                <input type="text" id="nip" class="tp-control" value="{{ $karyawan->nip ?? '-' }}" disabled>
                <div class="tp-hint">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                    NIP tidak dapat diubah.
                </div>
            </div>

            {{-- Nama --}}
            <div class="tp-field">
                <label class="tp-label" for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama"
                       class="tp-control @error('nama') is-invalid @enderror"
                       value="{{ old('nama', $karyawan->nama ?? $user->name) }}"
                       maxlength="255" autocomplete="name" required>
                @error('nama')
                    <div class="tp-error" role="alert">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="tp-field">
                <label class="tp-label" for="email">Email</label>
                <input type="email" id="email" name="email"
                       class="tp-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $karyawan->email ?? $user->email) }}"
                       maxlength="255" autocomplete="email" required>
                @error('email')
                    <div class="tp-error" role="alert">{{ $message }}</div>
                @enderror
            </div>

            {{-- Jabatan --}}
            <div class="tp-field" style="margin-bottom: 0;">
                <label class="tp-label" for="jabatan">Jabatan <small>Opsional</small></label>
                <input type="text" id="jabatan" name="jabatan"
                       class="tp-control @error('jabatan') is-invalid @enderror"
                       value="{{ old('jabatan', $karyawan->jabatan ?? '') }}"
                       maxlength="255" autocomplete="organization-title">
                @error('jabatan')
                    <div class="tp-error" role="alert">{{ $message }}</div>
                @enderror
            </div>

            <div class="tp-actions">
                <a href="{{ route('profile.index') }}" class="tp-btn tp-btn-ghost">Batal</a>
                <button type="submit" id="btnSimpan" class="tp-btn">Simpan Perubahan</button>
            </div>
        </form>

    </div>

</div>

<script>
    // Cegah klik ganda saat menyimpan
    document.getElementById('editForm').addEventListener('submit', function () {
        const btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';
    });
</script>

@endsection