@extends('layouts.app')

@section('title', 'Karyawan | HumasIn')
@section('page-title', 'Karyawan')
@section('page-description', 'Kelola data karyawan dan unit kerja Humas DJPK')

@section('content')

<style>
    .kw-page {
        --kw-accent: #4E8BBC;
        --kw-accent-soft: rgba(78, 139, 188, .12);
        --kw-text: #1d1d1f;
        --kw-muted: #6e6e73;
        --kw-line: rgba(0, 0, 0, .07);
        --kw-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 1280px; margin: 0 auto; color: var(--kw-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .kw-page *, .kw-page *::before, .kw-page *::after { box-sizing: border-box; }

    /* Header */
    .kw-page .kw-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; margin-bottom: 24px; }
    .kw-page .kw-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .kw-page .kw-sub { font-size: 13px; color: var(--kw-muted); margin: 0; }

    .kw-page .kw-btn-primary {
        display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 999px; border: 0;
        background: linear-gradient(180deg, #5b98c8, #4E8BBC); color: #fff; font: inherit; font-size: 13px; font-weight: 600;
        box-shadow: 0 1px 2px rgba(0,0,0,.12), 0 6px 16px rgba(78,139,188,.28), inset 0 1px 0 rgba(255,255,255,.25);
        transition: transform .15s ease, filter .15s ease; cursor: pointer;
    }
    .kw-page .kw-btn-primary:hover { filter: brightness(1.05); transform: translateY(-1px); }

    /* Tab (segmented control) */
    .kw-page .kw-tabs { display: inline-flex; gap: 2px; padding: 3px; margin-bottom: 20px; border-radius: 11px; background: rgba(120,120,128,.12); }
    .kw-page .kw-tab {
        padding: 7px 22px; border-radius: 8px; font-size: 13px; font-weight: 600; color: var(--kw-muted);
        transition: background .15s, color .15s, box-shadow .15s;
    }
    .kw-page .kw-tab:hover { color: var(--kw-text); }
    .kw-page .kw-tab.active { background: #fff; color: var(--kw-text); box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 0 0 .5px rgba(0,0,0,.04); }

    /* Panel */
    .kw-page .kw-panel { background: #fff; border: 1px solid var(--kw-line); border-radius: 18px; box-shadow: var(--kw-shadow); overflow: hidden; margin-bottom: 32px; }
    .kw-page .kw-panel-head { display: flex; justify-content: space-between; align-items: center; padding: 18px 20px 4px; }
    .kw-page .kw-panel-title { font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0; }
    .kw-page .kw-count {
        display: inline-flex; align-items: center; padding: 3px 11px; border-radius: 999px; font-size: 12px; font-weight: 600;
        background: var(--kw-accent-soft); color: #3b7aab;
    }

    .kw-page .kw-toolbar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; padding: 14px 20px 16px; border-bottom: 1px solid var(--kw-line); }
    .kw-page .kw-search { position: relative; flex: 1; min-width: 260px; }
    .kw-page .kw-search svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #8e8e93; pointer-events: none; }
    .kw-page .kw-input {
        width: 100%; height: 38px; padding: 0 14px 0 36px; border-radius: 10px; border: 1px solid transparent;
        background: rgba(120,120,128,.1); color: var(--kw-text); font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .kw-page .kw-input::placeholder { color: #8e8e93; }
    .kw-page .kw-input:focus { background: #fff; border-color: var(--kw-accent); box-shadow: 0 0 0 4px var(--kw-accent-soft); }

    .kw-page .kw-btn {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 18px; border-radius: 10px; border: 0;
        background: var(--kw-accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s;
    }
    .kw-page .kw-btn:hover { filter: brightness(1.06); }
    .kw-page .kw-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 16px; border-radius: 10px; border: 0;
        background: rgba(120,120,128,.1); color: #3a3a3c; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: background .15s;
    }
    .kw-page .kw-btn-ghost:hover { background: rgba(120,120,128,.18); }

    /* Tabel */
    .kw-page .kw-table-wrap { overflow-x: auto; }
    .kw-page .kw-table { width: 100%; border-collapse: collapse; }
    .kw-page .kw-table.kw-wide { min-width: 1100px; }
    .kw-page .kw-table.kw-narrow { min-width: 640px; }
    .kw-page .kw-table thead th {
        padding: 12px 20px; text-align: left; background: #fafafc; font-size: 11px; font-weight: 600;
        letter-spacing: .06em; text-transform: uppercase; color: var(--kw-muted); border-bottom: 1px solid var(--kw-line); white-space: nowrap;
    }
    .kw-page .kw-table tbody td { padding: 14px 20px; vertical-align: middle; border-bottom: 1px solid var(--kw-line); font-size: 13px; }
    .kw-page .kw-table tbody tr:last-child td { border-bottom: 0; }
    .kw-page .kw-table tbody tr { transition: background .15s; }
    .kw-page .kw-table tbody tr:hover { background: #f7f9fc; }
    .kw-page .kw-center { text-align: center !important; }

    .kw-page .kw-no { color: var(--kw-muted); font-variant-numeric: tabular-nums; }
    .kw-page .kw-mono { font-variant-numeric: tabular-nums; font-weight: 600; letter-spacing: .01em; }
    .kw-page .kw-muted { color: var(--kw-muted); }
    .kw-page .kw-person { display: flex; align-items: center; gap: 12px; }
    .kw-page .kw-avatar { width: 34px; height: 34px; flex-shrink: 0; display: grid; place-items: center; border-radius: 50%; background: #C2E1FC; color: #2b5c8f; font-size: 12px; font-weight: 700; }
    .kw-page .kw-name { font-weight: 600; letter-spacing: -.005em; }

    .kw-page .kw-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .kw-page .kw-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .kw-page .kw-badge-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .kw-page .kw-badge-red   { background: rgba(255,59,48,.12); color: #c9342a; }

    .kw-page .kw-actions { display: inline-flex; align-items: center; gap: 4px; }
    .kw-page .kw-act { display: inline-block; padding: 6px 12px; border-radius: 8px; border: 0; background: transparent; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; transition: background .15s; }
    .kw-page .kw-act-detail { color: #3a3a3c; } .kw-page .kw-act-detail:hover { background: rgba(120,120,128,.14); }
    .kw-page .kw-act-edit   { color: #3b7aab; } .kw-page .kw-act-edit:hover   { background: var(--kw-accent-soft); }
    .kw-page .kw-act-del    { color: #c9342a; } .kw-page .kw-act-del:hover    { background: rgba(255,59,48,.12); }

    .kw-page .kw-empty { padding: 64px 20px !important; text-align: center; color: var(--kw-muted); font-size: 13px; }
    .kw-page .kw-foot { display: flex; justify-content: flex-end; padding: 14px 20px; border-top: 1px solid var(--kw-line); background: #fafafc; }

    /* Modal hapus */
    .kw-page .kw-modal {
        display: none; position: fixed; inset: 0; z-index: 99999; align-items: center; justify-content: center; padding: 20px;
        background: rgba(0,0,0,.32); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
    }
    .kw-page .kw-modal-box {
        width: 400px; max-width: 100%; padding: 28px 26px 22px; text-align: center; background: rgba(255,255,255,.96);
        border-radius: 20px; box-shadow: 0 0 0 1px rgba(0,0,0,.06), 0 24px 60px rgba(0,0,0,.25); animation: kwPop .18s ease;
    }
    @keyframes kwPop { from { opacity: 0; transform: scale(.94); } to { opacity: 1; transform: scale(1); } }
    .kw-page .kw-modal-icon { width: 52px; height: 52px; margin: 0 auto 14px; display: grid; place-items: center; border-radius: 50%; background: rgba(255,59,48,.12); color: #c9342a; }
    .kw-page .kw-modal-icon svg { width: 26px; height: 26px; }
    .kw-page .kw-modal-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 8px; }
    .kw-page .kw-modal-text { font-size: 13px; line-height: 1.6; color: #3a3a3c; margin: 0; }
    .kw-page .kw-modal-warn { display: block; margin-top: 6px; font-size: 12px; color: #c9342a; }
    .kw-page .kw-modal-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 22px; }
    .kw-page .kw-modal-actions .kw-btn, .kw-page .kw-modal-actions .kw-btn-ghost { width: 100%; height: 40px; }
    .kw-page .kw-btn-danger { background: #ff3b30; }
</style>

<div class="kw-page">

    {{-- HEADER --}}
    <div class="kw-head">
        <div>
            <h2 class="kw-title">Data Karyawan</h2>
            <p class="kw-sub">Kelola data karyawan dan unit kerja Humas DJPK.</p>
        </div>

        @if ($tab === 'karyawan')
            <a href="{{ route('karyawan.create') }}" class="kw-btn-primary">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                Tambah Karyawan
            </a>
        @endif
    </div>

    {{-- TAB --}}
    <div class="kw-tabs">
        <a href="{{ route('karyawan.index', ['tab' => 'karyawan']) }}" class="kw-tab {{ $tab === 'karyawan' ? 'active' : '' }}">Karyawan</a>
        <a href="{{ route('karyawan.index', ['tab' => 'unit']) }}" class="kw-tab {{ $tab === 'unit' ? 'active' : '' }}">Unit</a>
    </div>

    {{-- ===================== TAB KARYAWAN ===================== --}}
    @if ($tab === 'karyawan')

        <div class="kw-panel">

            <div class="kw-panel-head">
                <h3 class="kw-panel-title">Daftar Karyawan</h3>
                <span class="kw-count">{{ $karyawan->count() }} karyawan</span>
            </div>

            <form method="GET" action="{{ route('karyawan.index') }}" class="kw-toolbar">
                <input type="hidden" name="tab" value="karyawan">

                <div class="kw-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                    <input type="text" name="search" class="kw-input" value="{{ request('search') }}" placeholder="Cari NIP, nama, unit, subunit, email, atau jabatan…">
                </div>

                <button type="submit" class="kw-btn">Cari</button>

                @if (request('search'))
                    <a href="{{ route('karyawan.index', ['tab' => 'karyawan']) }}" class="kw-btn-ghost">Reset</a>
                @endif
            </form>

            <div class="kw-table-wrap">
                <table class="kw-table kw-wide">
                    <thead>
                        <tr>
                            <th style="width: 56px;">No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Unit</th>
                            <th>Subunit</th>
                            <th>Email</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th class="kw-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($karyawan as $index => $item)
                            <tr>
                                <td class="kw-no">{{ $index + 1 }}</td>
                                <td class="kw-mono">{{ $item->nip }}</td>
                                <td>
                                    <div class="kw-person">
                                        <div class="kw-avatar">{{ strtoupper(mb_substr($item->nama, 0, 1)) }}</div>
                                        <span class="kw-name">{{ $item->nama }}</span>
                                    </div>
                                </td>
                                <td>{{ $item->unit->nama_unit ?? '—' }}</td>
                                <td class="kw-muted">{{ $item->subunitOrganisasi->nama_subunit ?? '—' }}</td>
                                <td class="kw-muted">{{ $item->email }}</td>
                                <td>{{ $item->jabatan ?? '—' }}</td>
                                <td>
                                    @if ($item->aktif)
                                        <span class="kw-badge kw-badge-green">Aktif</span>
                                    @else
                                        <span class="kw-badge kw-badge-red">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="kw-center" style="white-space: nowrap;">
                                    <div class="kw-actions">
                                        <a href="{{ route('karyawan.show', $item) }}" class="kw-act kw-act-detail">Detail</a>
                                        <a href="{{ route('karyawan.edit', $item) }}" class="kw-act kw-act-edit">Edit</a>

                                        @if (auth()->user()?->peran?->kode_peran === 'SUPER_ADMIN')
                                            <button type="button" class="kw-act kw-act-del btn-delete-karyawan"
                                                data-id="{{ $item->id }}" data-nama="{{ $item->nama }}">Hapus</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="kw-empty">
                                    @if (request('search'))
                                        Karyawan dengan kata pencarian <strong>"{{ request('search') }}"</strong> tidak ditemukan.
                                    @else
                                        Belum ada data karyawan.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    {{-- ===================== TAB UNIT ===================== --}}
    @else

        <div class="kw-panel">

            <div class="kw-panel-head">
                <h3 class="kw-panel-title">Daftar Unit Kerja</h3>
                <span class="kw-count">{{ $units->total() }} unit</span>
            </div>

            <form method="GET" action="{{ route('karyawan.index') }}" class="kw-toolbar">
                <input type="hidden" name="tab" value="unit">

                <div class="kw-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                    <input type="text" name="search_unit" class="kw-input" value="{{ request('search_unit') }}" placeholder="Cari kode atau nama unit…">
                </div>

                <button type="submit" class="kw-btn">Cari</button>

                @if (request('search_unit'))
                    <a href="{{ route('karyawan.index', ['tab' => 'unit']) }}" class="kw-btn-ghost">Reset</a>
                @endif
            </form>

            <div class="kw-table-wrap">
                <table class="kw-table kw-narrow">
                    <thead>
                        <tr>
                            <th style="width: 64px;">No.</th>
                            <th>Kode Unit</th>
                            <th>Nama Unit</th>
                            <th class="kw-center">Jumlah Karyawan</th>
                            <th>Status</th>
                            <th class="kw-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($units as $index => $item)
                            <tr>
                                <td class="kw-no">{{ $units->firstItem() + $index }}</td>
                                <td class="kw-mono">{{ $item->kode_unit }}</td>
                                <td class="kw-name">{{ $item->nama_unit }}</td>
                                <td class="kw-center kw-mono">{{ $item->karyawan_count }}</td>
                                <td>
                                    @if ($item->aktif)
                                        <span class="kw-badge kw-badge-green">Aktif</span>
                                    @else
                                        <span class="kw-badge kw-badge-red">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="kw-center">
                                    <a href="{{ route('karyawan.unit.detail', $item) }}" class="kw-act kw-act-detail">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="kw-empty">
                                    @if (request('search_unit'))
                                        Unit dengan kata pencarian <strong>"{{ request('search_unit') }}"</strong> tidak ditemukan.
                                    @else
                                        Belum ada data unit.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($units->hasPages())
                <div class="kw-foot">{{ $units->links() }}</div>
            @endif

        </div>

    @endif

    {{-- MODAL HAPUS KARYAWAN --}}
    <div id="deleteModal" class="kw-modal">
        <div class="kw-modal-box">
            <div class="kw-modal-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6M10 11v6M14 11v6"/></svg>
            </div>

            <h3 class="kw-modal-title">Hapus Karyawan?</h3>
            <p class="kw-modal-text">
                Apakah Anda yakin ingin menghapus karyawan
                <strong id="deleteEmployeeName">-</strong>?
                <span class="kw-modal-warn">Jika karyawan sudah memiliki akun, akun tersebut juga akan dihapus.</span>
            </p>

            <form id="deleteEmployeeForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="kw-modal-actions">
                    <button type="button" id="btnCancelDelete" class="kw-btn-ghost">Batal</button>
                    <button type="submit" class="kw-btn kw-btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal         = document.getElementById('deleteModal');
    const form          = document.getElementById('deleteEmployeeForm');
    const employeeName  = document.getElementById('deleteEmployeeName');
    const cancelButton  = document.getElementById('btnCancelDelete');

    document.querySelectorAll('.btn-delete-karyawan').forEach(function (button) {
        button.addEventListener('click', function () {
            employeeName.textContent = this.getAttribute('data-nama');
            form.action = "{{ url('/karyawan') }}/" + this.getAttribute('data-id');
            modal.style.display = 'flex';
        });
    });

    cancelButton.addEventListener('click', function () { modal.style.display = 'none'; });
    modal.addEventListener('click', function (e) { if (e.target === modal) modal.style.display = 'none'; });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') modal.style.display = 'none'; });
});
</script>

@endsection