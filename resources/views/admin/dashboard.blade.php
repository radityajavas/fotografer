@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Admin Cocofonder</h1>

    <!-- Grid Cards Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-500">Total Fotografer</p>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">12</h2>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-500">Booking Aktif</p>
            <h2 class="text-3xl font-bold text-indigo-600 mt-2">24</h2>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-500">Manajemen Jadwal</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">8 Agenda</h2>
        </div>
    </div>
</div>
@endsection