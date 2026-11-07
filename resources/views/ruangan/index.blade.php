@extends('layouts.app')

@section('title', 'Kelola Ruangan | HumasIn')
@section('page-title', 'Kelola Ruangan')
@section('page-description', 'Kelola data ruangan dan fasilitas Humas DJPK')

@section('content')

<style>
    .rg-page {
        --rg-accent: #4E8BBC;
        --rg-accent-soft: rgba(78, 139, 188, .12);
        --rg-text: #1d1d1f;
        --rg-muted: #6e6e73;
        --rg-line: rgba(0, 0, 0, .07);
        --rg-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 1280px; margin: 0 auto; color: var(--rg-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .rg-page *, .rg-page *::before, .rg-page *::after { box-sizing: border-box; }

    /* Header */
    .rg-page .rg-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; margin-bottom: 28px; }
    .rg-page .rg-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .rg-page .rg-sub { font-size: 13px; color: var(--rg-muted); margin: 0; }

    .rg-page .rg-btn-primary {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 999px; border: 0;
        background: linear-gradient(180deg, #5b98c8, #4E8BBC); color: #fff; font: inherit; font-size: 13px; font-weight: 600;
        box-shadow: 0 1px 2px rgba(0,0,0,.12), 0 6px 16px rgba(78,139,188,.28), inset 0 1px 0 rgba(255,255,255,.25);
        transition: transform .15s ease, filter .15s ease; cursor: pointer;
    }
    .rg-page .rg-btn-primary:hover { filter: brightness(1.05); transform: translateY(-1px); }

    /* Statistik */
    .rg-page .rg-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
    .rg-page .rg-stat {
        display: flex; align-items: center; gap: 16px; padding: 20px; background: #fff;
        border: 1px solid var(--rg-line); border-radius: 16px; box-shadow: var(--rg-shadow);
    }
    .rg-page .rg-stat-icon { width: 44px; height: 44px; flex-shrink: 0; display: grid; place-items: center; border-radius: 12px; }
    .rg-page .rg-stat-icon svg { width: 22px; height: 22px; }
    .rg-page .rg-stat-label { font-size: 12px; font-weight: 500; color: var(--rg-muted); margin-bottom: 2px; }
    .rg-page .rg-stat-value { font-size: 28px; font-weight: 700; letter-spacing: -.03em; line-height: 1.1; font-variant-numeric: tabular-nums; }
    .rg-page .rg-tone-blue  { background: rgba(78,139,188,.14); color: #3b7aab; }
    .rg-page .rg-tone-green { background: rgba(52,199,89,.14);  color: #248a3d; }
    .rg-page .rg-tone-red   { background: rgba(255,59,48,.12);  color: #c9342a; }

    /* Panel */
    .rg-page .rg-panel { background: #fff; border: 1px solid var(--rg-line); border-radius: 18px; box-shadow: var(--rg-shadow); overflow: hidden; margin-bottom: 32px; }
    .rg-page .rg-toolbar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; padding: 16px 20px; border-bottom: 1px solid var(--rg-line); }
    .rg-page .rg-search { position: relative; flex: 1; min-width: 260px; }
    .rg-page .rg-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #8e8e93; pointer-events: none; }
    .rg-page .rg-input, .rg-page .rg-select {
        height: 38px; border-radius: 10px; border: 1px solid transparent; background: rgba(120,120,128,.1);
        color: var(--rg-text); font: inherit; font-size: 13px; outline: none; transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .rg-page .rg-input { width: 100%; padding: 0 14px 0 36px; }
    .rg-page .rg-input::placeholder { color: #8e8e93; }
    .rg-page .rg-select {
        width: 180px; padding: 0 32px 0 12px; appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center;
    }
    .rg-page .rg-input:focus, .rg-page .rg-select:focus { background-color: #fff; border-color: var(--rg-accent); box-shadow: 0 0 0 4px var(--rg-accent-soft); }

    .rg-page .rg-btn {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 18px; border-radius: 10px; border: 0;
        background: var(--rg-accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s;
    }
    .rg-page .rg-btn:hover { filter: brightness(1.06); }
    .rg-page .rg-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 16px; border-radius: 10px; border: 0;
        background: rgba(120,120,128,.1); color: #3a3a3c; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: background .15s;
    }
    .rg-page .rg-btn-ghost:hover { background: rgba(120,120,128,.18); }

    /* Tabel */
    .rg-page .rg-table-wrap { overflow-x: auto; }
    .rg-page .rg-table { width: 100%; min-width: 1080px; border-collapse: collapse; }
    .rg-page .rg-table thead th {
        padding: 12px 20px; text-align: left; background: #fafafc; font-size: 11px; font-weight: 600;
        letter-spacing: .06em; text-transform: uppercase; color: var(--rg-muted); border-bottom: 1px solid var(--rg-line); white-space: nowrap;
    }
    .rg-page .rg-table tbody td { padding: 14px 20px; vertical-align: middle; border-bottom: 1px solid var(--rg-line); font-size: 13px; }
    .rg-page .rg-table tbody tr:last-child td { border-bottom: 0; }
    .rg-page .rg-table tbody tr { transition: background .15s; }
    .rg-page .rg-table tbody tr:hover { background: #f7f9fc; }
    .rg-page .rg-center { text-align: center !important; }

    .rg-page .rg-no { color: var(--rg-muted); font-variant-numeric: tabular-nums; }
    .rg-page .rg-thumb { width: 76px; height: 52px; border-radius: 12px; object-fit: cover; display: block; border: 1px solid var(--rg-line); box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    .rg-page .rg-thumb-empty { width: 76px; height: 52px; border-radius: 12px; display: grid; place-items: center; background: rgba(120,120,128,.1); color: #8e8e93; }
    .rg-page .rg-thumb-empty svg { width: 20px; height: 20px; }
    .rg-page .rg-name { font-weight: 600; letter-spacing: -.005em; }
    .rg-page .rg-loc { display: inline-flex; align-items: center; gap: 6px; color: var(--rg-muted); }
    .rg-page .rg-loc svg { width: 14px; height: 14px; flex-shrink: 0; color: #8e8e93; }
    .rg-page .rg-num { font-variant-numeric: tabular-nums; font-weight: 500; color: #3a3a3c; }
    .rg-page .rg-unit { color: var(--rg-muted); font-weight: 400; }

    .rg-page .rg-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .rg-page .rg-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .rg-page .rg-badge-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .rg-page .rg-badge-red   { background: rgba(255,59,48,.12); color: #c9342a; }

    .rg-page .rg-actions { display: inline-flex; align-items: center; gap: 4px; }
    .rg-page .rg-act { padding: 6px 12px; border-radius: 8px; border: 0; background: transparent; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; transition: background .15s; }
    .rg-page .rg-act-detail { color: #3a3a3c; } .rg-page .rg-act-detail:hover { background: rgba(120,120,128,.14); }
    .rg-page .rg-act-edit   { color: #3b7aab; } .rg-page .rg-act-edit:hover   { background: var(--rg-accent-soft); }
    .rg-page .rg-act-del    { color: #c9342a; } .rg-page .rg-act-del:hover    { background: rgba(255,59,48,.12); }

    .rg-page .rg-empty { padding: 64px 20px !important; text-align: center; color: var(--rg-muted); font-size: 13px; }
    .rg-page .rg-empty svg { width: 36px; height: 36px; margin-bottom: 10px; color: #c7c7cc; }

    /* Modal hapus */
    .rg-page .rg-modal {
        display: none; position: fixed; inset: 0; z-index: 99999; align-items: center; justify-content: center; padding: 20px;
        background: rgba(0,0,0,.32); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
    }
    .rg-page .rg-modal-box {
        width: 400px; max-width: 100%; padding: 28px 26px 22px; text-align: center; background: rgba(255,255,255,.96);
        border-radius: 20px; box-shadow: 0 0 0 1px rgba(0,0,0,.06), 0 24px 60px rgba(0,0,0,.25); animation: rgPop .18s ease;
    }
    @keyframes rgPop { from { opacity: 0; transform: scale(.94); } to { opacity: 1; transform: scale(1); } }
    .rg-page .rg-modal-icon { width: 52px; height: 52px; margin: 0 auto 14px; display: grid; place-items: center; border-radius: 50%; background: rgba(255,59,48,.12); color: #c9342a; }
    .rg-page .rg-modal-icon svg { width: 26px; height: 26px; }
    .rg-page .rg-modal-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 8px; }
    .rg-page .rg-modal-text { font-size: 13px; line-height: 1.6; color: #3a3a3c; margin: 0; }
    .rg-page .rg-modal-warn { display: block; margin-top: 6px; font-size: 12px; color: #c9342a; }
    .rg-page .rg-modal-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 22px; }
    .rg-page .rg-modal-actions .rg-btn, .rg-page .rg-modal-actions .rg-btn-ghost { width: 100%; height: 40px; }
    .rg-page .rg-btn-danger { background: #ff3b30; }

    @media (max-width: 800px) { .rg-page .rg-stats { grid-template-columns: 1fr; } }
</style>

<div class="rg-page">

    {{-- HEADER --}}
    <div class="rg-head">
        <div>
            <h2 class="rg-title"></h2>
            <p class="rg-sub"></p>
        </div>
        <a href="{{ route('ruangan.create') }}" class="rg-btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            Tambah Ruangan
        </a>
    </div>

    {{-- STATISTIK --}}
    <div class="rg-stats">
        <div class="rg-stat">
            <div class="rg-stat-icon rg-tone-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/><path d="M16 9h2a2 2 0 0 1 2 2v10M2 21h20M9 7h2M9 11h2M9 15h2"/></svg>
            </div>
            <div>
                <div class="rg-stat-label">Total Ruangan</div>
                <div class="rg-stat-value">{{ $totalRuangan }}</div>
            </div>
        </div>

        <div class="rg-stat">
            <div class="rg-stat-icon rg-tone-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 3 3 5-6"/></svg>
            </div>
            <div>
                <div class="rg-stat-label">Tersedia</div>
                <div class="rg-stat-value">{{ $ruanganTersedia }}</div>
            </div>
        </div>

        <div class="rg-stat">
            <div class="rg-stat-icon rg-tone-red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m15 9-6 6M9 9l6 6"/></svg>
            </div>
            <div>
                <div class="rg-stat-label">Tidak Tersedia</div>
                <div class="rg-stat-value">{{ $ruanganTidakTersedia }}</div>
            </div>
        </div>
    </div>

    {{-- PANEL TABEL --}}
    <div class="rg-panel">

        <form method="GET" action="{{ route('ruangan.index') }}" class="rg-toolbar">
            <div class="rg-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input type="text" name="search" class="rg-input" value="{{ request('search') }}" placeholder="Cari nama ruangan, lokasi, atau deskripsi…">
            </div>

            <select name="status" class="rg-select">
                <option value="">Semua Status</option>
                <option value="TERSEDIA" {{ request('status') === 'TERSEDIA' ? 'selected' : '' }}>Tersedia</option>
                <option value="TIDAK_TERSEDIA" {{ request('status') === 'TIDAK_TERSEDIA' ? 'selected' : '' }}>Tidak Tersedia</option>
            </select>

            <button type="submit" class="rg-btn">Cari</button>

            @if (request('search') || request('status'))
                <a href="{{ route('ruangan.index') }}" class="rg-btn-ghost">Reset</a>
            @endif
        </form>

        <div class="rg-table-wrap">
            <table class="rg-table">
                <thead>
                    <tr>
                        <th style="width: 56px;">No</th>
                        <th style="width: 100px;">Preview</th>
                        <th>Nama Ruangan</th>
                        <th>Lokasi</th>
                        <th>Kapasitas</th>
                        <th class="rg-center">Kuota</th>
                        <th>Durasi Maks.</th>
                        <th>Status</th>
                        <th class="rg-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ruangan as $index => $item)
                        <tr>
                            <td class="rg-no">{{ $index + 1 }}</td>

                            <td>
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_ruangan }}" class="rg-thumb">
                                @else
                                    <div class="rg-thumb-empty">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                    </div>
                                @endif
                            </td>

                            <td class="rg-name">{{ $item->nama_ruangan }}</td>

                            <td>
                                <span class="rg-loc">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    {{ $item->lokasi ?? '—' }}
                                </span>
                            </td>

                            <td class="rg-num">{{ $item->kapasitas }} <span class="rg-unit">orang</span></td>

                            <td class="rg-center rg-num">{{ $item->kuota }}</td>

                            <td class="rg-num">
                                {{ $item->durasi_maksimal }}
                                <span class="rg-unit">{{ strtolower($item->satuan_durasi) }}</span>
                            </td>

                            <td>
                                @if ($item->status === 'TERSEDIA')
                                    <span class="rg-badge rg-badge-green">Tersedia</span>
                                @else
                                    <span class="rg-badge rg-badge-red">Tidak Tersedia</span>
                                @endif
                            </td>

                            <td class="rg-center" style="white-space: nowrap;">
                                <div class="rg-actions">
                                    <a href="{{ route('ruangan.show', $item) }}" class="rg-act rg-act-detail">Detail</a>
                                    <a href="{{ route('ruangan.edit', $item) }}" class="rg-act rg-act-edit">Edit</a>

                                    @if (auth()->user()?->peran?->kode_peran === 'SUPER_ADMIN')
                                        <button type="button" class="rg-act rg-act-del btn-delete-ruangan"
                                            data-id="{{ $item->id }}" data-nama="{{ $item->nama_ruangan }}">Hapus</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="rg-empty">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/><path d="M16 9h2a2 2 0 0 1 2 2v10M2 21h20"/></svg>
                                <div>
                                    @if (request('search') || request('status'))
                                        Data ruangan tidak ditemukan.
                                    @else
                                        Belum ada data ruangan.
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL HAPUS --}}
    <div id="deleteModal" class="rg-modal">
        <div class="rg-modal-box">
            <div class="rg-modal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6M10 11v6M14 11v6"/></svg>
            </div>

            <h3 class="rg-modal-title">Hapus Ruangan?</h3>
            <p class="rg-modal-text">
                Apakah Anda yakin ingin menghapus ruangan
                <strong id="deleteRoomName">-</strong>?
                <span class="rg-modal-warn">Data ruangan yang dihapus tidak dapat dikembalikan.</span>
            </p>

            <form id="deleteRoomForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="rg-modal-actions">
                    <button type="button" id="btnCancelDelete" class="rg-btn-ghost">Batal</button>
                    <button type="submit" class="rg-btn rg-btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal        = document.getElementById('deleteModal');
    const form         = document.getElementById('deleteRoomForm');
    const roomName     = document.getElementById('deleteRoomName');
    const cancelButton = document.getElementById('btnCancelDelete');

    document.querySelectorAll('.btn-delete-ruangan').forEach(function (button) {
        button.addEventListener('click', function () {
            roomName.textContent = this.getAttribute('data-nama');
            form.action = '/ruangan/' + this.getAttribute('data-id');
            modal.style.display = 'flex';
        });
    });

    cancelButton.addEventListener('click', function () { modal.style.display = 'none'; });
    modal.addEventListener('click', function (e) { if (e.target === modal) modal.style.display = 'none'; });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') modal.style.display = 'none'; });
});
</script>

@endsection