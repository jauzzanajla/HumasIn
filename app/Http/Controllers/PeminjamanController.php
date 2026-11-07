<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeminjamanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HELPER SQL (jalan di MySQL lokal & PostgreSQL Supabase)
    |--------------------------------------------------------------------------
    */

    /** Gabungan kolom tanggal + jam, contoh hasil: "2026-11-08 09:30:00". */
    private const MULAI   = "CONCAT(tanggal_mulai, ' ', jam_mulai)";
    private const SELESAI = "CONCAT(tanggal_selesai, ' ', jam_selesai)";

    /** Samakan format waktu pembanding menjadi "Y-m-d H:i:s" supaya perbandingannya tepat. */
    private function waktu($nilai): string
    {
        return Carbon::parse($nilai)->format('Y-m-d H:i:s');
    }

    /*
    |--------------------------------------------------------------------------
    | KATALOG DAN KELOLA PINJAMAN
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $jadwalRuangan = $this->jadwalRuangan();

        return view('peminjaman.index', compact('jadwalRuangan'));
    }

    private function jadwalRuangan()
    {
        return Peminjaman::with('ruangan')
            ->whereNotNull('ruangan_id')
            ->whereIn('status', ['PENDING', 'DISETUJUI'])
            ->where(function ($q) {
                $q->whereNull('status_pengembalian')
                    ->orWhere('status_pengembalian', '!=', 'DIKEMBALIKAN');
            })
            ->whereRaw(self::MULAI . ' < ?', [$this->waktu(now()->addMonth())])
            ->whereRaw(self::SELESAI . ' > ?', [$this->waktu(now())])
            ->orderBy('tanggal_mulai')
            ->orderBy('jam_mulai')
            ->get();
    }

    public function kelola(Request $request)
    {
        $user = Auth::user();
        $user?->loadMissing('peran');

        $role = $user?->peran?->kode_peran;
        $isAdmin = in_array($role, ['SUPER_ADMIN', 'ADMIN'], true);

        $tab = $request->query('tab', 'pengajuan');

        if (!in_array($tab, ['pengajuan', 'dipinjam', 'dikembalikan', 'ditolak'], true)) {
            $tab = 'pengajuan';
        }

        $query = Peminjaman::with([
            'user.karyawan.unit',
            'user.karyawan.subunitOrganisasi',
            'aset',
            'ruangan',
        ]);

        // Karyawan dan TU hanya melihat pengajuan miliknya sendiri.
        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        if ($tab === 'pengajuan') {
            $query->where('status', 'PENDING');
        } elseif ($tab === 'ditolak') {
            $query->where('status', 'DITOLAK');
        } elseif ($tab === 'dipinjam') {
            $query->where('status', 'DISETUJUI')
                ->where(function ($q) {
                    $q->whereNull('status_pengembalian')
                        ->orWhere('status_pengembalian', 'BELUM_DIKEMBALIKAN');
                });
        } else {
            $query->where('status', 'DISETUJUI')
                ->where('status_pengembalian', 'DIKEMBALIKAN');
        }

        $peminjaman = $query->latest()->get();

        return view('peminjaman.admin-index', compact(
            'peminjaman',
            'tab',
            'isAdmin'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | KATALOG ASET
    |--------------------------------------------------------------------------
    */

    public function aset(Request $request)
    {
        $jadwal = $this->jadwalDariRequest($request);
        $search = $request->query('search');

        $query = Aset::where('status', 'TERSEDIA')
            ->where('jumlah', '>', 0);

        // Pencarian tidak membedakan huruf besar/kecil (PostgreSQL membedakannya kalau pakai LIKE biasa)
        if ($search) {
            $kata = '%' . mb_strtolower($search) . '%';
            $query->where(function ($q) use ($kata) {
                $q->whereRaw('LOWER(nama_aset) LIKE ?', [$kata])
                  ->orWhereRaw('LOWER(deskripsi) LIKE ?', [$kata]);
            });
        }

        $aset = $query->orderBy('nama_aset')->get();

        foreach ($aset as $item) {
            $terpakai = (int) $this->queryAktif('aset_id', $item->id, $jadwal)
                ->sum('jumlah');

            $item->stok_tersedia = max(0, (int) $item->jumlah - $terpakai);
        }

        return view('peminjaman.aset', compact('aset', 'search'));
    }

    public function createAset($id)
    {
        $aset = Aset::where('status', 'TERSEDIA')
            ->where('jumlah', '>', 0)
            ->findOrFail($id);

        return view('peminjaman.aset-create', compact('aset'));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PENGAJUAN ASET
    |--------------------------------------------------------------------------
    */

    public function storeAset(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_selesai' => 'required|date_format:H:i',
            'jumlah_pinjam' => 'required|integer|min:1',
            'keperluan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'syarat_ketentuan' => 'required|accepted',
        ], [
            'syarat_ketentuan.accepted' => 'Anda harus menyetujui syarat dan ketentuan peminjaman.',
        ]);

        $this->validatePeriod($validated);

        DB::transaction(function () use ($id, $validated) {
            $aset = Aset::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($aset->status !== 'TERSEDIA' || $aset->jumlah < 1) {
                throw ValidationException::withMessages([
                    'jumlah_pinjam' => 'Aset sedang tidak tersedia.',
                ]);
            }

            $this->assertAssetStock(
                $aset,
                $validated['tanggal_mulai'],
                $validated['jam_mulai'],
                $validated['tanggal_selesai'],
                $validated['jam_selesai'],
                (int) $validated['jumlah_pinjam']
            );

            Peminjaman::create([
                'user_id' => Auth::id(),
                'no_hp' => $validated['no_hp'],
                'aset_id' => $aset->id,
                'ruangan_id' => null,
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'jam_mulai' => $validated['jam_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'jam_selesai' => $validated['jam_selesai'],
                'keperluan' => $validated['keperluan'],
                'jumlah' => (int) $validated['jumlah_pinjam'],
                'status' => 'PENDING',
                'status_pengembalian' => 'BELUM_DIKEMBALIKAN',
            ]);
        }, 3);

        $namaAset = Aset::find($id)?->nama_aset ?? 'aset';
        Notifikasi::kirimKeAdmin(
            'Pengajuan peminjaman aset baru',
            Auth::user()->name . ' mengajukan peminjaman ' . $namaAset . ' (' . $validated['jumlah_pinjam'] . ' unit).',
            route('admin.dashboard'),
            'info'
        );

        return redirect()
            ->route('peminjaman.aset')
            ->with('success', 'Pengajuan aset berhasil dikirim dan menunggu persetujuan.');
    }

    /*
    |--------------------------------------------------------------------------
    | KATALOG RUANGAN
    |--------------------------------------------------------------------------
    */

    public function ruangan(Request $request)
    {
        $jadwal = $this->jadwalDariRequest($request);

        $ruangan = Ruangan::where('status', 'TERSEDIA')
            ->orderBy('nama_ruangan')
            ->get();

        foreach ($ruangan as $item) {
            $item->tersedia = ! $this->queryAktif('ruangan_id', $item->id, $jadwal)
                ->exists();
        }

        return view('peminjaman.ruangan', compact('ruangan'));
    }

    public function createRuangan($id)
    {
        $ruangan = Ruangan::where('status', 'TERSEDIA')
            ->findOrFail($id);

        return view('peminjaman.ruangan-create', compact('ruangan'));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PENGAJUAN RUANGAN
    |--------------------------------------------------------------------------
    */

    public function storeRuangan(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_selesai' => 'required|date_format:H:i',
            'keperluan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'syarat_ketentuan' => 'required|accepted',
        ], [
            'syarat_ketentuan.accepted' => 'Anda harus menyetujui syarat dan ketentuan peminjaman.',
        ]);

        $this->validatePeriod($validated);

        DB::transaction(function () use ($id, $validated) {
            $ruangan = Ruangan::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($ruangan->status !== 'TERSEDIA') {
                throw ValidationException::withMessages([
                    'tanggal_mulai' => 'Ruangan sedang tidak tersedia.',
                ]);
            }

            $this->assertRoomAvailable(
                $ruangan,
                $validated['tanggal_mulai'],
                $validated['jam_mulai'],
                $validated['tanggal_selesai'],
                $validated['jam_selesai']
            );

            Peminjaman::create([
                'user_id' => Auth::id(),
                'no_hp' => $validated['no_hp'],
                'aset_id' => null,
                'ruangan_id' => $ruangan->id,
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'jam_mulai' => $validated['jam_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'jam_selesai' => $validated['jam_selesai'],
                'keperluan' => $validated['keperluan'],
                'jumlah' => 1,
                'status' => 'PENDING',
                'status_pengembalian' => 'BELUM_DIKEMBALIKAN',
            ]);
        }, 3);

        $namaRuangan = Ruangan::find($id)?->nama_ruangan ?? 'ruangan';
        Notifikasi::kirimKeAdmin(
            'Pengajuan peminjaman ruangan baru',
            Auth::user()->name . ' mengajukan peminjaman ' . $namaRuangan . '.',
            route('admin.dashboard'),
            'info'
        );

        return redirect()
            ->route('peminjaman.ruangan')
            ->with('success', 'Pengajuan ruangan berhasil dikirim dan menunggu persetujuan.');
    }

    /*
    |--------------------------------------------------------------------------
    | SETUJUI ATAU TOLAK PENGAJUAN
    |--------------------------------------------------------------------------
    */

    public function updateStatus(Request $request, $id)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status' => 'required|in:DISETUJUI,DITOLAK',
            'alasan_penolakan' => 'required_if:status,DITOLAK|nullable|string|max:255',
        ], [
            'alasan_penolakan.required_if' => 'Alasan penolakan wajib diisi jika pengajuan ditolak.',
        ]);

        $pesan = DB::transaction(function () use ($id, $validated) {
            $peminjaman = Peminjaman::lockForUpdate()->findOrFail($id);

            if ($peminjaman->status !== 'PENDING') {
                throw ValidationException::withMessages([
                    'status' => 'Pengajuan ini sudah pernah diproses.',
                ]);
            }

            if ($validated['status'] === 'DISETUJUI') {
                if ($peminjaman->aset_id) {
                    $aset = Aset::whereKey($peminjaman->aset_id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($aset->status !== 'TERSEDIA') {
                        throw ValidationException::withMessages([
                            'status' => 'Aset sedang tidak tersedia.',
                        ]);
                    }

                    $this->assertAssetStock(
                        $aset,
                        $peminjaman->tanggal_mulai,
                        $peminjaman->jam_mulai,
                        $peminjaman->tanggal_selesai,
                        $peminjaman->jam_selesai,
                        (int) $peminjaman->jumlah,
                        $peminjaman->id
                    );
                }

                if ($peminjaman->ruangan_id) {
                    $ruangan = Ruangan::whereKey($peminjaman->ruangan_id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($ruangan->status !== 'TERSEDIA') {
                        throw ValidationException::withMessages([
                            'status' => 'Ruangan sedang tidak tersedia.',
                        ]);
                    }

                    $this->assertRoomAvailable(
                        $ruangan,
                        $peminjaman->tanggal_mulai,
                        $peminjaman->jam_mulai,
                        $peminjaman->tanggal_selesai,
                        $peminjaman->jam_selesai,
                        $peminjaman->id
                    );
                }
            }

            $peminjaman->update([
                'status' => $validated['status'],
                'alasan_penolakan' => $validated['status'] === 'DITOLAK' ? $validated['alasan_penolakan'] : null,
            ]);

            return $validated['status'] === 'DISETUJUI'
                ? 'Pengajuan peminjaman berhasil disetujui.'
                : 'Pengajuan peminjaman berhasil ditolak.';
        }, 3);

        $p = Peminjaman::with(['aset', 'ruangan'])->find($id);
        $nama = $p?->aset?->nama_aset ?? $p?->ruangan?->nama_ruangan ?? 'peminjaman';

        if ($validated['status'] === 'DISETUJUI') {
            Notifikasi::kirim($p->user_id, 'Peminjaman disetujui', "Pengajuan peminjaman {$nama} Anda telah disetujui.", route('peminjaman.kelola', ['tab' => 'dipinjam']), 'sukses');
        } else {
            Notifikasi::kirim($p->user_id, 'Peminjaman ditolak', "Pengajuan {$nama} ditolak. Alasan: " . $validated['alasan_penolakan'], route('peminjaman.kelola', ['tab' => 'ditolak']), 'tolak');
        }

        return redirect()
            ->route('peminjaman.kelola', [
                'tab' => $validated['status'] === 'DISETUJUI' ? 'dipinjam' : 'ditolak',
            ])
            ->with('success', $pesan);
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES PENGEMBALIAN
    |--------------------------------------------------------------------------
    */

    public function markReturned($id)
    {
        $this->authorizeAdmin();

        DB::transaction(function () use ($id) {
            $peminjaman = Peminjaman::lockForUpdate()
                ->findOrFail($id);

            if ($peminjaman->status !== 'DISETUJUI') {
                throw ValidationException::withMessages([
                    'status' => 'Hanya peminjaman yang disetujui yang bisa dikembalikan.',
                ]);
            }

            if ($peminjaman->status_pengembalian === 'DIKEMBALIKAN') {
                throw ValidationException::withMessages([
                    'status' => 'Peminjaman ini sudah tercatat dikembalikan.',
                ]);
            }

            $peminjaman->update([
                'status_pengembalian' => 'DIKEMBALIKAN',
            ]);
        }, 3);

        $p = Peminjaman::with(['aset', 'ruangan'])->find($id);

        Notifikasi::kirim(
            $p->user_id,
            'Pengembalian dicatat',
            'Pengembalian ' . ($p->aset?->nama_aset ?? $p->ruangan?->nama_ruangan) . ' telah dicatat. Terima kasih.',
            route('peminjaman.kelola', ['tab' => 'dikembalikan']),
            'sukses'
        );

        return redirect()
            ->route('peminjaman.kelola', ['tab' => 'dikembalikan'])
            ->with('success', 'Pengembalian berhasil dicatat.');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER KETERSEDIAAN
    |--------------------------------------------------------------------------
    */

    private function jadwalDariRequest(Request $request): ?array
    {
        $tanggalMulai = $request->query('tanggal_mulai');
        $jamMulai = $request->query('jam_mulai');
        $tanggalSelesai = $request->query('tanggal_selesai');
        $jamSelesai = $request->query('jam_selesai');

        if ($tanggalMulai && $jamMulai && $tanggalSelesai && $jamSelesai) {
            try {
                return [
                    $this->waktu("{$tanggalMulai} {$jamMulai}"),
                    $this->waktu("{$tanggalSelesai} {$jamSelesai}"),
                ];
            } catch (\Throwable $e) {
                return null; // format tanggal/jam di URL tidak valid -> abaikan filter
            }
        }

        return null;
    }

    private function queryAktif(
        string $kolom,
        int $id,
        ?array $jadwal = null,
        ?int $excludeId = null
    ) {
        $query = Peminjaman::where($kolom, $id)
            ->whereIn('status', ['PENDING', 'DISETUJUI'])
            ->where(function ($q) {
                $q->whereNull('status_pengembalian')
                    ->orWhere('status_pengembalian', '!=', 'DIKEMBALIKAN');
            });

        if ($jadwal) {
            [$mulai, $selesai] = $jadwal;

            // Bentrok jika: mulai_lama < selesai_baru DAN selesai_lama > mulai_baru
            $query->whereRaw(self::MULAI . ' < ?', [$this->waktu($selesai)])
                  ->whereRaw(self::SELESAI . ' > ?', [$this->waktu($mulai)]);
        } else {
            $query->whereRaw(self::SELESAI . ' > ?', [$this->waktu(now())]);
        }

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query;
    }

    private function assertAssetStock(
        Aset $aset,
        string $tanggalMulai,
        string $jamMulai,
        string $tanggalSelesai,
        string $jamSelesai,
        int $jumlahDiminta,
        ?int $excludeId = null
    ): void {
        $stokTerpakai = (int) $this->queryAktif(
            'aset_id',
            $aset->id,
            [$this->gabung($tanggalMulai, $jamMulai), $this->gabung($tanggalSelesai, $jamSelesai)],
            $excludeId
        )->sum('jumlah');

        $stokTersedia = (int) $aset->jumlah - $stokTerpakai;

        if ($jumlahDiminta > $stokTersedia) {
            throw ValidationException::withMessages([
                'jumlah_pinjam' =>
                    "Stok {$aset->nama_aset} tidak mencukupi untuk jadwal tersebut. "
                    . "Stok total: {$aset->jumlah}, stok tersedia: "
                    . max(0, $stokTersedia) . '.',
            ]);
        }
    }

    private function assertRoomAvailable(
        Ruangan $ruangan,
        string $tanggalMulai,
        string $jamMulai,
        string $tanggalSelesai,
        string $jamSelesai,
        ?int $excludeId = null
    ): void {
        $bentrok = $this->queryAktif(
            'ruangan_id',
            $ruangan->id,
            [$this->gabung($tanggalMulai, $jamMulai), $this->gabung($tanggalSelesai, $jamSelesai)],
            $excludeId
        )->exists();

        if ($bentrok) {
            throw ValidationException::withMessages([
                'tanggal_mulai' =>
                    "Ruangan {$ruangan->nama_ruangan} sudah dipesan pada jadwal tersebut.",
            ]);
        }
    }

    /**
     * Gabungkan tanggal + jam menjadi "Y-m-d H:i:s".
     * Aman walau tanggal dari database berbentuk "2026-11-08 00:00:00" (cast date).
     */
    private function gabung(string $tanggal, string $jam): string
    {
        return Carbon::parse($tanggal)->format('Y-m-d') . ' ' . Carbon::parse($jam)->format('H:i:s');
    }

    private function validatePeriod(array $data): void
    {
        $mulai = strtotime($data['tanggal_mulai'] . ' ' . $data['jam_mulai']);
        $selesai = strtotime($data['tanggal_selesai'] . ' ' . $data['jam_selesai']);

        if ($selesai <= $mulai) {
            throw ValidationException::withMessages([
                'jam_selesai' => 'Waktu selesai harus setelah waktu mulai.',
            ]);
        }
    }

    private function authorizeAdmin(): void
    {
        $user = Auth::user();
        $user?->loadMissing('peran');

        abort_unless(
            in_array($user?->peran?->kode_peran, ['SUPER_ADMIN', 'ADMIN'], true),
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | JSON KALENDER RUANGAN
    |--------------------------------------------------------------------------
    */

    public function jadwalKalenderJson(Request $request)
    {
        try {
            $bulan = Carbon::createFromFormat('Y-m', $request->get('bulan', now()->format('Y-m')))->startOfMonth();
        } catch (\Throwable $e) {
            $bulan = now()->startOfMonth();
        }

        $awal  = $bulan->copy()->startOfWeek()->toDateTimeString();
        $akhir = $bulan->copy()->endOfMonth()->endOfWeek()->toDateTimeString();

        $data = Peminjaman::with(['ruangan', 'user.karyawan.unit'])
            ->whereNotNull('ruangan_id')
            ->whereIn('status', ['PENDING', 'DISETUJUI'])
            ->where(function ($q) {
                $q->whereNull('status_pengembalian')
                    ->orWhere('status_pengembalian', '!=', 'DIKEMBALIKAN');
            })
            ->whereRaw(self::MULAI . ' <= ?', [$akhir])
            ->whereRaw(self::SELESAI . ' >= ?', [$awal])
            ->orderBy('tanggal_mulai')
            ->orderBy('jam_mulai')
            ->get();

        return response()->json([
            'events' => $data->map(function ($p) {
                $tglMulai   = Carbon::parse($p->tanggal_mulai)->format('Y-m-d');
                $tglSelesai = Carbon::parse($p->tanggal_selesai)->format('Y-m-d');

                return [
                    'id'       => $p->id,
                    'ruangan'  => $p->ruangan?->nama_ruangan ?? '-',
                    'judul'    => $p->keperluan ?? 'Peminjaman ruangan',
                    'peminjam' => $p->user?->karyawan?->nama ?? $p->user?->name ?? '-',
                    'unit'     => $p->user?->karyawan?->unit?->nama_unit ?? '-',
                    'mulai'    => $tglMulai . 'T' . substr($p->jam_mulai, 0, 5),
                    'selesai'  => $tglSelesai . 'T' . substr($p->jam_selesai, 0, 5),
                    'status'   => $p->status,
                ];
            })->values(),
        ]);
    }
}