@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Data Fotografer</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form Tambah Fotografer -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">Tambah Fotografer Baru</div>
        <div class="card-body">
            <form action="{{ route('admin.photographers.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Nama Fotografer" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" name="phone" class="form-control" placeholder="Nomor Telepon" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" name="specialization" class="form-control" placeholder="Spesialisasi (misal: Wedding)" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="status" class="form-control" required>
                            <option value="AVAILABLE">AVAILABLE</option>
                            <option value="UNAVAILABLE">UNAVAILABLE</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Simpan</button>
            </form>
        </div>
    </div>

    <!-- Tabel Data Fotografer -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>Nama</th>
                    <th>No HP</th>
                    <th>Spesialisasi</th>
                    <th>Status</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($photographers as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->specialization }}</td>
                    <td>
                        <span class="badge {{ $item->status == 'AVAILABLE' ? 'bg-success' : 'bg-danger' }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.photographers.edit', $item->id) }}" class="btn btn-warning btn-sm text-white">
                                Edit
                            </a>
                            <form action="{{ route('admin.photographers.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data fotografer.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection