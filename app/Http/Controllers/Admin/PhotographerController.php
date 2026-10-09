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
        $validated = $request->validate($this->rules(), $this->messages());

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
        $photographer->update($validated);

        return redirect()->route('admin.photographers.index')->with('success', 'Data fotografer berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $photographer = Photographer::findOrFail($id);
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
        ];
    }

    private function messages(): array
    {
        return [
            'phone.regex' => 'Nomor telepon tidak valid. Gunakan angka saja, contoh: 081234567890.',
        ];
    }
}
