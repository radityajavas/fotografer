@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Data Paket Foto</h2>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Tambah Paket -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tambah Paket Foto Baru</h3>
        <form action="{{ route('admin.packages.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Paket</label>
                    <input type="text" name="name" class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500" placeholder="misal: Platinum" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500" placeholder="misal: 500000" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Durasi (Jam)</label>
                    <input type="number" name="duration_hours" class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500" placeholder="misal: 3" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi Singkat</label>
                    <input type="text" name="description" class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500" placeholder="misal: Pernikahan" required>
                </div>
            </div>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                Simpan Paket
            </button>
        </form>
    </div>

    <!-- Tabel Data Paket -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                        <th class="p-4">No</th>
                        <th class="p-4">Nama Paket</th>
                        <th class="p-4">Harga</th>
                        <th class="p-4">Durasi</th>
                        <th class="p-4">Deskripsi</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($packages as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-gray-500">{{ $index + 1 }}</td>
                        <td class="p-4 font-semibold text-gray-800">{{ $item->name }}</td>
                        <td class="p-4 text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="p-4 text-gray-600">{{ $item->duration_hours }} Jam</td>
                        <td class="p-4 text-gray-600">{{ $item->description }}</td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.packages.edit', $item->id) }}" class="px-3 py-1 text-xs font-medium text-amber-700 bg-amber-100 rounded-md hover:bg-amber-200 transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.packages.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
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
                        <td colspan="6" class="p-6 text-center text-gray-400">Belum ada data paket foto.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection