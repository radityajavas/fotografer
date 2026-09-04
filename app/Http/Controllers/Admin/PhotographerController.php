<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Photographer;
use Illuminate\Http\Request;

class PhotographerController extends Controller
{
    public function index()
    {
        $photographers = Photographer::latest()->get();
        return view('admin.photographers.index', compact('photographers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:50',
            'specialization' => 'required|string|max:255',
            'status'         => 'required|in:AVAILABLE,UNAVAILABLE',
        ]);

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
        // Validasi disesuaikan: phone dibatasi hingga 50 karakter agar angka panjang tidak ditolak
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:50',
            'specialization' => 'required|string|max:255',
            'status'         => 'required|in:AVAILABLE,UNAVAILABLE',
        ]);

        $photographer = Photographer::findOrFail($id);
        $photographer->update($validated);

        return redirect()->route('admin.photographers.index')->with('success', 'Data fotografer berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $photographer = Photographer::findOrFail($id);
        $photographer->delete();

        return redirect()->back()->with('success', 'Data fotografer berhasil dihapus!');
    }
}