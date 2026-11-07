@extends('layouts.app')

@section('title', 'Status & Penugasan Admin | HumasIn')
@section('page-title', 'Status & Penugasan Admin')
@section('page-description', 'Perbarui status kesiapan tugas agar pekerjaan bisa didelegasikan secara efektif')

@section('content')

<style>
    .st-page {
        --st-accent: #4E8BBC;
        --st-accent-soft: rgba(78, 139, 188, .12);
        --st-text: #1d1d1f;
        --st-muted: #6e6e73;
        --st-line: rgba(0, 0, 0, .07);
        --st-shadow: 0 1px 2px rgba(0,0,0,.04), 0 8px 24px rgba(0,0,0,.04);

        max-width: 1280px; margin: 0 auto; color: var(--st-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .st-page *, .st-page *::before, .st-page *::after { box-sizing: border-box; }

    .st-page .st-head { margin-bottom: 28px; }
    .st-page .st-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .st-page .st-sub { font-size: 13px; color: var(--st-muted); margin: 0; }

    .st-page .st-layout { display: grid; grid-template-columns: 400px 1fr; gap: 24px; align-items: start; }

    .st-page .st-card { background: #fff; border: 1px solid var(--st-line); border-radius: 18px; box-shadow: var(--st-shadow); overflow: hidden; }
    .st-page .st-card-head { display: flex; align-items: center; gap: 12px; padding: 20px 24px; border-bottom: 1px solid var(--st-line); }
    .st-page .st-card-icon { width: 36px; height: 36px; flex-shrink: 0; display: grid; place-items: center; border-radius: 10px; background: rgba(78,139,188,.14); color: #3b7aab; }
    .st-page .st-card-icon svg { width: 18px; height: 18px; }
    .st-page .st-card-title { font-size: 15px; font-weight: 700; letter-spacing: -.01em; margin: 0; }
    .st-page .st-card-desc { font-size: 12px; color: var(--st-muted); margin: 2px 0 0; }
    .st-page .st-card-body { padding: 24px; }
    .st-page .st-card-foot { padding: 16px 24px; border-top: 1px solid var(--st-line); background: #fafafc; }

    /* Form */
    .st-page .st-field { margin-bottom: 22px; }
    .st-page .st-field:last-child { margin-bottom: 0; }
    .st-page .st-label { display: block; font-size: 12px; font-weight: 600; color: #3a3a3c; margin-bottom: 8px; }
    .st-page .st-req { color: #ff3b30; margin-left: 2px; }
    .st-page .st-error { font-size: 11px; color: #c9342a; margin-top: 6px; }

    .st-page .st-options { display: flex; flex-direction: column; gap: 8px; }
    .st-page .st-option { position: relative; display: block; cursor: pointer; }
    .st-page .st-option input { position: absolute; opacity: 0; pointer-events: none; }
    .st-page .st-option-box {
        display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 12px;
        border: 1px solid var(--st-line); background: rgba(120,120,128,.06);
        transition: background .15s, border-color .15s, box-shadow .15s;
    }
    .st-page .st-option:hover .st-option-box { background: rgba(120,120,128,.1); }
    .st-page .st-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; background: var(--dot); }
    .st-page .st-option-text strong { display: block; font-size: 13px; font-weight: 600; }
    .st-page .st-option-text span { display: block; font-size: 11px; color: var(--st-muted); margin-top: 1px; }
    .st-page .st-check {
        margin-left: auto; width: 18px; height: 18px; border-radius: 50%; flex-shrink: 0;
        border: 1.5px solid #c7c7cc; display: grid; place-items: center; color: transparent; transition: all .15s;
    }
    .st-page .st-check svg { width: 11px; height: 11px; }
    .st-page .st-option input:checked + .st-option-box { background: #fff; border-color: var(--st-accent); box-shadow: 0 0 0 4px var(--st-accent-soft); }
    .st-page .st-option input:checked + .st-option-box .st-check { background: var(--st-accent); border-color: var(--st-accent); color: #fff; }
    .st-page .st-option input:focus-visible + .st-option-box { box-shadow: 0 0 0 4px var(--st-accent-soft); }
    .st-page .dot-green { --dot: #34c759; }
    .st-page .dot-amber { --dot: #ff9f0a; }
    .st-page .dot-red   { --dot: #ff3b30; }

    .st-page .st-control {
        width: 100%; padding: 12px 14px; min-height: 110px; border-radius: 10px; resize: vertical;
        border: 1px solid var(--st-line); background: rgba(120,120,128,.08); color: var(--st-text);
        font: inherit; font-size: 13px; line-height: 1.5; outline: none; transition: background .15s, box-shadow .15s, border-color .15s;
    }
    .st-page .st-control::placeholder { color: #8e8e93; }
    .st-page .st-control:focus { background: #fff; border-color: var(--st-accent); box-shadow: 0 0 0 4px var(--st-accent-soft); }

    .st-page .st-btn {
        display: inline-flex; align-items: center; justify-content: center; width: 100%; height: 40px; border-radius: 10px; border: 0;
        background: var(--st-accent); color: #fff; font: inherit; font-size: 13px; font-weight: 600; cursor: pointer; transition: filter .15s;
    }
    .st-page .st-btn:hover { filter: brightness(1.06); }

    /* Tabel */
    .st-page .st-table-wrap { overflow-x: auto; }
    .st-page .st-table { width: 100%; min-width: 560px; border-collapse: collapse; }
    .st-page .st-table thead th {
        padding: 12px 20px; text-align: left; background: #fafafc; font-size: 11px; font-weight: 600;
        letter-spacing: .06em; text-transform: uppercase; color: var(--st-muted); border-bottom: 1px solid var(--st-line); white-space: nowrap;
    }
    .st-page .st-table tbody td { padding: 14px 20px; vertical-align: middle; border-bottom: 1px solid var(--st-line); font-size: 13px; }
    .st-page .st-table tbody tr:last-child td { border-bottom: 0; }
    .st-page .st-table tbody tr { transition: background .15s; }
    .st-page .st-table tbody tr:hover { background: #f7f9fc; }

    .st-page .st-person { display: flex; align-items: center; gap: 12px; }
    .st-page .st-avatar { width: 36px; height: 36px; flex-shrink: 0; display: grid; place-items: center; border-radius: 50%; background: #C2E1FC; color: #2b5c8f; font-size: 13px; font-weight: 700; }
    .st-page .st-person-name { font-weight: 600; letter-spacing: -.005em; }
    .st-page .st-person-unit { font-size: 11px; color: var(--st-muted); margin-top: 1px; }
    .st-page .st-note { color: var(--st-muted); line-height: 1.5; max-width: 320px; }

    .st-page .st-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 8px; border-radius: 999px; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .st-page .st-badge::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
    .st-page .st-badge-green { background: rgba(52,199,89,.14); color: #248a3d; }
    .st-page .st-badge-amber { background: rgba(255,159,10,.16); color: #b26a00; }
    .st-page .st-badge-red   { background: rgba(255,59,48,.12); color: #c9342a; }

    .st-page .st-empty { padding: 56px 20px !important; text-align: center; color: var(--st-muted); font-size: 13px; }

    @media (max-width: 1100px) { .st-page .st-layout { grid-template-columns: 1fr; } }
</style>

<div class="st-page">

    <div class="st-head">
        <h2 class="st-title"></h2>
        <p class="st-sub"></p>
    </div>

    <div class="st-layout">

        {{-- FORM STATUS SENDIRI --}}
        <form action="{{ route('admin.status.update') }}" method="POST" class="st-card">
            @csrf
            @method('PATCH')

            <div class="st-card-head">
                <div class="st-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                </div>
                <div>
                    <h3 class="st-card-title">Perbarui Status Saya</h3>
                    <p class="st-card-desc">Status ini terlihat oleh seluruh tim.</p>
                </div>
            </div>

            <div class="st-card-body">

                <div class="st-field">
                    <label class="st-label">Status Ketersediaan<span class="st-req">*</span></label>

                    @php $statusSekarang = old('status_ketersediaan', $currentStatus->status_ketersediaan); @endphp

                    <div class="st-options">
                        <label class="st-option">
                            <input type="radio" name="status_ketersediaan" value="TERSEDIA" {{ $statusSekarang === 'TERSEDIA' ? 'checked' : '' }} required>
                            <div class="st-option-box">
                                <span class="st-dot dot-green"></span>
                                <div class="st-option-text">
                                    <strong>Tersedia</strong>
                                    <span>Siap ditugaskan</span>
                                </div>
                                <span class="st-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg></span>
                            </div>
                        </label>

                        <label class="st-option">
                            <input type="radio" name="status_ketersediaan" value="SIBUK" {{ $statusSekarang === 'SIBUK' ? 'checked' : '' }}>
                            <div class="st-option-box">
                                <span class="st-dot dot-amber"></span>
                                <div class="st-option-text">
                                    <strong>Sibuk</strong>
                                    <span>Sedang mengerjakan tugas lain</span>
                                </div>
                                <span class="st-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg></span>
                            </div>
                        </label>

                        <label class="st-option">
                            <input type="radio" name="status_ketersediaan" value="TUGAS_LUAR" {{ $statusSekarang === 'TUGAS_LUAR' ? 'checked' : '' }}>
                            <div class="st-option-box">
                                <span class="st-dot dot-red"></span>
                                <div class="st-option-text">
                                    <strong>Tugas Luar</strong>
                                    <span>Dinas / di luar layanan Humas</span>
                                </div>
                                <span class="st-check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg></span>
                            </div>
                        </label>
                    </div>

                    @error('status_ketersediaan') <div class="st-error">{{ $message }}</div> @enderror
                </div>

                <div class="st-field">
                    <label class="st-label">Keterangan (Opsional)</label>
                    <textarea name="deskripsi_pekerjaan" rows="4" class="st-control" placeholder="Contoh: Sedang meliput acara di luar kantor atau mengerjakan laporan khusus...">{{ old('deskripsi_pekerjaan', $currentStatus->deskripsi_pekerjaan) }}</textarea>
                    @error('deskripsi_pekerjaan') <div class="st-error">{{ $message }}</div> @enderror
                </div>

            </div>

            <div class="st-card-foot">
                <button type="submit" class="st-btn">Simpan Perubahan Status</button>
            </div>
        </form>

        {{-- MONITORING TIM --}}
        <div class="st-card">
            <div class="st-card-head">
                <div class="st-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0M16 4.5a3.5 3.5 0 0 1 0 7M18 14.5a6.5 6.5 0 0 1 3.5 5.5"/></svg>
                </div>
                <div>
                    <h3 class="st-card-title">Monitoring Ketersediaan Tim</h3>
                    <p class="st-card-desc">Admin dan staf Humas.</p>
                </div>
            </div>

            <div class="st-table-wrap">
                <table class="st-table">
                    <thead>
                        <tr>
                            <th>Nama Staf / Admin</th>
                            <th>Status</th>
                            <th>Keterangan / Pekerjaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($allStatuses as $stat)
                            @php
                                $nama = $stat->user?->karyawan?->nama ?? $stat->user?->name ?? 'Admin';
                                $unit = $stat->user?->karyawan?->unit?->nama_unit ?? '-';
                                $inisial = strtoupper(mb_substr($nama, 0, 1));

                                if ($stat->status_ketersediaan === 'TERSEDIA') {
                                    $badgeClass = 'st-badge-green'; $label = 'Tersedia';
                                } elseif ($stat->status_ketersediaan === 'SIBUK') {
                                    $badgeClass = 'st-badge-amber'; $label = 'Sibuk';
                                } else {
                                    $badgeClass = 'st-badge-red'; $label = 'Tugas Luar';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="st-person">
                                        <div class="st-avatar">{{ $inisial }}</div>
                                        <div>
                                            <div class="st-person-name">{{ $nama }}</div>
                                            <div class="st-person-unit">{{ $unit }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="st-badge {{ $badgeClass }}">{{ $label }}</span></td>
                                <td class="st-note">{{ $stat->deskripsi_pekerjaan ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="st-empty">Belum ada data status admin yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection