@extends('layouts.app')

@section('title', 'Detail Aset')
@section('page-title', 'Detail Aset')
@section('page-description', 'Informasi lengkap spesifikasi dan status perangkat')

@section('content')
@include('aset._mac-style')

<div class="mac-wrap mac-narrow">



    <div class="mac-card">

        <div class="mac-card-head">
            <div>
                <h3 class="mac-card-title">Detail Informasi Aset</h3>
                <p class="mac-card-sub">ID Aset: #{{ $aset->id }}</p>
            </div>

            @if ($aset->status === 'TERSEDIA')
                <span class="mac-badge badge-green">Tersedia</span>
            @elseif ($aset->status === 'PERBAIKAN')
                <span class="mac-badge badge-amber">Perbaikan</span>
            @else
                <span class="mac-badge badge-red">Tidak Tersedia</span>
            @endif
        </div>

        <div class="mac-card-body">
            <div class="mac-detail">

                {{-- FOTO --}}
                <div>
                    @if ($aset->foto)
                        <img src="{{ asset('storage/' . $aset->foto) }}" alt="{{ $aset->nama_aset }}" class="mac-photo">
                    @else
                        <div class="mac-photo-empty">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                            Tidak ada foto
                        </div>
                    @endif
                </div>

                {{-- INFO --}}
                <div>
                    <div class="mac-kv-label" style="margin-bottom: 6px;">Nama Barang</div>
                    <h2 class="mac-detail-name">{{ $aset->nama_aset }}</h2>

                    <div class="mac-kv-grid">
                        <div class="mac-kv">
                            <div class="mac-kv-label">Jumlah Unit</div>
                            <div class="mac-kv-value">{{ $aset->jumlah }} Unit</div>
                        </div>

                        <div class="mac-kv">
                            <div class="mac-kv-label">Kondisi</div>
                            <div class="mac-kv-value">
                                @if ($aset->kondisi === 'BAIK')
                                    <span class="mac-cond cond-baik">Baik</span>
                                @elseif ($aset->kondisi === 'RUSAK_RINGAN')
                                    <span class="mac-cond cond-ringan">Rusak Ringan</span>
                                @else
                                    <span class="mac-cond cond-berat">Rusak Berat</span>
                                @endif
                            </div>
                        </div>

                        <div class="mac-kv">
                            <div class="mac-kv-label">Status Aset</div>
                            <div class="mac-kv-value">
                                @if ($aset->status === 'TERSEDIA')
                                    <span class="mac-badge badge-green">Tersedia</span>
                                @elseif ($aset->status === 'PERBAIKAN')
                                    <span class="mac-badge badge-amber">Perbaikan</span>
                                @else
                                    <span class="mac-badge badge-red">Tidak Tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mac-kv-label">Deskripsi</div>
                    <div class="mac-note">{{ $aset->deskripsi ?: 'Tidak ada deskripsi yang ditambahkan.' }}</div>
                </div>

            </div>
        </div>

        <div class="mac-card-foot">
            <a href="{{ route('aset.index') }}" class="mac-btn-ghost">Kembali</a>
            <a href="{{ route('aset.edit', $aset) }}" class="mac-btn">Edit Aset</a>
        </div>

    </div>
</div>

@endsection