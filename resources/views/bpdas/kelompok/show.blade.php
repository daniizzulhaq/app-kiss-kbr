@extends('layouts.dashboard')

@section('title', 'Detail Kelompok - Sistem KBR')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8 slide-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">👁️ Detail Kelompok</h1>
                <p class="text-gray-600">Informasi lengkap kelompok tani binaan</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('kelompok.data-kelompok.edit', $kelompok) }}"
                   class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition shadow-lg hover:shadow-xl font-semibold">
                    ✏️ Edit
                </a>
                <a href="{{ route('bpdas.kelompok.index') }}"
                   class="px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transition shadow-lg hover:shadow-xl font-semibold">
                    ⬅ Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Card Detail (2 kolom) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl p-8 slide-in">

            <!-- Nama Kelompok -->
            <h2 class="text-3xl font-extrabold text-gray-800 mb-8 border-b pb-3">
                {{ $kelompok->nama_kelompok }}
            </h2>

            <!-- Grid Detail -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">Nama Kelompok</p>
                    <p class="mt-1 text-xl font-semibold text-gray-900">{{ $kelompok->nama_kelompok }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">Blok</p>
                    <p class="mt-1 text-xl text-gray-900">{{ $kelompok->blok ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">Desa</p>
                    <p class="mt-1 text-xl text-gray-900">{{ $kelompok->desa ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">Kecamatan</p>
                    <p class="mt-1 text-xl text-gray-900">{{ $kelompok->kecamatan ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">Kabupaten</p>
                    <p class="mt-1 text-xl text-gray-900">{{ $kelompok->kabupaten ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">Koordinat</p>
                    <p class="mt-1 text-lg text-gray-900 font-mono">{{ $kelompok->koordinat ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">Jumlah Anggota</p>
                    <p class="mt-1 text-xl text-gray-900">{{ $kelompok->anggota ?? '-' }} orang</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">Kontak</p>
                    <p class="mt-1 text-xl text-gray-900">{{ $kelompok->kontak ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">SPKS</p>
                    <p class="mt-1 text-xl text-gray-900">{{ $kelompok->spks ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">Rekening</p>
                    <p class="mt-1 text-xl text-gray-900">{{ $kelompok->rekening ?? '-' }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">User</p>
                    <p class="mt-1 text-xl text-gray-900">{{ $kelompok->user->name }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">Email</p>
                    <p class="mt-1 text-lg text-gray-900">{{ $kelompok->user->email }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">Tanggal Dibuat</p>
                    <p class="mt-1 text-lg text-gray-900">{{ $kelompok->created_at->format('d M Y H:i') }}</p>
                </div>

                <div class="bg-gray-50 p-5 rounded-xl border">
                    <p class="text-sm font-medium text-gray-500">Terakhir Diupdate</p>
                    <p class="mt-1 text-lg text-gray-900">{{ $kelompok->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Map Section (1 kolom) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-xl p-6 slide-in sticky top-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">📍 Lokasi di Peta</h3>

                @if($kelompok->koordinat)
                    <div class="mb-4 bg-green-50 p-4 rounded-lg border border-green-200">
                        <p class="text-sm font-semibold text-green-800 mb-1">Koordinat:</p>
                        <p class="text-sm text-green-700 font-mono">{{ $kelompok->koordinat }}</p>
                    </div>

                    <!-- Map Container -->
                    <div id="map" class="w-full h-96 rounded-xl border-2 border-gray-300 shadow-lg mb-4"></div>

                    <!-- Map Actions -->
                    <div class="flex gap-2">
                        <a href="https://www.google.com/maps?q={{ $kelompok->koordinat }}" 
                           target="_blank"
                           class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium text-center text-sm">
                            🗺️ Buka di Google Maps
                        </a>
                        <button onclick="copyCoordinates()"
                                class="px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-medium text-sm">
                            📋
                        </button>
                    </div>

                    <p id="copyMessage" class="text-xs text-green-600 mt-2 text-center hidden">✅ Koordinat berhasil disalin!</p>
                @else
                    <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-200 text-center">
                        <p class="text-yellow-800 font-medium">⚠️ Koordinat belum tersedia</p>
                        <p class="text-sm text-yellow-600 mt-2">Silakan edit data untuk menambahkan lokasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($kelompok->koordinat)
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
// Parse coordinates
const coordinatesStr = "{{ $kelompok->koordinat }}";
const coords = coordinatesStr.split(',').map(c => parseFloat(c.trim()));

if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
    const lat = coords[0];
    const lng = coords[1];

    // Initialize map
    const map = L.map('map').setView([lat, lng], 15);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Custom icon for marker
    const customIcon = L.divIcon({
        className: 'custom-marker',
        html: '<div style="background-color: #ef4444; width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3);"><div style="transform: rotate(45deg); color: white; font-size: 16px; text-align: center; line-height: 24px;">📍</div></div>',
        iconSize: [30, 30],
        iconAnchor: [15, 30]
    });

    // Add marker
    const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

    // Add popup
    marker.bindPopup(`
        <div class="text-center">
            <strong class="text-lg">{{ $kelompok->nama_kelompok }}</strong><br>
            <span class="text-sm text-gray-600">{{ $kelompok->desa ?? '' }}{{ $kelompok->desa && $kelompok->kecamatan ? ', ' : '' }}{{ $kelompok->kecamatan ?? '' }}</span><br>
            <span class="text-xs font-mono text-gray-500 mt-1 block">${lat.toFixed(6)}, ${lng.toFixed(6)}</span>
        </div>
    `).openPopup();

    // Add circle to show area
    L.circle([lat, lng], {
        color: '#ef4444',
        fillColor: '#fecaca',
        fillOpacity: 0.2,
        radius: 100
    }).addTo(map);

    // Fix map rendering
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
}

// Copy coordinates function
function copyCoordinates() {
    const coordinates = "{{ $kelompok->koordinat }}";
    navigator.clipboard.writeText(coordinates).then(() => {
        const message = document.getElementById('copyMessage');
        message.classList.remove('hidden');
        setTimeout(() => {
            message.classList.add('hidden');
        }, 2000);
    }).catch(err => {
        alert('Gagal menyalin koordinat');
    });
}
</script>
@endif

@endsection