<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Peran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeActivationController extends Controller
{
    public function show()
    {
        return view('auth.employee-activation');
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'nip' => ['required', 'string'],
            'email' => ['required', 'email'],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $karyawan = Karyawan::with([
            'unit',
            'subunitOrganisasi',
            'user',
        ])
        ->where('nip', $validated['nip'])
        ->where('email', $validated['email'])
        ->where('aktif', true)
        ->first();

        if (!$karyawan) {
            return back()
                ->withErrors([
                    'aktivasi' => 'Data NIP dan email tidak ditemukan atau tidak sesuai.'
                ])
                ->withInput();
        }

        // Kalau sudah punya akun
        if ($karyawan->user) {
            return back()
                ->withErrors([
                    'aktivasi' => 'Karyawan ini sudah memiliki akun. Silakan login.'
                ])
                ->withInput();
        }

        // Simpan ID karyawan sementara di session
        session([
            'employee_activation_id' => $karyawan->id,
        ]);

        return redirect()
            ->route('activation.password');
    }


    public function showPassword()
    {
        $karyawanId = session('employee_activation_id');

        if (!$karyawanId) {
            return redirect()
                ->route('activation.show');
        }

        $karyawan = Karyawan::with([
            'unit',
            'subunitOrganisasi',
        ])->find($karyawanId);

        if (!$karyawan || !$karyawan->aktif) {
            session()->forget('employee_activation_id');

            return redirect()
                ->route('activation.show')
                ->withErrors([
                    'aktivasi' => 'Data karyawan tidak dapat digunakan.'
                ]);
        }

        return view(
            'auth.employee-password',
            compact('karyawan')
        );
    }


    public function setPassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        $karyawanId = session('employee_activation_id');

        if (!$karyawanId) {
            return redirect()
                ->route('activation.show');
        }

        $karyawan = Karyawan::with('user')
            ->find($karyawanId);

        if (!$karyawan || !$karyawan->aktif) {
            session()->forget('employee_activation_id');

            return redirect()
                ->route('activation.show')
                ->withErrors([
                    'aktivasi' => 'Data karyawan tidak dapat digunakan.'
                ]);
        }

        // Cegah pembuatan akun ganda
        if ($karyawan->user) {
            session()->forget('employee_activation_id');

            return redirect()
                ->route('login')
                ->withErrors([
                    'login' => 'Karyawan sudah memiliki akun. Silakan login.'
                ]);
        }

        $role = Peran::where('kode_peran', 'KARYAWAN')
            ->where('aktif', true)
            ->first();

        if (!$role) {
            return back()->withErrors([
                'password' => 'Role Karyawan belum tersedia.'
            ]);
        }

        DB::transaction(function () use (
            $karyawan,
            $role,
            $request
        ) {

            User::create([
                'karyawan_id' => $karyawan->id,
                'id_peran' => $role->id,
                'name' => $karyawan->nama,
                'email' => $karyawan->email,
                'password' => Hash::make($request->password),
            ]);
        });

        session()->forget('employee_activation_id');

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Akun berhasil dibuat. Silakan login menggunakan email, NIP, dan password Anda.'
            );
    }
}