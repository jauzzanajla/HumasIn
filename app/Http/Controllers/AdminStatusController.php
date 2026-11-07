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
    private const STATUS = ['TERSEDIA', 'SIBUK', 'TUGAS_LUAR'];

    /**
     * Halaman Status & Penugasan Admin (view: admin/status/index.blade.php).
     *
     * Variabel untuk view:
     * - $currentStatus : status milik user yang login (status_ketersediaan, deskripsi_pekerjaan)
     * - $allStatuses   : status seluruh Super Admin & Admin, masing-masing dengan relasi user
     */
    public function index()
    {
        $userId = Auth::id();

        // Semua akun Super Admin & Admin (tim Humas)
        $admin = User::with(['karyawan.unit'])
            ->whereHas('peran', fn ($q) => $q->whereIn('kode_peran', ['SUPER_ADMIN', 'ADMIN']))
            ->orderBy('name')
            ->get();

        // Status yang sudah tercatat
        $tercatat = AdminStatus::whereIn('user_id', $admin->pluck('id'))->get()->keyBy('user_id');

        // Admin yang belum pernah mengisi status tetap tampil, dianggap "Tersedia"
        $allStatuses = $admin->map(function ($u) use ($tercatat) {
            $status = $tercatat->get($u->id) ?? (new AdminStatus())->forceFill([
                'user_id'             => $u->id,
                'status_ketersediaan' => 'TERSEDIA',
                'deskripsi_pekerjaan' => null,
            ]);

            return $status->setRelation('user', $u);
        });

        $currentStatus = $allStatuses->firstWhere('user_id', $userId)
            ?? (new AdminStatus())->forceFill([
                'user_id'             => $userId,
                'status_ketersediaan' => 'TERSEDIA',
                'deskripsi_pekerjaan' => null,
            ]);

        return view('admin.status.index', compact('currentStatus', 'allStatuses'));
    }

    /**
     * Simpan status milik user yang sedang login.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'status_ketersediaan' => ['required', Rule::in(self::STATUS)],
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