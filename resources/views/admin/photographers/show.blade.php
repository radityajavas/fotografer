@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 30px 20px; font-family: sans-serif;">
    
    <!-- Tombol Kembali ke Galeri -->
    <div style="margin-bottom: 20px;">
        <a href="{{ url('/galeri') }}" 
           style="display: inline-flex; align-items: center; gap: 8px; background: white; color: #374151; border: 1px solid #e5e7eb; padding: 10px 18px; border-radius: 10px; font-size: 14px; font-weight: 600; text-decoration: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;"
           onmouseover="this.style.background='#f9fafb'; this.style.borderColor='#d1d5db';" 
           onmouseout="this.style.background='white'; this.style.borderColor='#e5e7eb';">
            <span>&larr;</span> Kembali ke Galeri
        </a>
    </div>

    <!-- Header Profil Fotografer -->
    <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; padding: 24px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        
        <div style="display: flex; align-items: center; gap: 20px;">
            <!-- Lingkaran Avatar Inisial -->
            <div style="width: 70px; height: 70px; border-radius: 50%; background: #ccfbf1; color: #115e59; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 24px; flex-shrink: 0;">
                {{ strtoupper(substr($photographer->name ?? $photographer->nama, 0, 1)) }}
            </div>
            
            <div>
                <h1 style="font-size: 22px; font-weight: bold; margin: 0; color: #111;">{{ $photographer->name ?? $photographer->nama }}</h1>
                
                <div style="margin-top: 6px; font-size: 14px; color: #4b5563;">
                    <p style="margin: 0 0 2px 0;">📞 {{ $photographer->phone ?? $photographer->telephone ?? '-' }}</p>
                    <p style="margin: 0;">📍 {{ $photographer->city ?? $photographer->alamat ?? 'Malang, Jawa Timur' }}</p>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 24px; border-left: 1px solid #e5e7eb; padding-left: 24px;">
            <div>
                <p style="margin: 0; font-size: 12px; color: #9ca3af;">Spesialisasi</p>
                <p style="margin: 4px 0 0 0; font-size: 14px; font-weight: bold; color: #1f2937;">{{ $photographer->specialization ?? '-' }}</p>
            </div>
            <div>
                <p style="margin: 0; font-size: 12px; color: #9ca3af;">Status</p>
                <p style="margin: 4px 0 0 0; font-size: 13px; font-weight: 600; color: #059669; background: #ecfdf5; padding: 2px 8px; border-radius: 4px;">
                    {{ $photographer->status ?? 'AVAILABLE' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Grid Hasil Foto Portofolio -->
    <div style="background: white; border-radius: 16px; border: 1px solid #e5e7eb; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <h2 style="font-size: 18px; font-weight: bold; margin-top: 0; margin-bottom: 20px; color: #111;">Hasil Foto Portofolio</h2>
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            @forelse($photographer->photos as $photo)
                <div onclick="openModal('{{ $photo->url }}')" 
                     style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.04); cursor: pointer; transition: transform 0.2s;"
                     onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                    
                    <div style="position: relative; height: 190px; background: #f3f4f6;">
                        <img src="{{ $photo->url }}" 
                             style="width: 100%; height: 100%; object-fit: cover;" 
                             alt="Hasil Foto">
                        
                        @if($photo->kategori)
                            <span style="position: absolute; top: 10px; left: 10px; background: rgba(17, 24, 39, 0.85); color: white; font-size: 11px; font-weight: bold; padding: 3px 8px; border-radius: 6px; backdrop-filter: blur(4px);">
                                {{ $photo->kategori }}
                            </span>
                        @endif
                    </div>

                    <div style="padding: 12px 16px; background: white;">
                        <p style="margin: 0; font-size: 13px; font-weight: 600; color: #374151;">
                            {{ $photographer->name }}
                        </p>
                        <p style="margin: 2px 0 0 0; font-size: 11px; color: #9ca3af;">
                            {{ $photographer->specialization ?? 'Portofolio' }}
                        </p>
                    </div>

                </div>
            @empty
                <div style="grid-column: span 3; text-align: center; padding: 40px; color: #9ca3af; font-size: 14px;">
                    Belum ada hasil foto yang diunggah oleh fotografer ini.
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Pop-up Modal Gambar + Tombol Silang (X) -->
<div id="imageModal" onclick="closeModal()" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.85); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    
    <button onclick="closeModal()" 
            style="position: absolute; top: 20px; right: 25px; background: rgba(255,255,255,0.25); color: white; border: none; width: 44px; height: 44px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold; line-height: 1; padding: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.3); transition: background 0.2s;"
            onmouseover="this.style.background='rgba(255,255,255,0.4)'" 
            onmouseout="this.style.background='rgba(255,255,255,0.25)'">
        ✕
    </button>
    
    <img id="modalImg" src="" onclick="event.stopPropagation()" style="max-width: 90%; max-height: 85vh; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); object-fit: contain;">
</div>

<script>
    function openModal(imgUrl) {
        document.getElementById('modalImg').src = imgUrl;
        document.getElementById('imageModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }
</script>
@endsection