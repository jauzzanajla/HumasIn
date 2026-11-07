<?php

namespace App\Http\Controllers;

use App\Models\DukunganKegiatan;
use App\Models\PengingatKalender;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KalenderController extends Controller
{
    /**
     * Warna resmi kalender. Kunci = nama kategori, nilai = kode hex.
     * Kode hex ini SAMA dengan pilihan warna di form Blade.
     */
    private const KATEGORI = [
        'hijau'  => '#6EAE78',
        'oranye' => '#E5A044',
        'biru'   => '#5C88D9',
        'coklat' => '#8B654B',
    ];

    private const WARNA_DEFAULT = '#6EAE78';

    /**
     * Semua event untuk kalender (dipakai Admin, Eselon II, dan Karyawan).
     * Gabungan Dukungan Kegiatan Eselon II + Pengingat Mandiri Admin.
     */
    public function getEvents(Request $request): JsonResponse
    {
        // 1. Dukungan kegiatan yang sudah didelegasikan / diproses / selesai
        $dukungan = DukunganKegiatan::with('user')
            ->whereIn('status', ['DIDELEGASIKAN', 'DIPROSES', 'SELESAI'])
            ->whereNotNull('tanggal_pelaksanaan')
            ->get()
            ->map(fn (DukunganKegiatan $item) => $this->dukunganKeKalender($item));

        // 2. Pengingat mandiri admin
        $pengingat = PengingatKalender::query()
            ->whereNotNull('tanggal')
            ->get()
            ->map(fn (PengingatKalender $item) => $this->pengingatKeKalender($item));

        return response()->json($dukungan->concat($pengingat)->values());
    }

    /*
    |--------------------------------------------------------------------------
    | PENGINGAT (EVENT MANDIRI ADMIN)
    |--------------------------------------------------------------------------
    */

    public function storePengingat(Request $request): JsonResponse
    {
        $data = $this->validatePengingat($request);

        $pengingat = PengingatKalender::create($data + [
            'dibuat_oleh' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Pengingat berhasil ditambahkan.',
            'data'    => $this->pengingatKeKalender($pengingat),
        ], 201);
    }

    public function updatePengingat(Request $request, PengingatKalender $pengingat): JsonResponse
    {
        $pengingat->update($this->validatePengingat($request));

        return response()->json([
            'message' => 'Pengingat berhasil diperbarui.',
            'data'    => $this->pengingatKeKalender($pengingat->fresh()),
        ]);
    }

    public function destroyPengingat(PengingatKalender $pengingat): JsonResponse
    {
        $pengingat->delete();

        return response()->json([
            'message' => 'Pengingat berhasil dihapus.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EVENT (DukunganKegiatan): ubah judul dan kategori warna
    |--------------------------------------------------------------------------
    */

    public function updateEvent(Request $request, $id): JsonResponse
    {
        $validated = $request->validate([
            'judul'    => 'required|string|max:255',
            'kategori' => 'required|string',
        ], [
            'judul.required'    => 'Judul wajib diisi.',
            'kategori.required' => 'Pilih kategori warna.',
        ]);

        // Terima nama kategori ("oranye") maupun kode hex ("#E5A044")
        $kategori = $this->namaKategori($validated['kategori']);
        if ($kategori === null) {
            return response()->json([
                'message' => 'Kategori warna tidak valid.',
                'errors'  => ['kategori' => ['Kategori warna tidak valid.']],
            ], 422);
        }

        $event = DukunganKegiatan::findOrFail($id);
        $event->nama_event     = $validated['judul'];
        $event->kategori_warna = $kategori;   // selalu disimpan sebagai nama: hijau/oranye/biru/coklat
        $event->save();

        return response()->json([
            'message' => 'Event berhasil diperbarui.',
            'data'    => $this->dukunganKeKalender($event->fresh('user')),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT DATA KALENDER
    |--------------------------------------------------------------------------
    */

    private function dukunganKeKalender(DukunganKegiatan $item): array
    {
        return [
            'id'    => 'dukungan-' . $item->id,
            'title' => $item->nama_event,
            'start' => $this->formatStart($item->tanggal_pelaksanaan),
            'color' => $this->warnaHex($item->kategori_warna),
            'extendedProps' => [
                'type'       => 'dukungan',
                'rid'        => $item->id,
                'keterangan' => 'Lokasi: ' . ($item->lokasi ?: '-') . ' | Pemohon: ' . ($item->user?->name ?? '-'),
            ],
        ];
    }

    private function pengingatKeKalender(PengingatKalender $item): array
    {
        return [
            'id'    => 'pengingat-' . $item->id,
            'title' => $item->judul,
            'start' => $this->formatStart($item->tanggal, $item->jam),
            'color' => $this->warnaHex($item->warna),
            'extendedProps' => [
                'type'       => 'pengingat',
                'rid'        => $item->id,
                'keterangan' => $item->keterangan,
            ],
        ];
    }

    /**
     * Format tanggal tanpa zona waktu ("2026-11-06T09:00:00") supaya
     * tanggal tidak bergeser satu hari saat dibaca di browser.
     */
    private function formatStart($tanggal, $jam = null): string
    {
        $dt = Carbon::parse($tanggal);

        if ($jam) {
            [$h, $m] = array_pad(explode(':', (string) $jam), 2, '00');
            $dt->setTime((int) $h, (int) $m);
        }

        return $dt->format('Y-m-d\TH:i:s');
    }

    /**
     * Ubah nilai warna apa pun (nama kategori / hex huruf kecil-besar / hex lama
     * di luar daftar) menjadi salah satu kode hex resmi.
     */
    private function warnaHex(?string $nilai): string
    {
        $nilai = trim((string) $nilai);

        if (isset(self::KATEGORI[strtolower($nilai)])) {
            return self::KATEGORI[strtolower($nilai)];
        }

        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $nilai)) {
            return self::WARNA_DEFAULT;
        }

        $hex = strtoupper($nilai);
        if (in_array($hex, self::KATEGORI, true)) {
            return $hex;
        }

        // Warna lama di luar daftar -> warna resmi yang paling mirip
        $rgb = fn ($h) => [hexdec(substr($h, 1, 2)), hexdec(substr($h, 3, 2)), hexdec(substr($h, 5, 2))];
        [$r, $g, $b] = $rgb($hex);

        $terdekat = self::WARNA_DEFAULT;
        $jarakMin = PHP_INT_MAX;
        foreach (self::KATEGORI as $resmi) {
            [$r2, $g2, $b2] = $rgb($resmi);
            $jarak = ($r - $r2) ** 2 + ($g - $g2) ** 2 + ($b - $b2) ** 2;
            if ($jarak < $jarakMin) {
                $jarakMin = $jarak;
                $terdekat = $resmi;
            }
        }

        return $terdekat;
    }

    /**
     * Ubah nama kategori atau kode hex menjadi nama kategori resmi.
     * Mengembalikan null jika tidak dikenal.
     */
    private function namaKategori(?string $nilai): ?string
    {
        $nilai = strtolower(trim((string) $nilai));

        if (array_key_exists($nilai, self::KATEGORI)) {
            return $nilai;
        }

        $nama = array_search(strtoupper($nilai), self::KATEGORI, true);

        return $nama === false ? null : $nama;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

    private function validatePengingat(Request $request): array
    {
        // Terima hex huruf kecil juga, lalu cocokkan ke daftar warna resmi
        $request->merge(['warna' => strtoupper(trim((string) $request->input('warna')))]);

        $data = $request->validate([
            'judul'      => 'required|string|max:100',
            'tanggal'    => 'required|date',
            'jam'        => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'warna'      => ['required', Rule::in(array_values(self::KATEGORI))],
            'keterangan' => 'nullable|string|max:255',
        ], [
            'judul.required'   => 'Judul pengingat wajib diisi.',
            'judul.max'        => 'Judul maksimal 100 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date'     => 'Format tanggal tidak valid.',
            'jam.regex'        => 'Format jam tidak valid.',
            'warna.required'   => 'Pilih warna label.',
            'warna.in'         => 'Pilih salah satu warna label yang tersedia.',
            'keterangan.max'   => 'Keterangan maksimal 255 karakter.',
        ]);

        // Seragamkan: jam format HH:MM
        $data['jam']   = !empty($data['jam']) ? substr($data['jam'], 0, 5) : null;

        return $data;
    }
}