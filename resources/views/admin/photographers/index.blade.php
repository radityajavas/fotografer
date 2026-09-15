@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header Halaman -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Data Fotografer</h1>
        <p class="text-sm text-gray-500">Kelola daftar fotografer dan status ketersediaannya.</p>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Tambah Fotografer -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tambah Fotografer Baru</h2>
        
        <form action="{{ route('admin.photographers.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Fotografer</label>
                    <input type="text" name="name" placeholder="Nama Fotografer" class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="phone" placeholder="08xxxxxxxxxx" class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Spesialisasi</label>
                    <input type="text" name="specialization" placeholder="misal: Wedding / Portrait" class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 bg-white" required>
                        <option value="AVAILABLE">AVAILABLE</option>
                        <option value="UNAVAILABLE">UNAVAILABLE</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                Simpan
            </button>
        </form>
    </div>

    <!-- Tabel Data Fotografer -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                        <th class="p-4">No</th>
                        <th class="p-4">Nama</th>
                        <th class="p-4">No HP</th>
                        <th class="p-4">Spesialisasi</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($photographers as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-gray-500">{{ $index + 1 }}</td>
                        <td class="p-4 font-semibold text-gray-800">{{ $item->name }}</td>
                        <td class="p-4 text-gray-600">{{ $item->phone }}</td>
                        <td class="p-4 text-gray-600">{{ $item->specialization }}</td>
                        <td class="p-4">
                            @if($item->status == 'AVAILABLE')
                                <span class="px-2.5 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                    AVAILABLE
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold text-gray-700 bg-gray-200 rounded-full">
                                    UNAVAILABLE
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.photographers.edit', $item->id) }}" class="px-3 py-1 text-xs font-medium text-amber-700 bg-amber-100 rounded-md hover:bg-amber-200 transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.photographers.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus fotografer ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-400">Belum ada data fotografer.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection