@extends('booking.layout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="card" style="width: 28rem;">
            <div class="card-header">Detail Booking</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><b>Nama Pelanggan: </b>{{ $booking->nama_pelanggan }}</li>
                    <li class="list-group-item"><b>Fotografer: </b>{{ $booking->photographer->name ?? '-' }}</li>
                    <li class="list-group-item"><b>Paket Foto: </b>{{ $booking->package->name ?? '-' }}</li>
                    <li class="list-group-item"><b>Tanggal Booking: </b>{{ $booking->tanggal_booking }}</li>
                    <li class="list-group-item"><b>Alamat Foto: </b>{{ $booking->alamat }}</li>
                    <li class="list-group-item"><b>Status: </b>{{ $booking->status }}</li>
                </ul>
            </div>
            <a class="btn btn-success mt-3" href="{{ route('booking.index') }}">Kembali</a>
        </div>
    </div>
</div>
@endsection