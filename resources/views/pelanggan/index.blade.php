@extends('layouts.admin')

@section('title', 'Data Pelanggan')

@section('content')
<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Daftar Pelanggan</h3>
            <p class="text-xs text-gray-500 mt-1">Kelola data pelanggan yang terdaftar di platform Cocofonder.</p>
        </div>
        <a href="#" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs px-4 py-2.5 rounded-xl font-semibold transition inline-flex items-center gap-1.5 shadow-sm">
            <span>+</span> Input Pelanggan
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-600 font-semibold text-xs uppercase">
                <tr>
                    <th class="p-4 rounded-l-xl">Nama</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">No HP</th>
                    <th class="p-4">Alamat</th>
                    <th class="p-4 text-center rounded-r-xl">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse($pelanggan ?? [] as $item)
                <tr class="hover:bg-gray-50/80 transition">
                    <td class="p-4 font-semibold text-gray-800">{{ $item->nama ?? $item->name }}</td>
                    <td class="p-4 text-gray-600">{{ $item->email }}</td>
                    <td class="p-4 text-gray-600">{{ $item->no_hp ?? '-' }}</td>
                    <td class="p-4 text-gray-500">{{ $item->alamat ?? '-' }}</td>
                    <td class="p-4 text-center space-x-1">
                        <button class="bg-amber-500 hover:bg-amber-600 text-white text-xs px-3 py-1.5 rounded-lg font-medium transition">Edit</button>
                        <button class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg font-medium transition">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-400">Belum ada data pelanggan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection