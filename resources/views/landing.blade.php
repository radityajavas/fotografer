@extends('layouts.app')

@section('content')

<h1 class="fw-bold mb-1" style="letter-spacing:-0.02em;">Temukan fotografer untuk momenmu</h1>
<p class="text-secondary mb-4">Pilih kota dan kategori, lihat fotografer yang tersedia, lalu buat pesanan.</p>

<form action="{{ route('fotografer.cari') }}" method="GET" class="search-bar mb-4">
    <select name="kota" style="border-left: 0;">
        <option value="">Semua kota</option>
        @foreach ($cities as $city)
            <option value="{{ $city }}" @selected(request('kota') == $city)>{{ $city }}</option>
        @endforeach
    </select>
    <select name="kategori">
        <option value="">Semua kategori</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat }}" @selected(request('kategori') == $cat)>
                {{ \Illuminate\Support\Str::ucfirst($cat) }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-brand">Cari</button>
</form>

<div class="table-wrap">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Fotografer</th>
                    <th>Spesialisasi</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($photographers as $item)
                    @php
                        $chips = array_filter(array_map('trim', explode(',', $item->specialization ?? '')));
                        $foto  = $item->photo ?? null;
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if ($foto)
                                    <img src="{{ asset('storage/' . $foto) }}" alt="{{ $item->name }}" class="avatar">
                                @else
                                    <div class="avatar avatar-fallback">{{ substr($item->name, 0, 2) }}</div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $item->name }}</div>
                                    <div class="small text-secondary">
                                        {{ $item->phone }}@if ($item->city) &bull; {{ $item->city }}@endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            @forelse ($chips as $c)
                                <span class="chip">{{ $c }}</span>
                            @empty
                                <span class="text-secondary small">-</span>
                            @endforelse
                        </td>

                        <td>
                            @if ($item->status == 'AVAILABLE')
                                <span class="badge rounded-pill text-bg-success fw-medium">Tersedia</span>
                            @else
                                <span class="badge rounded-pill text-bg-secondary fw-medium">Tidak tersedia</span>
                            @endif
                        </td>

                        <td class="text-end">
                            @if ($item->status == 'AVAILABLE')
                                <a href="{{ route('booking.create', ['photographer_id' => $item->id]) }}"
                                   class="btn btn-brand btn-sm px-3">Pesan</a>
                            @else
                                <button class="btn btn-outline-secondary btn-sm px-3" disabled>Pesan</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-5">Belum ada fotografer.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection