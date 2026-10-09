@extends('layouts.admin')
@section('title', 'Data Pelanggan')

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Daftar Pelanggan</h3>
      <div class="text-secondary small">Kelola data pelanggan yang terdaftar di platform.</div>
    </div>
    <div class="card-actions d-flex gap-2">
      <form method="GET" action="{{ route('admin.customers.index') }}" class="d-flex gap-2">
        <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama / email / HP">
        <button class="btn btn-icon" aria-label="Cari"><i class="ti ti-search"></i></button>
      </form>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add">
        <i class="ti ti-plus me-1"></i>Tambah
      </button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead>
        <tr><th>Nama</th><th>Email</th><th>No HP</th><th>Alamat</th><th class="w-1">Aksi</th></tr>
      </thead>
      <tbody>
        @forelse ($pelanggan as $c)
          <tr>
            <td class="fw-semibold">{{ $c->name }}</td>
            <td>{{ $c->email }}</td>
            <td>{{ $c->phone ?: '-' }}</td>
            <td class="text-secondary">{{ \Illuminate\Support\Str::limit($c->address ?: '-', 50) }}</td>
            <td class="text-nowrap">
              <a href="{{ route('admin.customers.edit', $c->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="ti ti-pencil me-1"></i>Edit
              </a>
              <form action="{{ route('admin.customers.destroy', $c->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Hapus pelanggan ini? Booking miliknya ikut terhapus.')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash me-1"></i>Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-secondary py-4">
            {{ $q !== '' ? 'Tidak ada pelanggan yang cocok.' : 'Belum ada pelanggan.' }}
          </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($pelanggan->hasPages())
    <div class="card-footer">{{ $pelanggan->links() }}</div>
  @endif
</div>

<div class="modal modal-blur fade" id="modal-add" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="{{ route('admin.customers.store') }}">
      @csrf
      <input type="hidden" name="_from" value="add">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Pelanggan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @include('admin.customers._fields', ['customer' => null])
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- Kalau validasi form tambah gagal, buka kembali modalnya --}}
@if ($errors->any() && old('_from') === 'add')
  @push('scripts')
  <script>new bootstrap.Modal(document.getElementById('modal-add')).show();</script>
  @endpush
@endif
@endsection
