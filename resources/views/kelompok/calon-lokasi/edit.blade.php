@extends('layouts.dashboard')

@section('title', 'Edit Calon Lokasi')

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('kelompok.calon-lokasi.index') }}" 
               class="text-green-600 hover:text-green-700 font-medium mb-4 inline-block">
                ← Kembali
            </a>
            <h2 class="text-3xl font-bold text-gray-800">✏️ Edit Calon Lokasi</h2>
            <p class="text-gray-600 mt-1">Perbarui data calon lokasi kegiatan kelompok tani</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <form action="{{ route('kelompok.calon-lokasi.update', $calonLokasi) }}" method="POST" enctype="multipart/form-data" id="locationForm">
                    @csrf
                    @method('PUT')

                    <!-- Nama Kelompok Desa -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Nama Kelompok Desa <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_kelompok_desa" 
                               value="{{ old('nama_kelompok_desa', $calonLokasi->nama_kelompok_desa) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('nama_kelompok_desa') border-red-500 @enderror"
                               placeholder="Kelompok Tani Makmur"
                               required>
                        @error('nama_kelompok_desa')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kecamatan & Kabupaten -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                Kecamatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="kecamatan" 
                                   value="{{ old('kecamatan', $calonLokasi->kecamatan) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('kecamatan') border-red-500 @enderror"
                                   required>
                            @error('kecamatan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                Kabupaten <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="kabupaten" 
                                   value="{{ old('kabupaten', $calonLokasi->kabupaten) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('kabupaten') border-red-500 @enderror"
                                   required>
                            @error('kabupaten')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Koordinat -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-3">
                            📍 Koordinat Lokasi <span class="text-red-500">*</span>
                        </label>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-3 rounded-lg">
                            <p class="text-sm text-blue-800 flex items-start">
                                <span class="mr-2">💡</span>
                                <span>Klik peta atau geser marker untuk mengubah lokasi</span>
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Latitude</label>
                                <input type="number" 
                                       step="0.000001"
                                       name="latitude" 
                                       id="latitude"
                                       value="{{ old('latitude', $calonLokasi->latitude) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-gray-50 @error('latitude') border-red-500 @enderror"
                                       readonly>
                                @error('latitude')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Longitude</label>
                                <input type="number" 
                                       step="0.000001"
                                       name="longitude" 
                                       id="longitude"
                                       value="{{ old('longitude', $calonLokasi->longitude) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 bg-gray-50 @error('longitude') border-red-500 @enderror"
                                       readonly>
                                @error('longitude')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Upload PDF -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">
                            📄 Upload PDF Koordinat (Opsional)
                        </label>
                        
                        @if($calonLokasi->koordinat_pdf_lokasi)
                        <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-700 mb-2">File saat ini:</p>
                            <a href="{{ asset('storage/' . $calonLokasi->koordinat_pdf_lokasi) }}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                📄 Lihat PDF
                            </a>
                        </div>
                        @endif
                        
                        <input type="file" 
                               name="koordinat_pdf_lokasi" 
                               accept=".pdf"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('koordinat_pdf_lokasi') border-red-500 @enderror">
                        @error('koordinat_pdf_lokasi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-2">Upload file baru untuk mengganti. Format: PDF, Max 5MB</p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Deskripsi Lokasi (Opsional)
                        </label>
                        <textarea name="deskripsi" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Detail lokasi, akses jalan, kondisi lahan...">{{ old('deskripsi', $calonLokasi->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('kelompok.calon-lokasi.index') }}" 
                           class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-medium">
                            Batal
                        </a>
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                            <span>💾</span>
                            <span>Update Lokasi</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Map Section -->
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">🗺️ Pilih Lokasi di Peta</h3>
                    <p class="text-sm text-gray-600">Klik atau geser marker untuk mengubah lokasi</p>
                </div>

                <!-- Search Box -->
                <div class="mb-4">
                    <input type="text" 
                           id="searchBox" 
                           placeholder="🔍 Cari lokasi..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Map Container -->
                <div id="map" class="w-full h-96 rounded-lg border-2 border-gray-300"></div>

                <!-- Selected Coordinates -->
                <div id="selectedCoords" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm font-semibold text-green-800 mb-1">✅ Lokasi Terpilih:</p>
                    <p class="text-xs text-green-700 font-mono" id="coordsDisplay">{{ $calonLokasi->latitude }}, {{ $calonLokasi->longitude }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Initialize map with existing location
const existingLat = {{ $calonLokasi->latitude ?? -6.2088 }};
const existingLng = {{ $calonLokasi->longitude ?? 106.8456 }};

const map = L.map('map').setView([existingLat, existingLng], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

// Add existing marker
let marker = L.marker([existingLat, existingLng], {
    draggable: true
}).addTo(map);

marker.bindPopup('<b>Lokasi Saat Ini</b><br>Geser untuk mengubah').openPopup();

// Function to update location
function updateLocation(lat, lng) {
    marker.setLatLng([lat, lng]);
    map.setView([lat, lng], map.getZoom());
    
    document.getElementById('latitude').value = lat.toFixed(6);
    document.getElementById('longitude').value = lng.toFixed(6);
    document.getElementById('coordsDisplay').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    
    marker.bindPopup(`<b>Lokasi Baru</b><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`).openPopup();
}

// Update on marker drag
marker.on('dragend', function(e) {
    const position = e.target.getLatLng();
    updateLocation(position.lat, position.lng);
});

// Update on map click
map.on('click', function(e) {
    updateLocation(e.latlng.lat, e.latlng.lng);
});

// Search functionality
document.getElementById('searchBox').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const query = this.value;
        
        if (query.length < 3) {
            alert('Masukkan minimal 3 karakter');
            return;
        }
        
        this.value = '🔍 Mencari...';
        this.disabled = true;
        
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=1`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
                    updateLocation(lat, lng);
                } else {
                    alert('Lokasi tidak ditemukan');
                }
                this.value = query;
                this.disabled = false;
            })
            .catch(() => {
                alert('Terjadi kesalahan');
                this.value = query;
                this.disabled = false;
            });
    }
});

// Form validation
document.getElementById('locationForm').addEventListener('submit', function(e) {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;
    
    if (!lat || !lng) {
        e.preventDefault();
        alert('⚠️ Koordinat lokasi harus diisi!');
        return false;
    }
});
</script>
@endsection