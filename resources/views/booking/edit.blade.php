@extends('booking.layout')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="card" style="width: 30rem;">
            <div class="card-header">Edit Data Booking</div>
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
                <form method="post" action="{{ route('booking.update', $booking->id_booking) }}" id="myForm">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_pelanggan">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" class="form-control" id="nama_pelanggan" value="{{ $booking->nama_pelanggan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="photographer_id">Fotografer</label>
                        <select name="photographer_id" class="form-control" id="photographer_id">
                            @foreach ($photographers as $fg)
                                <option value="{{ $fg->id }}" {{ $booking->photographer_id == $fg->id ? 'selected' : '' }}>
                                    {{ $fg->name }} ({{ $fg->specialization }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="package_id">Paket Foto</label>
                        <select name="package_id" class="form-control" id="package_id">
                            @foreach ($packages as $pkt)
                                <option value="{{ $pkt->id }}" {{ $booking->package_id == $pkt->id ? 'selected' : '' }}>
                                    {{ $pkt->name }} - Rp {{ number_format($pkt->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_booking">Tanggal Booking</label>
                        <input type="date" name="tanggal_booking" class="form-control" id="tanggal_booking" value="{{ $booking->tanggal_booking }}">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat Lokasi Foto</label>
                        <textarea name="alamat" class="form-control" id="alamat" rows="2">{{ $booking->alamat }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <input type="text" name="status" class="form-control" id="status" value="{{ $booking->status }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-success" href="{{ route('booking.index') }}">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection