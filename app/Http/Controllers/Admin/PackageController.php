<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        // Memanggil model Package menggunakan namespace lengkap secara langsung
        $packages = \App\Models\Package::latest()->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration_hours' => 'required|integer',
            'description' => 'required|string',
        ]);

        \App\Models\Package::create($request->all());

        return redirect()->back()->with('success', 'Paket foto berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $package = \App\Models\Package::findOrFail($id);
        $package->delete();
        return redirect()->back()->with('success', 'Paket foto berhasil dihapus!');
    }
}