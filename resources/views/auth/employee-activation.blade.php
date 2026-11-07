<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun | HumasIn</title>

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

        .auth-steps { list-style: none; margin-top: 32px; display: flex; flex-direction: column; gap: 16px; counter-reset: step; }
        .auth-steps li { display: flex; align-items: center; gap: 14px; font-size: 14px; font-weight: 500; counter-increment: step; }
        .auth-steps li::before {
            content: counter(step); width: 30px; height: 30px; flex-shrink: 0; display: grid; place-items: center;
            border-radius: 50%; background: rgba(255,255,255,.18); font-size: 13px; font-weight: 700;
        }
        .auth-foot { font-size: 12px; color: rgba(255,255,255,.7); }

        /* Panel kanan */
        .auth-main { display: flex; align-items: center; justify-content: center; padding: 32px 24px; background: #f5f7fa; }
        .auth-card { width: 100%; max-width: 440px; padding: 38px; background: #fff; border: 1px solid var(--line); border-radius: 22px; box-shadow: var(--shadow); }

        .card-brand { display: none; margin-bottom: 24px; text-align: center; font-size: 28px; font-weight: 700; letter-spacing: -.02em; }
        .card-brand span { color: var(--accent); }

        .card-icon { width: 44px; height: 44px; display: grid; place-items: center; margin-bottom: 16px; border-radius: 12px; background: var(--accent-soft); color: var(--accent-dark); }
        .card-icon svg { width: 22px; height: 22px; }

        .auth-title { font-size: 22px; font-weight: 700; letter-spacing: -.02em; margin-bottom: 6px; }
        .auth-desc { font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 24px; }

        /* Alert */
        .alert { display: flex; gap: 10px; align-items: flex-start; margin-bottom: 18px; padding: 12px 14px; border-radius: 12px; font-size: 13px; line-height: 1.5; }
        .alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px; }
        .alert-error { background: rgba(255,59,48,.08); border: 1px solid rgba(255,59,48,.22); color: #c9342a; }

        /* Form */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; margin-bottom: 7px; font-size: 12px; font-weight: 600; }
        .form-input {
            width: 100%; height: 46px; padding: 0 14px; border-radius: 12px;
            border: 1px solid var(--line); background: rgba(120,120,128,.08); color: var(--text);
            font: inherit; font-size: 14px; outline: none; transition: background .15s, box-shadow .15s, border-color .15s;
        }
        .form-input::placeholder { color: #8e8e93; }
        .form-input:focus { background: #fff; border-color: var(--accent); box-shadow: 0 0 0 4px var(--accent-soft); }
        .form-input.is-invalid { border-color: rgba(255,59,48,.5); background: rgba(255,59,48,.04); }
        .form-input.is-invalid:focus { border-color: #c9342a; box-shadow: 0 0 0 4px rgba(255,59,48,.12); }

        .btn { display: flex; align-items: center; justify-content: center; width: 100%; height: 46px; border: 0; border-radius: 12px; font: inherit; font-size: 14px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s; }
        .btn-primary { background: var(--accent); color: #fff; box-shadow: 0 4px 14px rgba(78,139,188,.35); margin-top: 6px; }
        .btn-primary:hover { filter: brightness(1.06); }
        .btn-primary:disabled { opacity: .7; cursor: wait; }

        .card-foot { margin-top: 24px; text-align: center; font-size: 13px; }
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
            <h2>Aktivasi akun karyawan</h2>
            <p>Akun dibuat dari data yang sudah didaftarkan oleh pengelola sistem. Pastikan NIP dan email yang Anda masukkan sama dengan data tersebut.</p>

            <ol class="auth-steps">
                <li>Masukkan NIP dan email terdaftar</li>
                <li>Sistem memverifikasi data Anda</li>
                <li>Lengkapi akun lalu masuk ke sistem</li>
            </ol>
        </div>

        <div class="auth-foot">Humas DJPK</div>
    </aside>


    {{-- PANEL KANAN: FORM --}}
    <main class="auth-main">
        <div class="auth-card">

            <div class="card-brand">Humas<span>In</span></div>

            <div class="card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M22 11h-6"/></svg>
            </div>

            <h1 class="auth-title">Aktivasi Akun Karyawan</h1>
            <p class="auth-desc">Masukkan NIP dan email yang telah didaftarkan oleh pengelola sistem untuk membuat akun Anda.</p>

            @if ($errors->any())
                <div class="alert alert-error" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form id="activationForm" method="POST" action="{{ route('activation.verify') }}">
                @csrf

                <div class="form-group">
                    <label for="nip" class="form-label">NIP</label>
                    <input type="text" id="nip" name="nip"
                           class="form-input @error('nip') is-invalid @enderror"
                           value="{{ old('nip') }}" placeholder="Masukkan NIP"
                           inputmode="numeric" autocomplete="off" autofocus required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email"
                           class="form-input @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="Masukkan email"
                           autocomplete="email" required>
                </div>

                <button type="submit" class="btn btn-primary" id="btnLanjut">Lanjutkan</button>
            </form>

            <div class="card-foot">
                <a href="{{ route('login') }}">&larr; Kembali ke Login</a>
            </div>

        </div>
    </main>

</div>

<script>
(function () {
    const form = document.getElementById('activationForm');
    const btn = document.getElementById('btnLanjut');

    // Cegah klik ganda saat verifikasi
    form.addEventListener('submit', function () {
        btn.disabled = true;
        btn.textContent = 'Memverifikasi...';
    });

    // Pulihkan tombol jika halaman dimuat ulang dari cache (tombol Back)
    window.addEventListener('pageshow', function () {
        btn.disabled = false;
        btn.textContent = 'Lanjutkan';
    });
})();
</script>

</body>

</html>