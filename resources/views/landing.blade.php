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

    <!-- Grid Card Fotografer (Dinamis dari Database) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($photographers as $item)
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="h-48 bg-gray-200 relative flex items-center justify-center">
                    <span class="text-gray-400 text-sm font-medium">Foto Profil Fotografer</span>
                    <span class="absolute top-3 right-3 px-2.5 py-1 text-xs font-bold rounded-full {{ $item->status == 'AVAILABLE' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $item->status }}
                    </span>
                </div>
                <div class="p-5">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center text-sm uppercase">
                            {{ substr($item->name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 leading-snug">{{ $item->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $item->phone }} &bull; {{ $item->specialization }}</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-50">
                        <div>
                            <span class="text-xs text-gray-400">Status</span>
                            <p class="text-xs font-semibold text-gray-700">{{ $item->status }}</p>
                        </div>
                        <a href="{{ route('booking.create', ['photographer_id' => $item->id]) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition">
                            Pesan
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-gray-100">
                <p class="text-gray-500 text-sm">Belum ada data fotografer yang tersedia.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection