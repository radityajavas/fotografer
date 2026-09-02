@extends('booking.layout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="card" style="width: 30rem;">
            <div class="card-header">Tambah Booking Baru</div>
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
                    <!-- Input Nama Pelanggan Diketik Sendiri -->
                    <div class="form-group">
                        <label for="nama_pelanggan">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" class="form-control" id="nama_pelanggan" placeholder="Ketik nama kamu di sini..." required>
                    </div>

                    <!-- Dropdown Fotografer -->
                    <div class="form-group">
                        <label for="photographer_id">Fotografer</label>
                        <select name="photographer_id" class="form-control" id="photographer_id" required>
                            <option value="">-- Pilih Fotografer --</option>
                            @foreach ($photographers as $fg)
                                <option value="{{ $fg->id }}">{{ $fg->name }} ({{ $fg->specialization }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dropdown Paket Foto -->
                    <div class="form-group">
                        <label for="package_id">Paket Foto</label>
                        <select name="package_id" class="form-control" id="package_id" required>
                            <option value="">-- Pilih Paket Foto --</option>
                            @foreach ($packages as $pkt)
                                <option value="{{ $pkt->id }}">{{ $pkt->name }} - Rp {{ number_format($pkt->price, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Booking -->
                    <div class="form-group">
                        <label for="tanggal_booking">Tanggal Booking</label>
                        <input type="date" name="tanggal_booking" class="form-control" id="tanggal_booking" required>
                    </div>

                    <!-- Alamat Foto -->
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
@endsection