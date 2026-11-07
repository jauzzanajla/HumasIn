@extends('layouts.app')

@section('title', 'Edit Aset')
@section('page-title', 'Edit Aset')
@section('page-description', 'Perbarui informasi dan spesifikasi data aset')

@section('content')
@include('aset._mac-style')

<div class="mac-wrap mac-narrow">

  

    <form action="{{ route('aset.update', $aset) }}" method="POST" enctype="multipart/form-data" class="mac-card">
        @csrf
        @method('PUT')

        <div class="mac-card-head">
            <div>
                <h3 class="mac-card-title">Sunting Data Aset</h3>
                <p class="mac-card-sub">Perbarui informasi detail spesifikasi dan status barang di bawah ini.</p>
            </div>
        </div>

        <div class="mac-card-body">

            @if ($errors->any())
                <div class="mac-alert">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <div>
                        <strong>Terdapat beberapa kesalahan pengisian:</strong>
                        @foreach ($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mac-form-grid">

                <div>
                    <label class="mac-label">Nama Barang<span class="req">*</span></label>
                    <input type="text" name="nama_aset" class="mac-control" value="{{ old('nama_aset', $aset->nama_aset) }}" required>
                </div>

                <div>
                    <label class="mac-label">Status Aset<span class="req">*</span></label>
                    <select name="status" class="mac-control" required>
                        <option value="">Pilih Status</option>
                        <option value="TERSEDIA" @selected(old('status', $aset->status) === 'TERSEDIA')>Tersedia</option>
                        <option value="TIDAK_TERSEDIA" @selected(old('status', $aset->status) === 'TIDAK_TERSEDIA')>Tidak Tersedia</option>
                        <option value="PERBAIKAN" @selected(old('status', $aset->status) === 'PERBAIKAN')>Perbaikan</option>
                    </select>
                </div>

                <div>
                    <label class="mac-label">Jumlah Unit<span class="req">*</span></label>
                    <input type="number" name="jumlah" class="mac-control" value="{{ old('jumlah', $aset->jumlah) }}" min="1" required>
                </div>

                <div>
                    <label class="mac-label">Kondisi Barang<span class="req">*</span></label>
                    <select name="kondisi" class="mac-control" required>
                        <option value="">Pilih Kondisi</option>
                        <option value="BAIK" @selected(old('kondisi', $aset->kondisi) === 'BAIK')>Baik</option>
                        <option value="RUSAK_RINGAN" @selected(old('kondisi', $aset->kondisi) === 'RUSAK_RINGAN')>Rusak Ringan</option>
                        <option value="RUSAK_BERAT" @selected(old('kondisi', $aset->kondisi) === 'RUSAK_BERAT')>Rusak Berat</option>
                    </select>
                </div>

                <div class="mac-span-2">
                    <label class="mac-label">Deskripsi Lengkap</label>
                    <textarea name="deskripsi" rows="3" class="mac-control">{{ old('deskripsi', $aset->deskripsi) }}</textarea>
                </div>

                <div class="mac-span-2">
                    <label class="mac-label">Foto Barang</label>

                    @if ($aset->foto)
                        <div class="mac-current">
                            <img src="{{ asset('storage/' . $aset->foto) }}" alt="Foto saat ini">
                            <span>Foto saat ini. Unggah file baru di bawah kalau ingin menggantinya.</span>
                        </div>
                    @endif

                    <div class="mac-drop">
                        <input type="file" name="foto" accept="image/jpeg,image/png,image/webp">
                        <span class="mac-drop-note">JPG, JPEG, PNG, WEBP (Maks. 5 MB)</span>
                    </div>
                </div>

            </div>
        </div>

        <div class="mac-card-foot">
            <a href="{{ route('aset.index') }}" class="mac-btn-ghost">Batal</a>
            <button type="submit" class="mac-btn">Perbarui Aset</button>
        </div>
    </form>

</div>

@endsection