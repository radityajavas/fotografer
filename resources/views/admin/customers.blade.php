@extends('layouts.admin')
@section('title', 'Data Pelanggan')

@section('content')
<div class="card">
  <div class="card-header">
    <div>
      <h3 class="card-title">Daftar Pelanggan</h3>
      <div class="text-secondary small">Kelola data pelanggan yang terdaftar di platform.</div>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead>
        <tr><th>Nama</th><th>Email</th><th>No HP</th><th>Alamat</th><th class="w-1"></th></tr>
      </thead>
      <tbody>
        @forelse ($pelanggan as $c)
          <tr>
            <td>{{ $c->name }}</td>
            <td>{{ $c->email }}</td>
            <td>{{ $c->phone ?? '-' }}</td>
            <td>{{ $c->address ?? '-' }}</td>
            <td>
              <form action="{{ route('admin.customers.destroy', $c->id) }}" method="POST"
                    onsubmit="return confirm('Hapus pelanggan ini?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-secondary">Belum ada pelanggan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection