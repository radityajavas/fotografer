@extends('booking.layout')

@section('content')
<!-- CSS Flatpickr untuk kalender -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Styling tanggal terisi: warna merah & tidak bisa diklik */
    .flatpickr-day.flatpickr-disabled, 
    .flatpickr-day.flatpickr-disabled:hover {
        background-color: #dc3545 !important;
        color: white !important;
        border-color: #dc3545 !important;
        cursor: not-allowed !important;
        opacity: 0.8;
        text-decoration: line-through;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="card" style="width: 30rem;">
            <div class="card-header font-weight-bold">Tambah Booking Baru</div>
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Inputan belum valid.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="post" action="{{ route('booking.store') }}" id="myForm">
                    @csrf

                    <!-- Input Nama Pelanggan (Diketik Manual) -->
                    <div class="form-group">
                        <label for="nama_pelanggan">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" class="form-control" id="nama_pelanggan" placeholder="Masukkan nama pelanggan..." required>
                    </div>

                    <!-- Dropdown Fotografer -->
                    <div class="form-group">
                        <label for="photographer_id">Fotografer</label>
                        <select name="photographer_id" class="form-control" id="photographer_id" required>
                            <option value="">-- Pilih Fotografer --</option>
                            @foreach ($photographers as $fg)
                                <option value="{{ $fg->id_photographer ?? $fg->id }}">{{ $fg->name }} ({{ $fg->specialization }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dropdown Paket Foto -->
                    <div class="form-group">
                        <label for="package_id">Paket Foto</label>
                        <select name="package_id" class="form-control" id="package_id" required>
                            <option value="">-- Pilih Paket Foto --</option>
                            @foreach ($packages as $pkt)
                                <option value="{{ $pkt->id_package ?? $pkt->id }}">{{ $pkt->name }} - Rp {{ number_format($pkt->price, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input Tanggal Booking (Flatpickr) -->
                    <div class="form-group">
                        <label for="tanggal_booking">Tanggal Booking</label>
                        <input type="text" name="tanggal_booking" class="form-control bg-white" id="tanggal_booking" placeholder="Pilih Tanggal..." required readonly>
                    </div>

                    <!-- Input Alamat -->
                    <div class="form-group">
                        <label for="alamat">Alamat Lokasi Foto</label>
                        <textarea name="alamat" class="form-control" id="alamat" rows="2" placeholder="Masukkan alamat lokasi..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-success" href="{{ route('booking.index') }}">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const bookedDates = JSON.parse('<?php /** @var array $bookedDates */ echo json_encode($bookedDates); ?>');

    // Jalankan plugin kalender Flatpickr
    flatpickr("#tanggal_booking", {
        dateFormat: "Y-m-d",
        minDate: "today",     // Tanggal yang sudah lewat tidak bisa dipilih
        disable: bookedDates   // Tanggal yang ada di DB mati & jadi warna merah
    });
</script>
@endsection