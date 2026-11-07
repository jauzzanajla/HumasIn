<?php

namespace App\Http\Controllers;

use App\Models\AdminStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminStatusController extends Controller
{
    /** Pilihan status (sesuai enum di tabel admin_statuses). */
    public const STATUS = [
        'TERSEDIA'   => 'Tersedia',
        'SIBUK'      => 'Sibuk',
        'TUGAS_LUAR' => 'Tugas Luar',
    ];

    /**
     * Halaman status ketersediaan: status milik sendiri + status seluruh tim Humas.
     */
    public function index()
    {
        $userId = Auth::id();

        // Semua akun Super Admin & Admin (tim Humas)
        $admin = User::with(['karyawan.unit', 'peran'])
            ->whereHas('peran', fn ($q) => $q->whereIn('kode_peran', ['SUPER_ADMIN', 'ADMIN']))
            ->orderBy('name')
            ->get();

        // Status yang sudah tercatat, dikelompokkan per user
        $statusPerUser = AdminStatus::whereIn('user_id', $admin->pluck('id'))
            ->get()
            ->keyBy('user_id');

        $tim = $admin->map(function ($u) use ($statusPerUser) {
            $s = $statusPerUser->get($u->id);

            return [
                'id'         => $u->id,
                'nama'       => $u->karyawan?->nama ?? $u->name,
                'unit'       => $u->karyawan?->unit?->nama_unit,
                'peran'      => $u->peran?->nama_peran,
                'status'     => $s?->status_ketersediaan ?? 'TERSEDIA',
                'deskripsi'  => $s?->deskripsi_pekerjaan,
                'diperbarui' => $s?->updated_at,
            ];
        });

        $statusSaya = $statusPerUser->get($userId);

        $ringkasan = collect(self::STATUS)->map(fn ($label, $kode) => $tim->where('status', $kode)->count());

        return view('admin.status-ketersediaan', [
            'tim'        => $tim,
            'statusSaya' => $statusSaya?->status_ketersediaan ?? 'TERSEDIA',
            'deskripsi'  => $statusSaya?->deskripsi_pekerjaan,
            'pilihan'    => self::STATUS,
            'ringkasan'  => $ringkasan,
            'userId'     => $userId,
        ]);
    }

    /**
     * Simpan status milik user yang sedang login.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'status_ketersediaan' => ['required', Rule::in(array_keys(self::STATUS))],
            'deskripsi_pekerjaan' => ['nullable', 'string', 'max:255'],
        ], [
            'status_ketersediaan.required' => 'Pilih status ketersediaan.',
            'status_ketersediaan.in'       => 'Status tidak valid.',
            'deskripsi_pekerjaan.max'      => 'Keterangan maksimal 255 karakter.',
        ]);

        $userId = Auth::id();

        $status = AdminStatus::where('user_id', $userId)->first() ?? new AdminStatus();
        $status->forceFill([
            'user_id'             => $userId,
            'status_ketersediaan' => $data['status_ketersediaan'],
            'deskripsi_pekerjaan' => $data['deskripsi_pekerjaan'] ?? null,
        ])->save();

        return redirect()
            ->route('admin.status.index')
            ->with('success', 'Status ketersediaan berhasil diperbarui.');
    }
}