@extends('layouts.admin')
@section('title', 'Edit Pelanggan')

@section('content')
<form class="card" method="POST" action="{{ route('admin.customers.update', $customer->id) }}">
  @csrf @method('PUT')
  <div class="card-body">
    @include('admin.customers._fields')
  </div>
  <div class="card-footer text-end">
    <a href="{{ route('admin.customers.index') }}" class="btn">Batal</a>
    <button class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>Simpan</button>
  </div>
</form>
@endsection
