@extends('layouts.admin')
@section('title', 'Edit Paket')

@section('content')
<form class="card" method="POST" action="{{ route('admin.packages.update', $package->id) }}">
  @csrf @method('PUT')
  <div class="card-body">
    @include('admin.packages._fields')
  </div>
  <div class="card-footer text-end">
    <a href="{{ route('admin.packages.index') }}" class="btn">Batal</a>
    <button class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
  </div>
</form>
@endsection