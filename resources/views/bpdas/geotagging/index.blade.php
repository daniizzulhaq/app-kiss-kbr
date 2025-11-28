@extends('layouts.dashboard')

@section('title', 'Geotagging Data Lokasi Kelompok')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8 slide-in">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-2">🗺️ Calon Lokasi Persemaian</h1>
        <p class="text-sm sm:text-base text-gray-600">Kelola dan verifikasi data lokasi dari kelompok tani</p>
    </div>

    <!-- Export Buttons -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800">📥 Export Data</h3>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <a href="{{ route('bpdas.geotagging.export.excel', request()->query()) }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium inline-flex items-center justify-center text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </a>
                
                <a href="{{ route('bpdas.geotagging.export.pdf', request()->query()) }}" 
                   class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium inline-flex items-center justify-center text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
        <p class="text-xs sm:text-sm text-gray-600 mt-2">Export data sesuai filter yang aktif</p>
    </div>

    @if(session('success'))
    <div class="mb-4 sm:mb-6 bg-green-50 border-l-4 border-green-500 p-3 sm:p-4 rounded-lg slide-in">
        <div class="flex items-center">
            <span class="text-xl sm:text-2xl mr-2 sm:mr-3">✅</span>
            <p class="text-sm sm:text-base text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Statistik Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-2 sm:mb-0">
                    <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Lokasi</p>
                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">{{ $calonLokasis->total() }}</h3>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg sm:rounded-xl flex items-center justify-center text-lg sm:text-xl lg:text-2xl">
                    📍
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-2 sm:mb-0">
                    <p class="text-gray-600 text-xs sm:text-sm mb-1">Pending</p>
                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-yellow-600">{{ $calonLokasis->where('status_verifikasi', 'pending')->count() }}</h3>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-lg sm:rounded-xl flex items-center justify-center text-lg sm:text-xl lg:text-2xl">
                    ⏳
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-2 sm:mb-0">
                    <p class="text-gray-600 text-xs sm:text-sm mb-1">Diverifikasi</p>
                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-600">{{ $calonLokasis->where('status_verifikasi', 'diverifikasi')->count() }}</h3>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-lg sm:rounded-xl flex items-center justify-center text-lg sm:text-xl lg:text-2xl">
                    ✅
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-2 sm:mb-0">
                    <p class="text-gray-600 text-xs sm:text-sm mb-1">Ditolak</p>
                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-red-600">{{ $calonLokasis->where('status_verifikasi', 'ditolak')->count() }}</h3>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-red-100 to-red-200 rounded-lg sm:rounded-xl flex items-center justify-center text-lg sm:text-xl lg:text-2xl">
                    ❌
                </div>
            </div>
        </div>
    </div>

    <!-- Peta Lokasi -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl p-4 sm:p-6 mb-6 sm:mb-8 slide-in">
        <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">🗺️ Peta Sebaran Lokasi</h2>
        <div id="map" class="w-full h-64 sm:h-80 lg:h-96 rounded-lg sm:rounded-xl border-2 border-gray-200"></div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-6">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div>
                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diverifikasi" {{ request('status') == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Kabupaten</label>
                <input type="text" name="kabupaten" value="{{ request('kabupaten') }}" 
                       placeholder="Cari kabupaten..."
                       class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Kecamatan</label>
                <input type="text" name="kecamatan" value="{{ request('kecamatan') }}" 
                       placeholder="Cari kecamatan..."
                       class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm sm:text-base">
                    🔍 Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table - Mobile Cards View -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kelompok</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Lokasi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Koordinat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Pengusul</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Dokumen</th>
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
                            @php $hasPdf = false; @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @php $fieldName = "pdf_dokumen_{$i}"; @endphp
                                @if($lokasi->$fieldName)
                                    @php $hasPdf = true; @endphp
                                    <a href="{{ asset('storage/' . $lokasi->$fieldName) }}" target="_blank" class="text-blue-600 hover:underline text-xs">
                                        Dokumen {{ $i }}
                                    </a><br>
                                @endif
                            @endfor
                            @if(!$hasPdf)
                                <span class="text-gray-500 text-xs">Tidak ada</span>
                            @endif
                        </td>
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
                        <td colspan="8" class="px-6 py-12 text-center">
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

        <!-- Mobile Cards View -->
        <div class="lg:hidden divide-y divide-gray-200">
            @forelse($calonLokasis as $index => $lokasi)
            <div class="p-4 hover:bg-green-50 transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-medium text-gray-500">#{!! $calonLokasis->firstItem() + $index !!}</span>
                            {!! $lokasi->status_badge !!}
                        </div>
                        <h3 class="font-bold text-gray-800 text-sm sm:text-base">{{ $lokasi->nama_kelompok_desa }}</h3>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex items-start">
                        <span class="text-gray-500 w-24 flex-shrink-0">Lokasi:</span>
                        <span class="text-gray-700">{{ $lokasi->kecamatan }}, {{ $lokasi->kabupaten }}</span>
                    </div>

                    @if($lokasi->center_latitude && $lokasi->center_longitude)
                    <div class="flex items-start">
                        <span class="text-gray-500 w-24 flex-shrink-0">Koordinat:</span>
                        <span class="text-xs text-green-700 font-mono bg-green-50 px-2 py-1 rounded break-all">
                            {{ number_format($lokasi->center_latitude, 6) }}, {{ number_format($lokasi->center_longitude, 6) }}
                        </span>
                    </div>
                    @endif

                    <div class="flex items-start">
                        <span class="text-gray-500 w-24 flex-shrink-0">Pengusul:</span>
                        <span class="text-gray-700">{{ $lokasi->user->name ?? '-' }}</span>
                    </div>

                    @php $hasPdf = false; @endphp
                    <div class="flex items-start">
                        <span class="text-gray-500 w-24 flex-shrink-0">Dokumen:</span>
                        <div class="flex-1">
                            @for($i = 1; $i <= 5; $i++)
                                @php $fieldName = "pdf_dokumen_{$i}"; @endphp
                                @if($lokasi->$fieldName)
                                    @php $hasPdf = true; @endphp
                                    <a href="{{ asset('storage/' . $lokasi->$fieldName) }}" target="_blank" class="text-blue-600 hover:underline text-xs block">
                                        📄 Dokumen {{ $i }}
                                    </a>
                                @endif
                            @endfor
                            @if(!$hasPdf)
                                <span class="text-gray-500 text-xs">Tidak ada</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-200">
                    <a href="{{ route('bpdas.geotagging.show', $lokasi) }}" 
                       class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium inline-flex items-center justify-center">
                        👁️ Lihat Detail
                    </a>
                </div>
            </div>
            @empty
            <div class="p-8 sm:p-12 text-center">
                <span class="text-4xl sm:text-6xl mb-3 sm:mb-4 block">📍</span>
                <p class="text-gray-500 font-medium text-sm sm:text-base">Belum ada data lokasi</p>
            </div>
            @endforelse
        </div>

        @if($calonLokasis->hasPages())
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200 bg-gray-50">
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

// Handle map resize on mobile
window.addEventListener('resize', function() {
    map.invalidateSize();
});
</script>
@endsection