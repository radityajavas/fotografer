@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative bg-indigo-900 text-white py-24 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4">
            Abadikan Momen Berharga Bersama Fotografer Terbaik
        </h1>
        <p class="text-indigo-200 text-lg mb-8">
            Cari, bandingkan portofolio, dan pesan fotografer profesional di kotamu dalam hitungan menit.
        </p>

        <!-- Floating Search Form -->
        <div class="bg-white p-3 rounded-2xl shadow-xl text-gray-800 flex flex-col md:flex-row gap-3">
            <div class="flex-1 text-left px-3 py-1 border-b md:border-b-0 md:border-r border-gray-200">
                <label class="block text-xs font-semibold text-gray-400 uppercase">Lokasi</label>
                <input type="text" placeholder="Misal: Surabaya, Malang" class="w-full focus:outline-none text-sm font-medium">
            </div>
            <div class="flex-1 text-left px-3 py-1 border-b md:border-b-0 md:border-r border-gray-200">
                <label class="block text-xs font-semibold text-gray-400 uppercase">Kategori</label>
                <select class="w-full focus:outline-none text-sm font-medium bg-transparent">
                    <option value="">Semua Kategori</option>
                    <option value="wedding">Wedding / Prewedding</option>
                    <option value="portrait">Portrait / Modeling</option>
                    <option value="event">Event / Konser</option>
                    <option value="product">Produk UMKM</option>
                </select>
            </div>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-8 py-3 rounded-xl transition">
                Cari Fotografer
            </button>
        </div>
    </div>
</div>

<!-- Featured Photographers Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Fotografer Populer</h2>
            <p class="text-gray-500 text-sm">Pilihan terbaik dengan ulasan tertinggi dari klien</p>
        </div>
        <a href="#" class="text-indigo-600 font-medium hover:underline text-sm">Lihat Semua &rarr;</a>
    </div>

    <!-- Grid Card Fotografer -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition">
            <div class="h-48 bg-gray-200 relative">
                <img src="https://images.unsplash.com/photo-1537633552985-df8429e8048b?q=80&w=600" class="w-full h-full object-cover">
                <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-xs font-bold px-2,5 py-1 rounded-full text-gray-800">
                    ★ 4.9 (32)
                </span>
            </div>
            <div class="p-5">
                <div class="flex items-center space-x-3 mb-3">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100" class="w-10 h-10 rounded-full object-cover">
                    <div>
                        <h3 class="font-bold text-gray-900 leading-snug">Rian Studio</h3>
                        <p class="text-xs text-gray-500">Surabaya &bull; Prewedding, Event</p>
                    </div>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-gray-50">
                    <div>
                        <span class="text-xs text-gray-400">Mulai dari</span>
                        <p class="text-sm font-bold text-indigo-600">Rp 450.000.000.000.00 <span class="text-xs text-gray-500 font-normal">/ jam</span></p>
                    </div>
                    <a href="#" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 rounded-lg text-xs font-semibold text-gray-700">Profil</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection