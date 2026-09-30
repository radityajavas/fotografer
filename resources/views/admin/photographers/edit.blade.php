@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header Halaman -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Data Fotografer</h1>
            <p class="text-sm text-gray-500">Ubah informasi fotografer di bawah ini.</p>
        </div>
        <a href="{{ route('admin.photographers.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
            Kembali
        </a>
    </div>

    <!-- Form Edit Fotografer -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('admin.photographers.update', $photographer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Fotografer</label>
                    <input type="text" name="name" value="{{ old('name', $photographer->name) }}" class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $photographer->phone) }}" class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Spesialisasi</label>
                    <input type="text" name="specialization" value="{{ old('specialization', $photographer->specialization) }}" class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status Ketersediaan</label>
                    <select name="status" class="w-full text-sm border border-gray-300 rounded-lg p-2.5 focus:ring-indigo-500 focus:border-indigo-500 bg-white" required>
                        <option value="AVAILABLE" {{ $photographer->status == 'AVAILABLE' ? 'selected' : '' }}>AVAILABLE</option>
                        <option value="UNAVAILABLE" {{ $photographer->status == 'UNAVAILABLE' ? 'selected' : '' }}>UNAVAILABLE</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.photographers.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection