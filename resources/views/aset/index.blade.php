@extends('layouts.app')

@section('title', 'Kelola Aset')
@section('page-title', 'Kelola Aset')
@section('page-description', 'Kelola data barang dan peralatan Humas DJPK')

@section('content')

<style>
    .mac-wrap {
        --accent: #4E8BBC;
        --accent-soft: rgba(78, 139, 188, .12);
        --text: #1d1d1f;
        --muted: #6e6e73;
        --line: rgba(0, 0, 0, .07);
        --surface: #ffffff;
        --shadow: 0 1px 2px rgba(0, 0, 0, .04), 0 8px 24px rgba(0, 0, 0, .04);

        max-width: 1280px;
        margin: 0 auto;
        color: var(--text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", Roboto, sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .mac-wrap * { box-sizing: border-box; }

    /* ---------- Header ---------- */
    .mac-head {
        display: flex; justify-content: space-between; align-items: flex-end;
        margin-bottom: 28px; gap: 16px;
    }
    .mac-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .mac-sub { font-size: 13px; color: var(--muted); margin: 0; }

    .mac-btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 18px; border-radius: 999px; border: 0;
        background: linear-gradient(180deg, #5b98c8, #4E8BBC);
        color: #fff; font-size: 13px; font-weight: 600; text-decoration: none;
        box-shadow: 0 1px 2px rgba(0,0,0,.12), 0 6px 16px rgba(78,139,188,.28), inset 0 1px 0 rgba(255,255,255,.25);
        transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
        cursor: pointer;
    }
    .mac-btn-primary:hover { filter: brightness(1.05); transform: translateY(-1px); }
    .mac-btn-primary:active { transform: translateY(0); }

    /* ---------- Alert ---------- */
    .mac-alert {
        display: flex; gap: 10px; align-items: center;
        margin-bottom: 20px; padding: 12px 16px; border-radius: 12px;
        background: rgba(255, 59, 48, .08); border: 1px solid rgba(255, 59, 48, .18);
        color: #b42318; font-size: 13px;
    }

    /* ---------- Stat cards ---------- */
    .mac-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
    .mac-stat {
        display: flex; align-items: center; gap: 16px;
        padding: 20px; background: var(--surface);
        border: 1px solid var(--line); border-radius: 16px; box-shadow: var(--shadow);
    }
    .mac-stat-icon {
        width: 44px; height: 44px; flex-shrink: 0;
        display: grid; place-items: center; border-radius: 12px;
    }
    .mac-stat-icon svg { width: 22px; height: 22px; }
    .mac-stat-label { font-size: 12px; font-weight: 500; color: var(--muted); margin-bottom: 2px; }
    .mac-stat-value { font-size: 28px; font-weight: 700; letter-spacing: -.03em; line-height: 1.1; font-variant-numeric: tabular-nums; }

    .tone-blue   { background: rgba(78,139,188,.14); color: #3b7aab; }
    .tone-indigo { background: rgba(88,86,214,.12);  color: #5856d6; }
    .tone-green  { background: rgba(52,199,89,.14);  color: #248a3d; }
    .tone-amber  { background: rgba(255,159,10,.15); color: #c77700; }

    /* ---------- Panel ---------- */
    .mac-panel {
        background: var(--surface); border: 1px solid var(--line);
        border-radius: 18px; box-shadow: var(--shadow); overflow: hidden; margin-bottom: 32px;
    }

    .mac-toolbar {
        display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
        padding: 16px 20px; border-bottom: 1px solid var(--line);
    }
    .mac-search { position: relative; flex: 1; min-width: 260px; }
    .mac-search svg {
        position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
        width: 16px; height: 16px; color: #8e8e93; pointer-events: none;
    }
    .mac-input, .mac-select {
        height: 38px; border-radius: 10px; border: 1px solid transparent;
        background: rgba(120,120,128,.1); color: var(--text);
        font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .mac-input { width: 100%; padding: 0 14px 0 36px; }
    .mac-input::placeholder { color: #8e8e93; }
    .mac-select {
        width: 180px; padding: 0 32px 0 12px; appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center;
    }
    .mac-input:focus, .mac-select:focus {
        background-color: #fff; border-color: var(--accent);
        box-shadow: 0 0 0 4px var(--accent-soft);
    }
    .mac-btn {
        height: 38px; padding: 0 18px; border-radius: 10px; border: 0;
        background: var(--accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600;
        cursor: pointer; transition: filter .15s;
    }
    .mac-btn:hover { filter: brightness(1.06); }
    .mac-btn-ghost {
        display: inline-flex; align-items: center; height: 38px; padding: 0 16px; border-radius: 10px;
        background: rgba(120,120,128,.1); color: #3a3a3c; font-size: 13px; font-weight: 600; text-decoration: none;
        transition: background .15s;
    }
    .mac-btn-ghost:hover { background: rgba(120,120,128,.18); }

    /* ---------- Table ---------- */
    .mac-table-wrap { overflow-x: auto; }
    .mac-table { width: 100%; border-collapse: collapse; min-width: 960px; }
    .mac-table thead th {
        padding: 12px 20px; text-align: left; background: #fafafc;
        font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase;
        color: var(--muted); border-bottom: 1px solid var(--line); white-space: nowrap;
    }
    .mac-table tbody td { padding: 14px 20px; vertical-align: middle; border-bottom: 1px solid var(--line); font-size: 13px; }
    .mac-table tbody tr:last-child td { border-bottom: 0; }
    .mac-table tbody tr { transition: background .15s; }
    .mac-table tbody tr:hover { background: #f7f9fc; }
    .t-center { text-align: center !important; }

    .mac-thumb {
        width: 46px; height: 46px; border-radius: 12px; object-fit: cover; display: block;
        border: 1px solid var(--line); box-shadow: 0 1px 3px rgba(0,0,0,.08);
    }
    .mac-thumb-empty {
        width: 46px; height: 46px; border-radius: 12px; display: grid; place-items: center;
        background: rgba(120,120,128,.1); color: #8e8e93;
    }
    .mac-thumb-empty svg { width: 20px; height: 20px; }

    .mac-name { font-weight: 600; letter-spacing: -.005em; }
    .mac-desc { color: var(--muted); max-width: 300px; line-height: 1.45; }
    .mac-qty { font-weight: 600; font-variant-numeric: tabular-nums; }

    /* Badge status */
    .mac-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap;
    }
    .mac-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .badge-green { background: rgba(52,199,89,.14);  color: #248a3d; }
    .badge-amber { background: rgba(255,159,10,.16); color: #b26a00; }
    .badge-red   { background: rgba(255,59,48,.12);  color: #c9342a; }

    /* Kondisi */
    .mac-cond { display: inline-flex; align-items: center; gap: 8px; color: #3a3a3c; font-weight: 500; }
    .mac-cond::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: var(--dot); }
    .cond-baik   { --dot: #34c759; }
    .cond-ringan { --dot: #ff9f0a; }
    .cond-berat  { --dot: #ff3b30; }

    /* Aksi */
    .mac-actions { display: inline-flex; align-items: center; gap: 4px; }
    .mac-act {
        padding: 6px 12px; border-radius: 8px; border: 0; background: transparent;
        font: inherit; font-size: 12px; font-weight: 600; text-decoration: none; cursor: pointer;
        transition: background .15s, color .15s;
    }
    .act-detail { color: #3a3a3c; }
    .act-detail:hover { background: rgba(120,120,128,.14); }
    .act-edit { color: #3b7aab; }
    .act-edit:hover { background: var(--accent-soft); }
    .act-del { color: #c9342a; }
    .act-del:hover { background: rgba(255,59,48,.12); }

    /* Empty */
    .mac-empty { padding: 64px 20px; text-align: center; color: var(--muted); font-size: 13px; }
    .mac-empty svg { width: 36px; height: 36px; margin-bottom: 10px; color: #c7c7cc; }

    /* Footer */
    .mac-foot {
        display: flex; justify-content: space-between; align-items: center; gap: 12px;
        padding: 14px 20px; border-top: 1px solid var(--line); background: #fafafc;
        font-size: 12px; color: var(--muted);
    }

    @media (max-width: 1100px) { .mac-stats { grid-template-columns: repeat(2, 1fr); } }
</style>

<div class="mac-wrap">

    {{-- HEADER --}}
    <div class="mac-head">
        <div>
            <h2 class="mac-title"></h2>
            <p class="mac-sub"></p>
        </div>
        <a href="{{ route('aset.create') }}" class="mac-btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Aset
        </a>
    </div>

    {{-- ERROR --}}
    @if (session('error'))
        <div class="mac-alert">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- STATISTIK --}}
    <div class="mac-stats">
        <div class="mac-stat">
            <div class="mac-stat-icon tone-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg>
            </div>
            <div>
                <div class="mac-stat-label">Total Jenis Aset</div>
                <div class="mac-stat-value">{{ $totalAset }}</div>
            </div>
        </div>

        <div class="mac-stat">
            <div class="mac-stat-icon tone-indigo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 9 5-9 5-9-5z"/><path d="m3 13 9 5 9-5"/></svg>
            </div>
            <div>
                <div class="mac-stat-label">Total Unit</div>
                <div class="mac-stat-value">{{ $totalUnit }}</div>
            </div>
        </div>

        <div class="mac-stat">
            <div class="mac-stat-icon tone-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 3 3 5-6"/></svg>
            </div>
            <div>
                <div class="mac-stat-label">Aset Tersedia</div>
                <div class="mac-stat-value">{{ $asetTersedia }}</div>
            </div>
        </div>

        <div class="mac-stat">
            <div class="mac-stat-icon tone-amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.4 2.4-2.6-.6-.6-2.6z"/></svg>
            </div>
            <div>
                <div class="mac-stat-label">Aset Perbaikan</div>
                <div class="mac-stat-value">{{ $asetPerbaikan }}</div>
            </div>
        </div>
    </div>

    {{-- PANEL TABEL --}}
    <div class="mac-panel">

        {{-- TOOLBAR --}}
        <form method="GET" action="{{ route('aset.index') }}" class="mac-toolbar">
            <div class="mac-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input
                    type="text" name="search" class="mac-input"
                    value="{{ request('search') }}"
                    placeholder="Cari nama aset, deskripsi, status, atau kondisi…"
                >
            </div>

            <select name="status" class="mac-select">
                <option value="">Semua Status</option>
                <option value="TERSEDIA" @selected(request('status') === 'TERSEDIA')>Tersedia</option>
                <option value="TIDAK_TERSEDIA" @selected(request('status') === 'TIDAK_TERSEDIA')>Tidak Tersedia</option>
                <option value="PERBAIKAN" @selected(request('status') === 'PERBAIKAN')>Perbaikan</option>
            </select>

            <button type="submit" class="mac-btn">Cari</button>

            @if (request()->filled('search') || request()->filled('status'))
                <a href="{{ route('aset.index') }}" class="mac-btn-ghost">Reset</a>
            @endif
        </form>

        {{-- TABEL --}}
        <div class="mac-table-wrap">
            <table class="mac-table">
                <thead>
                    <tr>
                        <th style="width: 86px;">Foto</th>
                        <th>Nama Barang</th>
                        <th>Deskripsi</th>
                        <th class="t-center">Jumlah</th>
                        <th>Status</th>
                        <th>Kondisi</th>
                        <th class="t-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($aset as $item)
                        <tr>
                            {{-- FOTO --}}
                            <td>
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_aset }}" class="mac-thumb">
                                @else
                                    <div class="mac-thumb-empty">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                    </div>
                                @endif
                            </td>

                            {{-- NAMA --}}
                            <td class="mac-name">{{ $item->nama_aset }}</td>

                            {{-- DESKRIPSI --}}
                            <td class="mac-desc">
                                {{ $item->deskripsi ? \Illuminate\Support\Str::limit($item->deskripsi, 80) : '—' }}
                            </td>

                            {{-- JUMLAH --}}
                            <td class="t-center mac-qty">{{ $item->jumlah }}</td>

                            {{-- STATUS --}}
                            <td>
                                @if ($item->status === 'TERSEDIA')
                                    <span class="mac-badge badge-green">Tersedia</span>
                                @elseif ($item->status === 'PERBAIKAN')
                                    <span class="mac-badge badge-amber">Perbaikan</span>
                                @else
                                    <span class="mac-badge badge-red">Tidak Tersedia</span>
                                @endif
                            </td>

                            {{-- KONDISI --}}
                            <td>
                                @if ($item->kondisi === 'BAIK')
                                    <span class="mac-cond cond-baik">Baik</span>
                                @elseif ($item->kondisi === 'RUSAK_RINGAN')
                                    <span class="mac-cond cond-ringan">Rusak Ringan</span>
                                @else
                                    <span class="mac-cond cond-berat">Rusak Berat</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="t-center" style="white-space: nowrap;">
                                <div class="mac-actions">
                                    <a href="{{ route('aset.show', $item) }}" class="mac-act act-detail">Detail</a>
                                    <a href="{{ route('aset.edit', $item) }}" class="mac-act act-edit">Edit</a>

                                    <form action="{{ route('aset.destroy', $item) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus aset ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mac-act act-del">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="mac-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg>
                                <div>
                                    @if (request()->filled('search') || request()->filled('status'))
                                        Tidak ada aset yang sesuai dengan kriteria pencarian atau filter Anda.
                                    @else
                                        Belum ada data aset yang terdaftar.
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- FOOTER + PAGINATION --}}
        @if ($aset->hasPages())
            <div class="mac-foot">
                <span>Menampilkan {{ $aset->firstItem() }}–{{ $aset->lastItem() }} dari {{ $aset->total() }} aset</span>
                <div>{{ $aset->links() }}</div>
            </div>
        @endif

    </div>

</div>

@endsection