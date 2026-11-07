<?php

namespace App\Http\Controllers;

use App\Models\Opini;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class OpiniController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $user?->loadMissing('peran');
        $role = $user?->peran?->kode_peran;
        $isAdmin = in_array($role, ['SUPER_ADMIN', 'ADMIN'], true);

        $query = Opini::with(['user.karyawan.unit', 'user.karyawan.subunitOrganisasi']);

        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        $opinis = $query->latest()->get();

        return view('opini.index', compact('opinis', 'isAdmin'));
    }

    public function create()
    {
        return view('opini.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_opini' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'ringkasan' => 'required|string',
            'file_naskah' => 'required|file|mimes:pdf,docx,doc|max:5120',
            'foto_penulis' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $naskahPath = $request->file('file_naskah')->store('opini/naskah', 'public');
        $fotoPath = $request->hasFile('foto_penulis') 
            ? $request->file('foto_penulis')->store('opini/foto', 'public') 
            : null;

        Opini::create([
            'user_id' => Auth::id(),
            'judul_opini' => $validated['judul_opini'],
            'penulis' => $validated['penulis'],
            'ringkasan' => $validated['ringkasan'],
            'file_naskah' => $naskahPath,
            'foto_penulis' => $fotoPath,
            'status' => 'PENDING',
        ]);
        Notifikasi::kirimKeAdmin('Naskah opini baru', Auth::user()->name . ' mengirim opini "' . $validated['judul_opini'] . '".', route('opini.index'), 'info');

        return redirect()->route('opini.index')->with('success', 'Materi opini berhasil dikirim ke Humas DJPK.');
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

        $opini = Opini::findOrFail($id);
        $opini->update([
            'status' => $validated['status'],
            'alasan_penolakan' => $validated['status'] === 'DITOLAK' ? $validated['alasan_penolakan'] : null,
        ]);
        Notifikasi::kirim(
    $opini->user_id,
    $validated['status'] === 'DISETUJUI' ? 'Opini diterbitkan' : 'Opini ditolak',
    $validated['status'] === 'DISETUJUI'
        ? "Opini \"{$opini->judul_opini}\" disetujui dan siap diterbitkan."
        : "Opini \"{$opini->judul_opini}\" ditolak. Alasan: " . $validated['alasan_penolakan'],
    route('opini.index'),
    $validated['status'] === 'DISETUJUI' ? 'sukses' : 'tolak'
);

        $msg = $validated['status'] === 'DISETUJUI' ? 'Opini disetujui dan siap diterbitkan.' : 'Opini ditolak.';

        return redirect()->route('opini.index')->with('success', $msg);
    }
}