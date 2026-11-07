@extends('layouts.app')

@section('title', 'Peminjaman Aset')
@section('page-title', 'Peminjaman Aset')
@section('page-description', 'Pilih aset yang tersedia untuk diajukan peminjaman')

@section('content')
@include('aset._mac-style')

<style>
    /* ---------- Katalog ---------- */
    .cat-bar {
        display: flex; justify-content: space-between; align-items: flex-end;
        gap: 24px; flex-wrap: wrap; margin-bottom: 32px;
    }
    .cat-count {
        display: inline-flex; align-items: center; gap: 6px; margin-top: 10px;
        padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 600;
        background: var(--accent-soft); color: #3b7aab;
    }

    .cat-search {
        display: flex; align-items: center; gap: 6px; width: 400px; max-width: 100%;
        padding: 5px 5px 5px 14px; border-radius: 999px;
        background: var(--surface); border: 1px solid var(--line); box-shadow: var(--shadow);
        transition: border-color .15s, box-shadow .15s;
    }
    .cat-search:focus-within { border-color: var(--accent); box-shadow: 0 0 0 4px var(--accent-soft); }
    .cat-search > svg { width: 16px; height: 16px; color: #8e8e93; flex-shrink: 0; }
    .cat-search input {
        flex: 1; min-width: 0; height: 34px; border: 0; outline: 0; background: transparent;
        font: inherit; font-size: 13px; color: var(--text);
    }
    .cat-search input::placeholder { color: #8e8e93; }
    .cat-search .cat-go {
        height: 34px; padding: 0 16px; border: 0; border-radius: 999px; cursor: pointer;
        background: var(--accent); color: #fff; font: inherit; font-size: 12px; font-weight: 600;
        transition: filter .15s;
    }
    .cat-search .cat-go:hover { filter: brightness(1.07); }
    .cat-reset {
        display: inline-grid; place-items: center; width: 30px; height: 30px; border-radius: 50%;
        background: rgba(120,120,128,.12); color: #3a3a3c; text-decoration: none; flex-shrink: 0;
        transition: background .15s;
    }
    .cat-reset:hover { background: rgba(120,120,128,.22); }
    .cat-reset svg { width: 12px; height: 12px; }

    .cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; margin-bottom: 28px; }

    .cat-card {
        display: flex; flex-direction: column; padding: 22px;
        background: var(--surface); border: 1px solid var(--line); border-radius: 22px;
        box-shadow: var(--shadow);
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }
    .cat-card:hover {
        transform: translateY(-4px); border-color: var(--accent);
        box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 18px 40px rgba(78,139,188,.18);
    }

    /* Efek Card Habis / Sold Out */
    .cat-card.is-sold-out {
        opacity: 0.6;
        background: #f7f7f9;
        filter: grayscale(80%);
    }
    .cat-card.is-sold-out:hover {
        transform: none;
        border-color: var(--line);
        box-shadow: var(--shadow);
    }
    .cat-sold-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 8px; border-radius: 999px; font-size: 11px; font-weight: 600;
        background: rgba(120,120,128,.16); color: #6e6e73;
    }
    .cat-cta.disabled {
        background: rgba(120,120,128,.15);
        color: #8e8e93;
        pointer-events: none;
        box-shadow: none;
    }

    .cat-name {
        font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0 0 8px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .cat-stock { display: flex; align-items: baseline; gap: 5px; margin-bottom: 16px; }
    .cat-stock b { font-size: 30px; font-weight: 700; letter-spacing: -.03em; line-height: 1; font-variant-numeric: tabular-nums; }
    .cat-stock span { font-size: 12px; color: var(--muted); font-weight: 500; }

    .cat-photo {
        position: relative; aspect-ratio: 4 / 3; border-radius: 16px; overflow: hidden;
        background: #f5f5f7; margin-bottom: 14px;
    }
    .cat-photo img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .35s ease; }
    .cat-card:hover .cat-photo img { transform: scale(1.04); }
    .cat-photo-empty { width: 100%; height: 100%; display: grid; place-items: center; align-content: center; gap: 6px; color: #b0b0b5; font-size: 11px; }
    .cat-photo-empty svg { width: 30px; height: 30px; }

    .cat-desc {
        font-size: 12px; line-height: 1.5; color: var(--muted); margin: 0 0 16px; min-height: 36px;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }

    .cat-meta {
        margin-top: auto; display: flex; justify-content: space-between; align-items: center; gap: 8px;
        padding-top: 14px; border-top: 1px solid var(--line);
        font-size: 12px; font-weight: 500; color: #6e6e73;
    }
    .cat-meta span { display: inline-flex; align-items: center; gap: 6px; }
    .cat-meta svg { width: 14px; height: 14px; color: #8e8e93; }
    .cat-meta .mac-cond { font-size: 12px; color: #6e6e73; }

    /* tombol muncul saat hover */
    .cat-cta {
        display: flex; align-items: center; justify-content: space-between;
        height: 0; margin-top: 0; padding: 0 16px; overflow: hidden; opacity: 0;
        border-radius: 12px; text-decoration: none; color: #fff; font-size: 13px; font-weight: 600;
        background: linear-gradient(180deg, #5b98c8, #4E8BBC);
        box-shadow: 0 6px 16px rgba(78,139,188,.28), inset 0 1px 0 rgba(255,255,255,.25);
        transition: height .2s ease, margin .2s ease, opacity .2s ease, filter .15s;
    }
    .cat-card:hover .cat-cta, .cat-cta:focus-visible { height: 42px; margin-top: 14px; opacity: 1; }
    .cat-cta:hover { filter: brightness(1.06); }
    .cat-cta i {
        display: grid; place-items: center; width: 26px; height: 26px; border-radius: 8px;
        background: rgba(255,255,255,.22); font-style: normal;
    }
    .cat-cta svg { width: 14px; height: 14px; }

    /* layar sentuh: tombol selalu tampil */
    @media (hover: none) {
        .cat-cta { height: 42px; margin-top: 14px; opacity: 1; }
    }

    .cat-empty {
        padding: 72px 20px; text-align: center; color: var(--muted); font-size: 13px;
        background: var(--surface); border: 1px solid var(--line); border-radius: 22px; box-shadow: var(--shadow);
    }
    .cat-empty svg { width: 40px; height: 40px; color: #c7c7cc; margin-bottom: 10px; }
    .cat-empty strong { display: block; font-size: 15px; color: var(--text); margin-bottom: 4px; }

    .cat-note { font-size: 12px; color: var(--muted); text-align: center; margin-bottom: 32px; }

    @media (max-width: 1280px) { .cat-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 960px)  { .cat-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 560px)  { .cat-grid { grid-template-columns: 1fr; } .cat-search { width: 100%; } }
</style>

<div class="mac-wrap">

    {{-- HEADER + SEARCH --}}
    <div class="cat-bar">
        <div>
            <h1 class="mac-title" style="font-size: 28px;"></h1>
            <p class="mac-sub"></p>
            <span class="cat-count">{{ $aset->count() }} aset ditampilkan</span>
        </div>

        <form action="{{ route('peminjaman.aset') }}" method="GET" class="cat-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama aset atau deskripsi…">

            @if (request('search'))
                <a href="{{ route('peminjaman.aset') }}" class="cat-reset" title="Reset">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                </a>
            @endif

            <button type="submit" class="cat-go">Cari</button>
        </form>
    </div>

    {{-- GRID KARTU --}}
    @forelse ($aset as $item)
        @if ($loop->first)
        <div class="cat-grid">
        @endif

            @php
                $kondisi = $item->kondisi ?? 'BAIK';
                $kondisiLabel = match ($kondisi) {
                    'RUSAK_RINGAN' => 'Rusak Ringan',
                    'RUSAK_BERAT'  => 'Rusak Berat',
                    default        => 'Baik',
                };
                $kondisiClass = match ($kondisi) {
                    'RUSAK_RINGAN' => 'cond-ringan',
                    'RUSAK_BERAT'  => 'cond-berat',
                    default        => 'cond-baik',
                };

                $stokTersedia = $item->stok_tersedia ?? $item->jumlah;
                $isHabis = $stokTersedia <= 0;
            @endphp

            <div class="cat-card {{ $isHabis ? 'is-sold-out' : '' }}">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; margin-bottom: 8px;">
                    <h3 class="cat-name" title="{{ $item->nama_aset }}" style="margin: 0;">{{ $item->nama_aset }}</h3>
                    @if ($isHabis)
                        <span class="cat-sold-badge">Habis</span>
                    @endif
                </div>

                <div class="cat-photo">
                    @if ($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_aset }}">
                    @else
                        <div class="cat-photo-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                            Tidak ada foto
                        </div>
                    @endif
                </div>

                <p class="cat-desc">{{ $item->deskripsi ?: 'Tidak ada deskripsi.' }}</p>

                <div class="cat-meta">
                    <span class="mac-cond {{ $kondisiClass }}">{{ $kondisiLabel }}</span>
                    <span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg>
                        {{ $stokTersedia }} unit
                    </span>
                </div>

                @if ($isHabis)
                    <div class="cat-cta disabled" style="height: 42px; margin-top: 14px; opacity: 1;">
                        Stok Habis
                        <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6L6 18M6 6l12 12"/></svg></i>
                    </div>
                @else
                    <a href="{{ route('peminjaman.aset.create', $item->id) }}" class="cat-cta">
                        Ajukan Peminjaman
                        <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></i>
                    </a>
                @endif
            </div>

        @if ($loop->last)
        </div>
        @endif
    @empty
        <div class="cat-empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <strong>Aset tidak ditemukan</strong>
            @if (request('search'))
                Tidak ada aset yang cocok dengan kata kunci "{{ request('search') }}".
            @else
                Belum ada aset yang tersedia untuk dipinjam.
            @endif
        </div>
    @endforelse

    <div class="cat-note">
        Ketersediaan stok untuk jadwal tertentu akan diverifikasi kembali saat pengajuan dan persetujuan.
    </div>

</div>

@endsection