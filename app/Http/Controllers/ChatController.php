<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Message;

class ChatController extends Controller
{
    // Menampilkan halaman chat berdasarkan ID Booking
    public function show($bookingId)
    {
        $booking = Booking::with(['user', 'photographer', 'package'])->findOrFail($bookingId);

        // Keamanan: Pastikan hanya pelanggan pemilik booking atau admin/fotografer terkait yang bisa lihat chat
        if (auth()->id() !== $booking->user_id && auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $messages = Message::with('sender')
            ->where('booking_id', $bookingId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.show', compact('booking', 'messages'));
    }

    // Mengirim pesan baru
    public function store(Request $request, $bookingId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'booking_id' => $bookingId,
            'sender_id'  => auth()->id(),
            'message'    => $request->message,
        ]);

        return redirect()->back();
    }
}