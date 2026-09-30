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

            <!-- Nomor Telepon -->
            <div>
                <label for="phone" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nomor Telepon</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    placeholder="08xxxxxxxxxx">
                @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Alamat -->
            <div>
                <label for="address" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Alamat</label>
                <textarea id="address" name="address" rows="3" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                @error('address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
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
                Masuk di sinia@extends('layouts.app')

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

            <!-- Nomor Telepon -->
            <div>
                <label for="phone" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nomor Telepon</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    placeholder="08xxxxxxxxxx">
                @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Alamat -->
            <div>
                <label for="address" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Alamat</label>
                <textarea id="address" name="address" rows="3" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                @error('address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Kata Sandi</label>
                <div class="relative">
                    <input id="password" name="password" type="password" required
                        class="w-full px-4 py-2.5 pr-12 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword('password', this)"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600"
                        aria-label="Tampilkan atau sembunyikan kata sandi">
                        <svg class="icon-show w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="icon-hide w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.1A9.8 9.8 0 0112 5c6 0 9.5 7 9.5 7a15.6 15.6 0 01-3.2 4.1M6.6 6.7A15.7 15.7 0 002.5 12s3.5 7 9.5 7c1.6 0 3-.4 4.3-1M9.9 9.9a3 3 0 004.2 4.2"/>
                        </svg>
                    </button>
                </div>
                @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label for="password-confirm" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Konfirmasi Kata Sandi</label>
                <div class="relative">
                    <input id="password-confirm" name="password_confirmation" type="password" required
                        class="w-full px-4 py-2.5 pr-12 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        placeholder="••••••••">
                    <button type="button" onclick="togglePassword('password-confirm', this)"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600"
                        aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi">
                        <svg class="icon-show w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg class="icon-hide w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.1A9.8 9.8 0 0112 5c6 0 9.5 7 9.5 7a15.6 15.6 0 01-3.2 4.1M6.6 6.7A15.7 15.7 0 002.5 12s3.5 7 9.5 7c1.6 0 3-.4 4.3-1M9.9 9.9a3 3 0 004.2 4.2"/>
                        </svg>
                    </button>
                </div>
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

<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        btn.querySelector('.icon-show').classList.toggle('hidden', !showing);
        btn.querySelector('.icon-hide').classList.toggle('hidden', showing);
    }
</script>
@endsection 
            </a>
        </div>

    </div>
</div>
@endsection