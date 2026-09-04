@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Data Fotografer</h2>

    <!-- Tampilkan Alert Jika Validasi Gagal -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.photographers.update', $photographer->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Fotografer</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $photographer->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $photographer->phone) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Spesialisasi</label>
                <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $photographer->specialization) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="AVAILABLE" {{ old('status', $photographer->status) == 'AVAILABLE' ? 'selected' : '' }}>AVAILABLE</option>
                    <option value="UNAVAILABLE" {{ old('status', $photographer->status) == 'UNAVAILABLE' ? 'selected' : '' }}>UNAVAILABLE</option>
                </select>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.photographers.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection