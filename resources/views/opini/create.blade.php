@extends('layouts.app')

@section('title', 'Kirim Opini | HumasIn')
@section('page-title', 'Kirim Opini')
@section('page-description', 'Kirim naskah opini final untuk direview dan diterbitkan Humas DJPK')

@section('content')

<style>
    .oc-page {
        --oc-accent: #4E8BBC; --oc-accent-soft: rgba(78,139,188,.12);
        --oc-text: #1d1d1f; --oc-muted: #6e6e73; --oc-line: rgba(0,0,0,.07);
        --oc-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);
        max-width: 960px; margin: 0 auto; color: var(--oc-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .oc-page *, .oc-page *::before, .oc-page *::after { box-sizing: border-box; }

    .oc-page .oc-back { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #3b7aab; }
    .oc-page .oc-back:hover { opacity: .7; }
    .oc-page .oc-back svg { width: 16px; height: 16px; }

    .oc-page .oc-card { background: #fff; border: 1px solid var(--oc-line); border-radius: 18px; box-shadow: var(--oc-shadow); overflow: hidden; margin-bottom: 32px; }
    .oc-page .oc-head { padding: 22px 32px; border-bottom: 1px solid var(--oc-line); }
    .oc-page .oc-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 3px; }
    .oc-page .oc-sub { font-size: 13px; color: var(--oc-muted); margin: 0; }
    .oc-page .oc-body { padding: 32px; }
    .oc-page .oc-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 18px 32px; border-top: 1px solid var(--oc-line); background: #fafafc; }

    .oc-page .oc-alert { margin-bottom: 24px; padding: 12px 16px; border-radius: 12px; background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.18); color: #b42318; font-size: 13px; line-height: 1.5; }

    .oc-page .oc-section { font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--oc-muted); padding-bottom: 8px; border-bottom: 1px solid var(--oc-line); margin: 0 0 18px; }
    .oc-page .oc-section:not(:first-child) { margin-top: 30px; }
    .oc-page .oc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px 24px; }
    .oc-page .oc-span-2 { grid-column: 1 / -1; }
    .oc-page .oc-label { display: block; font-size: 12px; font-weight: 600; color: #3a3a3c; margin-bottom: 7px; }
    .oc-page .oc-label small { font-weight: 500; color: var(--oc-muted); margin-left: 4px; }
    .oc-page .oc-req { color: #ff3b30; margin-left: 2px; }
    .oc-page .oc-hint { display: block; font-size: 11px; color: var(--oc-muted); margin-top: 6px; }

    .oc-page .oc-control {
        width: 100%; height: 40px; padding: 0 14px; border-radius: 10px; border: 1px solid var(--oc-line);
        background: rgba(120,120,128,.08); color: var(--oc-text); font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .oc-page .oc-control::placeholder { color: #8e8e93; }
    .oc-page .oc-control:focus { background: #fff; border-color: var(--oc-accent); box-shadow: 0 0 0 4px var(--oc-accent-soft); }
    .oc-page textarea.oc-control { height: auto; min-height: 130px; padding: 12px 14px; line-height: 1.5; resize: vertical; }

    .oc-page .oc-drop {
        display: flex; align-items: center; flex-wrap: wrap; gap: 12px; padding: 16px 18px; border-radius: 12px;
        border: 1.5px dashed rgba(78,139,188,.4); background: rgba(78,139,188,.05); transition: background .15s, border-color .15s;
    }
    .oc-page .oc-drop:hover { background: rgba(78,139,188,.09); border-color: var(--oc-accent); }
    .oc-page .oc-drop input[type=file] { font-size: 12px; color: var(--oc-muted); cursor: pointer; max-width: 100%; }
    .oc-page .oc-drop input[type=file]::file-selector-button {
        margin-right: 12px; padding: 7px 14px; border: 0; border-radius: 8px; background: #fff; color: #3a3a3c;
        font: inherit; font-size: 12px; font-weight: 600; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,.1), 0 0 0 1px var(--oc-line);
    }

    .oc-page .oc-preview { display: none; align-items: center; gap: 12px; margin-top: 12px; padding: 10px; border-radius: 12px; background: #fafafc; border: 1px solid var(--oc-line); }
    .oc-page .oc-preview img { width: 64px; height: 64px; object-fit: cover; border-radius: 50%; border: 1px solid var(--oc-line); flex-shrink: 0; }
    .oc-page .oc-preview span { font-size: 12px; color: var(--oc-muted); line-height: 1.4; }

    .oc-page .oc-btn, .oc-page .oc-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 40px; padding: 0 22px; border-radius: 10px; border: 0;
        font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s;
    }
    .oc-page .oc-btn { background: var(--oc-accent); color: #fff; } .oc-page .oc-btn:hover { filter: brightness(1.06); }
    .oc-page .oc-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; } .oc-page .oc-btn-ghost:hover { background: rgba(120,120,128,.18); }

    @media (max-width: 800px) {
        .oc-page .oc-grid { grid-template-columns: 1fr; }
        .oc-page .oc-head, .oc-page .oc-body, .oc-page .oc-foot { padding-left: 20px; padding-right: 20px; }
    }
</style>

<div class="oc-page">

   

    <form action="{{ route('opini.store') }}" method="POST" enctype="multipart/form-data" class="oc-card">
        @csrf

        <div class="oc-head">
            <h3 class="oc-title">Form Pengiriman Materi Opini Eselon II</h3>
            <p class="oc-sub">Kirimkan naskah opini final Anda untuk direview dan diterbitkan oleh Humas DJPK.</p>
        </div>

        <div class="oc-body">

            @if ($errors->any())
                <div class="oc-alert">@foreach ($errors->all() as $error)<div>• {{ $error }}</div>@endforeach</div>
            @endif

            <div class="oc-section">Informasi Opini</div>
            <div class="oc-grid">
                <div class="oc-span-2">
                    <label class="oc-label">Judul Opini<span class="oc-req">*</span></label>
                    <input type="text" name="judul_opini" class="oc-control" value="{{ old('judul_opini') }}" maxlength="255" required>
                </div>
                <div class="oc-span-2">
                    <label class="oc-label">Ringkasan / Sinopsis Opini<span class="oc-req">*</span></label>
                    <textarea name="ringkasan" rows="5" class="oc-control" required>{{ old('ringkasan') }}</textarea>
                </div>
            </div>

            <div class="oc-section">Penulis</div>
            <div class="oc-grid">
                <div>
                    <label class="oc-label">Nama Penulis / Pejabat<span class="oc-req">*</span></label>
                    <input type="text" name="penulis" class="oc-control" value="{{ old('penulis') }}" maxlength="255" placeholder="Contoh: Dr. Nama Pejabat, M.Si" required>
                </div>
                <div>
                    <label class="oc-label">Foto Penulis<small>Opsional</small></label>
                    <div class="oc-drop">
                        <input type="file" name="foto_penulis" id="ocFoto" accept="image/jpeg,image/png">
                    </div>
                    <span class="oc-hint">JPG atau PNG, maksimal 2 MB.</span>
                    <div class="oc-preview" id="ocPreview">
                        <img id="ocPreviewImg" src="" alt="Preview foto penulis">
                        <span>Preview foto penulis</span>
                    </div>
                </div>
            </div>

            <div class="oc-section">Naskah</div>
            <div class="oc-grid">
                <div class="oc-span-2">
                    <label class="oc-label">File Naskah Final<span class="oc-req">*</span></label>
                    <div class="oc-drop">
                        <input type="file" name="file_naskah" accept=".pdf,.doc,.docx" required>
                    </div>
                    <span class="oc-hint">PDF, DOC, atau DOCX, maksimal 5 MB.</span>
                </div>
            </div>
        </div>

        <div class="oc-foot">
            <a href="{{ route('opini.index') }}" class="oc-btn-ghost">Batal</a>
            <button type="submit" class="oc-btn">Kirim Materi Opini</button>
        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('ocFoto');
    const box = document.getElementById('ocPreview');
    const img = document.getElementById('ocPreviewImg');

    input.addEventListener('change', function () {
        const f = this.files[0];
        if (!f) { box.style.display = 'none'; img.src = ''; return; }
        const r = new FileReader();
        r.onload = function (e) { img.src = e.target.result; box.style.display = 'flex'; };
        r.readAsDataURL(f);
    });
});
</script>

@endsection