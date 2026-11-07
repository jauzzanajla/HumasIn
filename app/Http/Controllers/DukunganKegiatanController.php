<?php

namespace App\Http\Controllers;

use App\Models\AdminStatus;
use App\Models\DukunganKegiatan;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class DukunganKegiatanController extends Controller
{
    /**
     * Peran yang boleh MENDELEGASIKAN (memilih PIC).
     */
    private const ROLE_DELEGASI = ['SUPER_ADMIN'];

    /**
     * Peran yang boleh mengubah STATUS dan KATEGORI WARNA event.
     * Ubah menjadi ['SUPER_ADMIN'] jika seluruh kolom "Aksi / Delegasi"
     * ingin dibatasi hanya untuk Super Admin.
     */
    private const ROLE_KELOLA = ['SUPER_ADMIN', 'ADMIN'];

    // Halaman daftar pengajuan (untuk TU melihat miliknya, atau Admin melihat semua)
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user instanceof User) {
            $user->loadMissing('peran');
        }

        $role = $user?->peran?->kode_peran;
        $isAdmin = in_array($role, ['SUPER_ADMIN', 'ADMIN'], true);
        $bisaDelegasi = in_array($role, self::ROLE_DELEGASI, true);
        $bisaKelolaStatus = in_array($role, self::ROLE_KELOLA, true);

        $query = DukunganKegiatan::with(['user.karyawan', 'admin']);

        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        $semuaDukungans = $query->latest()->get();

        // Pisahkan data menjadi 2 kategori untuk sistem Tab
        $permintaanMasuk = $semuaDukungans->whereIn('status', ['PENDING', 'DIIDELEGASIKAN', 'DIPROSES', 'DITOLAK']);
        $permintaanSelesai = $semuaDukungans->where('status', 'SELESAI');

        // Ambil bulan dan tahun dari request, default ke bulan & tahun sekarang
        $bulanAktif = $request->input('bulan', now()->month);
        $tahunAktif = $request->input('tahun', now()->year);

        // Ambil semua event untuk kalender
        $semuaEvent = DukunganKegiatan::with(['user.karyawan'])
            ->whereIn('status', ['DIIDELEGASIKAN', 'DIPROSES', 'SELESAI'])
            ->get();

        // Daftar PIC dan status ketersediaannya hanya dibutuhkan oleh yang boleh mendelegasikan.
        $daftarAdmin = [];
        $statusAdmin = collect();

        if ($bisaDelegasi) {
            $daftarAdmin = User::whereHas('peran', function ($q) {
                $q->whereIn('kode_peran', ['SUPER_ADMIN', 'ADMIN']);
            })->get();

            // Peta [user_id => status_ketersediaan].
            // Admin yang belum punya baris dianggap TERSEDIA.
            $statusAdmin = AdminStatus::pluck('status_ketersediaan', 'user_id');
        }

        return view('dukungan.index', compact(
            'permintaanMasuk',
            'permintaanSelesai',
            'isAdmin',
            'bisaDelegasi',
            'bisaKelolaStatus',
            'daftarAdmin',
            'statusAdmin',
            'semuaEvent',
            'bulanAktif',
            'tahunAktif'
        ));
    }

    // Form pengajuan oleh TU Eselon II
    public function create()
    {
        return view('dukungan.create');
    }

    // Simpan data pengajuan event
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'required|date',
            'hari' => 'required|string|max:50',
            'bulan' => 'required|string|max:50',
            'tahun' => 'required|integer',
            'jam_pelaksanaan' => 'required',
            'lokasi' => 'required|string|max:255',
            'pejabat_hadir' => 'nullable|string|max:255',
            'jumlah_peserta' => 'nullable|integer',
        ]);

        DukunganKegiatan::create([
            'user_id' => Auth::id(),
            'nama_event' => $validated['nama_event'],
            'tanggal_pelaksanaan' => $validated['tanggal_pelaksanaan'],
            'hari' => $validated['hari'],
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
            'jam_pelaksanaan' => $validated['jam_pelaksanaan'],
            'lokasi' => $validated['lokasi'],
            'pejabat_hadir' => $validated['pejabat_hadir'],
            'jumlah_peserta' => $validated['jumlah_peserta'],

            'support_operator' => $request->has('support_operator'),
            'support_desain_grafis' => $request->has('support_desain_grafis'),
            'detail_desain_grafis' => $request->input('detail_desain_grafis'),
            'support_editing' => $request->has('support_editing'),
            'detail_editing_platform' => $request->input('detail_editing_platform'),
            'support_konten_web' => $request->has('support_konten_web'),
            'support_merchandise' => $request->has('support_merchandise'),
            'detail_merchandise' => $request->input('detail_merchandise'),
            'support_dokumentasi' => $request->has('support_dokumentasi'),
            'support_tim_acara' => $request->has('support_tim_acara'),
            'status' => 'PENDING',
        ]);
        Notifikasi::kirimKeAdmin(
    'Permohonan dukungan kegiatan baru',
    Auth::user()->name . ' mengajukan dukungan untuk "' . $validated['nama_event'] . '".',
    route('dukungan.index'), 'info'
);

        return redirect()->route('dukungan.index')
            ->with('success', 'Permintaan dukungan kegiatan berhasil diajukan.');
    }

    // Update status, kategori warna, atau delegasi PIC
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();

        if ($user instanceof User) {
            $user->loadMissing('peran');
        }

        $role = $user?->peran?->kode_peran;

        // Pengecekan peran di server (sebelumnya tidak ada sama sekali).
        abort_unless(
            in_array($role, self::ROLE_KELOLA, true),
            403,
            'Anda tidak memiliki akses ke aksi ini.'
        );

        $bisaDelegasi = in_array($role, self::ROLE_DELEGASI, true);
        $dukungan = DukunganKegiatan::findOrFail($id);
        $statusLama = $dukungan->status;
        $adminLama = $dukungan->admin_ids ?? [];

        $validated = $request->validate([
            'status' => 'required|in:PENDING,DIIDELEGASIKAN,DIPROSES,SELESAI,DITOLAK',
            'admin_ids' => 'nullable|array',
            'admin_ids.*' => 'exists:users,id',
            'kategori_warna' => 'required|in:hijau,oranye,biru,coklat',
        ]);

        if ($bisaDelegasi) {
            $adminIds = $validated['admin_ids'] ?? [];

            // PIC yang BARU ditambahkan tidak boleh sedang Sibuk / Tugas Luar.
            // PIC yang sudah tertugas sebelumnya tidak diperiksa supaya menyimpan
            // form tidak gagal hanya karena status mereka berubah belakangan.
            $adminBaru = array_diff($adminIds, $dukungan->admin_ids ?? []);

            if (!empty($adminBaru)) {
                $tidakTersedia = AdminStatus::with('user')
                    ->whereIn('user_id', $adminBaru)
                    ->whereIn('status_ketersediaan', ['SIBUK', 'TUGAS_LUAR'])
                    ->get();

                if ($tidakTersedia->isNotEmpty()) {
                    $nama = $tidakTersedia
                        ->map(fn ($s) => $s->user?->name)
                        ->filter()
                        ->implode(', ');

                    return redirect()->back()
                        ->with('error', "Tidak bisa menugaskan {$nama}: sedang sibuk atau tugas luar.");
                }
            }
        } else {
            // Peran yang tidak boleh mendelegasikan: abaikan input admin_ids
            // dan pertahankan PIC yang sudah ada.
            $adminIds = $dukungan->admin_ids ?? [];
        }

        $dukungan->update([
            'status' => $validated['status'],
            'admin_ids' => $adminIds,
            'kategori_warna' => $validated['kategori_warna'],
        ]);
        // Kabari pemohon kalau status berubah
if ($statusLama !== $validated['status']) {
    $label = [
        'PENDING'        => 'Menunggu',
        'DIIDELEGASIKAN' => 'Sudah didelegasikan ke tim',
        'DIPROSES'       => 'Sedang diproses',
        'SELESAI'        => 'Selesai',
        'DITOLAK'        => 'Ditolak',
    ][$validated['status']];

    Notifikasi::kirim(
        $dukungan->user_id,
        'Status dukungan kegiatan diperbarui',
        "Event \"{$dukungan->nama_event}\" kini berstatus: {$label}.",
        route('dukungan.index'),
        $validated['status'] === 'DITOLAK' ? 'tolak' : ($validated['status'] === 'SELESAI' ? 'sukses' : 'info')
    );
}

// Kabari PIC yang baru ditugaskan
$picBaru = array_diff($adminIds, $adminLama);
if (!empty($picBaru)) {
    Notifikasi::kirim(
        $picBaru,
        'Anda ditugaskan pada sebuah event',
        "Anda ditunjuk sebagai PIC untuk event \"{$dukungan->nama_event}\" pada " . \Carbon\Carbon::parse($dukungan->tanggal_pelaksanaan)->translatedFormat('d M Y') . '.',
        route('dukungan.index'),
        'peringatan'
    );
}

        return redirect()->back()
            ->with('success', 'Status, delegasi, dan kategori warna event berhasil diperbarui.');
    }
}