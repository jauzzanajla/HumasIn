@extends('layouts.app')

@section('title', 'Ganti Password')
@section('page-title', 'Ganti Password')
@section('page-description', 'Perbarui password akun Anda')

@section('content')

<div style="max-width:600px;">

    <div class="card" style="padding:28px;">

        <form
            action="{{ route('profile.password.update') }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="form-group">

                <label class="form-label">
                    Password Lama
                </label>

                <input
                    type="password"
                    name="password_lama"
                    class="form-control"
                    placeholder="Masukkan password lama"
                >

                @error('password_lama')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="form-group">

                <label class="form-label">
                    Password Baru
                </label>

                <input
                    type="password"
                    name="password_baru"
                    class="form-control"
                    placeholder="Minimal 8 karakter"
                >

                @error('password_baru')
                    <div class="error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="form-group">

                <label class="form-label">
                    Konfirmasi Password Baru
                </label>

                <input
                    type="password"
                    name="password_baru_confirmation"
                    class="form-control"
                    placeholder="Ulangi password baru"
                >

            </div>

            <div style="
                display:flex;
                justify-content:flex-end;
                gap:10px;
                margin-top:25px;
            ">

                <a
                    href="{{ route('profile.index') }}"
                    class="btn btn-light"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Simpan Password
                </button>

            </div>

        </form>

    </div>

</div>

@endsection