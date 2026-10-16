<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotographerController extends Controller
{
    public function index()
    {
        $photographers = Photographer::latest()->get();
        return view('admin.photographers.index', compact('photographers'));
    }

    public function show($id)
    {
        $photographer = Photographer::with('photos')->findOrFail($id);
        return view('admin.photographers.show', compact('photographer'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        // Proses upload foto profil baru jika ada
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('photographer-photos', 'public');
        }

        Photographer::create($validated);

        return redirect()->back()->with('success', 'Data fotografer berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $photographer = Photographer::findOrFail($id);
        return view('admin.photographers.edit', compact('photographer'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        $photographer = Photographer::findOrFail($id);

        // Proses jika ada upload foto baru saat diedit
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada di storage
            if ($photographer->photo && Storage::disk('public')->exists($photographer->photo)) {
                Storage::disk('public')->delete($photographer->photo);
            }
            // Simpan foto baru
            $validated['photo'] = $request->file('photo')->store('photographer-photos', 'public');
        }

        $photographer->update($validated);

        return redirect()->route('admin.photographers.index')->with('success', 'Data fotografer berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $photographer = Photographer::findOrFail($id);

        // Hapus file foto dari storage jika ada saat data dihapus
        if ($photographer->photo && Storage::disk('public')->exists($photographer->photo)) {
            Storage::disk('public')->delete($photographer->photo);
        }

        $photographer->delete();

        return redirect()->back()->with('success', 'Data fotografer berhasil dihapus!');
    }

    private function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'min:3', 'max:255'],
            'phone'          => ['required', 'string', 'regex:/^\+?[0-9][0-9\s\-]{7,18}$/'],
            'city'           => ['required', 'string', 'max:100'],
            'specialization' => ['required', 'string', 'max:255'],
            'status'         => ['required', 'in:AVAILABLE,UNAVAILABLE'],
            'photo'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // Validasi file foto profil
        ];
    }

    private function messages(): array
    {
        return [
            'phone.regex' => 'Nomor telepon tidak valid. Gunakan angka saja, contoh: 081234567890.',
            'photo.image' => 'File yang diunggah harus berupa gambar.',
            'photo.mimes' => 'Format foto harus berjenis jpeg, png, jpg, atau webp.',
            'photo.max'   => 'Ukuran foto maksimal adalah 2 MB.',
        ];
    }
}
