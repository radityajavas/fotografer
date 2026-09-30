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
            'total'     => Booking::count(),
            'pending'   => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats', 'status'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $booking = Booking::findOrFail($id);

        if ($request->status === 'confirmed') {
            $bentrok = Booking::where('id', '!=', $booking->id)
                ->where('photographer_id', $booking->photographer_id)
                ->whereDate('booking_date', $booking->booking_date)
                ->where('status', 'confirmed')
                ->exists();

            if ($bentrok) {
                return back()->withErrors(['status' => 'Gagal: fotografer ini sudah punya booking terkonfirmasi di tanggal yang sama (double booking).']);
            }

            $libur = Schedule::where('photographer_id', $booking->photographer_id)
                ->whereDate('date', $booking->booking_date)
                ->exists();

            if ($libur) {
                return back()->withErrors(['status' => 'Gagal: fotografer ditandai tidak tersedia pada tanggal ini.']);
            }
        }

        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pemesanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Pemesanan berhasil dihapus!');
    }
}