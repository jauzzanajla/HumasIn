@extends('layouts.app')

@section('title', 'Detail Ruangan | HumasIn')
@section('page-title', 'Detail Ruangan')
@section('page-description', 'Informasi detail ruangan dan fasilitas')

@section('content')

<style>
    .rg-page {
        --rg-accent: #4E8BBC;
        --rg-accent-soft: rgba(78, 139, 188, .12);
        --rg-text: #1d1d1f;
        --rg-muted: #6e6e73;
        --rg-line: rgba(0, 0, 0, .07);
        --rg-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 1080px; margin: 0 auto; color: var(--rg-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .rg-page *, .rg-page *::before, .rg-page *::after { box-sizing: border-box; }

    .rg-page .rg-back { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #3b7aab; transition: opacity .15s; }
    .rg-page .rg-back:hover { opacity: .7; }
    .rg-page .rg-back svg { width: 16px; height: 16px; }

    .rg-page .rg-card { background: #fff; border: 1px solid var(--rg-line); border-radius: 18px; box-shadow: var(--rg-shadow); overflow: hidden; }
    .rg-page .rg-card-head { display: flex; justify-content: space-between; align-items: center; gap: 16px; padding: 22px 32px; border-bottom: 1px solid var(--rg-line); }
    .rg-page .rg-card-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 3px; }
    .rg-page .rg-card-sub { font-size: 13px; color: var(--rg-muted); margin: 0; }
    .rg-page .rg-card-body { padding: 32px; }
    .rg-page .rg-card-foot { display: flex; align-items: center; gap: 10px; padding: 18px 32px; border-top: 1px solid var(--rg-line); background: #fafafc; }
    .rg-page .rg-spacer { flex: 1; }

    .rg-page .rg-detail { display: grid; grid-template-columns: 400px 1fr; gap: 36px; align-items: start; }

    .rg-page .rg-photo, .rg-page .rg-photo-empty { width: 100%; aspect-ratio: 4 / 3; border-radius: 16px; }
    .rg-page .rg-photo { object-fit: cover; display: block; border: 1px solid var(--rg-line); box-shadow: 0 4px 16px rgba(0,0,0,.08); }
    .rg-page .rg-photo-empty { display: grid; place-items: center; align-content: center; gap: 8px; background: rgba(120,120,128,.08); border: 1.5px dashed rgba(120,120,128,.3); color: #8e8e93; font-size: 12px; }
    .rg-page .rg-photo-empty svg { width: 32px; height: 32px; }

    .rg-page .rg-label { font-size: 11px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--rg-muted); margin-bottom: 8px; }
    .rg-page .rg-name { font-size: 26px; font-weight: 700; letter-spacing: -.025em; line-height: 1.2; margin: 0 0 8px; }
    .rg-page .rg-loc { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: var(--rg-muted); margin-bottom: 22px; }
    .rg-page .rg-loc svg { width: 15px; height: 15px; flex-shrink: 0; color: #8e8e93; }

    .rg-page .rg-kv-grid { display: grid; grid-template-columns: repeat(3, 1fr); border: 1px solid var(--rg-line); border-radius: 14px; overflow: hidden; margin-bottom: 22px; }
    .rg-page .rg-kv { padding: 16px 18px; }
    .rg-page .rg-kv + .rg-kv { border-left: 1px solid var(--rg-line); }
    .rg-page .rg-kv-value { font-size: 15px; font-weight: 600; font-variant-numeric: tabular-nums; min-height: 24px; display: flex; align-items: center; }
    .rg-page .rg-kv-value small { font-size: 13px; font-weight: 400; color: var(--rg-muted); margin-left: 5px; }

    .rg-page .rg-note { padding: 16px 18px; border-radius: 14px; background: #fafafc; border: 1px solid var(--rg-line); font-size: 13px; line-height: 1.65; color: #3a3a3c; white-space: pre-line; }

    .rg-page .rg-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .rg-page .rg-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .rg-page .rg-badge-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .rg-page .rg-badge-red   { background: rgba(255,59,48,.12); color: #c9342a; }

    .rg-page .rg-btn, .rg-page .rg-btn-ghost, .rg-page .rg-btn-del {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 38px; padding: 0 18px;
        border-radius: 10px; border: 0; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s;
    }
    .rg-page .rg-btn { background: var(--rg-accent); color: #fff; }
    .rg-page .rg-btn:hover { filter: brightness(1.06); }
    .rg-page .rg-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .rg-page .rg-btn-ghost:hover { background: rgba(120,120,128,.18); }
    .rg-page .rg-btn-del { background: rgba(255,59,48,.1); color: #c9342a; }
    .rg-page .rg-btn-del:hover { background: rgba(255,59,48,.18); }
    .rg-page .rg-btn-del svg { width: 15px; height: 15px; }

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

    @media (max-width: 900px) {
        .rg-page .rg-detail { grid-template-columns: 1fr; }
        .rg-page .rg-kv-grid { grid-template-columns: 1fr; }
        .rg-page .rg-kv + .rg-kv { border-left: 0; border-top: 1px solid var(--rg-line); }
    }
</style>

<div class="rg-page">

    <a href="{{ route('ruangan.index') }}" class="rg-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kelola Ruangan
    </a>

    <div class="rg-card">

        {{-- HEADER --}}
        <div class="rg-card-head">
            <div>
                <h3 class="rg-card-title">Detail Informasi Ruangan</h3>
                <p class="rg-card-sub">ID Ruangan: #{{ $ruangan->id }}</p>
            </div>

            @if ($ruangan->status === 'TERSEDIA')
                <span class="rg-badge rg-badge-green">Tersedia</span>
            @else
                <span class="rg-badge rg-badge-red">Tidak Tersedia</span>
            @endif
        </div>

        {{-- BODY --}}
        <div class="rg-card-body">
            <div class="rg-detail">

                {{-- PREVIEW RUANGAN --}}
                <div>
                    @if ($ruangan->foto)
                        <img src="{{ asset('storage/' . $ruangan->foto) }}" alt="{{ $ruangan->nama_ruangan }}" class="rg-photo">
                    @else
                        <div class="rg-photo-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                            Tidak ada foto ruangan
                        </div>
                    @endif
                </div>

                {{-- INFO --}}
                <div>
                    <div class="rg-label" style="margin-bottom: 6px;">Nama Ruangan</div>
                    <h2 class="rg-name">{{ $ruangan->nama_ruangan }}</h2>

                    <div class="rg-loc">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $ruangan->lokasi ?? 'Lokasi belum diisi' }}
                    </div>

                    <div class="rg-kv-grid">
                        <div class="rg-kv">
                            <div class="rg-label">Kapasitas</div>
                            <div class="rg-kv-value">{{ $ruangan->kapasitas }}<small>orang</small></div>
                        </div>

                        <div class="rg-kv">
                            <div class="rg-label">Kuota</div>
                            <div class="rg-kv-value">{{ $ruangan->kuota }}</div>
                        </div>

                        <div class="rg-kv">
                            <div class="rg-label">Durasi Maks.</div>
                            <div class="rg-kv-value">{{ $ruangan->durasi_maksimal }}<small>{{ strtolower($ruangan->satuan_durasi) }}</small></div>
                        </div>
                    </div>

                    <div class="rg-label">Deskripsi</div>
                    <div class="rg-note">{{ $ruangan->deskripsi ?: 'Tidak ada deskripsi yang ditambahkan.' }}</div>
                </div>

            </div>
        </div>

        {{-- FOOTER AKSI --}}
        <div class="rg-card-foot">
            <button type="button" id="btnOpenDelete" class="rg-btn-del">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6"/></svg>
                Hapus
            </button>

            <span class="rg-spacer"></span>

            <a href="{{ route('ruangan.index') }}" class="rg-btn-ghost">Kembali</a>
            <a href="{{ route('ruangan.edit', $ruangan) }}" class="rg-btn">Edit Ruangan</a>
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
                <strong>{{ $ruangan->nama_ruangan }}</strong>?
                <span class="rg-modal-warn">Data ruangan yang dihapus tidak dapat dikembalikan.</span>
            </p>

            <form action="{{ route('ruangan.destroy', $ruangan) }}" method="POST">
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
    const modal = document.getElementById('deleteModal');

    document.getElementById('btnOpenDelete').addEventListener('click', function () { modal.style.display = 'flex'; });
    document.getElementById('btnCancelDelete').addEventListener('click', function () { modal.style.display = 'none'; });
    modal.addEventListener('click', function (e) { if (e.target === modal) modal.style.display = 'none'; });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') modal.style.display = 'none'; });
});
</script>

@endsection