{{--
    Kartu statistik jadwal ruangan.
    Variabel: $jadwalRuangan (dari PeminjamanController@jadwalRuangan)
--}}
@php
    $jadwalRuangan = collect($jadwalRuangan ?? []);

    $stat = [
        [
            'label' => 'Ruangan Terpakai',
            'nilai' => $jadwalRuangan->pluck('ruangan_id')->unique()->count(),
            'bg'    => '#e8f0fe',
            'fg'    => '#4f7fd6',
            'ikon'  => 'pintu',
        ],
        [
            'label' => 'Total Jadwal',
            'nilai' => $jadwalRuangan->count(),
            'bg'    => '#ede9fe',
            'fg'    => '#6d5bd0',
            'ikon'  => 'kalender',
        ],
        [
            'label' => 'Berlangsung Sekarang',
            'nilai' => $jadwalRuangan->where('berlangsung', true)->count(),
            'bg'    => '#dcfce7',
            'fg'    => '#16a34a',
            'ikon'  => 'jam',
        ],
    ];
@endphp

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;">
    @foreach($stat as $s)
        <div style="display:flex;align-items:center;gap:16px;padding:20px 22px;background:#fff;border:1px solid #e8ecf1;border-radius:16px;box-shadow:0 2px 10px rgba(25,35,45,.04);">

            <div style="width:52px;height:52px;border-radius:14px;background:{{ $s['bg'] }};color:{{ $s['fg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    @if($s['ikon'] === 'pintu')
                        <path d="M18 20V6a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14"/>
                        <path d="M2 20h20"/>
                        <path d="M14 12v.01"/>
                    @elseif($s['ikon'] === 'kalender')
                        <rect width="18" height="18" x="3" y="4" rx="2"/>
                        <path d="M16 2v4M8 2v4M3 10h18"/>
                    @else
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    @endif
                </svg>
            </div>

            <div>
                <div style="font-size:14px;font-weight:500;color:#374151;">{{ $s['label'] }}</div>
                <div style="font-size:32px;font-weight:700;color:#111827;line-height:1.2;">{{ $s['nilai'] }}</div>
            </div>

        </div>
    @endforeach
</div>