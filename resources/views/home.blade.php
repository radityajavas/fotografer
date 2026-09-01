@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard Booking') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h5 class="mb-3">Selamat datang di Sistem Booking, {{ Auth::user()->name }}!</h5>

                    <p class="text-muted">Detail Akun Anda:</p>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><strong>Username:</strong> {{ Auth::user()->username }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ Auth::user()->email }}</li>
                    </ul>

                    <a href="#" class="btn btn-primary">Buat Pesanan Baru</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection