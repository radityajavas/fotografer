<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Message;
use App\Models\Package;
use App\Models\Photographer;
use App\Models\PhotographerPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerFlowSmokeTest extends TestCase
{
    use RefreshDatabase;

    private function customer(): User
    {
        return User::create([
            'name' => 'Pelanggan Uji',
            'username' => 'pelanggan_uji',
            'email' => 'uji@example.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);
    }

    private function photographer(): Photographer
    {
        return Photographer::create([
            'name' => 'Fotografer Uji',
            'phone' => '081234567890',
            'city' => 'Malang',
            'specialization' => 'Wedding',
            'status' => 'available',
        ]);
    }

    private function package(): Package
    {
        return Package::create([
            'name' => 'Paket Uji',
            'description' => 'Deskripsi paket uji',
            'price' => 1000000,
            'duration_hours' => 2,
        ]);
    }

    public function test_homepage_tidak_lagi_menampilkan_section_paket(): void
    {
        $pkg = $this->package();
        $this->photographer();

        $this->get('/')
            ->assertOk()
            ->assertDontSee($pkg->name)
            ->assertDontSee('Pesan Sekarang')
            ->assertDontSee('modalPesan')
            // Section lain & menu navbar tetap ada
            ->assertSee('Temukan fotografer untuk momenmu')
            ->assertSee('href="' . route('paket.index') . '"', false)
            ->assertSee('href="' . route('chat.start') . '"', false);
    }

    public function test_halaman_paket_tersedia_dan_menampilkan_semua_paket(): void
    {
        $this->photographer();
        $pkg = $this->package();

        $this->get('/paket')
            ->assertOk()
            ->assertSee('Paket Fotografi')
            ->assertSee($pkg->name)
            ->assertSee('Rp 1.000.000')
            ->assertSee('Durasi 2 jam')
            ->assertSee('Deskripsi paket uji')
            ->assertSee('Pesan Sekarang')
            ->assertSee('data-paket-id="' . $pkg->id . '"', false)
            ->assertSee('action="' . route('booking.create') . '"', false)
            ->assertSee('name="package_id"', false);
    }

    public function test_navbar_punya_menu_paket_fotografi(): void
    {
        $this->get('/paket')
            ->assertOk()
            ->assertSee('href="' . route('paket.index') . '"', false)
            ->assertSee('Paket Fotografi');
    }

    public function test_form_booking_membawa_paket_dari_url(): void
    {
        $p = $this->photographer();
        $pkg = $this->package();

        $response = $this->actingAs($this->customer())
            ->get('/booking/create?photographer_id=' . $p->id . '&package_id=' . $pkg->id)
            ->assertOk()
            ->assertSee('Pesan fotografer')
            ->assertSee('packageData', false)
            ->assertSee('modalKonfirmasi')
            ->assertViewHas('selectedPackageId', $pkg->id)
            ->assertViewHas('packageData');

        // Paket dari URL harus benar-benar terpilih (selected) di dropdown
        $this->assertMatchesRegularExpression(
            '/<option value="' . $pkg->id . '"\s+data-duration="2"\s+selected>/',
            $response->getContent()
        );
    }

    public function test_homepage_menautkan_profil_setiap_fotografer(): void
    {
        $p = $this->photographer();

        $this->get('/')
            ->assertOk()
            ->assertSee('href="' . route('photographers.show', $p->id) . '"', false)
            ->assertSee('Profil')
            ->assertSee('Lihat profil ' . $p->name, false);
    }

    public function test_profil_fotografer_menampilkan_info_dan_fotonya(): void
    {
        $p = $this->photographer();
        PhotographerPhoto::create([
            'photographer_id' => $p->id,
            'path' => 'photographer-photos/contoh.jpg',
            'kategori' => 'Wedding',
        ]);

        $this->get('/photographer/' . $p->id)
            ->assertOk()
            ->assertSee($p->name)
            ->assertSee($p->phone)
            ->assertSee($p->city)
            ->assertSee($p->specialization)
            ->assertSee('storage/photographer-photos/contoh.jpg', false)
            ->assertSee('Kembali ke Beranda')
            ->assertDontSee('Kembali ke Galeri')
            ->assertSee('href="' . route('landing') . '"', false)
            ->assertSee('href="' . route('chat.start', ['photographer_id' => $p->id]) . '"', false)
            ->assertSee('repeat(auto-fill, minmax(220px, 1fr))', false);
    }

    public function test_chat_dari_profil_langsung_ke_percakapan_fotografer_itu(): void
    {
        $user = $this->customer();
        $fotografer = $this->photographer();
        $fotograferLain = Photographer::create([
            'name' => 'Fotografer Lain',
            'phone' => '081298765432',
            'city' => 'Batu',
            'specialization' => 'Wisuda',
            'status' => 'available',
        ]);
        $pkg = $this->package();

        $bookingFotografer = Booking::create([
            'user_id' => $user->id,
            'photographer_id' => $fotografer->id,
            'package_id' => $pkg->id,
            'booking_date' => now()->addDays(3)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'lokasi' => 'Jl. Contoh Nomor 10, Malang',
            'status' => 'menunggu',
        ]);

        // Chat dari profil fotografer yang punya booking -> langsung ke percakapannya
        $this->actingAs($user)->get('/chat?photographer_id=' . $fotografer->id)
            ->assertRedirect(route('chat.show', $bookingFotografer->id));

        // Fotografer lain tanpa booking -> kembali ke profilnya (bukan booking palsu)
        $this->actingAs($user)->get('/chat?photographer_id=' . $fotograferLain->id)
            ->assertRedirect(route('photographers.show', $fotograferLain->id))
            ->assertSessionHas('error');

        $this->assertSame(1, Booking::count());
    }

    public function test_chat_dari_profil_guest_diarahkan_ke_login(): void
    {
        $p = $this->photographer();

        $this->get('/chat?photographer_id=' . $p->id)
            ->assertRedirect('/login')
            ->assertSessionHas('url.intended', url('/chat?photographer_id=' . $p->id));
    }

    public function test_halaman_chat_kembali_ke_beranda_dan_quick_replies_tetap_ada(): void
    {
        $user = $this->customer();
        $p = $this->photographer();
        $pkg = $this->package();

        $booking = Booking::create([
            'user_id' => $user->id,
            'photographer_id' => $p->id,
            'package_id' => $pkg->id,
            'booking_date' => now()->addDays(3)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'lokasi' => 'Jl. Contoh Nomor 10, Malang',
            'status' => 'menunggu',
        ]);

        $this->actingAs($user)->get('/booking/' . $booking->id . '/chat')
            ->assertOk()
            ->assertSee('href="' . route('landing') . '"', false)
            ->assertSee('Kembali ke Beranda')
            ->assertDontSee('Kembali ke pesanan')
            ->assertSee('id="quick-replies"', false)
            ->assertSee('name="message"', false);
    }

    public function test_galeri_punya_tombol_kembali_ke_beranda(): void
    {
        $this->photographer();

        $this->get('/galeri')
            ->assertOk()
            ->assertSee('Kembali ke Beranda')
            ->assertSee('class="btn btn-brand-outline btn-sm px-3 d-inline-flex align-items-center gap-2"', false)
            ->assertSee('href="' . route('landing') . '"', false);
    }

    public function test_halaman_login_tanpa_lupa_kata_sandi_dan_tombol_teal(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertDontSee('Lupa kata sandi', false)
            ->assertDontSee('password.request', false)
            ->assertSee('class="btn btn-brand w-100">Masuk</button>', false)
            ->assertSee('class="btn btn-brand btn-sm px-3 ms-1">Daftar</a>', false);
    }

    public function test_login_tetap_berfungsi_dan_backend_reset_password_tetap_ada(): void
    {
        $user = $this->customer();

        // Halaman login masih bisa dipakai untuk masuk
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect();
        $this->assertAuthenticatedAs($user);

        // Mekanisme reset password di backend tidak dihapus
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('password.request'));
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('password.email'));
    }

    public function test_halaman_daftar_punya_tombol_teal_dan_tautan_masuk(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('class="btn btn-brand w-100">Daftar</button>', false)
            ->assertSee('class="btn btn-brand btn-sm px-3 ms-1">Masuk</a>', false);
    }

    public function test_tombol_chat_dan_kembali_memakai_brand_teal(): void
    {
        $user = $this->customer();
        $p = $this->photographer();
        $pkg = $this->package();

        // Tombol chat mengambang di beranda memakai class btn-brand (#0f766e), bukan warna lama
        $this->get('/')
            ->assertOk()
            ->assertSee('class="btn btn-brand d-inline-flex align-items-center gap-2 rounded-pill"', false);

        // Tombol kembali di profil, galeri, dan chat: outline teal + ke route landing
        $this->get('/photographer/' . $p->id)
            ->assertOk()
            ->assertSee('class="btn btn-brand-outline btn-sm px-3 d-inline-flex align-items-center gap-2"', false)
            ->assertSee('class="btn btn-brand btn-sm px-3">Chat</a>', false);

        $this->get('/galeri')
            ->assertOk()
            ->assertSee('class="btn btn-brand-outline btn-sm px-3 d-inline-flex align-items-center gap-2"', false);

        $booking = Booking::create([
            'user_id' => $user->id,
            'photographer_id' => $p->id,
            'package_id' => $pkg->id,
            'booking_date' => now()->addDays(3)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'lokasi' => 'Jl. Contoh Nomor 10, Malang',
            'status' => 'menunggu',
        ]);

        $this->actingAs($user)->get('/booking/' . $booking->id . '/chat')
            ->assertOk()
            ->assertSee('class="btn btn-brand-outline btn-sm px-3 mb-2"', false)
            ->assertSee('href="' . route('landing') . '"', false);
    }

    public function test_form_booking_menampilkan_profil_fotografer_yang_dipilih(): void
    {
        $p = $this->photographer();
        $lain = Photographer::create([
            'name' => 'Fotografer Lain',
            'phone' => '081298765432',
            'city' => 'Batu',
            'specialization' => 'Wisuda',
            'status' => 'available',
        ]);

        PhotographerPhoto::create([
            'photographer_id' => $p->id,
            'path' => 'photographer-photos/profil.jpg',
            'kategori' => 'Wedding',
        ]);
        PhotographerPhoto::create([
            'photographer_id' => $p->id,
            'path' => 'photographer-photos/karya.jpg',
            'kategori' => 'Wedding',
        ]);

        $this->actingAs($user = $this->customer())
            ->get('/booking/create?photographer_id=' . $p->id)
            ->assertOk()
            ->assertSee('class="photographer-card"', false)
            ->assertSee($p->name)
            ->assertDontSee($lain->name)
            ->assertSee($p->specialization)
            ->assertSee($p->city)
            ->assertSee('2 foto portofolio siap dilihat')
            ->assertSee('Lihat Profil')
            ->assertSee('href="' . route('photographers.show', ['id' => $p->id, 'return_to' => 'booking']) . '"', false)
            ->assertSee('storage/photographer-photos/', false)
            ->assertSee('flex-direction: column')
            ->assertSee('name="photographer_id" value="' . $p->id . '"', false);

        // Fotografer tanpa foto: tampil inisial, bukan foto fotografer lain
        $this->actingAs($user)
            ->get('/booking/create?photographer_id=' . $lain->id)
            ->assertOk()
            ->assertSee($lain->name)
            ->assertSee('photographer-avatar-fallback', false)
            ->assertDontSee('foto portofolio siap dilihat')
            ->assertSee('href="' . route('photographers.show', ['id' => $lain->id, 'return_to' => 'booking']) . '"', false);
    }

    public function test_kembali_dari_profil_ke_form_booking_membawa_fotografer_dan_paket(): void
    {
        $user = $this->customer();
        $p = $this->photographer();
        $pkg = $this->package();

        $this->actingAs($user)
            ->get('/photographer/' . $p->id . '?return_to=booking&package_id=' . $pkg->id)
            ->assertOk()
            ->assertSee('Kembali ke Pemesanan')
            ->assertSee('href="' . str_replace('&', '&amp;', route('booking.create', [
                'photographer_id' => $p->id,
                'package_id' => $pkg->id,
            ])) . '"', false)
            ->assertDontSee('Kembali ke Beranda')
            ->assertDontSee('Kembali ke Galeri');
    }

    public function test_kembali_dari_profil_mengikuti_asal_galeri_atau_beranda(): void
    {
        $p = $this->photographer();

        $this->get('/photographer/' . $p->id . '?return_to=galeri')
            ->assertOk()
            ->assertSee('Kembali ke Galeri')
            ->assertSee('href="' . route('galeri') . '"', false)
            ->assertDontSee('Kembali ke Pemesanan');

        // Tanpa parameter (mis. dari beranda) tetap kembali ke beranda
        $this->get('/photographer/' . $p->id)
            ->assertOk()
            ->assertSee('Kembali ke Beranda')
            ->assertSee('href="' . route('landing') . '"', false)
            ->assertDontSee('Kembali ke Pemesanan');

        // Guest yang mengakses ?return_to=booking tidak diberi tombol ke form booking
        $this->get('/photographer/' . $p->id . '?return_to=booking')
            ->assertOk()
            ->assertSee('Kembali ke Beranda')
            ->assertDontSee('Kembali ke Pemesanan');
    }

    public function test_alur_lihat_profil_dan_kembali_ke_pemesanan_membuka_form_yang_sama(): void
    {
        $user = $this->customer();
        $p = $this->photographer();
        $pkg = $this->package();

        // 1. Form booking memuat tautan Lihat Profil dengan asal yang jelas
        $form = $this->actingAs($user)
            ->get('/booking/create?photographer_id=' . $p->id . '&package_id=' . $pkg->id)
            ->assertOk();

        $this->assertMatchesRegularExpression(
            '#/photographer/' . $p->id . '\?return_to=booking&amp;package_id=' . $pkg->id . '#',
            $form->getContent()
        );

        // 2. Profil menampilkan tombol Kembali ke Pemesanan
        $profil = $this->actingAs($user)
            ->get('/photographer/' . $p->id . '?return_to=booking&package_id=' . $pkg->id)
            ->assertOk()
            ->assertSee('Kembali ke Pemesanan')
            ->assertDontSee('Kembali ke Beranda');

        preg_match('#href="([^"]*/booking/create[^"]*)"#', $profil->getContent(), $m);
        $this->assertNotEmpty($m, 'Tautan Kembali ke Pemesanan tidak ditemukan pada profil.');

        // 3. Ikuti tautan: form booking terbuka lagi dengan fotografer & paket yang sama
        $this->actingAs($user)
            ->get(html_entity_decode($m[1]))
            ->assertOk()
            ->assertSee('name="photographer_id" value="' . $p->id . '"', false)
            ->assertSee('value="' . $pkg->id . '"', false);
    }

    public function test_kembali_ke_pemesanan_tetap_berfungsi_jika_parameter_hilang(): void
    {
        $user = $this->customer();
        $p = $this->photographer();
        $this->package();

        // Profil dibuka dari form booking tetapi parameter asal tidak ikut terbawa
        $this->actingAs($user)
            ->from('/booking/create?photographer_id=' . $p->id)
            ->get('/photographer/' . $p->id)
            ->assertOk()
            ->assertSee('Kembali ke Pemesanan')
            ->assertSee('href="' . route('booking.create', ['photographer_id' => $p->id]) . '"', false)
            ->assertDontSee('Kembali ke Beranda');
    }

    public function test_galeri_menautkan_profil_dengan_asal_galeri_dan_paket_punya_tombol_beranda_berwarna(): void
    {
        $p = $this->photographer();
        $this->package();

        $this->get('/galeri')
            ->assertOk()
            ->assertSee('href="' . route('photographers.show', ['id' => $p->id, 'return_to' => 'galeri']) . '"', false);

        $this->get('/paket')
            ->assertOk()
            ->assertSee('class="btn btn-brand btn-sm px-3 text-decoration-none">Beranda</a>', false)
            ->assertSee('href="' . route('landing') . '"', false);
    }

    public function test_tombol_batal_selalu_mengarah_ke_beranda(): void
    {
        $user = $this->customer();
        $p = $this->photographer();
        $this->package();

        $formUrl   = '/booking/create?photographer_id=' . $p->id;
        $profilUrl = route('photographers.show', $p->id);
        $batal     = 'href="' . route('landing') . '" class="btn btn-cancel">Batal</a>';

        // Dari halaman paket
        $this->actingAs($user)->from('/paket')->get($formUrl)
            ->assertOk()
            ->assertSee($batal, false);

        // Dari profil fotografer (referer yang dulu memicu bug)
        $this->actingAs($user)->from($profilUrl)->get($formUrl)
            ->assertOk()
            ->assertSee($batal, false)
            ->assertDontSee('href="' . $profilUrl . '" class="btn btn-cancel">Batal</a>', false)
            ->assertDontSee('href="' . route('bookings.my') . '" class="btn btn-cancel">Batal</a>', false);

        // Dibuka langsung tanpa referer
        $this->actingAs($user)->get($formUrl)
            ->assertOk()
            ->assertSee($batal, false);
    }

    public function test_form_booking_di_tengah_dan_tetap_responsif(): void
    {
        $p = $this->photographer();
        $this->package();

        $this->actingAs($this->customer())
            ->get('/booking/create?photographer_id=' . $p->id)
            ->assertOk()
            ->assertSee('max-width: 546px; margin: 0 auto', false)
            ->assertSee('@media (max-width: 480px)', false)
            ->assertSee('name="booking_date"', false)
            ->assertSee('name="start_time"', false)
            ->assertSee('name="lokasi"', false);
    }

    public function test_booking_tersimpan_dan_muncul_di_riwayat(): void
    {
        $user = $this->customer();
        $p = $this->photographer();
        $pkg = $this->package();

        $this->actingAs($user)->post('/booking', [
            'photographer_id' => $p->id,
            'package_id' => $pkg->id,
            'booking_date' => now()->addDays(7)->toDateString(),
            'start_time' => '10:00',
            'lokasi' => 'Jl. Contoh Nomor 10, Malang',
        ])->assertRedirect(route('bookings.my'));

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertSame('10:00', substr($booking->start_time, 0, 5));
        $this->assertSame('12:00', substr($booking->end_time, 0, 5));
        $this->assertSame('menunggu', $booking->status);

        $this->actingAs($user)->get('/my-bookings')
            ->assertOk()
            ->assertSee('10.00–12.00')
            ->assertSee('Menunggu');
    }

    public function test_jam_di_luar_operasional_ditolak_server(): void
    {
        $p = $this->photographer();
        $pkg = $this->package();

        $this->actingAs($this->customer())->post('/booking', [
            'photographer_id' => $p->id,
            'package_id' => $pkg->id,
            'booking_date' => now()->addDays(7)->toDateString(),
            'start_time' => '19:00',
            'lokasi' => 'Jl. Contoh Nomor 10, Malang',
        ])->assertSessionHasErrors('start_time');

        $this->assertSame(0, Booking::count());
    }

    public function test_chat_guest_diarahkan_ke_login(): void
    {
        $this->get('/chat')->assertRedirect('/login');
    }

    public function test_chat_pelanggan_satu_booking_langsung_ke_percakapan(): void
    {
        $user = $this->customer();
        $p = $this->photographer();
        $pkg = $this->package();

        $booking = Booking::create([
            'user_id' => $user->id,
            'photographer_id' => $p->id,
            'package_id' => $pkg->id,
            'booking_date' => now()->addDays(3)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'lokasi' => 'Jl. Contoh Nomor 10, Malang',
            'status' => 'menunggu',
        ]);

        $this->actingAs($user)->get('/chat')->assertRedirect(route('chat.show', $booking->id));
    }

    public function test_chat_pelanggan_tanpa_booking_diarahkan_ke_halaman_paket(): void
    {
        $this->actingAs($this->customer())->get('/chat')
            ->assertRedirect(route('paket.index'))
            ->assertSessionHas('error');
    }

    public function test_chat_langsung_membuka_percakapan_yang_sudah_ada(): void
    {
        $user = $this->customer();
        $p = $this->photographer();
        $pkg = $this->package();

        // Booking lama yang sudah punya percakapan
        $denganPesan = Booking::create([
            'user_id' => $user->id,
            'photographer_id' => $p->id,
            'package_id' => $pkg->id,
            'booking_date' => now()->addDays(3)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'lokasi' => 'Jl. Contoh Nomor 10, Malang',
            'status' => 'menunggu',
        ]);
        Message::create([
            'booking_id' => $denganPesan->id,
            'sender_id' => $user->id,
            'message' => 'Halo admin',
        ]);

        // Booking lebih baru tetapi belum punya percakapan
        Booking::create([
            'user_id' => $user->id,
            'photographer_id' => $p->id,
            'package_id' => $pkg->id,
            'booking_date' => now()->addDays(5)->toDateString(),
            'start_time' => '13:00',
            'end_time' => '15:00',
            'lokasi' => 'Jl. Contoh Nomor 10, Malang',
            'status' => 'menunggu',
        ]);

        // Tombol Chat harus langsung membuka percakapan (bukan riwayat)
        $this->actingAs($user)->get('/chat')
            ->assertRedirect(route('chat.show', $denganPesan->id));

        // Halaman chat memakai form & quick replies yang sudah ada
        $this->actingAs($user)->get('/booking/' . $denganPesan->id . '/chat')
            ->assertOk()
            ->assertSee('Chat dengan admin')
            ->assertSee('id="quick-replies"', false)
            ->assertSee('name="message"', false)
            ->assertSee('Kirim');
    }

    public function test_pelanggan_tidak_bisa_membuka_chat_booking_orang_lain(): void
    {
        $pemilik = $this->customer();
        $lain = User::create([
            'name' => 'Pelanggan Lain',
            'username' => 'pelanggan_lain',
            'email' => 'lain@example.com',
            'role' => 'customer',
            'password' => bcrypt('password'),
        ]);
        $p = $this->photographer();
        $pkg = $this->package();

        $booking = Booking::create([
            'user_id' => $pemilik->id,
            'photographer_id' => $p->id,
            'package_id' => $pkg->id,
            'booking_date' => now()->addDays(3)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'lokasi' => 'Jl. Contoh Nomor 10, Malang',
            'status' => 'menunggu',
        ]);

        $this->actingAs($lain)->get('/booking/' . $booking->id . '/chat')->assertNotFound();
    }
}
