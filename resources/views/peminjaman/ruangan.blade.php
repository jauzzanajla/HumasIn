@extends('layouts.app')

@section('title', 'Peminjaman Ruangan | HumasIn')
@section('page-title', 'Peminjaman Ruangan')
@section('page-description', 'Pilih ruangan yang ingin diajukan sesuai jadwal kegiatan')

@section('content')

<style>
    .rm-page {
        --rm-accent: #4E8BBC;
        --rm-accent-soft: rgba(78, 139, 188, .12);
        --rm-text: #1d1d1f;
        --rm-muted: #6e6e73;
        --rm-line: rgba(0, 0, 0, .07);
        --rm-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 1280px; margin: 0 auto; color: var(--rm-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .rm-page *, .rm-page *::before, .rm-page *::after { box-sizing: border-box; }

    .rm-page .rm-back { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #3b7aab; transition: opacity .15s; }
    .rm-page .rm-back:hover { opacity: .7; }
    .rm-page .rm-back svg { width: 16px; height: 16px; }

    .rm-page .rm-bar { display: flex; justify-content: space-between; align-items: flex-end; gap: 24px; flex-wrap: wrap; margin-bottom: 32px; }
    .rm-page .rm-title { font-size: 28px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .rm-page .rm-sub { font-size: 13px; color: var(--rm-muted); margin: 0; }
    .rm-page .rm-count { display: inline-flex; align-items: center; margin-top: 10px; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; background: var(--rm-accent-soft); color: #3b7aab; }

    .rm-page .rm-alert { margin-bottom: 18px; padding: 12px 16px; border-radius: 12px; font-size: 13px; line-height: 1.5; background: rgba(52,199,89,.1); border: 1px solid rgba(52,199,89,.22); color: #1f7a38; }
    .rm-page .rm-alert.err { background: rgba(255,59,48,.08); border-color: rgba(255,59,48,.18); color: #b42318; }

    /* Search */
    .rm-page .rm-search {
        display: flex; align-items: center; gap: 8px; width: 400px; max-width: 100%; padding: 5px 14px;
        border-radius: 999px; background: #fff; border: 1px solid var(--rm-line); box-shadow: var(--rm-shadow);
        transition: border-color .15s, box-shadow .15s;
    }
    .rm-page .rm-search:focus-within { border-color: var(--rm-accent); box-shadow: 0 0 0 4px var(--rm-accent-soft); }
    .rm-page .rm-search svg { width: 16px; height: 16px; color: #8e8e93; flex-shrink: 0; }
    .rm-page .rm-search input { flex: 1; min-width: 0; height: 34px; border: 0; outline: 0; background: transparent; font: inherit; font-size: 13px; color: var(--rm-text); }
    .rm-page .rm-search input::placeholder { color: #8e8e93; }

    /* Grid */
    .rm-page .rm-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; margin-bottom: 28px; }

    .rm-page .rm-card {
        display: flex; flex-direction: column; padding: 22px; background: #fff;
        border: 1px solid var(--rm-line); border-radius: 22px; box-shadow: var(--rm-shadow);
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
    }
    .rm-page .rm-card:hover { transform: translateY(-4px); border-color: var(--rm-accent); box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 18px 40px rgba(78,139,188,.18); }

    .rm-page .rm-name { font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0 0 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .rm-page .rm-big { display: flex; align-items: baseline; gap: 5px; margin-bottom: 16px; }
    .rm-page .rm-big b { font-size: 30px; font-weight: 700; letter-spacing: -.03em; line-height: 1; font-variant-numeric: tabular-nums; }
    .rm-page .rm-big span { font-size: 12px; color: var(--rm-muted); font-weight: 500; }

    .rm-page .rm-photo { aspect-ratio: 4 / 3; border-radius: 16px; overflow: hidden; background: #f5f5f7; margin-bottom: 14px; }
    .rm-page .rm-photo img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .35s ease; }
    .rm-page .rm-card:hover .rm-photo img { transform: scale(1.04); }
    .rm-page .rm-photo-empty { width: 100%; height: 100%; display: grid; place-items: center; align-content: center; gap: 6px; color: #b0b0b5; font-size: 11px; }
    .rm-page .rm-photo-empty svg { width: 30px; height: 30px; }

    .rm-page .rm-desc { font-size: 12px; line-height: 1.5; color: var(--rm-muted); margin: 0 0 16px; min-height: 36px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

    .rm-page .rm-meta { margin-top: auto; display: flex; justify-content: space-between; align-items: center; gap: 8px; padding-top: 14px; border-top: 1px solid var(--rm-line); font-size: 12px; font-weight: 500; color: var(--rm-muted); }
    .rm-page .rm-state { display: inline-flex; align-items: center; gap: 8px; color: #3a3a3c; }
    .rm-page .rm-state::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: var(--dot); }
    .rm-page .rm-state.ok   { --dot: #34c759; }
    .rm-page .rm-state.busy { --dot: #ff9f0a; }
    .rm-page .rm-meta-r { display: inline-flex; align-items: center; gap: 6px; }
    .rm-page .rm-meta-r svg { width: 14px; height: 14px; color: #8e8e93; }

    /* Tombol muncul saat hover */
    .rm-page .rm-cta {
        display: flex; align-items: center; justify-content: space-between; height: 0; margin-top: 0; padding: 0 16px; overflow: hidden; opacity: 0;
        border-radius: 12px; color: #fff; font-size: 13px; font-weight: 600;
        background: linear-gradient(180deg, #5b98c8, #4E8BBC);
        box-shadow: 0 6px 16px rgba(78,139,188,.28), inset 0 1px 0 rgba(255,255,255,.25);
        transition: height .2s ease, margin .2s ease, opacity .2s ease, filter .15s;
    }
    .rm-page .rm-card:hover .rm-cta, .rm-page .rm-cta:focus-visible { height: 42px; margin-top: 14px; opacity: 1; }
    .rm-page .rm-cta:hover { filter: brightness(1.06); }
    .rm-page .rm-cta i { display: grid; place-items: center; width: 26px; height: 26px; border-radius: 8px; background: rgba(255,255,255,.22); font-style: normal; }
    .rm-page .rm-cta svg { width: 14px; height: 14px; }
    @media (hover: none) { .rm-page .rm-cta { height: 42px; margin-top: 14px; opacity: 1; } }

    .rm-page .rm-empty { grid-column: 1 / -1; padding: 72px 20px; text-align: center; color: var(--rm-muted); font-size: 13px; background: #fff; border: 1px solid var(--rm-line); border-radius: 22px; box-shadow: var(--rm-shadow); }
    .rm-page .rm-empty svg { width: 40px; height: 40px; color: #c7c7cc; margin-bottom: 10px; }
    .rm-page .rm-empty strong { display: block; font-size: 15px; color: var(--rm-text); margin-bottom: 4px; }

    .rm-page .rm-note { font-size: 12px; color: var(--rm-muted); text-align: center; margin-bottom: 32px; }

    @media (max-width: 1280px) { .rm-page .rm-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 960px)  { .rm-page .rm-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 560px)  { .rm-page .rm-grid { grid-template-columns: 1fr; } .rm-page .rm-search { width: 100%; } }
</style>

<div class="rm-page">

  

    <div class="rm-bar">
        <div>
            <h1 class="rm-title"></h1>
            <p class="rm-sub"></p>
            <span class="rm-count" id="rmCount">{{ $ruangan->count() }} ruangan ditampilkan</span>
        </div>

        <label class="rm-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <input type="text" id="rmSearch" placeholder="Cari nama, lokasi, atau deskripsi…" autocomplete="off">
        </label>
    </div>

    @if (session('success')) <div class="rm-alert">{{ session('success') }}</div> @endif
    @if (session('error'))   <div class="rm-alert err">{{ session('error') }}</div> @endif
    @if ($errors->any())
        <div class="rm-alert err">@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
    @endif

    <div class="rm-grid" id="rmGrid">
        @foreach ($ruangan as $item)
            @php $tersedia = $item->tersedia ?? true; @endphp

            <div class="rm-card" data-cari="{{ strtolower($item->nama_ruangan . ' ' . $item->lokasi . ' ' . $item->deskripsi) }}">
                <h3 class="rm-name" title="{{ $item->nama_ruangan }}">{{ $item->nama_ruangan }}</h3>

                <div class="rm-big">
                    <b>{{ $item->kapasitas }}</b>
                    <span>orang kapasitas</span>
                </div>

                <div class="rm-photo">
                    @if ($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_ruangan }}">
                    @else
                        <div class="rm-photo-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                            Tidak ada foto
                        </div>
                    @endif
                </div>

                <p class="rm-desc">{{ $item->deskripsi ?: ($item->lokasi ?: 'Tidak ada deskripsi.') }}</p>

                <div class="rm-meta">
                    <span class="rm-state {{ $tersedia ? 'ok' : 'busy' }}">{{ $tersedia ? 'Tersedia' : 'Sedang Dipinjam' }}</span>
                    <span class="rm-meta-r">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                        Maks. {{ $item->durasi_maksimal }} {{ strtolower($item->satuan_durasi ?? 'jam') }}
                    </span>
                </div>

                @if ($tersedia)
                    <a href="{{ route('peminjaman.ruangan.create', $item->id) }}" class="rm-cta">
                        Ajukan Peminjaman
                        <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></i>
                    </a>
                @endif
            </div>
        @endforeach

        <div class="rm-empty" id="rmEmpty" style="{{ $ruangan->count() ? 'display:none' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
            <strong>Ruangan tidak ditemukan</strong>
            <span id="rmEmptyText">{{ $ruangan->count() ? '' : 'Belum ada ruangan yang tersedia.' }}</span>
        </div>
    </div>

    <p class="rm-note">
        Ketersediaan ruangan akan diperiksa kembali berdasarkan jadwal yang diajukan dan saat persetujuan diberikan.
    </p>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('rmSearch');
    const cards = document.querySelectorAll('#rmGrid .rm-card');
    const empty = document.getElementById('rmEmpty');
    const emptyText = document.getElementById('rmEmptyText');
    const count = document.getElementById('rmCount');

    if (!cards.length) return;

    input.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        let shown = 0;

        cards.forEach(function (c) {
            const ok = !q || c.dataset.cari.indexOf(q) !== -1;
            c.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });

        count.textContent = shown + ' ruangan ditampilkan';
        empty.style.display = shown ? 'none' : '';
        emptyText.textContent = shown ? '' : 'Tidak ada ruangan yang cocok dengan "' + this.value.trim() + '".';
    });
});
</script>

@endsection