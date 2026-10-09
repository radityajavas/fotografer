@extends('layouts.profil.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h4 class="mb-4">Edit Profil</h4>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('profile.update') }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="text-center mb-4">
                    @if ($user->photo)
                    <img
                        src="{{ asset('storage/' . $user->photo) }}"
                        alt="Foto Profil"
                        class="rounded-circle mb-3"
                        width="100"
                        height="100"
                        style="object-fit: cover;">
                    @endif
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $user->name) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $user->email) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor Telepon</label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        class="form-control @if ($errors->has('phone')) is-invalid @endif"
                        value="{{ old('phone', $user->phone ?? '') }}"
                        placeholder="Masukkan 8–15 digit nomor"
                        inputmode="numeric"
                        maxlength="15"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15); validasiTelepon(this)">

                    <div id="phoneError" class="text-danger small mt-1"
                        style="display: none;">
                        ❗ Nomor telepon harus terdiri dari 8–15 digit angka.
                    </div>

                    @error('phone')
                    <div class="text-danger small mt-1">
                        ❗ {{ $message }}
                    </div>
                    @enderror
                </div>

                <script>
                    function validasiTelepon(input) {
                        const error = document.getElementById('phoneError');
                        const jumlah = input.value.length;

                        if (jumlah > 0 && jumlah < 8) {
                            input.classList.add('is-invalid');
                            error.style.display = 'block';
                        } else {
                            input.classList.remove('is-invalid');
                            error.style.display = 'none';
                        }
                    }
                </script>

                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea
                        id="address"
                        name="address"
                        class="form-control"
                        rows="3"
                        placeholder="Masukkan alamat">{{ old('address', $user->address ?? '') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="photo" class="form-label">Ganti Foto Profil</label>
                    <input
                        type="file"
                        id="photo"
                        name="photo"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp">

                    <small class="text-muted">
                        Format JPG, PNG, atau WEBP. Maksimal 2 MB.
                    </small>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('profile') }}"
                        class="btn btn-outline-secondary w-50">
                        Batal
                    </a>

                    <button type="submit"
                        class="btn btn-success w-50">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection