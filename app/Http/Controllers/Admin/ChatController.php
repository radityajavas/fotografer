<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Message;

class ChatController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'photographer'])
            ->whereHas('messages')
            ->withMax('messages', 'created_at')
            ->orderByDesc('messages_max_created_at')
            ->get();

        return view('admin.chats.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'photographer', 'package'])->findOrFail($id);

        $messages = Message::with('sender')
            ->where('booking_id', $booking->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.chats.show', compact('booking', 'messages'));
    }

    public function store(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

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