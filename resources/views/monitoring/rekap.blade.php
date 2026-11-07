@extends('layouts.app')

@section('title', 'Rekap | HumasIn')
@section('page-title', 'Rekap')
@section('page-description', 'Rekapitulasi aktivitas dan layanan Humas DJPK')

@section('content')

<style>
    .rk-page {
        --rk-accent: #4E8BBC; --rk-accent-soft: rgba(78,139,188,.12);
        --rk-text: #1d1d1f; --rk-muted: #6e6e73; --rk-line: rgba(0,0,0,.07);
        --rk-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);
        max-width: 1400px; margin: 0 auto; color: var(--rk-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .rk-page *, .rk-page *::before, .rk-page *::after { box-sizing: border-box; }

    .rk-page .rk-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 24px; }
    .rk-page .rk-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .rk-page .rk-sub { font-size: 13px; color: var(--rk-muted); margin: 0; }
    .rk-page .rk-export { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 999px; color: #fff; font-size: 13px; font-weight: 600;
        background: linear-gradient(180deg, #5b98c8, #4E8BBC); box-shadow: 0 1px 2px rgba(0,0,0,.12), 0 6px 16px rgba(78,139,188,.28), inset 0 1px 0 rgba(255,255,255,.25); }
    .rk-page .rk-export:hover { filter: brightness(1.05); }
    .rk-page .rk-export svg { width: 15px; height: 15px; }

    /* Kartu jenis */
    .rk-page .rk-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
    .rk-page .rk-stat { display: flex; align-items: center; gap: 14px; padding: 18px; background: #fff; color: inherit; border: 1px solid var(--rk-line); border-radius: 16px; box-shadow: var(--rk-shadow); transition: transform .2s, border-color .2s, box-shadow .2s; }
    .rk-page .rk-stat:hover { transform: translateY(-2px); border-color: var(--rk-accent); }
    .rk-page .rk-stat.active { border-color: var(--rk-accent); box-shadow: 0 0 0 4px var(--rk-accent-soft), var(--rk-shadow); }
    .rk-page .rk-ico { width: 44px; height: 44px; flex-shrink: 0; display: grid; place-items: center; border-radius: 12px; }
    .rk-page .rk-ico svg { width: 22px; height: 22px; }
    .rk-page .t-blue { background: rgba(78,139,188,.14); color: #3b7aab; }
    .rk-page .t-indigo { background: rgba(88,86,214,.12); color: #5856d6; }
    .rk-page .t-amber { background: rgba(255,159,10,.15); color: #c77700; }
    .rk-page .t-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .rk-page .rk-stat-label { font-size: 12px; font-weight: 500; color: var(--rk-muted); margin-bottom: 2px; }
    .rk-page .rk-stat-value { font-size: 26px; font-weight: 700; letter-spacing: -.03em; line-height: 1.1; font-variant-numeric: tabular-nums; }

    /* Panel */
    .rk-page .rk-panel { background: #fff; border: 1px solid var(--rk-line); border-radius: 18px; box-shadow: var(--rk-shadow); overflow: hidden; margin-bottom: 32px; }
    .rk-page .rk-panel-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding: 18px 20px 4px; }
    .rk-page .rk-panel-title { font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0; }
    .rk-page .rk-count { display: inline-flex; padding: 3px 11px; border-radius: 999px; font-size: 12px; font-weight: 600; background: var(--rk-accent-soft); color: #3b7aab; }

    /* Ringkasan status */
    .rk-page .rk-chips { display: flex; gap: 8px; flex-wrap: wrap; padding: 12px 20px 4px; }
    .rk-page .rk-chip { display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; background: rgba(120,120,128,.1); color: #3a3a3c; transition: background .15s; }
    .rk-page .rk-chip:hover { background: rgba(120,120,128,.18); }
    .rk-page .rk-chip.active { background: var(--rk-accent); color: #fff; }
    .rk-page .rk-chip b { font-variant-numeric: tabular-nums; opacity: .8; }

    /* Filter */
    .rk-page .rk-filter { display: flex; align-items: flex-end; gap: 10px; flex-wrap: wrap; padding: 14px 20px 16px; border-bottom: 1px solid var(--rk-line); }
    .rk-page .rk-field { display: flex; flex-direction: column; gap: 4px; }
    .rk-page .rk-field.grow { flex: 1; min-width: 240px; }
    .rk-page .rk-field label { font-size: 11px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; color: var(--rk-muted); }
    .rk-page .rk-input, .rk-page .rk-select { height: 38px; padding: 0 12px; border-radius: 10px; border: 1px solid transparent; background: rgba(120,120,128,.1); color: var(--rk-text); font: inherit; font-size: 13px; outline: none; transition: background .15s, box-shadow .15s, border-color .15s; }
    .rk-page .rk-select { padding-right: 30px; appearance: none; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; }
    .rk-page .rk-input:focus, .rk-page .rk-select:focus { background-color: #fff; border-color: var(--rk-accent); box-shadow: 0 0 0 4px var(--rk-accent-soft); }
    .rk-page .rk-btn { display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 18px; border-radius: 10px; border: 0; background: var(--rk-accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; }
    .rk-page .rk-btn:hover { filter: brightness(1.06); }
    .rk-page .rk-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; } .rk-page .rk-btn-ghost:hover { background: rgba(120,120,128,.18); filter: none; }

    /* Tabel */
    .rk-page .rk-wrap { overflow-x: auto; }
    .rk-page .rk-table { width: 100%; min-width: 1000px; border-collapse: collapse; }
    .rk-page .rk-table thead th { padding: 12px 18px; text-align: left; background: #fafafc; font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--rk-muted); border-bottom: 1px solid var(--rk-line); white-space: nowrap; }
    .rk-page .rk-table tbody td { padding: 14px 18px; vertical-align: top; border-bottom: 1px solid var(--rk-line); font-size: 13px; }
    .rk-page .rk-table tbody tr:last-child td { border-bottom: 0; }
    .rk-page .rk-table tbody tr:hover { background: #f7f9fc; }
    .rk-page .rk-no { color: var(--rk-muted); font-variant-numeric: tabular-nums; }
    .rk-page .rk-strong { font-weight: 600; letter-spacing: -.005em; }
    .rk-page .rk-small { display: block; font-size: 11.5px; color: var(--rk-muted); margin-top: 2px; font-weight: 400; line-height: 1.45; }
    .rk-page .rk-kind { display: inline-flex; padding: 2px 9px; border-radius: 999px; font-size: 11px; font-weight: 600; margin-bottom: 4px; }
    .rk-page .rk-kind.aset { background: rgba(78,139,188,.14); color: #3b7aab; }
    .rk-page .rk-kind.ruangan { background: rgba(88,86,214,.12); color: #5856d6; }
    .rk-page .rk-tags { display: flex; flex-wrap: wrap; gap: 4px; max-width: 220px; }
    .rk-page .rk-tag { padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; background: rgba(78,139,188,.12); color: #3b7aab; }
    .rk-page .rk-dot { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .rk-page .rk-dot::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .rk-page .b-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .rk-page .b-amber { background: rgba(255,159,10,.16); color: #b26a00; }
    .rk-page .b-red   { background: rgba(255,59,48,.12); color: #c9342a; }
    .rk-page .b-blue  { background: rgba(78,139,188,.14); color: #3b7aab; }
    .rk-page .rk-reason { margin-top: 6px; font-size: 11.5px; color: #b42318; line-height: 1.4; max-width: 240px; }
    .rk-page .rk-late { color: #c9342a; font-weight: 600; }

    .rk-page .rk-empty { padding: 64px 20px; text-align: center; color: var(--rk-muted); font-size: 13px; }
    .rk-page .rk-empty svg { width: 38px; height: 38px; color: #c7c7cc; margin-bottom: 10px; }
    .rk-page .rk-empty strong { display: block; font-size: 15px; color: var(--rk-text); margin-bottom: 4px; }
    .rk-page .rk-foot { display: flex; justify-content: flex-end; padding: 14px 20px; border-top: 1px solid var(--rk-line); background: #fafafc; }
    .rk-page .rk-foot svg { width: 15px; height: 15px; }

    @media (max-width: 1100px) { .rk-page .rk-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .rk-page .rk-stats { grid-template-columns: 1fr; } }
</style>

@php
    use Carbon\Carbon;

    $labelStatus = ['PENDING' => 'Menunggu', 'DISETUJUI' => 'Disetujui', 'DITOLAK' => 'Ditolak', 'DIIDELEGASIKAN' => 'Didelegasikan', 'DIPROSES' => 'Diproses', 'SELESAI' => 'Selesai', 'DIKEMBALIKAN' => 'Dikembalikan'];
    $kelasStatus = ['PENDING' => 'b-amber', 'DISETUJUI' => 'b-green', 'DITOLAK' => 'b-red', 'DIIDELEGASIKAN' => 'b-blue', 'DIPROSES' => 'b-amber', 'SELESAI' => 'b-green', 'DIKEMBALIKAN' => 'b-blue'];

    $jenis = [
        'publikasi'  => ['Publikasi Web', 'pengajuan', 't-green',  '<path d="M3 11l18-8v18L3 13zM11 15l1 6"/>'],
        'peminjaman' => ['Peminjaman', 'transaksi', 't-blue', '<path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3 8 9 5 9-5M12 13v8"/>'],
        'event'      => ['Event & Dukungan', 'agenda', 't-indigo', '<rect x="3" y="4" width="18" height="17" rx="3"/><path d="M3 9h18M8 2v4M16 2v4"/>'],
        'opini'      => ['Opini Eselon II', 'naskah', 't-amber', '<path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>'],
    ];

    $pemohon = fn ($x) => $x->user?->karyawan?->nama ?? $x->user?->name ?? '—';
    $unitDari = fn ($x) => $x->user?->karyawan?->unit?->nama_unit ?? '—';

    $keepQuery = request()->except('page');
    $linkTipe = fn ($t) => route('monitoring.rekap', ['tipe' => $t]);
    $linkStatus = fn ($s) => route('monitoring.rekap', array_merge($keepQuery, ['status' => $s]));

    $adaFilter = request()->filled('search') || request()->filled('status') || request()->filled('tanggal_mulai') || request()->filled('tanggal_selesai') || request()->filled('kategori');
    $totalStatus = collect($jumlahStatus)->sum();

    $supportLabel = ['support_operator' => 'Operator', 'support_desain_grafis' => 'Desain Grafis', 'support_editing' => 'Editing', 'support_konten_web' => 'Konten Web', 'support_merchandise' => 'Merchandise', 'support_dokumentasi' => 'Dokumentasi', 'support_tim_acara' => 'Tim Acara'];
@endphp

<div class="rk-page">

    <div class="rk-head">
        <div>
            <h2 class="rk-title">Rekap Aktivitas & Layanan</h2>
            <p class="rk-sub">Riwayat dan rekapitulasi publikasi, peminjaman, event, dan opini dalam satu halaman.</p>
        </div>
        <a href="{{ route('monitoring.rekap.export', array_merge(['tipe' => $tipe], $keepQuery)) }}" class="rk-export">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v12M7 11l5 5 5-5M5 20h14"/></svg>
            Unduh {{ $adaFilter ? 'Hasil Filter' : 'Rekap' }} (CSV)
        </a>
    </div>

    {{-- JENIS REKAP --}}
    <div class="rk-stats">
        @foreach ($jenis as $kode => [$nama, $satuan, $tone, $path])
            <a href="{{ $linkTipe($kode) }}" class="rk-stat {{ $tipe === $kode ? 'active' : '' }}">
                <div class="rk-ico {{ $tone }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $path !!}</svg></div>
                <div>
                    <div class="rk-stat-label">{{ $nama }}</div>
                    <div class="rk-stat-value">{{ $total[$kode] }}</div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="rk-panel">

        <div class="rk-panel-head">
            <h3 class="rk-panel-title">Rekap {{ $jenis[$tipe][0] }}</h3>
            <span class="rk-count">{{ $items->total() }} data{{ $adaFilter ? ' (terfilter)' : '' }}</span>
        </div>

        {{-- RINGKASAN STATUS --}}
        <div class="rk-chips">
            <a href="{{ route('monitoring.rekap', array_merge($keepQuery, ['status' => null])) }}" class="rk-chip {{ ! request('status') ? 'active' : '' }}">Semua <b>{{ $totalStatus }}</b></a>
            @foreach ($statusOpsi as $s)
                <a href="{{ $linkStatus($s) }}" class="rk-chip {{ request('status') === $s ? 'active' : '' }}">{{ $labelStatus[$s] }} <b>{{ $jumlahStatus[$s] ?? 0 }}</b></a>
            @endforeach
        </div>

        {{-- FILTER --}}
        <form method="GET" action="{{ route('monitoring.rekap') }}" class="rk-filter">
            <input type="hidden" name="tipe" value="{{ $tipe }}">

            <div class="rk-field grow">
                <label>Kata kunci</label>
                <input type="text" name="search" class="rk-input" value="{{ request('search') }}" placeholder="Nama, NIP, kegiatan, fasilitas, keperluan…">
            </div>

            @if ($tipe === 'peminjaman')
                <div class="rk-field">
                    <label>Kategori</label>
                    <select name="kategori" class="rk-select">
                        <option value="">Semua</option>
                        <option value="aset" @selected(request('kategori') === 'aset')>Aset</option>
                        <option value="ruangan" @selected(request('kategori') === 'ruangan')>Ruangan</option>
                    </select>
                </div>
            @endif

            <div class="rk-field">
                <label>Status</label>
                <select name="status" class="rk-select">
                    <option value="">Semua</option>
                    @foreach ($statusOpsi as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ $labelStatus[$s] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="rk-field">
                <label>Dari tanggal</label>
                <input type="date" name="tanggal_mulai" class="rk-input" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="rk-field">
                <label>Sampai tanggal</label>
                <input type="date" name="tanggal_selesai" class="rk-input" value="{{ request('tanggal_selesai') }}">
            </div>

            <button type="submit" class="rk-btn">Terapkan</button>
            @if ($adaFilter)
                <a href="{{ route('monitoring.rekap', ['tipe' => $tipe]) }}" class="rk-btn rk-btn-ghost">Reset</a>
            @endif
        </form>

        {{-- TABEL --}}
        <div class="rk-wrap">
            <table class="rk-table">

                @if ($tipe === 'peminjaman')
                    <thead><tr>
                        <th>No</th><th>Peminjam</th><th>Fasilitas</th><th>Jumlah</th><th>Jadwal</th><th>Keperluan</th><th>Status</th>
                    </tr></thead>
                    <tbody>
                    @forelse ($items as $x)
                        @php
                            $aset = ! empty($x->aset_id);
                            $mulai = Carbon::parse(Carbon::parse($x->tanggal_mulai)->format('Y-m-d') . ' ' . $x->jam_mulai);
                            $selesai = Carbon::parse(Carbon::parse($x->tanggal_selesai)->format('Y-m-d') . ' ' . $x->jam_selesai);
                            $kembali = $x->status_pengembalian === 'DIKEMBALIKAN';
                            $telat = $x->status === 'DISETUJUI' && ! $kembali && $selesai->lt(now());
                            $kode = ($x->status === 'DISETUJUI' && $kembali) ? 'DIKEMBALIKAN' : $x->status;
                        @endphp
                        <tr>
                            <td class="rk-no">{{ $items->firstItem() + $loop->index }}</td>
                            <td><span class="rk-strong">{{ $pemohon($x) }}</span><span class="rk-small">{{ $unitDari($x) }}</span>@if ($x->no_hp)<span class="rk-small">{{ $x->no_hp }}</span>@endif</td>
                            <td><span class="rk-kind {{ $aset ? 'aset' : 'ruangan' }}">{{ $aset ? 'Aset' : 'Ruangan' }}</span><br><span class="rk-strong">{{ $aset ? ($x->aset?->nama_aset ?? '—') : ($x->ruangan?->nama_ruangan ?? '—') }}</span></td>
                            <td>{{ $aset ? $x->jumlah . ' unit' : '1 ruangan' }}</td>
                            <td style="white-space:nowrap;">{{ $mulai->translatedFormat('d M Y') }} · {{ $mulai->format('H:i') }}<span class="rk-small">s/d {{ $selesai->translatedFormat('d M Y') }} · {{ $selesai->format('H:i') }}</span></td>
                            <td style="max-width:240px;">{{ $x->keperluan ?: '—' }}</td>
                            <td>
                                <span class="rk-dot {{ $kelasStatus[$kode] ?? 'b-blue' }}">{{ $labelStatus[$kode] ?? $kode }}</span>
                                @if ($telat)<span class="rk-small rk-late">Terlambat dikembalikan</span>@endif
                                @if ($x->status === 'DITOLAK' && $x->alasan_penolakan)<div class="rk-reason">Alasan: {{ $x->alasan_penolakan }}</div>@endif
                            </td>
                        </tr>
                    @empty
                        @include('monitoring._rekap-kosong', ['kolom' => 7])
                    @endforelse
                    </tbody>

                @elseif ($tipe === 'event')
                    <thead><tr>
                        <th>No</th><th>Pemohon</th><th>Event</th><th>Waktu & Lokasi</th><th>Support</th><th>PIC</th><th>Status</th>
                    </tr></thead>
                    <tbody>
                    @forelse ($items as $x)
                        @php
                            $support = collect($supportLabel)->filter(fn ($l, $k) => $x->{$k});
                            $pic = collect($x->admin_ids ?? [])->map(fn ($id) => $namaPic[$id] ?? null)->filter();
                        @endphp
                        <tr>
                            <td class="rk-no">{{ $items->firstItem() + $loop->index }}</td>
                            <td><span class="rk-strong">{{ $pemohon($x) }}</span><span class="rk-small">{{ $unitDari($x) }}</span></td>
                            <td><span class="rk-strong">{{ $x->nama_event }}</span>
                                @if ($x->pejabat_hadir)<span class="rk-small">Pejabat: {{ $x->pejabat_hadir }}</span>@endif
                                @if ($x->jumlah_peserta)<span class="rk-small">{{ $x->jumlah_peserta }} peserta</span>@endif</td>
                            <td>{{ Carbon::parse($x->tanggal_pelaksanaan)->translatedFormat('d M Y') }} · {{ substr($x->jam_pelaksanaan, 0, 5) }}<span class="rk-small">{{ $x->lokasi }}</span></td>
                            <td>@if ($support->isNotEmpty())<div class="rk-tags">@foreach ($support as $l)<span class="rk-tag">{{ $l }}</span>@endforeach</div>@else —@endif</td>
                            <td>{{ $pic->isNotEmpty() ? $pic->implode(', ') : '—' }}</td>
                            <td><span class="rk-dot {{ $kelasStatus[$x->status] ?? 'b-blue' }}">{{ $labelStatus[$x->status] ?? $x->status }}</span></td>
                        </tr>
                    @empty
                        @include('monitoring._rekap-kosong', ['kolom' => 7])
                    @endforelse
                    </tbody>

                @elseif ($tipe === 'opini')
                    <thead><tr>
                        <th>No</th><th>Pemohon</th><th>Judul Opini</th><th>Penulis</th><th>Diajukan</th><th>Status</th>
                    </tr></thead>
                    <tbody>
                    @forelse ($items as $x)
                        <tr>
                            <td class="rk-no">{{ $items->firstItem() + $loop->index }}</td>
                            <td><span class="rk-strong">{{ $pemohon($x) }}</span><span class="rk-small">{{ $unitDari($x) }}</span></td>
                            <td class="rk-strong" style="max-width:320px;">{{ $x->judul_opini }}</td>
                            <td>{{ $x->penulis }}</td>
                            <td style="white-space:nowrap;">{{ $x->created_at?->translatedFormat('d M Y') }}<span class="rk-small">{{ $x->created_at?->format('H:i') }}</span></td>
                            <td>
                                <span class="rk-dot {{ $kelasStatus[$x->status] ?? 'b-blue' }}">{{ $labelStatus[$x->status] ?? $x->status }}</span>
                                @if ($x->status === 'DITOLAK' && $x->alasan_penolakan)<div class="rk-reason">Alasan: {{ $x->alasan_penolakan }}</div>@endif
                            </td>
                        </tr>
                    @empty
                        @include('monitoring._rekap-kosong', ['kolom' => 6])
                    @endforelse
                    </tbody>

                @else
                    <thead><tr>
                        <th>No</th><th>Pemohon</th><th>Kegiatan</th><th>Tanggal & Lokasi</th><th>Diajukan</th><th>Status</th>
                    </tr></thead>
                    <tbody>
                    @forelse ($items as $x)
                        <tr>
                            <td class="rk-no">{{ $items->firstItem() + $loop->index }}</td>
                            <td><span class="rk-strong">{{ $pemohon($x) }}</span><span class="rk-small">{{ $unitDari($x) }}</span></td>
                            <td class="rk-strong" style="max-width:320px;">{{ $x->nama_kegiatan }}</td>
                            <td>{{ Carbon::parse($x->when_tanggal)->translatedFormat('d M Y') }}<span class="rk-small">{{ $x->where_lokasi }}</span></td>
                            <td style="white-space:nowrap;">{{ $x->created_at?->translatedFormat('d M Y') }}<span class="rk-small">{{ $x->created_at?->format('H:i') }}</span></td>
                            <td>
                                <span class="rk-dot {{ $kelasStatus[$x->status] ?? 'b-blue' }}">{{ $labelStatus[$x->status] ?? $x->status }}</span>
                                @if ($x->status === 'DITOLAK' && $x->alasan_penolakan)<div class="rk-reason">Alasan: {{ $x->alasan_penolakan }}</div>@endif
                            </td>
                        </tr>
                    @empty
                        @include('monitoring._rekap-kosong', ['kolom' => 6])
                    @endforelse
                    </tbody>
                @endif

            </table>
        </div>

        @if ($items->hasPages())
            <div class="rk-foot">{{ $items->links() }}</div>
        @endif
    </div>

</div>

@endsection