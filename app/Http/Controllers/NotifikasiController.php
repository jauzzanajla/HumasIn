<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    private function milik()
    {
        return Notifikasi::where('user_id', Auth::id());
    }

    public function index(Request $request)
    {
        $filter = $request->query('filter', 'semua');

        $query = $this->milik()->latest();
        if ($filter === 'belum') $query->whereNull('dibaca_pada');

        $notifikasi = $query->paginate(15)->withQueryString();

        $totalBelum = $this->milik()->whereNull('dibaca_pada')->count();
        $totalSemua = $this->milik()->count();

        return view('notifikasi.index', compact('notifikasi', 'filter', 'totalBelum', 'totalSemua'));
    }

    public function baca($id)
    {
        $n = $this->milik()->findOrFail($id);
        if (! $n->dibaca_pada) $n->update(['dibaca_pada' => now()]);

        return redirect($n->url ?: route('notifikasi.index'));
    }

    public function tandai($id)
    {
        $n = $this->milik()->findOrFail($id);
        $n->update(['dibaca_pada' => $n->dibaca_pada ? null : now()]);

        return back();
    }

    public function bacaSemua()
    {
        $this->milik()->whereNull('dibaca_pada')->update(['dibaca_pada' => now()]);

        return back()->with('success', 'Semua notifikasi berhasil ditandai sudah dibaca.');
    }

    public function destroy($id)
    {
        $this->milik()->findOrFail($id)->delete();

        return back()->with('success', 'Notifikasi dihapus.');
    }

    public function hapusTerpilih(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!empty($ids)) {
            Notifikasi::where('user_id', Auth::id())
                ->whereIn('id', $ids)
                ->delete();

            return back()->with('success', 'Notifikasi yang dipilih berhasil dihapus.');
        }

        return back()->with('error', 'Tidak ada notifikasi yang dipilih.');
    }

    public function hapusSemua()
    {
        Notifikasi::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}