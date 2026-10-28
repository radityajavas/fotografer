<?php

namespace App\Http\Controllers;

use App\Models\Photographer;
use Illuminate\Http\Request;
use App\Models\Package;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing', [
            'photographers' => Photographer::with(['photos' => fn($q) => $q->latest()])->get(),
            'categories'    => $this->categories(),
        ]);
    }

    public function gallery()
    {
        $photographers = Photographer::with('photos')->latest()->get();

        return view('galeri', compact('photographers'));
    }

    // Halaman khusus daftar paket fotografi
    public function packages()
    {
        return view('paket', [
            'packages'      => Package::orderBy('price')->get(),
            'photographers' => Photographer::orderBy('name')->get(),
        ]);
    }

    public function cari(Request $request)
    {
        $photographers = Photographer::with(['photos' => fn($q) => $q->latest()])->get();

        if ($request->filled('lokasi')) {
            $lokasi = mb_strtolower(trim($request->lokasi));
            $photographers = $photographers->filter(
                fn($p) => str_contains(mb_strtolower($p->city ?? ''), $lokasi)
            );
        }

        if ($request->filled('kategori')) {
            $kategori = mb_strtolower(trim($request->kategori));
            $photographers = $photographers->filter(function ($p) use ($kategori) {
                return collect(explode(',', $p->specialization ?? ''))
                    ->map(fn($s) => mb_strtolower(trim($s)))
                    ->contains($kategori);
            });
        }

        return view('landing', [
            'photographers' => $photographers->values(),
            'categories'    => $this->categories(),
        ]);
    }

    private function categories()
    {
        return Photographer::pluck('specialization')
            ->flatMap(fn($s) => explode(',', $s ?? ''))
            ->map(fn($s) => trim($s))
            ->filter()
            ->unique(fn($s) => mb_strtolower($s))
            ->sort()
            ->values();
    }
}
