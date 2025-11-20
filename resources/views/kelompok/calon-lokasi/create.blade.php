@extends('layouts.dashboard')

@section('title', 'Tambah Calon Lokasi')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('kelompok.calon-lokasi.index') }}" 
               class="text-green-600 hover:text-green-700 font-medium mb-4 inline-block">
                ← Kembali
            </a>
            <h2 class="text-3xl font-bold text-gray-800">➕ Tambah Calon Lokasi</h2>
            <p class="text-gray-600 mt-1">Masukkan data calon lokasi kegiatan kelompok tani</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <form action="{{ route('kelompok.calon-lokasi.store') }}" method="POST" enctype="multipart/form-data" id="locationForm">
                    @csrf

                    <!-- Nama Kelompok Desa -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Nama Kelompok Desa <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nama_kelompok_desa" 
                               value="{{ old('nama_kelompok_desa') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('nama_kelompok_desa') border-red-500 @enderror"
                               placeholder="Contoh: Kelompok Tani Makmur"
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
                                   value="{{ old('kecamatan') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('kecamatan') border-red-500 @enderror"
                                   placeholder="Cianjur"
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
                                   value="{{ old('kabupaten') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('kabupaten') border-red-500 @enderror"
                                   placeholder="Kab. Cianjur"
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
                                <span><strong>Cara mengisi:</strong> Klik tombol "Gambar Area" di peta, lalu klik beberapa titik di peta untuk membentuk polygon area tanah. Klik titik pertama lagi untuk menyelesaikan.</span>
                            </p>
                        </div>
                        
                        <!-- Hidden inputs untuk polygon -->
                        <input type="hidden" name="polygon_coordinates" id="polygon_coordinates" required>
                        <input type="hidden" name="center_latitude" id="center_latitude" required>
                        <input type="hidden" name="center_longitude" id="center_longitude" required>
                        
                        <div id="polygonInfo" class="p-4 bg-gray-50 border border-gray-300 rounded-lg">
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold">Status:</span> 
                                <span id="polygonStatus" class="text-red-600">⚠️ Belum digambar</span>
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="font-semibold">Luas Area:</span> 
                                <span id="polygonArea">-</span>
                            </p>
                        </div>
                    </div>

                    <!-- Upload 5 PDF -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-3">
                            📄 Upload Dokumen PDF (Max 5 File)
                        </label>
                        
                        <div class="space-y-3">
                            <!-- PDF 1 -->
                            <div class="border border-gray-300 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dokumen 1 (Opsional)
                                </label>
                                <input type="file" 
                                       name="pdf_dokumen_1" 
                                       accept=".pdf"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                <p class="text-xs text-gray-500 mt-1">Format: PDF, Max 5MB</p>
                            </div>

                            <!-- PDF 2 -->
                            <div class="border border-gray-300 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dokumen 2 (Opsional)
                                </label>
                                <input type="file" 
                                       name="pdf_dokumen_2" 
                                       accept=".pdf"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                <p class="text-xs text-gray-500 mt-1">Format: PDF, Max 5MB</p>
                            </div>

                            <!-- PDF 3 -->
                            <div class="border border-gray-300 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dokumen 3 (Opsional)
                                </label>
                                <input type="file" 
                                       name="pdf_dokumen_3" 
                                       accept=".pdf"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                <p class="text-xs text-gray-500 mt-1">Format: PDF, Max 5MB</p>
                            </div>

                            <!-- PDF 4 -->
                            <div class="border border-gray-300 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dokumen 4 (Opsional)
                                </label>
                                <input type="file" 
                                       name="pdf_dokumen_4" 
                                       accept=".pdf"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                <p class="text-xs text-gray-500 mt-1">Format: PDF, Max 5MB</p>
                            </div>

                            <!-- PDF 5 -->
                            <div class="border border-gray-300 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Dokumen 5 (Opsional)
                                </label>
                                <input type="file" 
                                       name="pdf_dokumen_5" 
                                       accept=".pdf"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                <p class="text-xs text-gray-500 mt-1">Format: PDF, Max 5MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2">
                            Deskripsi Lokasi (Opsional)
                        </label>
                        <textarea name="deskripsi" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                                  placeholder="Detail lokasi, akses jalan, kondisi lahan...">{{ old('deskripsi') }}</textarea>
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
                            <span>Simpan Lokasi</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Map Section -->
            <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">🗺️ Gambar Area di Peta</h3>
                    <p class="text-sm text-gray-600">Gambar polygon untuk menandai area tanah lokasi kelompok tani</p>
                </div>

                <!-- Search Box -->
                <div class="mb-4">
                    <input type="text" 
                           id="searchBox" 
                           placeholder="🔍 Cari lokasi (contoh: Cianjur, Jawa Barat)"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <!-- Drawing Controls -->
                <div class="mb-4 flex gap-2">
                    <button type="button" 
                            id="drawPolygon"
                            class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center justify-center gap-2">
                        <span>✏️</span>
                        <span>Gambar Area</span>
                    </button>
                    <button type="button" 
                            id="clearPolygon"
                            class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium flex items-center justify-center gap-2">
                        <span>🗑️</span>
                        <span>Hapus</span>
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
// Leaflet GeometryUtil - HARUS DIDEFINISIKAN DULU SEBELUM DIGUNAKAN
L.GeometryUtil = {
    geodesicArea: function(latlngs) {
        const EARTH_RADIUS = 6378137; // meters
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

// Initialize map centered on Indonesia
const map = L.map('map').setView([-6.2088, 106.8456], 10);

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

// Feature group untuk menyimpan drawn items
const drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

let currentPolygon = null;

// Function to calculate polygon area
function calculateArea(latlngs) {
    try {
        let area = L.GeometryUtil.geodesicArea(latlngs);
        let areaInHectares = (area / 10000).toFixed(2);
        return areaInHectares;
    } catch (error) {
        console.error('Error calculating area:', error);
        return 0;
    }
}

// Function to update form with polygon data
function updatePolygonData(layer) {
    try {
        const latlngs = layer.getLatLngs()[0];
        
        // Convert to array of [lat, lng]
        const coordinates = latlngs.map(latlng => [latlng.lat, latlng.lng]);
        
        // Calculate center point
        const bounds = layer.getBounds();
        const center = bounds.getCenter();
        
        // Update hidden inputs
        document.getElementById('polygon_coordinates').value = JSON.stringify(coordinates);
        document.getElementById('center_latitude').value = center.lat.toFixed(6);
        document.getElementById('center_longitude').value = center.lng.toFixed(6);
        
        // Calculate area
        const area = calculateArea(latlngs);
        const areaM2 = (area * 10000).toFixed(0);
        
        // Update status
        document.getElementById('polygonStatus').textContent = '✅ Area telah digambar';
        document.getElementById('polygonStatus').classList.remove('text-red-600');
        document.getElementById('polygonStatus').classList.add('text-green-600');
        document.getElementById('polygonArea').textContent = `${area} hektar (${areaM2} m²)`;
        
        console.log('Polygon data updated:', {
            coordinates: coordinates.length + ' points',
            center: [center.lat, center.lng],
            area: area + ' ha'
        });
    } catch (error) {
        console.error('Error updating polygon data:', error);
        alert('Terjadi kesalahan saat memproses data polygon');
    }
}

// Draw Polygon Button
document.getElementById('drawPolygon').addEventListener('click', function() {
    // Clear existing polygon
    if (currentPolygon) {
        drawnItems.removeLayer(currentPolygon);
        currentPolygon = null;
    }
    
    // Start drawing
    const polygonDrawer = new L.Draw.Polygon(map, {
        shapeOptions: {
            color: '#10b981',
            fillColor: '#10b981',
            fillOpacity: 0.3,
            weight: 3
        },
        showArea: true,
        metric: true
    });
    
    polygonDrawer.enable();
    
    this.innerHTML = '<span>✏️</span><span>Klik di peta untuk menggambar...</span>';
    this.classList.add('opacity-50');
});

// Handle polygon creation
map.on(L.Draw.Event.CREATED, function(e) {
    const layer = e.layer;
    
    // Remove old polygon if exists
    if (currentPolygon) {
        drawnItems.removeLayer(currentPolygon);
    }
    
    // Add new polygon
    drawnItems.addLayer(layer);
    currentPolygon = layer;
    
    // Update form data
    updatePolygonData(layer);
    
    // Add popup
    const area = calculateArea(layer.getLatLngs()[0]);
    const areaM2 = (area * 10000).toFixed(0);
    layer.bindPopup(`<b>Area Lokasi</b><br>Luas: ${area} hektar<br>(${areaM2} m²)`).openPopup();
    
    // Reset button
    document.getElementById('drawPolygon').innerHTML = '<span>✏️</span><span>Gambar Area</span>';
    document.getElementById('drawPolygon').classList.remove('opacity-50');
    
    // Make polygon editable
    layer.on('click', function() {
        // Enable edit mode
        if (!layer.editing || !layer.editing.enabled()) {
            new L.Edit.Poly(layer).enable();
        }
    });
});

// Handle polygon edit
map.on('draw:edited', function(e) {
    const layers = e.layers;
    layers.eachLayer(function(layer) {
        updatePolygonData(layer);
        const area = calculateArea(layer.getLatLngs()[0]);
        const areaM2 = (area * 10000).toFixed(0);
        layer.getPopup().setContent(`<b>Area Lokasi</b><br>Luas: ${area} hektar<br>(${areaM2} m²)`);
    });
});

// Also handle direct layer edit
map.on('draw:editvertex', function(e) {
    if (currentPolygon) {
        updatePolygonData(currentPolygon);
    }
});

// Clear Polygon Button
document.getElementById('clearPolygon').addEventListener('click', function() {
    if (currentPolygon) {
        drawnItems.removeLayer(currentPolygon);
        currentPolygon = null;
        
        // Reset form
        document.getElementById('polygon_coordinates').value = '';
        document.getElementById('center_latitude').value = '';
        document.getElementById('center_longitude').value = '';
        
        // Reset status
        document.getElementById('polygonStatus').textContent = '⚠️ Belum digambar';
        document.getElementById('polygonStatus').classList.remove('text-green-600');
        document.getElementById('polygonStatus').classList.add('text-red-600');
        document.getElementById('polygonArea').textContent = '-';
        
        alert('Polygon berhasil dihapus');
    } else {
        alert('Tidak ada polygon untuk dihapus');
    }
});

// Get current location button
document.getElementById('getCurrentLocation').addEventListener('click', function() {
    if (navigator.geolocation) {
        this.innerHTML = '<span>⏳</span><span>Mendapatkan lokasi...</span>';
        this.disabled = true;
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                map.setView([lat, lng], 15);
                
                // Add marker for current location
                L.marker([lat, lng]).addTo(map)
                    .bindPopup('📍 Lokasi Anda Saat Ini')
                    .openPopup();
                
                this.innerHTML = '<span>📍</span><span>Gunakan Lokasi Saya Saat Ini</span>';
                this.disabled = false;
            },
            (error) => {
                console.error('Geolocation error:', error);
                alert('Tidak dapat mengakses lokasi. Pastikan Anda mengizinkan akses lokasi.');
                this.innerHTML = '<span>📍</span><span>Gunakan Lokasi Saya Saat Ini</span>';
                this.disabled = false;
            }
        );
    } else {
        alert('Browser Anda tidak mendukung Geolocation');
    }
});

// Simple search functionality using Nominatim
document.getElementById('searchBox').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const query = this.value;
        
        if (query.length < 3) {
            alert('Masukkan minimal 3 karakter untuk pencarian');
            return;
        }
        
        // Show loading
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
                    
                    // Add temporary marker
                    const marker = L.marker([lat, lng]).addTo(map)
                        .bindPopup(`📍 ${data[0].display_name}`)
                        .openPopup();
                    
                    // Remove marker after 5 seconds
                    setTimeout(() => marker.remove(), 5000);
                } else {
                    alert('Lokasi tidak ditemukan. Coba kata kunci lain.');
                }
                
                this.value = originalValue;
                this.disabled = false;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mencari lokasi');
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
        alert('⚠️ Silakan gambar area lokasi di peta terlebih dahulu!');
        
        // Scroll to map
        document.getElementById('map').scrollIntoView({ behavior: 'smooth', block: 'center' });
        return false;
    }
    
    // Confirm before submit
    const area = document.getElementById('polygonArea').textContent;
    if (!confirm(`Apakah data sudah benar?\n\nLuas area: ${area}\n\nKlik OK untuk menyimpan.`)) {
        e.preventDefault();
        return false;
    }
});

// Debug: Log when map is ready
console.log('Map initialized successfully');
console.log('Current map center:', map.getCenter());
</script>
@endsection