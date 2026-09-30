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
        $packages = Package::all();

        $offDates = Schedule::where('photographer_id', $photographerId)
            ->whereDate('date', '>=', today())
            ->orderBy('date')
            ->get()
            ->map(fn ($s) => $s->date->format('Y-m-d'))
            ->values();

        return view('booking.create', compact('selectedPhotographer', 'packages', 'offDates'));
    }

    // Memproses simpan booking
    public function store(Request $request)
    {
        $request->validate([
            'photographer_id' => 'required|exists:photographers,id',
            'package_id'      => 'required|exists:packages,id',
            'booking_date'    => 'required|date|after_or_equal:today',
            'lokasi'          => 'required|string|max:500',
        ]);

        // 1. Fotografer libur di tanggal itu
        $libur = Schedule::where('photographer_id', $request->photographer_id)
            ->whereDate('date', $request->booking_date)
            ->exists();

        if ($libur) {
            return back()
                ->withErrors(['booking_date' => 'Fotografer tidak tersedia pada tanggal ini.'])
                ->withInput();
        }

        // 2. Tanggal itu sudah dipesan (sudah diterima admin)
        $sudahDipesan = Booking::where('photographer_id', $request->photographer_id)
            ->whereDate('booking_date', $request->booking_date)
            ->whereIn('status', ['diterima', 'confirmed'])
            ->exists();

        if ($sudahDipesan) {
            return back()
                ->withErrors(['booking_date' => 'Tanggal ini sudah dipesan pelanggan lain.'])
                ->withInput();
        }

        Booking::create([
            'user_id'         => auth()->id(),
            'photographer_id' => $request->photographer_id,
            'package_id'      => $request->package_id,
            'booking_date'    => $request->booking_date,
            'lokasi'          => $request->lokasi,
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