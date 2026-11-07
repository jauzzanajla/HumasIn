<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna.
     */
    public function index()
    {
        $user = Auth::user();

        if (! $user instanceof \App\Models\User) {
            abort(401);
        }

        $user = $user->load([
            'karyawan.unit',
            'karyawan.subunitOrganisasi',
            'peran',
        ]);

        return view('profile.index', compact('user'));
    }

    /**
     * Menampilkan halaman edit data diri.
     */
    public function edit()
    {
        $user = Auth::user();

        if (! $user instanceof \App\Models\User) {
            abort(401);
        }

        $user = $user->load([
            'karyawan.unit',
            'karyawan.subunitOrganisasi',
            'peran',
        ]);

        return view('profile.edit', compact('user'));
    }

    /**
     * Memperbarui data diri pengguna.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $karyawan = $user->karyawan;

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('karyawan', 'email')->ignore($karyawan?->id),
            ],
            'jabatan' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        if ($karyawan) {
            $karyawan->update([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'jabatan' => $validated['jabatan'] ?? null,
            ]);
        }

        $user->update([
            'name' => $validated['nama'],
            'email' => $validated['email'],
        ]);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Data diri berhasil diperbarui.');
    }

    /**
     * Memperbarui foto profil pengguna.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'foto_profil' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], [
            'foto_profil.required' => 'Foto profil wajib dipilih.',
            'foto_profil.image' => 'File harus berupa gambar.',
            'foto_profil.mimes' => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'foto_profil.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user = Auth::user();
        $fotoProfil = $user->getAttribute('foto_profil');

        if ($fotoProfil && Storage::disk('public')->exists($fotoProfil)) {
            Storage::disk('public')->delete($fotoProfil);
        }

        $path = $request->file('foto_profil')->store('profile', 'public');

        $user->update([
            'foto_profil' => $path,
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Menghapus foto profil pengguna.
     */
    public function destroyPhoto()
    {
        $user = Auth::user();
        $fotoProfil = $user->getAttribute('foto_profil');

        if ($fotoProfil && Storage::disk('public')->exists($fotoProfil)) {
            Storage::disk('public')->delete($fotoProfil);
        }

        $user->update([
            'foto_profil' => null,
        ]);

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Menampilkan halaman ganti password.
     */
    public function editPassword()
    {
        return view('profile.password');
    }

    /**
     * Memperbarui password pengguna.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password_lama' => ['required', 'current_password:web'],
            'password_baru' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password_lama.required' => 'Password lama wajib diisi.',
            'password_lama.current_password' => 'Password lama tidak sesuai.',
            'password_baru.required' => 'Password baru wajib diisi.',
            'password_baru.min' => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $user = Auth::user();

        $user->update([
            'password' => Hash::make($validated['password_baru']),
        ]);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Password berhasil diubah.');
    }
}