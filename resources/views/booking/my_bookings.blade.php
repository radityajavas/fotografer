@extends('layouts.app')

@section('content')
@php
    $badge = [
        'pending'   => ['Menunggu',  'text-bg-warning'],
        'menunggu'  => ['Menunggu',  'text-bg-warning'],
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

@if (session('info'))
  <div class="alert alert-info">{{ session('info') }}</div>
@endif

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
            <td>
              {{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d M Y') : '-' }}
              @php
                // Contoh tampilan: 10.00–12.00 (data dari kolom start_time & end_time)
                $jam = $b->time_range
                    ? implode('–', array_map(fn ($t) => str_replace(':', '.', $t), explode('–', $b->time_range)))
                    : null;
              @endphp
              <div class="small text-secondary">
                Jam: {{ $jam ?? 'Belum ditentukan' }}
              </div>
            </td>
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
            <td colspan="5" class="text-center py-5">
              <p class="text-secondary mb-3">
                Belum ada pesanan. Chat dengan admin terhubung ke pesanan, jadi buat pesanan terlebih dahulu.
              </p>
              <a href="{{ route('paket.index') }}" class="btn btn-brand btn-sm px-3">Lihat Paket</a>
              <a href="{{ route('landing') }}" class="btn btn-outline-secondary btn-sm px-3">Cari Fotografer</a>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection