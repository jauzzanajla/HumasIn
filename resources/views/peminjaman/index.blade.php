@extends('layouts.app')

@section('title', 'Peminjaman | HumasIn')
@section('page-title', 'Peminjaman')
@section('page-description', 'Ajukan peminjaman aset dan ruangan, serta pantau jadwal ruangan')

@section('content')

<style>
    .pm-page {
        --pm-accent: #4E8BBC;
        --pm-accent-soft: rgba(78, 139, 188, .12);
        --pm-text: #1d1d1f;
        --pm-muted: #6e6e73;
        --pm-line: rgba(0, 0, 0, .07);
        --pm-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 1280px; margin: 0 auto; color: var(--pm-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .pm-page *, .pm-page *::before, .pm-page *::after { box-sizing: border-box; }

    .pm-page .pm-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .pm-page .pm-sub { font-size: 13px; color: var(--pm-muted); margin: 0; }
    .pm-page .pm-head { margin-bottom: 24px; }

    /* Kartu pilihan */
    .pm-page .pm-choices { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 36px; }
    .pm-page .pm-choice {
        display: flex; align-items: center; gap: 16px; padding: 20px; background: #fff; color: inherit;
        border: 1px solid var(--pm-line); border-radius: 16px; box-shadow: var(--pm-shadow);
        transition: transform .2s, border-color .2s, box-shadow .2s;
    }
    .pm-page .pm-choice:hover { transform: translateY(-2px); border-color: var(--pm-accent); box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 14px 30px rgba(78,139,188,.16); }
    .pm-page .pm-choice-icon { width: 48px; height: 48px; flex-shrink: 0; display: grid; place-items: center; border-radius: 14px; background: rgba(78,139,188,.14); color: #3b7aab; }
    .pm-page .pm-choice-icon.indigo { background: rgba(88,86,214,.12); color: #5856d6; }
    .pm-page .pm-choice-icon svg { width: 24px; height: 24px; }
    .pm-page .pm-choice-text { flex: 1; min-width: 0; }
    .pm-page .pm-choice-title { font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0 0 3px; }
    .pm-page .pm-choice-desc { font-size: 12px; color: var(--pm-muted); line-height: 1.5; margin: 0; }
    .pm-page .pm-choice-arrow { width: 18px; height: 18px; color: #8e8e93; flex-shrink: 0; }

    /* Jadwal */
    .pm-page .pm-section-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 16px; }
    .pm-page .pm-section-title { font-size: 18px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 3px; }
    .pm-page .pm-live { display: inline-flex; align-items: center; gap: 7px; padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; background: rgba(52,199,89,.12); color: #248a3d; }
    .pm-page .pm-live i { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
    .pm-page .pm-live.err { background: rgba(255,59,48,.1); color: #c9342a; }

    .pm-page .pm-layout { display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; margin-bottom: 32px; }
    .pm-page .pm-card { background: #fff; border: 1px solid var(--pm-line); border-radius: 18px; box-shadow: var(--pm-shadow); overflow: hidden; }

    /* Toolbar kalender */
    .pm-page .pm-toolbar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; padding: 16px 20px; border-bottom: 1px solid var(--pm-line); }
    .pm-page .pm-month { font-size: 16px; font-weight: 700; letter-spacing: -.01em; min-width: 150px; }
    .pm-page .pm-nav { display: inline-flex; gap: 4px; }
    .pm-page .pm-icon-btn {
        width: 32px; height: 32px; display: grid; place-items: center; border: 0; border-radius: 9px; cursor: pointer;
        background: rgba(120,120,128,.1); color: #3a3a3c; transition: background .15s;
    }
    .pm-page .pm-icon-btn:hover { background: rgba(120,120,128,.2); }
    .pm-page .pm-icon-btn svg { width: 15px; height: 15px; }
    .pm-page .pm-spacer { flex: 1; }
    .pm-page .pm-select {
        height: 34px; padding: 0 30px 0 12px; border-radius: 9px; border: 1px solid transparent; background: rgba(120,120,128,.1);
        color: var(--pm-text); font: inherit; font-size: 12px; font-weight: 500; outline: none; appearance: none; cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 10px center;
    }
    .pm-page .pm-select:focus { background-color: #fff; border-color: var(--pm-accent); box-shadow: 0 0 0 4px var(--pm-accent-soft); }
    .pm-page .pm-today-btn { height: 32px; padding: 0 14px; border: 0; border-radius: 9px; background: var(--pm-accent-soft); color: #3b7aab; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; }
    .pm-page .pm-today-btn:hover { background: rgba(78,139,188,.2); }

    /* Grid kalender */
    .pm-page .pm-weekdays { display: grid; grid-template-columns: repeat(7, 1fr); background: #fafafc; border-bottom: 1px solid var(--pm-line); }
    .pm-page .pm-weekdays div { padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--pm-muted); }
    .pm-page .pm-grid { display: grid; grid-template-columns: repeat(7, 1fr); }
    .pm-page .pm-cell {
        min-height: 104px; padding: 6px; border-right: 1px solid var(--pm-line); border-bottom: 1px solid var(--pm-line);
        cursor: pointer; transition: background .15s; overflow: hidden;
    }
    .pm-page .pm-cell:nth-child(7n) { border-right: 0; }
    .pm-page .pm-cell:nth-last-child(-n+7) { border-bottom: 0; }
    .pm-page .pm-cell:hover { background: #f7f9fc; }
    .pm-page .pm-cell.out { background: #fcfcfd; }
    .pm-page .pm-cell.out .pm-daynum { color: #c0c0c6; }
    .pm-page .pm-cell.selected { background: rgba(78,139,188,.07); box-shadow: inset 0 0 0 1.5px var(--pm-accent); }
    .pm-page .pm-daynum {
        display: inline-grid; place-items: center; min-width: 24px; height: 24px; padding: 0 6px; margin-bottom: 4px;
        border-radius: 999px; font-size: 12px; font-weight: 600; font-variant-numeric: tabular-nums; color: #3a3a3c;
    }
    .pm-page .pm-cell.today .pm-daynum { background: var(--pm-accent); color: #fff; }

    .pm-page .pm-chip {
        display: block; margin-bottom: 3px; padding: 3px 6px; border-radius: 6px; border-left: 3px solid var(--c);
        background: var(--bg); color: #2b2b2e; font-size: 10.5px; font-weight: 600; line-height: 1.3;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .pm-page .pm-chip small { font-weight: 500; opacity: .75; margin-right: 3px; }
    .pm-page .st-ok   { --c: #4E8BBC; --bg: rgba(78,139,188,.14); }
    .pm-page .st-wait { --c: #ff9f0a; --bg: rgba(255,159,10,.16); }
    .pm-page .pm-more { font-size: 10.5px; font-weight: 600; color: var(--pm-muted); padding: 0 4px; }

    .pm-page .pm-legend { display: flex; gap: 18px; flex-wrap: wrap; padding: 12px 20px; border-top: 1px solid var(--pm-line); background: #fafafc; font-size: 12px; color: var(--pm-muted); }
    .pm-page .pm-legend span { display: inline-flex; align-items: center; gap: 7px; }
    .pm-page .pm-legend i { width: 9px; height: 9px; border-radius: 3px; background: var(--c); }

    /* Panel kegiatan */
    .pm-page .pm-side-head { padding: 18px 20px; border-bottom: 1px solid var(--pm-line); }
    .pm-page .pm-side-date { font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0 0 3px; }
    .pm-page .pm-side-count { font-size: 12px; color: var(--pm-muted); }
    .pm-page .pm-list { max-height: 560px; overflow-y: auto; padding: 8px; }
    .pm-page .pm-item { padding: 14px; border-radius: 12px; border-left: 3px solid var(--c); background: var(--bg); margin: 6px; }
    .pm-page .pm-item-top { display: flex; justify-content: space-between; align-items: center; gap: 8px; margin-bottom: 6px; }
    .pm-page .pm-item-time { font-size: 12px; font-weight: 700; font-variant-numeric: tabular-nums; }
    .pm-page .pm-badge { padding: 2px 9px; border-radius: 999px; font-size: 10.5px; font-weight: 600; background: #fff; color: var(--c); }
    .pm-page .pm-item-title { font-size: 13px; font-weight: 700; letter-spacing: -.005em; margin: 0 0 8px; }
    .pm-page .pm-meta { display: flex; align-items: center; gap: 7px; font-size: 12px; color: #4a4a4f; margin-top: 4px; }
    .pm-page .pm-meta svg { width: 13px; height: 13px; flex-shrink: 0; color: #8e8e93; }
    .pm-page .pm-empty { padding: 48px 20px; text-align: center; color: var(--pm-muted); font-size: 13px; }
    .pm-page .pm-empty svg { width: 34px; height: 34px; color: #c7c7cc; margin-bottom: 8px; }

    @media (max-width: 1100px) { .pm-page .pm-layout { grid-template-columns: 1fr; } }
    @media (max-width: 700px) {
        .pm-page .pm-choices { grid-template-columns: 1fr; }
        .pm-page .pm-cell { min-height: 76px; }
        .pm-page .pm-chip small { display: none; }
    }
</style>

<div class="pm-page">

    <div class="pm-head">
        <h2 class="pm-title">Peminjaman</h2>
        <p class="pm-sub">Pilih fasilitas yang ingin Anda ajukan untuk dipinjam.</p>
    </div>

    {{-- PILIHAN --}}
    <div class="pm-choices">
        <a href="{{ route('peminjaman.aset') }}" class="pm-choice">
            <div class="pm-choice-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/></svg>
            </div>
            <div class="pm-choice-text">
                <h3 class="pm-choice-title">Peminjaman Aset</h3>
                <p class="pm-choice-desc">Ajukan peminjaman barang dan peralatan yang dikelola Humas DJPK.</p>
            </div>
            <svg class="pm-choice-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
        </a>

        <a href="{{ route('peminjaman.ruangan') }}" class="pm-choice">
            <div class="pm-choice-icon indigo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16"/><path d="M16 9h2a2 2 0 0 1 2 2v10M2 21h20M9 7h2M9 11h2M9 15h2"/></svg>
            </div>
            <div class="pm-choice-text">
                <h3 class="pm-choice-title">Peminjaman Ruangan</h3>
                <p class="pm-choice-desc">Ajukan penggunaan ruangan dan fasilitas yang tersedia di Humas DJPK.</p>
            </div>
            <svg class="pm-choice-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
        </a>
    </div>

    {{-- JADWAL RUANGAN --}}
    <div class="pm-section-head">
        <div>
            <h3 class="pm-section-title">Jadwal Ruangan</h3>
            <p class="pm-sub">Kegiatan yang menggunakan ruangan, per tanggal.</p>
        </div>
        <span class="pm-live" id="pmLive"><i></i><span id="pmLiveText">Memuat…</span></span>
    </div>

    <div class="pm-layout">

        {{-- KALENDER --}}
        <div class="pm-card">
            <div class="pm-toolbar">
                <div class="pm-month" id="pmMonth">—</div>
                <div class="pm-nav">
                    <button type="button" class="pm-icon-btn" id="pmPrev" aria-label="Bulan sebelumnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <button type="button" class="pm-icon-btn" id="pmNext" aria-label="Bulan berikutnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
                    </button>
                </div>
                <button type="button" class="pm-today-btn" id="pmToday">Hari ini</button>

                <span class="pm-spacer"></span>

                <select id="pmFilterRuangan" class="pm-select">
                    <option value="">Semua Ruangan</option>
                    @foreach ($daftarRuangan ?? [] as $namaRuangan)
                        <option value="{{ $namaRuangan }}">{{ $namaRuangan }}</option>
                    @endforeach
                </select>

                <select id="pmFilterStatus" class="pm-select">
                    <option value="">Semua Status</option>
                    <option value="DISETUJUI">Dipinjam</option>
                    <option value="PENDING">Menunggu persetujuan</option>
                </select>
            </div>

            <div class="pm-weekdays">
                <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div>
            </div>
            <div class="pm-grid" id="pmGrid"></div>

            <div class="pm-legend">
                <span class="st-ok"><i></i> Dipinjam</span>
                <span class="st-wait"><i></i> Menunggu persetujuan</span>
            </div>
        </div>

        {{-- DAFTAR KEGIATAN --}}
        <div class="pm-card">
            <div class="pm-side-head">
                <h4 class="pm-side-date" id="pmSideDate">—</h4>
                <div class="pm-side-count" id="pmSideCount">—</div>
            </div>
            <div class="pm-list" id="pmList"></div>
        </div>

    </div>
</div>

<script>
(function () {
    const URL_EVENTS = @json(route('peminjaman.jadwal.kalender'));
    const INTERVAL_MS = 30000;
    const NAMA_BULAN = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    const NAMA_HARI  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    const $ = function (id) { return document.getElementById(id); };
    const grid = $('pmGrid'), list = $('pmList');

    const pad = function (n) { return String(n).padStart(2, '0'); };
    const key = function (d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); };
    const fromKey = function (k) { const p = k.split('-'); return new Date(+p[0], +p[1] - 1, +p[2]); };
    const esc = function (t) { const d = document.createElement('div'); d.textContent = t == null ? '' : t; return d.innerHTML; };
    const jam = function (iso) { return iso.slice(11, 16).replace(':', '.'); };

    const today = new Date();
    let viewMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    let selected = key(today);
    let events = [];
    let loading = false;

    function statusClass(s) { return s === 'DISETUJUI' ? 'st-ok' : 'st-wait'; }
    function statusLabel(s) { return s === 'DISETUJUI' ? 'Dipinjam' : 'Menunggu'; }

    // Pecah setiap kegiatan menjadi entri per hari (mendukung kegiatan lebih dari satu hari)
    function byDay() {
        const fr = $('pmFilterRuangan').value, fs = $('pmFilterStatus').value;
        const map = {};

        events.forEach(function (ev) {
            if (fr && ev.ruangan !== fr) return;
            if (fs && ev.status !== fs) return;

            const start = fromKey(ev.mulai.slice(0, 10));
            const end = fromKey(ev.selesai.slice(0, 10));

            for (let d = new Date(start), i = 0; d <= end && i < 62; d.setDate(d.getDate() + 1), i++) {
                const k = key(d);
                (map[k] = map[k] || []).push(ev);
            }
        });

        Object.keys(map).forEach(function (k) {
            map[k].sort(function (a, b) { return a.mulai < b.mulai ? -1 : 1; });
        });
        return map;
    }

    function renderCalendar() {
        const map = byDay();
        $('pmMonth').textContent = NAMA_BULAN[viewMonth.getMonth()] + ' ' + viewMonth.getFullYear();

        const first = new Date(viewMonth);
        const offset = (first.getDay() + 6) % 7; // Senin = 0
        const start = new Date(first); start.setDate(1 - offset);

        let html = '';
        for (let i = 0; i < 42; i++) {
            const d = new Date(start); d.setDate(start.getDate() + i);
            const k = key(d);
            const evs = map[k] || [];
            const cls = ['pm-cell'];
            if (d.getMonth() !== viewMonth.getMonth()) cls.push('out');
            if (k === key(today)) cls.push('today');
            if (k === selected) cls.push('selected');

            const chips = evs.slice(0, 2).map(function (e) {
                return '<span class="pm-chip ' + statusClass(e.status) + '"><small>' + jam(e.mulai) + '</small>' + esc(e.ruangan) + '</span>';
            }).join('');
            const more = evs.length > 2 ? '<div class="pm-more">+' + (evs.length - 2) + ' lagi</div>' : '';

            html += '<div class="' + cls.join(' ') + '" data-k="' + k + '"><span class="pm-daynum">' + d.getDate() + '</span>' + chips + more + '</div>';
        }
        grid.innerHTML = html;
    }

    function renderList() {
        const map = byDay();
        const evs = map[selected] || [];
        const d = fromKey(selected);

        $('pmSideDate').textContent = NAMA_HARI[d.getDay()] + ', ' + d.getDate() + ' ' + NAMA_BULAN[d.getMonth()] + ' ' + d.getFullYear();
        $('pmSideCount').textContent = evs.length ? evs.length + ' kegiatan' : 'Tidak ada kegiatan';

        if (!evs.length) {
            list.innerHTML = '<div class="pm-empty"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="3"/><path d="M3 9h18M8 2v4M16 2v4"/></svg><div>Belum ada ruangan yang dipinjam pada tanggal ini.</div></div>';
            return;
        }

        const icoRoom = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16M2 21h20"/></svg>';
        const icoUser = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>';

        list.innerHTML = evs.map(function (e) {
            return '<div class="pm-item ' + statusClass(e.status) + '">' +
                '<div class="pm-item-top"><span class="pm-item-time">' + jam(e.mulai) + ' – ' + jam(e.selesai) + '</span>' +
                '<span class="pm-badge">' + statusLabel(e.status) + '</span></div>' +
                '<h5 class="pm-item-title">' + esc(e.judul) + '</h5>' +
                '<div class="pm-meta">' + icoRoom + esc(e.ruangan) + '</div>' +
                '<div class="pm-meta">' + icoUser + esc(e.peminjam) + ' · ' + esc(e.unit) + '</div>' +
                '</div>';
        }).join('');
    }

    function render() { renderCalendar(); renderList(); }

    function tandai(ok) {
        const el = $('pmLive');
        el.classList.toggle('err', !ok);
        $('pmLiveText').textContent = ok ? 'Diperbarui ' + pad(new Date().getHours()) + ':' + pad(new Date().getMinutes()) : 'Gagal memuat, mencoba lagi…';
    }

    async function muat() {
        if (loading) return;
        loading = true;
        try {
            const bulan = viewMonth.getFullYear() + '-' + pad(viewMonth.getMonth() + 1);
            const res = await fetch(URL_EVENTS + '?bulan=' + bulan, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                cache: 'no-store', credentials: 'same-origin'
            });
            if (res.status === 401 || res.status === 419) { window.location.reload(); return; }
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const data = await res.json();
            events = data.events || [];
            render();
            tandai(true);
        } catch (e) {
            tandai(false);
        } finally {
            loading = false;
        }
    }

    function geser(n) {
        viewMonth = new Date(viewMonth.getFullYear(), viewMonth.getMonth() + n, 1);
        muat();
        render();
    }

    grid.addEventListener('click', function (e) {
        const cell = e.target.closest('.pm-cell');
        if (!cell) return;
        selected = cell.dataset.k;
        const d = fromKey(selected);
        if (d.getMonth() !== viewMonth.getMonth()) {
            viewMonth = new Date(d.getFullYear(), d.getMonth(), 1);
            muat();
        }
        render();
    });

    $('pmPrev').addEventListener('click', function () { geser(-1); });
    $('pmNext').addEventListener('click', function () { geser(1); });
    $('pmToday').addEventListener('click', function () {
        viewMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        selected = key(today);
        muat(); render();
    });
    $('pmFilterRuangan').addEventListener('change', render);
    $('pmFilterStatus').addEventListener('change', render);

    setInterval(function () { if (!document.hidden) muat(); }, INTERVAL_MS);
    document.addEventListener('visibilitychange', function () { if (!document.hidden) muat(); });

    render();
    muat();
})();
</script>

@endsection