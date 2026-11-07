<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class RuanganController extends Controller
{
    /**
     * Menampilkan daftar ruangan.
     */
    public function index(Request $request)
    {
        $query = Ruangan::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'nama_ruangan',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'lokasi',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'deskripsi',
                    'like',
                    "%{$search}%"
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DATA RUANGAN
        |--------------------------------------------------------------------------
        */

        $ruangan = $query
            ->orderBy('nama_ruangan')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalRuangan = Ruangan::count();

        $ruanganTersedia = Ruangan::where(
            'status',
            'TERSEDIA'
        )->count();

        $ruanganTidakTersedia = Ruangan::where(
            'status',
            'TIDAK_TERSEDIA'
        )->count();


        return view(
            'ruangan.index',
            compact(
                'ruangan',
                'totalRuangan',
                'ruanganTersedia',
                'ruanganTidakTersedia'
            )
        );
    }


    /**
     * Menampilkan form tambah ruangan.
     */
    public function create()
    {
        return view('ruangan.create');
    }


    /**
     * Menyimpan ruangan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'nama_ruangan' => [
                'required',
                'string',
                'max:255',
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'lokasi' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kapasitas' => [
                'required',
                'integer',
                'min:1',
            ],

            'kuota' => [
                'required',
                'integer',
                'min:1',
            ],

            'durasi_maksimal' => [
                'required',
                'integer',
                'min:1',
            ],

            'satuan_durasi' => [
                'required',
                Rule::in([
                    'MENIT',
                    'JAM',
                    'HARI',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'TERSEDIA',
                    'TIDAK_TERSEDIA',
                ]),
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | UPLOAD FOTO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto')) {

            $validated['foto'] = $request
                ->file('foto')
                ->store(
                    'ruangan',
                    'public'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA
        |--------------------------------------------------------------------------
        */

        Ruangan::create($validated);


        return redirect()
            ->route('ruangan.index')
            ->with(
                'success',
                'Data ruangan berhasil ditambahkan.'
            );
    }


    /**
     * Menampilkan detail ruangan.
     */
    public function show(Ruangan $ruangan)
    {
        return view(
            'ruangan.show',
            compact('ruangan')
        );
    }


    /**
     * Menampilkan form edit ruangan.
     */
    public function edit(Ruangan $ruangan)
    {
        return view(
            'ruangan.edit',
            compact('ruangan')
        );
    }


    /**
     * Memperbarui data ruangan.
     */
    public function update(
        Request $request,
        Ruangan $ruangan
    ) {

        $validated = $request->validate([

            'nama_ruangan' => [
                'required',
                'string',
                'max:255',
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'lokasi' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kapasitas' => [
                'required',
                'integer',
                'min:1',
            ],

            'kuota' => [
                'required',
                'integer',
                'min:1',
            ],

            'durasi_maksimal' => [
                'required',
                'integer',
                'min:1',
            ],

            'satuan_durasi' => [
                'required',
                Rule::in([
                    'MENIT',
                    'JAM',
                    'HARI',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'TERSEDIA',
                    'TIDAK_TERSEDIA',
                ]),
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | GANTI FOTO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto')) {

            /*
            | Hapus foto lama
            */

            if ($ruangan->foto) {

                Storage::disk('public')->delete(
                    $ruangan->foto
                );

            }


            /*
            | Simpan foto baru
            */

            $validated['foto'] = $request
                ->file('foto')
                ->store(
                    'ruangan',
                    'public'
                );

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA
        |--------------------------------------------------------------------------
        */

        $ruangan->update($validated);


        return redirect()
            ->route('ruangan.index')
            ->with(
                'success',
                'Data ruangan berhasil diperbarui.'
            );
    }


    /**
     * Menghapus ruangan.
     */
    public function destroy(Ruangan $ruangan)
    {
        /*
        |--------------------------------------------------------------------------
        | HAPUS FOTO
        |--------------------------------------------------------------------------
        */

        if ($ruangan->foto) {

            Storage::disk('public')->delete(
                $ruangan->foto
            );

        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS DATA RUANGAN
        |--------------------------------------------------------------------------
        */

        $ruangan->delete();


        return redirect()
            ->route('ruangan.index')
            ->with(
                'success',
                'Data ruangan berhasil dihapus.'
            );
    }
}