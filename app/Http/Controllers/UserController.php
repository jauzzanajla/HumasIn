<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peran;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR AKUN USER
    |--------------------------------------------------------------------------
    */
public function index(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | DEFAULT
    |--------------------------------------------------------------------------
    | Tanpa pencarian:
    | hanya tampilkan ADMIN dan TU ESELON II.
    |
    | Jika ada pencarian:
    | KARYAWAN juga ikut dicari.
    |--------------------------------------------------------------------------
    */

    $query = User::with([
        'karyawan.unit',
        'karyawan.subunitOrganisasi',
        'peran',
    ]);


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            /*
            | Cari berdasarkan data user
            */

            $q->where('name', 'like', "%{$search}%")

                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                )


                /*
                | Cari berdasarkan karyawan
                */

                ->orWhereHas(
                    'karyawan',
                    function ($karyawan) use ($search) {

                        $karyawan
                            ->where(
                                'nama',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'nip',
                                'like',
                                "%{$search}%"
                            );

                    }
                )


                /*
                | Cari berdasarkan role
                */

                ->orWhereHas(
                    'peran',
                    function ($peran) use ($search) {

                        $peran->where(
                            'nama_peran',
                            'like',
                            "%{$search}%"
                        );

                    }
                );

        });

    } else {

        /*
        |--------------------------------------------------------------------------
        | TANPA SEARCH
        |--------------------------------------------------------------------------
        | Hanya ADMIN dan TU ESELON II.
        |--------------------------------------------------------------------------
        */

        $query->whereHas(
            'peran',
            function ($q) {

                $q->whereIn(
                    'kode_peran',
                    [
                        'ADMIN',
                        'TU_ESELON_II',
                    ]
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FILTER ROLE
    |--------------------------------------------------------------------------
    */

    if ($request->filled('role')) {

        $query->where(
            'id_peran',
            $request->role
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DATA
    |--------------------------------------------------------------------------
    */

    $users = $query
        ->orderBy('name')
        ->paginate(10)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | TOTAL AKUN YANG DITAMPILKAN DEFAULT
    |--------------------------------------------------------------------------
    */

    $totalUsers = User::whereHas(
        'peran',
        function ($q) {

            $q->whereIn(
                'kode_peran',
                [
                    'ADMIN',
                    'TU_ESELON_II',
                ]
            );

        }
    )->count();


    /*
    |--------------------------------------------------------------------------
    | ROLE FILTER
    |--------------------------------------------------------------------------
    */

    $roles = Peran::whereIn(
        'kode_peran',
        [
            'KARYAWAN',
            'ADMIN',
            'TU_ESELON_II',
        ]
    )
    ->where('aktif', true)
    ->orderBy('id')
    ->get();


    return view(
        'users.index',
        compact(
            'users',
            'totalUsers',
            'roles'
        )
    );
}


    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH AKUN
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Hanya karyawan yang BELUM memiliki akun
        |--------------------------------------------------------------------------
        */

        $karyawan = Karyawan::with([
            'unit',
            'subunitOrganisasi',
        ])
        ->where('aktif', true)
        ->whereDoesntHave('user')
        ->orderBy('nama')
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Role yang bisa dibuat manual
        |--------------------------------------------------------------------------
        |
        | Karyawan tidak perlu dibuat manual karena bisa aktivasi sendiri.
        |
        */

        $roles = Peran::whereIn('kode_peran', [
            'ADMIN',
            'TU_ESELON_II',
        ])
        ->where('aktif', true)
        ->orderBy('id')
        ->get();


        return view(
            'users.create',
            compact(
                'karyawan',
                'roles'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH KARYAWAN
    |--------------------------------------------------------------------------
    */

 public function searchKaryawan(Request $request)
{
    $keyword = trim($request->get('q', ''));

    if ($keyword === '') {
        return response()->json([]);
    }

    $karyawan = Karyawan::with([
        'unit',
        'user.peran',
    ])
        ->where('aktif', true)
        ->where(function ($query) use ($keyword) {

            $query->where(
                'nama',
                'like',
                "%{$keyword}%"
            )
            ->orWhere(
                'nip',
                'like',
                "%{$keyword}%"
            );

        })
        ->orderBy('nama')
        ->limit(10)
        ->get();

    $hasil = $karyawan->map(function ($pegawai) {

        return [
            'id' => $pegawai->id,
            'nama' => $pegawai->nama,
            'nip' => $pegawai->nip,
            'unit' => $pegawai->unit?->nama_unit ?? '-',

            'sudah_punya_akun' => $pegawai->user !== null,

            'user_id' => $pegawai->user?->id,

            'role' => $pegawai->user?->peran?->nama_peran,
        ];

    })->values();

    return response()->json($hasil);
}

    /*
    |--------------------------------------------------------------------------
    | SIMPAN AKUN BARU
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'karyawan_id' => [
                'required',
                'integer',
                'exists:karyawan,id',
                'unique:users,karyawan_id',
            ],

            'id_peran' => [
                'required',
                'integer',

                Rule::exists(
                    'peran',
                    'id'
                )->where(function ($query) {

                    $query
                        ->whereIn('kode_peran', [
                            'ADMIN',
                            'TU_ESELON_II',
                        ])
                        ->where('aktif', true);

                }),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

        ], [

            'karyawan_id.required' =>
                'Karyawan wajib dipilih.',

            'karyawan_id.unique' =>
                'Karyawan tersebut sudah memiliki akun.',

            'id_peran.required' =>
                'Role wajib dipilih.',

            'id_peran.exists' =>
                'Role tidak valid.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.unique' =>
                'Email sudah digunakan.',

            'password.required' =>
                'Password wajib diisi.',

            'password.min' =>
                'Password minimal 8 karakter.',

            'password.confirmed' =>
                'Konfirmasi password tidak sesuai.',

        ]);


        $karyawan = Karyawan::where(
            'aktif',
            true
        )->findOrFail(
            $validated['karyawan_id']
        );


        User::create([

            'karyawan_id' => $karyawan->id,

            'id_peran' => $validated['id_peran'],

            'name' => $karyawan->nama,

            'email' => $validated['email'],

            'password' => Hash::make(
                $validated['password']
            ),

        ]);


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Akun berhasil dibuat.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL USER
    |--------------------------------------------------------------------------
    */

    public function show(User $user)
    {
        abort(404);
    }


    /*
    |--------------------------------------------------------------------------
    | FORM EDIT USER
    |--------------------------------------------------------------------------
    */

    public function edit(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN tidak boleh diedit dari menu ini
        |--------------------------------------------------------------------------
        */

        abort_unless(

            $user->peran &&
            in_array(
                $user->peran->kode_peran,
                [
                    'KARYAWAN',
                    'ADMIN',
                    'TU_ESELON_II',
                ]
            ),

            404
        );


        $user->load([
            'karyawan.unit',
            'karyawan.subunitOrganisasi',
            'peran',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Role yang bisa dipilih
        |--------------------------------------------------------------------------
        */

        $roles = Peran::whereIn('kode_peran', [
            'KARYAWAN',
            'ADMIN',
            'TU_ESELON_II',
        ])
        ->where('aktif', true)
        ->orderBy('id')
        ->get();


        return view(
            'users.edit',
            compact(
                'user',
                'roles'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        User $user
    ) {

        /*
        |--------------------------------------------------------------------------
        | Hanya akun non-Super Admin
        |--------------------------------------------------------------------------
        */

        abort_unless(

            $user->peran &&
            in_array(
                $user->peran->kode_peran,
                [
                    'KARYAWAN',
                    'ADMIN',
                    'TU_ESELON_II',
                ]
            ),

            404
        );


        $validated = $request->validate([

            'id_peran' => [
                'required',
                'integer',

                Rule::exists(
                    'peran',
                    'id'
                )->where(function ($query) {

                    $query
                        ->whereIn('kode_peran', [
                            'KARYAWAN',
                            'ADMIN',
                            'TU_ESELON_II',
                        ])
                        ->where('aktif', true);

                }),
            ],

            'email' => [
                'required',
                'email',
                'max:255',

                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

        ], [

            'id_peran.required' =>
                'Role wajib dipilih.',

            'id_peran.exists' =>
                'Role tidak valid.',

            'email.required' =>
                'Email wajib diisi.',

            'email.email' =>
                'Format email tidak valid.',

            'email.unique' =>
                'Email sudah digunakan.',

            'password.min' =>
                'Password minimal 8 karakter.',

            'password.confirmed' =>
                'Konfirmasi password tidak sesuai.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Data yang diubah
        |--------------------------------------------------------------------------
        */

        $data = [

            'id_peran' => $validated['id_peran'],

            'email' => $validated['email'],

        ];


        /*
        |--------------------------------------------------------------------------
        | Password hanya diubah jika diisi
        |--------------------------------------------------------------------------
        */

        if (
            !empty(
                $validated['password']
            )
        ) {

            $data['password'] =
                Hash::make(
                    $validated['password']
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Nama tetap mengikuti data Karyawan
        |--------------------------------------------------------------------------
        */

        if ($user->karyawan) {

            $data['name'] =
                $user->karyawan->nama;

        }


        $user->update($data);


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Akun berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS USER
    |--------------------------------------------------------------------------
    */

    public function destroy(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Super Admin tidak boleh dihapus dari menu ini
        |--------------------------------------------------------------------------
        */

        abort_unless(

            $user->peran &&
            in_array(
                $user->peran->kode_peran,
                [
                    'KARYAWAN',
                    'ADMIN',
                    'TU_ESELON_II',
                ]
            ),

            404
        );


        $user->delete();


        return redirect()
            ->route('users.index')
            ->with(
                'success',
                'Akun berhasil dihapus.'
            );
    }
}