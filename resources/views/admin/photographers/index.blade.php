@extends('layouts.admin')
@section('title', 'Fotografer')

@section('content')

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Fotografer</h3>
    <div class="card-actions">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add"><i class="ti ti-plus me-1"></i>Tambah</button>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead><tr><th>Nama</th><th>Telepon</th><th>Kota</th><th>Spesialisasi</th><th>Status</th><th class="w-1"></th></tr></thead>
      <tbody>
        @forelse ($photographers as $p)
          <tr>
            <td>{{ $p->name }}</td>
            <td>{{ $p->phone }}</td>
            <td>{{ $p->city }}</td>
            <td>{{ $p->specialization }}</td>
            <td>
              <span class="badge {{ $p->status === 'AVAILABLE' ? 'bg-green-lt' : 'bg-red-lt' }}">{{ $p->status }}</span>
            </td>
            <td class="text-nowrap">
              <a href="{{ route('admin.photographers.edit', $p->id) }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-pencil me-1"></i>Edit</a>
              <form action="{{ route('admin.photographers.destroy', $p->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Hapus fotografer ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash me-1"></i>Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-secondary">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="modal modal-blur fade" id="modal-add" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="{{ route('admin.photographers.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Fotografer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @include('admin.photographers._fields', ['photographer' => null])
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection