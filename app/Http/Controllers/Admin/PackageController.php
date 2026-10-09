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
        $validated = $request->validate($this->rules());

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
        $validated = $request->validate($this->rules($id));

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

    private function rules($ignoreId = null): array
    {
        return [
            'name'           => ['required', 'string', 'min:3', 'max:255',
                                 \Illuminate\Validation\Rule::unique('packages', 'name')->ignore($ignoreId)],
            'price'          => ['required', 'numeric', 'min:0', 'max:999999999'],
            'duration_hours' => ['required', 'integer', 'between:1,24'],
            'description'    => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }
}
