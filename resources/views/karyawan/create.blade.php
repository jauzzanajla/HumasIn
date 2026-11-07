@extends('layouts.app')

@section('title', 'Tambah Karyawan | HumasIn')
@section('page-title', 'Tambah Karyawan')
@section('page-description', 'Menambahkan data karyawan baru')

@section('content')

<style>
    .kw-page {
        --kw-accent: #4E8BBC;
        --kw-accent-soft: rgba(78, 139, 188, .12);
        --kw-text: #1d1d1f;
        --kw-muted: #6e6e73;
        --kw-line: rgba(0, 0, 0, .07);
        --kw-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 960px; margin: 0 auto; color: var(--kw-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .kw-page *, .kw-page *::before, .kw-page *::after { box-sizing: border-box; }

    .kw-page .kw-back { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #3b7aab; transition: opacity .15s; }
    .kw-page .kw-back:hover { opacity: .7; }
    .kw-page .kw-back svg { width: 16px; height: 16px; }

    .kw-page .kw-card { background: #fff; border: 1px solid var(--kw-line); border-radius: 18px; box-shadow: var(--kw-shadow); overflow: hidden; }
    .kw-page .kw-card-head { padding: 22px 32px; border-bottom: 1px solid var(--kw-line); }
    .kw-page .kw-card-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 3px; }
    .kw-page .kw-card-sub { font-size: 13px; color: var(--kw-muted); margin: 0; }
    .kw-page .kw-card-body { padding: 32px; }
    .kw-page .kw-card-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 18px 32px; border-top: 1px solid var(--kw-line); background: #fafafc; }

    .kw-page .kw-alert {
        display: flex; gap: 10px; align-items: flex-start; margin-bottom: 24px; padding: 12px 16px; border-radius: 12px;
        background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.18); color: #b42318; font-size: 13px; line-height: 1.5;
    }
    .kw-page .kw-alert svg { flex-shrink: 0; margin-top: 2px; }
    .kw-page .kw-alert strong { display: block; margin-bottom: 2px; }

    .kw-page .kw-section-label { grid-column: 1 / -1; font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--kw-muted); padding-bottom: 8px; border-bottom: 1px solid var(--kw-line); }

    .kw-page .kw-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px 24px; }
    .kw-page .kw-span-2 { grid-column: 1 / -1; }
    .kw-page .kw-label { display: block; font-size: 12px; font-weight: 600; color: #3a3a3c; margin-bottom: 7px; }
    .kw-page .kw-req { color: #ff3b30; margin-left: 2px; }
    .kw-page .kw-hint { display: block; font-size: 11px; color: var(--kw-muted); margin-top: 6px; }
    .kw-page .kw-error { font-size: 11px; color: #c9342a; margin-top: 6px; }

    .kw-page .kw-control {
        width: 100%; height: 40px; padding: 0 14px; border-radius: 10px;
        border: 1px solid var(--kw-line); background: rgba(120,120,128,.08); color: var(--kw-text);
        font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .kw-page .kw-control::placeholder { color: #8e8e93; }
    .kw-page .kw-control:focus { background: #fff; border-color: var(--kw-accent); box-shadow: 0 0 0 4px var(--kw-accent-soft); }
    .kw-page .kw-control:disabled { opacity: .55; cursor: not-allowed; }
    .kw-page select.kw-control {
        appearance: none; cursor: pointer; padding-right: 36px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 14px center;
    }

    .kw-page .kw-btn, .kw-page .kw-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 20px;
        border-radius: 10px; border: 0; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s;
    }
    .kw-page .kw-btn { background: var(--kw-accent); color: #fff; }
    .kw-page .kw-btn:hover { filter: brightness(1.06); }
    .kw-page .kw-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .kw-page .kw-btn-ghost:hover { background: rgba(120,120,128,.18); }

    @media (max-width: 800px) {
        .kw-page .kw-form-grid { grid-template-columns: 1fr; }
        .kw-page .kw-card-head, .kw-page .kw-card-body, .kw-page .kw-card-foot { padding-left: 20px; padding-right: 20px; }
    }
</style>

<div class="kw-page">

    <a href="{{ route('karyawan.index') }}" class="kw-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Karyawan
    </a>

    <form action="{{ route('karyawan.store') }}" method="POST" class="kw-card">
        @csrf

        <div class="kw-card-head">
            <h3 class="kw-card-title">Informasi Karyawan</h3>
            <p class="kw-card-sub">Lengkapi informasi karyawan berikut.</p>
        </div>

        <div class="kw-card-body">

            @if ($errors->any())
                <div class="kw-alert">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <div>
                        <strong>Periksa kembali inputan Anda:</strong>
                        @foreach ($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="kw-form-grid">

                <div class="kw-section-label">Penempatan</div>

                {{-- UNIT --}}
                <div>
                    <label class="kw-label">Unit / Eselon II<span class="kw-req">*</span></label>
                    <select name="unit_id" id="unit_id" class="kw-control" required>
                        <option value="">Pilih Unit</option>
                        @foreach ($unit as $item)
                            <option value="{{ $item->id }}" {{ old('unit_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_unit }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_id') <div class="kw-error">{{ $message }}</div> @enderror
                </div>

                {{-- SUBUNIT --}}
                <div>
                    <label class="kw-label">Bagian / Subunit Organisasi</label>
                    <select name="subunit_organisasi_id" id="subunit_organisasi_id" class="kw-control">
                        <option value="">Pilih unit terlebih dahulu</option>
                        @foreach ($subunitOrganisasi as $subunit)
                            <option
                                value="{{ $subunit->id }}"
                                data-unit="{{ $subunit->unit_id }}"
                                data-jenis="{{ $subunit->jenis }}"
                                {{ old('subunit_organisasi_id') == $subunit->id ? 'selected' : '' }}>
                                {{ $subunit->nama_subunit }}
                            </option>
                        @endforeach
                    </select>
                    @error('subunit_organisasi_id') <div class="kw-error">{{ $message }}</div> @enderror
                    <small id="subunit-help" class="kw-hint">Pilih bagian atau subunit sesuai unit organisasi.</small>
                </div>

                <div class="kw-section-label" style="margin-top: 6px;">Data Pribadi</div>

                {{-- NIP --}}
                <div>
                    <label class="kw-label">NIP<span class="kw-req">*</span></label>
                    <input type="text" name="nip" class="kw-control" value="{{ old('nip') }}" placeholder="Masukkan NIP" required>
                    @error('nip') <div class="kw-error">{{ $message }}</div> @enderror
                </div>

                {{-- NAMA --}}
                <div>
                    <label class="kw-label">Nama Lengkap<span class="kw-req">*</span></label>
                    <input type="text" name="nama" class="kw-control" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                    @error('nama') <div class="kw-error">{{ $message }}</div> @enderror
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="kw-label">Email<span class="kw-req">*</span></label>
                    <input type="email" name="email" class="kw-control" value="{{ old('email') }}" placeholder="nama@kemenkeu.go.id" required>
                    @error('email') <div class="kw-error">{{ $message }}</div> @enderror
                </div>

                {{-- JABATAN --}}
                <div>
                    <label class="kw-label">Jabatan</label>
                    <input type="text" name="jabatan" class="kw-control" value="{{ old('jabatan') }}" placeholder="Masukkan jabatan secara manual">
                    @error('jabatan') <div class="kw-error">{{ $message }}</div> @enderror
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="kw-label">Status<span class="kw-req">*</span></label>
                    <select name="aktif" class="kw-control" required>
                        <option value="1" {{ old('aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('aktif') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('aktif') <div class="kw-error">{{ $message }}</div> @enderror
                </div>

            </div>
        </div>

        <div class="kw-card-foot">
            <a href="{{ route('karyawan.index') }}" class="kw-btn-ghost">Batal</a>
            <button type="submit" class="kw-btn">Simpan Karyawan</button>
        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const unitSelect = document.getElementById('unit_id');
    const subunitSelect = document.getElementById('subunit_organisasi_id');
    const help = document.getElementById('subunit-help');

    const options = Array.from(subunitSelect.querySelectorAll('option[data-unit]'))
        .map(option => ({
            value: option.value,
            unit: option.dataset.unit,
            jenis: option.dataset.jenis,
            text: option.textContent.trim()
        }));

    const previousValue = @json(old('subunit_organisasi_id', ''));

    function updateSubunits(selectedValue = '') {
        const unitId = unitSelect.value;
        const selectedUnit = unitSelect.options[unitSelect.selectedIndex];
        const unitName = selectedUnit ? selectedUnit.text.trim().toLowerCase() : '';

        subunitSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';

        if (!unitId) {
            placeholder.textContent = 'Pilih unit terlebih dahulu';
            subunitSelect.appendChild(placeholder);
            subunitSelect.disabled = true;
            help.textContent = 'Pilih unit organisasi terlebih dahulu.';
            return;
        }

        subunitSelect.disabled = false;

        const isSecretariat = unitName.includes('sekretariat');
        placeholder.textContent = isSecretariat ? 'Pilih Bagian' : 'Pilih Subunit Organisasi';
        subunitSelect.appendChild(placeholder);

        const filtered = options.filter(option => option.unit === unitId);

        filtered.forEach(item => {
            const option = document.createElement('option');
            option.value = item.value;
            option.textContent = item.text;
            option.dataset.jenis = item.jenis;

            if (String(item.value) === String(selectedValue)) {
                option.selected = true;
            }

            subunitSelect.appendChild(option);
        });

        help.textContent = filtered.length
            ? (isSecretariat
                ? 'Pilih bagian di bawah Sekretariat Direktorat Jenderal.'
                : 'Pilih subunit atau kelompok jabatan fungsional.')
            : 'Belum ada data subunit untuk unit ini.';
    }

    unitSelect.addEventListener('change', function () {
        updateSubunits('');
    });

    updateSubunits(previousValue);
});
</script>

@endsection