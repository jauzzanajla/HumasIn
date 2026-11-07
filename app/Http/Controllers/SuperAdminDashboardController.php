<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Karyawan;
use App\Models\Peminjaman;
use App\Models\Peran;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\DukunganKegiatan;
use App\Models\PengingatKalender;
use Carbon\Carbon;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        /* ---------- Statistik utama ---------- */
        $totalKaryawan   = Karyawan::where('aktif', true)->count();
        $totalAkun       = User::count();
        $belumPunyaAkun  = Karyawan::where('aktif', true)->whereDoesntHave('user')->count();
        $akunTanpaRole   = User::whereNull('id_peran')->count();

        /* ---------- Akun per role ---------- */
        $jumlahPerRole = User::selectRaw('id_peran, COUNT(*) as total')
            ->groupBy('id_peran')->pluck('total', 'id_peran');

        $akunPerRole = Peran::orderBy('id')->get()->map(fn ($p) => [
            'nama'  => $p->nama_peran,
            'total' => (int) ($jumlahPerRole[$p->id] ?? 0),
        ]);
        $maksRole = max(1, (int) $akunPerRole->max('total'));

        /* ---------- Tren peminjaman 6 bulan ---------- */
        $awal = now()->subMonths(5)->startOfMonth();
        $semua = Peminjaman::whereDate('tanggal_mulai', '>=', $awal->toDateString())
            ->get(['aset_id', 'ruangan_id', 'tanggal_mulai']);

        $tren = [];
        for ($i = 0; $i < 6; $i++) {
            $bulan = $awal->copy()->addMonths($i);
            $dalam = $semua->filter(fn ($p) => Carbon::parse($p->tanggal_mulai)->format('Y-m') === $bulan->format('Y-m'));
            $tren[] = [
                'label'   => $bulan->translatedFormat('M'),
                'aset'    => $dalam->whereNotNull('aset_id')->count(),
                'ruangan' => $dalam->whereNotNull('ruangan_id')->count(),
            ];
        }
        $maksTren = max(1, collect($tren)->max(fn ($t) => $t['aset'] + $t['ruangan']));

        /* ---------- Paling sering dipinjam ---------- */
        $topAset = Peminjaman::selectRaw('aset_id, COUNT(*) as total')
            ->whereNotNull('aset_id')->where('status', 'DISETUJUI')
            ->groupBy('aset_id')->orderByDesc('total')->limit(5)->get();
        $namaAset = Aset::whereIn('id', $topAset->pluck('aset_id'))->pluck('nama_aset', 'id');
        $topAset = $topAset->map(fn ($r) => ['nama' => $namaAset[$r->aset_id] ?? '-', 'total' => (int) $r->total]);

        $topRuangan = Peminjaman::selectRaw('ruangan_id, COUNT(*) as total')
            ->whereNotNull('ruangan_id')->where('status', 'DISETUJUI')
            ->groupBy('ruangan_id')->orderByDesc('total')->limit(5)->get();
        $namaRuangan = Ruangan::whereIn('id', $topRuangan->pluck('ruangan_id'))->pluck('nama_ruangan', 'id');
        $topRuangan = $topRuangan->map(fn ($r) => ['nama' => $namaRuangan[$r->ruangan_id] ?? '-', 'total' => (int) $r->total]);

        /* ---------- Peringatan ---------- */
        $menggantung = Peminjaman::where('status', 'PENDING')
            ->where('created_at', '<', now()->subDays(3))->count();
        $asetRusak = Aset::where('kondisi', 'RUSAK_BERAT')->count();

        // CONCAT(tanggal, ' ', jam) jalan di MySQL (laptop) dan PostgreSQL (Supabase)
        $terlambat = Peminjaman::where('status', 'DISETUJUI')
            ->where(function ($q) {
                $q->whereNull('status_pengembalian')->orWhere('status_pengembalian', '!=', 'DIKEMBALIKAN');
            })
            ->whereRaw("CONCAT(tanggal_selesai, ' ', jam_selesai) < ?", [now()->toDateTimeString()])
            ->count();

        $peringatan = collect([
            ['teks' => "$menggantung pengajuan peminjaman menunggu lebih dari 3 hari", 'jumlah' => $menggantung, 'url' => route('peminjaman.kelola'), 'level' => 'amber'],
            ['teks' => "$terlambat pengembalian melewati batas waktu", 'jumlah' => $terlambat, 'url' => route('peminjaman.kelola', ['tab' => 'dipinjam']), 'level' => 'red'],
            ['teks' => "$asetRusak aset berstatus rusak berat", 'jumlah' => $asetRusak, 'url' => route('aset.index'), 'level' => 'red'],
            ['teks' => "$belumPunyaAkun karyawan aktif belum punya akun", 'jumlah' => $belumPunyaAkun, 'url' => route('users.create'), 'level' => 'blue'],
            ['teks' => "$akunTanpaRole akun belum punya role", 'jumlah' => $akunTanpaRole, 'url' => route('users.index'), 'level' => 'amber'],
        ])->filter(fn ($p) => $p['jumlah'] > 0)->values();

        /* ---------- Aktivitas terbaru (termasuk pengingat kalender) ---------- */
        $aktivitas = collect();

        Peminjaman::with(['user.karyawan', 'aset', 'ruangan'])->latest()->limit(8)->get()
            ->each(function ($p) use ($aktivitas) {
                $objek = $p->aset?->nama_aset ?? $p->ruangan?->nama_ruangan ?? 'peminjaman';
                $kata = match ($p->status) {
                    'DISETUJUI' => 'peminjaman disetujui',
                    'DITOLAK'   => 'peminjaman ditolak',
                    default     => 'mengajukan peminjaman',
                };
                $aktivitas->push([
                    'waktu' => $p->updated_at ?? $p->created_at,
                    'teks'  => ($p->user?->karyawan?->nama ?? $p->user?->name ?? '-') . ' · ' . $kata,
                    'objek' => $objek,
                ]);
            });

        DukunganKegiatan::with('user')->latest()->limit(5)->get()
            ->each(function ($d) use ($aktivitas) {
                $aktivitas->push([
                    'waktu' => $d->updated_at ?? $d->created_at,
                    'teks'  => ($d->user?->name ?? '-') . ' · dukungan kegiatan',
                    'objek' => $d->nama_event,
                ]);
            });

        PengingatKalender::with('pembuat')->latest()->limit(5)->get()
            ->each(function ($pk) use ($aktivitas) {
                $aktivitas->push([
                    'waktu' => $pk->updated_at ?? $pk->created_at,
                    'teks'  => ($pk->pembuat?->name ?? 'Admin') . ' · menambah pengingat',
                    'objek' => $pk->judul,
                ]);
            });

        $aktivitas = $aktivitas->sortByDesc('waktu')->take(8)->values();

        return view('superadmin.dashboard', compact(
            'totalKaryawan', 'totalAkun', 'belumPunyaAkun', 'akunTanpaRole',
            'akunPerRole', 'maksRole', 'tren', 'maksTren',
            'topAset', 'topRuangan', 'peringatan', 'aktivitas'
        ));
    }
}