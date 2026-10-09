@extends('layouts.app')

@section('content')
@php
    $badge = [
        'pending'   => ['Menunggu',  'text-bg-warning'],
        'diterima'  => ['Diterima',  'text-bg-success'],
        'confirmed' => ['Diterima',  'text-bg-success'],
        'ditolak'   => ['Ditolak',   'text-bg-danger'],
        'rejected'  => ['Ditolak',   'text-bg-danger'],
        'cancelled' => ['Dibatalkan','text-bg-secondary'],
        'completed' => ['Selesai',   'text-bg-dark'],
    ];
@endphp

<h2 class="h4 mb-1">Pesanan Saya</h2>
<p class="text-secondary">Pantau status pesanan dan hubungi admin lewat chat.</p>

<div class="card">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Fotografer</th>
          <th>Paket</th>
          <th>Tanggal</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($bookings as $b)
          @php $s = strtolower($b->status); @endphp
          <tr>
            <td>{{ $b->photographer->name ?? '-' }}</td>
            <td>
              {{ $b->package->name ?? '-' }}
              @if ($b->package)
                <div class="small text-secondary">
                  {{ $b->package->duration_hours }} jam &bull; Rp {{ number_format($b->package->price, 0, ',', '.') }}
                </div>
              @endif
              @if (!empty($b->lokasi))
                <div class="small text-secondary">{{ $b->lokasi }}</div>
              @endif
            </td>
            <td>{{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d M Y') : '-' }}</td>
            <td>
              <span class="badge {{ $badge[$s][1] ?? 'text-bg-secondary' }}">
                {{ $badge[$s][0] ?? $b->status }}
              </span>
            </td>
            <td class="text-end">
              <a href="{{ route('chat.show', $b->id) }}" class="btn btn-sm btn-brand">Chat</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-secondary py-4">Belum ada pesanan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection