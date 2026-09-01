<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotographyController extends Controller
{
    public function index()
    {
        // Data Dummy / Data dari Database
        $services = [
            [
                'title' => '1. Fotografi Pernikahan',
                'description' => 'Mendokumentasikan momen paling berharga dalam hidup Anda dengan gaya sinematik dan elegan.',
                'image' => 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&q=80&w=500'
            ],
            [
                'title' => '2. Fotografi Produk',
                'description' => 'Tingkatkan nilai jual dan visual brand usaha Anda dengan foto produk berkualitas tinggi.',
                'image' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&q=80&w=500'
            ],
            [
                'title' => '3. Fotografi Event',
                'description' => 'Abadikan setiap momen seru dari acara penting, seminar, hingga perayaan ulang tahun.',
                'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&q=80&w=500'
            ]
        ];

        return view('fotografi', compact('services'));
    }
    

    public function storeContact(Request $request)
    {
        
        // Validasi form kontak
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'pesan' => 'required|string',
        ]);

        // Simpan ke database atau kirim email di sini
        
        return back()->with('success', 'Pesan Anda berhasil terkirim!');
    }
}