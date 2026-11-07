@extends('layouts.app')

@section('title', 'Event & Kalender | HumasIn')
@section('page-title', 'Event & Kalender Kegiatan')
@section('page-description', 'Kalender agenda dan pengelolaan permohonan dukungan kegiatan Humas DJPK')

@section('content')

<style>
    .event-container { display: flex; flex-direction: column; gap: 24px; }
    .section-card { background: #ffffff; border: 1px solid #edf0f3; border-radius: 14px; box-shadow: 0 3px 15px rgba(25, 35, 45, 0.025); overflow: hidden; padding: 22px; }
    
    /* Tab Navigasi Styling */
    .tab-nav { display: flex; gap: 10px; border-bottom: 2px solid #edf0f3; margin-bottom: 18px; padding-bottom: 0; }
    .tab-btn { padding: 10px 18px; font-size: 12px; font-weight: 600; color: #9298a1; background: none; border: none; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.2s; }
    .tab-btn.active { color: #579462; border-bottom-color: #579462; }
    .tab-pane { display: none; }
    .tab-pane.active { display: block; }

    /* Tabel Styling */
    .request-table { width: 100%; border-collapse: collapse; }
    .request-table th { padding: 12px 14px; text-align: left; font-size: 10px; color: #9298a1; text-transform: uppercase; letter-spacing: .4px; border-bottom: 1px solid #edf0f3; background: #fafbfc; }
    .request-table td { padding: 14px 14px; font-size: 11px; color: #4b5563; border-bottom: 1px solid #f1f2f4; vertical-align: middle; }
    
    /* Badge Status */
    .status-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 12px; font-size: 9px; font-weight: 600; }
    .status-pending { background: #fff5e7; color: #c1842f; }
    .status-process { background: #eef2ff; color: #4f46e5; }
    .status-complete { background: #edf7ef; color: #579462; }
    .status-reject { background: #fef2f2; color: #ef4444; }

    /* Tombol Detail & Ajukan */
    .btn-detail { padding: 5px 10px; background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; font-size: 10px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; cursor: pointer; transition: background 0.2s; }
    .btn-detail:hover { background: #e5e7eb; }
    
    .btn-ajukan { padding: 8px 16px; background: #6EAE78; color: white; border-radius: 8px; font-size: 11px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(110, 174, 120, 0.3); transition: background 0.2s; }
    .btn-ajukan:hover { background: #5d9d68; }

    /* Modal Pop-up UI Styling */
    .custom-modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); align-items: center; justify-content: center; }
    .custom-modal-content { background: #fff; border-radius: 12px; width: 420px; max-width: 90%; padding: 22px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); animation: fadeIn 0.2s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    
    .modal-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #f1f2f4; font-size: 11px; }
    .modal-label { font-weight: 600; color: #6b7280; }
    .modal-value { color: #20242b; text-align: right; }
</style>

<div class="event-container">

    {{-- ==========================================
         1. SECTION KALENDER (FullCalendar)
         - Semua role bisa melihat (klik item = detail)
         - Admin & Super Admin bisa tambah/edit/hapus pengingat
           dan mengubah judul + kategori warna event
    ========================================== --}}
    @include('dashboard._kalender')


    {{-- ==========================================
         2. SECTION DAFTAR PENGAJUAN (DENGAN TAB)
    ========================================== --}}
    <div class="section-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <div>
                <div style="font-size: 14px; font-weight: 700; color: #20242b; margin-bottom: 2px;">Daftar & Pengelolaan Permintaan Dukungan Kegiatan</div>
                <div style="font-size: 10px; color: #969ba4;">Kelola dan pantau status permohonan event secara terstruktur</div>
            </div>

            {{-- Tombol Ajukan Event khusus untuk TU Eselon II (Non-Admin) --}}
            @if(!$isAdmin)
                <a href="{{ route('dukungan.create') }}" class="btn-ajukan">
                    + Ajukan Dukungan Kegiatan
                </a>
            @endif
        </div>

        {{-- Navigasi Tab --}}
        <div class="tab-nav">
            <button class="tab-btn active" onclick="switchTab(event, 'tab-masuk')">
                📥 Pengajuan Terbaru ({{ $permintaanMasuk->count() }})
            </button>
            <button class="tab-btn" onclick="switchTab(event, 'tab-selesai')">
                ✅ Selesai ({{ $permintaanSelesai->count() }})
            </button>
        </div>

        {{-- KONTEN TAB 1: PERMINTAAN MASUK / DIPROSES --}}
        <div id="tab-masuk" class="tab-pane active">
            <div style="overflow-x: auto;">
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>Nama Event</th>
                            <th>Detail Event</th>
                            <th>Waktu & Lokasi</th>
                            <th>Pemohon (TU)</th>
                            <th>PIC Ditugaskan</th>
                            <th>Status</th>
                            @if($isAdmin)
                                <th style="text-align: right;">Aksi / Delegasi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permintaanMasuk as $item)
                            @php
                                $supports = [];
                                if($item->support_operator) $supports[] = 'Operator';
                                if($item->support_desain_grafis) $supports[] = 'Desain Grafis';
                                if($item->support_editing) $supports[] = 'Editing';
                                if($item->support_konten_web) $supports[] = 'Konten Web';
                                if($item->support_merchandise) $supports[] = 'Merchandise';
                                if($item->support_dokumentasi) $supports[] = 'Dokumentasi';
                                if($item->support_tim_acara) $supports[] = 'Tim Acara';
                                $supportText = count($supports) > 0 ? implode(', ', $supports) : 'Tidak ada';
                            @endphp
                            <tr>
                                <td style="font-weight: 700; color: #20242b;">
                                    {{ $item->nama_event }}
                                </td>
                                <td>
                                    <button type="button" class="btn-detail" onclick="openDetailModal('{{ $item->nama_event }}', '{{ $item->pejabat_hadir ?? '-' }}', '{{ $item->jumlah_peserta ?? '-' }}', '{{ $supportText }}')">
                                        🔍 Lihat Detail
                                    </button>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->format('d M Y') }}
                                    <div style="font-size: 9px; color: #666;">Jam: {{ $item->jam_pelaksanaan }} | Lokasi: {{ $item->lokasi }}</div>
                                </td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>
                                    @if(!empty($item->admin_ids))
                                        @foreach($item->admin_ids as $aId)
                                            @php $adm = \App\Models\User::find($aId); @endphp
                                            @if($adm) <span style="display: inline-block; background: #eef7f0; color: #579462; padding: 2px 6px; border-radius: 4px; font-size: 9px; margin-bottom: 2px;">✓ {{ $adm->name }}</span><br> @endif
                                        @endforeach
                                    @else
                                        <span style="color: #9ca3af; font-size: 10px;">Belum ada PIC</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge 
                                        @if($item->status == 'PENDING') status-pending 
                                        @elseif(in_array($item->status, ['DIIDELEGASIKAN', 'DIPROSES'])) status-process 
                                        @else status-reject @endif">
                                        {{ $item->status }}
                                    </span>
                                    <div style="font-size: 9px; color: #666; margin-top: 3px;">Kategori: {{ ucfirst($item->kategori_warna ?? 'Hijau') }}</div>
                                </td>

                                @if($isAdmin)
                                    <td style="text-align: right;">
                                        <form action="{{ route('dukungan.updateStatus', $item->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 6px; align-items: flex-end;">
                                            @csrf
                                            @method('PATCH')

                                            {{-- Pilih PIC Admin (Checkbox) --}}
                                            <div style="background: #f9fafb; border: 1px solid #e5e7eb; padding: 6px; border-radius: 6px; text-align: left; width: 150px; max-height: 80px; overflow-y: auto;">
                                                <span style="font-size: 9px; font-weight: 600; color: #374151; display: block; margin-bottom: 2px;">Delegasi ke PIC:</span>
                                                @foreach($daftarAdmin as $adm)
                                                    <label style="display: flex; align-items: center; gap: 4px; font-size: 9px; color: #4b5563; margin-bottom: 2px; cursor: pointer;">
                                                        <input type="checkbox" name="admin_ids[]" value="{{ $adm->id }}" {{ in_array($adm->id, $item->admin_ids ?? []) ? 'checked' : '' }}>
                                                        {{ $adm->name }}
                                                    </label>
                                                @endforeach
                                            </div>

                                            {{-- Pilih Kategori Warna --}}
                                            <select name="kategori_warna" style="padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 9px; width: 150px;">
                                                <option value="hijau" {{ $item->kategori_warna == 'hijau' ? 'selected' : '' }}>Hijau (Internal/Dirjen)</option>
                                                <option value="oranye" {{ $item->kategori_warna == 'oranye' ? 'selected' : '' }}>Oranye (Dihadiri Dirjen)</option>
                                                <option value="biru" {{ $item->kategori_warna == 'biru' ? 'selected' : '' }}>Biru (Eksternal)</option>
                                                <option value="coklat" {{ $item->kategori_warna == 'coklat' ? 'selected' : '' }}>Coklat (Eksternal & Dirjen)</option>
                                            </select>

                                            {{-- Pilih Status & Tombol Simpan --}}
                                            <div style="display: flex; gap: 4px; width: 150px;">
                                                <select name="status" style="padding: 4px 6px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 9px; flex: 1;">
                                                    <option value="PENDING" {{ $item->status == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                                    <option value="DIIDELEGASIKAN" {{ $item->status == 'DIIDELEGASIKAN' ? 'selected' : '' }}>DIIDELEGASIKAN</option>
                                                    <option value="DIPROSES" {{ $item->status == 'DIPROSES' ? 'selected' : '' }}>DIPROSES</option>
                                                    <option value="SELESAI" {{ $item->status == 'SELESAI' ? 'selected' : '' }}>SELESAI</option>
                                                    <option value="DITOLAK" {{ $item->status == 'DITOLAK' ? 'selected' : '' }}>DITOLAK</option>
                                                </select>
                                                <button type="submit" style="padding: 4px 8px; background: #6EAE78; color: white; border: none; border-radius: 4px; font-size: 9px; font-weight: 600; cursor: pointer;">Simpan</button>
                                            </div>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isAdmin ? 7 : 6 }}" style="text-align: center; padding: 40px; color: #9ca3af;">
                                    Tidak ada permintaan masuk atau yang sedang diproses saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- KONTEN TAB 2: SELESAI --}}
        <div id="tab-selesai" class="tab-pane">
            <div style="overflow-x: auto;">
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>Nama Event</th>
                            <th>Detail Event</th>
                            <th>Waktu & Lokasi</th>
                            <th>Pemohon (TU)</th>
                            <th>PIC Bertugas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permintaanSelesai as $item)
                            @php
                                $supports = [];
                                if($item->support_operator) $supports[] = 'Operator';
                                if($item->support_desain_grafis) $supports[] = 'Desain Grafis';
                                if($item->support_editing) $supports[] = 'Editing';
                                if($item->support_konten_web) $supports[] = 'Konten Web';
                                if($item->support_merchandise) $supports[] = 'Merchandise';
                                if($item->support_dokumentasi) $supports[] = 'Dokumentasi';
                                if($item->support_tim_acara) $supports[] = 'Tim Acara';
                                $supportText = count($supports) > 0 ? implode(', ', $supports) : 'Tidak ada';
                            @endphp
                            <tr>
                                <td style="font-weight: 700; color: #20242b;">
                                    {{ $item->nama_event }}
                                </td>
                                <td>
                                    <button type="button" class="btn-detail" onclick="openDetailModal('{{ $item->nama_event }}', '{{ $item->pejabat_hadir ?? '-' }}', '{{ $item->jumlah_peserta ?? '-' }}', '{{ $supportText }}')">
                                        🔍 Lihat Detail
                                    </button>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_pelaksanaan)->format('d M Y') }}
                                    <div style="font-size: 9px; color: #666;">Jam: {{ $item->jam_pelaksanaan }} | Lokasi: {{ $item->lokasi }}</div>
                                </td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>
                                    @if(!empty($item->admin_ids))
                                        @foreach($item->admin_ids as $aId)
                                            @php $adm = \App\Models\User::find($aId); @endphp
                                            @if($adm) <span style="display: inline-block; background: #eef7f0; color: #579462; padding: 2px 6px; border-radius: 4px; font-size: 9px; margin-bottom: 2px;">✓ {{ $adm->name }}</span><br> @endif
                                        @endforeach
                                    @else
                                        <span style="color: #9ca3af; font-size: 10px;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="status-badge status-complete">SELESAI</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: #9ca3af;">
                                    Belum ada riwayat kegiatan yang selesai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

{{-- MODAL POP-UP UI UNTUK DETAIL EVENT --}}
<div id="eventDetailModal" class="custom-modal">
    <div class="custom-modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #edf0f3; padding-bottom: 10px; margin-bottom: 14px;">
            <h3 style="font-size: 13px; font-weight: 700; color: #20242b; margin: 0;">📋 Detail Kegiatan</h3>
            <button type="button" onclick="closeDetailModal()" style="background: none; border: none; font-size: 14px; cursor: pointer; color: #9298a1;">✕</button>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 4px;">
            <div class="modal-row">
                <span class="modal-label">Nama Event</span>
                <span id="modalNamaEvent" class="modal-value" style="font-weight: 700;"></span>
            </div>
            <div class="modal-row">
                <span class="modal-label">Pejabat Hadir</span>
                <span id="modalPejabat" class="modal-value"></span>
            </div>
            <div class="modal-row">
                <span class="modal-label">Jumlah Peserta</span>
                <span id="modalPeserta" class="modal-value"></span>
            </div>
            <div class="modal-row" style="border-bottom: none;">
                <span class="modal-label">Jenis Dukungan (Support)</span>
                <span id="modalSupport" class="modal-value" style="color: #579462; font-weight: 600;"></span>
            </div>
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <button type="button" onclick="closeDetailModal()" style="padding: 6px 16px; background: #6EAE78; color: white; border: none; border-radius: 6px; font-size: 10px; font-weight: 600; cursor: pointer;">Tutup</button>
        </div>
    </div>
</div>

{{-- Skrip JavaScript untuk Navigasi Tab & Modal Pop-up --}}
<script>
    function switchTab(evt, tabId) {
        let panes = document.getElementsByClassName("tab-pane");
        for (let p of panes) {
            p.classList.remove("active");
        }
        let buttons = document.getElementsByClassName("tab-btn");
        for (let b of buttons) {
            b.classList.remove("active");
        }
        document.getElementById(tabId).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    function openDetailModal(nama, pejabat, peserta, support) {
        document.getElementById('modalNamaEvent').innerText = nama;
        document.getElementById('modalPejabat').innerText = pejabat;
        document.getElementById('modalPeserta').innerText = peserta + (peserta !== '-' ? ' orang' : '');
        document.getElementById('modalSupport').innerText = support;
        
        document.getElementById('eventDetailModal').style.display = 'flex';
    }

    function closeDetailModal() {
        document.getElementById('eventDetailModal').style.display = 'none';
    }

    // Tutup modal jika klik di luar area box modal
    document.getElementById('eventDetailModal').addEventListener('click', function (event) {
        if (event.target === this) {
            closeDetailModal();
        }
    });
</script>

@endsection