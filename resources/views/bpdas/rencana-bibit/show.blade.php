@extends('layouts.dashboard')

@section('title', 'Detail Rencana Bibit - Sistem KBR')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 slide-in">
        <a href="{{ route('bpdas.rencana-bibit.index') }}" 
           class="inline-flex items-center text-green-600 hover:text-green-700 mb-4 font-medium">
            ← Kembali ke Daftar
        </a>
        <div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">🌱 Detail Rencana Bibit</h1>
            <p class="text-gray-600">Informasi lengkap rencana kebutuhan bibit dari kelompok</p>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden slide-in">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-1">{{ $rencanaBibit->jenis_bibit }}</h2>
                    <p class="text-green-100">ID Bibit: #{{ $rencanaBibit->id_bibit }}</p>
                </div>
                <div class="text-right">
                    {!! $rencanaBibit->golongan_badge !!}
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-8">
            <!-- Kelompok Info Section -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-100 mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="text-2xl mr-2">👥</span>
                    Informasi Kelompok
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Kelompok</p>
                        <p class="text-base font-semibold text-gray-800">{{ $rencanaBibit->kelompok->nama_kelompok ?? '-' }}</p>
                    </div>
                    
                    <!-- Info Pengelola -->
                    @if($rencanaBibit->kelompok && $rencanaBibit->kelompok->user)
                    <div>
                        <p class="text-sm text-gray-600">Pengelola</p>
                        <p class="text-base font-semibold text-gray-800">{{ $rencanaBibit->kelompok->user->name }}</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600">Ketua Kelompok</p>
                        <p class="text-base font-semibold text-gray-800">{{ $rencanaBibit->kelompok->ketua ?? '-' }}</p>
                    </div>

                    @if($rencanaBibit->kelompok && $rencanaBibit->kelompok->anggota)
                    <div>
                        <p class="text-sm text-gray-600">Jumlah Anggota</p>
                        <p class="text-base font-semibold text-gray-800">{{ $rencanaBibit->kelompok->anggota }} orang</p>
                    </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600">Desa</p>
                        <p class="text-base font-semibold text-gray-800">{{ $rencanaBibit->kelompok->desa ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kecamatan</p>
                        <p class="text-base font-semibold text-gray-800">{{ $rencanaBibit->kelompok->kecamatan ?? '-' }}</p>
                    </div>

                    @if($rencanaBibit->kelompok && $rencanaBibit->kelompok->kabupaten)
                    <div>
                        <p class="text-sm text-gray-600">Kabupaten</p>
                        <p class="text-base font-semibold text-gray-800">{{ $rencanaBibit->kelompok->kabupaten }}</p>
                    </div>
                    @endif

                    @if($rencanaBibit->kelompok && ($rencanaBibit->kelompok->kontak || $rencanaBibit->kelompok->no_hp))
                    <div>
                        <p class="text-sm text-gray-600">Kontak</p>
                        <p class="text-base font-semibold text-gray-800">{{ $rencanaBibit->kelompok->kontak ?? $rencanaBibit->kelompok->no_hp }}</p>
                    </div>
                    @endif

                    @if($rencanaBibit->kelompok && $rencanaBibit->kelompok->koordinat)
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">📍 Koordinat Lokasi</p>
                        <p class="text-base font-semibold text-gray-800 font-mono bg-white px-3 py-2 rounded-lg mt-1">
                            {{ $rencanaBibit->kelompok->koordinat }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Golongan -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-2xl mr-4">
                            🏷️
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Golongan Bibit</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $rencanaBibit->golongan }}</p>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Batang -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center text-2xl mr-4">
                            🌳
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Jumlah Batang</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $rencanaBibit->jumlah_format }} Batang</p>
                        </div>
                    </div>
                </div>

                <!-- Tinggi -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-2xl mr-4">
                            📏
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tinggi Bibit</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $rencanaBibit->tinggi_format }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sertifikat -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-2xl mr-4">
                            📜
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Status Sertifikat</p>
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
            <div class="bg-purple-50 rounded-xl p-6 border-2 border-purple-100 mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="text-2xl mr-2">📸</span>
                    Dokumentasi Kelompok
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($rencanaBibit->kelompok->dokumentasi as $index => $foto)
                    <div class="relative group cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $foto) }}', {{ $index + 1 }})">
                        <img src="{{ asset('storage/' . $foto) }}" 
                             alt="Dokumentasi {{ $index + 1 }}"
                             class="w-full h-32 object-cover rounded-lg border-2 border-purple-200 hover:border-purple-400 transition-all duration-300 hover:shadow-lg">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                            <span class="text-white opacity-0 group-hover:opacity-100 text-xs font-semibold">🔍 Lihat</span>
                        </div>
                        <div class="absolute top-2 right-2 bg-purple-600 text-white text-xs px-2 py-1 rounded">
                            Foto {{ $index + 1 }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Timestamp -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-semibold">Dibuat:</span> 
                        {{ $rencanaBibit->created_at->format('d F Y, H:i') }} WIB
                    </div>
                    <div class="md:text-right">
                        <span class="font-semibold">Terakhir diupdate:</span> 
                        {{ $rencanaBibit->updated_at->format('d F Y, H:i') }} WIB
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex justify-between items-center">
            <a href="{{ route('bpdas.rencana-bibit.index') }}" 
               class="text-gray-600 hover:text-gray-800 font-medium flex items-center gap-2">
                <span>←</span>
                Kembali ke Daftar
            </a>
            
            <div class="flex gap-3">
                <!-- Tombol Print -->
                <button onclick="window.print()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-all flex items-center gap-2">
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
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-all flex items-center gap-2">
                    <span>🗺️</span>
                    Lihat Lokasi
                </a>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl w-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl font-bold">
            ✕
        </button>
        <img id="modalImage" src="" alt="Preview" class="w-full h-auto rounded-lg shadow-2xl">
        <p id="modalCaption" class="text-white text-center mt-4 font-semibold"></p>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        button, a[href*="maps"] {
            display: none !important;
        }
        body {
            background: white;
        }
    }
</style>

<script>
function openImageModal(src, index) {
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('modalImage').src = src;
    document.getElementById('modalCaption').textContent = `Dokumentasi Foto ${index}`;
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endsection