<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR ASET
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Aset::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'nama_aset',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'deskripsi',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'status',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'kondisi',
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


        $aset = $query
            ->orderBy('nama_aset')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalAset = Aset::count();

        $totalUnit = Aset::sum('jumlah');

        $asetTersedia = Aset::where(
            'status',
            'TERSEDIA'
        )->count();

        $asetPerbaikan = Aset::where(
            'status',
            'PERBAIKAN'
        )->count();


        return view(
            'aset.index',
            compact(
                'aset',
                'totalAset',
                'totalUnit',
                'asetTersedia',
                'asetPerbaikan'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('aset.create');
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN ASET
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'nama_aset' => [
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

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'jumlah' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:TERSEDIA,TIDAK_TERSEDIA,PERBAIKAN',
            ],

            'kondisi' => [
                'required',
                'in:BAIK,RUSAK_RINGAN,RUSAK_BERAT',
            ],

        ], [

            'nama_aset.required' =>
                'Nama barang wajib diisi.',

            'foto.image' =>
                'File yang diupload harus berupa gambar.',

            'foto.mimes' =>
                'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',

            'foto.max' =>
                'Ukuran foto maksimal 5 MB.',

            'jumlah.required' =>
                'Jumlah barang wajib diisi.',

            'jumlah.min' =>
                'Jumlah barang minimal 1.',

            'status.required' =>
                'Status wajib dipilih.',

            'kondisi.required' =>
                'Kondisi wajib dipilih.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | UPLOAD FOTO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto')) {

            $validated['foto'] =
                $request->file('foto')
                    ->store(
                        'aset',
                        'public'
                    );

        }


        Aset::create($validated);


        return redirect()
            ->route('aset.index')
            ->with(
                'success',
                'Aset berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    public function show(Aset $aset)
    {
        return view(
            'aset.show',
            compact('aset')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(Aset $aset)
    {
        return view(
            'aset.edit',
            compact('aset')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Aset $aset
    ) {

        $validated = $request->validate([

            'nama_aset' => [
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

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'jumlah' => [
                'required',
                'integer',
                'min:1',
            ],

            'status' => [
                'required',
                'in:TERSEDIA,TIDAK_TERSEDIA,PERBAIKAN',
            ],

            'kondisi' => [
                'required',
                'in:BAIK,RUSAK_RINGAN,RUSAK_BERAT',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | FOTO BARU
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto')) {

            if (
                $aset->foto &&
                Storage::disk('public')->exists(
                    $aset->foto
                )
            ) {

                Storage::disk('public')->delete(
                    $aset->foto
                );

            }


            $validated['foto'] =
                $request->file('foto')
                    ->store(
                        'aset',
                        'public'
                    );
        }


        $aset->update($validated);


        return redirect()
            ->route('aset.index')
            ->with(
                'success',
                'Aset berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS
    |--------------------------------------------------------------------------
    */

    public function destroy(Aset $aset)
    {
        /*
        |--------------------------------------------------------------------------
        | Hapus foto
        |--------------------------------------------------------------------------
        */

        if (
            $aset->foto &&
            Storage::disk('public')->exists(
                $aset->foto
            )
        ) {

            Storage::disk('public')->delete(
                $aset->foto
            );

        }


        $aset->delete();


        return redirect()
            ->route('aset.index')
            ->with(
                'success',
                'Aset berhasil dihapus.'
            );
    }
}