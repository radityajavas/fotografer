<?php

namespace App\Http\Controllers;

use App\Models\Booking;
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
        $photographers = \App\Models\Photographer::all();
        $packages = \App\Models\Package::all();

        return view('booking.create', compact('photographers', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'photographer_id' => 'required',
            'package_id' => 'required',
            'tanggal_booking' => 'required|date',
            'alamat' => 'required',
        ]);

        Booking::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'photographer_id' => $request->photographer_id,
            'package_id' => $request->package_id,
            'tanggal_booking' => $request->tanggal_booking,
            'alamat' => $request->alamat,
            'status' => 'Pending',
        ]);

        return redirect()->route('booking.index')
                         ->with('success', 'Data booking berhasil ditambahkan.');
    }

    public function show(Booking $booking)
    {
        return view('booking.detail', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $photographers = \App\Models\Photographer::all();
        $packages = \App\Models\Package::all();

        return view('booking.edit', compact('booking', 'photographers', 'packages'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'photographer_id' => 'required',
            'package_id' => 'required',
            'tanggal_booking' => 'required|date',
            'alamat' => 'required',
            'status' => 'required',
        ]);

        $booking->update($request->all());

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