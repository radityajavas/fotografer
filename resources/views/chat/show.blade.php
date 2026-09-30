@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">

    <a href="{{ route('bookings.my') }}" class="small">&larr; Kembali ke pesanan</a>
    <h2 class="h4 mt-2 mb-1">Chat dengan admin</h2>
    <p class="text-secondary small">
      Pesanan: {{ $booking->photographer->name ?? '-' }} &bull; {{ $booking->package->name ?? '-' }}
      &bull; {{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : '-' }}
    </p>

    <div class="border bg-white p-3 mb-3" style="height: 380px; overflow-y: auto;" id="chat-box">
      @forelse ($messages as $m)
        @php $mine = $m->sender_id === auth()->id(); @endphp
        <div class="d-flex mb-2 {{ $mine ? 'justify-content-end' : 'justify-content-start' }}">
          <div class="px-3 py-2 rounded {{ $mine ? 'bg-dark text-white' : 'bg-light border' }}" style="max-width: 80%;">
            <div class="small opacity-75">{{ $mine ? 'Kamu' : 'Admin' }} &bull; {{ $m->created_at->format('d M H:i') }}</div>
            <div>{{ $m->message }}</div>
          </div>
        </div>
      @empty
        <p class="text-secondary text-center mt-5 mb-0">Belum ada pesan. Tulis pesan pertamamu di bawah.</p>
      @endforelse
    </div>

    <form method="POST" action="{{ route('chat.store', $booking->id) }}" class="d-flex gap-2">
      @csrf
      <input type="text" name="message" class="form-control @error('message') is-invalid @enderror"
             placeholder="Tulis pesan..." required autocomplete="off">
      <button class="btn btn-brand">Kirim</button>
    </form>
    @error('message') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

  </div>
</div>

<script>
  var box = document.getElementById('chat-box');
  box.scrollTop = box.scrollHeight;
</script>
@endsection