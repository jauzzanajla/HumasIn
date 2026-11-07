@extends('layouts.app')

@section('content')

<div style="
    max-width:700px;
    margin:0 auto;
    padding:28px;
">

    <h2 style="margin-bottom:6px;">
        Edit Akun User
    </h2>

    <p style="
        color:#777;
        margin-bottom:24px;
    ">
        Ubah role, email, atau password akun pengguna.
    </p>


    {{-- ========================================================= --}}
    {{-- ERROR --}}
    {{-- ========================================================= --}}

    @if ($errors->any())

        <div style="
            background:#fff1f0;
            color:#b42318;
            padding:12px 16px;
            border-radius:8px;
            margin-bottom:20px;
        ">

            <ul style="
                margin:0;
                padding-left:20px;
            ">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FORM --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route('users.update', $user) }}"
        method="POST"
    >

        @csrf

        @method('PUT')


        {{-- ===================================================== --}}
        {{-- INFORMASI KARYAWAN --}}
        {{-- ===================================================== --}}

        <div style="
            margin-bottom:22px;
            padding:15px;
            background:#f7f8f7;
            border:1px solid #e5e7e5;
            border-radius:8px;
        ">

            <div style="
                font-weight:600;
                margin-bottom:12px;
            ">
                Informasi Karyawan
            </div>


            <div style="
                margin-bottom:8px;
            ">

                <span style="color:#777;">
                    Nama:
                </span>

                <strong>
                    {{ $user->karyawan?->nama ?? $user->name ?? '-' }}
                </strong>

            </div>


            <div style="
                margin-bottom:8px;
            ">

                <span style="color:#777;">
                    NIP:
                </span>

                {{ $user->karyawan?->nip ?? '-' }}

            </div>


            <div style="
                margin-bottom:8px;
            ">

                <span style="color:#777;">
                    Unit:
                </span>

                {{ $user->karyawan?->unit?->nama_unit ?? '-' }}

            </div>


            @if($user->karyawan?->subunitOrganisasi)

                <div>

                    <span style="color:#777;">
                        Subunit:
                    </span>

                    {{ $user->karyawan->subunitOrganisasi->nama_subunit }}

                </div>

            @endif

        </div>


        {{-- ===================================================== --}}
        {{-- ROLE --}}
        {{-- ===================================================== --}}

        <div style="
            margin-bottom:18px;
        ">

            <label
                for="id_peran"
                style="
                    display:block;
                    margin-bottom:7px;
                "
            >
                Role
            </label>


            <select
                name="id_peran"
                id="id_peran"
                required
                style="
                    width:100%;
                    padding:11px;
                    border:1px solid #ddd;
                    border-radius:8px;
                "
            >

                @foreach ($roles as $role)

                    <option
                        value="{{ $role->id }}"
                        @selected(
                            old(
                                'id_peran',
                                $user->id_peran
                            )
                            ==
                            $role->id
                        )
                    >
                        {{ $role->nama_peran }}
                    </option>

                @endforeach

            </select>


            <small style="
                display:block;
                margin-top:6px;
                color:#777;
            ">
                Role menentukan hak akses pengguna di HumasIn.
            </small>

        </div>


        {{-- ===================================================== --}}
        {{-- EMAIL --}}
        {{-- ===================================================== --}}

        <div style="
            margin-bottom:18px;
        ">

            <label
                for="email"
                style="
                    display:block;
                    margin-bottom:7px;
                "
            >
                Email Login
            </label>


            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email', $user->email) }}"
                required
                style="
                    width:100%;
                    padding:11px;
                    border:1px solid #ddd;
                    border-radius:8px;
                "
            >

        </div>


        {{-- ===================================================== --}}
        {{-- PASSWORD --}}
        {{-- ===================================================== --}}

        <div style="
            margin-bottom:18px;
        ">

            <label
                for="password"
                style="
                    display:block;
                    margin-bottom:7px;
                "
            >
                Password Baru
            </label>


            <input
                type="password"
                name="password"
                id="password"
                placeholder="Kosongkan jika tidak ingin mengganti password"
                style="
                    width:100%;
                    padding:11px;
                    border:1px solid #ddd;
                    border-radius:8px;
                "
            >


            <small style="color:#777;">
                Isi minimal 8 karakter jika ingin mengganti password.
            </small>

        </div>


        {{-- ===================================================== --}}
        {{-- KONFIRMASI PASSWORD --}}
        {{-- ===================================================== --}}

        <div style="
            margin-bottom:24px;
        ">

            <label
                for="password_confirmation"
                style="
                    display:block;
                    margin-bottom:7px;
                "
            >
                Konfirmasi Password Baru
            </label>


            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                style="
                    width:100%;
                    padding:11px;
                    border:1px solid #ddd;
                    border-radius:8px;
                "
            >

        </div>


        {{-- ===================================================== --}}
        {{-- BUTTON --}}
        {{-- ===================================================== --}}

        <div style="
            display:flex;
            gap:10px;
        ">

            <a
                href="{{ route('users.index') }}"
                style="
                    padding:11px 18px;
                    border:1px solid #ddd;
                    border-radius:8px;
                    text-decoration:none;
                    color:#333;
                "
            >
                Batal
            </a>


            <button
                type="submit"
                style="
                    padding:11px 18px;
                    border:none;
                    border-radius:8px;
                    background:#263b30;
                    color:white;
                    cursor:pointer;
                "
            >
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection