@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="row row-deck row-cards">
  @foreach ([
    ['Booking',    $totalBooking,      'calendar-check', 'bg-blue-lt',   'admin.bookings.index'],
    ['Pelanggan',  $totalPelanggan,    'users',          'bg-green-lt',  'admin.customers.index'],
    ['Fotografer', $totalPhotographer, 'camera',         'bg-purple-lt', 'admin.photographers.index'],
    ['Paket',      $totalPackage,      'package',        'bg-orange-lt', 'admin.packages.index'],
  ] as [$label, $total, $icon, $tone, $link])
    <div class="col-sm-6 col-lg-3">
      <a href="{{ route($link) }}" class="card card-link text-decoration-none">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <span class="avatar avatar-md {{ $tone }} me-3"><i class="ti ti-{{ $icon }} fs-2"></i></span>
            <div>
              <div class="subheader">{{ $label }}</div>
              <div class="h1 mb-0">{{ $total }}</div>
            </div>
          </div>
        </div>
      </a>
    </div>
  @endforeach

  <div class="col-12">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><i class="ti ti-clock me-2"></i>Booking Terbaru</h3></div>
      <div class="table-responsive">
        <table class="table table-vcenter card-table">
          <thead>
            <tr><th>Pelanggan</th><th>Tanggal Foto</th><th>Status</th></tr>
          </thead>
          <tbody>
            @forelse ($latestBookings as $b)
              @php
                $badge = [
                  'pending'   => 'bg-yellow-lt',
                  'confirmed' => 'bg-green-lt',
                  'completed' => 'bg-blue-lt',
                  'cancelled' => 'bg-red-lt',
                ][$b->status] ?? 'bg-secondary-lt';
              @endphp
              <tr>
                <td>{{ $b->user->name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}</td>
                <td><span class="badge {{ $badge }}">{{ $b->status ?? '-' }}</span></td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center text-secondary">Belum ada booking</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection