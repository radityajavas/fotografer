@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Paket Foto</h2>
    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.packages.update', $package->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Paket</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $package->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $package->price) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Durasi (Jam)</label>
                <input type="number" name="duration_hours" class="form-control" value="{{ old('duration_hours', $package->duration_hours) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="description" class="form-control" rows="3" required>{{ old('description', $package->description) }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsections