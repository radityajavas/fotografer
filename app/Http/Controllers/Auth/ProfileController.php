<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        return view('layouts.profil.index');
    }

    public function edit()
    {
        return view('layouts.profil.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable','regex:/^[0-9]{8,15}$/',],
            'address' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'phone')) {
            $user->phone = $validated['phone'] ?? null;
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'address')) {
            $user->address = $validated['address'] ?? null;
        }

        if ($request->hasFile('photo')) {
            $oldPhoto = $user->photo;
            $path = $request->file('photo')->store('profile-photos', 'public');

            $user->photo = $path;

            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }
        }

        $user->save();

        return redirect()
            ->route('profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = auth()->user();
        $oldPhoto = $user->photo;

        $path = $request->file('photo')->store('profile-photos', 'public');

        $user->photo = $path;
        $user->save();

        if ($oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()
            ->route('profile')
            ->with('success', 'Foto profil berhasil diperbarui!');
    }
}
