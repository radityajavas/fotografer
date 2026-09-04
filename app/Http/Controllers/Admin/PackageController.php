<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = \App\Models\Package::latest()->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric',
            'duration_hours' => 'required|integer',
            'description'    => 'required|string',
        ]);

        \App\Models\Package::create($validated);

        return redirect()->back()->with('success', 'Paket foto berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $package = \App\Models\Package::findOrFail($id);
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric',
            'duration_hours' => 'required|integer',
            'description'    => 'required|string',
        ]);

        $package = \App\Models\Package::findOrFail($id);
        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Paket foto berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $package = \App\Models\Package::findOrFail($id);
        $package->delete();

        return redirect()->back()->with('success', 'Paket foto berhasil dihapus!');
    }
}