@extends('layouts.app')

@section('content')

<h1 class="fw-bold mb-1" style="letter-spacing:-0.02em;">Temukan fotografer untuk momenmu</h1>
<p class="text-secondary mb-4">Pilih kota dan kategori, lihat fotografer yang tersedia, lalu buat pesanan.</p>

<form action="{{ route('fotografer.cari') }}" method="GET" class="search-bar mb-4">
    <input type="text" name="lokasi" value="{{ request('lokasi') }}" placeholder="Cari lokasi, mis. Malang">
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
                    <th>Hasil Foto</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($photographers as $item)
                    @php
                        $chips = array_filter(array_map('trim', explode(',', $item->specialization ?? '')));
                        $foto  = $item->photo ?? null;
                        $hasil = $item->photos->take(3);
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
                                    <a href="{{ route('photographers.show', $item->id) }}" class="fw-semibold text-decoration-none text-reset">{{ $item->name }}</a>
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
                            @if ($hasil->isEmpty())
                                <span class="text-secondary small">Belum ada foto</span>
                            @else
                                <a href="{{ route('photographers.show', $item->id) }}" class="d-flex align-items-center text-decoration-none">
                                    @foreach ($hasil as $p)
                                        <img src="{{ $p->url }}" alt="Hasil foto {{ $item->name }}"
                                             style="width:52px;height:52px;object-fit:cover;border-radius:6px;margin-right:6px">
                                    @endforeach
                                    @if ($item->photos->count() > 3)
                                        <span class="small text-secondary">+{{ $item->photos->count() - 3 }}</span>
                                    @endif
                                </a>
                            @endif
                        </td>

                        <td>
                            @if ($item->status == 'AVAILABLE')
                                <span class="badge rounded-pill text-bg-success fw-medium">Tersedia</span>
                            @else
                                <span class="badge rounded-pill text-bg-secondary fw-medium">Tidak tersedia</span>
                            @endif
                        </td>

                        <td class="text-end" style="white-space:nowrap">
                            <a href="{{ route('photographers.show', $item->id) }}" class="btn btn-outline-secondary btn-sm px-3">Lihat</a>
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
                        <td colspan="5" class="text-center text-secondary py-5">Belum ada fotografer.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection