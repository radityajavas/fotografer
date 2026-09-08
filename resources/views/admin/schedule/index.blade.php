@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Jadwal Agenda Fotografer</h1>
        <p class="text-sm text-gray-500">Pantau jadwal pemotretan yang disetujui untuk mencegah *clash* atau *double booking*.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($photographers as $p)
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $p->name }}</h3>
            <p class="text-xs text-indigo-600 font-medium mb-4">{{ $p->specialization ?? 'Fotografer' }}</p>
            
            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Jadwal Terisi:</h4>
            <div class="space-y-2">
                @forelse($p->bookings as $b)
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 text-xs">
                    <p class="font-bold text-gray-800">📅 {{ \Carbon\Carbon::parse($b->booking_date)->format('d F Y') }}</p>
                    <p class="text-gray-500 mt-1">Klien: {{ $b->user->name ?? 'Pelanggan' }}</p>
                </div>
                @empty
                <p class="text-xs text-emerald-600 bg-emerald-50 p-3 rounded-xl font-medium">Jadwal kosong (Tersedia)</p>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection