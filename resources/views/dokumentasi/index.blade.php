@extends('layouts.app')

@section('title', 'Dokumentasi Kegiatan | HumasIn')
@section('page-title', 'Dokumentasi Kegiatan & Arsip')
@section('page-description', 'Pusat folder arsip tautan Google Drive dokumentasi kegiatan DJPK')

@section('content')

<style>
    .dk-page {
        --dk-accent: #4E8BBC; --dk-accent-soft: rgba(78,139,188,.12);
        --dk-text: #1d1d1f; --dk-muted: #6e6e73; --dk-line: rgba(0,0,0,.07);
        --dk-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);
        max-width: 1280px; margin: 0 auto; color: var(--dk-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .dk-page *, .dk-page *::before, .dk-page *::after { box-sizing: border-box; }
    .dk-page button:focus-visible, .dk-page a:focus-visible { outline: 2px solid var(--dk-accent); outline-offset: 2px; }

    .dk-page .dk-alert { display: flex; gap: 10px; align-items: flex-start; margin-bottom: 20px; padding: 12px 16px; border-radius: 12px; background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.22); color: #c9342a; font-size: 13px; line-height: 1.5; }
    .dk-page .dk-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px; }

    .dk-page .dk-section { margin-bottom: 32px; }
    .dk-page .dk-section-title { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin: 0 0 14px; font-size: 15px; font-weight: 700; letter-spacing: -.01em; }
    .dk-page .dk-count { display: inline-flex; padding: 3px 11px; border-radius: 999px; font-size: 12px; font-weight: 600; background: var(--dk-accent-soft); color: #3b7aab; }

    /* Pencarian */
    .dk-page .dk-search { position: relative; width: 340px; max-width: 100%; font-weight: 400; }
    .dk-page .dk-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: #8e8e93; pointer-events: none; }
    .dk-page .dk-search input { width: 100%; height: 38px; padding: 0 14px 0 34px; border-radius: 10px; border: 1px solid transparent; background: rgba(120,120,128,.1); color: var(--dk-text); font: inherit; font-size: 13px; outline: none; transition: background .15s, box-shadow .15s, border-color .15s; }
    .dk-page .dk-search input::placeholder { color: #8e8e93; }
    .dk-page .dk-search input:focus { background: #fff; border-color: var(--dk-accent); box-shadow: 0 0 0 4px var(--dk-accent-soft); }

    /* Kartu admin */
    .dk-page .dk-admin { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 32px; }
    .dk-page .dk-card { background: #fff; border: 1px solid var(--dk-line); border-radius: 18px; box-shadow: var(--dk-shadow); padding: 20px; }
    .dk-page .dk-card-head { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
    .dk-page .dk-card-icon { width: 36px; height: 36px; flex-shrink: 0; display: grid; place-items: center; border-radius: 10px; background: rgba(78,139,188,.14); color: #3b7aab; }
    .dk-page .dk-card-icon svg { width: 18px; height: 18px; }
    .dk-page .dk-card-title { font-size: 14px; font-weight: 700; margin: 0; }
    .dk-page .dk-card-desc { font-size: 12px; color: var(--dk-muted); margin: 2px 0 0; }
    .dk-page .dk-stack { display: flex; flex-direction: column; gap: 10px; }
    .dk-page .dk-row { display: flex; gap: 10px; }
    .dk-page .dk-grow { flex: 1; min-width: 0; }

    .dk-page .dk-control { width: 100%; height: 40px; padding: 0 14px; border-radius: 10px; border: 1px solid var(--dk-line); background: rgba(120,120,128,.08); color: var(--dk-text); font: inherit; font-size: 13px; outline: none; transition: background .15s, box-shadow .15s, border-color .15s; }
    .dk-page .dk-control::placeholder { color: #8e8e93; }
    .dk-page .dk-control:focus { background: #fff; border-color: var(--dk-accent); box-shadow: 0 0 0 4px var(--dk-accent-soft); }
    .dk-page .dk-control.invalid { border-color: #ff3b30; background: rgba(255,59,48,.05); }
    .dk-page .dk-control.invalid:focus { box-shadow: 0 0 0 4px rgba(255,59,48,.15); }
    .dk-page select.dk-control { appearance: none; cursor: pointer; padding-right: 36px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; }
    .dk-page .dk-field-error { margin-top: 6px; font-size: 12px; font-weight: 500; color: #c9342a; line-height: 1.4; }
    .dk-page .dk-field-error[hidden] { display: none; }

    .dk-page .dk-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 40px; padding: 0 18px; border-radius: 10px; border: 0; background: var(--dk-accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: filter .15s; }
    .dk-page .dk-btn:hover { filter: brightness(1.06); }
    .dk-page .dk-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .dk-page .dk-btn-ghost:hover { background: rgba(120,120,128,.18); filter: none; }

    /* Folder */
    .dk-page .dk-folders { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 14px; }
    .dk-page .dk-folder-wrap { position: relative; min-width: 0; }
    .dk-page .dk-folder-wrap:has(.dk-dropdown:not([hidden])) { z-index: 20; }
    .dk-page .dk-folder { display: flex; align-items: center; gap: 6px; min-height: 76px; padding: 14px 10px 14px 16px; background: #fff; border: 1px solid var(--dk-line); border-radius: 16px; box-shadow: var(--dk-shadow); transition: transform .2s, border-color .2s, box-shadow .2s, background .2s; color: inherit; min-width: 0; }
    .dk-page .dk-folder:hover { transform: translateY(-2px); border-color: var(--dk-accent); box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 14px 30px rgba(78,139,188,.16); }
    .dk-page .dk-folder.active { border-color: var(--dk-accent); background: rgba(78,139,188,.06); box-shadow: 0 0 0 3px var(--dk-accent-soft); }
    .dk-page .dk-folder-link { display: flex; align-items: center; gap: 14px; flex: 1; min-width: 0; text-decoration: none; color: inherit; }
    .dk-page .dk-folder-icon { width: 42px; height: 42px; flex-shrink: 0; display: grid; place-items: center; border-radius: 12px; background: rgba(78,139,188,.14); color: #3b7aab; }
    .dk-page .dk-folder-icon svg { width: 21px; height: 21px; }
    .dk-page .dk-folder-icon.all { background: rgba(88,86,214,.12); color: #5856d6; }
    .dk-page .dk-folder-text { flex: 1; min-width: 0; }
    .dk-page .dk-folder-name { font-size: 13px; font-weight: 600; letter-spacing: -.005em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dk-page .dk-folder-meta { font-size: 11px; color: var(--dk-muted); margin-top: 2px; }
    .dk-page .dk-folders-empty { grid-column: 1 / -1; padding: 28px 20px; text-align: center; color: var(--dk-muted); font-size: 13px; background: #fff; border: 1px dashed rgba(0,0,0,.15); border-radius: 16px; }
    .dk-page .dk-folders-empty[hidden] { display: none; }

    /* Menu titik tiga */
    .dk-page .dk-menu { position: relative; flex-shrink: 0; }
    .dk-page .dk-menu-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border: 0; border-radius: 9px; background: transparent; color: var(--dk-muted); cursor: pointer; transition: background .15s, color .15s; }
    .dk-page .dk-menu-btn svg { width: 18px; height: 18px; }
    .dk-page .dk-menu-btn:hover, .dk-page .dk-menu-btn[aria-expanded="true"] { background: rgba(120,120,128,.12); color: var(--dk-text); }
    .dk-page .dk-dropdown { position: absolute; top: calc(100% + 6px); right: 0; z-index: 100; width: 170px; padding: 5px; background: #fff; border: 1px solid rgba(0,0,0,.08); border-radius: 12px; box-shadow: 0 8px 28px rgba(0,0,0,.12); }
    .dk-page .dk-dropdown[hidden] { display: none; }
    .dk-page .dk-dropdown form { margin: 0; }
    .dk-page .dk-menu-item { width: 100%; display: flex; align-items: center; gap: 9px; padding: 10px; border: 0; border-radius: 8px; background: transparent; color: #3a3a3c; text-align: left; font: inherit; font-size: 12px; font-weight: 500; cursor: pointer; }
    .dk-page .dk-menu-item svg { width: 15px; height: 15px; flex-shrink: 0; }
    .dk-page .dk-menu-item:hover { background: #f5f5f7; }
    .dk-page .dk-menu-item.danger { color: #c9342a; }
    .dk-page .dk-menu-item.danger:hover { background: rgba(255,59,48,.08); }

    /* Rename inline */
    .dk-page .dk-rename { display: none; min-height: 76px; padding: 12px 14px; background: #fff; border: 1px solid var(--dk-accent); border-radius: 16px; box-shadow: 0 0 0 3px var(--dk-accent-soft), var(--dk-shadow); }
    .dk-page .dk-folder-wrap.is-renaming .dk-folder { display: none; }
    .dk-page .dk-folder-wrap.is-renaming .dk-rename { display: block; }
    .dk-page .dk-rename label { display: block; font-size: 11px; font-weight: 600; color: var(--dk-muted); margin-bottom: 6px; }
    .dk-page .dk-rename .dk-control { height: 36px; }
    .dk-page .dk-rename-actions { display: flex; justify-content: flex-end; gap: 6px; margin-top: 8px; }
    .dk-page .dk-rename-actions .dk-btn { height: 32px; padding: 0 14px; font-size: 12px; border-radius: 8px; }

    /* Kartu tautan */
    .dk-page .dk-files { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 18px; }
    .dk-page .dk-file { display: flex; flex-direction: column; padding: 16px; background: #fff; border: 1px solid var(--dk-line); border-radius: 20px; box-shadow: var(--dk-shadow); transition: transform .2s, border-color .2s, box-shadow .2s; }
    .dk-page .dk-file:hover { transform: translateY(-3px); border-color: var(--dk-accent); box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 16px 36px rgba(78,139,188,.16); }
    .dk-page .dk-file-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; min-height: 26px; }
    .dk-page .dk-type { display: inline-flex; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; background: rgba(78,139,188,.14); color: #3b7aab; }
    .dk-page .dk-del-form { margin: 0; }
    .dk-page .dk-del { border: 0; background: transparent; color: #c9342a; font: inherit; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 8px; cursor: pointer; transition: background .15s; }
    .dk-page .dk-del:hover { background: rgba(255,59,48,.12); }
    .dk-page .dk-preview { aspect-ratio: 16 / 9; border-radius: 14px; overflow: hidden; background: #f5f5f7; margin-bottom: 14px; }
    .dk-page .dk-placeholder { width: 100%; height: 100%; display: grid; place-items: center; align-content: center; gap: 6px; color: #8e8e93; font-size: 11px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; }
    .dk-page .dk-placeholder svg { width: 34px; height: 34px; color: #b0b0b5; }
    .dk-page .dk-file-body { margin-bottom: 14px; }
    .dk-page .dk-file-name { font-size: 14px; font-weight: 700; letter-spacing: -.01em; margin: 0 0 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dk-page .dk-file-meta { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--dk-muted); min-width: 0; }
    .dk-page .dk-file-meta + .dk-file-meta { margin-top: 3px; }
    .dk-page .dk-file-meta svg { width: 13px; height: 13px; flex-shrink: 0; }
    .dk-page .dk-file-meta span { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .dk-page .dk-file-foot { display: flex; justify-content: flex-end; gap: 8px; margin-top: auto; padding-top: 14px; border-top: 1px solid var(--dk-line); }
    .dk-page .dk-act { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 34px; padding: 0 14px; border-radius: 9px; border: 0; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; background: var(--dk-accent); color: #fff; transition: filter .15s; }
    .dk-page .dk-act:hover { filter: brightness(1.06); }
    .dk-page .dk-act svg { width: 14px; height: 14px; }

    .dk-page .dk-empty { grid-column: 1 / -1; padding: 64px 20px; text-align: center; color: var(--dk-muted); font-size: 13px; background: #fff; border: 1px solid var(--dk-line); border-radius: 20px; box-shadow: var(--dk-shadow); }
    .dk-page .dk-empty[hidden] { display: none; }
    .dk-page .dk-empty svg { width: 38px; height: 38px; color: #c7c7cc; margin-bottom: 10px; }

    /* Modal konfirmasi */
    .dk-modal { display: none; position: fixed; inset: 0; z-index: 99999; align-items: center; justify-content: center; padding: 20px; background: rgba(0,0,0,.45); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif; }
    .dk-confirm-box { width: 400px; max-width: 100%; padding: 28px 26px 22px; text-align: center; background: rgba(255,255,255,.98); border-radius: 20px; box-shadow: 0 0 0 1px rgba(0,0,0,.06), 0 24px 60px rgba(0,0,0,.25); animation: dkPop .18s ease; color: #1d1d1f; }
    @keyframes dkPop { from { opacity: 0; transform: scale(.94); } to { opacity: 1; transform: scale(1); } }
    .dk-confirm-icon { width: 52px; height: 52px; margin: 0 auto 14px; display: grid; place-items: center; border-radius: 50%; background: rgba(255,59,48,.12); color: #c9342a; }
    .dk-confirm-icon svg { width: 26px; height: 26px; }
    .dk-confirm-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 8px; }
    .dk-confirm-text { font-size: 13px; line-height: 1.6; color: #3a3a3c; margin: 0; }
    .dk-confirm-text strong { word-break: break-word; }
    .dk-confirm-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 22px; }
    .dk-confirm-actions button { height: 40px; border: 0; border-radius: 10px; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; }
    .dk-confirm-cancel { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .dk-confirm-cancel:hover { background: rgba(120,120,128,.18); }
    .dk-confirm-ok { background: #ff3b30; color: #fff; }
    .dk-confirm-ok:hover { filter: brightness(1.06); }

    @media (max-width: 900px) { .dk-page .dk-admin { grid-template-columns: 1fr; } }
    @media (max-width: 560px) { .dk-page .dk-row { flex-direction: column; } }
    @media (prefers-reduced-motion: reduce) {
        .dk-page .dk-folder, .dk-page .dk-file { transition: none; }
        .dk-page .dk-folder:hover, .dk-page .dk-file:hover { transform: none; }
    }
</style>

@php
    $user = Auth::user();
    $user?->loadMissing('peran');
    $isAdmin = in_array($user?->peran?->kode_peran ?? '', ['SUPER_ADMIN', 'ADMIN'], true);
    $folderIco = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>';
@endphp

<div class="dk-page">

    @if ($errors->any())
        <div class="dk-alert" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
            <div>@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
        </div>
    @endif

    {{-- KONTROL ADMIN --}}
    @if ($isAdmin)
        <div class="dk-admin">

            <div class="dk-card">
                <div class="dk-card-head">
                    <div class="dk-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M12 11v5M9.5 13.5h5"/></svg></div>
                    <div>
                        <h4 class="dk-card-title">Buat Folder Kategori</h4>
                        <p class="dk-card-desc">Contoh: Rakor, Kunker.</p>
                    </div>
                </div>

                <form action="{{ route('dokumentasi.category.store') }}" method="POST" id="dkCreateFolder" novalidate>
                    @csrf
                    <div class="dk-row">
                        <div class="dk-grow">
                            <input type="text" name="nama_kategori" id="dkNamaBaru" class="dk-control" placeholder="Nama folder…"
                                   maxlength="100" value="{{ old('nama_kategori') }}" autocomplete="off" required>
                            <div class="dk-field-error" id="dkNamaBaruError" hidden></div>
                        </div>
                        <button type="submit" class="dk-btn">Buat Folder</button>
                    </div>
                </form>
            </div>

            <div class="dk-card">
                <div class="dk-card-head">
                    <div class="dk-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/></svg></div>
                    <div>
                        <h4 class="dk-card-title">Tambah Link Dokumentasi</h4>
                        <p class="dk-card-desc">Tautan Google Drive kegiatan.</p>
                    </div>
                </div>

                <form action="{{ route('dokumentasi.item.store') }}" method="POST">
                    @csrf
                    <div class="dk-stack">
                        <div class="dk-row">
                            <select name="category_id" class="dk-control" required>
                                <option value="">Pilih folder tujuan</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $selectedCategory && $selectedCategory->id === $cat->id ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="judul" class="dk-control" placeholder="Judul kegiatan / acara…" required>
                        </div>
                        <div class="dk-row">
                            <input type="url" name="link_url" class="dk-control" placeholder="https://drive.google.com/…" required>
                            <button type="submit" class="dk-btn">Simpan Link</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    @endif

    {{-- FOLDER --}}
    <div class="dk-section">
        <h3 class="dk-section-title">
            <span>Kategori / Folder <span class="dk-count" id="dkFolderCount">{{ $categories->count() }} folder</span></span>

            <label class="dk-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input type="text" id="dkSearch" placeholder="Cari folder atau arsip…" autocomplete="off" aria-label="Cari folder atau arsip">
            </label>
        </h3>

        <div class="dk-folders">

            <div class="dk-folder-wrap">
                <div class="dk-folder {{ !$selectedCategory ? 'active' : '' }}">
                    <a href="{{ route('dokumentasi.index') }}" class="dk-folder-link">
                        <div class="dk-folder-icon all"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg></div>
                        <div class="dk-folder-text">
                            <div class="dk-folder-name">Semua Dokumentasi</div>
                            <div class="dk-folder-meta">Tampilkan seluruh arsip</div>
                        </div>
                    </a>
                </div>
            </div>

            @foreach ($categories as $cat)
                <div class="dk-folder-wrap" data-folder data-nama="{{ $cat->nama_kategori }}" data-id="{{ $cat->id }}">

                    <div class="dk-folder {{ $selectedCategory && $selectedCategory->id === $cat->id ? 'active' : '' }}">
                        <a href="{{ route('dokumentasi.index', ['folder' => $cat->slug]) }}" class="dk-folder-link">
                            <div class="dk-folder-icon">{!! $folderIco !!}</div>
                            <div class="dk-folder-text">
                                <div class="dk-folder-name" title="{{ $cat->nama_kategori }}">{{ $cat->nama_kategori }}</div>
                                <div class="dk-folder-meta">{{ $cat->items->count() }} tautan tersedia</div>
                            </div>
                        </a>

                        @if ($isAdmin)
                            <div class="dk-menu">
                                <button type="button" class="dk-menu-btn" data-action="toggle-menu" aria-label="Menu folder {{ $cat->nama_kategori }}" aria-haspopup="true" aria-expanded="false">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.8"/><circle cx="12" cy="12" r="1.8"/><circle cx="12" cy="19" r="1.8"/></svg>
                                </button>

                                <div class="dk-dropdown" hidden>
                                    <button type="button" class="dk-menu-item" data-action="start-rename">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                        Ganti nama
                                    </button>

                                    <form action="{{ route('dokumentasi.category.destroy', $cat->id) }}" method="POST" class="js-confirm"
                                          data-confirm-title="Hapus folder?"
                                          data-confirm-name="{{ $cat->nama_kategori }}"
                                          data-confirm-text="akan dihapus. Tindakan ini tidak bisa dibatalkan."
                                          data-confirm-ok="Hapus Folder">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dk-menu-item danger">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v5M14 11v5"/></svg>
                                            Hapus folder
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($isAdmin)
                        <form action="{{ route('dokumentasi.category.update', $cat->id) }}" method="POST" class="dk-rename" novalidate>
                            @csrf
                            @method('PATCH')
                            <label for="rename-{{ $cat->id }}">Nama folder baru</label>
                            <input type="text" id="rename-{{ $cat->id }}" name="nama_kategori" class="dk-control" value="{{ $cat->nama_kategori }}" maxlength="100" autocomplete="off" required>
                            <div class="dk-field-error" hidden></div>
                            <div class="dk-rename-actions">
                                <button type="button" class="dk-btn dk-btn-ghost" data-action="cancel-rename">Batal</button>
                                <button type="submit" class="dk-btn">Simpan</button>
                            </div>
                        </form>
                    @endif

                </div>
            @endforeach

            <div class="dk-folders-empty" id="dkFoldersEmpty" hidden>Folder tidak ditemukan. Coba kata kunci lain.</div>
        </div>
    </div>

    {{-- ARSIP --}}
    <div class="dk-section">
        <h3 class="dk-section-title">
            <span>Arsip dalam folder: {{ $selectedCategory ? $selectedCategory->nama_kategori : 'Semua Dokumentasi' }}</span>
            <span class="dk-count" id="dkItemCount">{{ $items->count() }} tautan</span>
        </h3>

        <div class="dk-files" id="dkFiles">
            @foreach ($items as $item)
                @php $host = parse_url($item->link_url, PHP_URL_HOST) ?: $item->link_url; @endphp

                <div class="dk-file" data-item data-cari="{{ strtolower($item->judul . ' ' . ($item->category->nama_kategori ?? '') . ' ' . $host) }}">
                    <div class="dk-file-top">
                        <span class="dk-type">Google Drive</span>

                        @if ($isAdmin)
                            <form action="{{ route('dokumentasi.item.destroy', $item->id) }}" method="POST" class="dk-del-form js-confirm"
                                  data-confirm-title="Hapus tautan?"
                                  data-confirm-name="{{ $item->judul }}"
                                  data-confirm-text="akan dihapus dari arsip folder ini."
                                  data-confirm-ok="Hapus Tautan">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dk-del">Hapus</button>
                            </form>
                        @endif
                    </div>

                    <div class="dk-preview">
                        <div class="dk-placeholder">{!! $folderIco !!}<span>Arsip kegiatan</span></div>
                    </div>

                    <div class="dk-file-body">
                        <h4 class="dk-file-name" title="{{ $item->judul }}">{{ $item->judul }}</h4>
                        <div class="dk-file-meta">{!! $folderIco !!}<span>{{ $item->category->nama_kategori ?? '—' }}</span></div>
                        <div class="dk-file-meta">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/></svg>
                            <span>{{ $host }}</span>
                        </div>
                    </div>

                    <div class="dk-file-foot">
                        <a href="{{ $item->link_url }}" target="_blank" rel="noopener noreferrer" class="dk-act">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/></svg>
                            Buka Google Drive
                        </a>
                    </div>
                </div>
            @endforeach

            <div class="dk-empty" id="dkItemsEmpty" {{ $items->count() ? 'hidden' : '' }}>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                <div id="dkItemsEmptyText">Belum ada tautan dokumentasi di folder ini.</div>
            </div>
        </div>
    </div>

</div>

{{-- MODAL KONFIRMASI --}}
<div class="dk-modal" id="dkConfirm" role="alertdialog" aria-modal="true" aria-labelledby="dkConfirmTitle">
    <div class="dk-confirm-box">
        <div class="dk-confirm-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6M10 11v6M14 11v6"/></svg>
        </div>
        <h3 class="dk-confirm-title" id="dkConfirmTitle">Konfirmasi</h3>
        <p class="dk-confirm-text"><strong id="dkConfirmName"></strong> <span id="dkConfirmText"></span></p>
        <div class="dk-confirm-actions">
            <button type="button" class="dk-confirm-cancel" id="dkConfirmCancel">Batal</button>
            <button type="button" class="dk-confirm-ok" id="dkConfirmOk">Hapus</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const norm = function (s) { return (s || '').trim().replace(/\s+/g, ' ').toLowerCase(); };

    const wraps = Array.from(document.querySelectorAll('[data-folder]'));
    const items = Array.from(document.querySelectorAll('[data-item]'));
    const namaFolder = wraps.map(function (w) { return { id: w.dataset.id, nama: norm(w.dataset.nama) }; });

    function adaKembar(nama, kecualiId) {
        const n = norm(nama);
        return namaFolder.some(function (f) { return f.nama === n && String(f.id) !== String(kecualiId || ''); });
    }
    function tampilError(input, box, pesan) { input.classList.add('invalid'); box.textContent = pesan; box.hidden = false; }
    function hapusError(input, box) { input.classList.remove('invalid'); box.hidden = true; box.textContent = ''; }

    /* ============ Modal konfirmasi ============ */
    const cModal = document.getElementById('dkConfirm');
    const cOk = document.getElementById('dkConfirmOk');
    let formTertunda = null;

    function tutupConfirm() { cModal.style.display = 'none'; formTertunda = null; }

    document.querySelectorAll('form.js-confirm').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            formTertunda = form;
            document.getElementById('dkConfirmTitle').textContent = form.dataset.confirmTitle || 'Konfirmasi';
            document.getElementById('dkConfirmName').textContent = form.dataset.confirmName ? '"' + form.dataset.confirmName + '"' : '';
            document.getElementById('dkConfirmText').textContent = form.dataset.confirmText || '';
            cOk.textContent = form.dataset.confirmOk || 'Lanjutkan';
            cModal.style.display = 'flex';
            cOk.focus();
        });
    });
    cOk.addEventListener('click', function () {
        if (!formTertunda) return;
        const f = formTertunda;
        cModal.style.display = 'none';
        formTertunda = null;
        HTMLFormElement.prototype.submit.call(f);
    });
    document.getElementById('dkConfirmCancel').addEventListener('click', tutupConfirm);
    cModal.addEventListener('click', function (e) { if (e.target === cModal) tutupConfirm(); });

    /* ============ Pencarian (folder + arsip) ============ */
    const search = document.getElementById('dkSearch');
    const folderCount = document.getElementById('dkFolderCount');
    const itemCount = document.getElementById('dkItemCount');
    const foldersEmpty = document.getElementById('dkFoldersEmpty');
    const itemsEmpty = document.getElementById('dkItemsEmpty');
    const itemsEmptyText = document.getElementById('dkItemsEmptyText');
    const teksKosongAwal = itemsEmptyText.textContent;

    search.addEventListener('input', function () {
        const q = norm(this.value);

        let f = 0;
        wraps.forEach(function (w) {
            const ok = !q || norm(w.dataset.nama).indexOf(q) !== -1;
            w.style.display = ok ? '' : 'none';
            if (ok) f++;
        });
        folderCount.textContent = (q ? f + ' dari ' + wraps.length : wraps.length) + ' folder';
        foldersEmpty.hidden = !(q && wraps.length && f === 0);

        let n = 0;
        items.forEach(function (c) {
            const ok = !q || c.dataset.cari.indexOf(q) !== -1;
            c.style.display = ok ? '' : 'none';
            if (ok) n++;
        });
        itemCount.textContent = n + ' tautan';

        if (items.length) {
            itemsEmpty.hidden = n > 0;
            itemsEmptyText.textContent = 'Tidak ada arsip yang cocok dengan "' + this.value.trim() + '".';
        } else {
            itemsEmpty.hidden = false;
            itemsEmptyText.textContent = teksKosongAwal;
        }
    });

    /* ============ Buat folder: cek nama kembar ============ */
    const formBuat = document.getElementById('dkCreateFolder');
    if (formBuat) {
        const input = document.getElementById('dkNamaBaru');
        const box = document.getElementById('dkNamaBaruError');
        input.addEventListener('input', function () { hapusError(input, box); });

        formBuat.addEventListener('submit', function (e) {
            if (!norm(input.value)) {
                e.preventDefault();
                tampilError(input, box, 'Nama folder tidak boleh kosong.');
                input.focus();
            } else if (adaKembar(input.value)) {
                e.preventDefault();
                tampilError(input, box, 'Folder dengan nama "' + input.value.trim() + '" sudah ada. Gunakan nama lain.');
                input.focus();
            }
        });
    }

    /* ============ Ganti nama: cek nama kembar ============ */
    document.querySelectorAll('.dk-rename').forEach(function (form) {
        const wrap = form.closest('.dk-folder-wrap');
        const input = form.querySelector('input[name="nama_kategori"]');
        const box = form.querySelector('.dk-field-error');
        input.addEventListener('input', function () { hapusError(input, box); });

        form.addEventListener('submit', function (e) {
            if (!norm(input.value)) {
                e.preventDefault();
                tampilError(input, box, 'Nama folder tidak boleh kosong.');
            } else if (adaKembar(input.value, wrap.dataset.id)) {
                e.preventDefault();
                tampilError(input, box, 'Nama "' + input.value.trim() + '" sudah dipakai folder lain.');
                input.focus();
            }
        });
    });

    /* ============ Menu titik tiga & rename ============ */
    function closeAllMenus() {
        document.querySelectorAll('.dk-dropdown').forEach(function (m) {
            m.hidden = true;
            const b = m.parentElement.querySelector('.dk-menu-btn');
            if (b) b.setAttribute('aria-expanded', 'false');
        });
    }
    function resetRename(w) {
        w.classList.remove('is-renaming');
        const input = w.querySelector('.dk-rename input');
        const box = w.querySelector('.dk-rename .dk-field-error');
        if (input && box) { hapusError(input, box); input.value = w.dataset.nama; }
    }
    function cancelAllRename() {
        document.querySelectorAll('.dk-folder-wrap.is-renaming').forEach(resetRename);
    }

    document.addEventListener('click', function (e) {
        const t = e.target.closest('[data-action]');
        if (!t) { if (!e.target.closest('.dk-menu')) closeAllMenus(); return; }

        const a = t.dataset.action;

        if (a === 'toggle-menu') {
            const menu = t.parentElement.querySelector('.dk-dropdown');
            const buka = menu.hidden;
            closeAllMenus();
            menu.hidden = !buka;
            t.setAttribute('aria-expanded', String(buka));
        }
        if (a === 'start-rename') {
            const wrap = t.closest('.dk-folder-wrap');
            closeAllMenus();
            cancelAllRename();
            wrap.classList.add('is-renaming');
            const input = wrap.querySelector('.dk-rename input[name="nama_kategori"]');
            if (input) { input.focus(); input.select(); }
        }
        if (a === 'cancel-rename') resetRename(t.closest('.dk-folder-wrap'));
    });

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        tutupConfirm(); closeAllMenus(); cancelAllRename();
    });
});
</script>

@endsection