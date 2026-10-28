@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px; font-family: sans-serif;">
    
    <div style="margin-bottom: 20px;">
        <a href="{{ route('landing') }}" class="btn btn-brand-outline btn-sm px-3 d-inline-flex align-items-center gap-2">
            <span>&larr;</span> Kembali ke Beranda
        </a>
    </div>

    <!-- Judul Halaman -->
    <div style="margin-bottom: 30px; text-align: center;">
        <h1 style="font-size: 28px; font-weight: bold; color: #111; margin: 0;">Galeri Fotografer</h1>
        <p style="color: #6b7280; font-size: 14px; margin-top: 6px;">Jelajahi karya terbaik dari berbagai fotografer profesional.</p>
    </div>

    <!-- Grid card fotografer (responsif) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
        @forelse($photographers as $p)
            <a href="{{ route('photographers.show', ['id' => $p->id, 'return_to' => 'galeri']) }}"
               style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); text-decoration: none; display: flex; flex-direction: column; transition: transform 0.2s;">
                
                <!-- Bagian Foto Sampul (Landscape proporsional) -->
                <div style="position: relative; height: 200px; background: #f3f4f6; overflow: hidden;">
                    @if($p->photos->isNotEmpty())
                        <img src="{{ $p->photos->first()->url }}" 
                             style="width: 100%; height: 100%; object-fit: cover;" 
                             alt="Sampul Portofolio">
                    @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 13px;">
                            Belum ada foto
                        </div>
                    @endif
                    
                    <!-- Badge di Pojok Kiri Atas Foto -->
                    <span style="position: absolute; top: 12px; left: 12px; background: rgba(17, 24, 39, 0.85); color: white; font-size: 11px; font-weight: bold; padding: 4px 10px; border-radius: 6px; backdrop-filter: blur(4px); text-transform: uppercase; letter-spacing: 0.5px;">
                        {{ $p->photos->count() }} Foto
                    </span>
                </div>

                <!-- Bagian Informasi di Bawah Foto -->
                <div style="padding: 16px; background: white; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1;">
                    <div>
                        <h3 style="font-size: 16px; font-weight: bold; color: #111; margin: 0 0 4px 0;">
                            {{ $p->name }}
                        </h3>
                        <p style="font-size: 13px; color: #6b7280; margin: 0;">
                            {{ $p->specialization ?? 'Fotografer Profesional' }}
                        </p>
                    </div>
                    
                    <div style="margin-top: 12px; pt-2; border-top: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #4b5563;">
                        <span>📍 {{ $p->city ?? 'Malang' }}</span>
                        <span style="color: #0d9488; font-weight: 600;">Lihat Profil &rarr;</span>
                    </div>
                </div>

            </a>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px; background: white; border-radius: 16px; border: 1px solid #e5e7eb; color: #9ca3af;">
                Belum ada data fotografer.
            </div>
        @endforelse
    </div>

</div>
@endsection