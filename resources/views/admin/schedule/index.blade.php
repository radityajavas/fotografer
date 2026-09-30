@extends('layouts.admin')
@section('title', 'Jadwal Fotografer')

@section('content')
<p class="text-secondary mb-3">Pantau jadwal pemotretan untuk mencegah jadwal bentrok (double booking).</p>

<div class="row row-cards">
  @foreach ($photographers as $p)
    <div class="col-md-6 col-lg-4">
      <div class="card">
        <div class="card-header">
          <div>
            <h3 class="card-title">{{ $p->name }}</h3>
            <div class="text-secondary small">{{ $p->specialization ?? 'Fotografer' }}</div>
          </div>
        </div>
        <div class="list-group list-group-flush">
          @forelse ($p->bookings as $b)
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-bold">{{ \Carbon\Carbon::parse($b->booking_date)->translatedFormat('d F Y') }}</div>
                <div class="text-secondary small">
                  {{ $b->user->name ?? 'Pelanggan' }} · {{ $b->package->name ?? '-' }}
                </div>
              </div>
              <span class="badge {{ $b->status === 'confirmed' ? 'bg-green-lt' : 'bg-yellow-lt' }}">
                {{ $b->status === 'confirmed' ? 'Dikonfirmasi' : 'Menunggu' }}
              </span>
            </div>
          @empty
            <div class="list-group-item text-success">Jadwal kosong (tersedia)</div>
          @endforelse
        </div>
      </div>
    </div>
  @endforeach
</div>
@endsection