<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Ruangan;
use App\Models\Peminjaman;
use App\Models\DukunganKegiatan;
use App\Models\PengingatKalender; // <-- Pastikan model ini di-import
use App\Models\AdminStatus;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $sekarang = now()->toDateTimeString();

        // Peminjaman yang masih aktif (belum dikembalikan)
        $aktif = fn () => Peminjaman::where('status', 'DISETUJUI')
            ->where(function ($q) {
                $q->whereNull('status_pengembalian')
                  ->orWhere('status_pengembalian', '!=', 'DIKEMBALIKAN');
            });

        /* ---------- Antrean tindakan ---------- */
        $antreanPeminjaman = Peminjaman::with(['user.karyawan.unit', 'aset', 'ruangan'])
            ->where('status', 'PENDING')
            ->orderBy('tanggal_mulai')->orderBy('jam_mulai')
            ->limit(8)->get();

        $statusDukunganSelesai = ['DIIDELEGASIKAN', 'DIPROSES', 'SELESAI', 'DITOLAK'];
        $antreanDukungan = DukunganKegiatan::with('user')
            ->whereNotIn('status', $statusDukunganSelesai)
            ->orderBy('tanggal_pelaksanaan')
            ->limit(8)->get();

        $totalPeminjamanMenunggu = Peminjaman::where('status', 'PENDING')->count();
        $totalDukunganMenunggu   = DukunganKegiatan::whereNotIn('status', $statusDukunganSelesai)->count();

        /* ---------- Pengembalian terlambat / jatuh tempo ---------- */
        $terlambat = $aktif()->with(['user.karyawan.unit', 'aset', 'ruangan'])
            ->whereRaw('TIMESTAMP(tanggal_selesai, jam_selesai) < ?', [$sekarang])
            ->orderBy('tanggal_selesai')->limit(6)->get();

        $jatuhTempo = $aktif()->with(['user.karyawan.unit', 'aset', 'ruangan'])
            ->whereRaw('TIMESTAMP(tanggal_selesai, jam_selesai) >= ?', [$sekarang])
            ->whereRaw('TIMESTAMP(tanggal_selesai, jam_selesai) <= ?', [now()->addDay()->toDateTimeString()])
            ->orderBy('tanggal_selesai')->limit(6)->get();

        /* ---------- Kalender event (Gabungan Dukungan Kegiatan & Pengingat Kalender) ---------- */
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

        $eventPengingat = PengingatKalender::with('pembuat')
            ->get()
            ->map(fn ($p) => [
                'id'       => 'pengingat-' . $p->id,
                'nama'     => $p->judul,
                'tanggal'  => Carbon::parse($p->tanggal)->format('Y-m-d'),
                'warna'    => strtolower($p->warna ?? 'hijau'),
                'status'   => 'PENGINGAT',
                'pemohon'  => $p->pembuat?->name ?? 'Admin',
                'lokasi'   => $p->keterangan ?? null,
            ]);

        $kalenderEvent = $eventDukungan->concat($eventPengingat)->sortBy('tanggal')->values();

        $eventMingguIni = $kalenderEvent
            ->filter(fn ($e) => $e['tanggal'] >= now()->toDateString() && $e['tanggal'] <= now()->addDays(7)->toDateString())
            ->count();

        /* ---------- Ketersediaan tim ---------- */
        $timStatus = AdminStatus::with('user.karyawan.unit')->get();
        $timTersedia = $timStatus->where('status_ketersediaan', 'TERSEDIA')->count();

        /* ---------- Ringkasan aset & ruangan ---------- */
        $ringkasan = [
            'aset_total'      => Aset::count(),
            'aset_perbaikan'  => Aset::where('status', 'PERBAIKAN')->count(),
            'aset_rusak'      => Aset::where('kondisi', 'RUSAK_BERAT')->count(),
            'ruangan_total'   => Ruangan::count(),
            'ruangan_dipakai' => $aktif()->whereNotNull('ruangan_id')
                ->whereRaw('TIMESTAMP(tanggal_mulai, jam_mulai) <= ?', [$sekarang])
                ->whereRaw('TIMESTAMP(tanggal_selesai, jam_selesai) >= ?', [$sekarang])
                ->count(),
            'aset_dipinjam'   => (int) $aktif()->whereNotNull('aset_id')->sum('jumlah'),
        ];

        return view('admin.dashboard', compact(
            'antreanPeminjaman', 'antreanDukungan',
            'totalPeminjamanMenunggu', 'totalDukunganMenunggu',
            'terlambat', 'jatuhTempo',
            'kalenderEvent', 'eventMingguIni',
            'timStatus', 'timTersedia',
            'ringkasan'
        ));
    }
}