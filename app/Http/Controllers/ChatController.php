<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Message;

class ChatController extends Controller
{
    /**
     * Pintu masuk tombol Chat (beranda / profil fotografer).
     * Chat terhubung dengan booking, jadi langsung buka percakapan milik
     * pelanggan yang sedang login tanpa lewat halaman riwayat.
     */
    public function start(Request $request)
    {
        $query = Booking::where('user_id', auth()->id());

        // Jika dibuka dari profil fotografer, utamakan percakapan dengan fotografer itu
        $photographerId = $request->query('photographer_id');
        if ($photographerId) {
            $query->where('photographer_id', $photographerId);
        }

        // Utamakan booking yang sudah punya percakapan, lalu booking terbaru
        $booking = (clone $query)->whereHas('messages')->latest()->first()
            ?? (clone $query)->latest()->first();

        if ($booking) {
            return redirect()->route('chat.show', $booking->id);
        }

        // Belum ada booking dengan fotografer ini: kembali ke profilnya
        if ($photographerId) {
            return redirect()->route('photographers.show', $photographerId)
                ->with('error', 'Chat terhubung dengan pesanan. Kamu belum punya pesanan dengan fotografer ini, silakan pesan terlebih dahulu.');
        }

        // Belum punya booking sama sekali: jangan membuat booking palsu
        return redirect()->route('paket.index')
            ->with('error', 'Chat terhubung dengan pesanan. Kamu belum punya pesanan, silakan pilih paket dan buat pesanan terlebih dahulu.');
    }

    public function show($bookingId)
    {
        $booking = Booking::with(['photographer', 'package'])
            ->where('user_id', auth()->id())
            ->findOrFail($bookingId);

        $messages = Message::with('sender')
            ->where('booking_id', $booking->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.show', compact('booking', 'messages'));
    }

    public function store(Request $request, $bookingId)
    {
        $booking = Booking::where('user_id', auth()->id())->findOrFail($bookingId);

        $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:1000'],
        ]);

        Message::create([
            'booking_id' => $booking->id,
            'sender_id'  => auth()->id(),
            'message'    => trim($request->message),
        ]);

        return redirect()->back();
    }
}