@extends('layouts.app')

@section('title', 'Notifikasi | HumasIn')
@section('page-title', 'Notifikasi')
@section('page-description', 'Semua aktivitas dan pemberitahuan untuk Anda')

@section('content')

<style>
    .nf-page {
        --nf-accent: #4E8BBC; --nf-accent-soft: rgba(78,139,188,.12);
        --nf-text: #1d1d1f; --nf-muted: #6e6e73; --nf-line: rgba(0,0,0,.08);
        --nf-shadow: 0 1px 3px rgba(0,0,0,.04), 0 6px 20px rgba(0,0,0,.04);
        max-width: 900px; margin: 0 auto; color: var(--nf-text);
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Inter", "Segoe UI", sans-serif;
        -webkit-font-smoothing: antialiased;
    }
    .nf-page *, .nf-page *::before, .nf-page *::after { box-sizing: border-box; }

    .nf-page .nf-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; }
    .nf-page .nf-title { font-size: 24px; font-weight: 700; letter-spacing: -.02em; margin: 0 0 4px; }
    .nf-page .nf-sub { font-size: 13px; color: var(--nf-muted); margin: 0; }
    .nf-page .nf-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
    .nf-page .nf-actions form { margin: 0; }

    .nf-page .nf-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 36px; padding: 0 16px; border-radius: 10px; border: 0; font: inherit; font-size: 12.5px; font-weight: 600; cursor: pointer; transition: filter .15s, background .15s; text-decoration: none; }
    .nf-page .nf-btn-ok { background: var(--nf-accent); color: #fff; } .nf-page .nf-btn-ok:hover { filter: brightness(1.07); }
    .nf-page .nf-btn-no { background: rgba(255,59,48,.1); color: #c9342a; } .nf-page .nf-btn-no:hover { background: rgba(255,59,48,.18); }

    .nf-page .nf-tabs { display: inline-flex; gap: 2px; padding: 3px; margin-bottom: 16px; border-radius: 11px; background: rgba(120,120,128,.12); }
    .nf-page .nf-tab { padding: 7px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; color: var(--nf-muted); text-decoration: none; }
    .nf-page .nf-tab.active { background: #fff; color: var(--nf-text); box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 0 0 .5px rgba(0,0,0,.04); }
    .nf-page .nf-tab b { margin-left: 6px; padding: 1px 7px; border-radius: 999px; background: var(--nf-accent-soft); color: #3b7aab; font-size: 11px; }

    .nf-page .nf-card { background: #fff; border: 1px solid var(--nf-line); border-radius: 16px; box-shadow: var(--nf-shadow); overflow: visible; margin-bottom: 20px; }
    
    .nf-page .nf-item { 
        display: flex; 
        align-items: center; 
        gap: 16px; 
        padding: 16px 20px; 
        border-bottom: 1px solid var(--nf-line); 
        transition: background .15s; 
        position: relative; 
    }
    .nf-page .nf-item:last-child { border-bottom: 0; }
    .nf-page .nf-item:hover { background: #f8fafc; }
    .nf-page .nf-item.unread { background: rgba(78,139,188,.04); }
    .nf-page .nf-item.unread:hover { background: rgba(78,139,188,.08); }

    .nf-page .nf-checkbox { 
        width: 18px; 
        height: 18px; 
        accent-color: var(--nf-accent); 
        cursor: pointer; 
        flex-shrink: 0; 
        opacity: 0.2;
        transition: opacity .15s ease;
    }
    .nf-page .nf-item:hover .nf-checkbox,
    .nf-page .nf-checkbox:checked {
        opacity: 1;
    }

    .nf-page .nf-body { flex: 1; min-width: 0; }
    .nf-page .nf-judul { display: flex; align-items: center; gap: 8px; font-size: 13.5px; font-weight: 600; color: var(--nf-text); }
    .nf-page .nf-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--nf-accent); flex-shrink: 0; }
    .nf-page .nf-pesan { font-size: 13px; line-height: 1.5; color: #4a4a4f; margin-top: 2px; }
    .nf-page .nf-time { font-size: 11.5px; color: var(--nf-muted); margin-top: 4px; }

    .nf-dropdown { position: relative; display: flex; align-items: center; flex-shrink: 0; }
    .nf-dots-btn { 
        background: transparent; 
        border: none; 
        width: 32px; 
        height: 32px; 
        border-radius: 6px; 
        cursor: pointer; 
        display: grid; 
        place-items: center; 
        color: #6e6e73; 
        transition: background .15s ease; 
    }
    .nf-dots-btn:hover { background: rgba(0,0,0,.06); color: #1d1d1f; }
    .nf-dots-btn svg { width: 16px; height: 16px; }

    .nf-menu { 
        display: none; 
        position: absolute; 
        right: 0; 
        top: 38px; 
        background: #fff; 
        min-width: 180px; 
        box-shadow: 0 10px 25px rgba(0,0,0,.1); 
        border: 1px solid rgba(0,0,0,.08); 
        border-radius: 10px; 
        z-index: 99; 
        overflow: hidden; 
        padding: 5px; 
    }
    .nf-menu.show { display: block; }

    .nf-menu button, .nf-menu a, .nf-menu form { 
        display: block; 
        width: 100%; 
        margin: 0;
    }
    .nf-menu button, .nf-menu a { 
        display: flex; 
        width: 100%; 
        align-items: center; 
        gap: 8px; 
        padding: 8px 12px; 
        font-size: 12.5px; 
        font-weight: 500; 
        color: #333; 
        background: transparent; 
        border: none; 
        border-radius: 6px; 
        text-decoration: none; 
        text-align: left; 
        cursor: pointer; 
        transition: background .1s; 
    }
    .nf-menu button:hover, .nf-menu a:hover { background: #f2f4f7; color: #1d1d1f; }
    .nf-menu .text-danger { color: #c9342a; }
    .nf-menu .text-danger:hover { background: rgba(255,59,48,.08); color: #c9342a; }

    .nf-page .nf-empty { padding: 60px 20px; text-align: center; color: var(--nf-muted); font-size: 13px; }
    .nf-page .nf-empty svg { width: 40px; height: 40px; color: #c7c7cc; margin-bottom: 10px; }
    .nf-page .nf-empty strong { display: block; font-size: 15px; color: var(--nf-text); margin-bottom: 4px; }
    .nf-page .nf-foot { margin-top: 20px; display: flex; justify-content: center; }
</style>

@php
    $totalSemua = $totalSemua ?? ($notifikasi->total ?? 0);
    $totalBelum = $totalBelum ?? 0;
    $filter = $filter ?? 'semua';
@endphp

<div class="nf-page">

    <div class="nf-head">
        <div>
            <h2 class="nf-title">Notifikasi</h2>
            <p class="nf-sub">{{ $totalBelum ? $totalBelum . ' notifikasi belum dibaca.' : 'Semua notifikasi sudah dibaca.' }}</p>
        </div>

        <div class="nf-actions">
            <form action="{{ route('notifikasi.bacaSemua') }}" method="POST">
                @csrf
                <button type="submit" class="nf-btn nf-btn-ok">Tandai Semua Dibaca</button>
            </form>

            @if ($totalSemua > 0)
                <form action="{{ route('notifikasi.hapusSemua') }}" method="POST" onsubmit="return confirm('PERINGATAN: Yakin ingin menghapus SELURUH notifikasi Anda?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="nf-btn nf-btn-no">Hapus Semua</button>
                </form>
            @endif
        </div>
    </div>

    {{-- Tabs Filter --}}
    <div class="nf-tabs">
        <a href="{{ route('notifikasi.index', ['filter' => 'semua']) }}" class="nf-tab {{ $filter === 'semua' ? 'active' : '' }}">
            Semua <b>{{ $totalSemua }}</b>
        </a>
        <a href="{{ route('notifikasi.index', ['filter' => 'belum']) }}" class="nf-tab {{ $filter === 'belum' ? 'active' : '' }}">
            Belum Dibaca <b>{{ $totalBelum }}</b>
        </a>
    </div>

    <!-- Kotak Kartu Pembungkus Notifikasi -->
    <div class="nf-card">
        @if ($totalSemua > 0)
            <div style="padding: 12px 20px; border-bottom: 1px solid var(--nf-line); display: flex; align-items: center; justify-content: space-between; font-size: 13px; background: #fafbfc;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: 600;">
                    <input type="checkbox" id="pilih-semua" class="nf-checkbox" style="opacity: 1;"> Pilih Semua di Halaman Ini
                </label>
                <!-- Form Hapus Terpilih dipindah ke luar item list agar tidak bentrok -->
                <form action="{{ route('notifikasi.hapusTerpilih') }}" method="POST" id="form-hapus-terpilih" style="margin: 0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="nf-btn nf-btn-no" style="height: 30px; font-size: 11px; padding: 0 12px;" onclick="return confirm('Hapus notifikasi yang dipilih?')">
                        Hapus Terpilih
                    </button>
                </form>
            </div>
        @endif

        @forelse ($notifikasi as $n)
            @php
                $belum = ! $n->dibaca_pada;
            @endphp

            <div class="nf-item {{ $belum ? 'unread' : '' }}">
                <!-- Checkbox per item terhubung dengan form hapus terpilih di atas via atribut form -->
                <input type="checkbox" name="ids[]" value="{{ $n->id }}" form="form-hapus-terpilih" class="nf-checkbox item-checkbox">

                <div class="nf-body">
                    <div class="nf-judul">
                        @if ($belum)<span class="nf-dot"></span>@endif 
                        {{ $n->judul }}
                    </div>
                    <div class="nf-pesan">{{ $n->pesan }}</div>
                    <div class="nf-time">
                        {{ optional($n->created_at)->diffForHumans() }} 
                        @if($n->created_at)
                            · {{ $n->created_at->translatedFormat('d M Y, H:i') }}
                        @endif
                    </div>
                </div>

                <!-- Dropdown Titik Tiga -->
                <div class="nf-dropdown">
                    <button type="button" class="nf-dots-btn" onclick="toggleDropdown(event, 'dropdown-{{ $n->id }}')" title="Aksi lainnya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                        </svg>
                    </button>

                    <div id="dropdown-{{ $n->id }}" class="nf-menu">
                        @if (!empty($n->url))
                            <a href="{{ route('notifikasi.baca', $n->id) }}">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                Buka
                            </a>
                        @endif

                        <!-- Form Tandai Sudah/Belum Baca (Terpisah Aman) -->
                        <form action="{{ route('notifikasi.tandai', $n->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                {{ $belum ? 'Tandai Sudah Dibaca' : 'Tandai Belum Baca' }}
                            </button>
                        </form>


                        <!-- Form Hapus Satuan (Terpisah Aman) -->
                        <form action="{{ route('notifikasi.destroy', $n->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-danger">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="nf-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 0 1-3.4 0"/></svg>
                <strong>{{ $filter === 'belum' ? 'Tidak ada yang belum dibaca' : 'Belum ada notifikasi' }}</strong>
                Aktivitas terbaru akan muncul di sini.
            </div>
        @endforelse
    </div>

    @if (method_exists($notifikasi, 'hasPages') && $notifikasi->hasPages())
        <div class="nf-foot">{{ $notifikasi->links() }}</div>
    @endif

</div>

<!-- Script Dropdown & Checkbox -->
<script>
    function toggleDropdown(event, menuId) {
        event.stopPropagation();
        document.querySelectorAll('.nf-menu').forEach(menu => {
            if (menu.id !== menuId) menu.classList.remove('show');
        });
        document.getElementById(menuId).classList.toggle('show');
    }

    function toggleSelectCheckbox(button) {
        const itemDiv = button.closest('.nf-item');
        const checkbox = itemDiv.querySelector('.item-checkbox');
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
        }
    }

    document.addEventListener('click', function () {
        document.querySelectorAll('.nf-menu').forEach(menu => menu.classList.remove('show'));
    });

    document.addEventListener('DOMContentLoaded', function () {
        const pilihSemua = document.getElementById('pilih-semua');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');

        if (pilihSemua) {
            pilihSemua.addEventListener('change', function () {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = pilihSemua.checked;
                });
            });
        }
    });
</script>

@endsection