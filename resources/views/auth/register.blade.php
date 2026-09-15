@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl border border-gray-100 shadow-xl">
        
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Buat Akun Baru</h2>
            <p class="mt-2 text-sm text-gray-500">
                Bergabunglah dengan <span class="font-semibold text-indigo-600">Cocofonder</span>
            </p>
        </div>

        <form class="mt-8 space-y-4" action="{{ route('register') }}" method="POST">
            @csrf

            <!-- Nama -->
            <div>
                <label for="name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    placeholder="Nama Lengkap">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Alamat Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    placeholder="nama@email.com">
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Kata Sandi</label>
                <input id="password" name="password" type="password" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    placeholder="••••••••">
                @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password-confirm" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Konfirmasi Kata Sandi</label>
                <input id="password-confirm" name="password_confirmation" type="password" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    placeholder="••••••••">
            </div>

            <!-- Tombol Register -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="text-center text-xs text-gray-500 border-t border-gray-100 pt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 ml-1">
                Masuk di sini
            </a>
        </div>

    </div>
</div>
@endsection