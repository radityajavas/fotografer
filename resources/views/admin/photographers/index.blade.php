@extends('layouts.app') {{-- sesuaikan dengan layout admin kamu --}}

@section('content')
<div class="container mt-4">
    <h2>Data Fotografer</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-header">Tambah Fotografer Baru</div>
        <div class="card-body">
            <form action="{{ route('admin.photographers.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="name" class="form-control" placeholder="Nama Fotografer" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" name="phone" class="form-control" placeholder="Nomor Telepon" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" name="specialization" class="form-control" placeholder="Spesialisasi (misal: Wedding)" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>No HP</th>
                <th>Spesialisasi</th>
                <th>Status</th>
                <th>Aksi</th>
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
                    <span class="badge {{ $item->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                        {{ strtoupper($item->status) }}
                    </span>
                </td>
                <td>
                    <form action="{{ route('admin.photographers.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada data fotografer.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection