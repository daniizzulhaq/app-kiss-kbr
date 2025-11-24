@extends('layouts.dashboard')

@section('title', 'Detail Calon Lokasi')

@section('content')
<div class="py-6 sm:py-12">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <a href="{{ route('kelompok.calon-lokasi.index') }}" 
                   class="text-green-600 hover:text-green-700 font-medium mb-3 sm:mb-4 inline-flex items-center gap-2 text-sm sm:text-base">
                    <span>←</span>
                    <span>Kembali</span>
                </a>
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <span>📍</span>
                    <span>Detail Calon Lokasi</span>
                </h2>
                <p class="text-xs sm:text-sm lg:text-base text-gray-600 mt-1">Informasi lengkap calon lokasi kegiatan</p>
            </div>
            <div>
                {!! $calonLokasi->status_badge !!}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Info Section -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Data Umum -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8">
                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                        <span class="text-xl sm:text-2xl">📋</span>
                        <span>Data Umum</span>
                    </h3>
                    
                    <div class="space-y-3 sm:space-y-4">
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Nama Kelompok Desa</p>
                            <p class="text-sm sm:text-base lg:text-lg font-semibold text-gray-800 break-words">
                                {{ $calonLokasi->nama_kelompok_desa }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Kecamatan</p>
                                <p class="text-sm sm:text-base font-semibold text-gray-800 break-words">
                                    {{ $calonLokasi->kecamatan }}
                                </p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <p class="text-xs sm:text-sm text-gray-600 mb-1">Kabupaten</p>
                                <p class="text-sm sm:text-base font-semibold text-gray-800 break-words">
                                    {{ $calonLokasi->kabupaten }}
                                </p>
                            </div>
                        </div>

                        <div class="bg-purple-50 p-3 rounded-lg border border-purple-200">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1 flex items-center gap-1">
                                <span>📍</span>
                                <span>Koordinat Pusat Area</span>
                            </p>
                            <p class="font-mono text-xs sm:text-sm text-purple-700 break-all">
                                {{ $calonLokasi->center_latitude }}, {{ $calonLokasi->center_longitude }}
                            </p>
                        </div>

                        @if($calonLokasi->deskripsi)
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Deskripsi</p>
                            <p class="text-sm sm:text-base text-gray-800 break-words">{{ $calonLokasi->deskripsi }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Dokumen PDF -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8">
                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                        <span class="text-xl sm:text-2xl">📄</span>
                        <span>Dokumen PDF</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        @php $hasPdf = false; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @php $fieldName = "pdf_dokumen_{$i}"; @endphp
                            @if($calonLokasi->$fieldName)
                                @php $hasPdf = true; @endphp
                                <a href="{{ asset('storage/' . $calonLokasi->$fieldName) }}" 
                                   target="_blank"
                                   class="flex items-center gap-3 p-3 sm:p-4 border-2 border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all group">
                                    <div class="text-2xl sm:text-3xl">📑</div>
                                    <div class="flex-1">
                                        <p class="text-sm sm:text-base font-semibold text-gray-800 group-hover:text-green-700">
                                            Dokumen {{ $i }}
                                        </p>
                                        <p class="text-xs text-gray-500">Klik untuk membuka</p>
                                    </div>
                                    <div class="text-green-600">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </div>
                                </a>
                            @endif
                        @endfor

                        @if(!$hasPdf)
                            <div class="col-span-1 sm:col-span-2 text-center py-6 sm:py-8 bg-gray-50 rounded-lg">
                                <span class="text-4xl sm:text-5xl mb-2 block">📄</span>
                                <p class="text-sm sm:text-base text-gray-500">Tidak ada dokumen PDF yang diupload</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Catatan BPDAS -->
                @if($calonLokasi->catatan_bpdas)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 sm:p-6 rounded-lg">
                    <h4 class="text-sm sm:text-base font-bold text-blue-900 mb-2 flex items-center gap-2">
                        <span>💬</span>
                        <span>Catatan dari BPDAS</span>
                    </h4>
                    <p class="text-xs sm:text-sm lg:text-base text-blue-800 break-words">{{ $calonLokasi->catatan_bpdas }}</p>
                </div>
                @endif

                <!-- Action Buttons -->
                @if($calonLokasi->status_verifikasi === 'pending')
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <a href="{{ route('kelompok.calon-lokasi.edit', $calonLokasi) }}" 
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-medium transition-all text-center text-sm sm:text-base flex items-center justify-center gap-2">
                        <span>✏️</span>
                        <span>Edit Data</span>
                    </a>
                    <form action="{{ route('kelompok.calon-lokasi.destroy', $calonLokasi) }}" 
                          method="POST" 
                          class="flex-1"
                          onsubmit="return confirm('⚠️ Yakin ingin menghapus data ini?\n\nData tidak dapat dikembalikan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-medium transition-all text-sm sm:text-base flex items-center justify-center gap-2">
                            <span>🗑️</span>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Map Section -->
            <div class="lg:col-span-1 order-first lg:order-last">
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:sticky lg:top-6">
                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                        <span class="text-xl sm:text-2xl">🗺️</span>
                        <span>Peta Lokasi</span>
                    </h3>

                    <div id="map" class="w-full h-64 sm:h-80 lg:h-96 rounded-lg border-2 border-gray-300 shadow-inner mb-3 sm:mb-4"></div>

                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4">
                        <p class="text-xs sm:text-sm font-semibold text-green-800 mb-2 flex items-center gap-1">
                            <span>📐</span>
                            <span>Informasi Area</span>
                        </p>
                        <div class="space-y-1">
                            @php
                                $polygonCoordinates = is_array($calonLokasi->polygon_coordinates) 
                                    ? $calonLokasi->polygon_coordinates 
                                    : json_decode($calonLokasi->polygon_coordinates, true) ?? [];
                            @endphp
                            <div class="flex justify-between items-center bg-white p-2 rounded">
                                <span class="text-xs sm:text-sm text-green-700 font-semibold">Luas:</span>
                                <span class="text-xs sm:text-sm text-green-700 font-mono" id="areaSize">
                                    @if($calonLokasi->polygon_area)
                                        {{ $calonLokasi->formatted_area }}
                                    @else
                                        Tidak ada data
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-center bg-white p-2 rounded">
                                <span class="text-xs sm:text-sm text-green-700 font-semibold">Titik Polygon:</span>
                                <span class="text-xs sm:text-sm text-green-700" id="pointCount">
                                    @if(is_array($polygonCoordinates) && count($polygonCoordinates) > 0)
                                        {{ count($polygonCoordinates) }} titik
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Box untuk Mobile -->
        <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4 rounded-lg lg:hidden">
            <div class="flex items-start">
                <span class="text-xl sm:text-2xl mr-2 sm:mr-3">💡</span>
                <div>
                    <p class="text-xs sm:text-sm text-blue-800 font-semibold mb-1">Tips:</p>
                    <p class="text-xs sm:text-sm text-blue-700">
                        Gunakan dua jari untuk zoom in/out pada peta. 
                        Geser untuk melihat area sekitar.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const polygonCoordinates = @json($polygonCoordinates);
const centerLat = {{ $calonLokasi->center_latitude ?? -6.2088 }};
const centerLng = {{ $calonLokasi->center_longitude ?? 106.8456 }};

// Deteksi mobile untuk konfigurasi map
const isMobile = window.innerWidth < 640;
const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;

const map = L.map('map', {
    // Opsi tambahan untuk mobile
    tap: !isMobile, // Nonaktifkan tap di mobile untuk menghindari konflik dengan scroll
    dragging: true,
    touchZoom: true,
    scrollWheelZoom: !isMobile, // Nonaktifkan scroll zoom di mobile
    doubleClickZoom: true,
}).setView([centerLat, centerLng], isMobile ? 14 : 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

// Fungsi hitung luas geodesic
L.GeometryUtil = {
    geodesicArea: function(latlngs) {
        const EARTH_RADIUS = 6378137;
        const toRad = function(num) { return num * Math.PI / 180; };
        let area = 0;
        if (latlngs.length > 2) {
            for (let i = 0; i < latlngs.length; i++) {
                const j = (i + 1) % latlngs.length;
                const xi = latlngs[i].lng;
                const yi = latlngs[i].lat;
                const xj = latlngs[j].lng;
                const yj = latlngs[j].lat;
                area += toRad(xj - xi) * (2 + Math.sin(toRad(yi)) + Math.sin(toRad(yj)));
            }
            area = area * EARTH_RADIUS * EARTH_RADIUS / 2.0;
        }
        return Math.abs(area);
    }
};

if (polygonCoordinates && polygonCoordinates.length > 0) {
    const latlngs = polygonCoordinates.map(coord => L.latLng(coord[0], coord[1]));
    const polygon = L.polygon(latlngs, { 
        color:'#10b981', 
        fillColor:'#10b981', 
        fillOpacity:0.3, 
        weight: isMobile ? 2 : 3 
    }).addTo(map);
    
    const area = L.GeometryUtil.geodesicArea(latlngs);
    const areaInHectares = (area / 10000).toFixed(2);
    const areaInSquareMeters = area.toFixed(0);

    const areaSizeEl = document.getElementById('areaSize');
    const pointCountEl = document.getElementById('pointCount');

    if(areaSizeEl) areaSizeEl.textContent = `${areaInHectares} ha (${areaInSquareMeters} m²)`;
    if(pointCountEl) pointCountEl.textContent = `${latlngs.length} titik`;

    const popupContent = isMobile 
        ? `<div class="text-center text-xs"><b>{{ $calonLokasi->nama_kelompok_desa }}</b><br><span class="text-gray-600">${areaInHectares} ha</span></div>`
        : `<div class="text-center"><b class="text-lg">{{ $calonLokasi->nama_kelompok_desa }}</b><br><span class="text-sm text-gray-600">Luas: ${areaInHectares} hektar</span></div>`;
    
    polygon.bindPopup(popupContent).openPopup();
    map.fitBounds(polygon.getBounds(), { padding: isMobile ? [20, 20] : [50, 50] });
    
    latlngs.forEach((latlng, index) => {
        L.circleMarker(latlng, {
            radius: isMobile ? 4 : 6,
            fillColor:'#059669',
            color:'#fff',
            weight: isMobile ? 1 : 2,
            fillOpacity:1
        }).addTo(map).bindPopup(`Titik ${index+1}`);
    });
} else {
    L.marker([centerLat, centerLng]).addTo(map)
        .bindPopup(`<b class="${isMobile ? 'text-sm' : ''}">{{ $calonLokasi->nama_kelompok_desa }}</b>`)
        .openPopup();
    
    const areaSizeEl = document.getElementById('areaSize');
    if(areaSizeEl) areaSizeEl.textContent = 'Tidak ada data polygon';
}

// Force map resize after load untuk memastikan ukuran benar
setTimeout(() => {
    map.invalidateSize();
}, 100);

// Handle window resize
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        map.invalidateSize();
    }, 250);
});
</script>
@endsection