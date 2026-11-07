```blade
@extends('layouts.app')

@section('content')
<div style="padding: 28px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <p style="color: #6EAE78; font-size: 12px; font-weight: 700; letter-spacing: 1px;">
                DATA & MANAJEMEN
            </p>
            <h2 style="margin: 6px 0;">Data Unit Kerja</h2>
            <p style="color: #888;">Kelola unit kerja Eselon II DJPK.</p>
        </div>
    </div>

    <div style="background: white; border: 1px solid #eee; border-radius: 12px; padding: 20px;">
        <div style="margin-bottom: 18px;">
            <strong>Daftar Unit Kerja</strong>
            <p style="font-size: 13px; color: #888; margin-top: 5px;">
                Total {{ $units->total() }} unit
            </p>
        </div>

        <form method="GET" action="{{ route('unit.index') }}"
              style="display: flex; gap: 10px; margin-bottom: 20px;">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari kode atau nama unit..."
                style="flex: 1; padding: 11px; border: 1px solid #ddd; border-radius: 8px;"
            >
            <button type="submit"
                    style="padding: 10px 18px; background: #6EAE78; color: white; border: 0; border-radius: 8px; cursor: pointer;">
                Cari
            </button>
        </form>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #f8f9f8;">
                        <th style="padding: 13px;">No.</th>
                        <th style="padding: 13px;">Kode Unit</th>
                        <th style="padding: 13px;">Nama Unit</th>
                        <th style="padding: 13px;">Jumlah Karyawan</th>
                        <th style="padding: 13px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($units as $unit)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 13px;">
                                {{ $units->firstItem() + $loop->index }}
                            </td>
                            <td style="padding: 13px;">{{ $unit->kode_unit }}</td>
                            <td style="padding: 13px;">{{ $unit->nama_unit }}</td>
                            <td style="padding: 13px;">
                                {{ $unit->karyawan_count }}
                            </td>
                            <td style="padding: 13px;">
                                <span style="padding: 5px 10px; border-radius: 20px; font-size: 12px; background: {{ $unit->aktif ? '#edf7ef' : '#fbeeee' }}; color: {{ $unit->aktif ? '#438653' : '#b42318' }};">
                                    {{ $unit->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 30px; text-align: center; color: #888;">
                                Belum ada data unit kerja.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $units->links() }}
        </div>
    </div>
</div>
@endsection
```