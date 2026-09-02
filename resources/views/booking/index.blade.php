@extends('booking.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left mt-2">
            <h2>REKAYASA PERANGKAT LUNAK - DATA BOOKING FOTOGRAFER</h2>
        </div>
        <div class="float-right my-2">
            <a class="btn btn-success" href="{{ route('booking.create') }}"> Input Booking</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Nama Pelanggan</th>
        <th>Fotografer</th>
        <th>Paket Foto</th>
        <th>Tanggal Booking</th>
        <th>Alamat</th>
        <th>Status</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($booking as $index => $b)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $b->nama_pelanggan }}</td>
        <td>{{ $b->photographer->name ?? '-' }}</td>
        <td>{{ $b->package->name ?? '-' }}</td>
        <td>{{ $b->tanggal_booking }}</td>
        <td>{{ $b->alamat }}</td>
        <td><span class="badge badge-info">{{ $b->status }}</span></td>
        <td>
            <form action="{{ route('booking.destroy', $b->id_booking) }}" method="POST">
                <a class="btn btn-info" href="{{ route('booking.show', $b->id_booking) }}">Show</a>
                <a class="btn btn-primary" href="{{ route('booking.edit', $b->id_booking) }}">Edit</a>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin menghapus data ini?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

<div class="d-flex justify-content-center">
    {!! $booking->links() !!}
</div>
@endsection