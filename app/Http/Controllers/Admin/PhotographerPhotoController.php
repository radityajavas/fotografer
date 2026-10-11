<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photographer;
use App\Models\PhotographerPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotographerPhotoController extends Controller
{
    public function index($id)
    {
        $photographer = Photographer::with(['photos' => fn ($q) => $q->latest()])->findOrFail($id);

        return view('admin.photographers.photos', compact('photographer'));
    }

    public function store(Request $request, $id)
    {
        $photographer = Photographer::findOrFail($id);

        $request->validate([
            'photos'   => ['required', 'array', 'max:10'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'kategori' => ['nullable', 'string', 'max:50'],
        ], [
            'photos.required' => 'Pilih minimal satu foto.',
            'photos.max'      => 'Maksimal 10 foto sekali upload.',
            'photos.*.image'  => 'File harus berupa gambar.',
            'photos.*.mimes'  => 'Format foto harus jpg, jpeg, png, atau webp.',
            'photos.*.max'    => 'Ukuran tiap foto maksimal 2 MB.',
        ]);

        foreach ($request->file('photos') as $file) {
            $photographer->photos()->create([
                'path'     => $file->store('photographer-photos', 'public'),
                'kategori' => trim((string) $request->kategori) ?: null,
            ]);
        }

        return back()->with('success', 'Foto berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $photo = PhotographerPhoto::findOrFail($id);

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}