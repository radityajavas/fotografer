@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Form Pemesanan Fotografer</h2>

        <form action="{{ route('booking.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Fotografer Yang Dipilih -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Fotografer</label>
                <input type="hidden" name="photographer_id" value="{{ $selectedPhotographer->id ?? '' }}">
                <input type="text" readonly value="{{ $selectedPhotographer->name ?? 'Fotografer Tidak Ditemukan' }}" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700">
            </div>

            <!-- Pilih Paket -->
            <div>
                <label for="package_id" class="block text-xs font-semibold text-gray-700 uppercase mb-2">Pilih Paket</label>
                <select name="package_id" id="package_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Paket Fotografi --</option>
                    @foreach($packages as $pkg)
                        <option value="{{ $pkg->id }}">{{ $pkg->name ?? $pkg->nama_paket }} - Rp {{ number_format($pkg->price ?? $pkg->harga ?? 0, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tanggal Booking -->
            <div>
                <label for="booking_date" class="block text-xs font-semibold text-gray-700 uppercase mb-2">Tanggal Pelaksanaan</label>
                <input type="date" name="booking_date" id="booking_date" required class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-sm transition">
                Konfirmasi Booking
            </button>
        </form>
    </div>
</div>
@endsection