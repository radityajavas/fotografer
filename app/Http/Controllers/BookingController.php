<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Photographer;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // ... method kamu yang sudah ada (seperti index, store, dll) ...

    /**
     * Menampilkan daftar booking & statistik di Panel Admin
     */
    public function adminIndex(Request $request)
    {
        $statusFilter = $request->query('status');

        $query = Booking::with(['photographer', 'package', 'user']);

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $bookings = $query->latest()->paginate(10);

        // Ringkasan Statistik Laporan
        $stats = [
            'total_booking' => Booking::count(),
            'pending' => Booking::where('status', 'menunggu')->count(),
            'approved' => Booking::where('status', 'diterima')->count(),
            'rejected' => Booking::where('status', 'ditolak')->count(),
            'total_pendapatan' => Booking::where('status', 'diterima')->sum('total_harga')
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Memperbarui status booking (Setujui / Tolak)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diterima,ditolak'
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return redirect()->back()->with('success', 'Status booking berhasil diperbarui.');
    }

    /**
     * Kelola Jadwal Agenda Fotografer
     */
    public function schedule()
    {
        $photographers = Photographer::with(['bookings' => function($q) {
            $q->where('status', 'diterima');
        }])->get();

        return view('admin.schedule.index', compact('photographers'));
    }
}