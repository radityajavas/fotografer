@extends('layouts.app') 
@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Pemesanan Saya</h1>
        <p class="text-sm text-gray-500">Pantau status konfirmasi pesanan dan hubungi fotografer di sini.</p>
    </div>

    <div class="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="p-4">Fotografer</th>
                        <th class="p-4">Paket</th>
                        <th class="p-4">Tanggal Booking</th>
                        <th class="p-4">Status Konfirmasi</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 font-medium text-gray-900">
                                {{ $booking->photographer->name ?? 'Fotografer Tidak Ditemukan' }}
                            </td>
                            <td class="p-4 text-gray-600">
                                {{ $booking->package->name ?? '-' }}
                            </td>
                            <td class="p-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                            </td>
                            <td class="p-4">
                                @if($booking->status == 'pending')
                                    <span class="inline-flex items-center gap-1.5 bg-yellow-50 text-yellow-700 border border-yellow-200 text-xs px-3 py-1 rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                        Menunggu Konfirmasi
                                    </span>
                                @elseif($booking->status == 'approved')
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs px-3 py-1 rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Diterima / Disetujui
                                    </span>
                                @elseif($booking->status == 'rejected')
                                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 border border-red-200 text-xs px-3 py-1 rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Ditolak
                                    </span>
                                @elseif($booking->status == 'completed')
                                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200 text-xs px-3 py-1 rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full font-medium">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('chat.show', $booking->id) }}" 
                                   class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-200 text-xs px-3 py-1.5 rounded-lg font-medium transition">
                                    💬 Chat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">
                                Kamu belum memiliki riwayat pemesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection