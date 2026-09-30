@extends('layouts.admin')
@section('title', 'Data Paket')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Paket Fotografi</h3>
    <div class="card-actions">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add">Tambah</button>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead>
        <tr><th>Nama Paket</th><th>Harga</th><th>Durasi</th><th>Deskripsi</th><th class="w-1"></th></tr>
      </thead>
      <tbody>
        @forelse ($packages as $pkg)
          <tr>
            <td>{{ $pkg->name }}</td>
            <td>Rp {{ number_format($pkg->price, 0, ',', '.') }}</td>
            <td>{{ $pkg->duration_hours }} jam</td>
            <td class="text-secondary">{{ \Illuminate\Support\Str::limit($pkg->description, 60) }}</td>
            <td class="text-nowrap">
              <a href="{{ route('admin.packages.edit', $pkg->id) }}" class="btn btn-sm">Edit</a>
              <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Hapus paket ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-secondary">Belum ada paket.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="modal modal-blur fade" id="modal-add" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="{{ route('admin.packages.store') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Paket</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @include('admin.packages._fields', ['package' => null])
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection