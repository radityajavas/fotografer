<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photographer;
use App\Models\Package;
use App\Models\Booking;

class BookingController extends Controller
{
    // ==========================================
    // 1. FITUR PELANGGAN (CUSTOMER)
    // ==========================================

    // Menampilkan Form Booking
    public function create(Request $request)
    {
        $photographerId = $request->query('photographer_id');
        $selectedPhotographer = Photographer::find($photographerId);
        $packages = Package::all();

        return view('booking.create', compact('selectedPhotographer', 'packages'));
    }

    // Memproses Simpan Booking
    public function store(Request $request)
    {
        $request->validate([
            'photographer_id' => 'required|exists:photographers,id',
            'package_id'      => 'required',
            'booking_date'    => 'required|date',
        ]);

        Booking::create([
            'user_id'         => auth()->id(),
            'photographer_id' => $request->photographer_id,
            'package_id'      => $request->package_id,
            'booking_date'    => $request->booking_date,
            'status'          => 'pending',
        ]);

        return redirect()->route('home')->with('success', 'Pemesanan berhasil dibuat!');
    }

    // ==========================================
    // 2. FITUR ADMIN
    // ==========================================

    // Menampilkan Daftar Booking di Halaman Admin
    public function adminIndex()
    {
        $bookings = Booking::with(['user', 'photographer', 'package'])->latest()->get();

        // Menghitung total pendapatan dari booking status approved atau completed
        $totalPendapatan = $bookings->filter(function ($booking) {
            return in_array($booking->status, ['approved', 'completed']);
        })->sum(function ($booking) {
            return $booking->package ? $booking->package->price : 0;
        });

        // Variabel $stats lengkap sesuai kebutuhan view blade admin
        $stats = [
            'total_booking'    => $bookings->count(),
            'pending'          => $bookings->where('status', 'pending')->count(),
            'approved'         => $bookings->where('status', 'approved')->count(),
            'completed'        => $bookings->where('status', 'completed')->count(),
            'total_pendapatan' => $totalPendapatan,
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    // Mengubah Status Booking (Approve/Reject/Complete)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status booking berhasil diperbarui!');
    }

    // Menampilkan Jadwal Pemotretan Fotografer
    public function schedule()
    {
        $photographers = Photographer::with(['bookings' => function($query) {
            $query->whereIn('status', ['approved', 'completed'])->orderBy('booking_date', 'asc');
        }])->get();

        $schedules = Booking::with(['photographer', 'user'])
            ->whereIn('status', ['approved', 'completed'])
            ->orderBy('booking_date', 'asc')
            ->get();

        return view('admin.schedule.index', compact('schedules', 'photographers'));
    }
    // Menampilkan daftar pesanan milik pengguna yang sedang login
public function myBookings()
{
    $bookings = Booking::with(['photographer', 'package'])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('booking.my_bookings', compact('bookings'));
}
}