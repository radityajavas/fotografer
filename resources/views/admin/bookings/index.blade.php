@extends('layouts.admin')
@section('title', 'Data Booking')

@section('content')
<div class="row row-cards mb-3">
  @foreach ([
    'all'       => 'Semua',
    'pending'   => 'Menunggu',
    'confirmed' => 'Dikonfirmasi',
    'completed' => 'Selesai',
    'cancelled' => 'Dibatalkan',
  ] as $key => $label)
    <div class="col-6 col-lg">
      <a href="{{ route('admin.bookings.index', ['status' => $key]) }}"
         class="card card-link {{ ($status ?? 'all') === $key || (!$status && $key === 'all') ? 'border-primary' : '' }}">
        <div class="card-body">
          <div class="subheader">{{ $label }}</div>
          <div class="h2 mb-0">{{ $stats[$key === 'all' ? 'total' : $key] }}</div>
        </div>
      </a>
    </div>
  @endforeach
</div>

<div class="card">
  <div class="card-header"><h3 class="card-title">Daftar Transaksi Booking</h3></div>
  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead>
        <tr>
          <th>Pemesan</th><th>Fotografer</th><th>Tanggal Foto</th>
          <th>Total Harga</th><th>Status</th><th class="w-1"></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($bookings as $b)
          <tr>
            <td>{{ $b->user->name ?? '-' }}</td>
            <td>{{ $b->photographer->name ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}</td>
            <td>Rp {{ number_format($b->package->price ?? 0, 0, ',', '.') }}</td>
            <td>
              <form action="{{ route('admin.bookings.updateStatus', $b->id) }}" method="POST">
                @csrf @method('PATCH')
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                  @foreach (['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $val => $text)
                    <option value="{{ $val }}" @selected($b->status === $val)>{{ $text }}</option>
                  @endforeach
                </select>
              </form>
            </td>
            <td>
              <form action="{{ route('admin.bookings.destroy', $b->id) }}" method="POST"
                    onsubmit="return confirm('Hapus booking ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-secondary">Belum ada booking.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">{{ $bookings->withQueryString()->links() }}</div>
</div>
@endsection