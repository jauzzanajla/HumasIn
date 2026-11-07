@php
    // Dikirim dari route / controller. Default kosong supaya halaman tidak error.
    $eventTerdekat = collect($eventTerdekat ?? [])->take(5);

    // OPSIONAL: isi untuk menampilkan section Pengumuman (kosongkan = disembunyikan).
    // Contoh: [['judul' => '...', 'isi' => '...', 'tanggal' => '12 Nov 2026']]
    $pengumuman = $pengumuman ?? [];

    // OPSIONAL: isi kontak Humas (yang kosong tidak ditampilkan; semua kosong = section disembunyikan).
    $kontak = array_filter([
        'Email'       => '',
        'Telepon'     => '',
        'Lokasi'      => '',
        'Jam layanan' => '',
    ]);
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HumasIn - Humas DJPK</title>
    <meta name="description" content="HumasIn, sistem informasi Humas DJPK untuk pengelolaan kegiatan, dokumentasi, peminjaman aset dan ruangan, serta layanan Humas.">

    <style>
        :root {
            --accent: #4E8BBC;
            --accent-dark: #3b7aab;
            --accent-soft: rgba(78, 139, 188, .12);
            --text: #1d1d1f;
            --muted: #6e6e73;
            --line: rgba(0, 0, 0, .08);
            --shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.05);
        }

        * { box-sizing: border-box; }

        html { scroll-behavior: smooth; scroll-padding-top: 88px; }

        body {
            margin: 0; color: var(--text); background: #fff;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", Arial, sans-serif;
            -webkit-font-smoothing: antialiased; line-height: 1.5;
        }

        a { text-decoration: none; color: inherit; }
        a:focus-visible, button:focus-visible, summary:focus-visible { outline: 2px solid var(--accent); outline-offset: 3px; border-radius: 8px; }

        .container { width: 100%; max-width: 1600px; margin: 0 auto; padding: 0 clamp(24px, 5vw, 96px); }

        /* Tombol */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 44px; padding: 0 22px; border-radius: 12px;
            font-size: 14px; font-weight: 600; transition: filter .15s, background .15s, transform .15s; white-space: nowrap;
        }
        .btn-primary { background: var(--accent); color: #fff; box-shadow: 0 4px 14px rgba(78,139,188,.35); }
        .btn-primary:hover { filter: brightness(1.06); }
        .btn-ghost { background: #fff; color: #3a3a3c; border: 1px solid var(--line); }
        .btn-ghost:hover { background: #f5f5f7; }

        /* Navbar */
        .navbar {
            position: sticky; top: 0; z-index: 50; height: 72px;
            background: rgba(255,255,255,.85); -webkit-backdrop-filter: blur(14px); backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--line);
        }
        .navbar .container { height: 100%; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { font-size: 24px; font-weight: 700; letter-spacing: -.02em; }
        .brand span { color: var(--accent); }
        .nav-links { display: flex; align-items: center; gap: 4px; }
        .nav-links a { padding: 8px 14px; border-radius: 10px; font-size: 14px; font-weight: 500; color: var(--muted); transition: background .15s, color .15s; }
        .nav-links a:hover { background: rgba(120,120,128,.1); color: var(--text); }
        .nav-right { display: flex; align-items: center; gap: 12px; }
        .nav-right .btn { height: 40px; padding: 0 20px; }

        /* Hero */
        .hero {
            display: flex; align-items: center; min-height: calc(100vh - 72px); padding: 64px 0;
            background:
                radial-gradient(900px 480px at 88% 12%, rgba(78,139,188,.16), transparent 62%),
                radial-gradient(700px 400px at 0% 100%, rgba(78,139,188,.08), transparent 60%),
                linear-gradient(180deg, #f4f8fb 0%, #ffffff 100%);
        }
        .hero-grid { display: grid; grid-template-columns: 1.1fr .9fr; gap: clamp(40px, 5vw, 110px); align-items: center; }
        .hero-label {
            display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; padding: 6px 14px; border-radius: 999px;
            background: var(--accent-soft); color: var(--accent-dark); font-size: 12px; font-weight: 700; letter-spacing: .06em;
        }
        .hero h1 { margin: 0 0 22px; font-size: clamp(40px, 3.8vw, 68px); line-height: 1.1; font-weight: 700; letter-spacing: -.03em; }
        .hero h1 span { color: var(--accent); }
        .hero-desc { max-width: 680px; margin: 0 0 36px; color: var(--muted); font-size: clamp(17px, 1.25vw, 20px); line-height: 1.7; }
        .hero-buttons { display: flex; flex-wrap: wrap; gap: 12px; }

        /* Kartu agenda terdekat */
        .agenda-card { width: 100%; max-width: 600px; justify-self: end; background: #fff; border: 1px solid var(--line); border-radius: 24px; box-shadow: var(--shadow); padding: 28px; }
        .agenda-head { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
        .agenda-icon { width: 40px; height: 40px; flex-shrink: 0; display: grid; place-items: center; border-radius: 12px; background: var(--accent-soft); color: var(--accent-dark); }
        .agenda-icon svg { width: 20px; height: 20px; }
        .agenda-head h2 { margin: 0; font-size: 16px; font-weight: 700; letter-spacing: -.01em; }
        .agenda-head p { margin: 2px 0 0; font-size: 12px; color: var(--muted); }
        .agenda-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 10px; }
        .agenda-item { display: flex; align-items: center; gap: 14px; padding: 12px; border: 1px solid var(--line); border-radius: 14px; transition: border-color .15s, background .15s; }
        .agenda-item:hover { border-color: var(--accent); background: rgba(78,139,188,.04); }
        .agenda-date { width: 52px; flex-shrink: 0; padding: 7px 0; text-align: center; border-radius: 12px; background: var(--accent-soft); color: var(--accent-dark); }
        .agenda-date .d { display: block; font-size: 20px; font-weight: 700; line-height: 1.1; }
        .agenda-date .m { display: block; margin-top: 2px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
        .agenda-info { flex: 1; min-width: 0; }
        .agenda-name { font-size: 14px; font-weight: 600; letter-spacing: -.005em; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .agenda-meta { margin-top: 3px; font-size: 12px; color: var(--muted); }
        .agenda-badge { flex-shrink: 0; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; background: rgba(52,199,89,.14); color: #1f7a38; }
        .agenda-badge.soft { background: rgba(255,149,0,.14); color: #b26a00; }
        .agenda-empty { padding: 28px 12px; text-align: center; font-size: 13px; color: var(--muted); border: 1px dashed var(--line); border-radius: 14px; }
        .agenda-link { display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--line); font-size: 13px; font-weight: 600; color: var(--accent-dark); }
        .agenda-link:hover { text-decoration: underline; }

        /* Section umum */
        .section { padding: clamp(64px, 7vw, 120px) 0; }
        .section-alt { background: #f9fafb; }
        .section-title { text-align: center; max-width: 720px; margin: 0 auto clamp(36px, 4vw, 56px); }
        .section-title h2 { margin: 0 0 10px; font-size: clamp(28px, 2.5vw, 42px); font-weight: 700; letter-spacing: -.02em; }
        .section-title p { margin: 0; color: var(--muted); font-size: clamp(15px, 1.1vw, 17px); }

        /* Pengumuman */
        .notice-list { max-width: 960px; margin: 0 auto; display: flex; flex-direction: column; gap: 12px; }
        .notice { padding: 18px 20px; background: #fff; border: 1px solid var(--line); border-left: 4px solid var(--accent); border-radius: 14px; box-shadow: var(--shadow); }
        .notice h3 { margin: 0 0 4px; font-size: 15px; font-weight: 700; }
        .notice p { margin: 0; font-size: 14px; color: var(--muted); }
        .notice time { display: block; margin-top: 8px; font-size: 12px; color: #8e8e93; }

        /* Layanan */
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; }
        .feature-card { padding: 24px; background: #fff; border: 1px solid var(--line); border-radius: 20px; box-shadow: var(--shadow); transition: transform .2s, border-color .2s, box-shadow .2s; }
        .feature-card:hover { transform: translateY(-3px); border-color: var(--accent); box-shadow: 0 2px 4px rgba(0,0,0,.04), 0 16px 36px rgba(78,139,188,.16); }
        .feature-icon { width: 44px; height: 44px; display: grid; place-items: center; margin-bottom: 16px; border-radius: 12px; background: var(--accent-soft); color: var(--accent-dark); }
        .feature-icon svg { width: 22px; height: 22px; }
        .feature-card h3 { margin: 0 0 8px; font-size: 16px; font-weight: 700; letter-spacing: -.01em; }
        .feature-card p { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.6; }

        /* Alur */
        .steps { list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; counter-reset: step; }
        .step { position: relative; padding: 24px; background: #fff; border: 1px solid var(--line); border-radius: 20px; box-shadow: var(--shadow); }
        .step-no { width: 36px; height: 36px; display: grid; place-items: center; margin-bottom: 14px; border-radius: 50%; background: var(--accent); color: #fff; font-size: 14px; font-weight: 700; }
        .step h3 { margin: 0 0 6px; font-size: 16px; font-weight: 700; }
        .step p { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.6; }
        .steps-note { margin: 22px 0 0; text-align: center; font-size: 13px; color: var(--muted); }

        /* FAQ */
        .faq { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 12px; }
        .faq details { background: #fff; border: 1px solid var(--line); border-radius: 14px; box-shadow: var(--shadow); }
        .faq summary { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 20px; font-size: 14px; font-weight: 600; cursor: pointer; list-style: none; }
        .faq summary::-webkit-details-marker { display: none; }
        .faq summary::after { content: "+"; flex-shrink: 0; width: 24px; height: 24px; display: grid; place-items: center; border-radius: 50%; background: var(--accent-soft); color: var(--accent-dark); font-size: 16px; font-weight: 700; line-height: 1; }
        .faq details[open] summary::after { content: "\2212"; }
        .faq details p { margin: 0; padding: 0 20px 18px; font-size: 14px; color: var(--muted); line-height: 1.7; }

        /* Kontak */
        .contact-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; max-width: 1200px; margin: 0 auto; }
        .contact-card { padding: 20px; background: #fff; border: 1px solid var(--line); border-radius: 16px; box-shadow: var(--shadow); }
        .contact-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); margin-bottom: 6px; }
        .contact-value { font-size: 14px; font-weight: 600; word-break: break-word; }

        /* Footer */
        footer { padding: 32px 0; border-top: 1px solid var(--line); color: #8e8e93; font-size: 13px; }
        .footer-inner { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; }
        .footer-links { display: flex; gap: 18px; }
        .footer-links a:hover { color: var(--text); }

        /* Desktop lebar */
        @media (min-width: 1100px) {
            .faq { display: grid; grid-template-columns: 1fr 1fr; align-items: start; }
        }
        @media (min-width: 1280px) {
            .feature-grid { grid-template-columns: repeat(5, 1fr); }
        }
        @media (min-width: 1440px) {
            .btn { height: 48px; padding: 0 26px; font-size: 15px; }
            .nav-right .btn { height: 42px; }
            .agenda-name { font-size: 15px; }
            .agenda-item { padding: 14px; }
            .feature-card, .step { padding: 28px; }
            .feature-card p, .step p { font-size: 15px; }
        }

        /* Responsive */
        @media (max-width: 960px) {
            .container { padding: 0 24px; }
            .hero { min-height: auto; padding: 48px 0 56px; }
            .agenda-card { max-width: none; justify-self: stretch; }
            .hero-grid { grid-template-columns: 1fr; gap: 40px; }
            .hero h1 { font-size: 38px; }
            .steps { grid-template-columns: repeat(2, 1fr); }
            .section { padding: 56px 0; }
        }
        @media (max-width: 700px) {
            .nav-links { display: none; }
        }
        @media (max-width: 560px) {
            .hero h1 { font-size: 32px; }
            .steps { grid-template-columns: 1fr; }
            .hero-buttons .btn { flex: 1; }
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .feature-card, .btn { transition: none; }
            .feature-card:hover { transform: none; }
        }
    </style>
</head>

<body>

    {{-- ===================================================== --}}
    {{-- NAVBAR --}}
    {{-- ===================================================== --}}
    <nav class="navbar">
        <div class="container">
            <a href="#" class="brand" aria-label="HumasIn">Humas<span>In</span></a>

            <div class="nav-links">
                <a href="#agenda">Agenda</a>
                <a href="#layanan">Layanan</a>
                <a href="#alur">Alur</a>
                <a href="#faq">FAQ</a>
                @if (count($kontak))
                    <a href="#kontak">Kontak</a>
                @endif
            </div>

            <div class="nav-right">
                <a href="{{ route('login') }}" class="btn btn-primary">Masuk</a>
            </div>
        </div>
    </nav>


    {{-- ===================================================== --}}
    {{-- HERO + AGENDA TERDEKAT --}}
    {{-- ===================================================== --}}
    <section class="hero" id="agenda">
        <div class="container hero-grid">

            <div>
              

                <h1>Kelola Aktivitas Humas <span>Lebih Terintegrasi</span></h1>

                <p class="hero-desc">
                    HumasIn mendukung pengelolaan kegiatan, dokumentasi, peminjaman aset dan ruangan,
                    serta kebutuhan layanan Humas di lingkungan DJPK dalam satu tempat.
                </p>

                <div class="hero-buttons">
                    <a href="{{ route('login') }}" class="btn btn-primary">Masuk ke Sistem</a>
                    <a href="#layanan" class="btn btn-ghost">Lihat Layanan</a>
                </div>
            </div>

            {{-- Hanya judul, tanggal, dan jam yang ditampilkan ke publik --}}
            <aside class="agenda-card" aria-labelledby="agendaTitle">
                <div class="agenda-head">
                    <div class="agenda-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
                    </div>
                    <div>
                        <h2 id="agendaTitle">Agenda Terdekat</h2>
                        <p>Kegiatan Humas yang akan berlangsung</p>
                    </div>
                </div>

                <ul class="agenda-list">
                    @forelse ($eventTerdekat as $event)
                        @php
                            $tgl = \Carbon\Carbon::parse($event->tanggal_pelaksanaan)->locale('id');
                            $jam = substr((string) $event->jam_pelaksanaan, 0, 5);
                        @endphp
                        <li class="agenda-item">
                            <div class="agenda-date" aria-hidden="true">
                                <span class="d">{{ $tgl->format('d') }}</span>
                                <span class="m">{{ $tgl->translatedFormat('M') }}</span>
                            </div>
                            <div class="agenda-info">
                                <div class="agenda-name">{{ $event->nama_event }}</div>
                                <div class="agenda-meta">
                                    {{ $tgl->translatedFormat('l, d F Y') }}{{ $jam ? ' · ' . $jam . ' WIB' : '' }}
                                </div>
                            </div>
                            @if ($tgl->isToday())
                                <span class="agenda-badge">Hari ini</span>
                            @elseif ($tgl->isTomorrow())
                                <span class="agenda-badge soft">Besok</span>
                            @endif
                        </li>
                    @empty
                        <li class="agenda-empty">Belum ada agenda terdekat.</li>
                    @endforelse
                </ul>

                <a href="{{ route('login') }}" class="agenda-link">Masuk untuk melihat kalender lengkap &rarr;</a>
            </aside>

        </div>
    </section>


    {{-- ===================================================== --}}
    {{-- PENGUMUMAN (opsional) --}}
    {{-- ===================================================== --}}
    @if (count($pengumuman))
        <section class="section section-alt" id="pengumuman">
            <div class="container">
                <div class="section-title">
                    <h2>Pengumuman</h2>
                    <p>Informasi terbaru dari Humas DJPK.</p>
                </div>

                <div class="notice-list">
                    @foreach ($pengumuman as $info)
                        <article class="notice">
                            <h3>{{ $info['judul'] }}</h3>
                            <p>{{ $info['isi'] }}</p>
                            @if (!empty($info['tanggal']))
                                <time>{{ $info['tanggal'] }}</time>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ===================================================== --}}
    {{-- LAYANAN --}}
    {{-- ===================================================== --}}
    <section class="section" id="layanan">
        <div class="container">
            <div class="section-title">
                <h2>Layanan HumasIn</h2>
                <p>Fasilitas yang tersedia untuk mendukung aktivitas Humas DJPK.</p>
            </div>

            <div class="feature-grid">

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
                    </div>
                    <h3>Event &amp; Kalender</h3>
                    <p>Ajukan dukungan kegiatan dan pantau agenda serta status permohonan Anda.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 8 12 3 3 8l9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8M12 13v8"/></svg>
                    </div>
                    <h3>Peminjaman</h3>
                    <p>Pinjam aset, peralatan, dan ruangan Humas dengan stok dan status yang jelas.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    </div>
                    <h3>Dokumentasi Kegiatan</h3>
                    <p>Arsip tautan dokumentasi kegiatan yang tertata dalam folder kategori.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                    </div>
                    <h3>Template Desain</h3>
                    <p>Template, ikon, dan materi presentasi resmi yang siap diunduh.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 11v2a1 1 0 0 0 1 1h3l5 4V6L7 10H4a1 1 0 0 0-1 1z"/><path d="M16 8.5a5 5 0 0 1 0 7M19 6a8.5 8.5 0 0 1 0 12"/></svg>
                    </div>
                    <h3>Publikasi</h3>
                    <p>Pengelolaan materi dan publikasi kegiatan melalui kanal komunikasi Humas.</p>
                </div>

            </div>
        </div>
    </section>


    {{-- ===================================================== --}}
    {{-- ALUR PENGAJUAN --}}
    {{-- ===================================================== --}}
    <section class="section section-alt" id="alur">
        <div class="container">
            <div class="section-title">
                <h2>Alur Pengajuan Dukungan Kegiatan</h2>
                <p>Empat tahap dari pengajuan sampai kegiatan selesai.</p>
            </div>

            <ol class="steps">
                <li class="step">
                    <div class="step-no">1</div>
                    <h3>Ajukan</h3>
                    <p>TU unit kerja mengisi formulir dukungan kegiatan beserta kebutuhan yang diperlukan.</p>
                </li>
                <li class="step">
                    <div class="step-no">2</div>
                    <h3>Didelegasikan</h3>
                    <p>Super Admin menugaskan PIC yang tersedia untuk menangani kegiatan.</p>
                </li>
                <li class="step">
                    <div class="step-no">3</div>
                    <h3>Diproses</h3>
                    <p>PIC menyiapkan dukungan sesuai kebutuhan dan status dapat dipantau di sistem.</p>
                </li>
                <li class="step">
                    <div class="step-no">4</div>
                    <h3>Selesai</h3>
                    <p>Kegiatan terlaksana dan tercatat sebagai riwayat di sistem.</p>
                </li>
            </ol>

            <p class="steps-note">Pengajuan yang tidak dapat dipenuhi akan berstatus Ditolak.</p>
        </div>
    </section>


    {{-- ===================================================== --}}
    {{-- FAQ --}}
    {{-- ===================================================== --}}
    <section class="section" id="faq">
        <div class="container">
            <div class="section-title">
                <h2>Pertanyaan Umum</h2>
                <p>Jawaban singkat seputar penggunaan HumasIn.</p>
            </div>

            <div class="faq">
                <details>
                    <summary>Siapa yang dapat mengajukan dukungan kegiatan?</summary>
                    <p>Pengajuan dilakukan oleh TU unit kerja (Eselon II) melalui menu Event &amp; Kalender setelah masuk ke sistem.</p>
                </details>

                <details>
                    <summary>Dukungan apa saja yang dapat diminta?</summary>
                    <p>Operator, desain grafis, editing, konten web, merchandise, dokumentasi, dan tim acara. Pilih sesuai kebutuhan kegiatan saat mengisi formulir.</p>
                </details>

                <details>
                    <summary>Bagaimana memantau status pengajuan?</summary>
                    <p>Status tampil di daftar pengajuan pada menu Event &amp; Kalender, yaitu Menunggu, Didelegasikan, Diproses, Selesai, atau Ditolak.</p>
                </details>

                <details>
                    <summary>Bagaimana meminjam aset atau ruangan?</summary>
                    <p>Buka menu Peminjaman, pilih aset atau ruangan yang tersedia, lalu ikuti proses peminjaman. Status ketersediaan dan stok ditampilkan di katalog.</p>
                </details>
            </div>
        </div>
    </section>


    {{-- ===================================================== --}}
    {{-- KONTAK (opsional) --}}
    {{-- ===================================================== --}}
    @if (count($kontak))
        <section class="section section-alt" id="kontak">
            <div class="container">
                <div class="section-title">
                    <h2>Kontak Humas</h2>
                    <p>Hubungi kami untuk bantuan lebih lanjut.</p>
                </div>

                <div class="contact-grid">
                    @foreach ($kontak as $label => $nilai)
                        <div class="contact-card">
                            <div class="contact-label">{{ $label }}</div>
                            <div class="contact-value">{{ $nilai }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ===================================================== --}}
    {{-- FOOTER --}}
    {{-- ===================================================== --}}
    <footer>
        <div class="container footer-inner">
            <div>&copy; {{ date('Y') }} HumasIn &mdash; Sistem Informasi Humas DJPK</div>
            <div class="footer-links">
                <a href="#layanan">Layanan</a>
                <a href="#alur">Alur</a>
                <a href="#faq">FAQ</a>
                <a href="{{ route('login') }}">Masuk</a>
            </div>
        </div>
    </footer>

</body>

</html>  