<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HumasIn</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --accent: #4E8BBC;
            --accent-dark: #3b7aab;
            --accent-soft: rgba(78, 139, 188, .12);
            --text: #1d1d1f;
            --muted: #6e6e73;
            --line: rgba(0, 0, 0, .08);
            --shadow: 0 1px 2px rgba(0,0,0,.04), 0 12px 40px rgba(0,0,0,.07);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh; color: var(--text); background: #fff;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            -webkit-font-smoothing: antialiased; line-height: 1.5;
        }

        a { color: inherit; text-decoration: none; }
        a:focus-visible, button:focus-visible, input:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

        /* Layout split */
        .auth { display: grid; grid-template-columns: minmax(420px, 1.1fr) 1fr; min-height: 100vh; }

        /* Panel kiri */
        .auth-side {
            position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;
            padding: clamp(32px, 4vw, 72px); color: #fff;
            background: linear-gradient(145deg, #3b7aab 0%, #4E8BBC 55%, #6fa3cc 100%);
        }
        .auth-side::before, .auth-side::after { content: ""; position: absolute; border-radius: 50%; background: rgba(255,255,255,.08); pointer-events: none; }
        .auth-side::before { width: 520px; height: 520px; right: -180px; top: -160px; }
        .auth-side::after { width: 380px; height: 380px; left: -140px; bottom: -140px; }
        .auth-side > * { position: relative; z-index: 1; }

        .auth-brand { font-size: 28px; font-weight: 700; letter-spacing: -.02em; }
        .auth-brand span { color: #cfe3f3; }

        .auth-hero h2 { font-size: clamp(30px, 3vw, 48px); line-height: 1.15; font-weight: 700; letter-spacing: -.03em; margin-bottom: 16px; }
        .auth-hero p { max-width: 480px; font-size: 16px; line-height: 1.7; color: rgba(255,255,255,.86); }
        .auth-points { list-style: none; margin-top: 32px; display: flex; flex-direction: column; gap: 14px; }
        .auth-points li { display: flex; align-items: center; gap: 12px; font-size: 14px; font-weight: 500; }
        .auth-points .tick { width: 28px; height: 28px; flex-shrink: 0; display: grid; place-items: center; border-radius: 50%; background: rgba(255,255,255,.18); }
        .auth-points .tick svg { width: 14px; height: 14px; }
        .auth-foot { font-size: 12px; color: rgba(255,255,255,.7); }

        /* Panel kanan */
        .auth-main { display: flex; align-items: center; justify-content: center; padding: 32px 24px; background: #f5f7fa; }
        .auth-card { width: 100%; max-width: 440px; padding: 38px; background: #fff; border: 1px solid var(--line); border-radius: 22px; box-shadow: var(--shadow); }

        .card-brand { display: none; margin-bottom: 24px; text-align: center; font-size: 28px; font-weight: 700; letter-spacing: -.02em; }
        .card-brand span { color: var(--accent); }

        .auth-title { font-size: 22px; font-weight: 700; letter-spacing: -.02em; margin-bottom: 6px; }
        .auth-desc { font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 24px; }

        /* Alert */
        .alert { display: flex; gap: 10px; align-items: flex-start; margin-bottom: 18px; padding: 12px 14px; border-radius: 12px; font-size: 13px; line-height: 1.5; }
        .alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px; }
        .alert-error { background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.22); color: #c9342a; }
        .alert-success { background: rgba(52,199,89,.1); border: 1px solid rgba(52,199,89,.22); color: #1f7a38; }

        /* Form */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; margin-bottom: 7px; font-size: 12px; font-weight: 600; }
        .input-wrap { position: relative; }
        .form-input {
            width: 100%; height: 46px; padding: 0 14px; border-radius: 12px;
            border: 1px solid var(--line); background: rgba(120,120,128,.08); color: var(--text);
            font: inherit; font-size: 14px; outline: none; transition: background .15s, box-shadow .15s, border-color .15s;
        }
        .form-input::placeholder { color: #8e8e93; }
        .form-input:focus { background: #fff; border-color: var(--accent); box-shadow: 0 0 0 4px var(--accent-soft); }
        .form-input.is-invalid { border-color: rgba(255,59,48,.5); background: rgba(255,59,48,.04); }
        .form-input.is-invalid:focus { border-color: #c9342a; box-shadow: 0 0 0 4px rgba(255,59,48,.12); }
        .input-wrap .form-input { padding-right: 46px; }
        .toggle-pw { position: absolute; top: 50%; right: 8px; transform: translateY(-50%); width: 32px; height: 32px; display: grid; place-items: center; border: 0; border-radius: 8px; background: transparent; color: var(--muted); cursor: pointer; }
        .toggle-pw:hover { background: rgba(120,120,128,.12); color: var(--text); }
        .toggle-pw svg { width: 18px; height: 18px; }
        .toggle-pw [hidden] { display: none; }

        .btn { display: flex; align-items: center; justify-content: center; width: 100%; height: 46px; border: 0; border-radius: 12px; font: inherit; font-size: 14px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s; }
        .btn-primary { background: var(--accent); color: #fff; box-shadow: 0 4px 14px rgba(78,139,188,.35); }
        .btn-primary:hover { filter: brightness(1.06); }
        .btn-primary:disabled { opacity: .7; cursor: wait; }
        .btn-ghost { background: rgba(120,120,128,.1); color: #3a3a3c; }
        .btn-ghost:hover { background: rgba(120,120,128,.18); }

        .divider { display: flex; align-items: center; gap: 12px; margin: 22px 0; font-size: 12px; color: #8e8e93; }
        .divider::before, .divider::after { content: ""; flex: 1; height: 1px; background: var(--line); }

        .card-foot { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: 24px; font-size: 12px; color: #8e8e93; }
        .card-foot a { color: var(--accent-dark); font-weight: 600; }
        .card-foot a:hover { text-decoration: underline; }

        @media (max-width: 900px) {
            .auth { grid-template-columns: 1fr; }
            .auth-side { display: none; }
            .card-brand { display: block; }
            .auth-main { padding: 24px 16px; }
        }
        @media (max-width: 480px) {
            .auth-card { padding: 28px 22px; }
        }
        @media (prefers-reduced-motion: reduce) {
            .form-input, .btn { transition: none; }
        }
    </style>
</head>

<body>

<div class="auth">

    {{-- PANEL KIRI (desktop) --}}
    <aside class="auth-side">
        <div class="auth-brand">Humas<span>In</span></div>

        <div class="auth-hero">
            <h2>Kelola aktivitas Humas lebih terintegrasi</h2>
            <p>Sistem informasi untuk mendukung kegiatan, dokumentasi, peminjaman aset dan ruangan, serta layanan Humas di lingkungan DJPK.</p>

            <ul class="auth-points">
                <li>
                    <span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"/></svg></span>
                    Ajukan dan pantau dukungan kegiatan
                </li>
                <li>
                    <span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"/></svg></span>
                    Pinjam aset dan ruangan Humas
                </li>
                <li>
                    <span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5 9-10"/></svg></span>
                    Akses dokumentasi dan template desain
                </li>
            </ul>
        </div>

        <div class="auth-foot">Humas DJPK</div>
    </aside>


    {{-- PANEL KANAN: FORM --}}
    <main class="auth-main">
        <div class="auth-card">

            <div class="card-brand">Humas<span>In</span></div>

            <h1 class="auth-title">Masuk ke HumasIn</h1>
            <p class="auth-desc">Masukkan email, NIP, dan password untuk mengakses sistem.</p>

            {{-- NOTIFIKASI --}}
            @if (session('success') || session('status'))
                <div class="alert alert-success" role="status">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/></svg>
                    <span>{{ session('success') ?? session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- FORM --}}
            <form id="loginForm" action="{{ route('login.process') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email"
                           class="form-input @error('email') is-invalid @enderror"
                           placeholder="Masukkan email" value="{{ old('email') }}"
                           autocomplete="username" autofocus required>
                </div>

                <div class="form-group">
                    <label for="nip" class="form-label">NIP</label>
                    <input type="text" id="nip" name="nip"
                           class="form-input @error('nip') is-invalid @enderror"
                           placeholder="Masukkan NIP" value="{{ old('nip') }}"
                           inputmode="numeric" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrap">
                        <input type="password" id="password" name="password"
                               class="form-input @error('password') is-invalid @enderror"
                               placeholder="Masukkan password" autocomplete="current-password" required>
                        <button type="button" class="toggle-pw" id="togglePassword" aria-label="Tampilkan password">
                            <span class="eye-on">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                            </span>
                            <span class="eye-off" hidden>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3l18 18"/><path d="M10.6 6.1A10 10 0 0 1 12 6c6.5 0 10 6 10 6a17 17 0 0 1-3.2 3.9M6.6 6.6A17 17 0 0 0 2 12s3.5 7 10 7a9.7 9.7 0 0 0 4.4-1"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                            </span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="btnMasuk">Masuk</button>
            </form>

            <div class="divider">Karyawan baru?</div>

            <a href="{{ route('activation.show') }}" class="btn btn-ghost">Aktivasi Akun Karyawan</a>

            <div class="card-foot">
                <a href="{{ url('/') }}">&larr; Kembali ke beranda</a>
                <span>Humas DJPK</span>
            </div>

        </div>
    </main>

</div>

<script>
(function () {
    const pw = document.getElementById('password');
    const toggle = document.getElementById('togglePassword');
    const form = document.getElementById('loginForm');
    const btn = document.getElementById('btnMasuk');

    // Tampilkan / sembunyikan password
    toggle.addEventListener('click', function () {
        const tampil = pw.type === 'password';
        pw.type = tampil ? 'text' : 'password';
        toggle.setAttribute('aria-label', tampil ? 'Sembunyikan password' : 'Tampilkan password');
        toggle.querySelector('.eye-on').hidden = tampil;
        toggle.querySelector('.eye-off').hidden = !tampil;
    });

    // Cegah klik ganda saat proses login
    form.addEventListener('submit', function () {
        btn.disabled = true;
        btn.textContent = 'Memproses...';
    });

    // Pulihkan tombol jika halaman dimuat ulang dari cache (tombol Back)
    window.addEventListener('pageshow', function () {
        btn.disabled = false;
        btn.textContent = 'Masuk';
    });
})();
</script>

</body>

</html>