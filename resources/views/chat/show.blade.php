@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    <!-- Header Chat -->
    <div class="bg-white p-4 border border-gray-200 rounded-t-xl flex justify-between items-center shadow-sm">
        <div>
            <h2 class="font-bold text-gray-800 text-lg">
                Chat: {{ $booking->photographer->name ?? 'Fotografer' }}
            </h2>
            <p class="text-xs text-gray-500">
                Paket: {{ $booking->package->name ?? '-' }} | Tanggal: {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
            </p>
        </div>
        <a href="{{ route('bookings.my') }}" class="text-sm text-gray-600 hover:text-indigo-600">
            ← Kembali
        </a>
    </div>

    <!-- Area Pesan (Kotak Chat) -->
    <div class="bg-gray-50 p-4 border-x border-gray-200 h-96 overflow-y-auto flex flex-col gap-3">
        @forelse($messages as $msg)
            @php $isMe = $msg->sender_id === auth()->id(); @endphp
            <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                <span class="text-[10px] text-gray-400 mb-0.5">
                    {{ $msg->sender->name }} • {{ $msg->created_at->format('H:i') }}
                </span>
                <div class="max-w-xs md:max-w-md px-4 py-2 rounded-2xl text-sm {{ $isMe ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white border border-gray-200 text-gray-800 rounded-bl-none shadow-sm' }}">
                    {{ $msg->message }}
                </div>
            </div>
        @empty
            <div class="m-auto text-center text-gray-400 text-sm">
                Belum ada pesan. Mulai percakapan sekarang!
            </div>
        @endforelse
    </div>

    <!-- Form Kirim Pesan -->
    <div class="bg-white p-3 border border-gray-200 rounded-b-xl shadow-sm">
        <form action="{{ route('chat.store', $booking->id) }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="message" placeholder="Tulis pesan kamu di sini..." required
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2 rounded-lg text-sm transition">
                Kirim
            </button>
        </form>
    </div>
</div>
@endsection 