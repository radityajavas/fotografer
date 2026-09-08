<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cocofonder - Platform Booking Fotografer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="text-2xl font-bold text-indigo-600 tracking-tight">Cocofonder.</a>
            
            <div class="hidden md:flex items-center space-x-6">
                <a href="#" class="text-gray-600 hover:text-indigo-600 font-medium">Cari Fotografer</a>
                <a href="#" class="text-gray-600 hover:text-indigo-600 font-medium">Kategori</a>
                <a href="#" class="text-gray-600 hover:text-indigo-600 font-medium">Jadi Partner</a>
            </div>

            <div class="flex items-center space-x-3">
                <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600">Masuk</a>
                <a href="#" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-20 py-10">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
            &copy; 2026 cocofonder. All rights reserved.
        </div>
    </footer>

</body>
</html>