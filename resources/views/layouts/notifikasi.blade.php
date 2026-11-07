{{-- NOTIFIKASI DROPDOWN MAC STYLE --}}
<div style="position: relative; display: inline-block; margin-right: 16px;" x-data="{ open: false }">
    <button @click="open = !open" style="
        background: #f4f6f8;
        border: 1px solid #e2e8f0;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        transition: background 0.15s;
    ">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; color: #475569;">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>

        @php
            $unreadCount = auth()->check() ? auth()->user()->notifikasiBelumDibaca()->count() : 0;
        @endphp

        @if ($unreadCount > 0)
            <span style="
                position: absolute;
                top: 2px;
                right: 2px;
                background: #ef4444;
                color: white;
                font-size: 10px;
                font-weight: 700;
                width: 16px;
                height: 16px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            ">{{ $unreadCount }}</span>
        @endif
    </button>

    {{-- Dropdown Box --}}
    <div x-show="open" @click.away="open = false" style="
        display: none;
        position: absolute;
        right: 0;
        top: 48px;
        width: 320px;
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
        z-index: 99;
    ">
        <div style="padding: 14px 16px; background: #fafbfc; border-bottom: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 13px; font-weight: 600; color: #1e293b;">Notifikasi</span>
            @if ($unreadCount > 0)
                <form action="{{ route('notifikasi.bacaSemua') }}" method="POST">
                    @csrf
                    <button type="submit" style="background: none; border: none; font-size: 11px; color: #4e8bbc; cursor: pointer; font-weight: 500;">Tandai semua dibaca</button>
                </form>
            @endif
        </div>

        <div style="max-height: 280px; overflow-y: auto;">
            @forelse(auth()->user()->notifikasi as $notif)
                <a href="{{ route('notifikasi.baca', $notif->id) }}" style="
                    display: block;
                    padding: 12px 16px;
                    border-bottom: 1px solid #f1f5f9;
                    text-decoration: none;
                    background: {{ $notif->dibaca_pada ? '#ffffff' : '#f8fafc' }};
                    transition: background 0.1s;
                ">
                    <div style="font-size: 12px; font-weight: 600; color: #1e293b; margin-bottom: 2px;">{{ $notif->judul }}</div>
                    <div style="font-size: 11px; color: #64748b; line-height: 1.4;">{{ $notif->pesan }}</div>
                    <div style="font-size: 10px; color: #94a3b8; margin-top: 4px;">{{ $notif->created_at->diffForHumans() }}</div>
                </a>
            @empty
                <div style="padding: 30px; text-align: center; color: #94a3b8; font-size: 12px;">
                    Belum ada notifikasi.
                </div>
            @endforelse
        </div>
    </div>
</div>