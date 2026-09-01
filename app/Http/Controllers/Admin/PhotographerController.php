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
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string|max:255',
        ]);

        Photographer::create($request->all());

        return redirect()->back()->with('success', 'Data fotografer berhasil ditambahkan!');
    }

    public function update(Request $request, Photographer $photographer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string|max:255',
            'status' => 'required|in:available,busy',
        ]);

        $photographer->update($request->all());

        return redirect()->back()->with('success', 'Data fotografer berhasil diperbarui!');
    }

    public function destroy(Photographer $photographer)
    {
        $photographer->delete();
        return redirect()->back()->with('success', 'Data fotografer berhasil dihapus!');
    }
}