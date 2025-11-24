@extends('layouts.dashboard')

@section('title', 'Calon Lokasi - Sistem KBR')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
    <!-- Header -->
    <div class="mb-6 sm:mb-8 slide-in">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-1 sm:mb-2 flex items-center gap-2">
                    <span>📍</span>
                    <span>Calon Lokasi Persemayam</span>
                </h1>
                <p class="text-sm sm:text-base text-gray-600">Kelola data calon lokasi kegiatan kelompok tani</p>
            </div>
            <a href="{{ route('kelompok.calon-lokasi.create') }}" 
               class="px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold text-sm sm:text-base text-center">
                ➕ Tambah Lokasi
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="mb-4 sm:mb-6 bg-green-50 border-l-4 border-green-500 p-3 sm:p-4 rounded-lg slide-in">
        <div class="flex items-center">
            <span class="text-xl sm:text-2xl mr-2 sm:mr-3">✅</span>
            <p class="text-sm sm:text-base text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Statistik Cards -->
    @php
        $allLokasis = $calonLokasis->items();
        $totalLokasi = $calonLokasis->total();
        $diverifikasiCount = collect($allLokasis)->where('status_verifikasi', 'diverifikasi')->count();
        $pendingCount = collect($allLokasis)->where('status_verifikasi', 'pending')->count();
        $ditolakCount = collect($allLokasis)->where('status_verifikasi', 'ditolak')->count();
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm mb-0.5 sm:mb-1">Total Lokasi</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $totalLokasi }}</h3>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg sm:rounded-xl flex items-center justify-center text-xl sm:text-2xl">
                    📍
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm mb-0.5 sm:mb-1">Diverifikasi</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-green-600">{{ $diverifikasiCount }}</h3>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-lg sm:rounded-xl flex items-center justify-center text-xl sm:text-2xl">
                    ✅
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm mb-0.5 sm:mb-1">Menunggu</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-yellow-600">{{ $pendingCount }}</h3>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-lg sm:rounded-xl flex items-center justify-center text-xl sm:text-2xl">
                    ⏳
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-3 sm:p-4 lg:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm mb-0.5 sm:mb-1">Ditolak</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-red-600">{{ $ditolakCount }}</h3>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-red-100 to-red-200 rounded-lg sm:rounded-xl flex items-center justify-center text-xl sm:text-2xl">
                    ❌
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Table View (lg and above) -->
    <div class="hidden lg:block bg-white rounded-2xl shadow-xl overflow-hidden slide-in">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kelompok Desa</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kecamatan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kabupaten</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Koordinat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Luas Area</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Dokumen</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($calonLokasis as $index => $lokasi)
                    @php
                        $polygon = is_array($lokasi->polygon_coordinates) 
                                    ? $lokasi->polygon_coordinates 
                                    : json_decode($lokasi->polygon_coordinates, true);
                    @endphp
                    <tr class="hover:bg-green-50 transition-colors duration-200">
                        <td class="px-6 py-4 text-gray-700">{{ $calonLokasis->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $lokasi->nama_kelompok_desa }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $lokasi->kecamatan }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $lokasi->kabupaten }}</td>
                        
                        <td class="px-6 py-4 text-xs text-purple-700 font-mono">
                            @if($polygon && is_array($polygon) && count($polygon) > 0)
                                <div class="bg-purple-50 p-2 rounded space-y-1">
                                    @foreach($polygon as $point)
                                        @if(isset($point[0]) && isset($point[1]))
                                            📍 {{ number_format($point[0], 6) }}, {{ number_format($point[1], 6) }}
                                        @endif
                                    @endforeach
                                </div>
                            @elseif($lokasi->center_latitude && $lokasi->center_longitude)
                                📍 {{ number_format($lokasi->center_latitude, 6) }}, {{ number_format($lokasi->center_longitude, 6) }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if($lokasi->polygon_area)
                                <span class="text-xs font-semibold text-blue-700 bg-blue-50 px-2 py-1 rounded">
                                    📐 {{ $lokasi->formatted_area }}
                                </span>
                            @elseif($polygon && is_array($polygon))
                                <span class="text-xs text-amber-700 bg-amber-50 px-2 py-1 rounded">
                                    ⚠️ Error hitung
                                </span>
                            @else
                                <span class="text-xs text-gray-500">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if($lokasi->pdf_count > 0)
                                <span class="text-xs font-semibold text-orange-700 bg-orange-50 px-2 py-1 rounded">
                                    📄 {{ $lokasi->pdf_count }} file
                                </span>
                            @else
                                <span class="text-xs text-gray-500">Tidak ada</span>
                            @endif
                        </td>

                        <td class="px-6 py-4">{!! $lokasi->status_badge !!}</td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('kelompok.calon-lokasi.show', $lokasi) }}" 
                                   class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                                    👁️ Lihat
                                </a>
                                @if($lokasi->status_verifikasi === 'pending')
                                <a href="{{ route('kelompok.calon-lokasi.edit', $lokasi) }}" 
                                   class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-sm font-medium">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('kelompok.calon-lokasi.destroy', $lokasi) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('⚠️ Yakin ingin menghapus lokasi \"{{ $lokasi->nama_kelompok_desa }}\"?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                        🗑️ Hapus
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-gray-500 italic px-2">
                                    {{ $lokasi->status_verifikasi === 'diverifikasi' ? '✅ Terverifikasi' : '❌ Ditolak' }}
                                </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-6xl mb-4">📍</span>
                                <p class="text-gray-500 font-medium text-lg mb-2">Belum ada calon lokasi</p>
                                <p class="text-gray-400 text-sm mb-4">Mulai tambahkan lokasi pertama Anda</p>
                                <a href="{{ route('kelompok.calon-lokasi.create') }}" 
                                   class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                                    ➕ Tambahkan Lokasi Pertama
                                </a>
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

    <!-- Mobile & Tablet Card View (below lg) -->
    <div class="lg:hidden space-y-4">
        @forelse($calonLokasis as $index => $lokasi)
            @php
                $polygon = is_array($lokasi->polygon_coordinates) 
                            ? $lokasi->polygon_coordinates 
                            : json_decode($lokasi->polygon_coordinates, true);
            @endphp
            
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 slide-in">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold text-white bg-white bg-opacity-20 px-2 py-1 rounded">
                                    #{{ $calonLokasis->firstItem() + $index }}
                                </span>
                                {!! $lokasi->status_badge !!}
                            </div>
                            <h4 class="text-base sm:text-lg font-bold text-white">
                                {{ $lokasi->nama_kelompok_desa }}
                            </h4>
                        </div>
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-4 space-y-3">
                    <!-- Location Info -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                <span>🏘️</span>
                                <span>Kecamatan</span>
                            </p>
                            <p class="text-sm font-semibold text-gray-900 break-words">
                                {{ $lokasi->kecamatan }}
                            </p>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                <span>🏛️</span>
                                <span>Kabupaten</span>
                            </p>
                            <p class="text-sm font-semibold text-gray-900 break-words">
                                {{ $lokasi->kabupaten }}
                            </p>
                        </div>
                    </div>

                    <!-- Coordinates -->
                    <div class="bg-purple-50 p-3 rounded-lg border border-purple-200">
                        <p class="text-xs text-gray-600 mb-2 flex items-center gap-1 font-semibold">
                            <span>📍</span>
                            <span>Koordinat</span>
                        </p>
                        @if($polygon && is_array($polygon) && count($polygon) > 0)
                            <div class="space-y-1 max-h-32 overflow-y-auto text-xs">
                                @foreach($polygon as $point)
                                    @if(isset($point[0]) && isset($point[1]))
                                        <div class="text-purple-700 font-mono bg-white p-1.5 rounded">
                                            📍 {{ number_format($point[0], 6) }}, {{ number_format($point[1], 6) }}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @elseif($lokasi->center_latitude && $lokasi->center_longitude)
                            <p class="text-sm text-purple-700 font-mono bg-white p-2 rounded">
                                📍 {{ number_format($lokasi->center_latitude, 6) }}, {{ number_format($lokasi->center_longitude, 6) }}
                            </p>
                        @else
                            <p class="text-sm text-gray-500">-</p>
                        @endif
                    </div>

                    <!-- Area & Documents -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                            <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                <span>📐</span>
                                <span>Luas Area</span>
                            </p>
                            @if($lokasi->polygon_area)
                                <p class="text-sm font-semibold text-blue-700">
                                    {{ $lokasi->formatted_area }}
                                </p>
                            @elseif($polygon && is_array($polygon))
                                <p class="text-xs text-amber-700">⚠️ Error hitung</p>
                            @else
                                <p class="text-sm text-gray-500">-</p>
                            @endif
                        </div>

                        <div class="bg-orange-50 p-3 rounded-lg border border-orange-200">
                            <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                <span>📄</span>
                                <span>Dokumen</span>
                            </p>
                            @if($lokasi->pdf_count > 0)
                                <p class="text-sm font-semibold text-orange-700">
                                    {{ $lokasi->pdf_count }} file
                                </p>
                            @else
                                <p class="text-sm text-gray-500">Tidak ada</p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-2 flex flex-col sm:flex-row gap-2">
                        <a href="{{ route('kelompok.calon-lokasi.show', $lokasi) }}" 
                           class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 text-sm font-semibold">
                            <span>👁️</span>
                            <span>Lihat Detail</span>
                        </a>
                        
                        @if($lokasi->status_verifikasi === 'pending')
                            <a href="{{ route('kelompok.calon-lokasi.edit', $lokasi) }}" 
                               class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition duration-300 text-sm font-semibold">
                                <span>✏️</span>
                                <span>Edit</span>
                            </a>
                            
                            <form action="{{ route('kelompok.calon-lokasi.destroy', $lokasi) }}" 
                                  method="POST" 
                                  class="flex-1"
                                  onsubmit="return confirm('⚠️ Yakin ingin menghapus lokasi \"{{ $lokasi->nama_kelompok_desa }}\"?\n\nData tidak dapat dikembalikan!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-300 text-sm font-semibold">
                                    <span>🗑️</span>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        @else
                            <div class="flex-1 bg-gray-100 px-4 py-2.5 rounded-lg text-center">
                                <span class="text-xs text-gray-600 italic">
                                    {{ $lokasi->status_verifikasi === 'diverifikasi' ? '✅ Terverifikasi' : '❌ Ditolak' }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-lg p-8 sm:p-12 text-center">
                <span class="text-5xl sm:text-6xl mb-4 block">📍</span>
                <p class="text-gray-500 font-medium text-base sm:text-lg mb-2">Belum ada calon lokasi</p>
                <p class="text-gray-400 text-xs sm:text-sm mb-4">Mulai tambahkan lokasi pertama Anda</p>
                <a href="{{ route('kelompok.calon-lokasi.create') }}" 
                   class="inline-block px-4 sm:px-6 py-2.5 sm:py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold text-sm sm:text-base">
                    ➕ Tambahkan Lokasi Pertama
                </a>
            </div>
        @endforelse

        <!-- Mobile Pagination -->
        @if($calonLokasis->hasPages())
        <div class="bg-white rounded-xl shadow-lg p-4">
            {{ $calonLokasis->links() }}
        </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4 rounded-lg">
        <div class="flex items-start">
            <span class="text-xl sm:text-2xl mr-2 sm:mr-3">💡</span>
            <div>
                <p class="text-sm sm:text-base text-blue-800 font-semibold mb-2">Informasi Penting</p>
                <ul class="text-xs sm:text-sm text-blue-700 space-y-1.5">
                    <li>• <strong>Polygon:</strong> Area lokasi yang digambar di peta menggunakan beberapa titik koordinat</li>
                    <li>• <strong>Luas Area:</strong> Dihitung otomatis dari polygon yang digambar (dalam hektar atau m²)</li>
                    <li>• <strong>Dokumen:</strong> Maksimal 5 file PDF (max 5MB/file)</li>
                    <li>• <strong>Status Pending:</strong> Lokasi dapat diedit dan dihapus</li>
                    <li>• <strong>Status Terverifikasi/Ditolak:</strong> Lokasi hanya dapat dilihat</li>
                    <li>• Pastikan koordinat polygon sudah akurat sebelum submit</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- CSS untuk animasi -->
<style>
@keyframes slideIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.slide-in { animation: slideIn 0.5s ease-out; }
</style>
@endsection