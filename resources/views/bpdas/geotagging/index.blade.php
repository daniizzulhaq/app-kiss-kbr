@extends('layouts.dashboard')

@section('title', 'Geotagging Data Lokasi Kelompok')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 slide-in">
        <h1 class="text-4xl font-bold text-gray-800 mb-2">🗺️ Geotagging Data Lokasi</h1>
        <p class="text-gray-600">Kelola dan verifikasi data lokasi dari kelompok tani</p>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg slide-in">
        <div class="flex items-center">
            <span class="text-2xl mr-3">✅</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Lokasi</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $calonLokasis->total() }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-2xl">
                    📍
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Pending</p>
                    <h3 class="text-3xl font-bold text-yellow-600">{{ $calonLokasis->where('status_verifikasi', 'pending')->count() }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl flex items-center justify-center text-2xl">
                    ⏳
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Diverifikasi</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ $calonLokasis->where('status_verifikasi', 'diverifikasi')->count() }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center text-2xl">
                    ✅
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Ditolak</p>
                    <h3 class="text-3xl font-bold text-red-600">{{ $calonLokasis->where('status_verifikasi', 'ditolak')->count() }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center text-2xl">
                    ❌
                </div>
            </div>
        </div>
    </div>

    <!-- Peta Lokasi -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 slide-in">
        <h2 class="text-xl font-bold text-gray-800 mb-4">🗺️ Peta Sebaran Lokasi</h2>
        <div id="map" class="w-full h-96 rounded-xl border-2 border-gray-200"></div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diverifikasi" {{ request('status') == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kabupaten</label>
                <input type="text" name="kabupaten" value="{{ request('kabupaten') }}" 
                       placeholder="Cari kabupaten..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan</label>
                <input type="text" name="kecamatan" value="{{ request('kecamatan') }}" 
                       placeholder="Cari kecamatan..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    🔍 Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kelompok</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Lokasi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Koordinat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Pengusul</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($calonLokasis as $index => $lokasi)
                    <tr class="hover:bg-green-50 transition-colors duration-200">
                        <td class="px-6 py-4 text-gray-700">{{ $calonLokasis->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $lokasi->nama_kelompok_desa }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">{{ $lokasi->kecamatan }}</div>
                            <div class="text-xs text-gray-500">{{ $lokasi->kabupaten }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($lokasi->center_latitude && $lokasi->center_longitude)
                                <span class="text-xs text-green-700 font-mono bg-green-50 px-2 py-1 rounded">
                                    {{ number_format($lokasi->center_latitude, 6) }}, {{ number_format($lokasi->center_longitude, 6) }}
                                </span>
                            @else
                                <span class="text-xs text-gray-500">Tidak ada koordinat</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $lokasi->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">{!! $lokasi->status_badge !!}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('bpdas.geotagging.show', $lokasi) }}" 
                                   class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                                    👁️ Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-6xl mb-4">📍</span>
                                <p class="text-gray-500 font-medium">Belum ada data lokasi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($calonLokasis->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $calonLokasis->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Leaflet JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Initialize map
const map = L.map('map').setView([-6.175392, 106.827153], 8);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Add markers
const locations = @json($lokasiMap);

locations.forEach(loc => {
    const color = loc.status_verifikasi === 'diverifikasi' ? 'green' : 
                  loc.status_verifikasi === 'ditolak' ? 'red' : 'orange';

    const icon = L.divIcon({
        html: `<div style="background-color: ${color}; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
        className: '',
        iconSize: [20, 20]
    });

    let popupContent = `<b>${loc.nama_kelompok_desa}</b><br>Status: ${loc.status_verifikasi}`;

    // Tambahkan info polygon jika ada
    if(loc.polygon_coordinates && loc.polygon_coordinates.length > 0){
        const points = loc.polygon_coordinates.length;
        popupContent += `<br>Jumlah titik polygon: ${points}`;
    }

    L.marker([loc.latitude, loc.longitude], { icon: icon })
        .bindPopup(popupContent)
        .addTo(map);
});

// Fit bounds jika ada marker
if (locations.length > 0) {
    const bounds = L.latLngBounds(locations.map(loc => [loc.latitude, loc.longitude]));
    map.fitBounds(bounds, { padding: [50, 50] });
}
</script>
@endsection
