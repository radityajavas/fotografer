<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Booking::with(['user', 'photographer', 'package']);

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->paginate(10);

        $stats = [
            'total'      => Booking::count(),
            'pending'    => Booking::where('status', 'menunggu')->count(),
            'diterima'   => Booking::where('status', 'diterima')->count(),
            'ditolak'    => Booking::where('status', 'ditolak')->count(),
            'pendapatan' => Booking::with('package')
                ->where('status', 'diterima')
                ->get()
                ->sum(fn ($b) => $b->package->price ?? 0),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats', 'status'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:diterima,ditolak',
        ], [
            'status.required' => 'Status wajib diisi.',
            'status.in'       => 'Status tidak valid.',
        ]);

        $booking   = Booking::findOrFail($id);
        $newStatus = $validated['status'];

        // 1. Status final (diterima/ditolak) tidak boleh diubah lagi
        if (in_array(strtolower($booking->status), ['diterima', 'ditolak'], true)) {
            return redirect()->back()->with('error', 'Status pemesanan sudah ' . $booking->status . ' dan tidak bisa diubah lagi.');
        }

        // 2. Validasi khusus saat menyetujui booking
        if ($newStatus === 'diterima') {
            // Tanggal foto sudah lewat
            if ($booking->booking_date && \Carbon\Carbon::parse($booking->booking_date)->isPast()
                && !\Carbon\Carbon::parse($booking->booking_date)->isToday()) {
                return redirect()->back()->with('error', 'Booking tidak bisa disetujui karena tanggal foto sudah lewat.');
            }

            // Fotografer menandai tanggal/jam itu tidak tersedia
            $libur = Schedule::where('photographer_id', $booking->photographer_id)
                ->overlap($booking->booking_date, $booking->start_time, $booking->end_time)
                ->exists();

            if ($libur) {
                return redirect()->back()->with('error', 'Booking tidak bisa disetujui: fotografer menandai tanggal/jam itu tidak tersedia.');
            }

            // Jam bentrok dengan booking lain yang sudah diterima
            $bentrok = Booking::where('photographer_id', $booking->photographer_id)
                ->where('status', 'diterima')
                ->where('id', '!=', $booking->id)
                ->overlap($booking->booking_date, $booking->start_time, $booking->end_time)
                ->exists();

            if ($bentrok) {
                return redirect()->back()->with('error', 'Jadwal bentrok: fotografer ini sudah punya booking yang diterima di tanggal/jam yang bertabrakan.');
            }
        }

        $booking->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status pemesanan berhasil diperbarui!');
    }
}