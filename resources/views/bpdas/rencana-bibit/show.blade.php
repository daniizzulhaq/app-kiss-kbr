@extends('layouts.dashboard')

@section('title', 'Detail Rencana Bibit - Sistem KBR')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8 slide-in">
        <a href="{{ route('bpdas.rencana-bibit.index') }}" 
           class="inline-flex items-center text-green-600 hover:text-green-700 mb-3 sm:mb-4 font-medium text-sm sm:text-base">
            ← Kembali ke Daftar
        </a>
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-2">🌱 Detail Rencana Bibit</h1>
            <p class="text-sm sm:text-base text-gray-600">Informasi lengkap rencana kebutuhan bibit dari kelompok</p>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden slide-in">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex-1">
                    <h2 class="text-xl sm:text-2xl font-bold text-white mb-1">{{ $rencanaBibit->jenis_bibit }}</h2>
                    <p class="text-sm sm:text-base text-green-100">ID Bibit: #{{ $rencanaBibit->id_bibit }}</p>
                </div>
                <div class="sm:text-right">
                    {!! $rencanaBibit->golongan_badge !!}
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-4 sm:p-6 lg:p-8">
            <!-- Kelompok Info Section -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg sm:rounded-xl p-4 sm:p-6 border-2 border-blue-100 mb-6 sm:mb-8">
                <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4 flex items-center">
                    <span class="text-xl sm:text-2xl mr-2">👥</span>
                    Informasi Kelompok
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Nama Kelompok</p>
                        <p class="text-sm sm:text-base font-semibold text-gray-800 mt-1">{{ $rencanaBibit->kelompok->nama_kelompok ?? '-' }}</p>
                    </div>
                    
                    <!-- Info Pengelola -->
                    @if($rencanaBibit->kelompok && $rencanaBibit->kelompok->user)
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Pengelola</p>
                        <p class="text-sm sm:text-base font-semibold text-gray-800 mt-1">{{ $rencanaBibit->kelompok->user->name }}</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Ketua Kelompok</p>
                        <p class="text-sm sm:text-base font-semibold text-gray-800 mt-1">{{ $rencanaBibit->kelompok->ketua ?? '-' }}</p>
                    </div>

                    @if($rencanaBibit->kelompok && $rencanaBibit->kelompok->anggota)
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Jumlah Anggota</p>
                        <p class="text-sm sm:text-base font-semibold text-gray-800 mt-1">{{ $rencanaBibit->kelompok->anggota }} orang</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Desa</p>
                        <p class="text-sm sm:text-base font-semibold text-gray-800 mt-1">{{ $rencanaBibit->kelompok->desa ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Kecamatan</p>
                        <p class="text-sm sm:text-base font-semibold text-gray-800 mt-1">{{ $rencanaBibit->kelompok->kecamatan ?? '-' }}</p>
                    </div>

                    @if($rencanaBibit->kelompok && $rencanaBibit->kelompok->kabupaten)
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Kabupaten</p>
                        <p class="text-sm sm:text-base font-semibold text-gray-800 mt-1">{{ $rencanaBibit->kelompok->kabupaten }}</p>
                    </div>
                    @endif

                    @if($rencanaBibit->kelompok && ($rencanaBibit->kelompok->kontak || $rencanaBibit->kelompok->no_hp))
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Kontak</p>
                        <p class="text-sm sm:text-base font-semibold text-gray-800 mt-1">{{ $rencanaBibit->kelompok->kontak ?? $rencanaBibit->kelompok->no_hp }}</p>
                    </div>
                    @endif

                    @if($rencanaBibit->kelompok && $rencanaBibit->kelompok->koordinat)
                    <div class="sm:col-span-2">
                        <p class="text-xs sm:text-sm text-gray-600">📍 Koordinat Lokasi</p>
                        <p class="text-sm sm:text-base font-semibold text-gray-800 font-mono bg-white px-3 py-2 rounded-lg mt-1 break-all">
                            {{ $rencanaBibit->kelompok->koordinat }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Golongan -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl mr-3 sm:mr-4 flex-shrink-0">
                            🏷️
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Golongan Bibit</p>
                            <p class="text-base sm:text-lg font-semibold text-gray-800 truncate">{{ $rencanaBibit->golongan }}</p>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Batang -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl mr-3 sm:mr-4 flex-shrink-0">
                            🌳
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Jumlah Batang</p>
                            <p class="text-base sm:text-lg font-semibold text-gray-800">{{ $rencanaBibit->jumlah_format }} Batang</p>
                        </div>
                    </div>
                </div>

                <!-- Tinggi -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl mr-3 sm:mr-4 flex-shrink-0">
                            📏
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Tinggi Bibit</p>
                            <p class="text-base sm:text-lg font-semibold text-gray-800">{{ $rencanaBibit->tinggi_format }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sertifikat -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl mr-3 sm:mr-4 flex-shrink-0">
                            📜
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Status Sertifikat</p>
                            {!! $rencanaBibit->sertifikat_badge !!}
                            @if($rencanaBibit->sertifikat)
                            <p class="text-xs text-gray-700 mt-1">{{ $rencanaBibit->sertifikat }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumentasi Foto (jika ada) -->
            @if($rencanaBibit->kelompok && $rencanaBibit->kelompok->dokumentasi && count($rencanaBibit->kelompok->dokumentasi) > 0)
            <div class="bg-purple-50 rounded-lg sm:rounded-xl p-4 sm:p-6 border-2 border-purple-100 mb-6 sm:mb-8">
                <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4 flex items-center">
                    <span class="text-xl sm:text-2xl mr-2">📸</span>
                    Dokumentasi Kelompok
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3">
                    @foreach($rencanaBibit->kelompok->dokumentasi as $index => $foto)
                    <div class="relative group cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $foto) }}', {{ $index + 1 }})">
                        <img src="{{ asset('storage/' . $foto) }}" 
                             alt="Dokumentasi {{ $index + 1 }}"
                             class="w-full h-24 sm:h-32 object-cover rounded-lg border-2 border-purple-200 hover:border-purple-400 transition-all duration-300 hover:shadow-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                            <span class="text-white opacity-0 group-hover:opacity-100 text-xs font-semibold">🔍 Lihat</span>
                        </div>
                        <div class="absolute top-1 sm:top-2 right-1 sm:right-2 bg-purple-600 text-white text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 rounded">
                            Foto {{ $index + 1 }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Timestamp -->
            <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm text-gray-600">
                    <div>
                        <span class="font-semibold">Dibuat:</span> 
                        <span class="block sm:inline mt-1 sm:mt-0">{{ $rencanaBibit->created_at->format('d F Y, H:i') }} WIB</span>
                    </div>
                    <div class="sm:text-right">
                        <span class="font-semibold">Terakhir diupdate:</span> 
                        <span class="block sm:inline mt-1 sm:mt-0">{{ $rencanaBibit->updated_at->format('d F Y, H:i') }} WIB</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-gray-50 px-4 sm:px-6 lg:px-8 py-3 sm:py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <a href="{{ route('bpdas.rencana-bibit.index') }}" 
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
                    @if($rencanaBibit->kelompok && $rencanaBibit->kelompok->koordinat)
                    @php
                        $coords = explode(',', $rencanaBibit->kelompok->koordinat);
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
        button, a[href*="maps"], .no-print {
            display: none !important;
        }
        body {
            background: white;
        }
        .bg-gradient-to-r,
        .bg-gradient-to-br {
            background: #10b981 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    /* Mobile modal improvements */
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