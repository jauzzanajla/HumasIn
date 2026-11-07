<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DukunganKegiatan;
use App\Models\PengingatKalender; // Tambahkan model ini
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class KaryawanDashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->loadMissing(['karyawan.unit', 'karyawan.subunitOrganisasi', 'peran']);
        $sekarang = now();

        /* ---------- Peminjaman saya ---------- */
        $peminjaman = Peminjaman::with(['aset', 'ruangan'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $aktif = $peminjaman->filter(fn ($p) =>
            $p->status === 'DISETUJUI' && $p->status_pengembalian !== 'DIKEMBALIKAN'
        )->map(function ($p) use ($sekarang) {
            $batas = Carbon::parse($p->tanggal_selesai . ' ' . $p->jam_selesai);
            return [
                'nama'      => $p->aset?->nama_aset ?? $p->ruangan?->nama_ruangan ?? 'Peminjaman',
                'jenis'     => $p->ruangan_id ? 'Ruangan' : 'Aset',
                'jumlah'    => $p->ruangan_id ? null : $p->jumlah,
                'batas'     => $batas->translatedFormat('d M Y') . ' ' . $batas->format('H:i'),
                'terlambat' => $batas->lt($sekarang),
            ];
        })->values();

        /* ---------- Dukungan kegiatan saya ---------- */
        $dukungan = DukunganKegiatan::where('user_id', $user->id)
            ->latest()
            ->get();

        /* ---------- Gabungan pengajuan (terbaru dulu) ---------- */
        $pengajuan = collect();

        foreach ($peminjaman as $p) {
            [$label, $kelas] = match (true) {
                $p->status === 'PENDING'                            => ['Menunggu', 'b-blue'],
                $p->status === 'DITOLAK'                            => ['Ditolak', 'b-red'],
                $p->status_pengembalian === 'DIKEMBALIKAN'          => ['Dikembalikan', 'b-gray'],
                default                                             => ['Disetujui', 'b-green'],
            };

            $pengajuan->push([
                'waktu'   => $p->created_at,
                'jenis'   => $p->ruangan_id ? 'Ruangan' : 'Aset',
                'judul'   => $p->aset?->nama_aset ?? $p->ruangan?->nama_ruangan ?? 'Peminjaman',
                'tanggal' => Carbon::parse($p->tanggal_mulai)->translatedFormat('d M Y'),
                'label'   => $label,
                'kelas'   => $kelas,
                'alasan'  => $p->status === 'DITOLAK' ? $p->alasan_penolakan : null,
            ]);
        }

        foreach ($dukungan as $d) {
            [$label, $kelas] = match ($d->status) {
                'SELESAI'                   => ['Selesai', 'b-green'],
                'DIIDELEGASIKAN', 'DIPROSES' => ['Diproses', 'b-amber'],
                'DITOLAK'                   => ['Ditolak', 'b-red'],
                default                     => ['Menunggu', 'b-blue'],
            };

            $pengajuan->push([
                'waktu'   => $d->created_at,
                'jenis'   => 'Dukungan Kegiatan',
                'judul'   => $d->nama_event,
                'tanggal' => Carbon::parse($d->tanggal_pelaksanaan)->translatedFormat('d M Y'),
                'label'   => $label,
                'kelas'   => $kelas,
                'alasan'  => null,
            ]);
        }

        $pengajuan = $pengajuan->sortByDesc('waktu')->values()->take(8);

        /* ---------- Statistik ---------- */
        $stat = [
            'menunggu' => $peminjaman->where('status', 'PENDING')->count()
                        + $dukungan->whereNotIn('status', ['DIIDELEGASIKAN', 'DIPROSES', 'SELESAI', 'DITOLAK'])->count(),
            'dipinjam' => $aktif->count(),
            'terlambat' => $aktif->where('terlambat', true)->count(),
            'ditolak'  => $peminjaman->where('status', 'DITOLAK')->count()
                        + $dukungan->where('status', 'DITOLAK')->count(),
        ];

        /* ---------- Jadwal ruangan minggu ini ---------- */
        $awal  = $sekarang->copy()->startOfWeek();
        $akhir = $sekarang->copy()->endOfWeek();

        $bookingRuangan = Peminjaman::with(['ruangan', 'user.karyawan'])
            ->whereNotNull('ruangan_id')
            ->whereIn('status', ['PENDING', 'DISETUJUI'])
            ->where(function ($q) {
                $q->whereNull('status_pengembalian')->orWhere('status_pengembalian', '!=', 'DIKEMBALIKAN');
            })
            ->whereRaw('TIMESTAMP(tanggal_mulai, jam_mulai) <= ?', [$akhir->toDateTimeString()])
            ->whereRaw('TIMESTAMP(tanggal_selesai, jam_selesai) >= ?', [$awal->toDateTimeString()])
            ->orderBy('tanggal_mulai')->orderBy('jam_mulai')
            ->get();

        $jadwalMinggu = [];
        for ($i = 0; $i < 7; $i++) {
            $hari = $awal->copy()->addDays($i);
            $tgl  = $hari->toDateString();

            $items = $bookingRuangan->filter(fn ($b) =>
                Carbon::parse($b->tanggal_mulai)->toDateString() <= $tgl && Carbon::parse($b->tanggal_selesai)->toDateString() >= $tgl
            )->map(fn ($b) => [
                'ruangan'  => $b->ruangan?->nama_ruangan ?? '-',
                'jam'      => substr($b->jam_mulai, 0, 5) . ' – ' . substr($b->jam_selesai, 0, 5),
                'peminjam' => $b->user?->karyawan?->nama ?? $b->user?->name ?? '-',
                'pending'  => $b->status === 'PENDING',
            ])->values();

            $jadwalMinggu[] = [
                'label' => $hari->translatedFormat('l, d M'),
                'today' => $hari->isToday(),
                'items' => $items,
            ];
        }

        /* ---------- Event mendatang ---------- */
        $eventMendatang = DukunganKegiatan::whereDate('tanggal_pelaksanaan', '>=', now()->toDateString())
            ->where('status', '!=', 'DITOLAK')
            ->orderBy('tanggal_pelaksanaan')
            ->limit(5)
            ->get();

        /* ---------- Kalender Event Grid Interaktif (Gabungan Dukungan & Pengingat) ---------- */
        $eventDukungan = DukunganKegiatan::with('user')
            ->whereBetween('tanggal_pelaksanaan', [now()->subMonths(3)->toDateString(), now()->addMonths(9)->toDateString()])
            ->get()
            ->map(fn ($e) => [
                'id'       => 'dukungan-' . $e->id,
                'nama'     => $e->nama_event,
                'tanggal'  => Carbon::parse($e->tanggal_pelaksanaan)->format('Y-m-d'),
                'warna'    => strtolower($e->kategori_warna ?? 'hijau'),
                'status'   => $e->status,
                'pemohon'  => $e->user?->name ?? '-',
                'lokasi'   => $e->lokasi ?? null,
            ]);

        $eventPengingat = PengingatKalender::all()
            ->map(fn ($p) => [
                'id'       => 'pengingat-' . $p->id,
                'nama'     => $p->judul,
                'tanggal'  => Carbon::parse($p->tanggal)->format('Y-m-d'),
                'warna'    => ltrim($p->warna, '#') === '6EAE78' ? 'hijau' : 'biru',
                'status'   => 'PENGINGAT',
                'pemohon'  => $p->pembuat?->name ?? 'Admin',
                'lokasi'   => $p->keterangan ?? null,
            ]);

        $kalenderEvent = $eventDukungan->concat($eventPengingat)->sortBy('tanggal')->values();

        return view('karyawan.dashboard', compact(
            'user', 'stat', 'pengajuan', 'aktif', 'jadwalMinggu', 'eventMendatang', 'kalenderEvent'
        ));
    }
}