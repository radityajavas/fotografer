<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photographer;
use App\Models\Package;
use App\Models\Booking;
use App\Models\Schedule;

class BookingController extends Controller
{
    // Jam operasional (menit sejak 00:00): 08:00 - 20:00
    private const OPEN_MINUTES  = 8 * 60;
    private const CLOSE_MINUTES = 20 * 60;

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

        // Hanya libur SEHARIAN yang menonaktifkan tanggal di kalender
        $offDates = Schedule::where('photographer_id', $selectedPhotographer->id)
            ->whereDate('date', '>=', today())
            ->where(fn ($q) => $q->whereNull('start_time')->orWhereNull('end_time'))
            ->orderBy('date')
            ->get()
            ->map(fn ($s) => $s->date->format('Y-m-d'))
            ->unique()
            ->values();

        // Libur per jam (bukan seharian), dikelompokkan per tanggal: ['2026-10-10' => ['09:00–12:00', ...]]
        $offBlocks = Schedule::where('photographer_id', $selectedPhotographer->id)
            ->whereDate('date', '>=', today())
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($s) => $s->date->format('Y-m-d'))
            ->map(fn ($g) => $g->map(fn ($s) => $s->time_range)->values());

        return view('booking.create', compact('selectedPhotographer', 'packages', 'offDates', 'offBlocks', 'areas'));
    }

    // Memproses simpan booking
    public function store(Request $request)
    {
        $request->validate([
            'photographer_id' => ['required', 'exists:photographers,id'],
            'package_id'      => ['required', 'exists:packages,id'],
            'booking_date'    => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . today()->addYears(2)->toDateString()],
            'start_time'      => ['required', 'date_format:H:i'],
            'lokasi'          => ['required', 'string', 'min:10', 'max:400'],
        ], [
            'lokasi.min'                   => 'Alamat terlalu singkat, tulis minimal 10 karakter (jalan, nomor, patokan).',
            'booking_date.before_or_equal' => 'Tanggal pelaksanaan maksimal 2 tahun dari sekarang.',
            'start_time.required'          => 'Pilih jam mulai terlebih dahulu.',
            'start_time.date_format'       => 'Format jam mulai tidak valid.',
        ]);

        $photographer = Photographer::findOrFail($request->photographer_id);
        $package      = Package::findOrFail($request->package_id);

        // 0. Fotografer harus sedang menerima pesanan
        if (strtoupper($photographer->status) !== 'AVAILABLE') {
            return back()
                ->withErrors(['photographer_id' => 'Fotografer ini sedang tidak menerima pesanan.'])
                ->withInput();
        }

        // Hitung jam selesai dari durasi paket
        $start = $request->start_time;
        $end   = null;

        if ($start) {
            [$h, $m]  = array_map('intval', explode(':', $start));
            $startMin = $h * 60 + $m;
            $endMin   = $startMin + ((int) $package->duration_hours) * 60;

            if ($startMin < self::OPEN_MINUTES) {
                return back()
                    ->withErrors(['start_time' => 'Jam mulai paling awal 08.00.'])
                    ->withInput();
            }

            if ($endMin > self::CLOSE_MINUTES) {
                return back()
                    ->withErrors(['start_time' => 'Sesi ' . $package->duration_hours . ' jam dari jam itu melewati jam operasional (selesai maksimal 20.00).'])
                    ->withInput();
            }

            $end = sprintf('%02d:%02d', intdiv($endMin, 60), $endMin % 60);
        }

        // 1. Fotografer libur pada tanggal/jam itu
        $libur = Schedule::where('photographer_id', $photographer->id)
            ->overlap($request->booking_date, $start, $end)
            ->exists();

        if ($libur) {
            return back()
                ->withErrors(['booking_date' => 'Fotografer tidak tersedia pada tanggal/jam ini.'])
                ->withInput();
        }

        // 2. Jam itu sudah dipesan (sudah diterima admin)
        $sudahDipesan = Booking::where('photographer_id', $photographer->id)
            ->whereIn('status', ['diterima', 'confirmed'])
            ->overlap($request->booking_date, $start, $end)
            ->exists();

        if ($sudahDipesan) {
            return back()
                ->withErrors(['booking_date' => 'Tanggal/jam ini sudah dipesan pelanggan lain.'])
                ->withInput();
        }

        // 3. Hindari pesanan ganda dari pelanggan yang sama pada jam yang bertabrakan
        $pesananGanda = Booking::where('user_id', auth()->id())
            ->where('photographer_id', $photographer->id)
            ->whereIn('status', ['menunggu', 'diterima', 'confirmed'])
            ->overlap($request->booking_date, $start, $end)
            ->exists();

        if ($pesananGanda) {
            return back()
                ->withErrors(['booking_date' => 'Kamu sudah punya pesanan dengan fotografer ini di tanggal/jam yang bertabrakan.'])
                ->withInput();
        }

        Booking::create([
            'user_id'         => auth()->id(),
            'photographer_id' => $photographer->id,
            'package_id'      => $request->package_id,
            'booking_date'    => $request->booking_date,
            'start_time'      => $start,
            'end_time'        => $end,
            'lokasi'          => trim($request->lokasi),
            'status'          => 'menunggu',
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