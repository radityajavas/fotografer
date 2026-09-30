<?php

namespace App\Http\Controllers;

use App\Models\Photographer;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('landing', [
            'photographers' => Photographer::all(),
            'categories'    => $this->categories(),
            'cities'        => $this->cities(),
        ]);
    }

    public function cari(Request $request)
    {
        $photographers = Photographer::all();

        if ($request->filled('kota')) {
            $kota = mb_strtolower(trim($request->kota));
            $photographers = $photographers->filter(
                fn ($p) => mb_strtolower(trim($p->city ?? '')) === $kota
            );
        }

        if ($request->filled('kategori')) {
            $kategori = mb_strtolower(trim($request->kategori));
            $photographers = $photographers->filter(function ($p) use ($kategori) {
                return collect(explode(',', $p->specialization ?? ''))
                    ->map(fn ($s) => mb_strtolower(trim($s)))
                    ->contains($kategori);
            });
        }

        return view('landing', [
            'photographers' => $photographers->values(),
            'categories'    => $this->categories(),
            'cities'        => $this->cities(),
        ]);
    }

    private function categories()
    {
        return Photographer::pluck('specialization')
            ->flatMap(fn ($s) => explode(',', $s ?? ''))
            ->map(fn ($s) => trim($s))
            ->filter()
            ->unique(fn ($s) => mb_strtolower($s))
            ->sort()
            ->values();
    }

    private function cities()
    {
        return Photographer::pluck('city')
            ->map(fn ($c) => trim($c ?? ''))
            ->filter()
            ->unique(fn ($c) => mb_strtolower($c))
            ->sort()
            ->values();
    }
}