@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Booking & Laporan Statistik</h1>
        <p class="text-sm text-gray-500">Kelola status pesanan masuk dan ringkasan transaksi platform.</p>
    </div>

    <!-- Ringkasan Laporan & Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 uppercase">Total Pemesanan</span>
            <p class="text-2xl font-black text-gray-800 mt-1">{{ $stats['total_booking'] }}</p>
        </div>
        <div class="bg-amber-50 p-5 rounded-2xl border border-amber-100 shadow-sm">
            <span class="text-xs font-semibold text-amber-600 uppercase">Menunggu Konfirmasi</span>
            <p class="text-2xl font-black text-amber-700 mt-1">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100 shadow-sm">
            <span class="text-xs font-semibold text-emerald-600 uppercase">Diterima / Disetujui</span>
            <p class="text-2xl font-black text-emerald-700 mt-1">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-indigo-50 p-5 rounded-2xl border border-indigo-100 shadow-sm">
            <span class="text-xs font-semibold text-indigo-600 uppercase">Total Pendapatan</span>
            <p class="text-xl font-black text-indigo-700 mt-1">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Tabel Daftar Booking -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Daftar Transaksi Booking</h3>
            
            <!-- Filter Status -->
            <div class="flex space-x-2">
                <a href="{{ route('admin.bookings.index') }}" class="px-3 py-1 text-xs rounded-lg border font-medium">Semua</a>
                <a href="{{ route('admin.bookings.index', ['status' => 'menunggu']) }}" class="px-3 py-1 text-xs rounded-lg bg-amber-50 text-amber-600 font-medium">Menunggu</a>
                <a href="{{ route('admin.bookings.index', ['status' => 'diterima']) }}" class="px-3 py-1 text-xs rounded-lg bg-emerald-50 text-emerald-600 font-medium">Diterima</a>
            </div>
        </div>

        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs font-semibold">
                <tr>
                    <th class="px-6 py-3">Pemesan</th>
                    <th class="px-6 py-3">Fotografer</th>
                    <th class="px-6 py-3">Tanggal Foto</th>
                    <th class="px-6 py-3">Total Harga</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $b)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $b->user->name ?? 'Pelanggan' }}</td>
                    <td class="px-6 py-4">{{ $b->photographer->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">Rp {{ number_format($b->total_harga, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full 
                            {{ $b->status === 'diterima' ? 'bg-emerald-100 text-emerald-700' : '' }}
                            {{ $b->status === 'menunggu' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $b->status === 'ditolak' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($b->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-1">
                        <form action="{{ route('admin.bookings.updateStatus', $b->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="diterima">
                            <button type="submit" class="px-3 py-1 bg-emerald-600 text-white text-xs rounded-md font-medium hover:bg-emerald-700">Setujui</button>
                        </form>
                        <form action="{{ route('admin.bookings.updateStatus', $b->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="ditolak">
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs rounded-md font-medium hover:bg-red-700">Tolak</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada data pemesanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 