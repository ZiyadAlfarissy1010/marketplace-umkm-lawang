<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_telp' => 'required|string',
            'alamat' => 'required|string',
            'password' => 'nullable|min:6|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi Foto
        ]);

        $data = $request->only('name', 'email', 'no_telp', 'alamat');

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Proses Upload Foto Profil
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            // Simpan foto baru di folder storage/app/public/foto_profil
            $data['foto'] = $request->file('foto')->store('foto_profil', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil akun berhasil diperbarui!');
    }
}