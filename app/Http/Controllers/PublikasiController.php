<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Publikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $user?->loadMissing('peran');
        $role = $user?->peran?->kode_peran;
        $isAdmin = in_array($role, ['SUPER_ADMIN', 'ADMIN'], true);

        $query = Publikasi::with(['user.karyawan.unit', 'user.karyawan.subunitOrganisasi']);

        // Jika bukan admin, TU Eselon II hanya melihat pengajuannya sendiri
        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        $publikasis = $query->latest()->get();

        return view('publikasi.index', compact('publikasis', 'isAdmin'));
    }

    public function create()
    {
        return view('publikasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'what' => 'required|string',
            'who' => 'required|string',
            'when_tanggal' => 'required|date',
            'where_lokasi' => 'required|string|max:255',
            'why' => 'required|string',
            'how' => 'required|string',
            'foto_kegiatan' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'file_pendukung' => 'nullable|file|mimes:pdf,docx,doc,zip|max:5120',
        ]);

        $fotoPath = $request->file('foto_kegiatan')->store('publikasi/foto', 'public');
        $filePath = $request->hasFile('file_pendukung') 
            ? $request->file('file_pendukung')->store('publikasi/file', 'public') 
            : null;

        Publikasi::create([
            'user_id' => Auth::id(),
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'deskripsi' => $validated['deskripsi'],
            'what' => $validated['what'],
            'who' => $validated['who'],
            'when_tanggal' => $validated['when_tanggal'],
            'where_lokasi' => $validated['where_lokasi'],
            'why' => $validated['why'],
            'how' => $validated['how'],
            'foto_kegiatan' => $fotoPath,
            'file_pendukung' => $filePath,
            'status' => 'PENDING',
        ]);
        Notifikasi::kirimKeAdmin('Permintaan publikasi baru', Auth::user()->name . ' mengajukan publikasi "' . $validated['nama_kegiatan'] . '".', route('publikasi.index'), 'info');

        return redirect()->route('publikasi.index')->with('success', 'Permintaan publikasi website DJPK berhasil diajukan.');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $user?->loadMissing('peran');
        $isAdmin = in_array($user?->peran?->kode_peran, ['SUPER_ADMIN', 'ADMIN'], true);

        abort_unless($isAdmin, 403, 'Unauthorized.');

        $validated = $request->validate([
            'status' => 'required|in:DISETUJUI,DITOLAK',
            'alasan_penolakan' => 'required_if:status,DITOLAK|nullable|string',
        ]);

        $publikasi = Publikasi::findOrFail($id);
        $publikasi->update([
            'status' => $validated['status'],
            'alasan_penolakan' => $validated['status'] === 'DITOLAK' ? $validated['alasan_penolakan'] : null,
        ]);

        $msg = $validated['status'] === 'DISETUJUI' ? 'Publikasi disetujui & siap diunggah ke website DJPK.' : 'Publikasi ditolak.';
        Notifikasi::kirim(
    $publikasi->user_id,
    $validated['status'] === 'DISETUJUI' ? 'Publikasi disetujui' : 'Publikasi ditolak',
    $validated['status'] === 'DISETUJUI'
        ? "Materi \"{$publikasi->nama_kegiatan}\" disetujui dan siap diunggah."
        : "Materi \"{$publikasi->nama_kegiatan}\" ditolak. Alasan: " . $validated['alasan_penolakan'],
    route('publikasi.index'),
    $validated['status'] === 'DISETUJUI' ? 'sukses' : 'tolak'
);
        return redirect()->route('publikasi.index')->with('success', $msg);
    }
}