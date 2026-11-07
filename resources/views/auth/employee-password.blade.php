<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Buat Password | HumasIn</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;

            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f6f8f7;

            font-family: 'Inter', sans-serif;
        }

        .container {
            width: 100%;
            max-width: 430px;

            padding: 20px;
        }

        .card {
            background: #ffffff;

            border: 1px solid #edf0ee;

            border-radius: 18px;

            padding: 34px;

            box-shadow:
                0 8px 30px rgba(30, 50, 40, 0.06);
        }

        .brand {
            text-align: center;

            font-size: 26px;
            font-weight: 750;

            letter-spacing: -0.8px;
        }

        .brand span {
            color: #6EAE78;
        }

        .title {
            margin-top: 28px;

            text-align: center;

            font-size: 20px;
            font-weight: 700;
        }

        .description {
            margin-top: 8px;

            text-align: center;

            font-size: 12px;
            line-height: 1.6;

            color: #90999f;
        }

        .employee-info {
            margin-top: 22px;

            padding: 14px;

            border-radius: 10px;

            background: #f5f9f6;
        }

        .employee-name {
            font-size: 13px;
            font-weight: 650;

            color: #333333;
        }

        .employee-unit {
            margin-top: 4px;

            font-size: 10px;

            color: #89938d;
        }

        .form-group {
            margin-top: 19px;
        }

        label {
            display: block;

            margin-bottom: 7px;

            font-size: 12px;
            font-weight: 600;

            color: #444444;
        }

        input {
            width: 100%;

            height: 44px;

            padding: 0 13px;

            border: 1px solid #e2e6e4;

            border-radius: 9px;

            outline: none;

            font-family: inherit;
            font-size: 13px;
        }

        input:focus {
            border-color: #6EAE78;
        }

        .hint {
            margin-top: 7px;

            font-size: 10px;

            color: #9aa1a6;
        }

        .error {
            margin-top: 16px;

            padding: 11px 13px;

            border-radius: 9px;

            background: #fceeee;

            color: #b95c5c;

            font-size: 11px;
        }

        .button {
            width: 100%;

            height: 44px;

            margin-top: 24px;

            border: none;

            border-radius: 9px;

            background: #6EAE78;

            color: #ffffff;

            font-family: inherit;

            font-size: 13px;
            font-weight: 600;

            cursor: pointer;
        }

        .button:hover {
            background: #5e9e69;
        }

    </style>

</head>


<body>

<div class="container">

    <div class="card">

        <div class="brand">
            Humas<span>In</span>
        </div>

        <div class="title">
            Buat Password
        </div>

        <div class="description">
            Buat password untuk mengaktifkan akun
            HumasIn Anda.
        </div>


        <div class="employee-info">

            <div class="employee-name">
                {{ $karyawan->nama }}
            </div>

            <div class="employee-unit">

                {{ $karyawan->unit->nama_unit ?? '-' }}

                @if($karyawan->subunitOrganisasi)
                    · {{ $karyawan->subunitOrganisasi->nama_subunit }}
                @endif

            </div>

        </div>


        @if($errors->any())

            <div class="error">

                {{ $errors->first() }}

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('activation.password.store') }}"
        >

            @csrf


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    required
                >

                <div class="hint">
                    Gunakan minimal 8 karakter.
                </div>

            </div>


            <div class="form-group">

                <label for="password_confirmation">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password"
                    required
                >

            </div>


            <button
                type="submit"
                class="button"
            >
                Buat Password &amp; Aktifkan Akun
            </button>

        </form>

    </div>

</div>

</body>

</html>