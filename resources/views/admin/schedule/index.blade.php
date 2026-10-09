@extends('layouts.admin')
@section('title', 'Manajemen Jadwal')

@section('content')
<div class="card mb-3">
  <div class="card-header"><h3 class="card-title">Tandai Tanggal Tidak Tersedia</h3></div>
  <form method="POST" action="{{ route('admin.schedule.store') }}">
    @csrf
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Fotografer</label>
          <select name="photographer_id" class="form-select" required>
            <option value="">Pilih fotografer</option>
            @foreach ($photographers as $p)
              <option value="{{ $p->id }}" @selected(old('photographer_id') == $p->id)>{{ $p->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Tanggal</label>
          <input type="date" name="date" class="form-control" min="{{ today()->toDateString() }}"
                 value="{{ old('date') }}" required>
        </div>
        <div class="col-md-5">
          <label class="form-label">Keterangan (opsional)</label>
          <input type="text" name="note" class="form-control" placeholder="Contoh: Libur, makan di mcd, farming piala"
                 value="{{ old('note') }}">
        </div>
      </div>
    </div>
    <div class="card-footer text-end">
      <button class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
    </div>
  </form>
</div>

<div class="row row-cards">
  @foreach ($photographers as $p)
    @php
      $perTanggal = $p->bookings->groupBy(fn ($b) => \Carbon\Carbon::parse($b->booking_date)->toDateString());
    @endphp
    <div class="col-md-6 col-lg-4">
      <div class="card">
        <div class="card-header">
          <div>
            <h3 class="card-title">{{ $p->name }}</h3>
            <div class="text-secondary small">{{ $p->specialization ?? 'Fotografer' }}</div>
          </div>
        </div>

        <div class="list-group list-group-flush">
          @foreach ($p->schedules as $s)
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-bold">{{ $s->date->translatedFormat('d F Y') }}</div>
                <div class="text-secondary small">{{ $s->note ?: 'Tidak tersedia' }}</div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary-lt">Libur</span>
                <form action="{{ route('admin.schedule.destroy', $s->id) }}" method="POST"
                      onsubmit="return confirm('Hapus tanggal ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash me-1"></i>Hapus</button>
                </form>
              </div>
            </div>
          @endforeach

          @foreach ($p->bookings as $b)
            @php
              $tgl = \Carbon\Carbon::parse($b->booking_date)->toDateString();
              $bentrok = $perTanggal[$tgl]->count() > 1;
            @endphp
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-bold">{{ \Carbon\Carbon::parse($b->booking_date)->translatedFormat('d F Y') }}</div>
                <div class="text-secondary small">
                  {{ $b->user->name ?? 'Pelanggan' }} · {{ $b->package->name ?? '-' }}
                </div>
                @if ($bentrok)
                  <div class="text-danger small">Bentrok: ada lebih dari satu booking di tanggal ini</div>
                @endif
              </div>
              <span class="badge {{ $b->status === 'confirmed' ? 'bg-green-lt' : 'bg-yellow-lt' }}">
                {{ $b->status === 'confirmed' ? 'Dikonfirmasi' : 'Menunggu' }}
              </span>
            </div>
          @endforeach

          @if ($p->schedules->isEmpty() && $p->bookings->isEmpty())
            <div class="list-group-item text-success">Jadwal kosong (tersedia)</div>
          @endif
        </div>
      </div>
    </div>
  @endforeach
</div>
@endsection