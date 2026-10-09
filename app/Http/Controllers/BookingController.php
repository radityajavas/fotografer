<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photographer;
use App\Models\Package;
use App\Models\Booking;
use App\Models\Schedule;

class BookingController extends Controller
{
    // Menampilkan form booking
    public function create(Request $request)
    {
        $photographerId = $request->query('photographer_id');
        $selectedPhotographer = Photographer::find($photographerId);

        if (! $selectedPhotographer) {
            return redirect()->route('landing')->with('error', 'Fotografer tidak ditemukan.');
        }

        $packages = Package::orderBy('price')->get();
        $areas    = $selectedPhotographer->serviceAreas();

        $offDates = Schedule::where('photographer_id', $selectedPhotographer->id)
            ->whereDate('date', '>=', today())
            ->orderBy('date')
            ->get()
            ->map(fn ($s) => $s->date->format('Y-m-d'))
            ->values();

        return view('booking.create', compact('selectedPhotographer', 'packages', 'offDates', 'areas'));
    }

    // Memproses simpan booking
    public function store(Request $request)
    {
        $request->validate([
            'photographer_id' => ['required', 'exists:photographers,id'],
            'package_id'      => ['required', 'exists:packages,id'],
            'booking_date'    => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . today()->addYears(2)->toDateString()],
            'kota'            => ['required', 'string', 'max:100'],
            'lokasi'          => ['required', 'string', 'min:10', 'max:400'],
        ], [
            'lokasi.min'                  => 'Alamat terlalu singkat, tulis minimal 10 karakter (jalan, nomor, patokan).',
            'booking_date.before_or_equal' => 'Tanggal pelaksanaan maksimal 2 tahun dari sekarang.',
        ]);

        $photographer = Photographer::findOrFail($request->photographer_id);

        // 0. Fotografer harus sedang menerima pesanan
        if (strtoupper($photographer->status) !== 'AVAILABLE') {
            return back()
                ->withErrors(['photographer_id' => 'Fotografer ini sedang tidak menerima pesanan.'])
                ->withInput();
        }

        // 1. Lokasi hanya boleh di kota yang dijangkau fotografer
        $areas = collect($photographer->serviceAreas());
        $kota  = $areas->first(fn ($a) => mb_strtolower($a) === mb_strtolower(trim($request->kota)));

        if (! $kota) {
            return back()
                ->withErrors(['kota' => 'Fotografer hanya melayani: ' . ($areas->implode(', ') ?: 'belum ada wilayah layanan') . '.'])
                ->withInput();
        }

        // 2. Fotografer libur di tanggal itu
        $libur = Schedule::where('photographer_id', $photographer->id)
            ->whereDate('date', $request->booking_date)
            ->exists();

        if ($libur) {
            return back()
                ->withErrors(['booking_date' => 'Fotografer tidak tersedia pada tanggal ini.'])
                ->withInput();
        }

        // 3. Tanggal itu sudah dipesan (sudah diterima admin)
        $sudahDipesan = Booking::where('photographer_id', $photographer->id)
            ->whereDate('booking_date', $request->booking_date)
            ->whereIn('status', ['diterima', 'confirmed'])
            ->exists();

        if ($sudahDipesan) {
            return back()
                ->withErrors(['booking_date' => 'Tanggal ini sudah dipesan pelanggan lain.'])
                ->withInput();
        }

        // 4. Hindari pesanan ganda dari pelanggan yang sama
        $pesananGanda = Booking::where('user_id', auth()->id())
            ->where('photographer_id', $photographer->id)
            ->whereDate('booking_date', $request->booking_date)
            ->whereIn('status', ['pending', 'diterima', 'confirmed'])
            ->exists();

        if ($pesananGanda) {
            return back()
                ->withErrors(['booking_date' => 'Kamu sudah punya pesanan dengan fotografer ini di tanggal yang sama.'])
                ->withInput();
        }

        Booking::create([
            'user_id'         => auth()->id(),
            'photographer_id' => $photographer->id,
            'package_id'      => $request->package_id,
            'booking_date'    => $request->booking_date,
            'lokasi'          => trim($request->lokasi) . ', ' . $kota,
            'status'          => 'pending',
        ]);

        return redirect()->route('bookings.my')->with('success', 'Pemesanan berhasil dibuat!');
    }

    // Daftar pesanan milik pengguna yang sedang login
    public function myBookings()
    {
        $bookings = Booking::with(['photographer', 'package'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('booking.my_bookings', compact('bookings'));
    }

    // Jadwal pemotretan fotografer (halaman admin)
    public function schedule()
    {
        $photographers = Photographer::with(['bookings' => function ($query) {
            $query->whereIn('status', ['approved', 'completed', 'Diterima'])->orderBy('booking_date', 'asc');
        }])->get();

        $schedules = Booking::with(['photographer', 'user'])
            ->whereIn('status', ['approved', 'completed', 'Diterima'])
            ->orderBy('booking_date', 'asc')
            ->get();

        return view('admin.schedule.index', compact('schedules', 'photographers'));
    }
}