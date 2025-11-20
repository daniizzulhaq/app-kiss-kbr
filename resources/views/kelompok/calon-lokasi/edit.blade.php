@extends('layouts.dashboard')

@section('title', 'Edit Calon Lokasi')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

                    <!-- Area Polygon Info -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-3">
                            📍 Area Lokasi (Polygon) <span class="text-red-500">*</span>
                        </label>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-3 rounded-lg">
                            <p class="text-sm text-blue-800 flex items-start">
                                <span class="mr-2">💡</span>
                                <span><strong>Cara edit:</strong> Klik tombol "Edit Area" untuk mengubah polygon, atau klik "Gambar Ulang" untuk membuat polygon baru.</span>
                            </p>
                        </div>
                        
                        <!-- Hidden inputs -->
                        <input type="hidden" name="polygon_coordinates" id="polygon_coordinates" required>
                        <input type="hidden" name="center_latitude" id="center_latitude" required>
                        <input type="hidden" name="center_longitude" id="center_longitude" required>
                        
                        <div id="polygonInfo" class="p-4 bg-gray-50 border border-gray-300 rounded-lg">
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold">Status:</span> 
                                <span id="polygonStatus" class="text-green-600">✅ Area tersimpan</span>
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="font-semibold">Luas Area:</span> 
                                <span id="polygonArea">Menghitung...</span>
                            </p>
                        </div>
                    </div>

                    <!-- Upload 5 PDF -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-3">
                            📄 Upload Dokumen PDF (Max 5 File)
                        </label>
                        
                        <div class="space-y-3">
                            @for($i = 1; $i <= 5; $i++)
                                @php $fieldName = "pdf_dokumen_{$i}"; @endphp
                                <div class="border border-gray-300 rounded-lg p-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Dokumen {{ $i }} (Opsional)
                                    </label>
                                    
                                    @if($calonLokasi->$fieldName)
                                    <div class="mb-2 p-2 bg-green-50 rounded border border-green-200 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="text-green-700">📄</span>
                                            <a href="{{ asset('storage/' . $calonLokasi->$fieldName) }}" 
                                               target="_blank"
                                               class="text-xs text-green-700 hover:text-green-900 font-medium">
                                                File tersedia - Klik untuk lihat
                                            </a>
                                        </div>
                                        <span class="text-xs text-green-600">✅</span>
                                    </div>
                                    @endif
                                    
                                    <input type="file" 
                                           name="{{ $fieldName }}" 
                                           accept=".pdf"
                                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($calonLokasi->$fieldName)
                                            Upload file baru untuk mengganti. 
                                        @endif
                                        Format: PDF, Max 5MB
                                    </p>
                                </div>
                            @endfor
                        </div>
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
                    <h3 class="text-xl font-bold text-gray-800 mb-2">🗺️ Edit Area di Peta</h3>
                    <p class="text-sm text-gray-600">Edit polygon atau gambar ulang area lokasi</p>
                </div>

                <!-- Search Box -->
                <div class="mb-4">
                    <input type="text" 
                           id="searchBox" 
                           placeholder="🔍 Cari lokasi..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Drawing Controls -->
                <div class="mb-4 flex gap-2">
                    <button type="button" 
                            id="editPolygon"
                            class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center gap-2">
                        <span>✏️</span>
                        <span>Edit Area</span>
                    </button>
                    <button type="button" 
                            id="redrawPolygon"
                            class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center justify-center gap-2">
                        <span>🔄</span>
                        <span>Gambar Ulang</span>
                    </button>
                </div>

                <!-- Map Container -->
                <div id="map" class="w-full h-96 rounded-lg border-2 border-gray-300 shadow-inner"></div>

                <!-- Current Location Button -->
                <button type="button" 
                        id="getCurrentLocation"
                        class="mt-4 w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center gap-2">
                    <span>📍</span>
                    <span>Gunakan Lokasi Saya Saat Ini</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

<script>
// Get existing polygon data
const existingPolygon = @json($calonLokasi->polygon_coordinates);
const centerLat = {{ $calonLokasi->center_latitude ?? -6.2088 }};
const centerLng = {{ $calonLokasi->center_longitude ?? 106.8456 }};

// Initialize map
const map = L.map('map').setView([centerLat, centerLng], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

// Feature group for drawn items
const drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

let currentPolygon = null;

// Geometry utility for area calculation
L.GeometryUtil = {
    geodesicArea: function(latlngs) {
        const EARTH_RADIUS = 6378137;
        const toRad = function(num) {
            return num * Math.PI / 180;
        };
        
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

// Calculate area in hectares
function calculateArea(latlngs) {
    let area = L.GeometryUtil.geodesicArea(latlngs);
    let areaInHectares = (area / 10000).toFixed(2);
    return areaInHectares;
}

// Update form with polygon data
function updatePolygonData(layer) {
    const latlngs = layer.getLatLngs()[0];
    const coordinates = latlngs.map(latlng => [latlng.lat, latlng.lng]);
    const bounds = layer.getBounds();
    const center = bounds.getCenter();
    
    document.getElementById('polygon_coordinates').value = JSON.stringify(coordinates);
    document.getElementById('center_latitude').value = center.lat.toFixed(6);
    document.getElementById('center_longitude').value = center.lng.toFixed(6);
    
    const area = calculateArea(latlngs);
    document.getElementById('polygonStatus').textContent = '✅ Area telah diupdate';
    document.getElementById('polygonStatus').classList.remove('text-red-600');
    document.getElementById('polygonStatus').classList.add('text-green-600');
    document.getElementById('polygonArea').textContent = `${area} hektar (${(area * 10000).toFixed(0)} m²)`;
}

// Load existing polygon
if (existingPolygon && existingPolygon.length > 0) {
    const latlngs = existingPolygon.map(coord => L.latLng(coord[0], coord[1]));
    
    currentPolygon = L.polygon(latlngs, {
        color: '#10b981',
        fillColor: '#10b981',
        fillOpacity: 0.3,
        weight: 3
    }).addTo(drawnItems);
    
    // Set initial form values
    document.getElementById('polygon_coordinates').value = JSON.stringify(existingPolygon);
    document.getElementById('center_latitude').value = centerLat.toFixed(6);
    document.getElementById('center_longitude').value = centerLng.toFixed(6);
    
    const area = calculateArea(latlngs);
    document.getElementById('polygonArea').textContent = `${area} hektar (${(area * 10000).toFixed(0)} m²)`;
    
    currentPolygon.bindPopup(`<b>Area Saat Ini</b><br>Luas: ${area} hektar`);
    map.fitBounds(currentPolygon.getBounds());
}

// Edit Polygon Button
document.getElementById('editPolygon').addEventListener('click', function() {
    if (currentPolygon) {
        currentPolygon.editing.enable();
        this.textContent = '✅ Mode Edit Aktif';
        this.classList.add('opacity-75');
        alert('Mode edit aktif! Geser titik-titik polygon untuk mengubah bentuk area.');
    } else {
        alert('Tidak ada polygon untuk diedit. Gunakan tombol "Gambar Ulang".');
    }
});

// Update data when editing
map.on('draw:edited', function(e) {
    const layers = e.layers;
    layers.eachLayer(function(layer) {
        updatePolygonData(layer);
        const area = calculateArea(layer.getLatLngs()[0]);
        layer.getPopup().setContent(`<b>Area Diupdate</b><br>Luas: ${area} hektar`);
    });
    
    document.getElementById('editPolygon').innerHTML = '<span>✏️</span><span>Edit Area</span>';
    document.getElementById('editPolygon').classList.remove('opacity-75');
});

// Redraw Polygon Button
document.getElementById('redrawPolygon').addEventListener('click', function() {
    if (currentPolygon) {
        if (confirm('Yakin ingin menggambar ulang? Polygon lama akan dihapus.')) {
            drawnItems.removeLayer(currentPolygon);
            currentPolygon = null;
        } else {
            return;
        }
    }
    
    new L.Draw.Polygon(map, {
        shapeOptions: {
            color: '#10b981',
            fillColor: '#10b981',
            fillOpacity: 0.3,
            weight: 3
        }
    }).enable();
    
    this.textContent = '✏️ Klik di peta untuk menggambar...';
    this.classList.add('opacity-50');
});

// Handle new polygon creation
map.on(L.Draw.Event.CREATED, function(e) {
    const layer = e.layer;
    
    if (currentPolygon) {
        drawnItems.removeLayer(currentPolygon);
    }
    
    drawnItems.addLayer(layer);
    currentPolygon = layer;
    
    updatePolygonData(layer);
    
    const area = calculateArea(layer.getLatLngs()[0]);
    layer.bindPopup(`<b>Area Baru</b><br>Luas: ${area} hektar`).openPopup();
    
    document.getElementById('redrawPolygon').innerHTML = '<span>🔄</span><span>Gambar Ulang</span>';
    document.getElementById('redrawPolygon').classList.remove('opacity-50');
    
    layer.editing.enable();
});

// Get current location
document.getElementById('getCurrentLocation').addEventListener('click', function() {
    if (navigator.geolocation) {
        this.innerHTML = '<span>⏳</span><span>Mendapatkan lokasi...</span>';
        this.disabled = true;
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 15);
                this.innerHTML = '<span>📍</span><span>Gunakan Lokasi Saya Saat Ini</span>';
                this.disabled = false;
            },
            (error) => {
                alert('Tidak dapat mengakses lokasi.');
                this.innerHTML = '<span>📍</span><span>Gunakan Lokasi Saya Saat Ini</span>';
                this.disabled = false;
            }
        );
    } else {
        alert('Browser tidak mendukung Geolocation');
    }
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
        
        const originalValue = this.value;
        this.value = '🔍 Mencari...';
        this.disabled = true;
        
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=1`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
                    map.setView([lat, lng], 13);
                } else {
                    alert('Lokasi tidak ditemukan');
                }
                this.value = originalValue;
                this.disabled = false;
            })
            .catch(() => {
                alert('Terjadi kesalahan');
                this.value = originalValue;
                this.disabled = false;
            });
    }
});

// Form validation
document.getElementById('locationForm').addEventListener('submit', function(e) {
    const polygonCoords = document.getElementById('polygon_coordinates').value;
    
    if (!polygonCoords) {
        e.preventDefault();
        alert('⚠️ Silakan gambar atau edit area lokasi di peta terlebih dahulu!');
        return false;
    }
});
</script>
@endsection