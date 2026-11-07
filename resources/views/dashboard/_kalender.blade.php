@php
    $isPrivileged = in_array(Auth::user()?->peran?->kode_peran, ['SUPER_ADMIN', 'ADMIN']);
@endphp

<style>
    [x-cloak] { display: none !important; }

    /* =========================================================
       TOKEN TEMA (dipakai kalender + modal yang dipindah ke body)
    ========================================================== */
    .kl, .kl-modal, .kl-toast {
        --kl-accent: #4E8BBC; --kl-accent-soft: rgba(78,139,188,.12);
        --kl-text: #1d1d1f; --kl-muted: #6e6e73; --kl-line: rgba(0,0,0,.07);
        --kl-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .kl *, .kl *::before, .kl *::after,
    .kl-modal *, .kl-modal *::before, .kl-modal *::after { box-sizing: border-box; }

    .kl { position: relative; z-index: 5; background: #fff; border: 1px solid var(--kl-line); border-radius: 18px; box-shadow: var(--kl-shadow); overflow: hidden; color: var(--kl-text); }
    :is(.kl, .kl-modal) button:focus-visible { outline: 2px solid var(--kl-accent); outline-offset: 2px; }

    /* =========================================================
       HEADER
    ========================================================== */
    .kl .kl-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding: 18px 20px 14px; }
    .kl .kl-title { font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0; }
    .kl .kl-sub { font-size: 12px; color: var(--kl-muted); margin-top: 2px; }
    .kl .kl-tools { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .kl .kl-month { font-size: 14px; font-weight: 700; letter-spacing: -.01em; min-width: 130px; text-align: center; }
    .kl .kl-icon { width: 32px; height: 32px; display: grid; place-items: center; border: 0; border-radius: 9px; background: rgba(120,120,128,.1); color: #3a3a3c; cursor: pointer; transition: background .15s; }
    .kl .kl-icon:hover { background: rgba(120,120,128,.2); }
    .kl .kl-icon svg { width: 15px; height: 15px; }

    /* =========================================================
       TOMBOL
    ========================================================== */
    :is(.kl, .kl-modal) .kl-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 32px; padding: 0 14px; border: 0; border-radius: 9px; background: var(--kl-accent); color: #fff; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: filter .15s, background .15s, opacity .15s; }
    :is(.kl, .kl-modal) .kl-btn:hover:not(:disabled) { filter: brightness(1.06); }
    :is(.kl, .kl-modal) .kl-btn:disabled { opacity: .5; cursor: not-allowed; }
    :is(.kl, .kl-modal) .kl-btn.soft { background: var(--kl-accent-soft); color: #3b7aab; }
    :is(.kl, .kl-modal) .kl-btn.soft:hover:not(:disabled) { background: rgba(78,139,188,.2); filter: none; }
    :is(.kl, .kl-modal) .kl-btn.ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    :is(.kl, .kl-modal) .kl-btn.ghost:hover:not(:disabled) { background: rgba(120,120,128,.18); filter: none; }
    :is(.kl, .kl-modal) .kl-btn.danger { background: #ff3b30; }
    :is(.kl, .kl-modal) .kl-btn.danger-soft { background: rgba(255,59,48,.1); color: #c9342a; }
    :is(.kl, .kl-modal) .kl-btn.danger-soft:hover:not(:disabled) { background: rgba(255,59,48,.18); filter: none; }
    :is(.kl, .kl-modal) .kl-btn.lg { height: 40px; }
    :is(.kl, .kl-modal) .kl-btn svg { width: 13px; height: 13px; }

    /* =========================================================
       PERINGATAN GAGAL MUAT
    ========================================================== */
    .kl .kl-alert { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin: 0 20px 14px; padding: 10px 14px; border-radius: 10px; background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.2); color: #c9342a; font-size: 12.5px; }

    /* =========================================================
       GRID KALENDER
    ========================================================== */
    .kl .kl-wd { display: grid; grid-template-columns: repeat(7, 1fr); background: #fafafc; border-top: 1px solid var(--kl-line); border-bottom: 1px solid var(--kl-line); }
    .kl .kl-wd div { padding: 9px 4px; text-align: center; font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--kl-muted); }
    .kl .kl-wd div:last-child { color: #d0453b; }

    .kl .kl-gridwrap { position: relative; }
    .kl .kl-grid { display: grid; grid-template-columns: repeat(7, 1fr); }
    .kl .kl-cell { min-height: 100px; min-width: 0; padding: 6px; border-right: 1px solid var(--kl-line); border-bottom: 1px solid var(--kl-line); overflow: hidden; transition: background .15s, box-shadow .15s; }
    .kl .kl-cell:nth-child(7n) { border-right: 0; }
    .kl .kl-cell:nth-last-child(-n+7) { border-bottom: 0; }
    .kl .kl-cell:hover { background: #f7f9fc; }
    .kl .kl-cell.admin { cursor: pointer; }
    .kl .kl-cell.admin:hover { box-shadow: inset 0 0 0 1.5px rgba(78,139,188,.45); }
    .kl .kl-cell.out { background: #fcfcfd; }
    .kl .kl-cell.out .kl-dn { color: #c0c0c6; }
    .kl .kl-cell-top { display: flex; justify-content: space-between; align-items: center; }
    .kl .kl-dn { display: inline-grid; place-items: center; min-width: 24px; height: 24px; padding: 0 6px; margin-bottom: 4px; border-radius: 999px; font-size: 12px; font-weight: 600; color: #3a3a3c; font-variant-numeric: tabular-nums; }
    .kl .kl-cell.minggu .kl-dn { color: #d0453b; }
    .kl .kl-cell.today .kl-dn { background: var(--kl-accent); color: #fff; }
    .kl .kl-add { width: 20px; height: 20px; display: grid; place-items: center; border-radius: 6px; background: var(--kl-accent-soft); color: #3b7aab; font-size: 13px; font-weight: 700; line-height: 1; opacity: 0; transition: opacity .15s; }
    .kl .kl-cell.admin:hover .kl-add { opacity: 1; }

    .kl .kl-chip { display: block; width: 100%; margin-bottom: 3px; padding: 3px 6px; border: 0; border-left: 3px solid var(--c); border-radius: 6px; background: var(--bg); color: var(--kl-text); font: inherit; font-size: 10.5px; font-weight: 600; line-height: 1.3; text-align: left; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer; }
    .kl .kl-chip:hover { filter: brightness(.96); }
    .kl .kl-more { border: 0; background: transparent; padding: 0 4px; font: inherit; font-size: 10.5px; font-weight: 600; color: var(--kl-accent); cursor: pointer; }
    .kl .kl-more:hover { text-decoration: underline; }

    .kl .kl-loading { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,.7); font-size: 12.5px; font-weight: 600; color: var(--kl-muted); }

    /* =========================================================
       LEGENDA / FILTER WARNA
    ========================================================== */
    .kl .kl-legend { display: flex; align-items: center; gap: 6px 8px; flex-wrap: wrap; padding: 10px 20px; border-top: 1px solid var(--kl-line); background: #fafafc; font-size: 12px; color: var(--kl-muted); }
    .kl .kl-legend-label { margin-right: 4px; }
    .kl .kl-filter { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border: 1px solid transparent; border-radius: 999px; background: transparent; color: #3a3a3c; font: inherit; font-size: 12px; cursor: pointer; transition: background .15s, opacity .15s; }
    .kl .kl-filter:hover { background: rgba(120,120,128,.1); }
    .kl .kl-filter i { width: 9px; height: 9px; border-radius: 3px; }
    .kl .kl-filter.off { opacity: .4; }
    .kl .kl-filter.off span { text-decoration: line-through; }

    /* =========================================================
       AGENDA TERDEKAT
    ========================================================== */
    .kl .kl-up { padding: 14px 20px 18px; border-top: 1px solid var(--kl-line); }
    .kl .kl-up-title { font-size: 13px; font-weight: 700; margin-bottom: 8px; }
    .kl .kl-up-item { display: flex; align-items: center; gap: 12px; width: 100%; padding: 8px; border: 0; border-radius: 12px; background: transparent; font: inherit; text-align: left; cursor: pointer; transition: background .15s; }
    .kl .kl-up-item:hover { background: #f7f9fc; }
    .kl .kl-up-date { width: 42px; height: 46px; flex-shrink: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 11px; background: var(--bg); color: var(--kl-text); box-shadow: inset 3px 0 0 var(--c); line-height: 1.1; }
    .kl .kl-up-date b { font-size: 16px; font-weight: 700; }
    .kl .kl-up-date small { font-size: 10px; font-weight: 600; color: var(--kl-muted); text-transform: uppercase; }
    .kl .kl-up-text { min-width: 0; }
    .kl .kl-up-text b { display: block; font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .kl .kl-up-text small { display: block; font-size: 11.5px; color: var(--kl-muted); margin-top: 2px; }
    .kl .kl-empty { font-size: 12.5px; color: var(--kl-muted); padding: 4px 0; }

    /* =========================================================
       MODAL
    ========================================================== */
    .kl-modal { position: fixed; inset: 0; z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 20px; background: rgba(0,0,0,.4); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); color: var(--kl-text); }
    .kl-modal .kl-box { width: 460px; max-width: 100%; max-height: 90vh; overflow-y: auto; background: #fff; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,.25); animation: klPop .18s ease-out; }
    .kl-modal .kl-box.sm { width: 400px; padding: 28px 26px 22px; text-align: center; }
    @keyframes klPop { from { opacity: 0; transform: scale(.95); } to { opacity: 1; transform: scale(1); } }
    .kl-modal .kl-mhead { position: sticky; top: 0; z-index: 1; display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 16px 20px; border-bottom: 1px solid var(--kl-line); background: #fafafc; }
    .kl-modal .kl-mtitle { font-size: 15px; font-weight: 700; margin: 0; }
    .kl-modal .kl-x { width: 32px; height: 32px; flex-shrink: 0; border: 0; border-radius: 9px; background: transparent; font-size: 16px; line-height: 1; color: var(--kl-muted); cursor: pointer; }
    .kl-modal .kl-x:hover { background: rgba(120,120,128,.12); color: var(--kl-text); }
    .kl-modal .kl-mbody { padding: 20px; }
    .kl-modal .kl-mfoot { display: flex; justify-content: flex-end; align-items: center; gap: 8px; margin-top: 20px; }
    .kl-modal .kl-mfoot-left { display: flex; gap: 8px; margin-right: auto; }

    /* Detail */
    .kl-modal .kl-ev-title { display: flex; align-items: center; gap: 10px; font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 6px; }
    .kl-modal .kl-ev-dot { width: 12px; height: 12px; border-radius: 4px; flex-shrink: 0; }
    .kl-modal .kl-row { display: flex; justify-content: space-between; gap: 16px; padding: 11px 0; border-bottom: 1px solid var(--kl-line); font-size: 13px; }
    .kl-modal .kl-row:last-of-type { border-bottom: 0; }
    .kl-modal .kl-row > span:first-child { font-weight: 600; color: var(--kl-muted); flex-shrink: 0; }
    .kl-modal .kl-row > span:last-child { text-align: right; word-break: break-word; }

    /* Daftar satu hari */
    .kl-modal .kl-daylist { display: flex; flex-direction: column; gap: 8px; }
    .kl-modal .kl-dayitem { display: block; width: 100%; padding: 12px; border: 0; border-left: 3px solid var(--c); border-radius: 12px; background: var(--bg); color: var(--kl-text); font: inherit; text-align: left; cursor: pointer; }
    .kl-modal .kl-dayitem:hover { filter: brightness(.97); }
    .kl-modal .kl-dayitem b { display: block; font-size: 13px; }
    .kl-modal .kl-dayitem small { display: block; font-size: 11.5px; color: var(--kl-muted); margin-top: 2px; }

    /* Form */
    .kl-modal .kl-field { margin-bottom: 14px; }
    .kl-modal .kl-field label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 6px; }
    .kl-modal .kl-field .req { color: #ff3b30; }
    .kl-modal .kl-field .opt { font-weight: 500; color: var(--kl-muted); }
    .kl-modal .kl-two { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .kl-modal .kl-control { width: 100%; height: 40px; padding: 0 14px; border-radius: 10px; border: 1px solid var(--kl-line); background: rgba(120,120,128,.08); color: var(--kl-text); font: inherit; font-size: 13px; outline: none; transition: background .15s, box-shadow .15s, border-color .15s; }
    .kl-modal .kl-control:focus { background: #fff; border-color: var(--kl-accent); box-shadow: 0 0 0 4px var(--kl-accent-soft); }
    .kl-modal textarea.kl-control { height: auto; min-height: 80px; padding: 12px 14px; line-height: 1.5; resize: vertical; }
    .kl-modal .kl-hint { margin-top: 5px; font-size: 11px; color: var(--kl-muted); text-align: right; }
    .kl-modal .kl-hint.left { margin: -6px 0 14px; text-align: left; color: #3b7aab; font-weight: 600; }
    .kl-modal .kl-note { margin-bottom: 14px; padding: 10px 14px; border-radius: 10px; background: var(--kl-accent-soft); color: #2f6690; font-size: 12px; line-height: 1.5; }
    .kl-modal .kl-error { margin-bottom: 14px; padding: 10px 14px; border-radius: 10px; background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.2); color: #c9342a; font-size: 12.5px; line-height: 1.5; text-align: left; }

    .kl-modal .kl-swatches { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .kl-modal .kl-swatch { display: flex; align-items: center; gap: 8px; height: 40px; padding: 0 12px; border: 1px solid var(--kl-line); border-radius: 10px; background: rgba(120,120,128,.08); color: #3a3a3c; font: inherit; font-size: 12.5px; text-align: left; cursor: pointer; transition: background .15s, border-color .15s, box-shadow .15s; }
    .kl-modal .kl-swatch i { width: 12px; height: 12px; border-radius: 4px; background: var(--c); flex-shrink: 0; }
    .kl-modal .kl-swatch:hover { border-color: var(--c); }
    .kl-modal .kl-swatch.on { border-color: var(--c); background: var(--bg); color: var(--kl-text); font-weight: 600; box-shadow: 0 0 0 3px var(--bg); }

    /* Konfirmasi hapus */
    .kl-modal .kl-cicon { width: 52px; height: 52px; margin: 0 auto 14px; display: grid; place-items: center; border-radius: 50%; background: rgba(255,59,48,.12); color: #c9342a; }
    .kl-modal .kl-cicon svg { width: 26px; height: 26px; }
    .kl-modal .kl-ctitle { font-size: 17px; font-weight: 700; margin: 0 0 8px; }
    .kl-modal .kl-ctext { font-size: 13px; line-height: 1.6; color: #3a3a3c; margin: 0; }
    .kl-modal .kl-cact { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 22px; }

    /* Notifikasi */
    .kl-toast { position: fixed; right: 24px; bottom: 24px; z-index: 100000; padding: 12px 16px; border-radius: 12px; background: rgba(29,29,31,.92); color: #fff; font-size: 13px; font-weight: 500; box-shadow: 0 12px 30px rgba(0,0,0,.2); }

    /* =========================================================
       RESPONSIF
    ========================================================== */
    @media (max-width: 700px) {
        .kl .kl-cell { min-height: 64px; padding: 4px; }
        .kl .kl-chip { height: 5px; padding: 0; font-size: 0; border-left: 0; background: var(--c); }
        .kl .kl-add { display: none; }
        .kl-modal .kl-two, .kl-modal .kl-swatches { grid-template-columns: 1fr; }
    }
    @media (prefers-reduced-motion: reduce) {
        .kl-modal .kl-box { animation: none; }
        .kl *, .kl-modal * { transition: none !important; }
    }
</style>

<div class="kl" x-data="kalenderApp()" @keydown.escape.window="tutupSemua()">

    {{-- HEADER --}}
    <div class="kl-head">
        <div>
            <h3 class="kl-title">Kalender</h3>
            <div class="kl-sub">Agenda dukungan kegiatan Eselon II dan pengingat admin</div>
        </div>

        <div class="kl-tools">
            <button type="button" class="kl-icon" @click="ubahBulan(-1)" aria-label="Bulan sebelumnya" title="Bulan sebelumnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <span class="kl-month" x-text="namaBulanTahun()"></span>
            <button type="button" class="kl-icon" @click="ubahBulan(1)" aria-label="Bulan berikutnya" title="Bulan berikutnya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
            </button>
            <button type="button" class="kl-btn soft" @click="keHariIni()" :disabled="sedangBulanIni()">Hari ini</button>

            @if($isPrivileged)
                <button type="button" class="kl-btn" @click="bukaModalTambah()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Pengingat
                </button>
            @endif
        </div>
    </div>

    {{-- GAGAL MUAT --}}
    <div class="kl-alert" x-show="gagalMuat" x-cloak>
        <span>Agenda gagal dimuat. Periksa koneksi lalu coba lagi.</span>
        <button type="button" class="kl-btn danger-soft" @click="fetchEvents()">Coba lagi</button>
    </div>

    {{-- NAMA HARI --}}
    <div class="kl-wd"><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div></div>

    {{-- GRID TANGGAL --}}
    <div class="kl-gridwrap">
        <div class="kl-grid">
            <template x-for="kotak in gridHari" :key="kotak.ds">
                <div class="kl-cell"
                     :class="{ out: !kotak.bulanIni, today: kotak.hariIni, minggu: kotak.minggu, admin: isAdmin }"
                     :title="isAdmin ? 'Klik untuk menambah pengingat di tanggal ini' : ''"
                     @click="if (isAdmin) bukaModalTambah(kotak.ds)">
                    <div class="kl-cell-top">
                        <span class="kl-dn" x-text="kotak.tanggal"></span>
                        <span class="kl-add" x-show="isAdmin">+</span>
                    </div>

                    <template x-for="ev in eventsPada(kotak.ds).slice(0, 2)" :key="ev.id">
                        <button type="button" class="kl-chip" :style="gayaWarna(ev)" :title="ev.title"
                                @click.stop="pilihEvent(ev)" x-text="ev.title"></button>
                    </template>

                    <button type="button" class="kl-more" x-show="eventsPada(kotak.ds).length > 2"
                            @click.stop="bukaHari(kotak.ds)"
                            x-text="'+' + (eventsPada(kotak.ds).length - 2) + ' lagi'"></button>
                </div>
            </template>
        </div>
        <div class="kl-loading" x-show="loading" x-cloak>Memuat agenda…</div>
    </div>

    {{-- LEGENDA (klik untuk menyaring) --}}
    <div class="kl-legend">
        <span class="kl-legend-label">Tampilkan:</span>
        <template x-for="(label, hex) in pilihanWarna" :key="hex">
            <button type="button" class="kl-filter" :class="{ off: tersembunyi.includes(hex) }" @click="toggleWarna(hex)"
                    :title="tersembunyi.includes(hex) ? 'Klik untuk menampilkan' : 'Klik untuk menyembunyikan'">
                <i :style="'background:' + hex"></i><span x-text="label"></span>
            </button>
        </template>
    </div>

    {{-- AGENDA TERDEKAT --}}
    <div class="kl-up">
        <div class="kl-up-title">Agenda terdekat</div>
        <template x-for="ev in agendaTerdekat" :key="'up-' + ev.id">
            <button type="button" class="kl-up-item" @click="pilihEvent(ev)">
                <span class="kl-up-date" :style="gayaWarna(ev)">
                    <b x-text="tglAngka(ev.start)"></b>
                    <small x-text="blnSingkat(ev.start)"></small>
                </span>
                <span class="kl-up-text">
                    <b x-text="ev.title"></b>
                    <small x-text="ringkasan(ev)"></small>
                </span>
            </button>
        </template>
        <div class="kl-empty" x-show="!loading && agendaTerdekat.length === 0">Belum ada agenda mendatang.</div>
    </div>

    {{-- ================= MODAL DETAIL ================= --}}
    <template x-teleport="body">
        <div class="kl-modal" x-show="modalDetailOpen" x-cloak @click.self="modalDetailOpen = false">
            <div class="kl-box" role="dialog" aria-modal="true">
                <div class="kl-mhead">
                    <h3 class="kl-mtitle">Detail Agenda</h3>
                    <button type="button" class="kl-x" @click="modalDetailOpen = false" aria-label="Tutup">&#10005;</button>
                </div>
                <div class="kl-mbody">
                    <h4 class="kl-ev-title"><span class="kl-ev-dot" :style="'background:' + warna(eventAktif)"></span><span x-text="eventAktif.title"></span></h4>

                    <div class="kl-row"><span>Tanggal</span><span x-text="formatTanggal(eventAktif.start)"></span></div>
                    <div class="kl-row" x-show="jamDari(eventAktif.start)"><span>Jam</span><span x-text="jamDari(eventAktif.start) + ' WIB'"></span></div>
                    <div class="kl-row" x-show="eventAktif.extendedProps?.keterangan"><span>Keterangan</span><span x-text="eventAktif.extendedProps?.keterangan"></span></div>
                    <div class="kl-row"><span>Jenis</span><span x-text="jenisLabel(eventAktif)"></span></div>
                    <div class="kl-row"><span>Label</span><span x-text="pilihanWarna[warna(eventAktif)] || '-'"></span></div>

                    <div class="kl-mfoot">
                        @if($isPrivileged)
                            <div class="kl-mfoot-left">
                                <button type="button" class="kl-btn soft lg" @click="bukaModalEdit(eventAktif)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    Edit
                                </button>
                                <button type="button" class="kl-btn danger-soft lg" x-show="isPengingat(eventAktif)" @click="mintaHapus()">Hapus</button>
                            </div>
                        @endif
                        <button type="button" class="kl-btn ghost lg" @click="modalDetailOpen = false">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- ================= MODAL DAFTAR SATU HARI ================= --}}
    <template x-teleport="body">
        <div class="kl-modal" x-show="modalHariOpen" x-cloak @click.self="modalHariOpen = false">
            <div class="kl-box" role="dialog" aria-modal="true">
                <div class="kl-mhead">
                    <h3 class="kl-mtitle" x-text="formatTanggal(hariAktif)"></h3>
                    <button type="button" class="kl-x" @click="modalHariOpen = false" aria-label="Tutup">&#10005;</button>
                </div>
                <div class="kl-mbody">
                    <div class="kl-daylist">
                        <template x-for="ev in eventsPada(hariAktif)" :key="'d-' + ev.id">
                            <button type="button" class="kl-dayitem" :style="gayaWarna(ev)" @click="modalHariOpen = false; pilihEvent(ev)">
                                <b x-text="ev.title"></b>
                                <small x-text="ringkasan(ev)"></small>
                            </button>
                        </template>
                    </div>
                    <div class="kl-mfoot">
                        @if($isPrivileged)
                            <div class="kl-mfoot-left">
                                <button type="button" class="kl-btn soft lg" @click="bukaModalTambah(hariAktif)">+ Tambah di tanggal ini</button>
                            </div>
                        @endif
                        <button type="button" class="kl-btn ghost lg" @click="modalHariOpen = false">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    @if($isPrivileged)
    {{-- ================= MODAL FORM TAMBAH / EDIT ================= --}}
    <template x-teleport="body">
        <div class="kl-modal" x-show="modalFormOpen" x-cloak @click.self="modalFormOpen = false">
            <div class="kl-box" role="dialog" aria-modal="true">
                <div class="kl-mhead">
                    <h3 class="kl-mtitle" x-text="judulForm()"></h3>
                    <button type="button" class="kl-x" @click="modalFormOpen = false" aria-label="Tutup">&#10005;</button>
                </div>
                <form class="kl-mbody" @submit.prevent="simpanForm()">
                    <div class="kl-error" x-show="pesanError" x-text="pesanError"></div>

                    <div class="kl-note" x-show="editDukungan()">
                        Tanggal, lokasi, dan detail lain event dukungan diubah dari menu <b>Dukungan Kegiatan</b>.
                        Di sini hanya judul dan warna label.
                    </div>

                    <div class="kl-field">
                        <label for="kl-judul">Judul<span class="req"> *</span></label>
                        <input id="kl-judul" type="text" class="kl-control" x-model="form.judul" :maxlength="batasJudul()" required
                               placeholder="Contoh: Rapat Koordinasi Internal">
                        <div class="kl-hint" x-text="form.judul.length + '/' + batasJudul()"></div>
                    </div>

                    <div class="kl-two" x-show="!editDukungan()">
                        <div class="kl-field">
                            <label for="kl-tanggal">Tanggal<span class="req"> *</span></label>
                            <input id="kl-tanggal" type="date" class="kl-control" x-model="form.tanggal" :required="!editDukungan()">
                        </div>
                        <div class="kl-field">
                            <label for="kl-jam">Jam <span class="opt">(opsional)</span></label>
                            <input id="kl-jam" type="time" class="kl-control" x-model="form.jam">
                        </div>
                    </div>
                    <div class="kl-hint left" x-show="form.tanggal && !editDukungan()"
                         x-text="formatTanggal(form.tanggal) + (form.jam ? ', pukul ' + form.jam + ' WIB' : '')"></div>

                    <div class="kl-field">
                        <label>Warna label<span class="req"> *</span></label>
                        <div class="kl-swatches">
                            <template x-for="(label, hex) in pilihanWarna" :key="'sw-' + hex">
                                <button type="button" class="kl-swatch" :class="{ on: form.warna === hex }"
                                        :style="'--c:' + hex + '; --bg:' + hex + '26'" @click="form.warna = hex">
                                    <i></i><span x-text="label"></span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="kl-field" x-show="!editDukungan()">
                        <label for="kl-ket">Keterangan</label>
                        <textarea id="kl-ket" class="kl-control" maxlength="255" x-model="form.keterangan"
                                  placeholder="Tambahkan catatan jika perlu…"></textarea>
                        <div class="kl-hint" x-text="form.keterangan.length + '/255'"></div>
                    </div>

                    <div class="kl-mfoot">
                        <button type="button" class="kl-btn ghost lg" @click="modalFormOpen = false">Batal</button>
                        <button type="submit" class="kl-btn lg" :disabled="menyimpan"
                                x-text="menyimpan ? 'Menyimpan…' : (modeEdit ? 'Simpan Perubahan' : 'Simpan Pengingat')"></button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    {{-- ================= MODAL KONFIRMASI HAPUS ================= --}}
    <template x-teleport="body">
        <div class="kl-modal" x-show="modalHapusOpen" x-cloak @click.self="modalHapusOpen = false">
            <div class="kl-box sm" role="alertdialog" aria-modal="true">
                <div class="kl-cicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6M10 11v6M14 11v6"/></svg></div>
                <h3 class="kl-ctitle">Hapus pengingat?</h3>
                <p class="kl-ctext"><strong x-text="eventAktif.title"></strong> akan dihapus. Tindakan ini tidak bisa dibatalkan.</p>
                <div class="kl-error" style="margin: 14px 0 0;" x-show="pesanError" x-text="pesanError"></div>
                <div class="kl-cact">
                    <button type="button" class="kl-btn ghost lg" @click="modalHapusOpen = false">Batal</button>
                    <button type="button" class="kl-btn danger lg" :disabled="menyimpan" @click="hapusPengingat()"
                            x-text="menyimpan ? 'Menghapus…' : 'Hapus'"></button>
                </div>
            </div>
        </div>
    </template>
    @endif

    {{-- NOTIFIKASI --}}
    <template x-teleport="body">
        <div class="kl-toast" x-show="toast" x-transition x-cloak x-text="toast" role="status"></div>
    </template>
</div>

<script>
function kalenderApp() {
    const pad = (n) => String(n).padStart(2, '0');
    const fmt = (d) => d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
    const tgl = (s) => String(s || '').slice(0, 10);
    const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const BLN = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const HARI = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const WARNA_DEFAULT = '#6EAE78';
    const URL_BASE = '{{ url("kalender") }}';
    const CSRF = '{{ csrf_token() }}';

    return {
        tahunAktif: new Date().getFullYear(),
        bulanAktif: new Date().getMonth(),
        events: [],
        loading: true,
        gagalMuat: false,
        tersembunyi: [],
        eventAktif: {},
        hariAktif: '',
        modalDetailOpen: false,
        modalHariOpen: false,
        modalFormOpen: false,
        modalHapusOpen: false,
        modeEdit: false,
        editJenis: '',
        editId: null,
        menyimpan: false,
        pesanError: '',
        toast: '',
        _toastTimer: null,
        isAdmin: @json($isPrivileged),
        form: { judul: '', tanggal: '', jam: '', warna: '#5C88D9', keterangan: '' },
        pilihanWarna: {
            '#6EAE78': 'Hijau (Internal)',
            '#E5A044': 'Oranye (Penting)',
            '#5C88D9': 'Biru (Eksternal)',
            '#8B654B': 'Coklat (Khusus)'
        },

        init() { this.fetchEvents(); },

        async fetchEvents() {
            this.loading = true;
            this.gagalMuat = false;
            try {
                const res = await fetch(URL_BASE + '/events-json', { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error(res.status);
                const data = await res.json();
                this.events = Array.isArray(data) ? data : [];
            } catch (e) {
                this.gagalMuat = true;
            } finally {
                this.loading = false;
            }
        },

        /* ---------- Data turunan ---------- */
        get eventTampil() {
            return this.events.filter(ev => !this.tersembunyi.includes(this.warna(ev)));
        },

        get petaEvent() {
            const peta = {};
            this.eventTampil.forEach(ev => {
                const k = tgl(ev.start);
                (peta[k] = peta[k] || []).push(ev);
            });
            Object.values(peta).forEach(a => a.sort((x, y) => String(x.start).localeCompare(String(y.start))));
            return peta;
        },

        get gridHari() {
            const first = new Date(this.tahunAktif, this.bulanAktif, 1);
            const offset = (first.getDay() + 6) % 7; // Senin = 0
            const jumlahHari = new Date(this.tahunAktif, this.bulanAktif + 1, 0).getDate();
            const total = Math.ceil((offset + jumlahHari) / 7) * 7;
            const today = fmt(new Date());

            return Array.from({ length: total }, (_, i) => {
                const d = new Date(this.tahunAktif, this.bulanAktif, 1 - offset + i);
                const ds = fmt(d);
                return {
                    tanggal: d.getDate(),
                    ds: ds,
                    bulanIni: d.getMonth() === this.bulanAktif,
                    hariIni: ds === today,
                    minggu: d.getDay() === 0
                };
            });
        },

        get agendaTerdekat() {
            const today = fmt(new Date());
            return this.eventTampil
                .filter(ev => tgl(ev.start) >= today)
                .sort((a, b) => String(a.start).localeCompare(String(b.start)))
                .slice(0, 5);
        },

        eventsPada(ds) { return this.petaEvent[ds] || []; },

        /* ---------- Tampilan ---------- */
        warna(ev) { return (ev && ev.color) ? String(ev.color).toUpperCase() : WARNA_DEFAULT; },
        gayaWarna(ev) { const c = this.warna(ev); return '--c:' + c + '; --bg:' + c + '26'; },
        isPengingat(ev) { return !!(ev && ev.extendedProps && ev.extendedProps.type === 'pengingat'); },
        jenisLabel(ev) { return this.isPengingat(ev) ? 'Pengingat mandiri admin' : 'Permintaan Dukungan Eselon II'; },

        namaBulanTahun() { return BULAN[this.bulanAktif] + ' ' + this.tahunAktif; },

        formatTanggal(s) {
            const p = tgl(s).split('-').map(Number);
            if (p.length < 3 || !p[0]) return '';
            const d = new Date(p[0], p[1] - 1, p[2]);
            return HARI[d.getDay()] + ', ' + p[2] + ' ' + BULAN[p[1] - 1] + ' ' + p[0];
        },

        jamDari(s) {
            s = s ? String(s) : '';
            if (s.length < 16 || s.indexOf('T') === -1) return '';
            const j = s.slice(11, 16);
            return j === '00:00' ? '' : j;
        },

        tglAngka(s) { return parseInt(tgl(s).slice(8, 10), 10); },
        blnSingkat(s) { return BLN[parseInt(tgl(s).slice(5, 7), 10) - 1]; },

        ringkasan(ev) {
            const p = tgl(ev.start).split('-').map(Number);
            const bagian = [HARI[new Date(p[0], p[1] - 1, p[2]).getDay()]];
            const jam = this.jamDari(ev.start);
            if (jam) bagian.push(jam + ' WIB');
            bagian.push(this.isPengingat(ev) ? 'Pengingat' : 'Dukungan kegiatan');
            return bagian.join(' · ');
        },

        /* ---------- Navigasi ---------- */
        sedangBulanIni() {
            const n = new Date();
            return this.tahunAktif === n.getFullYear() && this.bulanAktif === n.getMonth();
        },

        ubahBulan(dir) {
            const d = new Date(this.tahunAktif, this.bulanAktif + dir, 1);
            this.tahunAktif = d.getFullYear();
            this.bulanAktif = d.getMonth();
        },

        keHariIni() {
            const n = new Date();
            this.tahunAktif = n.getFullYear();
            this.bulanAktif = n.getMonth();
        },

        lompatKe(tanggal) {
            const p = String(tanggal || '').split('-').map(Number);
            if (!p[0]) return;
            this.tahunAktif = p[0];
            this.bulanAktif = p[1] - 1;
        },

        toggleWarna(hex) {
            const i = this.tersembunyi.indexOf(hex);
            if (i === -1) this.tersembunyi.push(hex);
            else this.tersembunyi.splice(i, 1);
        },

        /* ---------- Modal ---------- */
        pilihEvent(ev) {
            this.pesanError = '';
            this.eventAktif = ev;
            this.modalDetailOpen = true;
        },

        bukaHari(ds) { this.hariAktif = ds; this.modalHariOpen = true; },

        tutupSemua() {
            this.modalDetailOpen = this.modalHariOpen = this.modalFormOpen = this.modalHapusOpen = false;
        },

        fokusJudul() {
            this.$nextTick(() => { const el = document.getElementById('kl-judul'); if (el) el.focus(); });
        },

        bukaModalTambah(ds) {
            if (!this.isAdmin) return;
            this.pesanError = '';
            this.modeEdit = false;
            this.editJenis = '';
            this.editId = null;
            this.form = { judul: '', tanggal: ds || fmt(new Date()), jam: '', warna: '#5C88D9', keterangan: '' };
            this.modalHariOpen = false;
            this.modalFormOpen = true;
            this.fokusJudul();
        },

        bukaModalEdit(ev) {
            if (!this.isAdmin || !ev) return;
            const p = ev.extendedProps || {};
            this.pesanError = '';
            this.modeEdit = true;
            this.editJenis = p.type === 'pengingat' ? 'pengingat' : 'dukungan';
            this.editId = p.rid || String(ev.id).replace(/^(pengingat|dukungan)-/, '');
            this.form = {
                judul: ev.title || '',
                tanggal: tgl(ev.start),
                jam: this.jamDari(ev.start),
                warna: this.pilihanWarna[this.warna(ev)] ? this.warna(ev) : WARNA_DEFAULT,
                keterangan: this.editJenis === 'pengingat' ? (p.keterangan || '') : ''
            };
            this.modalDetailOpen = false;
            this.modalFormOpen = true;
            this.fokusJudul();
        },

        editDukungan() { return this.modeEdit && this.editJenis === 'dukungan'; },
        batasJudul() { return this.editDukungan() ? 255 : 100; },
        judulForm() {
            if (!this.modeEdit) return 'Tambah Pengingat / Event';
            return this.editJenis === 'dukungan' ? 'Edit Event Dukungan Kegiatan' : 'Edit Pengingat';
        },

        mintaHapus() {
            if (!this.isAdmin) return;
            this.pesanError = '';
            this.modalHapusOpen = true;
        },

        tampilkanToast(pesan) {
            this.toast = pesan;
            clearTimeout(this._toastTimer);
            this._toastTimer = setTimeout(() => { this.toast = ''; }, 2600);
        },

        /* ---------- Kirim ke server ---------- */
        async kirim(url, method, body) {
            const opsi = { method: method, headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } };
            if (body) {
                opsi.headers['Content-Type'] = 'application/json';
                opsi.body = JSON.stringify(body);
            }
            const res = await fetch(url, opsi);
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                const pertama = data.errors ? Object.values(data.errors)[0][0] : null;
                throw new Error(pertama || data.message || 'Permintaan gagal. Coba lagi.');
            }
            return data;
        },

        async simpanForm() {
            if (!this.isAdmin || this.menyimpan) return;
            this.menyimpan = true;
            this.pesanError = '';

            try {
                if (!this.modeEdit) {
                    await this.kirim(URL_BASE + '/pengingat', 'POST', this.form);
                    this.lompatKe(this.form.tanggal);
                    this.tampilkanToast('Pengingat berhasil ditambahkan.');
                } else if (this.editJenis === 'pengingat') {
                    await this.kirim(URL_BASE + '/pengingat/' + this.editId, 'PATCH', this.form);
                    this.lompatKe(this.form.tanggal);
                    this.tampilkanToast('Perubahan berhasil disimpan.');
                } else {
                    await this.kirim(URL_BASE + '/event/' + this.editId, 'PATCH', { judul: this.form.judul, kategori: this.form.warna });
                    this.tampilkanToast('Perubahan berhasil disimpan.');
                }

                this.modalFormOpen = false;
                this.modeEdit = false;
                this.fetchEvents();
            } catch (e) {
                this.pesanError = (e instanceof TypeError) ? 'Tidak bisa terhubung ke server. Coba lagi.' : e.message;
            } finally {
                this.menyimpan = false;
            }
        },

        async hapusPengingat() {
            if (!this.isAdmin || this.menyimpan) return;
            this.menyimpan = true;
            this.pesanError = '';
            const p = this.eventAktif.extendedProps || {};
            const id = p.rid || String(this.eventAktif.id).replace('pengingat-', '');

            try {
                await this.kirim(URL_BASE + '/pengingat/' + id, 'DELETE');
                this.modalHapusOpen = false;
                this.modalDetailOpen = false;
                this.tampilkanToast('Pengingat berhasil dihapus.');
                this.fetchEvents();
            } catch (e) {
                this.pesanError = (e instanceof TypeError) ? 'Tidak bisa terhubung ke server. Coba lagi.' : e.message;
            } finally {
                this.menyimpan = false;
            }
        }
    };
}
</script>