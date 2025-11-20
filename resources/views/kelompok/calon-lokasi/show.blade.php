@extends('layouts.dashboard')

@section('title', 'Detail Calon Lokasi')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('kelompok.calon-lokasi.index') }}" 
                   class="text-green-600 hover:text-green-700 font-medium mb-4 inline-block">
                    ← Kembali
                </a>
                <h2 class="text-3xl font-bold text-gray-800">📍 Detail Calon Lokasi</h2>
                <p class="text-gray-600 mt-1">Informasi lengkap calon lokasi kegiatan</p>
            </div>
            <div>
                {!! $calonLokasi->status_badge !!}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Info Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Umum -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span>📋</span>
                        <span>Data Umum</span>
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="border-b border-gray-200 pb-3">
                            <p class="text-sm text-gray-600 mb-1">Nama Kelompok Desa</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $calonLokasi->nama_kelompok_desa }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 border-b border-gray-200 pb-3">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Kecamatan</p>
                                <p class="font-semibold text-gray-800">{{ $calonLokasi->kecamatan }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Kabupaten</p>
                                <p class="font-semibold text-gray-800">{{ $calonLokasi->kabupaten }}</p>
                            </div>
                        </div>

                        <div class="border-b border-gray-200 pb-3">
                            <p class="text-sm text-gray-600 mb-1">Koordinat Pusat Area</p>
                            <p class="font-mono text-sm text-gray-800">
                                {{ $calonLokasi->center_latitude }}, {{ $calonLokasi->center_longitude }}
                            </p>
                        </div>

                        @if($calonLokasi->deskripsi)
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Deskripsi</p>
                            <p class="text-gray-800">{{ $calonLokasi->deskripsi }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Dokumen PDF -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span>📄</span>
                        <span>Dokumen PDF</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php $hasPdf = false; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            @php $fieldName = "pdf_dokumen_{$i}"; @endphp
                            @if($calonLokasi->$fieldName)
                                @php $hasPdf = true; @endphp
                                <a href="{{ asset('storage/' . $calonLokasi->$fieldName) }}" 
                                   target="_blank"
                                   class="flex items-center gap-3 p-4 border-2 border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all group">
                                    <div class="text-3xl">📑</div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800 group-hover:text-green-700">Dokumen {{ $i }}</p>
                                        <p class="text-xs text-gray-500">Klik untuk membuka</p>
                                    </div>
                                    <div class="text-green-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </div>
                                </a>
                            @endif
                        @endfor

                        @if(!$hasPdf)
                            <div class="col-span-2 text-center py-8 bg-gray-50 rounded-lg">
                                <p class="text-gray-500">Tidak ada dokumen PDF yang diupload</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($calonLokasi->catatan_bpdas)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-lg">
                    <h4 class="font-bold text-blue-900 mb-2 flex items-center gap-2">
                        <span>💬</span>
                        <span>Catatan dari BPDAS</span>
                    </h4>
                    <p class="text-blue-800">{{ $calonLokasi->catatan_bpdas }}</p>
                </div>
                @endif

                @if($calonLokasi->status_verifikasi === 'pending')
                <div class="flex gap-3">
                    <a href="{{ route('kelompok.calon-lokasi.edit', $calonLokasi) }}" 
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all text-center">
                        ✏️ Edit Data
                    </a>
                    <form action="{{ route('kelompok.calon-lokasi.destroy', $calonLokasi) }}" 
                          method="POST" 
                          class="flex-1"
                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-all">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Map Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span>🗺️</span>
                        <span>Peta Lokasi</span>
                    </h3>

                    <div id="map" class="w-full h-96 rounded-lg border-2 border-gray-300 shadow-inner mb-4"></div>

                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-sm font-semibold text-green-800 mb-2">📐 Informasi Area</p>
                        <div class="space-y-1">
                            @php
                                $polygonCoordinates = is_array($calonLokasi->polygon_coordinates) 
                                    ? $calonLokasi->polygon_coordinates 
                                    : json_decode($calonLokasi->polygon_coordinates, true) ?? [];
                            @endphp
                            <p class="text-xs text-green-700">
                                <span class="font-semibold">Luas:</span> 
                                <span id="areaSize">
                                    @if($calonLokasi->polygon_area)
                                        {{ $calonLokasi->formatted_area }}
                                    @else
                                        Tidak ada data
                                    @endif
                                </span>
                            </p>
                            <p class="text-xs text-green-700">
                                <span class="font-semibold">Titik Polygon:</span> 
                                <span id="pointCount">
                                    @if(is_array($polygonCoordinates) && count($polygonCoordinates) > 0)
                                        {{ count($polygonCoordinates) }} titik
                                    @else
                                        -
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
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

const map = L.map('map').setView([centerLat, centerLng], 15);

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
    const polygon = L.polygon(latlngs, { color:'#10b981', fillColor:'#10b981', fillOpacity:0.3, weight:3 }).addTo(map);
    const area = L.GeometryUtil.geodesicArea(latlngs);
    const areaInHectares = (area / 10000).toFixed(2);
    const areaInSquareMeters = area.toFixed(0);

    const areaSizeEl = document.getElementById('areaSize');
    const pointCountEl = document.getElementById('pointCount');

    if(areaSizeEl) areaSizeEl.textContent = `${areaInHectares} ha (${areaInSquareMeters} m²)`;
    if(pointCountEl) pointCountEl.textContent = `${latlngs.length} titik`;

    polygon.bindPopup(`<div class="text-center"><b class="text-lg">{{ $calonLokasi->nama_kelompok_desa }}</b><br><span class="text-sm text-gray-600">Luas: ${areaInHectares} hektar</span></div>`).openPopup();
    map.fitBounds(polygon.getBounds());
    latlngs.forEach((latlng,index)=>{
        L.circleMarker(latlng,{radius:6,fillColor:'#059669',color:'#fff',weight:2,fillOpacity:1}).addTo(map).bindPopup(`Titik ${index+1}`);
    });
} else {
    L.marker([centerLat, centerLng]).addTo(map).bindPopup('<b>{{ $calonLokasi->nama_kelompok_desa }}</b>').openPopup();
    document.getElementById('areaSize').textContent = 'Tidak ada data polygon';
}
</script>
@endsection
