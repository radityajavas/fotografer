@extends('layouts.admin')
@section('title', 'Chat Pelanggan')

@section('content')
<div class="card">
  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead>
        <tr><th>Pelanggan</th><th>Fotografer</th><th>Pesan terakhir</th><th class="w-1"></th></tr>
      </thead>
      <tbody>
        @forelse ($bookings as $b)
          <tr>
            <td class="fw-semibold">{{ $b->user->name ?? '-' }}</td>
            <td>{{ $b->photographer->name ?? '-' }}</td>
            <td class="text-secondary">
              {{ \Carbon\Carbon::parse($b->messages_max_created_at)->diffForHumans() }}
            </td>
            <td>
              <a href="{{ route('admin.chats.show', $b->id) }}" class="btn btn-sm btn-primary">Buka</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-center text-secondary py-4">Belum ada chat masuk.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection