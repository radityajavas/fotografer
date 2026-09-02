@extends('pelanggan.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left mt-2">
            <h2>DATA PELANGGAN</h2>
        </div>
        <div class="float-right my-2">
            <a class="btn btn-success" href="{{ route('pelanggan.create') }}"> Input Pelanggan</a>
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
        <th>Nama</th>
        <th>Email</th>
        <th>No HP</th>
        <th>Alamat</th>
        <th width="280px">Action</th>
    </tr>
    @foreach ($pelanggan as $p)
    <tr>
        <td>{{ $p->nama }}</td>
        <td>{{ $p->email }}</td>
        <td>{{ $p->no_hp }}</td>
        <td>{{ $p->alamat }}</td>
        <td>
            <form action="{{ route('pelanggan.destroy', $p->id_pelanggan) }}" method="POST">
                <a class="btn btn-info" href="{{ route('pelanggan.show', $p->id_pelanggan) }}">Show</a>
                <a class="btn btn-primary" href="{{ route('pelanggan.edit', $p->id_pelanggan) }}">Edit</a>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>

{{-- Link Pagination Modul 7 --}}
<div class="d-flex justify-content-center">
    {!! $pelanggan->links() !!}
</div>
@endsection