@extends('layouts.app')

@section('title', 'Template Desain | HumasIn')
@section('page-title', 'Template Desain & Aset')
@section('page-description', 'Pusat penyimpanan folder aset, ikon, dan materi presentasi DJPK')

@section('content')

<style>
    .tp-page {
        --tp-accent: #4E8BBC;
        --tp-accent-soft: rgba(78, 139, 188, .12);
        --tp-text: #1d1d1f;
        --tp-muted: #6e6e73;
        --tp-line: rgba(0, 0, 0, .07);
        --tp-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 1280px; margin: 0 auto; color: var(--tp-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .tp-page *, .tp-page *::before, .tp-page *::after { box-sizing: border-box; }
    .tp-page button:focus-visible,
    .tp-page a:focus-visible { outline: 2px solid var(--tp-accent); outline-offset: 2px; }

    /* Alert */
    .tp-page .tp-alert {
        display: flex; gap: 10px; align-items: flex-start; margin-bottom: 20px; padding: 12px 16px; border-radius: 12px;
        background: rgba(52,199,89,.1); border: 1px solid rgba(52,199,89,.22); color: #1f7a38; font-size: 13px; line-height: 1.5;
    }
    .tp-page .tp-alert.error { background: rgba(255,59,48,.08); border-color: rgba(255,59,48,.22); color: #c9342a; }
    .tp-page .tp-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px; }

    /* Section */
    .tp-page .tp-section { margin-bottom: 32px; }
    .tp-page .tp-section-title { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin: 0 0 14px; font-size: 15px; font-weight: 700; letter-spacing: -.01em; }
    .tp-page .tp-count { display: inline-flex; padding: 3px 11px; border-radius: 999px; font-size: 12px; font-weight: 600; background: var(--tp-accent-soft); color: #3b7aab; }

    /* Pencarian folder */
    .tp-page .tp-search { position: relative; width: 300px; max-width: 100%; font-weight: 400; }
    .tp-page .tp-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: #8e8e93; pointer-events: none; }
    .tp-page .tp-search input {
        width: 100%; height: 38px; padding: 0 14px 0 34px; border-radius: 10px; border: 1px solid transparent;
        background: rgba(120,120,128,.1); color: var(--tp-text); font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .tp-page .tp-search input::placeholder { color: #8e8e93; }
    .tp-page .tp-search input:focus { background: #fff; border-color: var(--tp-accent); box-shadow: 0 0 0 4px var(--tp-accent-soft); }

    /* Kartu admin */
    .tp-page .tp-admin { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 32px; }
    .tp-page .tp-card { background: #fff; border: 1px solid var(--tp-line); border-radius: 18px; box-shadow: var(--tp-shadow); padding: 20px; }
    .tp-page .tp-card-head { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
    .tp-page .tp-card-icon { width: 36px; height: 36px; flex-shrink: 0; display: grid; place-items: center; border-radius: 10px; background: rgba(78,139,188,.14); color: #3b7aab; }
    .tp-page .tp-card-icon svg { width: 18px; height: 18px; }
    .tp-page .tp-card-title { font-size: 14px; font-weight: 700; margin: 0; }
    .tp-page .tp-card-desc { font-size: 12px; color: var(--tp-muted); margin: 2px 0 0; }
    .tp-page .tp-stack { display: flex; flex-direction: column; gap: 10px; }
    .tp-page .tp-row { display: flex; gap: 10px; }
    .tp-page .tp-grow { flex: 1; min-width: 0; }

    .tp-page .tp-control {
        width: 100%; height: 40px; padding: 0 14px; border-radius: 10px;
        border: 1px solid var(--tp-line); background: rgba(120,120,128,.08); color: var(--tp-text);
        font: inherit; font-size: 13px; outline: none; transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .tp-page .tp-control::placeholder { color: #8e8e93; }
    .tp-page .tp-control:focus { background: #fff; border-color: var(--tp-accent); box-shadow: 0 0 0 4px var(--tp-accent-soft); }
    .tp-page .tp-control.invalid { border-color: #ff3b30; background: rgba(255,59,48,.05); }
    .tp-page .tp-control.invalid:focus { box-shadow: 0 0 0 4px rgba(255,59,48,.15); }
    .tp-page select.tp-control {
        appearance: none; cursor: pointer; padding-right: 36px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 14px center;
    }
    .tp-page .tp-field-error { margin-top: 6px; font-size: 12px; font-weight: 500; color: #c9342a; line-height: 1.4; }
    .tp-page .tp-field-error[hidden] { display: none; }

    .tp-page .tp-file {
        flex: 1; min-width: 0; display: flex; align-items: center; padding: 6px 10px; border-radius: 10px;
        border: 1.5px dashed rgba(78,139,188,.4); background: rgba(78,139,188,.05);
    }
    .tp-page .tp-file input[type=file] { width: 100%; font-size: 12px; color: var(--tp-muted); cursor: pointer; }
    .tp-page .tp-file input[type=file]::file-selector-button {
        margin-right: 10px; padding: 6px 12px; border: 0; border-radius: 8px; background: #fff; color: #3a3a3c;
        font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,.1), 0 0 0 1px var(--tp-line);
    }

    .tp-page .tp-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 40px; padding: 0 18px; border-radius: 10px; border: 0;
        background: var(--tp-accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: filter .15s;
    }
    .tp-page .tp-btn:hover { filter: brightness(1.06); }
    .tp-page .tp-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .tp-page .tp-btn-ghost:hover { background: rgba(120,120,128,.18); filter: none; }

    /* Folder */
    .tp-page .tp-folders { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 14px; }
    .tp-page .tp-folder-wrap { position: relative; min-width: 0; }
    .tp-page .tp-folder-wrap:has(.tp-folder-dropdown:not([hidden])) { z-index: 20; }

    .tp-page .tp-folder {
        display: flex; align-items: center; gap: 6px; min-height: 76px; padding: 14px 10px 14px 16px; background: #fff; border: 1px solid var(--tp-line);
        border-radius: 16px; box-shadow: var(--tp-shadow); transition: transform .2s, border-color .2s, box-shadow .2s, background .2s;
        color: inherit; min-width: 0;
    }
    .tp-page .tp-folder:hover { transform: translateY(-2px); border-color: var(--tp-accent); box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 14px 30px rgba(78,139,188,.16); }
    .tp-page .tp-folder.active { border-color: var(--tp-accent); background: rgba(78,139,188,.06); box-shadow: 0 0 0 3px var(--tp-accent-soft); }
    .tp-page .tp-folder-link { display: flex; align-items: center; gap: 14px; flex: 1; min-width: 0; text-decoration: none; color: inherit; }
    .tp-page .tp-folder-icon { width: 42px; height: 42px; flex-shrink: 0; display: grid; place-items: center; border-radius: 12px; background: rgba(78,139,188,.14); color: #3b7aab; }
    .tp-page .tp-folder-icon svg { width: 21px; height: 21px; }
    .tp-page .tp-folder-icon.all { background: rgba(88,86,214,.12); color: #5856d6; }
    .tp-page .tp-folder-text { flex: 1; min-width: 0; }
    .tp-page .tp-folder-name { font-size: 13px; font-weight: 600; letter-spacing: -.005em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .tp-page .tp-folder-meta { font-size: 11px; color: var(--tp-muted); margin-top: 2px; }

    .tp-page .tp-folders-empty { grid-column: 1 / -1; padding: 28px 20px; text-align: center; color: var(--tp-muted); font-size: 13px; background: #fff; border: 1px dashed rgba(0,0,0,.15); border-radius: 16px; }
    .tp-page .tp-folders-empty[hidden] { display: none; }

    /* Menu titik tiga */
    .tp-page .tp-folder-menu { position: relative; flex-shrink: 0; }
    .tp-page .tp-folder-menu-btn {
        width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;
        border: 0; border-radius: 9px; background: transparent; color: var(--tp-muted); cursor: pointer; transition: background .15s, color .15s;
    }
    .tp-page .tp-folder-menu-btn svg { width: 18px; height: 18px; }
    .tp-page .tp-folder-menu-btn:hover,
    .tp-page .tp-folder-menu-btn[aria-expanded="true"] { background: rgba(120,120,128,.12); color: var(--tp-text); }

    .tp-page .tp-folder-dropdown {
        position: absolute; top: calc(100% + 6px); right: 0; z-index: 100; width: 170px; padding: 5px;
        background: #fff; border: 1px solid rgba(0,0,0,.08); border-radius: 12px; box-shadow: 0 8px 28px rgba(0,0,0,.12);
    }
    .tp-page .tp-folder-dropdown[hidden] { display: none; }
    .tp-page .tp-folder-dropdown form { margin: 0; }
    .tp-page .tp-folder-menu-item {
        width: 100%; display: flex; align-items: center; gap: 9px; padding: 10px; border: 0; border-radius: 8px;
        background: transparent; color: #3a3a3c; text-align: left; font: inherit; font-size: 12px; font-weight: 500; cursor: pointer;
    }
    .tp-page .tp-folder-menu-item svg { width: 15px; height: 15px; flex-shrink: 0; }
    .tp-page .tp-folder-menu-item:hover { background: #f5f5f7; }
    .tp-page .tp-folder-menu-item.danger { color: #c9342a; }
    .tp-page .tp-folder-menu-item.danger:hover { background: rgba(255,59,48,.08); }

    /* Rename inline */
    .tp-page .tp-folder-rename {
        display: none; min-height: 76px; padding: 12px 14px; background: #fff; border: 1px solid var(--tp-accent);
        border-radius: 16px; box-shadow: 0 0 0 3px var(--tp-accent-soft), var(--tp-shadow);
    }
    .tp-page .tp-folder-wrap.is-renaming .tp-folder { display: none; }
    .tp-page .tp-folder-wrap.is-renaming .tp-folder-rename { display: block; }
    .tp-page .tp-folder-rename label { display: block; font-size: 11px; font-weight: 600; color: var(--tp-muted); margin-bottom: 6px; }
    .tp-page .tp-folder-rename .tp-control { height: 36px; }
    .tp-page .tp-folder-rename .tp-field-error { margin-bottom: 2px; }
    .tp-page .tp-folder-rename-actions { display: flex; justify-content: flex-end; gap: 6px; margin-top: 8px; }
    .tp-page .tp-folder-rename-actions .tp-btn { height: 32px; padding: 0 14px; font-size: 12px; border-radius: 8px; }

    /* File */
    .tp-page .tp-files { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 18px; }
    .tp-page .tp-file-card {
        display: flex; flex-direction: column; padding: 16px; background: #fff; border: 1px solid var(--tp-line);
        border-radius: 20px; box-shadow: var(--tp-shadow); transition: transform .2s, border-color .2s, box-shadow .2s;
    }
    .tp-page .tp-file-card:hover { transform: translateY(-3px); border-color: var(--tp-accent); box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 16px 36px rgba(78,139,188,.16); }
    .tp-page .tp-file-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; min-height: 26px; }
    .tp-page .tp-type { display: inline-flex; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; background: rgba(78,139,188,.14); color: #3b7aab; }
    .tp-page .tp-del-form { margin: 0; }
    .tp-page .tp-del { border: 0; background: transparent; color: #c9342a; font: inherit; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 8px; cursor: pointer; transition: background .15s; }
    .tp-page .tp-del:hover { background: rgba(255,59,48,.12); }

    .tp-page .tp-preview { position: relative; aspect-ratio: 4 / 3; border-radius: 14px; overflow: hidden; background: #f5f5f7; margin-bottom: 14px; }
    .tp-page .tp-preview img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .35s ease; }
    .tp-page .tp-file-card:hover .tp-preview img { transform: scale(1.04); }
    .tp-page .tp-placeholder { width: 100%; height: 100%; display: grid; place-items: center; align-content: center; gap: 6px; color: #8e8e93; font-size: 11px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; }
    .tp-page .tp-placeholder svg { width: 34px; height: 34px; color: #b0b0b5; }

    .tp-page .tp-file-body { margin-bottom: 14px; }
    .tp-page .tp-file-name { font-size: 14px; font-weight: 700; letter-spacing: -.01em; margin: 0 0 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .tp-page .tp-file-meta { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; color: var(--tp-muted); }
    .tp-page .tp-file-meta svg { width: 13px; height: 13px; }

    .tp-page .tp-file-foot { display: flex; justify-content: flex-end; gap: 8px; margin-top: auto; padding-top: 14px; border-top: 1px solid var(--tp-line); }
    .tp-page .tp-act {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 34px; padding: 0 14px; border-radius: 9px; border: 0;
        font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s; text-decoration: none;
    }
    .tp-page .tp-act svg { width: 14px; height: 14px; }
    .tp-page .tp-act-view { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .tp-page .tp-act-view:hover { background: rgba(120,120,128,.18); }
    .tp-page .tp-act-dl { background: var(--tp-accent); color: #fff; }
    .tp-page .tp-act-dl:hover { filter: brightness(1.06); }

    .tp-page .tp-empty {
        grid-column: 1 / -1; padding: 64px 20px; text-align: center; color: var(--tp-muted); font-size: 13px;
        background: #fff; border: 1px solid var(--tp-line); border-radius: 20px; box-shadow: var(--tp-shadow);
    }
    .tp-page .tp-empty svg { width: 38px; height: 38px; color: #c7c7cc; margin-bottom: 10px; }

    /* Modal (preview + konfirmasi) */
    .tp-modal { display: none; position: fixed; inset: 0; z-index: 99999; align-items: center; justify-content: center; padding: 20px; background: rgba(0,0,0,.45); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif; }
    .tp-modal-box { width: 700px; max-width: 100%; max-height: 90vh; background: #fff; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,.25); display: flex; flex-direction: column; overflow: hidden; }
    .tp-modal-head { padding: 16px 20px; border-bottom: 1px solid rgba(0,0,0,.07); display: flex; justify-content: space-between; align-items: center; gap: 12px; background: #fafafc; }
    .tp-modal-title { font-size: 15px; font-weight: 700; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .tp-modal-close { flex-shrink: 0; width: 32px; height: 32px; border: 0; border-radius: 9px; background: transparent; font-size: 22px; line-height: 1; cursor: pointer; color: #6e6e73; }
    .tp-modal-close:hover { background: rgba(120,120,128,.12); color: #1d1d1f; }
    .tp-modal-body { padding: 20px; overflow-y: auto; background: #f5f5f7; display: flex; align-items: center; justify-content: center; min-height: 300px; }
    .tp-modal-body img { max-width: 100%; max-height: 60vh; border-radius: 12px; object-fit: contain; box-shadow: 0 4px 12px rgba(0,0,0,.08); }
    .tp-modal-body iframe { width: 100%; height: 60vh; border: 0; border-radius: 12px; background: #fff; }
    .tp-modal-fallback { text-align: center; color: #6e6e73; font-size: 13px; line-height: 1.6; }
    .tp-modal-fallback .tp-act { margin-top: 12px; }

    /* Konfirmasi */
    .tp-confirm-box { width: 400px; max-width: 100%; padding: 28px 26px 22px; text-align: center; background: rgba(255,255,255,.98); border-radius: 20px; box-shadow: 0 0 0 1px rgba(0,0,0,.06), 0 24px 60px rgba(0,0,0,.25); animation: tpPop .18s ease; color: #1d1d1f; }
    @keyframes tpPop { from { opacity: 0; transform: scale(.94); } to { opacity: 1; transform: scale(1); } }
    .tp-confirm-icon { width: 52px; height: 52px; margin: 0 auto 14px; display: grid; place-items: center; border-radius: 50%; background: rgba(255,59,48,.12); color: #c9342a; }
    .tp-confirm-icon svg { width: 26px; height: 26px; }
    .tp-confirm-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 8px; }
    .tp-confirm-text { font-size: 13px; line-height: 1.6; color: #3a3a3c; margin: 0; }
    .tp-confirm-text strong { word-break: break-word; }
    .tp-confirm-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 22px; }
    .tp-confirm-actions button { height: 40px; border: 0; border-radius: 10px; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; }
    .tp-confirm-cancel { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .tp-confirm-cancel:hover { background: rgba(120,120,128,.18); }
    .tp-confirm-ok { background: #ff3b30; color: #fff; }
    .tp-confirm-ok:hover { filter: brightness(1.06); }

    @media (max-width: 900px) { .tp-page .tp-admin { grid-template-columns: 1fr; } }
    @media (max-width: 560px) { .tp-page .tp-row { flex-direction: column; } }
    @media (prefers-reduced-motion: reduce) {
        .tp-page .tp-folder, .tp-page .tp-file-card, .tp-page .tp-preview img { transition: none; }
        .tp-page .tp-folder:hover, .tp-page .tp-file-card:hover { transform: none; }
    }
</style>

@php
    $user = Auth::user();
    $user?->loadMissing('peran');
    $isAdmin = in_array($user?->peran?->kode_peran ?? '', ['SUPER_ADMIN', 'ADMIN'], true);

    $icoWarn = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>';
@endphp

<div class="tp-page">

    {{-- PESAN ERROR DARI SERVER --}}
    @if ($errors->any())
        <div class="tp-alert error" role="alert">
            {!! $icoWarn !!}
            <div>@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
        </div>
    @endif

    {{-- KONTROL ADMIN --}}
    @if ($isAdmin)
        <div class="tp-admin">

            {{-- Buat folder --}}
            <div class="tp-card">
                <div class="tp-card-head">
                    <div class="tp-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M12 11v5M9.5 13.5h5"/></svg>
                    </div>
                    <div>
                        <h4 class="tp-card-title">Buat Folder Kategori</h4>
                        <p class="tp-card-desc">Contoh: Icon, Presentasi.</p>
                    </div>
                </div>

                <form action="{{ route('template.category.store') }}" method="POST" id="tpCreateFolder" novalidate>
                    @csrf
                    <div class="tp-row">
                        <div class="tp-grow">
                            <input type="text" name="nama_kategori" id="tpNamaBaru" class="tp-control" placeholder="Nama folder…"
                                   maxlength="100" value="{{ old('nama_kategori') }}" autocomplete="off" required>
                            <div class="tp-field-error" id="tpNamaBaruError" hidden></div>
                        </div>
                        <button type="submit" class="tp-btn">Buat Folder</button>
                    </div>
                </form>
            </div>

            {{-- Upload file --}}
            <div class="tp-card">
                <div class="tp-card-head">
                    <div class="tp-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5"/><path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/></svg>
                    </div>
                    <div>
                        <h4 class="tp-card-title">Upload File Template</h4>
                        <p class="tp-card-desc">Template, ikon, atau gambar.</p>
                    </div>
                </div>

                <form action="{{ route('template.item.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="tp-stack">
                        <div class="tp-row">
                            <select name="category_id" class="tp-control" required>
                                <option value="">Pilih folder tujuan</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $selectedCategory && $selectedCategory->id === $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" name="judul" class="tp-control" placeholder="Judul file / aset…" required>
                        </div>
                        <div class="tp-row">
                            <div class="tp-file"><input type="file" name="file" required></div>
                            <button type="submit" class="tp-btn">Upload</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    @endif

    {{-- FOLDER --}}
    <div class="tp-section">
        <h3 class="tp-section-title">
            <span>Kategori / Folder <span class="tp-count" id="tpFolderCount">{{ $categories->count() }} folder</span></span>

            <label class="tp-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input type="text" id="tpFolderSearch" placeholder="Cari folder…" autocomplete="off" aria-label="Cari folder">
            </label>
        </h3>

        <div class="tp-folders" id="tpFolders">

            {{-- Semua file --}}
            <div class="tp-folder-wrap" data-keep="1">
                <div class="tp-folder {{ !$selectedCategory ? 'active' : '' }}">
                    <a href="{{ route('template.index') }}" class="tp-folder-link">
                        <div class="tp-folder-icon all">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                        </div>
                        <div class="tp-folder-text">
                            <div class="tp-folder-name">Semua File</div>
                            <div class="tp-folder-meta">Tampilkan seluruh aset</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Folder kategori --}}
            @foreach ($categories as $cat)
                <div class="tp-folder-wrap" data-folder data-nama="{{ $cat->nama_kategori }}" data-id="{{ $cat->id }}">

                    <div class="tp-folder {{ $selectedCategory && $selectedCategory->id === $cat->id ? 'active' : '' }}">
                        <a href="{{ route('template.index', ['folder' => $cat->slug]) }}" class="tp-folder-link">
                            <div class="tp-folder-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                            </div>
                            <div class="tp-folder-text">
                                <div class="tp-folder-name" title="{{ $cat->nama_kategori }}">{{ $cat->nama_kategori }}</div>
                                <div class="tp-folder-meta">{{ $cat->items->count() }} file tersedia</div>
                            </div>
                        </a>

                        @if ($isAdmin)
                            <div class="tp-folder-menu">
                                <button type="button" class="tp-folder-menu-btn" data-action="toggle-menu"
                                        aria-label="Menu folder {{ $cat->nama_kategori }}" aria-haspopup="true" aria-expanded="false">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.8"/><circle cx="12" cy="12" r="1.8"/><circle cx="12" cy="19" r="1.8"/></svg>
                                </button>

                                <div class="tp-folder-dropdown" hidden>
                                    <button type="button" class="tp-folder-menu-item" data-action="start-rename">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                        Ganti nama
                                    </button>

                                    <form action="{{ route('template.category.destroy', $cat->id) }}" method="POST" class="js-confirm"
                                          data-confirm-title="Hapus folder?"
                                          data-confirm-name="{{ $cat->nama_kategori }}"
                                          data-confirm-text="akan dihapus. Tindakan ini tidak bisa dibatalkan."
                                          data-confirm-ok="Hapus Folder">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="tp-folder-menu-item danger">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v5M14 11v5"/></svg>
                                            Hapus folder
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($isAdmin)
                        <form action="{{ route('template.category.update', $cat->id) }}" method="POST" class="tp-folder-rename" novalidate>
                            @csrf
                            @method('PATCH')

                            <label for="rename-{{ $cat->id }}">Nama folder baru</label>
                            <input type="text" id="rename-{{ $cat->id }}" name="nama_kategori" class="tp-control"
                                   value="{{ $cat->nama_kategori }}" maxlength="100" autocomplete="off" required>
                            <div class="tp-field-error" hidden></div>

                            <div class="tp-folder-rename-actions">
                                <button type="button" class="tp-btn tp-btn-ghost" data-action="cancel-rename">Batal</button>
                                <button type="submit" class="tp-btn">Simpan</button>
                            </div>
                        </form>
                    @endif

                </div>
            @endforeach

            <div class="tp-folders-empty" id="tpFoldersEmpty" hidden>
                Folder tidak ditemukan. Coba kata kunci lain.
            </div>

        </div>
    </div>

    {{-- FILE --}}
    <div class="tp-section">
        <h3 class="tp-section-title">
            <span>File dalam folder: {{ $selectedCategory ? $selectedCategory->nama_kategori : 'Semua File' }}</span>
            <span class="tp-count">{{ $items->count() }} file</span>
        </h3>

        <div class="tp-files">
            @forelse ($items as $item)
                @php
                    $isImage = in_array(strtolower($item->tipe_file), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                    $fileUrl = Storage::url($item->file_path);
                @endphp

                <div class="tp-file-card">
                    <div class="tp-file-top">
                        <span class="tp-type">{{ $item->tipe_file ?? 'FILE' }}</span>

                        @if ($isAdmin)
                            <form action="{{ route('template.item.destroy', $item->id) }}" method="POST" class="tp-del-form js-confirm"
                                  data-confirm-title="Hapus file?"
                                  data-confirm-name="{{ $item->judul }}"
                                  data-confirm-text="akan dihapus permanen dari folder ini."
                                  data-confirm-ok="Hapus File">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="tp-del">Hapus</button>
                            </form>
                        @endif
                    </div>

                    <div class="tp-preview">
                        @if ($isImage)
                            <img src="{{ $fileUrl }}" alt="{{ $item->judul }}" loading="lazy">
                        @else
                            <div class="tp-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg>
                                <span>{{ strtoupper($item->tipe_file ?? 'FILE') }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="tp-file-body">
                        <h4 class="tp-file-name" title="{{ $item->judul }}">{{ $item->judul }}</h4>
                        <div class="tp-file-meta">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                            {{ $item->category->nama_kategori ?? '—' }}
                        </div>
                    </div>

                    <div class="tp-file-foot">
                        <button type="button" class="tp-act tp-act-view btn-preview"
                                data-url="{{ $fileUrl }}" data-title="{{ $item->judul }}" data-type="{{ strtolower($item->tipe_file) }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                            Lihat
                        </button>
                        <a href="{{ $fileUrl }}" download class="tp-act tp-act-dl">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v12M7 11l5 5 5-5M5 20h14"/></svg>
                            Download
                        </a>
                    </div>
                </div>
            @empty
                <div class="tp-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    <div>Belum ada file di folder ini.</div>
                </div>
            @endforelse
        </div>
    </div>

</div>

{{-- MODAL PREVIEW FILE --}}
<div class="tp-modal" id="tpModalPreview" role="dialog" aria-modal="true" aria-labelledby="tpModalTitle">
    <div class="tp-modal-box">
        <div class="tp-modal-head">
            <h3 class="tp-modal-title" id="tpModalTitle">Preview File</h3>
            <button type="button" class="tp-modal-close" id="tpModalClose" aria-label="Tutup preview">&times;</button>
        </div>
        <div class="tp-modal-body" id="tpModalContent"></div>
    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div class="tp-modal" id="tpConfirm" role="alertdialog" aria-modal="true" aria-labelledby="tpConfirmTitle">
    <div class="tp-confirm-box">
        <div class="tp-confirm-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6M10 11v6M14 11v6"/></svg>
        </div>
        <h3 class="tp-confirm-title" id="tpConfirmTitle">Konfirmasi</h3>
        <p class="tp-confirm-text"><strong id="tpConfirmName"></strong> <span id="tpConfirmText"></span></p>
        <div class="tp-confirm-actions">
            <button type="button" class="tp-confirm-cancel" id="tpConfirmCancel">Batal</button>
            <button type="button" class="tp-confirm-ok" id="tpConfirmOk">Hapus</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ============ Util ============ */
    const norm = function (s) { return (s || '').trim().replace(/\s+/g, ' ').toLowerCase(); };

    const wraps = Array.from(document.querySelectorAll('[data-folder]'));
    const namaFolder = wraps.map(function (w) { return { id: w.dataset.id, nama: norm(w.dataset.nama) }; });

    function adaKembar(nama, kecualiId) {
        const n = norm(nama);
        return namaFolder.some(function (f) { return f.nama === n && String(f.id) !== String(kecualiId || ''); });
    }

    function tampilError(input, box, pesan) {
        input.classList.add('invalid');
        box.textContent = pesan;
        box.hidden = false;
    }
    function hapusError(input, box) {
        input.classList.remove('invalid');
        box.hidden = true;
        box.textContent = '';
    }

    /* ============ Modal konfirmasi (pengganti confirm()) ============ */
    const cModal = document.getElementById('tpConfirm');
    const cTitle = document.getElementById('tpConfirmTitle');
    const cName = document.getElementById('tpConfirmName');
    const cText = document.getElementById('tpConfirmText');
    const cOk = document.getElementById('tpConfirmOk');
    let formTertunda = null;

    function tutupConfirm() { cModal.style.display = 'none'; formTertunda = null; }

    document.querySelectorAll('form.js-confirm').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            formTertunda = form;
            cTitle.textContent = form.dataset.confirmTitle || 'Konfirmasi';
            cName.textContent = form.dataset.confirmName ? '"' + form.dataset.confirmName + '"' : '';
            cText.textContent = form.dataset.confirmText || '';
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
        HTMLFormElement.prototype.submit.call(f); // kirim tanpa memicu listener lagi
    });
    document.getElementById('tpConfirmCancel').addEventListener('click', tutupConfirm);
    cModal.addEventListener('click', function (e) { if (e.target === cModal) tutupConfirm(); });

    /* ============ Pencarian folder ============ */
    const search = document.getElementById('tpFolderSearch');
    const counter = document.getElementById('tpFolderCount');
    const emptyBox = document.getElementById('tpFoldersEmpty');

    search.addEventListener('input', function () {
        const q = norm(this.value);
        let tampil = 0;

        wraps.forEach(function (w) {
            const cocok = !q || norm(w.dataset.nama).indexOf(q) !== -1;
            w.style.display = cocok ? '' : 'none';
            if (cocok) tampil++;
        });

        counter.textContent = (q ? tampil + ' dari ' + wraps.length : wraps.length) + ' folder';
        emptyBox.hidden = !(q && tampil === 0);
    });

    /* ============ Buat folder: cek nama kembar ============ */
    const formBuat = document.getElementById('tpCreateFolder');
    if (formBuat) {
        const input = document.getElementById('tpNamaBaru');
        const box = document.getElementById('tpNamaBaruError');

        input.addEventListener('input', function () { hapusError(input, box); });

        formBuat.addEventListener('submit', function (e) {
            const nama = input.value;

            if (!norm(nama)) {
                e.preventDefault();
                tampilError(input, box, 'Nama folder tidak boleh kosong.');
                input.focus();
                return;
            }
            if (adaKembar(nama)) {
                e.preventDefault();
                tampilError(input, box, 'Folder dengan nama "' + nama.trim() + '" sudah ada. Gunakan nama lain.');
                input.focus();
            }
        });
    }

    /* ============ Ganti nama folder: cek nama kembar ============ */
    document.querySelectorAll('.tp-folder-rename').forEach(function (form) {
        const wrap = form.closest('.tp-folder-wrap');
        const input = form.querySelector('input[name="nama_kategori"]');
        const box = form.querySelector('.tp-field-error');

        input.addEventListener('input', function () { hapusError(input, box); });

        form.addEventListener('submit', function (e) {
            const nama = input.value;

            if (!norm(nama)) {
                e.preventDefault();
                tampilError(input, box, 'Nama folder tidak boleh kosong.');
                return;
            }
            if (adaKembar(nama, wrap.dataset.id)) {
                e.preventDefault();
                tampilError(input, box, 'Nama "' + nama.trim() + '" sudah dipakai folder lain.');
                input.focus();
            }
        });
    });

    /* ============ Preview file ============ */
    const modal = document.getElementById('tpModalPreview');
    const modalTitle = document.getElementById('tpModalTitle');
    const modalContent = document.getElementById('tpModalContent');
    const imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];

    function tutupModal() {
        modal.style.display = 'none';
        modalContent.innerHTML = '';
    }

    document.querySelectorAll('.btn-preview').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const url = btn.dataset.url, title = btn.dataset.title, type = btn.dataset.type;

            modalTitle.textContent = title;
            modalContent.innerHTML = '';

            if (imageTypes.includes(type)) {
                const img = document.createElement('img');
                img.src = url; img.alt = title;
                modalContent.appendChild(img);
            } else if (type === 'pdf') {
                const iframe = document.createElement('iframe');
                iframe.src = url; iframe.title = title;
                modalContent.appendChild(iframe);
            } else {
                const box = document.createElement('div');
                box.className = 'tp-modal-fallback';
                box.innerHTML = '<div>Preview belum tersedia untuk file ini.<br>Download untuk membukanya.</div>';
                const link = document.createElement('a');
                link.href = url; link.setAttribute('download', '');
                link.className = 'tp-act tp-act-dl';
                link.textContent = 'Download';
                box.appendChild(link);
                modalContent.appendChild(box);
            }

            modal.style.display = 'flex';
        });
    });

    document.getElementById('tpModalClose').addEventListener('click', tutupModal);
    modal.addEventListener('click', function (e) { if (e.target === modal) tutupModal(); });

    /* ============ Menu folder: titik tiga & rename ============ */
    function closeAllMenus() {
        document.querySelectorAll('.tp-folder-dropdown').forEach(function (menu) {
            menu.hidden = true;
            const btn = menu.parentElement.querySelector('.tp-folder-menu-btn');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        });
    }

    function cancelAllRename() {
        document.querySelectorAll('.tp-folder-wrap.is-renaming').forEach(function (w) {
            w.classList.remove('is-renaming');
            const input = w.querySelector('.tp-folder-rename input');
            const box = w.querySelector('.tp-folder-rename .tp-field-error');
            if (input && box) { hapusError(input, box); input.value = w.dataset.nama; }
        });
    }

    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('[data-action]');

        if (!trigger) {
            if (!e.target.closest('.tp-folder-menu')) closeAllMenus();
            return;
        }

        const action = trigger.dataset.action;

        if (action === 'toggle-menu') {
            const menu = trigger.parentElement.querySelector('.tp-folder-dropdown');
            const open = menu.hidden;
            closeAllMenus();
            menu.hidden = !open;
            trigger.setAttribute('aria-expanded', String(open));
        }

        if (action === 'start-rename') {
            const wrap = trigger.closest('.tp-folder-wrap');
            closeAllMenus();
            cancelAllRename();
            wrap.classList.add('is-renaming');
            const input = wrap.querySelector('.tp-folder-rename input[name="nama_kategori"]');
            if (input) { input.focus(); input.select(); }
        }

        if (action === 'cancel-rename') {
            const wrap = trigger.closest('.tp-folder-wrap');
            wrap.classList.remove('is-renaming');
            const input = wrap.querySelector('.tp-folder-rename input');
            const box = wrap.querySelector('.tp-folder-rename .tp-field-error');
            hapusError(input, box);
            input.value = wrap.dataset.nama;
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        tutupModal();
        tutupConfirm();
        closeAllMenus();
        cancelAllRename();
    });
});
</script>

@endsection