@extends('layouts.app')

@section('title', 'Tambah Ruangan | HumasIn')
@section('page-title', 'Tambah Ruangan')
@section('page-description', 'Tambahkan data ruangan atau fasilitas baru')

@section('content')

<style>
    .rg-page {
        --rg-accent: #4E8BBC;
        --rg-accent-soft: rgba(78, 139, 188, .12);
        --rg-text: #1d1d1f;
        --rg-muted: #6e6e73;
        --rg-line: rgba(0, 0, 0, .07);
        --rg-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 960px; margin: 0 auto; color: var(--rg-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .rg-page *, .rg-page *::before, .rg-page *::after { box-sizing: border-box; }

    .rg-page .rg-back { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #3b7aab; transition: opacity .15s; }
    .rg-page .rg-back:hover { opacity: .7; }
    .rg-page .rg-back svg { width: 16px; height: 16px; }

    .rg-page .rg-card { background: #fff; border: 1px solid var(--rg-line); border-radius: 18px; box-shadow: var(--rg-shadow); overflow: hidden; }
    .rg-page .rg-card-head { padding: 22px 32px; border-bottom: 1px solid var(--rg-line); }
    .rg-page .rg-card-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 3px; }
    .rg-page .rg-card-sub { font-size: 13px; color: var(--rg-muted); margin: 0; }
    .rg-page .rg-card-body { padding: 32px; }
    .rg-page .rg-card-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 18px 32px; border-top: 1px solid var(--rg-line); background: #fafafc; }

    /* Form */
    .rg-page .rg-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px 24px; }
    .rg-page .rg-span-2 { grid-column: 1 / -1; }
    .rg-page .rg-label { display: block; font-size: 12px; font-weight: 600; color: #3a3a3c; margin-bottom: 7px; }
    .rg-page .rg-label .rg-req { color: #ff3b30; margin-left: 2px; }
    .rg-page .rg-hint { font-size: 11px; color: var(--rg-muted); }
    .rg-page .rg-error { font-size: 11px; color: #c9342a; margin-top: 6px; }

    .rg-page .rg-control {
        width: 100%; height: 40px; padding: 0 14px; border-radius: 10px;
        border: 1px solid var(--rg-line); background: rgba(120,120,128,.08); color: var(--rg-text);
        font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .rg-page .rg-control::placeholder { color: #8e8e93; }
    .rg-page .rg-control:focus { background: #fff; border-color: var(--rg-accent); box-shadow: 0 0 0 4px var(--rg-accent-soft); }
    .rg-page select.rg-control {
        appearance: none; cursor: pointer; padding-right: 36px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 14px center;
    }
    .rg-page textarea.rg-control { height: auto; min-height: 110px; padding: 12px 14px; line-height: 1.5; resize: vertical; }

    /* Upload foto */
    .rg-page .rg-drop {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        padding: 18px 20px; border-radius: 12px; border: 1.5px dashed rgba(78,139,188,.4); background: rgba(78,139,188,.05);
        transition: background .15s, border-color .15s;
    }
    .rg-page .rg-drop:hover { background: rgba(78,139,188,.09); border-color: var(--rg-accent); }
    .rg-page .rg-drop input[type=file] { font-size: 12px; color: var(--rg-muted); cursor: pointer; }
    .rg-page .rg-drop input[type=file]::file-selector-button {
        margin-right: 12px; padding: 7px 14px; border: 0; border-radius: 8px;
        background: #fff; color: #3a3a3c; font: inherit; font-size: 12px; font-weight: 600; cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,.1), 0 0 0 1px var(--rg-line); transition: background .15s;
    }
    .rg-page .rg-drop input[type=file]::file-selector-button:hover { background: #f5f5f7; }

    .rg-page .rg-preview { display: none; margin-top: 14px; }
    .rg-page .rg-preview-label { font-size: 11px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: var(--rg-muted); margin-bottom: 8px; }
    .rg-page .rg-preview img {
        display: block; width: 280px; max-width: 100%; aspect-ratio: 4 / 3; object-fit: cover;
        border-radius: 14px; border: 1px solid var(--rg-line); box-shadow: 0 4px 16px rgba(0,0,0,.08);
    }

    /* Tombol */
    .rg-page .rg-btn, .rg-page .rg-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 20px;
        border-radius: 10px; border: 0; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s;
    }
    .rg-page .rg-btn { background: var(--rg-accent); color: #fff; }
    .rg-page .rg-btn:hover { filter: brightness(1.06); }
    .rg-page .rg-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .rg-page .rg-btn-ghost:hover { background: rgba(120,120,128,.18); }

    @media (max-width: 800px) {
        .rg-page .rg-form-grid { grid-template-columns: 1fr; }
        .rg-page .rg-card-head, .rg-page .rg-card-body, .rg-page .rg-card-foot { padding-left: 20px; padding-right: 20px; }
    }
</style>

<div class="rg-page">

    
    <form action="{{ route('ruangan.store') }}" method="POST" enctype="multipart/form-data" class="rg-card">
        @csrf

        <div class="rg-card-head">
            <h3 class="rg-card-title">Form Tambah Ruangan</h3>
            <p class="rg-card-sub">Lengkapi informasi ruangan yang akan digunakan.</p>
        </div>

        <div class="rg-card-body">
            <div class="rg-form-grid">

                {{-- NAMA --}}
                <div class="rg-span-2">
                    <label class="rg-label">Nama Ruangan<span class="rg-req">*</span></label>
                    <input type="text" name="nama_ruangan" class="rg-control" value="{{ old('nama_ruangan') }}" placeholder="Contoh: Ruang Kolaborasi 1" required>
                    @error('nama_ruangan') <div class="rg-error">{{ $message }}</div> @enderror
                </div>

                {{-- LOKASI --}}
                <div>
                    <label class="rg-label">Lokasi</label>
                    <input type="text" name="lokasi" class="rg-control" value="{{ old('lokasi') }}" placeholder="Contoh: Lantai 3">
                    @error('lokasi') <div class="rg-error">{{ $message }}</div> @enderror
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="rg-label">Status<span class="rg-req">*</span></label>
                    <select name="status" class="rg-control" required>
                        <option value="TERSEDIA" {{ old('status', 'TERSEDIA') === 'TERSEDIA' ? 'selected' : '' }}>Tersedia</option>
                        <option value="TIDAK_TERSEDIA" {{ old('status') === 'TIDAK_TERSEDIA' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                    @error('status') <div class="rg-error">{{ $message }}</div> @enderror
                </div>

                {{-- KAPASITAS --}}
                <div>
                    <label class="rg-label">Kapasitas (orang)<span class="rg-req">*</span></label>
                    <input type="number" name="kapasitas" class="rg-control" value="{{ old('kapasitas') }}" min="1" placeholder="Contoh: 10" required>
                    @error('kapasitas') <div class="rg-error">{{ $message }}</div> @enderror
                </div>

                {{-- KUOTA --}}
                <div>
                    <label class="rg-label">Kuota<span class="rg-req">*</span></label>
                    <input type="number" name="kuota" class="rg-control" value="{{ old('kuota') }}" min="1" placeholder="Contoh: 10" required>
                    @error('kuota') <div class="rg-error">{{ $message }}</div> @enderror
                </div>

                {{-- DURASI --}}
                <div>
                    <label class="rg-label">Durasi Maksimal<span class="rg-req">*</span></label>
                    <input type="number" name="durasi_maksimal" class="rg-control" value="{{ old('durasi_maksimal', 1) }}" min="1" required>
                    @error('durasi_maksimal') <div class="rg-error">{{ $message }}</div> @enderror
                </div>

                {{-- SATUAN --}}
                <div>
                    <label class="rg-label">Satuan Durasi<span class="rg-req">*</span></label>
                    <select name="satuan_durasi" class="rg-control" required>
                        <option value="MENIT" {{ old('satuan_durasi') === 'MENIT' ? 'selected' : '' }}>Menit</option>
                        <option value="JAM" {{ old('satuan_durasi', 'JAM') === 'JAM' ? 'selected' : '' }}>Jam</option>
                        <option value="HARI" {{ old('satuan_durasi') === 'HARI' ? 'selected' : '' }}>Hari</option>
                    </select>
                    @error('satuan_durasi') <div class="rg-error">{{ $message }}</div> @enderror
                </div>

                {{-- DESKRIPSI --}}
                <div class="rg-span-2">
                    <label class="rg-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="rg-control" placeholder="Tambahkan keterangan ruangan...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="rg-error">{{ $message }}</div> @enderror
                </div>

                {{-- FOTO --}}
                <div class="rg-span-2">
                    <label class="rg-label">Foto Ruangan</label>

                    <div class="rg-drop">
                        <input type="file" name="foto" id="fotoRuangan" accept="image/jpeg,image/png,image/webp">
                        <span class="rg-hint">JPG, JPEG, PNG, WEBP (Maks. 5 MB)</span>
                    </div>

                    <div id="previewContainer" class="rg-preview">
                        <div class="rg-preview-label">Preview Foto</div>
                        <img id="previewImage" src="" alt="Preview Ruangan">
                    </div>

                    @error('foto') <div class="rg-error">{{ $message }}</div> @enderror
                </div>

            </div>
        </div>

        <div class="rg-card-foot">
            <a href="{{ route('ruangan.index') }}" class="rg-btn-ghost">Batal</a>
            <button type="submit" class="rg-btn">Simpan Ruangan</button>
        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('fotoRuangan');
    const previewContainer = document.getElementById('previewContainer');
    const previewImage = document.getElementById('previewImage');

    input.addEventListener('change', function () {
        const file = this.files[0];

        if (!file) {
            previewContainer.style.display = 'none';
            previewImage.src = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            previewImage.src = event.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
});
</script>

@endsection