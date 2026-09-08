@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl border border-gray-100 shadow-xl">
        
        <!-- Header Form -->
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang</h2>
            <p class="mt-2 text-sm text-gray-500">
                Silakan masuk ke akun <span class="font-semibold text-indigo-600">Cocofonder</span> Anda
            </p>
        </div>

        <!-- Form Login -->
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <!-- Input Email / Username -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                        Alamat Email / Username
                    </label>
                    <input id="email" name="email" type="text" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('email') border-red-500 @enderror"
                        placeholder="nama@email.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                        Kata Sandi
                    </label>
                    <input id="password" name="password" type="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition @error('password') border-red-500 @enderror"
                        placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-600">
                        Ingat Saya
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500 text-xs">
                        Lupa Kata Sandi?
                    </a>
                @endif
            </div>

            <!-- Tombol Submit -->
            <div>
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    Masuk Sekarang
                </button>
            </div>
        </form>

        <!-- Footer Link Register -->
        <div class="text-center text-xs text-gray-500 border-t border-gray-100 pt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 ml-1">
                Daftar di sini
            </a>
        </div>

    </div>
</div>
@endsection