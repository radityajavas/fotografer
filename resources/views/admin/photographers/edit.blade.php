@extends('layouts.admin')

@section('title', 'Edit Data Fotografer')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Ubah informasi fotografer</h3>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.photographers.update', $photographer->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Foto Profil</label>
                <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/png, image/jpeg, image/webp">
                <div class="form-text">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</div>
                @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Nama fotografer</label>
                <input type="text" name="name" value="{{ old('name', $photographer->name) }}"
                    class="form-control @error('name') is-invalid @enderror" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $photographer->phone) }}"
                    class="form-control @error('phone') is-invalid @enderror" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kota</label>
                <input type="text" name="city" value="{{ old('city', $photographer->city ?? '') }}" class="form-control"
                    placeholder="mis. Malang">
            </div>

            <div class="mb-3">
                <label class="form-label">Spesialisasi</label>
                <input type="text" name="specialization"
                    value="{{ old('specialization', $photographer->specialization) }}"
                    class="form-control @error('specialization') is-invalid @enderror" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status ketersediaan</label>
                <select name="status" class="form-select">
                    <option value="AVAILABLE" @selected(old('status', $photographer->status) == 'AVAILABLE')>
                        AVAILABLE
                    </option>
                    <option value="UNAVAILABLE" @selected(old('status', $photographer->status) == 'UNAVAILABLE')>
                        UNAVAILABLE
                    </option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan perubahan</button>
                <a href="{{ route('admin.photographers.index') }}" class="btn">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection