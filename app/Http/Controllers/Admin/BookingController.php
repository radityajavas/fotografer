<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Photographer;
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

        Booking::findOrFail($id)->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status pemesanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Pemesanan berhasil dihapus!');
    }

    public function schedule()
    {
        $photographers = Photographer::with(['bookings' => function ($q) {
            $q->with(['user', 'package'])
              ->whereIn('status', ['pending', 'confirmed'])
              ->whereDate('booking_date', '>=', today())
              ->orderBy('booking_date');
        }])->get();

        return view('admin.schedule.index', compact('photographers'));
    }
}