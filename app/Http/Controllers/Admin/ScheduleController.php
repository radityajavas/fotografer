<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Photographer;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $photographers = Photographer::with([
            'bookings' => function ($q) {
                $q->with(['user', 'package'])
                  ->whereIn('status', ['pending', 'confirmed'])
                  ->whereDate('booking_date', '>=', today())
                  ->orderBy('booking_date');
            },
            'schedules' => function ($q) {
                $q->whereDate('date', '>=', today())->orderBy('date');
            },
        ])->get();

        return view('admin.schedule.index', compact('photographers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'photographer_id' => 'required|exists:photographers,id',
            'date'            => 'required|date|after_or_equal:today',
            'note'            => 'nullable|string|max:255',
        ]);

        if (Schedule::where('photographer_id', $data['photographer_id'])->whereDate('date', $data['date'])->exists()) {
            return back()->withErrors(['date' => 'Tanggal itu sudah ditandai tidak tersedia untuk fotografer ini.'])->withInput();
        }

        $sudahAdaBooking = Booking::where('photographer_id', $data['photographer_id'])
            ->whereDate('booking_date', $data['date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($sudahAdaBooking) {
            return back()->withErrors(['date' => 'Tidak bisa diblokir: sudah ada booking (menunggu/dikonfirmasi) pada tanggal itu.'])->withInput();
        }

        Schedule::create($data);

        return back()->with('success', 'Tanggal tidak tersedia berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Schedule::findOrFail($id)->delete();

        return back()->with('success', 'Tanggal tidak tersedia berhasil dihapus!');
    }
}