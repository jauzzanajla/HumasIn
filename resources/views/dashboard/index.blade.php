@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Kelola dan tindak lanjuti layanan Humas DJPK')

@section('content')

<style>
    .admin-dashboard { display: flex; flex-direction: column; gap: 22px; }
    .welcome-card { background: #ffffff; border: 1px solid #edf0f3; border-radius: 14px; padding: 24px 26px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 3px 15px rgba(25, 35, 45, 0.025); }
    .welcome-title { font-size: 18px; font-weight: 700; color: #20242b; }
    .welcome-description { margin-top: 6px; font-size: 11px; color: #9298a1; }
    .welcome-badge { display: inline-flex; align-items: center; padding: 7px 11px; background: #eef7f0; color: #579462; border-radius: 20px; font-size: 10px; font-weight: 600; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .stat-card { background: #ffffff; border: 1px solid #edf0f3; border-radius: 13px; padding: 20px; box-shadow: 0 3px 15px rgba(25, 35, 45, 0.025); }
    .stat-label { font-size: 10px; color: #9298a1; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
    .stat-value { margin-top: 9px; font-size: 25px; font-weight: 700; color: #20242b; }
    .stat-description { margin-top: 5px; font-size: 10px; color: #a0a5ad; }
    .stat-green { border-top: 3px solid #6eae78; }
    .stat-orange { border-top: 3px solid #e5a044; }
    .stat-blue { border-top: 3px solid #5c88d9; }
    .stat-brown { border-top: 3px solid #8b654b; }
    .dashboard-grid { display: grid; grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr); gap: 18px; }
    .dashboard-card { background: #ffffff; border: 1px solid #edf0f3; border-radius: 14px; box-shadow: 0 3px 15px rgba(25, 35, 45, 0.025); overflow: hidden; }
    .card-header { padding: 18px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #edf0f3; }
    .card-title { font-size: 13px; font-weight: 700; color: #20242b; }
    .card-subtitle { margin-top: 4px; font-size: 10px; color: #969ba4; }
    .card-link { font-size: 10px; color: #5d9d68; font-weight: 600; text-decoration: none; }
    .request-table { width: 100%; border-collapse: collapse; }
    .request-table th { padding: 12px 18px; text-align: left; font-size: 9px; color: #9298a1; text-transform: uppercase; letter-spacing: .4px; border-bottom: 1px solid #edf0f3; }
    .request-table td { padding: 14px 18px; font-size: 11px; color: #555b64; border-bottom: 1px solid #f1f2f4; }
    .request-table tr:last-child td { border-bottom: none; }
    .request-name { color: #30353c; font-weight: 600; }
    .request-unit { display: block; margin-top: 3px; font-size: 9px; color: #9aa0a8; }
    .status { display: inline-flex; align-items: center; padding: 5px 9px; border-radius: 20px; font-size: 9px; font-weight: 600; }
    .status-process { background: #fff5e7; color: #c1842f; }
    .status-waiting { background: #edf3fc; color: #537dbd; }
    .status-complete { background: #edf7ef; color: #579462; }
</style>

<div class="admin-dashboard">

   

    {{-- MAIN DASHBOARD --}}
    <div class="dashboard-grid">

        {{-- PERMINTAAN YANG PERLU DITANGANI --}}
        <div class="dashboard-card">
            <div class="card-header">
                <div>
                    <div class="card-title">Permintaan yang Perlu Ditangani</div>
                    <div class="card-subtitle">Daftar permohonan event dari unit Eselon II</div>
                </div>
                <a href="{{ route('dukungan.index') }}" class="card-link">Lihat Semua</a>
            </div>

            <div style="overflow-x:auto;">
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>Permintaan Event</th>
                            <th>Jenis Layanan</th>
                            <th>Tanggal Acara</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permintaanTerbaru ?? [] as $item)
                            <tr>
                                <td>
                                    <div class="request-name">{{ $item->nama_event }}</div>
                                    <span class="request-unit">Pemohon: {{ $item->user->name ?? '-' }}</span>
                                </td>
                                <td>Dukungan Kegiatan</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->format('d M Y') }}</td>
                                <td>
                                    @if($item->status === 'SELESAI')
                                        <span class="status status-complete">Selesai</span>
                                    @elseif(in_array($item->status, ['DIIDELEGASIKAN', 'DIPROSES']))
                                        <span class="status status-process">Diproses</span>
                                    @else
                                        <span class="status status-waiting">Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:30px; color:#a0a5ad;">
                                    Belum ada permintaan yang perlu ditangani.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- KALENDER & EVENT MENDATANG --}}
      {{-- KALENDER & EVENT MENDATANG --}}
        <div class="dashboard-card" x-data="dashboardKalender()">
            <div class="card-header">
                <div>
                    <div class="card-title">Kalender Event & Pengingat</div>
                    <div class="card-subtitle">Agenda kegiatan eselon II dan pengingat mandiri</div>
                </div>
                <a href="{{ route('event.index') }}" class="card-link">Kelola</a>
            </div>

            <div style="padding: 16px 18px;">
                {{-- Navigasi Bulan / Kalender Bar --}}
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
                    <div style="font-weight: 700; font-size: 12px; color: #20242b;" x-text="namaBulanTahun()"></div>
                    <div style="display: flex; gap: 4px;">
                        <button type="button" @click="ubahBulan(-1)" style="background: #f3f4f6; border: none; border-radius: 6px; width: 26px; height: 26px; cursor: pointer; font-weight: 600; color: #374151;">‹</button>
                        <button type="button" @click="ubahBulan(1)" style="background: #f3f4f6; border: none; border-radius: 6px; width: 26px; height: 26px; cursor: pointer; font-weight: 600; color: #374151;">›</button>
                    </div>
                </div>

                {{-- Grid Hari --}}
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center; font-size: 10px; font-weight: 600; color: #9298a1; margin-bottom: 6px;">
                    <div>Min</div><div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div>
                </div>

                {{-- Grid Tanggal Dinamis --}}
                <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center;">
                    <template x-for="kotak in gridHari">
                        <div @click="pilihTanggal(kotak)" 
                             style="min-height: 48px; padding: 3px; display: flex; flex-direction: column; align-items: center; border-radius: 6px; cursor: pointer; transition: background 0.15s;"
                             :style="kotak.dateString === tanggalPilih ? 'background: #eef7f0; border: 1px solid #6eae78;' : (kotak.isCurrentMonth ? 'background: #f9fafb; border: 1px solid #edf0f3;' : 'background: #f3f4f6; opacity: 0.4; border: 1px solid #edf0f3;')">
                            
                            <span style="font-size: 11px; margin-bottom: 2px;"
                                  :style="kotak.isToday ? 'font-weight: 700; color: #579462; background: #dcfce7; border-radius: 50%; width: 18px; height: 18px; display: inline-flex; align-items: center; justify-content: center;' : 'font-weight: 600; color: #374151;'"
                                  x-text="kotak.tanggalAngka">
                            </span>
                            
                            <div style="display: flex; flex-direction: column; gap: 1px; width: 100%; overflow: hidden;">
                                <template x-for="ev in kotak.events">
                                    <div style="color: white; font-size: 8px; padding: 1px 3px; border-radius: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                                         :style="'background-color: ' + getWarnaHex(ev.warna)"
                                         x-text="ev.nama" 
                                         :title="ev.nama">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Legend Warna --}}
                <div style="margin-top: 12px; border-top: 1px solid #edf0f3; padding-top: 8px; display: flex; flex-wrap: wrap; gap: 8px; font-size: 9px; color: #6b7280;">
                    <div style="display: flex; align-items: center; gap: 3px;"><span style="width: 6px; height: 6px; background: #6eae78; border-radius: 50%;"></span> Hijau</div>
                    <div style="display: flex; align-items: center; gap: 3px;"><span style="width: 6px; height: 6px; background: #e5a044; border-radius: 50%;"></span> Oranye</div>
                    <div style="display: flex; align-items: center; gap: 3px;"><span style="width: 6px; height: 6px; background: #5c88d9; border-radius: 50%;"></span> Biru</div>
                    <div style="display: flex; align-items: center; gap: 3px;"><span style="width: 6px; height: 6px; background: #8b654b; border-radius: 50%;"></span> Coklat</div>
                </div>

                {{-- Kotak Rincian Kegiatan pada Tanggal yang Diklik --}}
                <div style="margin-top: 12px; border-top: 1px solid #edf0f3; padding-top: 10px;">
                    <div style="font-size: 11px; font-weight: 700; color: #20242b; margin-bottom: 6px;">
                        Agenda tanggal: <span x-text="tanggalPilih"></span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 4px; max-height: 110px; overflow-y: auto;">
                        <template x-for="ev in getEventsOnSelectedDate()">
                            <div style="font-size: 10px; padding: 6px 8px; background: #f8fafc; border-radius: 6px; border-left: 3px solid #6eae78;" :style="'border-left-color: ' + getWarnaHex(ev.warna)">
                                <div style="font-weight: 700; color: #20242b;" x-text="ev.nama"></div>
                                <div style="font-size: 9px; color: #6b7280; margin-top: 2px;" x-text="'Pemohon / Sumber: ' + ev.pemohon"></div>
                            </div>
                        </template>
                        <div style="font-size: 10px; color: #9298a1; text-align: center; padding: 6px 0;" x-show="getEventsOnSelectedDate().length === 0">
                            Tidak ada kegiatan pada tanggal ini.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection