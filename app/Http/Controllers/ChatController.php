<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Message;

class ChatController extends Controller
{
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
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'booking_id' => $booking->id,
            'sender_id'  => auth()->id(),
            'message'    => $request->message,
        ]);

        return redirect()->back();
    }
}