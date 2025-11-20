@extends('layouts.dashboard')

@section('title', 'Edit Data Kelompok')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">✏️ Edit Data Kelompok</h1>
            <p class="text-gray-600">Perbarui data kelompok dengan lengkap dan benar.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Form Section -->
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                <form action="{{ route('kelompok.data-kelompok.update', $kelompok) }}" method="POST" enctype="multipart/form-data" id="kelompokForm">
                    @csrf
                    @method('PUT')

                    <!-- Nama Kelompok -->
                    <div class="mb-6">
                        <label for="nama_kelompok" class="block text-sm text-gray-700 font-semibold mb-2">
                            Nama Kelompok <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kelompok" id="nama_kelompok" required
                            value="{{ old('nama_kelompok', $kelompok->nama_kelompok) }}"
                            class="w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600 @error('nama_kelompok') border-red-500 @enderror">
                        @error('nama_kelompok')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Blok & Desa -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm text-gray-700 font-semibold mb-2">Blok</label>
                            <input type="text" name="blok" value="{{ old('blok', $kelompok->blok) }}"
                                class="w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                            @error('blok') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 font-semibold mb-2">Desa</label>
                            <input type="text" name="desa" value="{{ old('desa', $kelompok->desa) }}"
                                class="w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                            @error('desa') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Kecamatan & Kabupaten -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm text-gray-700 font-semibold mb-2">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', $kelompok->kecamatan) }}"
                                class="w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                            @error('kecamatan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 font-semibold mb-2">Kabupaten</label>
                            <input type="text" name="kabupaten" value="{{ old('kabupaten', $kelompok->kabupaten) }}"
                                class="w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                            @error('kabupaten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Koordinat (Hidden, Auto-fill dari map) -->
                    <div class="mb-6">
                        <label class="block text-sm text-gray-700 font-semibold mb-2">
                            📍 Koordinat Lokasi
                        </label>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-3 rounded-lg">
                            <p class="text-sm text-blue-800 flex items-start">
                                <span class="mr-2">💡</span>
                                <span><strong>Klik pada peta</strong> di sebelah kanan untuk memperbarui lokasi. Koordinat akan terisi otomatis!</span>
                            </p>
                        </div>
                        <input type="text" name="koordinat" id="koordinat" 
                            placeholder="Klik peta untuk mengisi koordinat"
                            value="{{ old('koordinat', $kelompok->koordinat) }}" readonly
                            class="w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm bg-gray-50 focus:border-green-600 focus:ring-green-600">
                        @error('koordinat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Anggota & Kontak -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm text-gray-700 font-semibold mb-2">Jumlah Anggota</label>
                            <input type="number" name="anggota" min="0" value="{{ old('anggota', $kelompok->anggota) }}"
                                class="w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                            @error('anggota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 font-semibold mb-2">Kontak</label>
                            <input type="text" name="kontak" placeholder="cth: 08123456789"
                                value="{{ old('kontak', $kelompok->kontak) }}"
                                class="w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                            @error('kontak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- SPKS -->
                    <div class="mb-6">
                        <label class="block text-sm text-gray-700 font-semibold mb-2">SPKS</label>
                        <input type="text" name="spks" value="{{ old('spks', $kelompok->spks) }}"
                            class="w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">
                        @error('spks') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Rekening -->
                    <div class="mb-6">
                        <label class="block text-sm text-gray-700 font-semibold mb-2">Rekening</label>
                        <textarea name="rekening" rows="3"
                            class="w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600">{{ old('rekening', $kelompok->rekening) }}</textarea>
                        @error('rekening') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Dokumentasi Foto yang Sudah Ada -->
                    @php
                        // Handle both array and JSON string formats
                        $dokumentasiArray = [];
                        if (isset($kelompok->dokumentasi)) {
                            if (is_array($kelompok->dokumentasi)) {
                                $dokumentasiArray = $kelompok->dokumentasi;
                            } elseif (is_string($kelompok->dokumentasi)) {
                                $decoded = json_decode($kelompok->dokumentasi, true);
                                $dokumentasiArray = is_array($decoded) ? $decoded : [];
                            }
                        }
                    @endphp

                    @if(!empty($dokumentasiArray))
                    <div class="mb-6">
                        <label class="block text-sm text-gray-700 font-semibold mb-2">
                            📸 Foto Dokumentasi Saat Ini
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                            @foreach($dokumentasiArray as $index => $foto)
                            <div class="relative group">
                                <img src="{{ Storage::url($foto) }}" 
                                     class="w-full h-32 object-cover rounded-lg border-2 border-gray-300"
                                     alt="Foto {{ $index + 1 }}">
                                <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                    Foto {{ $index + 1 }}
                                </div>
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all rounded-lg flex items-center justify-center">
                                    <button type="button" 
                                            onclick="deletePhoto({{ $kelompok->id }}, '{{ $foto }}', this)"
                                            class="opacity-0 group-hover:opacity-100 bg-red-600 text-white px-3 py-1 rounded-lg text-sm font-semibold hover:bg-red-700 transition-all">
                                        🗑️ Hapus
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Upload Foto Baru -->
                    <div class="mb-6">
                        <label class="block text-sm text-gray-700 font-semibold mb-2">
                            📸 Upload Foto Baru (Maksimal 5 Foto)
                        </label>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-3 rounded-lg">
                            <p class="text-sm text-blue-800 flex items-start">
                                <span class="mr-2">💡</span>
                                <span>Upload foto dokumentasi tambahan. Format: JPG, PNG. Maksimal 2MB per foto. Foto yang sudah ada tidak akan terhapus kecuali Anda menghapusnya secara manual.</span>
                            </p>
                        </div>
                        
                        <input type="file" 
                               name="dokumentasi[]" 
                               id="dokumentasi" 
                               multiple 
                               accept="image/jpeg,image/jpg,image/png"
                               class="w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-green-600 focus:ring-green-600"
                               onchange="previewImages(event)">
                        
                        @error('dokumentasi.*')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Preview Container untuk foto baru -->
                        <div id="imagePreviewContainer" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-3"></div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('kelompok.data-kelompok.index') }}"
                            class="px-6 py-3 rounded-xl bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold transition-all duration-200">
                            Batal
                        </a>

                        <button type="submit"
                            class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md hover:shadow-xl transition-all duration-300">
                            ✅ Update Data
                        </button>
                    </div>

                </form>
            </div>

            <!-- Map Section -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 sticky top-6">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">🗺️ Pilih Lokasi di Peta</h3>
                    <p class="text-sm text-gray-600">Klik pada peta untuk memperbarui lokasi kelompok</p>
                </div>

                <!-- Search Box -->
                <div class="mb-4">
                    <input type="text" 
                           id="searchBox" 
                           placeholder="🔍 Cari lokasi (contoh: Purwodadi, Jawa Tengah)"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
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

                <!-- Selected Coordinates Display -->
                <div id="selectedCoords" class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm font-semibold text-green-800 mb-1">✅ Lokasi Terpilih:</p>
                    <p class="text-xs text-green-700 font-mono" id="coordsDisplay">{{ $kelompok->koordinat ?? '-' }}</p>
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
// Parse existing coordinates
let initialLat = -7.0876;
let initialLng = 110.9779;
let initialZoom = 13;

const existingCoordinates = "{{ $kelompok->koordinat ?? '' }}";
if (existingCoordinates) {
    const coords = existingCoordinates.split(',').map(c => parseFloat(c.trim()));
    if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
        initialLat = coords[0];
        initialLng = coords[1];
        initialZoom = 15;
    }
}

// Initialize map
const map = L.map('map').setView([initialLat, initialLng], initialZoom);

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
    
    // Add new marker (draggable)
    marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);
    
    // Update form input (format: lat, lng)
    document.getElementById('koordinat').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    
    // Show selected coordinates
    document.getElementById('selectedCoords').classList.remove('hidden');
    document.getElementById('coordsDisplay').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    
    // Add popup to marker
    marker.bindPopup(`<b>Lokasi Terpilih</b><br>Lat: ${lat.toFixed(6)}<br>Lng: ${lng.toFixed(6)}`).openPopup();
    
    // Update location when marker is dragged
    marker.on('dragend', function(e) {
        const position = e.target.getLatLng();
        updateLocation(position.lat, position.lng);
    });
}

// Set initial marker if coordinates exist
if (existingCoordinates) {
    updateLocation(initialLat, initialLng);
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
                
                map.setView([lat, lng], 15);
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
                    
                    map.setView([lat, lng], 13);
                    updateLocation(lat, lng);
                    
                    this.value = '';
                    this.placeholder = `✅ ${data[0].display_name}`;
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
            div.innerHTML = `
                <img src="${e.target.result}" 
                     class="w-full h-32 object-cover rounded-lg border-2 border-green-300"
                     alt="Preview ${index + 1}">
                <div class="absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-1 rounded">
                    Foto Baru ${index + 1}
                </div>
            `;
            container.appendChild(div);
        };
        
        reader.readAsDataURL(file);
    });
}

// Function to delete existing photo
function deletePhoto(kelompokId, photoPath, button) {
    if (!confirm('Apakah Anda yakin ingin menghapus foto ini?')) return;

    button.disabled = true;
    button.textContent = '⏳ Menghapus...';

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // GUNAKAN ROUTE HELPER LARAVEL
    const url = "{{ route('kelompok.data-kelompok.delete-photo', ':id') }}".replace(':id', kelompokId);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ photo_path: photoPath })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            button.closest('.relative.group').remove();
            alert(data.message);
        } else {
            throw new Error(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(err => {
        console.error(err);
        alert('❌ Terjadi kesalahan saat menghapus foto: ' + err.message);
        button.disabled = false;
        button.innerHTML = '🗑️ Hapus';
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
</script>
@endsection