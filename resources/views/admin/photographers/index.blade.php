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
      <thead>
        <tr>
          <th>Nama</th>
          <th>Telepon</th>
          <th>Kota</th>
          <th>Spesialisasi</th>
          <th>Status</th>
          <th class="w-1"></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($photographers as $p)
        <tr>
          <!-- Menampilkan Foto Profil / Inisial di sebelah Nama -->
          <td>
            <div class="d-flex align-items-center py-1">
              @if($p->photo)
              <span class="avatar me-2"
                style="background-image: url('{{ asset('storage/' . $p->photo) }}'); background-size: cover; background-position: center;">
              </span>
              @else
              <span class="avatar me-2 bg-teal-lt">
                {{ strtoupper(substr($p->name, 0, 2)) }}
              </span>
              @endif 
              <div class="font-weight-medium">{{ $p->name }}</div>
            </div>
          </td>
          <td>{{ $p->phone }}</td>
          <td>{{ $p->city }}</td>
          <td>{{ $p->specialization }}</td>
          <td>
            <span class="badge {{ $p->status === 'AVAILABLE' ? 'bg-green-lt' : 'bg-red-lt' }}">{{ $p->status }}</span>
          </td>
          <td class="text-nowrap">
            <a href="{{ route('admin.photographers.photos', $p->id) }}" class="btn btn-sm btn-outline-secondary"><i class="ti ti-photo me-1"></i>Foto</a>
            <a href="{{ route('admin.photographers.edit', $p->id) }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-pencil me-1"></i>Edit</a>
            <form action="{{ route('admin.photographers.destroy', $p->id) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Hapus fotografer ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"><i class="ti ti-trash me-1"></i>Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-secondary">Belum ada data.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL TAMBAH (Ditambahkan enctype="multipart/form-data") -->
<div class="modal modal-blur fade" id="modal-add" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="{{ route('admin.photographers.store') }}" enctype="multipart/form-data">
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