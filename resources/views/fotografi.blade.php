<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LENSA ABADI - Jasa Fotografer Profesional</title>
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: {
                            100: '#FAF6F0',
                            200: '#F5EFE6',
                            300: '#E8DFD8',
                        },
                        sage: {
                            500: '#5C6B5E',
                            600: '#4A584C',
                        },
                        darkness: '#1E2320',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-cream-100 text-darkness font-sans antialiased">

 <!-- NAVBAR -->
<header class="bg-cream-100 border-b border-cream-300/50 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-2 font-bold text-xl tracking-wide">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-sage-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h0.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <circle cx="12" cy="13" r="3" stroke-width="2" />
            </svg>
            <span>Lensa Abadi</span>
        </div>
        
        <nav class="hidden md:flex space-x-8 text-sm font-medium text-gray-700">
            <a href="#home" class="hover:text-sage-600 transition">Home</a>
            <a href="#services" class="hover:text-sage-600 transition">Services</a>
            <a href="#portfolio" class="hover:text-sage-600 transition">Portfolio</a>
            <a href="#contact" class="hover:text-sage-600 transition">Contact</a>
        </nav>

        <!-- CONTAINER UNTUK DUA TOMBOL TANPA MERUBAH BENTUK -->
        <div class="flex items-center space-x-1">
            <a href="#contact" class="bg-sage-500 hover:bg-sage-600 text-white text-xs px-5 py-2.5 rounded-full font-medium transition shadow-sm">
                Book Now
            </a>
            <a href="#contact" class="bg-sage-500 hover:bg-sage-600 text-white text-xs px-5 py-2.5 rounded-full font-medium transition shadow-sm">
                Register/Login
            </a>
        </div>

    </div>
</header>

    <!-- HERO SECTION -->
    <section id="home" class="max-w-6xl mx-auto px-6 py-12 md:py-20 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <h1 class="text-4xl md:text-5xl font-extrabold text-darkness leading-tight tracking-tight">
                LENSA ABADI
            </h1>
            <p class="text-lg text-gray-600 font-medium">
                Jasa Fotografer Profesional
            </p>
            <div>
                <a href="#portfolio" class="inline-block bg-sage-500 hover:bg-sage-600 text-white px-6 py-3 rounded-full text-sm font-medium transition shadow-md">
                    Lihat Galeri
                </a>
            </div>
        </div>
        <div class="relative">
            <img src="https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&q=80&w=800" 
                 alt="Wedding Photography" 
                 class="rounded-2xl shadow-xl w-full object-cover h-[350px] md:h-[420px]">
        </div>
    </section>

    <!-- LAYANAN KAMI -->
    <section id="services" class="py-16 bg-cream-200">
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-12">Layanan Kami</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <img src="https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&q=80&w=500" alt="Fotografi Pernikahan" class="w-full h-48 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="font-bold text-lg mb-2">1. Fotografi Pernikahan</h3>
                        <p class="text-gray-500 text-xs leading-relaxed mb-4">Mendokumentasikan momen paling berharga dalam hidup Anda dengan gaya sinematik dan elegan.</p>
                        <a href="#contact" class="text-sage-600 font-semibold text-xs border-b border-sage-600 pb-0.5 hover:text-sage-500">Selengkapnya</a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <img src="https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&q=80&w=500" alt="Fotografi Produk" class="w-full h-48 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="font-bold text-lg mb-2">2. Fotografi Produk</h3>
                        <p class="text-gray-500 text-xs leading-relaxed mb-4">Tingkatkan nilai jual dan visual brand usaha Anda dengan foto produk berkualitas tinggi.</p>
                        <a href="#contact" class="text-sage-600 font-semibold text-xs border-b border-sage-600 pb-0.5 hover:text-sage-500">Selengkapnya</a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&q=80&w=500" alt="Fotografi Event" class="w-full h-48 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="font-bold text-lg mb-2">3. Fotografi Event</h3>
                        <p class="text-gray-500 text-xs leading-relaxed mb-4">Abadikan setiap momen seru dari acara penting, seminar, hingga perayaan ulang tahun.</p>
                        <a href="#contact" class="text-sage-600 font-semibold text-xs border-b border-sage-600 pb-0.5 hover:text-sage-500">Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- GALERI FOTO TERBAIK -->
    <section id="portfolio" class="py-16 max-w-6xl mx-auto px-6">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-bold mb-2">Galeri Foto Terbaik</h2>
            <p class="text-xs text-gray-500">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-4">
                <img src="https://images.unsplash.com/photo-1606800052052-a08af7148866?auto=format&fit=crop&q=80&w=600" class="rounded-xl w-full h-[320px] object-cover" alt="Gallery 1">
            </div>
            <div class="space-y-4">
                <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=600" class="rounded-xl w-full h-[152px] object-cover" alt="Gallery 2">
                <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&q=80&w=600" class="rounded-xl w-full h-[152px] object-cover" alt="Gallery 3">
            </div>
            <div class="space-y-4">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=600" class="rounded-xl w-full h-[320px] object-cover" alt="Gallery 4">
            </div>
        </div>
    </section>

    <!-- MENGAPA MEMILIH KAMI -->
    <section class="py-16 bg-cream-200">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-12">Mengapa Memilih Kami?</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="flex flex-col items-center space-y-3">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm">
                        💡
                    </div>
                    <h4 class="font-bold text-sm">Kreativitas</h4>
                    <p class="text-[11px] text-gray-500">Sudut pandang visual unik dan estetik.</p>
                </div>
                <div class="flex flex-col items-center space-y-3">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm">
                        💼
                    </div>
                    <h4 class="font-bold text-sm">Profesionalisme</h4>
                    <p class="text-[11px] text-gray-500">Tim berpengalaman & tim kerja solid.</p>
                </div>
                <div class="flex flex-col items-center space-y-3">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm">
                        🏷️
                    </div>
                    <h4 class="font-bold text-sm">Harga Terjangkau</h4>
                    <p class="text-[11px] text-gray-500">Paket fleksibel sesuai kebutuhan.</p>
                </div>
                <div class="flex flex-col items-center space-y-3">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm">
                        ⏱️
                    </div>
                    <h4 class="font-bold text-sm">Tepat Waktu</h4>
                    <p class="text-[11px] text-gray-500">Pengiriman hasil foto sesuai jadwal.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONI KLIEN -->
    <section class="py-16 max-w-6xl mx-auto px-6">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">Testimoni Klien</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-4">
                <img src="https://i.pravatar.cc/100?img=33" class="w-12 h-12 rounded-full mx-auto mb-3" alt="Client Avatar">
                <p class="text-xs text-gray-500 italic">"Hasil foto sangat memuaskan, detail warna sangat alami!"</p>
            </div>
            <div class="text-center p-4">
                <img src="https://i.pravatar.cc/100?img=47" class="w-12 h-12 rounded-full mx-auto mb-3" alt="Client Avatar">
                <p class="text-xs text-gray-500 italic">"Sangat ramah dan sabar saat mengarahkan gaya pernikahan kami."</p>
            </div>
            <div class="text-center p-4">
                <img src="https://i.pravatar.cc/100?img=12" class="w-12 h-12 rounded-full mx-auto mb-3" alt="Client Avatar">
                <p class="text-xs text-gray-500 italic">"Proses cepat dan pengerjaan foto produk tepat waktu!"</p>
            </div>
            <div class="text-center p-4">
                <img src="https://i.pravatar.cc/100?img=5" class="w-12 h-12 rounded-full mx-auto mb-3" alt="Client Avatar">
                <p class="text-xs text-gray-500 italic">"Sangat direkomendasikan untuk event besar kantor!"</p>
            </div>
        </div>
    </section>

    <!-- FOOTER / KONTAK & RESERVASI -->
    <footer id="contact" class="bg-darkness text-white py-12">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-10">
            <div>
                <h3 class="text-xl font-bold mb-6">Kontak & Reservasi</h3>
                <form action="#" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <input type="text" placeholder="Nama" class="w-full px-4 py-2 text-xs rounded-md bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-sage-500">
                    </div>
                    <div>
                        <input type="email" placeholder="Email" class="w-full px-4 py-2 text-xs rounded-md bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-sage-500">
                    </div>
                    <div>
                        <textarea rows="3" placeholder="Pesan" class="w-full px-4 py-2 text-xs rounded-md bg-gray-800 text-white border border-gray-700 focus:outline-none focus:border-sage-500"></textarea>
                    </div>
                    <button type="submit" class="bg-sage-500 hover:bg-sage-600 text-white text-xs px-6 py-2.5 rounded-md font-medium transition">
                        Kirim Pesan
                    </button>
                </form>
            </div>

            <div class="space-y-4 text-xs text-gray-300">
                <div class="w-full h-32 bg-gray-800 rounded-md overflow-hidden relative">
                    <!-- Placeholder Maps -->
                    <div class="absolute inset-0 flex items-center justify-center text-gray-500">
                        [ Lokasi Google Maps ]
                    </div>
                </div>
                <div class="space-y-2">
                    <p><strong>WhatsApp:</strong> +62 812-3456-7890</p>
                    <p><strong>Alamat:</strong> Malang, Jawa Timur, Indonesia</p>
                    <p><strong>Instagram:</strong> @lensaabadi.photo</p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>