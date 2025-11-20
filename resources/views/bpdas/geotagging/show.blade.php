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

<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-8 slide-in">
        <a href="{{ route('bpdas.geotagging.index') }}" 
           class="inline-flex items-center text-green-600 hover:text-green-700 font-semibold mb-4">
            ← Kembali ke Daftar
        </a>
        <h1 class="text-4xl font-bold text-gray-800 mb-2">📍 Detail Calon Lokasi</h1>
        <p class="text-gray-600">Informasi lengkap calon lokasi kegiatan</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Info Lokasi -->
            <div class="bg-white rounded-2xl shadow-xl p-8 slide-in">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">📋 Informasi Lokasi</h2>
                    {!! $calonLokasi->status_badge !!}
                </div>

                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-4">
                        <label class="text-sm font-semibold text-gray-600">Nama Kelompok Desa</label>
                        <p class="text-lg text-gray-800 mt-1">{{ $calonLokasi->nama_kelompok_desa }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-b border-gray-200 pb-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Kecamatan</label>
                            <p class="text-gray-800 mt-1">{{ $calonLokasi->kecamatan }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-600">Kabupaten</label>
                            <p class="text-gray-800 mt-1">{{ $calonLokasi->kabupaten }}</p>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <label class="text-sm font-semibold text-gray-600">📍 Koordinat Pusat</label>
                        <p class="text-gray-800 mt-1 font-mono">
                            {{ number_format($centerLat, 6) }}, {{ number_format($centerLng, 6) }}
                        </p>
                        <a href="https://www.google.com/maps?q={{ $centerLat }},{{ $centerLng }}" 
                           target="_blank"
                           class="inline-flex items-center mt-2 text-blue-600 hover:text-blue-700 text-sm font-medium">
                            🗺️ Lihat di Google Maps →
                        </a>
                    </div>

                    <!-- PDF Dokumen -->
                 <div class="border-b border-gray-200 pb-4 mt-4">
    <label class="text-sm font-semibold text-gray-600">📄 Dokumen PDF</label>
    <div class="mt-2 space-y-2">
        @if(count($pdfFiles) > 0)
            @foreach($pdfFiles as $index => $file)
                <a href="{{ asset('storage/' . $file) }}" target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium">
                   📑 Dokumen {{ $index + 1 }}
                </a>
            @endforeach
        @else
            <p class="text-gray-500">PDF belum di-upload oleh kelompok.</p>
        @endif
    </div>
</div>



                    @if($calonLokasi->deskripsi)
                    <div class="border-b border-gray-200 pb-4">
                        <label class="text-sm font-semibold text-gray-600">Deskripsi</label>
                        <p class="text-gray-700 mt-2 leading-relaxed">{{ $calonLokasi->deskripsi }}</p>
                    </div>
                    @endif

                    <div class="border-b border-gray-200 pb-4">
                        <label class="text-sm font-semibold text-gray-600">Diajukan Oleh</label>
                        <p class="text-gray-800 mt-1">{{ $calonLokasi->user->name ?? '-' }}</p>
                        <p class="text-sm text-gray-500">{{ $calonLokasi->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    @if($calonLokasi->catatan_bpdas)
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <label class="text-sm font-semibold text-blue-800">💬 Catatan dari BPDAS</label>
                        <p class="text-blue-700 mt-2">{{ $calonLokasi->catatan_bpdas }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Peta -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">🗺️ Lokasi di Peta</h2>
                <div id="detailMap" class="w-full h-96 rounded-xl border-2 border-gray-200"></div>
                <p class="text-sm text-gray-500 mt-2">
                    Jumlah Titik Polygon: {{ count($polygonCoordinates) }}<br>
                    Luas Area: {{ $calonLokasi->formatted_area ?? '-' }}
                </p>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            @if($calonLokasi->status_verifikasi === 'pending')
            <div class="bg-white rounded-2xl shadow-xl p-6 slide-in">
                <h3 class="text-xl font-bold text-gray-800 mb-4">✅ Verifikasi Lokasi</h3>
                <form action="{{ route('bpdas.geotagging.verifikasi', $calonLokasi) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status_verifikasi" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500"
                                required>
                            <option value="">Pilih Status</option>
                            <option value="diverifikasi">✅ Diverifikasi</option>
                            <option value="ditolak">❌ Ditolak</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="catatan_bpdas" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500"
                                  placeholder="Tambahkan catatan atau alasan..."></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all shadow-lg font-semibold">
                        💾 Simpan Verifikasi
                    </button>
                </form>
            </div>
            @endif
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
        pdfFiles.forEach(file => {
            popupContent += `<a href="{{ asset('storage') }}/${file}" target="_blank">📑 Lihat PDF</a><br>`;
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
</script>
@endsection
