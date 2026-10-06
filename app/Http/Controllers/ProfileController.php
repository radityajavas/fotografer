<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updatePhoto(Request $request)
    {
        // Memastikan yang dikirim memang file gambar
        // max:2048 = ukuran maksimal 2 MB
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Ambil user yang sedang login
        $user = auth()->user();

        // Ambil file foto yang dipilih user
        $photo = $request->file('photo');

        // Simpan foto ke folder storage/app/public/profile
        // Hasilnya berupa nama file yang disimpan ke database
        $photoPath = $photo->store('profile', 'public');

        // Simpan lokasi foto ke kolom photo milik user
        $user->update([
            'photo' => $photoPath,
        ]);

        // Kembali ke halaman sebelumnya
        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}