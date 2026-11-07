<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nip.required' => 'NIP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::with([
            'karyawan',
            'peran',
        ])
        ->where('email', $credentials['email'])
        ->whereHas('karyawan', function ($query) use ($credentials) {
            $query->where('nip', $credentials['nip'])
                  ->where('email', $credentials['email'])
                  ->where('aktif', true);
        })
        ->first();

        if (
            !$user ||
            !$user->peran ||
            !$user->peran->aktif ||
            !Hash::check($credentials['password'], $user->password)
        ) {
            return back()
                ->withErrors([
                    'login' => 'Email, NIP, atau password tidak sesuai.',
                ])
                ->withInput(
                    $request->only('email', 'nip')
                );
        }

        Auth::login($user);

$request->session()->regenerate();

if ($user->peran->kode_peran === 'SUPER_ADMIN') {
    return redirect()->route('dashboard');
}

if ($user->peran->kode_peran === 'ADMIN') {
    return redirect()->route('admin.dashboard');
}

return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}