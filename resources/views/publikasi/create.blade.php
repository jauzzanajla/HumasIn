@extends('layouts.app')

@section('title', 'Ajukan Publikasi | HumasIn')
@section('page-title', 'Ajukan Publikasi')
@section('page-description', 'Lengkapi materi, foto, dan informasi 5W + 1H kegiatan')

@section('content')

<style>
    .pc-page {
        --pc-accent: #4E8BBC; --pc-accent-soft: rgba(78,139,188,.12);
        --pc-text: #1d1d1f; --pc-muted: #6e6e73; --pc-line: rgba(0,0,0,.07);
        --pc-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);
        max-width: 960px; margin: 0 auto; color: var(--pc-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .pc-page *, .pc-page *::before, .pc-page *::after { box-sizing: border-box; }

    .pc-page .pc-back { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #3b7aab; }
    .pc-page .pc-back:hover { opacity: .7; }
    .pc-page .pc-back svg { width: 16px; height: 16px; }

    .pc-page .pc-card { background: #fff; border: 1px solid var(--pc-line); border-radius: 18px; box-shadow: var(--pc-shadow); overflow: hidden; margin-bottom: 32px; }
    .pc-page .pc-head { padding: 22px 32px; border-bottom: 1px solid var(--pc-line); }
    .pc-page .pc-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 3px; }
    .pc-page .pc-sub { font-size: 13px; color: var(--pc-muted); margin: 0; }
    .pc-page .pc-body { padding: 32px; }
    .pc-page .pc-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 18px 32px; border-top: 1px solid var(--pc-line); background: #fafafc; }

    .pc-page .pc-alert { margin-bottom: 24px; padding: 12px 16px; border-radius: 12px; background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.18); color: #b42318; font-size: 13px; line-height: 1.5; }

    .pc-page .pc-section { font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--pc-muted); padding-bottom: 8px; border-bottom: 1px solid var(--pc-line); margin: 0 0 18px; }
    .pc-page .pc-section:not(:first-child) { margin-top: 30px; }
    .pc-page .pc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px 24px; }
    .pc-page .pc-span-2 { grid-column: 1 / -1; }
    .pc-page .pc-label { display: block; font-size: 12px; font-weight: 600; color: #3a3a3c; margin-bottom: 7px; }
    .pc-page .pc-label small { font-weight: 500; color: var(--pc-muted); margin-left: 4px; }
    .pc-page .pc-req { color: #ff3b30; margin-left: 2px; }
    .pc-page .pc-hint { display: block; font-size: 11px; color: var(--pc-muted); margin-top: 6px; }

    .pc-page .pc-control {
        width: 100%; height: 40px; padding: 0 14px; border-radius: 10px; border: 1px solid var(--pc-line);
        background: rgba(120,120,128,.08); color: var(--pc-text); font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .pc-page .pc-control::placeholder { color: #8e8e93; }
    .pc-page .pc-control:focus { background: #fff; border-color: var(--pc-accent); box-shadow: 0 0 0 4px var(--pc-accent-soft); }
    .pc-page textarea.pc-control { height: auto; min-height: 110px; padding: 12px 14px; line-height: 1.5; resize: vertical; }

    .pc-page .pc-drop {
        display: flex; align-items: center; flex-wrap: wrap; gap: 12px; padding: 16px 18px; border-radius: 12px;
        border: 1.5px dashed rgba(78,139,188,.4); background: rgba(78,139,188,.05); transition: background .15s, border-color .15s;
    }
    .pc-page .pc-drop:hover { background: rgba(78,139,188,.09); border-color: var(--pc-accent); }
    .pc-page .pc-drop input[type=file] { font-size: 12px; color: var(--pc-muted); cursor: pointer; max-width: 100%; }
    .pc-page .pc-drop input[type=file]::file-selector-button {
        margin-right: 12px; padding: 7px 14px; border: 0; border-radius: 8px; background: #fff; color: #3a3a3c;
        font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,.1), 0 0 0 1px var(--pc-line);
    }
    .pc-page .pc-preview { display: none; margin-top: 12px; }
    .pc-page .pc-preview img { display: block; width: 100%; max-width: 280px; aspect-ratio: 4 / 3; object-fit: cover; border-radius: 14px; border: 1px solid var(--pc-line); box-shadow: 0 4px 16px rgba(0,0,0,.08); }

    .pc-page .pc-btn, .pc-page .pc-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 40px; padding: 0 22px; border-radius: 10px; border: 0;
        font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s;
    }
    .pc-page .pc-btn { background: var(--pc-accent); color: #fff; } .pc-page .pc-btn:hover { filter: brightness(1.06); }
    .pc-page .pc-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; } .pc-page .pc-btn-ghost:hover { background: rgba(120,120,128,.18); }

    @media (max-width: 800px) {
        .pc-page .pc-grid { grid-template-columns: 1fr; }
        .pc-page .pc-head, .pc-page .pc-body, .pc-page .pc-foot { padding-left: 20px; padding-right: 20px; }
    }
</style>

<div class="pc-page">

   

    <form action="{{ route('publikasi.store') }}" method="POST" enctype="multipart/form-data" class="pc-card">
        @csrf

        <div class="pc-head">
            <h3 class="pc-title">Form Permintaan Publikasi Website DJPK</h3>
            <p class="pc-sub">Lengkapi materi, foto, dan informasi 5W + 1H kegiatan Anda.</p>
        </div>

        <div class="pc-body">

            @if ($errors->any())
                <div class="pc-alert">@foreach ($errors->all() as $error)<div>• {{ $error }}</div>@endforeach</div>
            @endif

            <div class="pc-section">Informasi Kegiatan</div>
            <div class="pc-grid">
                <div class="pc-span-2">
                    <label class="pc-label">Nama Kegiatan<span class="pc-req">*</span></label>
                    <input type="text" name="nama_kegiatan" class="pc-control" value="{{ old('nama_kegiatan') }}" required>
                </div>
                <div class="pc-span-2">
                    <label class="pc-label">Deskripsi Singkat / Narasi Berita<span class="pc-req">*</span></label>
                    <textarea name="deskripsi" rows="4" class="pc-control" required>{{ old('deskripsi') }}</textarea>
                </div>
            </div>

            <div class="pc-section">Informasi 5W + 1H</div>
            <div class="pc-grid">
                <div>
                    <label class="pc-label">What<small>Apa yang terjadi?</small><span class="pc-req">*</span></label>
                    <input type="text" name="what" class="pc-control" value="{{ old('what') }}" required>
                </div>
                <div>
                    <label class="pc-label">Who<small>Siapa yang terlibat?</small><span class="pc-req">*</span></label>
                    <input type="text" name="who" class="pc-control" value="{{ old('who') }}" required>
                </div>
                <div>
                    <label class="pc-label">When<small>Tanggal kegiatan</small><span class="pc-req">*</span></label>
                    <input type="date" name="when_tanggal" class="pc-control" value="{{ old('when_tanggal') }}" required>
                </div>
                <div>
                    <label class="pc-label">Where<small>Lokasi kegiatan</small><span class="pc-req">*</span></label>
                    <input type="text" name="where_lokasi" class="pc-control" value="{{ old('where_lokasi') }}" maxlength="255" required>
                </div>
                <div>
                    <label class="pc-label">Why<small>Mengapa kegiatan ini penting?</small><span class="pc-req">*</span></label>
                    <input type="text" name="why" class="pc-control" value="{{ old('why') }}" required>
                </div>
                <div>
                    <label class="pc-label">How<small>Bagaimana jalannya acara?</small><span class="pc-req">*</span></label>
                    <input type="text" name="how" class="pc-control" value="{{ old('how') }}" required>
                </div>
            </div>

            <div class="pc-section">Lampiran Berkas & Foto</div>
            <div class="pc-grid">
                <div>
                    <label class="pc-label">Foto Kegiatan<span class="pc-req">*</span></label>
                    <div class="pc-drop">
                        <input type="file" name="foto_kegiatan" id="pcFoto" accept="image/jpeg,image/png" required>
                    </div>
                    <span class="pc-hint">JPG atau PNG, maksimal 2 MB.</span>
                    <div class="pc-preview" id="pcPreview"><img id="pcPreviewImg" src="" alt="Preview foto"></div>
                </div>
                <div>
                    <label class="pc-label">File Pendukung<small>Opsional</small></label>
                    <div class="pc-drop">
                        <input type="file" name="file_pendukung" accept=".pdf,.doc,.docx,.zip">
                    </div>
                    <span class="pc-hint">PDF, DOC, DOCX, atau ZIP, maksimal 5 MB.</span>
                </div>
            </div>
        </div>

        <div class="pc-foot">
            <a href="{{ route('publikasi.index') }}" class="pc-btn-ghost">Batal</a>
            <button type="submit" class="pc-btn">Kirim Permintaan Publikasi</button>
        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('pcFoto');
    const box = document.getElementById('pcPreview');
    const img = document.getElementById('pcPreviewImg');

    input.addEventListener('change', function () {
        const f = this.files[0];
        if (!f) { box.style.display = 'none'; img.src = ''; return; }
        const r = new FileReader();
        r.onload = function (e) { img.src = e.target.result; box.style.display = 'block'; };
        r.readAsDataURL(f);
    });
});
</script>

@endsection