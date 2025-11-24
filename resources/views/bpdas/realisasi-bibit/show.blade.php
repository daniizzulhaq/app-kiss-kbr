@extends('layouts.dashboard')

@section('title', 'Detail Realisasi Bibit - Sistem KBR')
@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Detail Realisasi Bibit</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Informasi lengkap realisasi bibit</p>
        </div>
        <a href="{{ route('bpdas.realisasi-bibit.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-xl text-sm sm:text-base">
            <span class="text-lg sm:text-xl">←</span>
            <span class="font-medium">Kembali</span>
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
        <!-- Header Card -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h2 class="text-xl sm:text-2xl font-bold text-white flex items-center gap-2 sm:gap-3">
                <span class="text-2xl sm:text-3xl">🌱</span>
                <span class="break-words">{{ $realisasiBibit->jenis_bibit }}</span>
            </h2>
            <p class="text-sm sm:text-base text-green-100 mt-1">ID Bibit: #{{ $realisasiBibit->id_bibit }}</p>
        </div>

        <!-- Content -->
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                
                <!-- Informasi Kelompok -->
                <div class="space-y-4 sm:space-y-6">
                    <div class="border-l-4 border-green-600 pl-3 sm:pl-4">
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                            <span class="text-xl sm:text-2xl">👥</span>
                            Informasi Kelompok
                        </h3>
                        
                        <div class="space-y-3 sm:space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-start">
                                <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Nama Kelompok</div>
                                <div class="flex-1">
                                    <span class="text-sm sm:text-base text-gray-900 font-semibold">
                                        {{ $realisasiBibit->kelompok->nama_kelompok ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Info Pengelola -->
                            @if($realisasiBibit->kelompok && $realisasiBibit->kelompok->user)
                            <div class="flex flex-col sm:flex-row sm:items-start">
                                <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Pengelola</div>
                                <div class="flex-1">
                                    <span class="text-sm sm:text-base text-gray-900 font-semibold">
                                        {{ $realisasiBibit->kelompok->user->name }}
                                    </span>
                                </div>
                            </div>
                            @endif

                            @if($realisasiBibit->kelompok)
                                @if($realisasiBibit->kelompok->blok)
                                <div class="flex flex-col sm:flex-row sm:items-start">
                                    <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Blok</div>
                                    <div class="flex-1 text-sm sm:text-base text-gray-900">
                                        {{ $realisasiBibit->kelompok->blok }}
                                    </div>
                                </div>
                                @endif

                                <div class="flex flex-col sm:flex-row sm:items-start">
                                    <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Desa</div>
                                    <div class="flex-1 text-sm sm:text-base text-gray-900">
                                        {{ $realisasiBibit->kelompok->desa ?? '-' }}
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-start">
                                    <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Kecamatan</div>
                                    <div class="flex-1 text-sm sm:text-base text-gray-900">
                                        {{ $realisasiBibit->kelompok->kecamatan ?? '-' }}
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-start">
                                    <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Kabupaten</div>
                                    <div class="flex-1 text-sm sm:text-base text-gray-900">
                                        {{ $realisasiBibit->kelompok->kabupaten ?? '-' }}
                                    </div>
                                </div>

                                @if($realisasiBibit->kelompok->koordinat)
                                <div class="flex flex-col sm:flex-row sm:items-start">
                                    <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">📍 Koordinat</div>
                                    <div class="flex-1 text-sm sm:text-base text-gray-900 font-mono bg-gray-50 px-2 sm:px-3 py-1.5 sm:py-2 rounded break-all">
                                        {{ $realisasiBibit->kelompok->koordinat }}
                                    </div>
                                </div>
                                @endif

                                @if($realisasiBibit->kelompok->ketua)
                                <div class="flex flex-col sm:flex-row sm:items-start">
                                    <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Ketua Kelompok</div>
                                    <div class="flex-1 text-sm sm:text-base text-gray-900">
                                        {{ $realisasiBibit->kelompok->ketua }}
                                    </div>
                                </div>
                                @endif

                                @if($realisasiBibit->kelompok->anggota)
                                <div class="flex flex-col sm:flex-row sm:items-start">
                                    <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Jumlah Anggota</div>
                                    <div class="flex-1 text-sm sm:text-base text-gray-900">
                                        {{ $realisasiBibit->kelompok->anggota }} orang
                                    </div>
                                </div>
                                @endif

                                @if($realisasiBibit->kelompok->kontak || $realisasiBibit->kelompok->no_hp)
                                <div class="flex flex-col sm:flex-row sm:items-start">
                                    <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Kontak</div>
                                    <div class="flex-1 text-sm sm:text-base text-gray-900">
                                        {{ $realisasiBibit->kelompok->kontak ?? $realisasiBibit->kelompok->no_hp }}
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informasi Bibit -->
                <div class="space-y-4 sm:space-y-6">
                    <div class="border-l-4 border-blue-600 pl-3 sm:pl-4">
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                            <span class="text-xl sm:text-2xl">🌿</span>
                            Informasi Bibit
                        </h3>
                        
                        <div class="space-y-3 sm:space-y-4">
                            <div class="flex flex-col sm:flex-row sm:items-start">
                                <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Jenis Bibit</div>
                                <div class="flex-1">
                                    <span class="text-green-700 font-bold text-base sm:text-lg">
                                        {{ $realisasiBibit->jenis_bibit }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-start">
                                <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Golongan</div>
                                <div class="flex-1">
                                    <span class="px-3 sm:px-4 py-1 sm:py-1.5 text-xs sm:text-sm font-semibold rounded-full bg-blue-100 text-blue-800 inline-block">
                                        {{ $realisasiBibit->golongan ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-start">
                                <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Jumlah</div>
                                <div class="flex-1">
                                    <span class="text-green-600 font-bold text-xl sm:text-2xl">
                                        {{ number_format($realisasiBibit->jumlah_btg) }}
                                    </span>
                                    <span class="text-gray-600 text-xs sm:text-sm ml-2">Batang</span>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-start">
                                <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Tinggi</div>
                                <div class="flex-1">
                                    @if($realisasiBibit->tinggi)
                                        <span class="text-gray-900 font-semibold text-base sm:text-lg">
                                            {{ number_format($realisasiBibit->tinggi, 2) }}
                                        </span>
                                        <span class="text-gray-600 text-xs sm:text-sm ml-1">cm</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </div>

                            @if(isset($realisasiBibit->sertifikat))
                            <div class="flex flex-col sm:flex-row sm:items-start">
                                <div class="sm:w-40 text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-0">Sertifikat</div>
                                <div class="flex-1">
                                    @if($realisasiBibit->sertifikat)
                                        <span class="px-3 py-1 text-xs sm:text-sm font-semibold rounded-full bg-green-100 text-green-800 inline-block">
                                            ✓ Bersertifikat
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs sm:text-sm font-semibold rounded-full bg-gray-100 text-gray-800 inline-block">
                                            Non-Sertifikat
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Dokumentasi Foto (jika ada) -->
                    @if($realisasiBibit->kelompok && $realisasiBibit->kelompok->dokumentasi && count($realisasiBibit->kelompok->dokumentasi) > 0)
                    <div class="border-l-4 border-purple-600 pl-3 sm:pl-4">
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                            <span class="text-xl sm:text-2xl">📸</span>
                            Dokumentasi Kelompok
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3">
                            @foreach($realisasiBibit->kelompok->dokumentasi as $index => $foto)
                            <div class="relative group cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $foto) }}', {{ $index + 1 }})">
                                <img src="{{ asset('storage/' . $foto) }}" 
                                     alt="Dokumentasi {{ $index + 1 }}"
                                     class="w-full h-20 sm:h-24 object-cover rounded-lg border-2 border-gray-200 hover:border-purple-500 transition-all duration-300 hover:shadow-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                                    <span class="text-white opacity-0 group-hover:opacity-100 text-xs font-semibold">🔍 Lihat</span>
                                </div>
                                <div class="absolute top-1 right-1 bg-purple-600 text-white text-xs px-1.5 py-0.5 rounded">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

            </div>

            <!-- Informasi Waktu -->
            <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div class="flex items-start gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                        <span class="text-lg sm:text-xl flex-shrink-0">📅</span>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium">Tanggal Input</div>
                            <div class="text-gray-900 break-words">{{ $realisasiBibit->created_at->format('d F Y, H:i') }} WIB</div>
                        </div>
                    </div>
                    
                    @if($realisasiBibit->updated_at != $realisasiBibit->created_at)
                    <div class="flex items-start gap-2 sm:gap-3 text-xs sm:text-sm text-gray-600">
                        <span class="text-lg sm:text-xl flex-shrink-0">🔄</span>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium">Terakhir Diupdate</div>
                            <div class="text-gray-900 break-words">{{ $realisasiBibit->updated_at->format('d F Y, H:i') }} WIB</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer Card dengan Action Buttons -->
        <div class="bg-gray-50 px-4 sm:px-6 lg:px-8 py-3 sm:py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <a href="{{ route('bpdas.realisasi-bibit.index') }}" 
                   class="text-gray-600 hover:text-gray-800 font-medium flex items-center justify-center sm:justify-start gap-2 text-sm sm:text-base order-2 sm:order-1">
                    <span>←</span>
                    Kembali ke Daftar
                </a>
                
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 order-1 sm:order-2">
                    <!-- Tombol Print -->
                    <button onclick="window.print()" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 sm:py-2.5 rounded-lg font-medium transition-all flex items-center justify-center gap-2 text-sm sm:text-base">
                        <span>🖨️</span>
                        Print
                    </button>

                    <!-- Tombol Lihat di Maps (jika ada koordinat) -->
                    @if($realisasiBibit->kelompok && $realisasiBibit->kelompok->koordinat)
                    @php
                        $coords = explode(',', $realisasiBibit->kelompok->koordinat);
                        $lat = isset($coords[0]) ? trim($coords[0]) : null;
                        $lng = isset($coords[1]) ? trim($coords[1]) : null;
                    @endphp
                    @if($lat && $lng)
                    <a href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}" 
                       target="_blank"
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 sm:py-2.5 rounded-lg font-medium transition-all flex items-center justify-center gap-2 text-sm sm:text-base">
                        <span>🗺️</span>
                        Lihat Lokasi
                    </a>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Card Statistik Tambahan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mt-4 sm:mt-6">
        <!-- Card 1 -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-green-100 text-xs sm:text-sm font-medium">Total Bibit</p>
                    <p class="text-2xl sm:text-3xl font-bold mt-1 break-words">{{ number_format($realisasiBibit->jumlah_btg) }}</p>
                    <p class="text-green-100 text-xs mt-1">Batang</p>
                </div>
                <div class="text-3xl sm:text-5xl opacity-50 flex-shrink-0 ml-2">🌱</div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-blue-100 text-xs sm:text-sm font-medium">Tinggi Rata-rata</p>
                    <p class="text-2xl sm:text-3xl font-bold mt-1 break-words">
                        {{ $realisasiBibit->tinggi ? number_format($realisasiBibit->tinggi, 2) : '-' }}
                    </p>
                    <p class="text-blue-100 text-xs mt-1">Centimeter</p>
                </div>
                <div class="text-3xl sm:text-5xl opacity-50 flex-shrink-0 ml-2">📏</div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-purple-100 text-xs sm:text-sm font-medium">Golongan</p>
                    <p class="text-xl sm:text-2xl font-bold mt-1 break-words">{{ $realisasiBibit->golongan ?? '-' }}</p>
                    <p class="text-purple-100 text-xs mt-1">Kategori Tumbuh</p>
                </div>
                <div class="text-3xl sm:text-5xl opacity-50 flex-shrink-0 ml-2">📊</div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl w-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" class="absolute -top-8 sm:-top-10 right-0 text-white hover:text-gray-300 text-xl sm:text-2xl font-bold w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center">
            ✕
        </button>
        <img id="modalImage" src="" alt="Preview" class="w-full h-auto rounded-lg shadow-2xl max-h-[70vh] sm:max-h-[80vh] object-contain">
        <p id="modalCaption" class="text-white text-center mt-3 sm:mt-4 font-semibold text-sm sm:text-base"></p>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        .no-print, button, a[href*="maps"] {
            display: none !important;
        }
        body {
            background: white;
        }
        .container {
            max-width: 100%;
        }
        .bg-gradient-to-r,
        .bg-gradient-to-br {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    /* Modal improvements */
    #imageModal {
        display: none;
    }
    #imageModal:not(.hidden) {
        display: flex;
    }
</style>

<script>
function openImageModal(src, index) {
    const modal = document.getElementById('imageModal');
    modal.classList.remove('hidden');
    document.getElementById('modalImage').src = src;
    document.getElementById('modalCaption').textContent = `Dokumentasi Foto ${index}`;
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Prevent scrolling on touch devices when modal is open
document.getElementById('imageModal').addEventListener('touchmove', function(e) {
    if (e.target === this) {
        e.preventDefault();
    }
}, { passive: false });
</script>
@endsection