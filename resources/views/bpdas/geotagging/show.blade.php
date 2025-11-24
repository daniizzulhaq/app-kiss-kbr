@extends('layouts.dashboard')

@section('title', 'Detail Lokasi - Geotagging')

@section('content')
@php
    // Polygon
    $polygonCoordinates = is_array($calonLokasi->polygon_coordinates)
        ? $calonLokasi->polygon_coordinates
        : json_decode($calonLokasi->polygon_coordinates, true) ?? [];

    // Koordinat pusat
    $centerLat = $calonLokasi->latitude ?? $calonLokasi->center_latitude ?? -6.2088;
    $centerLng = $calonLokasi->longitude ?? $calonLokasi->center_longitude ?? 106.8456;

    // Ambil PDF sesuai field index
    $pdfFiles = [];
    for($i = 1; $i <= 5; $i++){
        $fieldName = "pdf_dokumen_{$i}";
        if($calonLokasi->$fieldName){
            $pdfFiles[] = $calonLokasi->$fieldName;
        }
    }
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8 slide-in">
        <a href="{{ route('bpdas.geotagging.index') }}" 
           class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold mb-3 sm:mb-4 text-sm sm:text-base">
            ← Kembali ke Daftar
        </a>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-2">📍 Detail Calon Lokasi</h1>
        <p class="text-sm sm:text-base text-gray-600">Informasi lengkap calon lokasi kegiatan</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
        <!-- Sidebar - Tampil di atas pada mobile -->
        <div class="lg:col-span-1 lg:order-2 space-y-4 sm:space-y-6">
            @if($calonLokasi->status_verifikasi === 'pending')
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 slide-in">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">✅ Verifikasi Lokasi</h3>
                <form action="{{ route('bpdas.geotagging.verifikasi', $calonLokasi) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 sm:mb-4">
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status_verifikasi" 
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-green-500"
                                required>
                            <option value="">Pilih Status</option>
                            <option value="diverifikasi">✅ Diverifikasi</option>
                            <option value="ditolak">❌ Ditolak</option>
                        </select>
                    </div>

                    <div class="mb-3 sm:mb-4">
                        <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="catatan_bpdas" rows="4"
                                  class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-green-500"
                                  placeholder="Tambahkan catatan atau alasan..."></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg sm:rounded-xl hover:from-green-700 hover:to-green-800 transition-all shadow-lg font-semibold text-sm sm:text-base">
                        💾 Simpan Verifikasi
                    </button>
                </form>
            </div>
            @endif

            <!-- Status Card - Muncul jika sudah diverifikasi -->
            @if($calonLokasi->status_verifikasi !== 'pending')
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">📊 Status Verifikasi</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs sm:text-sm font-semibold text-gray-600">Status Saat Ini</label>
                        <div class="mt-2">
                            {!! $calonLokasi->status_badge !!}
                        </div>
                    </div>
                    @if($calonLokasi->catatan_bpdas)
                    <div>
                        <label class="text-xs sm:text-sm font-semibold text-gray-600">Catatan BPDAS</label>
                        <p class="text-sm text-gray-700 mt-1 bg-gray-50 p-3 rounded-lg">{{ $calonLokasi->catatan_bpdas }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 lg:order-1 space-y-4 sm:space-y-6">
            <!-- Info Lokasi -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 lg:p-8 slide-in">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-3">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">📋 Informasi Lokasi</h2>
                    <div class="sm:hidden">
                        {!! $calonLokasi->status_badge !!}
                    </div>
                    <div class="hidden sm:block">
                        {!! $calonLokasi->status_badge !!}
                    </div>
                </div>

                <div class="space-y-3 sm:space-y-4">
                    <div class="border-b border-gray-200 pb-3 sm:pb-4">
                        <label class="text-xs sm:text-sm font-semibold text-gray-600">Nama Kelompok Desa</label>
                        <p class="text-base sm:text-lg text-gray-800 mt-1">{{ $calonLokasi->nama_kelompok_desa }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 border-b border-gray-200 pb-3 sm:pb-4">
                        <div>
                            <label class="text-xs sm:text-sm font-semibold text-gray-600">Kecamatan</label>
                            <p class="text-sm sm:text-base text-gray-800 mt-1">{{ $calonLokasi->kecamatan }}</p>
                        </div>
                        <div>
                            <label class="text-xs sm:text-sm font-semibold text-gray-600">Kabupaten</label>
                            <p class="text-sm sm:text-base text-gray-800 mt-1">{{ $calonLokasi->kabupaten }}</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-3 sm:pb-4">
                        <label class="text-xs sm:text-sm font-semibold text-gray-600">📍 Koordinat Pusat</label>
                        <p class="text-sm sm:text-base text-gray-800 mt-1 font-mono break-all">
                            {{ number_format($centerLat, 6) }}, {{ number_format($centerLng, 6) }}
                        </p>
                        <a href="https://www.google.com/maps?q={{ $centerLat }},{{ $centerLng }}" 
                           target="_blank"
                           class="inline-flex items-center mt-2 text-blue-600 hover:text-blue-700 text-xs sm:text-sm font-medium">
                            🗺️ Lihat di Google Maps →
                        </a>
                    </div>

                    <!-- PDF Dokumen -->
                    <div class="border-b border-gray-200 pb-3 sm:pb-4">
                        <label class="text-xs sm:text-sm font-semibold text-gray-600">📄 Dokumen PDF</label>
                        <div class="mt-2 space-y-2">
                            @if(count($pdfFiles) > 0)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($pdfFiles as $index => $file)
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                           class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium text-xs sm:text-sm">
                                           📑 Dokumen {{ $index + 1 }}
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">PDF belum di-upload oleh kelompok.</p>
                            @endif
                        </div>
                    </div>

                    @if($calonLokasi->deskripsi)
                    <div class="border-b border-gray-200 pb-3 sm:pb-4">
                        <label class="text-xs sm:text-sm font-semibold text-gray-600">Deskripsi</label>
                        <p class="text-sm sm:text-base text-gray-700 mt-2 leading-relaxed">{{ $calonLokasi->deskripsi }}</p>
                    </div>
                    @endif

                    <div class="border-b border-gray-200 pb-3 sm:pb-4">
                        <label class="text-xs sm:text-sm font-semibold text-gray-600">Diajukan Oleh</label>
                        <p class="text-sm sm:text-base text-gray-800 mt-1">{{ $calonLokasi->user->name ?? '-' }}</p>
                        <p class="text-xs sm:text-sm text-gray-500">{{ $calonLokasi->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    @if($calonLokasi->catatan_bpdas && $calonLokasi->status_verifikasi !== 'pending')
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-3 sm:p-4 rounded">
                        <label class="text-xs sm:text-sm font-semibold text-blue-800">💬 Catatan dari BPDAS</label>
                        <p class="text-sm sm:text-base text-blue-700 mt-2">{{ $calonLokasi->catatan_bpdas }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Peta -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">🗺️ Lokasi di Peta</h2>
                <div id="detailMap" class="w-full h-64 sm:h-80 lg:h-96 rounded-lg sm:rounded-xl border-2 border-gray-200"></div>
                <div class="mt-3 sm:mt-4 p-3 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-2 gap-2 text-xs sm:text-sm text-gray-600">
                        <div>
                            <span class="font-semibold">Jumlah Titik:</span>
                            <span class="block sm:inline sm:ml-1">{{ count($polygonCoordinates) }}</span>
                        </div>
                        <div>
                            <span class="font-semibold">Luas Area:</span>
                            <span class="block sm:inline sm:ml-1">{{ $calonLokasi->formatted_area ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('detailMap').setView([{{ $centerLat }}, {{ $centerLng }}], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Polygon
const polygonCoords = @json($polygonCoordinates);

if(polygonCoords.length > 0){
    const latlngs = polygonCoords.map(c => [c[0], c[1]]);
    const polygon = L.polygon(latlngs, { color:'#10b981', fillColor:'#10b981', fillOpacity:0.3, weight:3 }).addTo(map);

    // Marker di pusat
    const center = [{{ $centerLat }}, {{ $centerLng }}];

    let popupContent = `<b>{{ $calonLokasi->nama_kelompok_desa }}</b><br>
                        Jumlah Titik: ${latlngs.length}<br>
                        Luas Area: {{ $calonLokasi->formatted_area ?? '-' }}<br>`;

    // Tambahkan PDF di popup
    const pdfFiles = @json($pdfFiles);
    if(pdfFiles.length > 0){
        pdfFiles.forEach((file, index) => {
            popupContent += `<a href="{{ asset('storage') }}/${file}" target="_blank" style="display:block; margin-top:4px;">📑 Dokumen ${index + 1}</a>`;
        });
    } else {
        popupContent += 'PDF belum di-upload';
    }

    L.marker(center).addTo(map)
        .bindPopup(popupContent)
        .openPopup();

    map.fitBounds(polygon.getBounds());
} else {
    L.marker([{{ $centerLat }}, {{ $centerLng }}]).addTo(map)
        .bindPopup('<b>{{ $calonLokasi->nama_kelompok_desa }}</b><br>Tidak ada polygon<br>PDF belum di-upload')
        .openPopup();
}

// Handle map resize on mobile
window.addEventListener('resize', function() {
    map.invalidateSize();
});

// Trigger resize after load to ensure map renders correctly
setTimeout(function() {
    map.invalidateSize();
}, 250);
</script>
@endsection