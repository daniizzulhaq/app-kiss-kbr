@extends('layouts.dashboard')

@section('title', 'Detail Kelompok - BPDAS')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Header -->
    <div class="mb-6 md:mb-8 slide-in">
        <div class="flex flex-col gap-3">
            <a href="{{ route('bpdas.kelompok.index') }}" 
               class="text-blue-600 hover:text-blue-800 inline-flex items-center gap-2 text-sm md:text-base">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar
            </a>
            <div>
                <h1 class="text-2xl md:text-4xl font-bold text-gray-800 mb-2">👥 Detail Kelompok</h1>
                <p class="text-sm md:text-base text-gray-600">Informasi lengkap kelompok {{ $kelompok->nama_kelompok }}</p>
            </div>
        </div>
    </div>

    <!-- Layout Responsive: Stack Mobile, 2 Kolom Desktop -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        
        <!-- Data Kelompok -->
        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg md:shadow-xl p-4 md:p-8 border border-gray-100 slide-in">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-4 md:mb-6 pb-3 md:pb-4 border-b border-gray-200">
                📋 Informasi Kelompok
            </h2>

            <div class="space-y-4 md:space-y-6">

                <div>
                    <p class="text-xs md:text-sm text-gray-500 mb-1">Nama Kelompok</p>
                    <p class="text-base md:text-lg font-semibold text-gray-900">{{ $kelompok->nama_kelompok }}</p>
                </div>

                <div>
                    <p class="text-xs md:text-sm text-gray-500 mb-1">Pengelola</p>
                    <p class="text-sm md:text-base font-semibold text-gray-900">{{ $kelompok->user->name ?? '-' }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs md:text-sm text-gray-500 mb-1">Blok</p>
                        <p class="text-sm md:text-base font-semibold text-gray-900">{{ $kelompok->blok ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm text-gray-500 mb-1">Desa</p>
                        <p class="text-sm md:text-base font-semibold text-gray-900">{{ $kelompok->desa ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs md:text-sm text-gray-500 mb-1">Kecamatan</p>
                        <p class="text-sm md:text-base font-semibold text-gray-900">{{ $kelompok->kecamatan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm text-gray-500 mb-1">Kabupaten</p>
                        <p class="text-sm md:text-base font-semibold text-gray-900">{{ $kelompok->kabupaten ?? '-' }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-xs md:text-sm text-gray-500 mb-1">📍 Koordinat</p>
                    <p class="text-xs md:text-base font-semibold text-gray-900 font-mono bg-gray-50 px-3 py-2 rounded-lg break-all">
                        {{ $kelompok->koordinat ?? '-' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs md:text-sm text-gray-500 mb-1">Jumlah Anggota</p>
                        <p class="text-sm md:text-base font-semibold text-gray-900">
                            {{ $kelompok->anggota ?? '0' }} orang
                        </p>
                    </div>
                    <div>
                        <p class="text-xs md:text-sm text-gray-500 mb-1">Kontak</p>
                        <p class="text-sm md:text-base font-semibold text-gray-900 break-all">{{ $kelompok->kontak ?? '-' }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-xs md:text-sm text-gray-500 mb-1">SPKS</p>
                    <p class="text-sm md:text-base font-semibold text-gray-900">{{ $kelompok->spks ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-xs md:text-sm text-gray-500 mb-1">Rekening</p>
                    <p class="text-sm md:text-base font-semibold text-gray-900">{{ $kelompok->rekening ?? '-' }}</p>
                </div>

            </div>

            <!-- Dokumentasi Foto -->
            @if($kelompok->dokumentasi && count($kelompok->dokumentasi) > 0)
            <div class="mt-4 md:mt-6 pt-4 md:pt-6 border-t border-gray-200">
                <h3 class="text-base md:text-lg font-bold text-gray-800 mb-3 md:mb-4">📸 Dokumentasi</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 md:gap-3">
                    @foreach($kelompok->dokumentasi as $index => $foto)
                    <div class="relative group cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $foto) }}', {{ $index + 1 }})">
                        <img src="{{ asset('storage/' . $foto) }}" 
                             alt="Dokumentasi {{ $index + 1 }}"
                             class="w-full h-24 sm:h-32 object-cover rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-all duration-300 hover:shadow-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                            <span class="text-white opacity-0 group-hover:opacity-100 text-xs md:text-sm font-semibold">🔍 Lihat</span>
                        </div>
                        <div class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded">
                            #{{ $index + 1 }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="mt-4 md:mt-6 pt-4 md:pt-6 border-t border-gray-200">
                <h3 class="text-base md:text-lg font-bold text-gray-800 mb-3 md:mb-4">📸 Dokumentasi</h3>
                <div class="text-center py-6 md:py-8 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">Belum ada dokumentasi foto</p>
                </div>
            </div>
            @endif

        </div>

        <!-- Peta Lokasi -->
        <div class="bg-white rounded-xl md:rounded-2xl shadow-lg md:shadow-xl p-4 md:p-8 border border-gray-100 slide-in lg:sticky lg:top-6">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-3 md:mb-4 pb-3 md:pb-4 border-b border-gray-200">
                🗺️ Lokasi Kelompok
            </h2>

            @if($kelompok->koordinat)
            <div id="map" class="w-full h-64 sm:h-80 md:h-96 rounded-lg border-2 border-gray-300 shadow-inner mb-3 md:mb-4"></div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 md:p-4">
                <p class="text-xs md:text-sm font-semibold text-blue-800 mb-2">📍 Koordinat Lokasi:</p>
                <p class="text-xs md:text-sm text-blue-700 font-mono break-all">{{ $kelompok->koordinat }}</p>
                
                <p class="text-xs md:text-sm text-blue-800 font-semibold mt-2 md:mt-3 mb-1">Nama Kelompok:</p>
                <p class="text-xs md:text-sm text-blue-700">{{ $kelompok->nama_kelompok }}</p>

                @if($kelompok->desa || $kelompok->kecamatan)
                <p class="text-xs md:text-sm text-blue-800 font-semibold mt-2 md:mt-3 mb-1">Alamat:</p>
                <p class="text-xs md:text-sm text-blue-700">
                    {{ $kelompok->desa ? 'Desa ' . $kelompok->desa : '' }}
                    {{ $kelompok->kecamatan ? ', Kec. ' . $kelompok->kecamatan : '' }}
                </p>
                @endif
            </div>

            @php
                $coords = explode(',', $kelompok->koordinat);
                $lat = isset($coords[0]) ? trim($coords[0]) : null;
                $lng = isset($coords[1]) ? trim($coords[1]) : null;
            @endphp
            
            @if($lat && $lng)
            <a href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}" 
               target="_blank"
               class="mt-3 md:mt-4 w-full px-4 md:px-6 py-2.5 md:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center gap-2 text-sm md:text-base">
                <span>🌐</span>
                <span>Buka di Google Maps</span>
            </a>
            @endif

            @else
            <div class="text-center py-8 md:py-12 bg-gray-50 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-sm text-gray-500">📍 Koordinat belum diisi</p>
            </div>
            @endif

        </div>

    </div>
</div>

<!-- Image Modal - Responsive -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-2 md:p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl w-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" 
                class="absolute -top-8 md:-top-10 right-0 text-white hover:text-gray-300 text-xl md:text-2xl font-bold bg-black bg-opacity-50 rounded-full w-8 h-8 md:w-10 md:h-10 flex items-center justify-center">
            ✕
        </button>
        <img id="modalImage" src="" alt="Preview" class="w-full h-auto rounded-lg shadow-2xl max-h-[80vh] object-contain">
        <p id="modalCaption" class="text-white text-center mt-3 md:mt-4 font-semibold text-sm md:text-base"></p>
    </div>
</div>

@if($kelompok && $kelompok->koordinat)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const koordinat = "{{ $kelompok->koordinat }}";
    const coords = koordinat.split(',');
    const lat = parseFloat(coords[0].trim());
    const lng = parseFloat(coords[1].trim());

    if (isNaN(lat) || isNaN(lng)) {
        document.getElementById('map').innerHTML = 
            '<div class="h-full flex items-center justify-center text-gray-500">' +
            '<p class="text-sm">Format koordinat tidak valid</p></div>';
        return;
    }

    const map = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Responsive marker size
    const markerSize = window.innerWidth < 640 ? 25 : 30;
    
    const customIcon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="background-color: #2563eb; width: ${markerSize}px; height: ${markerSize}px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: ${markerSize-10}px;">📍</div>`,
        iconSize: [markerSize, markerSize],
        iconAnchor: [markerSize/2, markerSize/2]
    });

    const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

    let popupContent = '<div style="min-width: 180px; max-width: 250px;">';
    popupContent += '<h3 style="font-weight: bold; margin-bottom: 8px; color: #2563eb; font-size: 14px;">{{ $kelompok->nama_kelompok }}</h3>';
    
    @if($kelompok->desa || $kelompok->kecamatan)
    popupContent += '<p style="margin-bottom: 4px; font-size: 12px;">';
    popupContent += '{{ $kelompok->desa ? "Desa " . $kelompok->desa : "" }}';
    popupContent += '{{ $kelompok->kecamatan ? ", Kec. " . $kelompok->kecamatan : "" }}';
    popupContent += '</p>';
    @endif
    
    @if($kelompok->anggota)
    popupContent += '<p style="font-size: 12px; color: #666;">👥 {{ $kelompok->anggota }} Anggota</p>';
    @endif
    
    popupContent += '<p style="font-family: monospace; font-size: 11px; color: #666; margin-top: 8px; padding-top: 8px; border-top: 1px solid #ddd;">';
    popupContent += lat.toFixed(6) + ', ' + lng.toFixed(6);
    popupContent += '</p>';
    popupContent += '</div>';

    marker.bindPopup(popupContent).openPopup();

    // Responsive circle radius
    const circleRadius = window.innerWidth < 640 ? 300 : 500;
    
    L.circle([lat, lng], {
        color: '#2563eb',
        fillColor: '#3b82f6',
        fillOpacity: 0.1,
        radius: circleRadius
    }).addTo(map);

    setTimeout(function() {
        map.invalidateSize();
    }, 100);

    // Handle window resize for map
    window.addEventListener('resize', function() {
        map.invalidateSize();
    });
});

function openImageModal(src, index) {
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('modalImage').src = src;
    document.getElementById('modalCaption').textContent = `Dokumentasi Foto ${index}`;
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Touch swipe to close modal on mobile
let touchStartY = 0;
document.getElementById('imageModal')?.addEventListener('touchstart', function(e) {
    touchStartY = e.touches[0].clientY;
});

document.getElementById('imageModal')?.addEventListener('touchend', function(e) {
    const touchEndY = e.changedTouches[0].clientY;
    if (touchStartY - touchEndY > 50) { // Swipe up
        closeImageModal();
    }
});
</script>
@endif

@endsection