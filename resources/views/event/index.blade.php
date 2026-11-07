@extends('layouts.app')

@section('title', 'Event & Kalender | HumasIn')
@section('page-title', 'Event & Kalender Kegiatan')
@section('page-description', 'Kalender agenda dan pengelolaan permohonan dukungan kegiatan Humas DJPK')

@section('content')

@php
    /* ---------- Data bantu untuk tampilan ---------- */
    $STATUS = [
        'PENDING'        => ['Menunggu',      'b-amber'],
        'DIDELEGASIKAN'  => ['Didelegasikan', 'b-blue'],
        'DIIDELEGASIKAN' => ['Didelegasikan', 'b-blue'],   // data lama (salah ketik)
        'DIPROSES'       => ['Diproses',      'b-indigo'],
        'SELESAI'        => ['Selesai',       'b-green'],
        'DITOLAK'        => ['Ditolak',       'b-red'],
    ];

    // Sama persis dengan warna & label di kalender
    $KATEGORI = [
        'hijau'  => ['Hijau (Internal)',  '#6EAE78'],
        'oranye' => ['Oranye (Penting)',  '#E5A044'],
        'biru'   => ['Biru (Eksternal)',  '#5C88D9'],
        'coklat' => ['Coklat (Khusus)',   '#8B654B'],
    ];

    // Ambil nama semua PIC sekaligus (tidak query satu per satu di dalam tabel)
    $semuaPicId = $permintaanMasuk->concat($permintaanSelesai)
        ->flatMap(fn ($i) => (array) ($i->admin_ids ?? []))
        ->unique()->values();
    $namaPic = \App\Models\User::whereIn('id', $semuaPicId)->pluck('name', 'id');

    $dukungan = function ($item) {
        $daftar = [
            'support_operator'      => 'Operator',
            'support_desain_grafis' => 'Desain Grafis',
            'support_editing'       => 'Editing',
            'support_konten_web'    => 'Konten Web',
            'support_merchandise'   => 'Merchandise',
            'support_dokumentasi'   => 'Dokumentasi',
            'support_tim_acara'     => 'Tim Acara',
        ];
        return collect($daftar)->filter(fn ($label, $kolom) => (bool) $item->{$kolom})->values()->all();
    };

    $detailJson = function ($item) use ($dukungan, $namaPic, $STATUS, $KATEGORI) {
        $tgl = \Carbon\Carbon::parse($item->tanggal_pelaksanaan);
        return json_encode([
            'nama'      => $item->nama_event,
            'tanggal'   => $tgl->translatedFormat('l, d F Y'),
            'jam'       => substr((string) $item->jam_pelaksanaan, 0, 5),
            'lokasi'    => $item->lokasi,
            'pemohon'   => $item->user->name ?? '-',
            'pejabat'   => $item->pejabat_hadir ?: '-',
            'peserta'   => $item->jumlah_peserta ? $item->jumlah_peserta . ' orang' : '-',
            'dukungan'  => $dukungan($item),
            'desain'    => $item->detail_desain_grafis,
            'editing'   => $item->detail_editing_platform,
            'merch'     => $item->detail_merchandise,
            'pic'       => collect((array) ($item->admin_ids ?? []))->map(fn ($id) => $namaPic[$id] ?? null)->filter()->values()->all(),
            'status'    => $STATUS[$item->status][0] ?? $item->status,
            'kategori'  => $KATEGORI[strtolower($item->kategori_warna ?? 'hijau')][0] ?? '-',
        ]);
    };
@endphp

<style>
    .ev-page {
        --ev-accent: #4E8BBC; --ev-accent-soft: rgba(78,139,188,.12);
        --ev-text: #1d1d1f; --ev-muted: #6e6e73; --ev-line: rgba(0,0,0,.07);
        --ev-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);
        display: flex; flex-direction: column; gap: 20px;
        max-width: 1280px; margin: 0 auto; color: var(--ev-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .ev-page *, .ev-page *::before, .ev-page *::after { box-sizing: border-box; }

    .ev-page .ev-card { background: #fff; border: 1px solid var(--ev-line); border-radius: 18px; box-shadow: var(--ev-shadow); overflow: hidden; }
    .ev-page .ev-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding: 18px 20px 14px; }
    .ev-page .ev-title { font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0; }
    .ev-page .ev-sub { font-size: 12px; color: var(--ev-muted); margin-top: 2px; }

    .ev-page .ev-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 34px; padding: 0 14px; border: 0; border-radius: 9px; background: var(--ev-accent); color: #fff; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; text-decoration: none; white-space: nowrap; transition: filter .15s, background .15s; }
    .ev-page .ev-btn:hover { filter: brightness(1.06); }
    .ev-page .ev-btn.ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .ev-page .ev-btn.ghost:hover { background: rgba(120,120,128,.18); filter: none; }
    .ev-page .ev-btn.sm { height: 30px; padding: 0 12px; font-size: 11.5px; }

    /* Tab (segmented control) */
    .ev-page .ev-tabs { display: inline-flex; gap: 2px; padding: 3px; margin: 0 20px 14px; border-radius: 11px; background: rgba(120,120,128,.12); }
    .ev-page .ev-tab { padding: 6px 14px; border: 0; border-radius: 8px; background: transparent; color: var(--ev-muted); font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; }
    .ev-page .ev-tab.active { background: #fff; color: var(--ev-text); box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 0 0 .5px rgba(0,0,0,.04); }
    .ev-page .ev-tab b { margin-left: 5px; padding: 1px 7px; border-radius: 999px; background: var(--ev-accent-soft); color: #3b7aab; font-size: 11px; }
    .ev-page .ev-pane { display: none; }
    .ev-page .ev-pane.active { display: block; }

    /* Tabel */
    .ev-page .ev-scroll { overflow-x: auto; }
    .ev-page .ev-table { width: 100%; border-collapse: collapse; min-width: 760px; }
    .ev-page .ev-table th { padding: 10px 14px; text-align: left; font-size: 10.5px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--ev-muted); background: #fafafc; border-top: 1px solid var(--ev-line); border-bottom: 1px solid var(--ev-line); white-space: nowrap; }
    .ev-page .ev-table td { padding: 14px; font-size: 12.5px; border-bottom: 1px solid var(--ev-line); vertical-align: top; }
    .ev-page .ev-table tr:last-child td { border-bottom: 0; }
    .ev-page .ev-table tbody tr:hover td { background: #fbfcfe; }
    .ev-page .ev-name { font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; }
    .ev-page .ev-dot { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }
    .ev-page .ev-meta { font-size: 11.5px; color: var(--ev-muted); margin-top: 3px; }
    .ev-page .ev-pic { display: inline-block; margin: 0 4px 4px 0; padding: 2px 8px; border-radius: 999px; background: var(--ev-accent-soft); color: #2f6690; font-size: 11px; font-weight: 600; }
    .ev-page .ev-none { color: #aeaeb2; font-size: 12px; }
    .ev-page .ev-empty { padding: 40px 20px; text-align: center; font-size: 13px; color: var(--ev-muted); }

    .ev-page .ev-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px 3px 8px; border-radius: 999px; font-size: 11px; font-weight: 600; white-space: nowrap; }
    .ev-page .ev-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .ev-page .b-amber  { background: rgba(255,159,10,.16); color: #b26a00; }
    .ev-page .b-blue   { background: rgba(78,139,188,.14); color: #2f6690; }
    .ev-page .b-indigo { background: rgba(88,86,214,.12); color: #4b49c4; }
    .ev-page .b-green  { background: rgba(52,199,89,.14); color: #248a3d; }
    .ev-page .b-red    { background: rgba(255,59,48,.12); color: #c9342a; }

    /* Form kelola (admin) */
    .ev-page .ev-form { display: grid; gap: 6px; width: 210px; margin-left: auto; }
    .ev-page .ev-select { width: 100%; height: 32px; padding: 0 30px 0 10px; border-radius: 8px; border: 1px solid var(--ev-line); background: rgba(120,120,128,.08) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 10px center; appearance: none; font: inherit; font-size: 12px; color: var(--ev-text); cursor: pointer; outline: none; }
    .ev-page .ev-select:focus { background-color: #fff; border-color: var(--ev-accent); box-shadow: 0 0 0 3px var(--ev-accent-soft); }
    .ev-page .ev-pic-box { border: 1px solid var(--ev-line); border-radius: 8px; background: rgba(120,120,128,.05); }
    .ev-page .ev-pic-box summary { list-style: none; padding: 7px 10px; font-size: 12px; cursor: pointer; display: flex; justify-content: space-between; }
    .ev-page .ev-pic-box summary::-webkit-details-marker { display: none; }
    .ev-page .ev-pic-box summary::after { content: "▾"; color: #8e8e93; }
    .ev-page .ev-pic-box[open] summary::after { content: "▴"; }
    .ev-page .ev-pic-list { max-height: 120px; overflow-y: auto; padding: 2px 10px 8px; border-top: 1px solid var(--ev-line); }
    .ev-page .ev-pic-list label { display: flex; align-items: center; gap: 6px; padding: 4px 0; font-size: 12px; cursor: pointer; }
    .ev-page .ev-pic-list input { accent-color: var(--ev-accent); }

    /* Modal detail */
    .ev-modal { display: none; position: fixed; inset: 0; z-index: 99999; align-items: center; justify-content: center; padding: 20px; background: rgba(0,0,0,.4); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif; color: #1d1d1f; }
    .ev-modal.open { display: flex; }
    .ev-modal .ev-box { width: 480px; max-width: 100%; max-height: 90vh; overflow-y: auto; background: #fff; border-radius: 20px; box-shadow: 0 25px 50px rgba(0,0,0,.25); animation: evPop .18s ease-out; }
    @keyframes evPop { from { opacity: 0; transform: scale(.95); } to { opacity: 1; transform: scale(1); } }
    .ev-modal .ev-mhead { position: sticky; top: 0; display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid rgba(0,0,0,.07); background: #fafafc; }
    .ev-modal .ev-mtitle { margin: 0; font-size: 15px; font-weight: 700; }
    .ev-modal .ev-x { width: 32px; height: 32px; border: 0; border-radius: 9px; background: transparent; font-size: 16px; color: #6e6e73; cursor: pointer; }
    .ev-modal .ev-x:hover { background: rgba(120,120,128,.12); color: #1d1d1f; }
    .ev-modal .ev-mbody { padding: 20px; }
    .ev-modal .ev-mname { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 8px; }
    .ev-modal .ev-row { display: flex; justify-content: space-between; gap: 16px; padding: 10px 0; border-bottom: 1px solid rgba(0,0,0,.07); font-size: 13px; }
    .ev-modal .ev-row:last-child { border-bottom: 0; }
    .ev-modal .ev-row > span:first-child { font-weight: 600; color: #6e6e73; flex-shrink: 0; }
    .ev-modal .ev-row > span:last-child { text-align: right; word-break: break-word; }
    .ev-modal .ev-chips { display: flex; flex-wrap: wrap; gap: 4px; justify-content: flex-end; }
    .ev-modal .ev-chip { padding: 2px 8px; border-radius: 999px; background: rgba(78,139,188,.12); color: #2f6690; font-size: 11.5px; font-weight: 600; }
    .ev-modal .ev-mfoot { margin-top: 18px; text-align: right; }
    .ev-modal .ev-close { height: 40px; padding: 0 18px; border: 0; border-radius: 10px; background: rgba(120,120,128,.1); color: #3a3a3c; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; }

    @media (prefers-reduced-motion: reduce) { .ev-modal .ev-box { animation: none; } }
</style>

<div class="ev-page">

    {{-- 1. KALENDER (komponen bersama) --}}
    @include('dashboard._kalender')

    {{-- 2. DAFTAR PERMINTAAN DUKUNGAN KEGIATAN --}}
    <div class="ev-card">
        <div class="ev-head">
            <div>
                <h3 class="ev-title">Permintaan Dukungan Kegiatan</h3>
                <div class="ev-sub">Kelola dan pantau status permohonan dukungan dari Eselon II</div>
            </div>
            @if(!$isAdmin)
                <a href="{{ route('dukungan.create') }}" class="ev-btn">+ Ajukan Dukungan Kegiatan</a>
            @endif
        </div>

        <div class="ev-tabs" role="tablist">
            <button type="button" class="ev-tab active" data-pane="ev-masuk" role="tab">Berjalan<b>{{ $permintaanMasuk->count() }}</b></button>
            <button type="button" class="ev-tab" data-pane="ev-selesai" role="tab">Selesai<b>{{ $permintaanSelesai->count() }}</b></button>
        </div>

        {{-- TAB: BERJALAN --}}
        <div class="ev-pane active" id="ev-masuk">
            <div class="ev-scroll">
                <table class="ev-table">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Waktu & Lokasi</th>
                            <th>Pemohon</th>
                            <th>PIC</th>
                            <th>Status</th>
                            @if($isAdmin)<th style="text-align:right">Kelola</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permintaanMasuk as $item)
                            @php
                                $kat = $KATEGORI[strtolower($item->kategori_warna ?? 'hijau')] ?? $KATEGORI['hijau'];
                                $st  = $STATUS[$item->status] ?? [$item->status, 'b-amber'];
                                $pic = collect((array) ($item->admin_ids ?? []));
                            @endphp
                            <tr>
                                <td>
                                    <div class="ev-name"><span class="ev-dot" style="background: {{ $kat[1] }}" title="{{ $kat[0] }}"></span>{{ $item->nama_event }}</div>
                                    <button type="button" class="ev-btn ghost sm" style="margin-top:8px" data-detail="{{ $detailJson($item) }}">Lihat detail</button>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->translatedFormat('d M Y') }}
                                    <div class="ev-meta">{{ substr((string) $item->jam_pelaksanaan, 0, 5) }} WIB · {{ $item->lokasi }}</div>
                                </td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>
                                    @forelse($pic as $id)
                                        @if(isset($namaPic[$id]))<span class="ev-pic">{{ $namaPic[$id] }}</span>@endif
                                    @empty
                                        <span class="ev-none">Belum ada PIC</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span class="ev-badge {{ $st[1] }}">{{ $st[0] }}</span>
                                    <div class="ev-meta">{{ $kat[0] }}</div>
                                </td>

                                @if($isAdmin)
                                    <td>
                                        <form class="ev-form" action="{{ route('dukungan.updateStatus', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <details class="ev-pic-box">
                                                <summary>PIC: {{ $pic->count() ? $pic->count() . ' dipilih' : 'pilih' }}</summary>
                                                <div class="ev-pic-list">
                                                    @foreach($daftarAdmin as $adm)
                                                        <label>
                                                            <input type="checkbox" name="admin_ids[]" value="{{ $adm->id }}" {{ $pic->contains($adm->id) || $pic->contains((string) $adm->id) ? 'checked' : '' }}>
                                                            {{ $adm->name }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </details>

                                            <select name="kategori_warna" class="ev-select" aria-label="Kategori warna">
                                                @foreach($KATEGORI as $kode => $k)
                                                    <option value="{{ $kode }}" {{ strtolower($item->kategori_warna ?? 'hijau') === $kode ? 'selected' : '' }}>{{ $k[0] }}</option>
                                                @endforeach
                                            </select>

                                            <div style="display:flex; gap:6px">
                                                <select name="status" class="ev-select" aria-label="Status">
                                                    @foreach(['PENDING','DIDELEGASIKAN','DIPROSES','SELESAI','DITOLAK'] as $kode)
                                                        <option value="{{ $kode }}" {{ ($item->status === $kode || ($kode === 'DIDELEGASIKAN' && $item->status === 'DIIDELEGASIKAN')) ? 'selected' : '' }}>{{ $STATUS[$kode][0] }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="ev-btn sm">Simpan</button>
                                            </div>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="{{ $isAdmin ? 6 : 5 }}" class="ev-empty">Tidak ada permintaan yang sedang berjalan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB: SELESAI --}}
        <div class="ev-pane" id="ev-selesai">
            <div class="ev-scroll">
                <table class="ev-table">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Waktu & Lokasi</th>
                            <th>Pemohon</th>
                            <th>PIC</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permintaanSelesai as $item)
                            @php
                                $kat = $KATEGORI[strtolower($item->kategori_warna ?? 'hijau')] ?? $KATEGORI['hijau'];
                                $pic = collect((array) ($item->admin_ids ?? []));
                            @endphp
                            <tr>
                                <td>
                                    <div class="ev-name"><span class="ev-dot" style="background: {{ $kat[1] }}" title="{{ $kat[0] }}"></span>{{ $item->nama_event }}</div>
                                    <button type="button" class="ev-btn ghost sm" style="margin-top:8px" data-detail="{{ $detailJson($item) }}">Lihat detail</button>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->translatedFormat('d M Y') }}
                                    <div class="ev-meta">{{ substr((string) $item->jam_pelaksanaan, 0, 5) }} WIB · {{ $item->lokasi }}</div>
                                </td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>
                                    @forelse($pic as $id)
                                        @if(isset($namaPic[$id]))<span class="ev-pic">{{ $namaPic[$id] }}</span>@endif
                                    @empty
                                        <span class="ev-none">-</span>
                                    @endforelse
                                </td>
                                <td><span class="ev-badge b-green">Selesai</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="ev-empty">Belum ada kegiatan yang selesai.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL KEGIATAN --}}
<div class="ev-modal" id="evModal" role="dialog" aria-modal="true" aria-labelledby="evModalTitle">
    <div class="ev-box">
        <div class="ev-mhead">
            <h3 class="ev-mtitle" id="evModalTitle">Detail Kegiatan</h3>
            <button type="button" class="ev-x" data-tutup aria-label="Tutup">&#10005;</button>
        </div>
        <div class="ev-mbody">
            <h4 class="ev-mname" id="evNama"></h4>
            <div id="evIsi"></div>
            <div class="ev-mfoot"><button type="button" class="ev-close" data-tutup>Tutup</button></div>
        </div>
    </div>
</div>

<script>
(function () {
    /* ---------- Tab ---------- */
    document.querySelectorAll('.ev-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.ev-tab').forEach(function (t) { t.classList.remove('active'); });
            document.querySelectorAll('.ev-pane').forEach(function (p) { p.classList.remove('active'); });
            tab.classList.add('active');
            document.getElementById(tab.dataset.pane).classList.add('active');
        });
    });

    /* ---------- Modal detail ---------- */
    const modal = document.getElementById('evModal');
    const esc = function (t) { const d = document.createElement('div'); d.textContent = t == null ? '' : t; return d.innerHTML; };
    const baris = function (label, isi) { return '<div class="ev-row"><span>' + label + '</span><span>' + isi + '</span></div>'; };
    const chips = function (arr) {
        return arr && arr.length
            ? '<span class="ev-chips">' + arr.map(function (x) { return '<span class="ev-chip">' + esc(x) + '</span>'; }).join('') + '</span>'
            : '-';
    };

    document.querySelectorAll('[data-detail]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const d = JSON.parse(btn.dataset.detail);
            document.getElementById('evNama').textContent = d.nama;

            let html = '';
            html += baris('Tanggal', esc(d.tanggal));
            html += baris('Jam', esc(d.jam) + ' WIB');
            html += baris('Lokasi', esc(d.lokasi));
            html += baris('Pemohon', esc(d.pemohon));
            html += baris('Pejabat hadir', esc(d.pejabat));
            html += baris('Jumlah peserta', esc(d.peserta));
            html += baris('Jenis dukungan', chips(d.dukungan));
            if (d.desain)  html += baris('Desain grafis', esc(d.desain));
            if (d.editing) html += baris('Platform editing', esc(d.editing));
            if (d.merch)   html += baris('Merchandise', esc(d.merch));
            html += baris('PIC', chips(d.pic));
            html += baris('Status', esc(d.status));
            html += baris('Label kalender', esc(d.kategori));

            document.getElementById('evIsi').innerHTML = html;
            modal.classList.add('open');
        });
    });

    const tutup = function () { modal.classList.remove('open'); };
    modal.querySelectorAll('[data-tutup]').forEach(function (b) { b.addEventListener('click', tutup); });
    modal.addEventListener('click', function (e) { if (e.target === modal) tutup(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') tutup(); });
})();
</script>

@endsection