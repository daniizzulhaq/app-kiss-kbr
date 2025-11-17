@extends('layouts.dashboard')

@section('title', 'Data Kelompok - Sistem KBR')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8 slide-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">👥 Data Kelompok</h1>
                <p class="text-gray-600">Detail informasi kelompok yang Anda kelola</p>
            </div>

            @if($kelompok)
            <a href="{{ route('kelompok.data-kelompok.edit', $kelompok) }}"
               class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                ✏️ Edit Data
            </a>
            @endif
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-600 p-4 rounded-lg slide-in">
            <p class="text-green-800 font-medium">✅ {{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-600 p-4 rounded-lg slide-in">
            <p class="text-red-800 font-medium">❌ {{ session('error') }}</p>
        </div>
    @endif


    @if($kelompok)
    
    <!-- Layout 2 Kolom: Data & Peta -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Data Kelompok -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 slide-in">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b border-gray-200">
                📋 Informasi Kelompok
            </h2>

            <div class="space-y-6">

                <div>
                    <p class="text-sm text-gray-500 mb-1">Nama Kelompok</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $kelompok->nama_kelompok }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Blok</p>
                        <p class="text-base font-semibold text-gray-900">{{ $kelompok->blok ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Desa</p>
                        <p class="text-base font-semibold text-gray-900">{{ $kelompok->desa ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kecamatan</p>
                        <p class="text-base font-semibold text-gray-900">{{ $kelompok->kecamatan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kabupaten</p>
                        <p class="text-base font-semibold text-gray-900">{{ $kelompok->kabupaten ?? '-' }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">📍 Koordinat</p>
                    <p class="text-base font-semibold text-gray-900 font-mono bg-gray-50 px-3 py-2 rounded-lg">
                        {{ $kelompok->koordinat ?? '-' }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Jumlah Anggota</p>
                        <p class="text-base font-semibold text-gray-900">
                            {{ $kelompok->anggota ?? '0' }} orang
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Kontak</p>
                        <p class="text-base font-semibold text-gray-900">{{ $kelompok->kontak ?? '-' }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">SPKS</p>
                    <p class="text-base font-semibold text-gray-900">{{ $kelompok->spks ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500 mb-1">Rekening</p>
                    <p class="text-base font-semibold text-gray-900">{{ $kelompok->rekening ?? '-' }}</p>
                </div>

            </div>

            <!-- Button Delete -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <form action="{{ route('kelompok.data-kelompok.destroy', $kelompok) }}" 
                    method="POST"
                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kelompok ini?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        class="w-full px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 shadow-md hover:shadow-lg transition-all duration-300 font-semibold">
                        🗑️ Hapus Data Kelompok
                    </button>
                </form>
            </div>

        </div>

        <!-- Peta Lokasi -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 slide-in lg:sticky lg:top-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-4 border-b border-gray-200">
                🗺️ Lokasi Kelompok
            </h2>

            @if($kelompok->koordinat)
            <!-- Map Container -->
            <div id="map" class="w-full h-96 rounded-lg border-2 border-gray-300 shadow-inner mb-4"></div>

            <!-- Info Box -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm font-semibold text-green-800 mb-2">📍 Koordinat Lokasi:</p>
                <p class="text-sm text-green-700 font-mono">{{ $kelompok->koordinat }}</p>
                
                @if($kelompok->nama_kelompok)
                <p class="text-sm text-green-800 font-semibold mt-3 mb-1">Nama Kelompok:</p>
                <p class="text-sm text-green-700">{{ $kelompok->nama_kelompok }}</p>
                @endif

                @if($kelompok->desa || $kelompok->kecamatan)
                <p class="text-sm text-green-800 font-semibold mt-3 mb-1">Alamat:</p>
                <p class="text-sm text-green-700">
                    {{ $kelompok->desa ? 'Desa ' . $kelompok->desa : '' }}
                    {{ $kelompok->kecamatan ? ', Kec. ' . $kelompok->kecamatan : '' }}
                </p>
                @endif
            </div>

            <!-- Button untuk buka di Google Maps -->
            @php
                $coords = explode(',', $kelompok->koordinat);
                $lat = isset($coords[0]) ? trim($coords[0]) : null;
                $lng = isset($coords[1]) ? trim($coords[1]) : null;
            @endphp
            
            @if($lat && $lng)
            <a href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}" 
               target="_blank"
               class="mt-4 w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center gap-2">
                <span>🌐</span>
                <span>Buka di Google Maps</span>
            </a>
            @endif

            @else
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <p class="text-gray-500 mb-2">📍 Koordinat belum diisi</p>
                <p class="text-sm text-gray-400">Silakan edit data untuk menambahkan lokasi</p>
            </div>
            @endif

        </div>

    </div>

    @else

    <!-- Jika belum ada data -->
    <div class="bg-white rounded-2xl shadow-xl p-12 border border-gray-100 slide-in text-center">
        <div class="max-w-md mx-auto">
            <div class="text-6xl mb-4">👥</div>
            <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Data Kelompok</h3>
            <p class="text-gray-500 text-lg mb-6">
                Anda belum memiliki data kelompok. Silakan tambahkan data kelompok terlebih dahulu.
            </p>

            <a href="{{ route('kelompok.data-kelompok.create') }}"
               class="inline-block px-8 py-4 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                ➕ Tambah Data Kelompok
            </a>
        </div>
    </div>

    @endif
</div>

@if($kelompok && $kelompok->koordinat)
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Parse koordinat dari data kelompok
    const koordinat = "{{ $kelompok->koordinat }}";
    const coords = koordinat.split(',');
    const lat = parseFloat(coords[0].trim());
    const lng = parseFloat(coords[1].trim());

    // Validasi koordinat
    if (isNaN(lat) || isNaN(lng)) {
        document.getElementById('map').innerHTML = 
            '<div class="h-full flex items-center justify-center text-gray-500">' +
            '<p>Format koordinat tidak valid</p></div>';
        return;
    }

    // Initialize map
    const map = L.map('map').setView([lat, lng], 15);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Custom icon (opsional - bisa pakai default atau custom)
    const customIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background-color: #16a34a; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: 16px;">📍</div>',
        iconSize: [30, 30],
        iconAnchor: [15, 15]
    });

    // Add marker
    const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

    // Popup content
    let popupContent = '<div style="min-width: 200px;">';
    popupContent += '<h3 style="font-weight: bold; margin-bottom: 8px; color: #16a34a;">{{ $kelompok->nama_kelompok }}</h3>';
    
    @if($kelompok->desa || $kelompok->kecamatan)
    popupContent += '<p style="margin-bottom: 4px; font-size: 14px;">';
    popupContent += '{{ $kelompok->desa ? "Desa " . $kelompok->desa : "" }}';
    popupContent += '{{ $kelompok->kecamatan ? ", Kec. " . $kelompok->kecamatan : "" }}';
    popupContent += '</p>';
    @endif
    
    @if($kelompok->anggota)
    popupContent += '<p style="font-size: 14px; color: #666;">👥 {{ $kelompok->anggota }} Anggota</p>';
    @endif
    
    popupContent += '<p style="font-family: monospace; font-size: 12px; color: #666; margin-top: 8px; padding-top: 8px; border-top: 1px solid #ddd;">';
    popupContent += lat.toFixed(6) + ', ' + lng.toFixed(6);
    popupContent += '</p>';
    popupContent += '</div>';

    marker.bindPopup(popupContent).openPopup();

    // Add circle radius (opsional - untuk menunjukkan area)
    L.circle([lat, lng], {
        color: '#16a34a',
        fillColor: '#22c55e',
        fillOpacity: 0.1,
        radius: 500 // 500 meter radius
    }).addTo(map);

    // Fix map rendering
    setTimeout(function() {
        map.invalidateSize();
    }, 100);
});
</script>
@endif

@endsection