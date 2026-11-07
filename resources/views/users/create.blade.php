@extends('layouts.app')

@section('title', 'Tambah Akun | HumasIn')
@section('page-title', 'Tambah Akun')
@section('page-description', 'Buat akun pengguna baru dan atur hak akses')

@section('content')

<style>
    .us-page {
        --us-accent: #4E8BBC;
        --us-accent-soft: rgba(78, 139, 188, .12);
        --us-text: #1d1d1f;
        --us-muted: #6e6e73;
        --us-line: rgba(0, 0, 0, .07);
        --us-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 960px; margin: 0 auto; color: var(--us-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .us-page *, .us-page *::before, .us-page *::after { box-sizing: border-box; }

    .us-page .us-back { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; font-size: 13px; font-weight: 500; color: #3b7aab; transition: opacity .15s; }
    .us-page .us-back:hover { opacity: .7; }
    .us-page .us-back svg { width: 16px; height: 16px; }

    .us-page .us-card { background: #fff; border: 1px solid var(--us-line); border-radius: 18px; box-shadow: var(--us-shadow); overflow: visible; }
    .us-page .us-card-head { padding: 22px 32px; border-bottom: 1px solid var(--us-line); border-radius: 18px 18px 0 0; }
    .us-page .us-card-title { font-size: 17px; font-weight: 700; letter-spacing: -.015em; margin: 0 0 3px; }
    .us-page .us-card-sub { font-size: 13px; color: var(--us-muted); margin: 0; }
    .us-page .us-card-body { padding: 32px; }
    .us-page .us-card-foot { display: flex; justify-content: flex-end; gap: 10px; padding: 18px 32px; border-top: 1px solid var(--us-line); background: #fafafc; border-radius: 0 0 18px 18px; }

    .us-page .us-alert {
        display: flex; gap: 10px; align-items: flex-start; margin-bottom: 24px; padding: 12px 16px; border-radius: 12px;
        background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.18); color: #b42318; font-size: 13px; line-height: 1.5;
    }
    .us-page .us-alert svg { flex-shrink: 0; margin-top: 2px; }

    .us-page .us-section-label { grid-column: 1 / -1; font-size: 11px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--us-muted); padding-bottom: 8px; border-bottom: 1px solid var(--us-line); }

    .us-page .us-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px 24px; }
    .us-page .us-span-2 { grid-column: 1 / -1; }
    .us-page .us-label { display: block; font-size: 12px; font-weight: 600; color: #3a3a3c; margin-bottom: 7px; }
    .us-page .us-req { color: #ff3b30; margin-left: 2px; }
    .us-page .us-hint { display: block; font-size: 11px; color: var(--us-muted); margin-top: 6px; }

    .us-page .us-control {
        width: 100%; height: 40px; padding: 0 14px; border-radius: 10px;
        border: 1px solid var(--us-line); background: rgba(120,120,128,.08); color: var(--us-text);
        font: inherit; font-size: 13px; outline: none;
        transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .us-page .us-control::placeholder { color: #8e8e93; }
    .us-page .us-control:focus { background: #fff; border-color: var(--us-accent); box-shadow: 0 0 0 4px var(--us-accent-soft); }
    .us-page select.us-control {
        appearance: none; cursor: pointer; padding-right: 36px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238e8e93' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 14px center;
    }

    /* Pencarian karyawan */
    .us-page .us-picker { position: relative; }
    .us-page .us-picker-icon { position: absolute; left: 12px; top: 12px; width: 16px; height: 16px; color: #8e8e93; pointer-events: none; }
    .us-page .us-picker .us-control { padding-left: 38px; }

    .us-page .us-results {
        display: none; position: absolute; left: 0; right: 0; top: calc(100% + 6px); z-index: 50;
        max-height: 280px; overflow-y: auto; padding: 6px;
        background: rgba(255,255,255,.98); border: 1px solid var(--us-line); border-radius: 14px;
        box-shadow: 0 12px 32px rgba(0,0,0,.14);
    }
    .us-page .us-result-item { display: flex; align-items: center; gap: 12px; padding: 9px 10px; border-radius: 10px; cursor: pointer; transition: background .12s; }
    .us-page .us-result-item:hover { background: var(--us-accent-soft); }
    .us-page .us-result-msg { padding: 12px 10px; font-size: 12px; color: var(--us-muted); }
    .us-page .us-result-msg.err { color: #c9342a; }

    .us-page .us-avatar { width: 34px; height: 34px; flex-shrink: 0; display: grid; place-items: center; border-radius: 50%; background: #C2E1FC; color: #2b5c8f; font-size: 12px; font-weight: 700; }
    .us-page .us-person-name { font-size: 13px; font-weight: 600; letter-spacing: -.005em; }
    .us-page .us-person-meta { font-size: 11px; color: var(--us-muted); margin-top: 1px; }

    .us-page .us-selected {
        display: none; align-items: center; gap: 12px; margin-top: 10px; padding: 12px 14px; border-radius: 12px;
        background: rgba(78,139,188,.07); border: 1px solid rgba(78,139,188,.28);
    }
    .us-page .us-selected-tag { font-size: 10px; font-weight: 600; letter-spacing: .05em; text-transform: uppercase; color: #3b7aab; margin-bottom: 2px; }
    .us-page .us-selected-body { flex: 1; min-width: 0; }
    .us-page .us-selected-clear {
        border: 0; background: rgba(120,120,128,.14); color: #3a3a3c; font: inherit; font-size: 12px; font-weight: 600;
        padding: 6px 12px; border-radius: 8px; cursor: pointer; transition: background .15s;
    }
    .us-page .us-selected-clear:hover { background: rgba(120,120,128,.24); }

    .us-page .us-btn, .us-page .us-btn-ghost {
        display: inline-flex; align-items: center; justify-content: center; height: 38px; padding: 0 20px;
        border-radius: 10px; border: 0; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s;
    }
    .us-page .us-btn { background: var(--us-accent); color: #fff; }
    .us-page .us-btn:hover { filter: brightness(1.06); }
    .us-page .us-btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
    .us-page .us-btn-ghost:hover { background: rgba(120,120,128,.18); }

    @media (max-width: 800px) {
        .us-page .us-form-grid { grid-template-columns: 1fr; }
        .us-page .us-card-head, .us-page .us-card-body, .us-page .us-card-foot { padding-left: 20px; padding-right: 20px; }
    }
</style>

<div class="us-page">

    <a href="{{ route('users.index') }}" class="us-back">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kembali ke daftar akun
    </a>

    <form id="formAkun" action="{{ route('users.store') }}" method="POST" class="us-card">
        @csrf

        <div class="us-card-head">
            <h3 class="us-card-title">Tambah Akun</h3>
            <p class="us-card-sub">Pilih karyawan, tentukan role, lalu buat kredensial login.</p>
        </div>

        <div class="us-card-body">

            @if ($errors->any())
                <div class="us-alert">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="us-form-grid">

                <div class="us-section-label">Karyawan & Hak Akses</div>

                {{-- KARYAWAN --}}
                <div class="us-span-2">
                    <label class="us-label" for="search_karyawan">Karyawan<span class="us-req">*</span></label>

                    <div class="us-picker">
                        <svg class="us-picker-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                        <input type="text" id="search_karyawan" class="us-control" placeholder="Ketik nama atau NIP…" autocomplete="off">
                        <div id="hasil_karyawan" class="us-results"></div>
                    </div>

                    <input type="hidden" name="karyawan_id" id="karyawan_id" value="{{ old('karyawan_id') }}">

                    <div id="karyawan_terpilih" class="us-selected"></div>
                </div>

                {{-- ROLE --}}
                <div class="us-span-2">
                    <label class="us-label" for="id_peran">Role<span class="us-req">*</span></label>
                    <select name="id_peran" id="id_peran" class="us-control" required>
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected(old('id_peran') == $role->id)>{{ $role->nama_peran }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="us-section-label" style="margin-top: 6px;">Kredensial Login</div>

                {{-- EMAIL --}}
                <div class="us-span-2">
                    <label class="us-label" for="email">Email Login<span class="us-req">*</span></label>
                    <input type="email" name="email" id="email" class="us-control" value="{{ old('email') }}" placeholder="nama@kemenkeu.go.id" required>
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="us-label" for="password">Kata Sandi Awal<span class="us-req">*</span></label>
                    <input type="password" name="password" id="password" class="us-control" minlength="8" required>
                    <small class="us-hint">Minimal 8 karakter.</small>
                </div>

                {{-- KONFIRMASI --}}
                <div>
                    <label class="us-label" for="password_confirmation">Konfirmasi Kata Sandi<span class="us-req">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="us-control" minlength="8" required>
                </div>

            </div>
        </div>

        <div class="us-card-foot">
            <a href="{{ route('users.index') }}" class="us-btn-ghost">Batal</a>
            <button type="submit" class="us-btn">Simpan Akun</button>
        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form             = document.getElementById('formAkun');
    const searchInput      = document.getElementById('search_karyawan');
    const hasil            = document.getElementById('hasil_karyawan');
    const karyawanId       = document.getElementById('karyawan_id');
    const karyawanTerpilih = document.getElementById('karyawan_terpilih');

    let timer = null;

    function esc(text) {
        const d = document.createElement('div');
        d.textContent = text ?? '';
        return d.innerHTML;
    }

    function inisial(nama) {
        return (nama || '').trim().split(/\s+/).filter(Boolean).slice(0, 2)
            .map(function (k) { return k.charAt(0).toUpperCase(); }).join('') || 'U';
    }

    function pesan(teks, error) {
        hasil.innerHTML = '<div class="us-result-msg' + (error ? ' err' : '') + '">' + teks + '</div>';
        hasil.style.display = 'block';
    }

    function resetPilihan() {
        karyawanId.value = '';
        karyawanTerpilih.style.display = 'none';
        karyawanTerpilih.innerHTML = '';
    }

    searchInput.addEventListener('input', function () {
        const keyword = this.value.trim();

        resetPilihan();

        if (keyword.length === 0) {
            hasil.innerHTML = '';
            hasil.style.display = 'none';
            return;
        }

        if (keyword.length < 2) {
            pesan('Ketik minimal 2 karakter.');
            return;
        }

        clearTimeout(timer);

        timer = setTimeout(function () {
            pesan('Mencari…');

            fetch(`{{ route('users.search.karyawan') }}?q=${encodeURIComponent(keyword)}`, {
                method: 'GET',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (response) {
                if (!response.ok) throw new Error('Gagal mengambil data karyawan.');
                return response.json();
            })
            .then(function (data) {
                hasil.innerHTML = '';

                if (data.length === 0) {
                    pesan('Karyawan tidak ditemukan.');
                    return;
                }

                data.forEach(function (pegawai) {
                    const item = document.createElement('div');
                    item.className = 'us-result-item';
                    item.innerHTML =
                        '<div class="us-avatar">' + esc(inisial(pegawai.nama)) + '</div>' +
                        '<div>' +
                            '<div class="us-person-name">' + esc(pegawai.nama) + '</div>' +
                            '<div class="us-person-meta">NIP: ' + esc(pegawai.nip) + ' · ' + esc(pegawai.unit) + '</div>' +
                        '</div>';

                    item.addEventListener('click', function () {
                        karyawanId.value  = pegawai.id;
                        searchInput.value = pegawai.nama;

                        karyawanTerpilih.innerHTML =
                            '<div class="us-avatar">' + esc(inisial(pegawai.nama)) + '</div>' +
                            '<div class="us-selected-body">' +
                                '<div class="us-selected-tag">Karyawan dipilih</div>' +
                                '<div class="us-person-name">' + esc(pegawai.nama) + '</div>' +
                                '<div class="us-person-meta">NIP: ' + esc(pegawai.nip) + ' · ' + esc(pegawai.unit) + '</div>' +
                            '</div>' +
                            '<button type="button" class="us-selected-clear" id="btnGanti">Ganti</button>';
                        karyawanTerpilih.style.display = 'flex';

                        document.getElementById('btnGanti').addEventListener('click', function () {
                            resetPilihan();
                            searchInput.value = '';
                            searchInput.focus();
                        });

                        hasil.style.display = 'none';
                    });

                    hasil.appendChild(item);
                });

                hasil.style.display = 'block';
            })
            .catch(function (error) {
                console.error(error);
                pesan('Terjadi kesalahan saat mencari karyawan.', true);
            });
        }, 300);
    });

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.us-picker')) hasil.style.display = 'none';
    });

    // Validasi: karyawan wajib dipilih
    form.addEventListener('submit', function (event) {
        if (!karyawanId.value) {
            event.preventDefault();
            alert('Silakan pilih karyawan terlebih dahulu.');
            searchInput.focus();
        }
    });
});
</script>

@endsection