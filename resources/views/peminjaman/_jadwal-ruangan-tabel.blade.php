@php
    $jadwalRuangan = collect($jadwalRuangan ?? []);

    $namaHari = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    $namaBulan = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $fmtTanggal = fn ($c) => $namaHari[$c->dayOfWeek] . ', ' . $c->day . ' ' . $namaBulan[$c->month] . ' ' . $c->year;

    $thStyle = 'padding:14px 18px;font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#6b7280;white-space:nowrap;';
    $tdStyle = 'padding:16px 18px;vertical-align:top;';
@endphp

<div style="overflow-x:auto;">
    <table style="width:100%;min-width:940px;border-collapse:collapse;text-align:left;font-size:14px;">
        <thead>
            <tr style="border-bottom:1px solid #eef0f3;">
                <th style="{{ $thStyle }}">Ruangan</th>
                <th style="{{ $thStyle }}">Tanggal</th>
                <th style="{{ $thStyle }}">Jam</th>
                <th style="{{ $thStyle }}">Peminjam</th>
                <th style="{{ $thStyle }}">Acara</th>
                <th style="{{ $thStyle }}">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($jadwalRuangan as $p)
                @php
                    $mulai = $p->mulai_at;
                    $selesai = $p->selesai_at;
                    $sehari = $mulai->isSameDay($selesai);
                    $pending = $p->status === 'PENDING';

                    $namaRuang = $p->ruangan?->nama_ruangan ?? 'Ruangan tidak diketahui';
                    $lokasi = $p->ruangan?->lokasi;
                    $peminjam = $p->user?->karyawan?->nama ?? $p->user?->name ?? '-';
                    $unit = $p->user?->karyawan?->unit?->nama_unit;
                    $acara = $p->keperluan ?: '-';
                @endphp

                <tr data-row
                    data-ruangan="{{ $namaRuang }}"
                    data-status="{{ $p->status }}"
                    data-berlangsung="{{ $p->berlangsung ? '1' : '0' }}"
                    data-cari="{{ mb_strtolower($namaRuang . ' ' . $peminjam . ' ' . $unit . ' ' . $acara) }}"
                    style="border-bottom:1px solid #f1f3f6;">

                    <td style="{{ $tdStyle }}">
                        <div style="font-weight:700;color:#111827;">{{ $namaRuang }}</div>
                        @if($lokasi)
                            <div style="font-size:12px;color:#9ca3af;margin-top:3px;">{{ $lokasi }}</div>
                        @endif
                    </td>

                    <td style="{{ $tdStyle }}white-space:nowrap;color:#374151;">
                        {{ $fmtTanggal($mulai) }}
                        @unless($sehari)
                            <div style="font-size:12px;color:#9ca3af;margin-top:3px;">sampai {{ $fmtTanggal($selesai) }}</div>
                        @endunless
                    </td>

                    <td style="{{ $tdStyle }}white-space:nowrap;color:#374151;font-weight:600;">
                        {{ $mulai->format('H:i') }} {{ $sehari ? '–' : '→' }} {{ $selesai->format('H:i') }}
                    </td>

                    <td style="{{ $tdStyle }}">
                        <div style="font-weight:600;color:#111827;">{{ $peminjam }}</div>
                        @if($unit)
                            <div style="font-size:12px;color:#9ca3af;margin-top:3px;">{{ $unit }}</div>
                        @endif
                    </td>

                    <td style="{{ $tdStyle }}color:#4b5563;max-width:280px;line-height:1.5;">
                        {{ $acara }}
                    </td>

                    <td style="{{ $tdStyle }}">
                        <div style="display:flex;flex-direction:column;align-items:flex-start;gap:6px;">
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:999px;font-size:12px;font-weight:700;white-space:nowrap;{{ $pending ? 'background:#fef3c7;color:#92400e;' : 'background:#e0ecff;color:#2f5fb3;' }}">
                                <span style="width:6px;height:6px;border-radius:50%;background:currentColor;"></span>
                                {{ $pending ? 'Menunggu persetujuan' : 'Dipinjam' }}
                            </span>

                            @if($p->berlangsung)
                                <span style="display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:999px;font-size:12px;font-weight:700;white-space:nowrap;background:#dcfce7;color:#15803d;">
                                    <span style="width:6px;height:6px;border-radius:50%;background:currentColor;"></span>
                                    Sedang berlangsung
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding:44px 18px;text-align:center;color:#9ca3af;">
                        <div style="font-size:15px;font-weight:600;color:#6b7280;margin-bottom:4px;">Belum ada ruangan yang dipinjam</div>
                        <div style="font-size:13px;">Tidak ada jadwal peminjaman ruangan dalam 30 hari ke depan.</div>
                    </td>
                </tr>
            @endforelse

            @if($jadwalRuangan->isNotEmpty())
                <tr data-kosong-filter style="display:none;">
                    <td colspan="6" style="padding:36px 18px;text-align:center;color:#9ca3af;font-size:14px;">
                        Tidak ada jadwal yang cocok dengan pencarian atau filter.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>