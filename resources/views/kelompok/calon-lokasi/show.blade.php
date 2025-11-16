@extends('layouts.dashboard')

@section('title', 'Detail Calon Lokasi')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('kelompok.calon-lokasi.index') }}" 
               class="text-green-600 hover:text-green-700 font-medium mb-4 inline-block">
                ← Kembali ke Daftar
            </a>
            <h2 class="text-3xl font-bold text-gray-800">📍 Detail Calon Lokasi</h2>
            <p class="text-gray-600 mt-1">Informasi detail calon lokasi yang telah Anda ajukan</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Info Lokasi -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-800">📋 Informasi Lokasi</h3>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                            @if($calonLokasi->status_verifikasi == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($calonLokasi->status_verifikasi == 'diverifikasi') bg-green-100 text-green-800
                            @elseif($calonLokasi->status_verifikasi == 'ditolak') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($calonLokasi->status_verifikasi == 'pending') ⏳ Menunggu
                            @elseif($calonLokasi->status_verifikasi == 'diverifikasi') ✅ Diverifikasi
                            @elseif($calonLokasi->status_verifikasi == 'ditolak') ❌ Ditolak
                            @else - @endif
                        </span>
                    </div>

                    <div class="space-y-4">
                        <div class="border-b border-gray-200 pb-4">
                            <label class="text-sm font-semibold text-gray-600">Nama Kelompok Desa</label>
                            <p class="text-lg text-gray-800 mt-1 font-semibold">{{ $calonLokasi->nama_kelompok_desa }}</p>
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
                            <label class="text-sm font-semibold text-gray-600">📍 Koordinat GPS</label>
                            @if($calonLokasi->latitude && $calonLokasi->longitude)
                                <p class="text-gray-800 mt-1 font-mono text-lg">
                                    {{ number_format($calonLokasi->latitude, 6) }}, {{ number_format($calonLokasi->longitude, 6) }}
                                </p>
                                <a href="https://www.google.com/maps?q={{ $calonLokasi->latitude }},{{ $calonLokasi->longitude }}" 
                                   target="_blank"
                                   class="inline-flex items-center mt-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                                    🗺️ Buka di Google Maps
                                </a>
                            @else
                                <p class="text-gray-500 mt-1 text-sm">Koordinat tidak tersedia</p>
                            @endif
                        </div>

                        @if($calonLokasi->koordinat_pdf_lokasi)
                        <div class="border-b border-gray-200 pb-4">
                            <label class="text-sm font-semibold text-gray-600">📄 Dokumen PDF Koordinat</label>
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $calonLokasi->koordinat_pdf_lokasi) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors font-medium">
                                    📄 Lihat PDF Koordinat
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($calonLokasi->deskripsi)
                        <div class="border-b border-gray-200 pb-4">
                            <label class="text-sm font-semibold text-gray-600">📝 Deskripsi Lokasi</label>
                            <p class="text-gray-700 mt-2 leading-relaxed">{{ $calonLokasi->deskripsi }}</p>
                        </div>
                        @endif

                        <div class="border-b border-gray-200 pb-4">
                            <label class="text-sm font-semibold text-gray-600">📅 Tanggal Pengajuan</label>
                            <p class="text-gray-800 mt-1">{{ $calonLokasi->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>

                        @if($calonLokasi->catatan_bpdas)
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                            <label class="text-sm font-semibold text-blue-800">💬 Tanggapan dari BPDAS</label>
                            <p class="text-blue-700 mt-2 leading-relaxed">{{ $calonLokasi->catatan_bpdas }}</p>
                            <p class="text-xs text-blue-600 mt-2">{{ $calonLokasi->updated_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Peta -->
                @if($calonLokasi->latitude && $calonLokasi->longitude)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">🗺️ Lokasi di Peta</h3>
                    <div id="detailMap" class="w-full h-96 rounded-lg border-2 border-gray-200"></div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl shadow-lg p-6 border-2 border-green-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">ℹ️ Status Pengajuan</h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-600 font-semibold mb-1">Status Saat Ini:</p>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full inline-block
                                @if($calonLokasi->status_verifikasi == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($calonLokasi->status_verifikasi == 'diverifikasi') bg-green-100 text-green-800
                                @elseif($calonLokasi->status_verifikasi == 'ditolak') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                @if($calonLokasi->status_verifikasi == 'pending') ⏳ Menunggu Verifikasi
                                @elseif($calonLokasi->status_verifikasi == 'diverifikasi') ✅ Sudah Diverifikasi
                                @elseif($calonLokasi->status_verifikasi == 'ditolak') ❌ Ditolak
                                @else - @endif
                            </span>
                        </div>
                        
                        <div class="pt-3 border-t border-green-200">
                            <p class="text-gray-600 font-semibold mb-1">Tanggal Pengajuan:</p>
                            <p class="text-gray-700">{{ $calonLokasi->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>

                        @if($calonLokasi->status_verifikasi !== 'pending')
                        <div class="pt-3 border-t border-green-200">
                            <p class="text-gray-600 font-semibold mb-1">Tanggal Diproses:</p>
                            <p class="text-gray-700">{{ $calonLokasi->updated_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                @if($calonLokasi->status_verifikasi === 'pending')
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">⚙️ Aksi</h3>
                    <div class="space-y-3">
                        <a href="{{ route('kelompok.calon-lokasi.edit', $calonLokasi) }}" 
                           class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-medium">
                            <span>✏️</span>
                            <span>Edit Lokasi</span>
                        </a>
                        
                        <form action="{{ route('kelompok.calon-lokasi.destroy', $calonLokasi) }}" 
                              method="POST" 
                              onsubmit="return confirm('⚠️ Yakin ingin menghapus lokasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                <span>🗑️</span>
                                <span>Hapus Lokasi</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Info Help -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">💡 Informasi</h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @if($calonLokasi->status_verifikasi === 'pending')
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Lokasi Anda sedang menunggu verifikasi dari BPDAS</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Anda masih bisa mengedit atau menghapus lokasi ini</span>
                        </li>
                        @elseif($calonLokasi->status_verifikasi === 'diverifikasi')
                        <li class="flex items-start">
                            <span class="mr-2">✅</span>
                            <span>Lokasi Anda telah diverifikasi oleh BPDAS</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Data tidak dapat diubah setelah diverifikasi</span>
                        </li>
                        @elseif($calonLokasi->status_verifikasi === 'ditolak')
                        <li class="flex items-start">
                            <span class="mr-2">❌</span>
                            <span>Lokasi Anda ditolak. Lihat catatan dari BPDAS</span>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2">•</span>
                            <span>Anda dapat mengajukan lokasi baru</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
@if($calonLokasi->latitude && $calonLokasi->longitude)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const detailMap = L.map('detailMap').setView([{{ $calonLokasi->latitude }}, {{ $calonLokasi->longitude }}], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(detailMap);

// Add marker
const marker = L.marker([{{ $calonLokasi->latitude }}, {{ $calonLokasi->longitude }}])
    .bindPopup(`
        <div style="text-align: center;">
            <strong style="font-size: 14px;">{{ $calonLokasi->nama_kelompok_desa }}</strong><br>
            <span style="font-size: 12px; color: #666;">{{ $calonLokasi->kecamatan }}, {{ $calonLokasi->kabupaten }}</span><br>
            <a href="https://www.google.com/maps?q={{ $calonLokasi->latitude }},{{ $calonLokasi->longitude }}" 
               target="_blank" 
               style="color: #2563eb; font-size: 11px; text-decoration: underline;">
                Lihat di Google Maps
            </a>
        </div>
    `)
    .addTo(detailMap)
    .openPopup();

// Add circle radius (optional - visual aid)
L.circle([{{ $calonLokasi->latitude }}, {{ $calonLokasi->longitude }}], {
    color: 'green',
    fillColor: '#22c55e',
    fillOpacity: 0.1,
    radius: 500
}).addTo(detailMap);
</script>
@endif
@endsection