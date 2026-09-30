@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Profile Header -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex items-center space-x-5">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200" class="w-20 h-20 rounded-full object-cover">
            <div>
                <div class="flex items-center space-x-2">
                    <h1 class="text-2xl font-bold text-gray-900">Rian Studio</h1>
                    <span class="bg-blue-100 text-blue-600 text-xs font-bold px-2 py-0.5 rounded-full">Terverifikasi</span>
                </div>
                <p class="text-gray-500 text-sm mt-1">Spesialis Wedding, Portrait & Fashion Photographer</p>
                <p class="text-xs text-gray-400 mt-0.5">📍 Surabaya, Jawa Timur</p>
            </div>
        </div>
        <div class="flex gap-6 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6 text-center">
            <div>
                <p class="text-xs text-gray-400">Rating</p>
                <p class="text-lg font-bold text-gray-800">★ 4.9</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Selesai</p>
                <p class="text-lg font-bold text-gray-800">45 Sesi</p>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Portfolio Gallery -->
        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-xl font-bold text-gray-900">Portofolio Hasil Foto</h2>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <img src="https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=400" class="rounded-xl object-cover w-full h-48">
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=400" class="rounded-xl object-cover w-full h-48">
                <img src="https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?q=80&w=400" class="rounded-xl object-cover w-full h-48">
                <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?q=80&w=400" class="rounded-xl object-cover w-full h-48">
            </div>
        </div>

        <!-- Right Column: Sticky Booking Widget -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm sticky top-24">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Pesan Sesi Foto</h3>
                <p class="text-sm font-semibold text-indigo-600 mb-4">Rp 450.000 <span class="text-xs text-gray-400 font-normal">/ jam</span></p>

                <form action="#" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Acara</label>
                        <input type="date" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Durasi (Jam)</label>
                        <select class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-600">
                            <option value="1">1 Jam</option>
                            <option value="2">2 Jam</option>
                            <option value="4">Half Day (4 Jam)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Lokasi Pemotretan</label>
                        <input type="text" placeholder="Alamat / Nama tempat" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-600">
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-xl text-sm transition">
                        Lanjut ke Pembayaran
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection