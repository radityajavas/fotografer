@extends('layouts.admin')

@section('title', 'Data Booking')

@section('content')
@php
    $current = $status ?? 'all';
    $badge = [
        'pending'  => ['Menunggu', 'bg-yellow-lt'],
        'diterima' => ['Diterima', 'bg-green-lt'],
        'ditolak'  => ['Ditolak',  'bg-red-lt'],
    ];
@endphp

<div class="row row-deck row-cards mb-3">
  <div class="col-sm-6 col-lg-3">
    <div class="card"><div class="card-body">
      <div class="subheader">Total Pemesanan</div>
      <div class="h1 mb-0">{{ $stats['total'] }}</div>
    </div></div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card"><div class="card-body">
      <div class="subheader">Menunggu Konfirmasi</div>
      <div class="h1 mb-0">{{ $stats['pending'] }}</div>
    </div></div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card"><div class="card-body">
      <div class="subheader">Diterima</div>
      <div class="h1 mb-0">{{ $stats['diterima'] }}</div>
    </div></div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card"><div class="card-body">
      <div class="subheader">Total Pendapatan</div>
      <div class="h1 mb-0">Rp {{ number_format($stats['pendapatan'], 0, ',', '.') }}</div>
    </div></div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <ul class="nav nav-pills card-header-pills">
      @foreach (['all' => 'Semua', 'pending' => 'Menunggu', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $val => $label)
        <li class="nav-item">
          <a class="nav-link {{ $current === $val ? 'active' : '' }}"
             href="{{ route('admin.bookings.index', ['status' => $val]) }}">{{ $label }}</a>
        </li>
      @endforeach
    </ul>
  </div>

  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead>
        <tr>
          <th>Pemesan</th>
          <th>Fotografer</th>
          <th>Tanggal Foto</th>
          <th>Total Harga</th>
          <th>Status</th>
          <th class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($bookings as $booking)
          @php $s = strtolower($booking->status); @endphp
          <tr>
            <td class="fw-semibold">{{ $booking->user->name ?? '-' }}</td>
            <td>{{ $booking->photographer->name ?? '-' }}</td>
            <td>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') : '-' }}</td>
            <td>
              @if ($booking->package)
                Rp {{ number_format($booking->package->price, 0, ',', '.') }}
              @else
                -
              @endif
            </td>
            <td>
              <span class="badge {{ $badge[$s][1] ?? 'bg-secondary-lt' }}">
                {{ $badge[$s][0] ?? $booking->status }}
              </span>
            </td>
            <td class="text-center text-nowrap">
              <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="diterima">
                <button type="submit" class="btn btn-success btn-sm" @disabled($s === 'diterima')><i class="ti ti-check me-1"></i>Setujui</button>
              </form>

              <form action="{{ route('admin.bookings.updateStatus', $booking->id) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="ditolak">
                <button type="submit" class="btn btn-danger btn-sm" @disabled($s === 'ditolak')><i class="ti ti-x me-1"></i>Tolak</button>
              </form>

              <a href="{{ route('admin.chats.show', $booking->id) }}" class="btn btn-sm btn-outline-secondary"><i class="ti ti-message-circle me-1"></i>Chat</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-secondary py-4">Belum ada data booking.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($bookings->hasPages())
    <div class="card-footer">{{ $bookings->withQueryString()->links() }}</div>
  @endif
</div>
@endsection