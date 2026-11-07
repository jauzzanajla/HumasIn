<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Unit;
use App\Models\SubunitOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR KARYAWAN + UNIT
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'karyawan');

        /*
        |--------------------------------------------------------------------------
        | DATA KARYAWAN
        |--------------------------------------------------------------------------
        */

        $karyawanQuery = Karyawan::with([
            'unit',
            'subunitOrganisasi',
            'user.peran',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH KARYAWAN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $karyawanQuery->where(function ($q) use ($search) {

                $q->where(
                    'nip',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'nama',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'jabatan',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'unit',
                    function ($unit) use ($search) {

                        $unit
                            ->where(
                                'kode_unit',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'nama_unit',
                                'like',
                                "%{$search}%"
                            );

                    }
                )

                ->orWhereHas(
                    'subunitOrganisasi',
                    function ($subunit) use ($search) {

                        $subunit->where(
                            'nama_subunit',
                            'like',
                            "%{$search}%"
                        );

                    }
                );

            });
        }


        $karyawan = $karyawanQuery
            ->orderBy('nama')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | DATA UNIT
        |--------------------------------------------------------------------------
        */

        $unitQuery = Unit::withCount('karyawan');


        if ($request->filled('search_unit')) {

            $searchUnit = trim(
                $request->search_unit
            );

            $unitQuery->where(function ($q) use ($searchUnit) {

                $q->where(
                    'kode_unit',
                    'like',
                    "%{$searchUnit}%"
                )

                ->orWhere(
                    'nama_unit',
                    'like',
                    "%{$searchUnit}%"
                );

            });
        }


        $units = $unitQuery
            ->orderBy('nama_unit')
            ->paginate(
                10,
                ['*'],
                'unit_page'
            )
            ->withQueryString();


        return view(
            'karyawan.index',
            compact(
                'karyawan',
                'units',
                'tab'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH KARYAWAN
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $unit = Unit::where(
            'aktif',
            true
        )
            ->orderBy('nama_unit')
            ->get();


        $subunitOrganisasi = SubunitOrganisasi::where(
            'aktif',
            true
        )
            ->orderBy('nama_subunit')
            ->get();


        return view(
            'karyawan.create',
            compact(
                'unit',
                'subunitOrganisasi'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SIMPAN KARYAWAN
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'unit_id' => [
                'required',
                'exists:unit,id',
            ],

            'subunit_organisasi_id' => [

                'nullable',

                'exists:subunit_organisasi,id',

                Rule::exists(
                    'subunit_organisasi',
                    'id'
                )
                    ->where(
                        'unit_id',
                        $request->input('unit_id')
                    )
                    ->where(
                        'aktif',
                        true
                    ),

            ],

            'nip' => [
                'required',
                'string',
                'max:50',
                'unique:karyawan,nip',
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:karyawan,email',
            ],

            'jabatan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'aktif' => [
                'required',
                'boolean',
            ],

        ]);


        Karyawan::create(
            $validated
        );


        return redirect()
            ->route(
                'karyawan.index',
                [
                    'tab' => 'karyawan',
                ]
            )
            ->with(
                'success',
                'Data karyawan berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL KARYAWAN
    |--------------------------------------------------------------------------
    */

    public function show(Karyawan $karyawan)
    {
        $karyawan->load([
            'unit',
            'subunitOrganisasi',
            'user.peran',
        ]);


        return view(
            'karyawan.show',
            compact('karyawan')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM EDIT KARYAWAN
    |--------------------------------------------------------------------------
    */

    public function edit(Karyawan $karyawan)
    {
        $unit = Unit::where(
            'aktif',
            true
        )
            ->orderBy('nama_unit')
            ->get();


        $subunitOrganisasi = SubunitOrganisasi::where(
            'aktif',
            true
        )
            ->orderBy('nama_subunit')
            ->get();


        $karyawan->load([
            'unit',
            'subunitOrganisasi',
            'user.peran',
        ]);


        return view(
            'karyawan.edit',
            compact(
                'karyawan',
                'unit',
                'subunitOrganisasi'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE KARYAWAN
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Karyawan $karyawan
    ) {

        $validated = $request->validate([

            'unit_id' => [
                'required',
                'exists:unit,id',
            ],

            'subunit_organisasi_id' => [

                'nullable',

                'exists:subunit_organisasi,id',

                Rule::exists(
                    'subunit_organisasi',
                    'id'
                )
                    ->where(
                        'unit_id',
                        $request->input('unit_id')
                    )
                    ->where(
                        'aktif',
                        true
                    ),

            ],

            'nip' => [

                'required',
                'string',
                'max:50',

                Rule::unique(
                    'karyawan',
                    'nip'
                )->ignore(
                    $karyawan->id
                ),

            ],

            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [

                'required',
                'email',
                'max:255',

                Rule::unique(
                    'karyawan',
                    'email'
                )->ignore(
                    $karyawan->id
                ),

            ],

            'jabatan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'aktif' => [
                'required',
                'boolean',
            ],

        ]);


        $karyawan->update(
            $validated
        );


        /*
        |--------------------------------------------------------------------------
        | SINKRONISASI DATA USER
        |--------------------------------------------------------------------------
        |
        | Kalau karyawan sudah punya akun, nama dan email user
        | mengikuti data karyawan.
        |
        */

        if ($karyawan->user) {

            $karyawan->user->update([
                'name' => $karyawan->nama,
                'email' => $karyawan->email,
            ]);

        }


        return redirect()
            ->route(
                'karyawan.index',
                [
                    'tab' => 'karyawan',
                ]
            )
            ->with(
                'success',
                'Data karyawan berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS KARYAWAN
    |--------------------------------------------------------------------------
    |
    | Hanya Super Admin.
    |
    | Jika karyawan sudah memiliki akun:
    | 1. Hapus akun User
    | 2. Hapus data Karyawan
    |
    | Menggunakan transaction agar aman.
    |
    */

    public function destroy(Karyawan $karyawan)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK ROLE LOGIN
        |--------------------------------------------------------------------------
        */

        /** @var \App\Models\User|null $userLogin */
        $userLogin = Auth::user();
        $userLogin?->loadMissing('peran');

        abort_unless(
            $userLogin?->peran?->kode_peran === 'SUPER_ADMIN',
            403
        );


        /*
        |--------------------------------------------------------------------------
        | LOAD USER + ROLE
        |--------------------------------------------------------------------------
        */

        $karyawan->load([
            'user.peran',
        ]);


        /*
        |--------------------------------------------------------------------------
        | JANGAN HAPUS SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $karyawan->user &&
            $karyawan->user->peran &&
            $karyawan->user->peran->kode_peran === 'SUPER_ADMIN'
        ) {

            return redirect()
                ->route(
                    'karyawan.index',
                    [
                        'tab' => 'karyawan',
                    ]
                )
                ->with(
                    'error',
                    'Data Super Admin tidak dapat dihapus.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS USER + KARYAWAN
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use ($karyawan) {

                /*
                | Hapus akun User jika ada.
                */

                if ($karyawan->user) {

                    $karyawan->user->delete();

                }


                /*
                | Setelah User terhapus,
                | baru hapus Karyawan.
                */

                $karyawan->delete();

            }
        );


        return redirect()
            ->route(
                'karyawan.index',
                [
                    'tab' => 'karyawan',
                ]
            )
            ->with(
                'success',
                'Data karyawan dan akun terkait berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL KARYAWAN BERDASARKAN UNIT
    |--------------------------------------------------------------------------
    */

    public function unitDetail(
        Request $request,
        Unit $unit
    ) {

        $query = Karyawan::with([
            'unit',
            'subunitOrganisasi',
            'user.peran',
        ])
            ->where(
                'unit_id',
                $unit->id
            );


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );


            $query->where(function ($q) use ($search) {

                $q->where(
                    'nip',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'nama',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'jabatan',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'subunitOrganisasi',
                    function ($subunit) use ($search) {

                        $subunit->where(
                            'nama_subunit',
                            'like',
                            "%{$search}%"
                        );

                    }
                );

            });
        }


        $karyawan = $query
            ->orderBy('nama')
            ->get();


        return view(
            'karyawan.unit-detail',
            compact(
                'unit',
                'karyawan'
            )
        );
    }
}