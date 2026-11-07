<?php

namespace App\Http\Controllers;

use App\Models\DukunganKegiatan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user?->loadMissing('peran');
        $role = $user?->peran?->kode_peran;
        $isAdmin = in_array($role, ['SUPER_ADMIN', 'ADMIN'], true);

        // Statistik
        if ($isAdmin) {
            $totalPermintaan = DukunganKegiatan::count();
            $permintaanDiproses = DukunganKegiatan::whereIn('status', ['PENDING', 'DIIDELEGASIKAN', 'DIPROSES'])->count();
            $permintaanSelesai = DukunganKegiatan::where('status', 'SELESAI')->count();
        } else {
            $totalPermintaan = DukunganKegiatan::where('user_id', $user->id)->count();
            $permintaanDiproses = DukunganKegiatan::where('user_id', $user->id)->whereIn('status', ['PENDING', 'DIIDELEGASIKAN', 'DIPROSES'])->count();
            $permintaanSelesai = DukunganKegiatan::where('user_id', $user->id)->where('status', 'SELESAI')->count();
        }
        
        $eventMendatang = DukunganKegiatan::where('tanggal_pelaksanaan', '>=', now()->toDateString())
            ->whereIn('status', ['PENDING', 'DIIDELEGASIKAN', 'DIPROSES'])
            ->count();

        // Tabel untuk Admin
        $permintaanTerbaru = DukunganKegiatan::with(['user.karyawan'])
            ->latest()
            ->take(5)
            ->get();

        // Data untuk Hero Slider (Event mendatang & sedang berlangsung)
        $sliderEvents = DukunganKegiatan::with(['user.karyawan'])
            ->where('tanggal_pelaksanaan', '>=', now()->subDays(2)->toDateString())
            ->whereIn('status', ['DIIDELEGASIKAN', 'DIPROSES', 'SELESAI'])
            ->orderBy('tanggal_pelaksanaan', 'asc')
            ->take(5)
            ->get();

        // Data Real-time Kegiatan di Ruangan (Peminjaman Ruangan yang aktif hari ini)
        $realtimeRuangan = Peminjaman::with(['ruangan', 'user'])
            ->whereDate('tanggal_mulai', '<=', now()->toDateString())
            ->whereDate('tanggal_selesai', '>=', now()->toDateString())
            ->where('status', 'DISETUJUI')
            ->get();

        // Data untuk kalender
        $eventTerbaru = DukunganKegiatan::with(['user.karyawan'])
            ->whereIn('status', ['DIIDELEGASIKAN', 'DIPROSES', 'SELESAI'])
            ->get();

        return view('dashboard.index', compact(
            'totalPermintaan',
            'permintaanDiproses',
            'permintaanSelesai',
            'eventMendatang',
            'permintaanTerbaru',
            'sliderEvents',
            'realtimeRuangan',
            'eventTerbaru',
            'isAdmin'
        ));
    }
}