@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="row row-deck row-cards">
  @foreach ([
    ['Booking', $totalBooking],
    ['Pelanggan', $totalPelanggan],
    ['Fotografer', $totalPhotographer],
    ['Paket', $totalPackage],
  ] as [$label, $total])
    <div class="col-sm-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="subheader">{{ $label }}</div>
          <div class="h1 mb-0">{{ $total }}</div>
        </div>
      </div>
    </div>
  @endforeach

  <div class="col-12">
    <div class="card">
      <div class="card-header"><h3 class="card-title">Booking Terbaru</h3></div>
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