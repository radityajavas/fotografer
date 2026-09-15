<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cocofonder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <a href="/" class="text-xl font-bold text-indigo-600 tracking-tight">Cocofonder.</a>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-6 text-sm font-medium text-gray-600">
                    <a href="/" class="hover:text-indigo-600 transition">Beranda</a>
                    <a href="#" class="hover:text-indigo-600 transition">Cari Fotografer</a>
                    <a href="#" class="hover:text-indigo-600 transition">Kategori</a>

                    {{-- Admin Links --}}
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <div class="h-4 w-[1px] bg-gray-200 my-auto"></div>
                            <a href="{{ route('admin.photographers.index') }}" class="hover:text-indigo-600 transition">Fotografer</a>
                            <a href="{{ route('admin.packages.index') }}" class="hover:text-indigo-600 transition">Paket</a>
                            <a href="{{ route('admin.bookings.index') }}" class="hover:text-indigo-600 transition">Booking</a>
                            <a href="{{ route('admin.schedule.index') }}" class="hover:text-indigo-600 transition">Jadwal</a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right Profile / Auth -->
            <div class="flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">Daftar</a>
                @else
                    <div class="flex items-center space-x-3 text-sm">
                        <span class="text-gray-500">Halo, <span class="font-semibold text-gray-800">{{ auth()->user()->name }}</span></span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-md hover:bg-red-100 transition">
                                Keluar
                            </button>
                        </form>
                    </div>
                @endguest
            </div>

        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-20 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-gray-400">
            &copy; 2026 Cocofonder.
        </div>
    </footer>

</body>
</html>