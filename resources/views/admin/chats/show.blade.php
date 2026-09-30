@extends('layouts.admin')
@section('title', 'Chat dengan ' . ($booking->user->name ?? 'Pelanggan'))

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">
      {{ $booking->user->name ?? '-' }}
      <span class="text-secondary fw-normal">
        &bull; {{ $booking->photographer->name ?? '-' }} &bull; {{ $booking->package->name ?? '-' }}
      </span>
    </h3>
    <div class="card-actions">
      <a href="{{ route('admin.chats.index') }}" class="btn btn-sm">Kembali</a>
    </div>
  </div>

  <div class="card-body" style="height: 400px; overflow-y: auto;" id="chat-box">
    @forelse ($messages as $m)
      @php $fromAdmin = ($m->sender->role ?? null) === 'admin'; @endphp
      <div class="d-flex mb-2 {{ $fromAdmin ? 'justify-content-end' : 'justify-content-start' }}">
        <div class="px-3 py-2 rounded {{ $fromAdmin ? 'bg-dark text-white' : 'bg-light border' }}" style="max-width: 80%;">
          <div class="small opacity-75">
            {{ $fromAdmin ? 'Admin' : ($m->sender->name ?? 'Pelanggan') }} &bull; {{ $m->created_at->format('d M H:i') }}
          </div>
          <div>{{ $m->message }}</div>
        </div>
      </div>
    @empty
      <p class="text-secondary text-center mt-5">Belum ada pesan.</p>
    @endforelse
  </div>

  <div class="card-footer">
    <form method="POST" action="{{ route('admin.chats.store', $booking->id) }}" class="d-flex gap-2">
      @csrf
      <input type="text" name="message" class="form-control" placeholder="Tulis balasan..." required autocomplete="off">
      <button class="btn btn-primary">Kirim</button>
    </form>
  </div>
</div>

<script>
  var box = document.getElementById('chat-box');
  box.scrollTop = box.scrollHeight;
</script>
@endsection