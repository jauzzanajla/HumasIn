@extends('layouts.app')

@section('title', 'Dashboard Super Admin | HumasIn')
@section('page-title', 'Dashboard')
@section('page-description', 'Pengawasan dan manajemen sistem Humas DJPK')

@section('content')

<style>
    .sa-page {
        --sa-accent: #4E8BBC; --sa-accent-soft: rgba(78,139,188,.12);
        --sa-text: #1d1d1f; --sa-muted: #6e6e73; --sa-line: rgba(0,0,0,.07);
        --sa-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);
        max-width: 1280px; margin: 0 auto; color: var(--sa-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .sa-page *, .sa-page *::before, .sa-page *::after { box-sizing: border-box; }

    .sa-page .sa-head { margin-bottom: 24px; }
    .sa-page .sa-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .sa-page .sa-sub { font-size: 13px; color: var(--sa-muted); margin: 0; }

    .sa-page .sa-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
    .sa-page .sa-stat { display: flex; align-items: center; gap: 16px; padding: 20px; background: #fff; border: 1px solid var(--sa-line); border-radius: 16px; box-shadow: var(--sa-shadow); color: inherit; transition: transform .2s, border-color .2s; }
    .sa-page a.sa-stat:hover { transform: translateY(-2px); border-color: var(--sa-accent); }
    .sa-page .sa-icon { width: 44px; height: 44px; flex-shrink: 0; display: grid; place-items: center; border-radius: 12px; }
    .sa-page .sa-icon svg { width: 22px; height: 22px; }
    .sa-page .t-blue { background: rgba(78,139,188,.14); color: #3b7aab; }
    .sa-page .t-indigo { background: rgba(88,86,214,.12); color: #5856d6; }
    .sa-page .t-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .sa-page .t-amber { background: rgba(255,159,10,.15); color: #c77700; }
    .sa-page .t-red { background: rgba(255,59,48,.12); color: #c9342a; }
    .sa-page .sa-stat-label { font-size: 12px; font-weight: 500; color: var(--sa-muted); margin-bottom: 2px; }
    .sa-page .sa-stat-value { font-size: 28px; font-weight: 700; letter-spacing: -.03em; line-height: 1.1; font-variant-numeric: tabular-nums; }

    .sa-page .sa-row { display: grid; gap: 20px; margin-bottom: 20px; align-items: start; }
    .sa-page .r-21 { grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); }
    .sa-page .r-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }

    .sa-page .sa-card { background: #fff; border: 1px solid var(--sa-line); border-radius: 18px; box-shadow: var(--sa-shadow); overflow: hidden; }
    .sa-page .sa-card-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 18px 20px 12px; }
    .sa-page .sa-card-title { font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0; }
    .sa-page .sa-card-sub { font-size: 12px; color: var(--sa-muted); margin-top: 2px; }
    .sa-page .sa-link { font-size: 12px; font-weight: 600; color: #3b7aab; white-space: nowrap; }
    .sa-page .sa-link:hover { opacity: .7; }
    .sa-page .sa-body { padding: 4px 20px 20px; }
    .sa-page .sa-empty { padding: 32px 20px; text-align: center; color: var(--sa-muted); font-size: 13px; }

    /* Grafik tren */
    .sa-page .sa-chart { display: flex; align-items: flex-end; gap: 14px; height: 190px; padding-top: 8px; }
    .sa-page .sa-col { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; gap: 8px; }
    .sa-page .sa-bar { width: 100%; max-width: 44px; display: flex; flex-direction: column-reverse; border-radius: 8px; overflow: hidden; background: rgba(120,120,128,.08); min-height: 4px; }
    .sa-page .sa-seg-a { background: #4E8BBC; }
    .sa-page .sa-seg-r { background: #5856d6; }
    .sa-page .sa-col-num { font-size: 11px; font-weight: 600; color: var(--sa-muted); font-variant-numeric: tabular-nums; }
    .sa-page .sa-col-lbl { font-size: 12px; color: var(--sa-muted); text-transform: capitalize; }
    .sa-page .sa-legend { display: flex; gap: 16px; margin-top: 14px; font-size: 12px; color: var(--sa-muted); }
    .sa-page .sa-legend span { display: inline-flex; align-items: center; gap: 6px; }
    .sa-page .sa-legend i { width: 9px; height: 9px; border-radius: 3px; }

    /* Bar horizontal */
    .sa-page .sa-h { margin-bottom: 14px; }
    .sa-page .sa-h:last-child { margin-bottom: 0; }
    .sa-page .sa-h-top { display: flex; justify-content: space-between; gap: 10px; font-size: 13px; margin-bottom: 6px; }
    .sa-page .sa-h-top span { font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sa-page .sa-h-top b { font-variant-numeric: tabular-nums; color: var(--sa-muted); font-weight: 600; }
    .sa-page .sa-track { height: 8px; border-radius: 999px; background: rgba(120,120,128,.1); overflow: hidden; }
    .sa-page .sa-fill { height: 100%; border-radius: 999px; background: var(--c, #4E8BBC); }

    /* Peringatan & aktivitas */
    .sa-page .sa-list { padding: 0 8px 8px; }
    .sa-page .sa-item { display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 12px; color: inherit; transition: background .15s; }
    .sa-page a.sa-item:hover { background: #f7f9fc; }
    .sa-page .sa-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .sa-page .d-red { background: #ff3b30; } .sa-page .d-amber { background: #ff9f0a; } .sa-page .d-blue { background: #4E8BBC; }
    .sa-page .sa-item-body { flex: 1; min-width: 0; }
    .sa-page .sa-item-title { font-size: 13px; font-weight: 600; }
    .sa-page .sa-item-meta { font-size: 11.5px; color: var(--sa-muted); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sa-page .sa-arrow { width: 15px; height: 15px; color: #8e8e93; flex-shrink: 0; }
    .sa-page .sa-ok { display: flex; align-items: center; gap: 10px; padding: 28px 20px; justify-content: center; color: #248a3d; font-size: 13px; font-weight: 600; }

    @media (max-width: 1100px) { .sa-page .sa-stats { grid-template-columns: repeat(2, 1fr); } .sa-page .r-21, .sa-page .r-2 { grid-template-columns: 1fr; } }
    @media (max-width: 600px) { .sa-page .sa-stats { grid-template-columns: 1fr; } }
</style>

@php
    $nama = auth()->user()?->karyawan?->nama ?? auth()->user()?->name ?? 'Super Admin';
@endphp

<div class="sa-page">

    <div class="sa-head">
        <h2 class="sa-title">Halo, {{ $nama }}</h2>
        <p class="sa-sub">{{ now()->translatedFormat('l, d F Y') }} · Ringkasan kondisi sistem.</p>
    </div>

    {{-- STATISTIK --}}
    <div class="sa-stats">
        <a href="{{ route('karyawan.index') }}" class="sa-stat">
            <div class="sa-icon t-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0M16 4.5a3.5 3.5 0 0 1 0 7M18 14.5a6.5 6.5 0 0 1 3.5 5.5"/></svg></div>
            <div><div class="sa-stat-label">Karyawan Aktif</div><div class="sa-stat-value">{{ $totalKaryawan }}</div></div>
        </a>
        <a href="{{ route('users.index') }}" class="sa-stat">
            <div class="sa-icon t-indigo"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></div>
            <div><div class="sa-stat-label">Total Akun</div><div class="sa-stat-value">{{ $totalAkun }}</div></div>
        </a>
        <a href="{{ route('users.create') }}" class="sa-stat">
            <div class="sa-icon {{ $belumPunyaAkun ? 't-amber' : 't-green' }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="10" cy="8" r="4"/><path d="M2 21a8 8 0 0 1 14-5M19 14v6M16 17h6"/></svg></div>
            <div><div class="sa-stat-label">Belum Punya Akun</div><div class="sa-stat-value">{{ $belumPunyaAkun }}</div></div>
        </a>
        <a href="{{ route('admin.dashboard') }}" class="sa-stat">
            <div class="sa-icon t-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 3 3 5-6"/></svg></div>
            <div><div class="sa-stat-label">Antrean Persetujuan</div><div class="sa-stat-value" style="font-size:15px;color:#3b7aab;">Buka →</div></div>
        </a>
    </div>

    {{-- TREN + AKUN PER ROLE --}}
    <div class="sa-row r-21">

        <div class="sa-card">
            <div class="sa-card-head">
                <div><h3 class="sa-card-title">Tren Peminjaman</h3><div class="sa-card-sub">6 bulan terakhir, berdasarkan tanggal mulai</div></div>
            </div>
            <div class="sa-body">
                <div class="sa-chart">
                    @foreach ($tren as $t)
                        @php $total = $t['aset'] + $t['ruangan']; @endphp
                        <div class="sa-col">
                            <span class="sa-col-num">{{ $total }}</span>
                            <div class="sa-bar" style="height: {{ max(2, round($total / $maksTren * 150)) }}px">
                                @if ($t['aset'])<div class="sa-seg-a" style="height: {{ $t['aset'] / $total * 100 }}%"></div>@endif
                                @if ($t['ruangan'])<div class="sa-seg-r" style="height: {{ $t['ruangan'] / $total * 100 }}%"></div>@endif
                            </div>
                            <span class="sa-col-lbl">{{ $t['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="sa-legend">
                    <span><i style="background:#4E8BBC"></i> Aset</span>
                    <span><i style="background:#5856d6"></i> Ruangan</span>
                </div>
            </div>
        </div>

        <div class="sa-card">
            <div class="sa-card-head">
                <div><h3 class="sa-card-title">Akun per Role</h3><div class="sa-card-sub">Sebaran hak akses</div></div>
                <a href="{{ route('users.index') }}" class="sa-link">Kelola →</a>
            </div>
            <div class="sa-body">
                @foreach ($akunPerRole as $r)
                    <div class="sa-h">
                        <div class="sa-h-top"><span>{{ $r['nama'] }}</span><b>{{ $r['total'] }}</b></div>
                        <div class="sa-track"><div class="sa-fill" style="width: {{ $r['total'] / $maksRole * 100 }}%"></div></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    

    {{-- PALING SERING DIPINJAM --}}
    <div class="sa-row r-2">
        <div class="sa-card">
            <div class="sa-card-head"><div><h3 class="sa-card-title">Aset Paling Sering Dipinjam</h3><div class="sa-card-sub">Berdasarkan peminjaman disetujui</div></div></div>
            <div class="sa-body">
                @forelse ($topAset as $a)
                    <div class="sa-h">
                        <div class="sa-h-top"><span>{{ $a['nama'] }}</span><b>{{ $a['total'] }}×</b></div>
                        <div class="sa-track"><div class="sa-fill" style="width: {{ $a['total'] / max(1, $topAset->first()['total']) * 100 }}%"></div></div>
                    </div>
                @empty
                    <div class="sa-empty">Belum ada data peminjaman aset.</div>
                @endforelse
            </div>
        </div>

        <div class="sa-card">
            <div class="sa-card-head"><div><h3 class="sa-card-title">Ruangan Paling Sering Dipinjam</h3><div class="sa-card-sub">Berdasarkan peminjaman disetujui</div></div></div>
            <div class="sa-body">
                @forelse ($topRuangan as $a)
                    <div class="sa-h">
                        <div class="sa-h-top"><span>{{ $a['nama'] }}</span><b>{{ $a['total'] }}×</b></div>
                        <div class="sa-track"><div class="sa-fill" style="--c:#5856d6; width: {{ $a['total'] / max(1, $topRuangan->first()['total']) * 100 }}%"></div></div>
                    </div>
                @empty
                    <div class="sa-empty">Belum ada data peminjaman ruangan.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- PERINGATAN + AKTIVITAS --}}
    <div class="sa-row r-2">
        <div class="sa-card">
            <div class="sa-card-head"><div><h3 class="sa-card-title">Peringatan</h3><div class="sa-card-sub">Hal yang perlu perhatian</div></div></div>
            <div class="sa-list">
                @forelse ($peringatan as $p)
                    <a href="{{ $p['url'] }}" class="sa-item">
                        <span class="sa-dot d-{{ $p['level'] }}"></span>
                        <div class="sa-item-body"><div class="sa-item-title">{{ $p['teks'] }}</div></div>
                        <svg class="sa-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
                    </a>
                @empty
                    <div class="sa-ok">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m8 12.5 3 3 5-6"/></svg>
                        Semua aman, tidak ada peringatan.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="sa-card">
            <div class="sa-card-head">
                <div><h3 class="sa-card-title">Aktivitas Terbaru</h3><div class="sa-card-sub">Peminjaman dan dukungan kegiatan</div></div>
                <a href="{{ route('monitoring.riwayat') }}" class="sa-link">Riwayat →</a>
            </div>
            <div class="sa-list">
                @forelse ($aktivitas as $a)
                    <div class="sa-item">
                        <span class="sa-dot d-blue"></span>
                        <div class="sa-item-body">
                            <div class="sa-item-title">{{ $a['objek'] }}</div>
                            <div class="sa-item-meta">{{ $a['teks'] }} · {{ \Carbon\Carbon::parse($a['waktu'])->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="sa-empty">Belum ada aktivitas.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection