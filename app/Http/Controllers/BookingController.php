<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Photographer;
use App\Models\Package;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $booking = Booking::with(['photographer', 'package'])->paginate(5);
        return view('booking.index', compact('booking'));
    }

    public function create()
    {
        $photographers = Photographer::all();
        $packages = Package::all();
        
        // Ambil daftar tanggal yang sudah dibooking
        $bookedDates = Booking::pluck('tanggal_booking')->toArray();

        return view('booking.create', compact('photographers', 'packages', 'bookedDates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan'  => 'required|string|max:255',
            'photographer_id' => 'required',
            'package_id'      => 'required',
            'tanggal_booking' => 'required|date',
            'alamat'          => 'required|string',
        ]);

        Booking::create($validated);

        return redirect()->route('booking.index')
                         ->with('success', 'Data booking berhasil ditambahkan.');
    }

    public function show(Booking $booking)
    {
        return view('booking.detail', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $photographers = Photographer::all();
        $packages = Package::all();

        return view('booking.edit', compact('booking', 'photographers', 'packages'));
    }

    public function update(Request $request, Booking $booking)
    {
      
        $validated = $request->validate([
            'nama_pelanggan'  => 'required|string|max:255',
            'photographer_id' => 'required',
            'package_id'      => 'required',
            'tanggal_booking' => 'required|date',
            'alamat'          => 'required|string',
        ]);

        $booking->update($validated);

        return redirect()->route('booking.index')
                         ->with('success', 'Data booking berhasil diperbarui.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('booking.index')
                         ->with('success', 'Data booking berhasil dihapus.');
    }
}