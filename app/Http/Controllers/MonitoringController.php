<?php

namespace App\Http\Controllers;

use App\Models\DukunganKegiatan;
use App\Models\Opini;
use App\Models\Peminjaman;
use App\Models\Publikasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    private const TIPE = ['publikasi', 'peminjaman', 'event', 'opini'];

    private const STATUS_EVENT = ['PENDING', 'DIIDELEGASIKAN', 'DIPROSES', 'SELESAI', 'DITOLAK'];

    /** Hanya Admin dan Super Admin. */
    private function authorizeAdmin(): void
    {
        $user = Auth::user();

        abort_unless(
            in_array($user?->peran?->kode_peran, ['SUPER_ADMIN', 'ADMIN'], true),
            403,
            'Anda tidak memiliki akses ke halaman monitoring.'
        );
    }

    private function tipe(Request $request): string
    {
        $tipe = $request->input('tipe', 'publikasi');

        return in_array($tipe, self::TIPE, true) ? $tipe : 'publikasi';
    }

    /**
     * Query dasar per jenis rekap, lengkap dengan pencarian dan filter.
     * $denganStatus = false dipakai untuk menghitung jumlah per status.
     */
    private function query(string $tipe, Request $request, bool $denganStatus = true)
    {
        [$query, $kolomTanggal, $kolomCari] = match ($tipe) {
            'peminjaman' => [Peminjaman::with(['user.karyawan.unit', 'aset', 'ruangan']), 'tanggal_mulai', ['keperluan', 'no_hp']],
            'event'      => [DukunganKegiatan::with(['user.karyawan.unit']), 'tanggal_pelaksanaan', ['nama_event', 'lokasi', 'pejabat_hadir']],
            'opini'      => [Opini::with(['user.karyawan.unit']), 'created_at', ['judul_opini', 'penulis']],
            default      => [Publikasi::with(['user.karyawan.unit']), 'when_tanggal', ['nama_kegiatan', 'deskripsi', 'where_lokasi']],
        };

        $cari = trim((string) $request->input('search'));

        if ($cari !== '') {
            $query->where(function ($q) use ($cari, $kolomCari, $tipe) {
                foreach ($kolomCari as $kolom) {
                    $q->orWhere($kolom, 'like', "%{$cari}%");
                }

                $q->orWhereHas('user', function ($u) use ($cari) {
                    $u->where('name', 'like', "%{$cari}%")
                        ->orWhereHas('karyawan', fn ($k) => $k->where('nama', 'like', "%{$cari}%")->orWhere('nip', 'like', "%{$cari}%"));
                });

                if ($tipe === 'peminjaman') {
                    $q->orWhereHas('aset', fn ($a) => $a->where('nama_aset', 'like', "%{$cari}%"))
                        ->orWhereHas('ruangan', fn ($r) => $r->where('nama_ruangan', 'like', "%{$cari}%"));
                }
            });
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate($kolomTanggal, '>=', $request->input('tanggal_mulai'));
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate($kolomTanggal, '<=', $request->input('tanggal_selesai'));
        }

        if ($tipe === 'peminjaman') {
            if ($request->input('kategori') === 'aset') {
                $query->whereNotNull('aset_id');
            } elseif ($request->input('kategori') === 'ruangan') {
                $query->whereNotNull('ruangan_id');
            }
        }

        if ($denganStatus && $request->filled('status')) {
            $status = $request->input('status');

            if ($tipe === 'peminjaman' && $status === 'DIKEMBALIKAN') {
                $query->where('status', 'DISETUJUI')->where('status_pengembalian', 'DIKEMBALIKAN');
            } else {
                $query->where('status', $status);
            }
        }

        return $query;
    }

    /* ------------------------------------------------------------------ */

    public function rekap(Request $request)
    {
        $this->authorizeAdmin();

        $tipe = $this->tipe($request);

        // Total keseluruhan untuk kartu ringkasan
        $total = [
            'publikasi'  => Publikasi::count(),
            'peminjaman' => Peminjaman::count(),
            'event'      => DukunganKegiatan::count(),
            'opini'      => Opini::count(),
        ];

        // Jumlah per status (mengikuti pencarian dan tanggal, tanpa filter status)
        $dasar = $this->query($tipe, $request, false);
        $jumlahStatus = (clone $dasar)->reorder()
            ->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        if ($tipe === 'peminjaman') {
            $jumlahStatus['DIKEMBALIKAN'] = (clone $dasar)->reorder()
                ->where('status', 'DISETUJUI')->where('status_pengembalian', 'DIKEMBALIKAN')->count();
            $jumlahStatus['DISETUJUI'] = max(0, ($jumlahStatus['DISETUJUI'] ?? 0) - $jumlahStatus['DIKEMBALIKAN']);
        }

        $statusOpsi = $tipe === 'event'
            ? self::STATUS_EVENT
            : ($tipe === 'peminjaman'
                ? ['PENDING', 'DISETUJUI', 'DIKEMBALIKAN', 'DITOLAK']
                : ['PENDING', 'DISETUJUI', 'DITOLAK']);

        $items = $this->query($tipe, $request)->latest()->paginate(15)->withQueryString();

        // Nama PIC untuk tab event
        $namaPic = collect();
        if ($tipe === 'event') {
            $ids = $items->getCollection()->flatMap(fn ($e) => $e->admin_ids ?? [])->unique()->values();
            $namaPic = User::whereIn('id', $ids)->pluck('name', 'id');
        }

        return view('monitoring.rekap', [
            'tipe'         => $tipe,
            'items'        => $items,
            'total'        => $total,
            'jumlahStatus' => $jumlahStatus,
            'statusOpsi'   => $statusOpsi,
            'namaPic'      => $namaPic,
        ]);
    }

    /* ------------------------------------------------------------------ */

    public function exportRekap(Request $request)
    {
        $this->authorizeAdmin();

        $tipe = $this->tipe($request);
        $data = $this->query($tipe, $request)->latest()->get();

        $judul = match ($tipe) {
            'peminjaman' => 'Peminjaman',
            'event'      => 'Event-Dukungan-Kegiatan',
            'opini'      => 'Opini',
            default      => 'Publikasi',
        };

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Rekap-' . $judul . '-' . date('Y-m-d') . '.csv"',
            'Cache-Control'       => 'no-store, no-cache',
        ];

        $namaPic = $tipe === 'event'
            ? User::whereIn('id', $data->flatMap(fn ($e) => $e->admin_ids ?? [])->unique())->pluck('name', 'id')
            : collect();

        $callback = function () use ($tipe, $data, $namaPic) {
            $f = fopen('php://output', 'w');
            fwrite($f, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8

            $pemohon = fn ($x) => $x->user?->karyawan?->nama ?? $x->user?->name ?? '-';
            $unit    = fn ($x) => $x->user?->karyawan?->unit?->nama_unit ?? '-';

            if ($tipe === 'peminjaman') {
                fputcsv($f, ['No', 'Peminjam', 'Unit', 'No. HP', 'Jenis', 'Fasilitas', 'Jumlah', 'Mulai', 'Selesai', 'Keperluan', 'Status', 'Pengembalian', 'Alasan Penolakan']);
                foreach ($data as $i => $x) {
                    $aset = ! empty($x->aset_id);
                    fputcsv($f, [
                        $i + 1, $pemohon($x), $unit($x), $x->no_hp,
                        $aset ? 'Aset' : 'Ruangan',
                        $aset ? ($x->aset?->nama_aset ?? '-') : ($x->ruangan?->nama_ruangan ?? '-'),
                        $aset ? $x->jumlah : 1,
                        Carbon::parse($x->tanggal_mulai)->format('d/m/Y') . ' ' . substr($x->jam_mulai, 0, 5),
                        Carbon::parse($x->tanggal_selesai)->format('d/m/Y') . ' ' . substr($x->jam_selesai, 0, 5),
                        $x->keperluan, $x->status,
                        $x->status === 'DISETUJUI' ? ($x->status_pengembalian === 'DIKEMBALIKAN' ? 'Dikembalikan' : 'Belum dikembalikan') : '-',
                        $x->alasan_penolakan,
                    ]);
                }
            } elseif ($tipe === 'event') {
                fputcsv($f, ['No', 'Pemohon', 'Unit', 'Nama Event', 'Tanggal', 'Jam', 'Lokasi', 'Pejabat Hadir', 'Jumlah Peserta', 'Support', 'PIC', 'Status']);
                $label = ['support_operator' => 'Operator', 'support_desain_grafis' => 'Desain Grafis', 'support_editing' => 'Editing', 'support_konten_web' => 'Konten Web', 'support_merchandise' => 'Merchandise', 'support_dokumentasi' => 'Dokumentasi', 'support_tim_acara' => 'Tim Acara'];
                foreach ($data as $i => $x) {
                    $support = collect($label)->filter(fn ($l, $k) => $x->{$k})->values()->implode(', ');
                    $pic = collect($x->admin_ids ?? [])->map(fn ($id) => $namaPic[$id] ?? null)->filter()->implode(', ');
                    fputcsv($f, [
                        $i + 1, $pemohon($x), $unit($x), $x->nama_event,
                        Carbon::parse($x->tanggal_pelaksanaan)->format('d/m/Y'), $x->jam_pelaksanaan, $x->lokasi,
                        $x->pejabat_hadir, $x->jumlah_peserta, $support ?: '-', $pic ?: '-', $x->status,
                    ]);
                }
            } elseif ($tipe === 'opini') {
                fputcsv($f, ['No', 'Pemohon', 'Unit', 'Judul Opini', 'Penulis', 'Diajukan', 'Status', 'Alasan Penolakan']);
                foreach ($data as $i => $x) {
                    fputcsv($f, [$i + 1, $pemohon($x), $unit($x), $x->judul_opini, $x->penulis, $x->created_at?->format('d/m/Y H:i'), $x->status, $x->alasan_penolakan]);
                }
            } else {
                fputcsv($f, ['No', 'Pemohon', 'Unit', 'Nama Kegiatan', 'Tanggal Kegiatan', 'Lokasi', 'Diajukan', 'Status', 'Alasan Penolakan']);
                foreach ($data as $i => $x) {
                    fputcsv($f, [$i + 1, $pemohon($x), $unit($x), $x->nama_kegiatan, Carbon::parse($x->when_tanggal)->format('d/m/Y'), $x->where_lokasi, $x->created_at?->format('d/m/Y H:i'), $x->status, $x->alasan_penolakan]);
                }
            }

            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }

    /* ------------------------------------------------------------------ */
    /* Riwayat sudah digabung ke Rekap. Link lama dialihkan.              */
    /* ------------------------------------------------------------------ */

    public function riwayat(Request $request)
    {
        $this->authorizeAdmin();

        return redirect()->route('monitoring.rekap', array_merge($request->query(), ['tipe' => 'peminjaman']));
    }

    public function exportRiwayat(Request $request)
    {
        $this->authorizeAdmin();

        return redirect()->route('monitoring.rekap.export', array_merge($request->query(), ['tipe' => 'peminjaman']));
    }
}