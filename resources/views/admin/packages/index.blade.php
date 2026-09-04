@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Data Paket Foto</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form Tambah Paket -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-light">Tambah Paket Foto Baru</div>
        <div class="card-body">
            <form action="{{ route('admin.packages.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Nama Paket (misal: Platinum)" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="number" name="price" class="form-control" placeholder="Harga (Rp)" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <input type="number" name="duration_hours" class="form-control" placeholder="Durasi (Jam)" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="text" name="description" class="form-control" placeholder="Deskripsi Singkat" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Simpan Paket</button>
            </form>
        </div>
    </div>

    <!-- Tabel Data Paket -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th width="50">No</th>
                    <th>Nama Paket</th>
                    <th>Harga</th>
                    <th>Durasi</th>
                    <th>Deskripsi</th>
                    <th width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($packages as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->duration_hours }} Jam</td>
                    <td>{{ $item->description }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.packages.edit', $item->id) }}" class="btn btn-warning btn-sm text-white">Edit</a>
                            <form action="{{ route('admin.packages.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data paket foto.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection