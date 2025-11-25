@extends('layouts.dashboard')

@section('title', 'Tambah Data Kelompok')

@section('content')
@include('components.alert')
<div class="py-6 sm:py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-2">➕ Tambah Data Kelompok</h1>
            <p class="text-sm sm:text-base text-gray-600">Silakan isi data kelompok dengan lengkap dan benar.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            
            <!-- Form Section -->
            <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-xl sm:rounded-2xl shadow-xl border border-gray-100">
                <form action="{{ route('kelompok.data-kelompok.store') }}" method="POST" enctype="multipart/form-data" id="kelompokForm">
                    @csrf

                    <!-- Nama Kelompok -->
                    <div class="mb-4 sm:mb-6">
                        <label for="nama_kelompok" class="block text-xs sm:text-sm text-gray-700 font-semibold mb-2">
                            Nama Kelompok <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kelompok" id="nama_kelompok" required
                            value="{{ old('nama_kelompok') }}"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 @error('nama_kelompok') border-red-500 @enderror">
                        @error('nama_kelompok')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Blok & Desa -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <div>
                            <label class="block text-xs sm:text-sm text-gray-700 font-semibold mb-2">Blok</label>
                            <input type="text" name="blok" value="{{ old('blok') }}"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                            @error('blok') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm text-gray-700 font-semibold mb-2">Desa</label>
                            <input type="text" name="desa" value="{{ old('desa') }}"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                            @error('desa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Kecamatan & Kabupaten -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <div>
                            <label class="block text-xs sm:text-sm text-gray-700 font-semibold mb-2">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan') }}"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                            @error('kecamatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm text-gray-700 font-semibold mb-2">Kabupaten</label>
                            <input type="text" name="kabupaten" value="{{ old('kabupaten') }}"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                            @error('kabupaten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Koordinat (Hidden, Auto-fill dari map) -->
                    <div class="mb-4 sm:mb-6">
                        <label class="block text-xs sm:text-sm text-gray-700 font-semibold mb-2">
                            📍 Koordinat Lokasi
                        </label>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4 mb-3 rounded-lg">
                            <p class="text-xs sm:text-sm text-blue-800 flex items-start">
                                <span class="mr-2">💡</span>
                                <span><strong>Klik pada peta</strong> di bawah untuk menandai lokasi. Koordinat akan terisi otomatis!</span>
                            </p>
                        </div>
                        <input type="text" name="koordinat" id="koordinat" 
                            placeholder="Klik peta untuk mengisi koordinat"
                            value="{{ old('koordinat') }}" readonly
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm rounded-lg sm:rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-green-600 focus:ring-green-600 break-all">
                        @error('koordinat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Anggota & Kontak -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <div>
                            <label class="block text-xs sm:text-sm text-gray-700 font-semibold mb-2">Jumlah Anggota</label>
                            <input type="number" name="anggota" min="0" value="{{ old('anggota') }}"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                            @error('anggota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs sm:text-sm text-gray-700 font-semibold mb-2">Kontak</label>
                            <input type="text" name="kontak" placeholder="cth: 08123456789"
                                value="{{ old('kontak') }}"
                                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                            @error('kontak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- SPKS -->
                    <div class="mb-4 sm:mb-6">
                        <label class="block text-xs sm:text-sm text-gray-700 font-semibold mb-2">SPKS</label>
                        <input type="text" name="spks" value="{{ old('spks') }}"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                        @error('spks') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Rekening -->
                    <div class="mb-4 sm:mb-6">
                        <label class="block text-xs sm:text-sm text-gray-700 font-semibold mb-2">Rekening</label>
                        <textarea name="rekening" rows="3"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">{{ old('rekening') }}</textarea>
                        @error('rekening') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Dokumentasi Foto -->
                    <div class="mb-4 sm:mb-6">
                        <label class="block text-xs sm:text-sm text-gray-700 font-semibold mb-2">
                            📸 Dokumentasi Kelompok (Maksimal 5 Foto)
                        </label>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4 mb-3 rounded-lg">
                            <p class="text-xs sm:text-sm text-blue-800 flex items-start">
                                <span class="mr-2">💡</span>
                                <span>Upload foto dokumentasi kelompok seperti foto anggota, kegiatan, atau lokasi. Format: JPG, PNG. Maksimal 2MB per foto.</span>
                            </p>
                        </div>
                        
                        <input type="file" 
                               name="dokumentasi[]" 
                               id="dokumentasi" 
                               multiple 
                               accept="image/jpeg,image/jpg,image/png"
                               class="w-full px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm rounded-lg sm:rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600"
                               onchange="previewImages(event)">
                        
                        @error('dokumentasi.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Preview Container -->
                        <div id="imagePreviewContainer" class="mt-3 sm:mt-4 grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3"></div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('kelompok.data-kelompok.index') }}"
                            class="w-full sm:w-auto text-center px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg sm:rounded-xl bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold transition-all duration-200 text-sm sm:text-base">
                            Batal
                        </a>

                        <button type="submit"
                            class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg sm:rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow-md hover:shadow-xl transition-all duration-300 text-sm sm:text-base">
                            💾 Simpan Data
                        </button>
                    </div>

                </form>
            </div>

            <!-- Map Section -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 border border-gray-100 lg:sticky lg:top-6">
                <div class="mb-3 sm:mb-4">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">🗺️ Pilih Lokasi di Peta</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Klik pada peta untuk menandai lokasi kelompok</p>
                </div>

                <!-- Search Box -->
                <div class="mb-3 sm:mb-4">
                    <input type="text" 
                           id="searchBox" 
                           placeholder="🔍 Cari lokasi (contoh: Purwodadi)"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>

                <!-- Map Container -->
                <div id="map" class="w-full h-64 sm:h-80 lg:h-96 rounded-lg border-2 border-gray-300 shadow-inner"></div>

                <!-- Current Location Button -->
                <button type="button" 
                        id="getCurrentLocation"
                        class="mt-3 sm:mt-4 w-full px-3 sm:px-4 py-2.5 sm:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center gap-2 text-xs sm:text-sm">
                    <span>📍</span>
                    <span>Gunakan Lokasi Saya Saat Ini</span>
                </button>

                <!-- Selected Coordinates Display -->
                <div id="selectedCoords" class="mt-3 sm:mt-4 p-3 sm:p-4 bg-green-50 border border-green-200 rounded-lg hidden">
                    <p class="text-xs sm:text-sm font-semibold text-green-800 mb-1">✅ Lokasi Terpilih:</p>
                    <p class="text-xs text-green-700 font-mono break-all" id="coordsDisplay">-</p>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
// Detect mobile device
const isMobile = window.innerWidth < 640;

// Initialize map centered on Purwodadi, Jawa Tengah
const map = L.map('map').setView([-7.0876, 110.9779], isMobile ? 12 : 13);

// Add OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

// Marker variable
let marker = null;

// Function to update marker and form
function updateLocation(lat, lng) {
    // Remove old marker if exists
    if (marker) {
        map.removeLayer(marker);
    }
    
    // Marker size based on screen size
    const markerSize = isMobile ? 24 : 30;
    
    // Custom icon
    const customIcon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="background-color: #16a34a; width: ${markerSize}px; height: ${markerSize}px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; font-size: ${isMobile ? '14px' : '16px'};">📍</div>`,
        iconSize: [markerSize, markerSize],
        iconAnchor: [markerSize/2, markerSize/2]
    });
    
    // Add new marker (draggable)
    marker = L.marker([lat, lng], {
        draggable: true,
        icon: customIcon
    }).addTo(map);
    
    // Update form input (format: lat, lng)
    document.getElementById('koordinat').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    
    // Show selected coordinates
    document.getElementById('selectedCoords').classList.remove('hidden');
    document.getElementById('coordsDisplay').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    
    // Add popup to marker - smaller on mobile
    const popupContent = isMobile 
        ? `<div style="font-size: 12px;"><b>Lokasi Terpilih</b><br>${lat.toFixed(6)}, ${lng.toFixed(6)}</div>`
        : `<b>Lokasi Terpilih</b><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`;
    
    marker.bindPopup(popupContent).openPopup();
    
    // Update location when marker is dragged
    marker.on('dragend', function(e) {
        const position = e.target.getLatLng();
        updateLocation(position.lat, position.lng);
    });
}

// Click on map to select location
map.on('click', function(e) {
    updateLocation(e.latlng.lat, e.latlng.lng);
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
                
                map.setView([lat, lng], isMobile ? 14 : 15);
                updateLocation(lat, lng);
                
                this.innerHTML = '<span>📍</span><span>Gunakan Lokasi Saya Saat Ini</span>';
                this.disabled = false;
            },
            (error) => {
                alert('Tidak dapat mengakses lokasi. Pastikan Anda mengizinkan akses lokasi.');
                this.innerHTML = '<span>📍</span><span>Gunakan Lokasi Saya Saat Ini</span>';
                this.disabled = false;
            }
        );
    } else {
        alert('Browser Anda tidak mendukung Geolocation');
    }
});

// Search functionality using Nominatim
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
                    
                    map.setView([lat, lng], isMobile ? 12 : 13);
                    updateLocation(lat, lng);
                    
                    this.value = '';
                    // Truncate long display names on mobile
                    const displayName = isMobile && data[0].display_name.length > 30 
                        ? data[0].display_name.substring(0, 30) + '...'
                        : data[0].display_name;
                    this.placeholder = `✅ ${displayName}`;
                } else {
                    alert('Lokasi tidak ditemukan. Coba kata kunci lain.');
                    this.value = originalValue;
                }
                
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

// Preview images before upload
function previewImages(event) {
    const container = document.getElementById('imagePreviewContainer');
    container.innerHTML = '';
    
    const files = event.target.files;
    
    if (files.length > 5) {
        alert('⚠️ Maksimal 5 foto yang dapat diupload!');
        event.target.value = '';
        return;
    }
    
    Array.from(files).forEach((file, index) => {
        // Check file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert(`⚠️ File ${file.name} melebihi ukuran maksimal 2MB!`);
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative group';
            const imageHeight = isMobile ? 'h-24' : 'h-32';
            div.innerHTML = `
                <img src="${e.target.result}" 
                     class="w-full ${imageHeight} object-cover rounded-lg border-2 border-gray-300"
                     alt="Preview ${index + 1}">
                <div class="absolute top-1 sm:top-2 right-1 sm:right-2 bg-green-600 text-white text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 rounded">
                    Foto ${index + 1}
                </div>
            `;
            container.appendChild(div);
        };
        
        reader.readAsDataURL(file);
    });
}

// Form validation
document.getElementById('kelompokForm').addEventListener('submit', function(e) {
    const koordinat = document.getElementById('koordinat').value;
    
    if (!koordinat) {
        e.preventDefault();
        alert('⚠️ Silakan pilih lokasi di peta terlebih dahulu!');
        return false;
    }
});

// Fix map rendering on load
setTimeout(function() {
    map.invalidateSize();
}, 100);

// Re-render map on window resize
window.addEventListener('resize', function() {
    map.invalidateSize();
});
</script>
@endsection