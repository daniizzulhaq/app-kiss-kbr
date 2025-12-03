@extends('layouts.dashboard')

@section('title', 'Detail Progress Fisik - Sistem KBR')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Detail Progress Fisik</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Informasi lengkap kegiatan dan dokumentasi</p>
            </div>
            <div class="flex gap-2">
                @if($progressFisik->status_verifikasi != 'disetujui')
                    <a href="{{ route('kelompok.progress-fisik.edit', $progressFisik->id) }}" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 sm:px-6 py-2.5 rounded-lg flex items-center gap-2 transition-all shadow-lg text-sm sm:text-base">
                        <span class="text-lg">✏️</span>
                        <span class="font-medium">Edit</span>
                    </a>
                @endif
                <a href="{{ route('kelompok.progress-fisik.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 sm:px-6 py-2.5 rounded-lg flex items-center gap-2 transition-all shadow-lg text-sm sm:text-base">
                    <span class="text-lg">←</span>
                    <span class="font-medium">Kembali</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-xl sm:text-2xl mr-2 sm:mr-3">✅</span>
            <p class="font-medium text-sm sm:text-base">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Info Kegiatan -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-4 sm:p-6 mb-4 sm:mb-6 rounded-lg shadow-md">
        <div class="flex items-start gap-3 sm:gap-4">
            <span class="text-3xl sm:text-4xl">📋</span>
            <div class="flex-1">
                <h3 class="text-lg sm:text-xl font-bold text-blue-900 mb-2">{{ $progressFisik->masterKegiatan->nama_kegiatan }}</h3>
                <div class="flex flex-wrap gap-2 sm:gap-3">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        📂 {{ $progressFisik->masterKegiatan->kategori->nama }}
                    </span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        📏 Satuan: {{ $progressFisik->masterKegiatan->satuan }}
                    </span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                        🔢 Kode: {{ $progressFisik->masterKegiatan->nomor }}
                    </span>
                </div>
                @if($progressFisik->keterangan)
                    <div class="mt-3 p-3 bg-white rounded-lg">
                        <p class="text-xs sm:text-sm text-gray-700">
                            <strong>Keterangan:</strong> {{ $progressFisik->keterangan }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Status Verifikasi -->
    <div class="mb-4 sm:mb-6 p-4 sm:p-6 rounded-lg shadow-md border-l-4
                {{ $progressFisik->status_verifikasi == 'disetujui' ? 'bg-green-50 border-green-500' : 
                   ($progressFisik->status_verifikasi == 'ditolak' ? 'bg-red-50 border-red-500' : 'bg-yellow-50 border-yellow-500') }}">
        <div class="flex items-start gap-3 sm:gap-4">
            <span class="text-3xl sm:text-4xl">
                {{ $progressFisik->status_verifikasi == 'disetujui' ? '✅' : 
                   ($progressFisik->status_verifikasi == 'ditolak' ? '❌' : '⏳') }}
            </span>
            <div class="flex-1">
                <p class="text-base sm:text-lg font-bold 
                          {{ $progressFisik->status_verifikasi == 'disetujui' ? 'text-green-900' : 
                             ($progressFisik->status_verifikasi == 'ditolak' ? 'text-red-900' : 'text-yellow-900') }}">
                    Status: 
                    @if($progressFisik->status_verifikasi == 'pending')
                        Menunggu Verifikasi
                    @elseif($progressFisik->status_verifikasi == 'disetujui')
                        Disetujui
                    @else
                        Ditolak
                    @endif
                </p>
                @if($progressFisik->catatan_verifikasi)
                    <p class="text-xs sm:text-sm mt-2 
                              {{ $progressFisik->status_verifikasi == 'disetujui' ? 'text-green-700' : 
                                 ($progressFisik->status_verifikasi == 'ditolak' ? 'text-red-700' : 'text-yellow-700') }}">
                        <strong>Catatan:</strong> {{ $progressFisik->catatan_verifikasi }}
                    </p>
                @endif
                @if($progressFisik->verified_at)
                    <p class="text-xs mt-1 
                              {{ $progressFisik->status_verifikasi == 'disetujui' ? 'text-green-600' : 
                                 ($progressFisik->status_verifikasi == 'ditolak' ? 'text-red-600' : 'text-yellow-600') }}">
                        Diverifikasi oleh {{ $progressFisik->verifier->name }} pada {{ $progressFisik->verified_at->format('d M Y H:i') }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Volume & Progress -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>📊</span>
                Volume & Progress
            </h3>

            <div class="space-y-4">
                <!-- Volume Target -->
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-xs sm:text-sm font-medium text-gray-700">Volume Target</span>
                    <span class="text-base sm:text-lg font-bold text-gray-900">
                        {{ rtrim(rtrim(number_format($progressFisik->volume_target, 2), '0'), '.') }} {{ $progressFisik->masterKegiatan->satuan }}
                    </span>
                </div>

                <!-- Volume Realisasi -->
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <span class="text-xs sm:text-sm font-medium text-gray-700">Volume Realisasi</span>
                    <span class="text-base sm:text-lg font-bold text-green-600">
                        {{ rtrim(rtrim(number_format($progressFisik->volume_realisasi, 2), '0'), '.') }} {{ $progressFisik->masterKegiatan->satuan }}
                    </span>
                </div>

                <!-- Progress Bar -->
                <div class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-sm font-bold text-gray-700">Persentase Progress</span>
                        <span class="text-2xl font-bold text-indigo-600">{{ number_format($progressFisik->persentase_fisik, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-6 shadow-inner">
                        <div class="h-6 rounded-full flex items-center justify-center text-white text-xs font-bold transition-all duration-300
                                    {{ $progressFisik->persentase_fisik >= 100 ? 'bg-gradient-to-r from-green-400 to-green-600' : 
                                       ($progressFisik->persentase_fisik >= 50 ? 'bg-gradient-to-r from-blue-400 to-blue-600' : 'bg-gradient-to-r from-yellow-400 to-yellow-600') }}" 
                             style="width: {{ min($progressFisik->persentase_fisik, 100) }}%">
                            {{ number_format($progressFisik->persentase_fisik, 0) }}%
                        </div>
                    </div>
                </div>

                <!-- Status Selesai -->
                @if($progressFisik->is_selesai)
                    <div class="p-3 bg-green-100 border border-green-400 rounded-lg text-center">
                        <span class="text-2xl">🎉</span>
                        <p class="text-sm font-bold text-green-800 mt-1">Kegiatan Selesai</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Anggaran & Biaya -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>💰</span>
                Anggaran & Biaya
            </h3>

            <div class="space-y-4">
                <!-- Biaya Satuan -->
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-xs sm:text-sm font-medium text-gray-700">Biaya per Satuan</span>
                    <span class="text-base sm:text-lg font-bold text-gray-900">
                        Rp {{ number_format($progressFisik->biaya_satuan, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Total Biaya (Dialokasikan) -->
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <span class="text-xs sm:text-sm font-medium text-gray-700">Total Biaya (Target)</span>
                    <span class="text-base sm:text-lg font-bold text-blue-600">
                        Rp {{ number_format($progressFisik->total_biaya, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Biaya Realisasi -->
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <span class="text-xs sm:text-sm font-medium text-gray-700">Biaya Realisasi</span>
                    <span class="text-base sm:text-lg font-bold text-green-600">
                        Rp {{ number_format($progressFisik->biaya_realisasi, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Sisa Biaya -->
                @php
                    $sisaBiaya = $progressFisik->total_biaya - $progressFisik->biaya_realisasi;
                @endphp
                <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                    <span class="text-xs sm:text-sm font-medium text-gray-700">Sisa Biaya</span>
                    <span class="text-base sm:text-lg font-bold text-purple-600">
                        Rp {{ number_format($sisaBiaya, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Pelaksanaan -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 mt-4 sm:mt-6">
        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span>📅</span>
            Jadwal Pelaksanaan
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-xs font-medium text-gray-600 mb-1">Tanggal Mulai</p>
                <p class="text-base sm:text-lg font-bold text-blue-900">
                    {{ $progressFisik->tanggal_mulai ? $progressFisik->tanggal_mulai->format('d M Y') : 'Belum ditentukan' }}
                </p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-xs font-medium text-gray-600 mb-1">Tanggal Selesai</p>
                <p class="text-base sm:text-lg font-bold text-green-900">
                    {{ $progressFisik->tanggal_selesai ? $progressFisik->tanggal_selesai->format('d M Y') : 'Belum selesai' }}
                </p>
            </div>
        </div>

        @if($progressFisik->tanggal_mulai && $progressFisik->tanggal_selesai)
            @php
                $durasi = $progressFisik->tanggal_mulai->diffInDays($progressFisik->tanggal_selesai);
            @endphp
            <div class="mt-4 p-3 bg-indigo-50 rounded-lg text-center">
                <p class="text-sm text-gray-700">
                    <strong>Durasi Pelaksanaan:</strong> {{ $durasi }} hari
                </p>
            </div>
        @endif
    </div>

    <!-- Dokumentasi Foto & PDF -->
    @php
        $fotos = $progressFisik->dokumentasi->filter(function($dok) {
            return !str_contains($dok->foto, '/pdf/');
        });
        
        $pdfs = $progressFisik->dokumentasi->filter(function($dok) {
            return str_contains($dok->foto, '/pdf/');
        });
    @endphp

    @if($progressFisik->dokumentasi->count() > 0)
        <!-- Section Dokumentasi Foto -->
        @if($fotos->count() > 0)
        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 mt-4 sm:mt-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>📸</span>
                Dokumentasi Foto
                <span class="text-sm font-normal text-gray-500">({{ $fotos->count() }} foto)</span>
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($fotos as $dok)
                    <div class="group relative bg-white border-2 border-gray-200 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all cursor-pointer"
                         onclick="openImageModal('{{ Storage::url($dok->foto) }}', '{{ $dok->keterangan }}', '{{ $dok->tanggal_foto->format('d M Y') }}')">
                        <img src="{{ Storage::url($dok->foto) }}" 
                             alt="Dokumentasi" 
                             class="w-full h-40 sm:h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end">
                            <div class="p-3 w-full">
                                <p class="text-white text-xs font-semibold line-clamp-2">
                                    {{ $dok->keterangan ?? 'Dokumentasi Kegiatan' }}
                                </p>
                                <p class="text-gray-300 text-xs mt-1">
                                    📅 {{ $dok->tanggal_foto->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="absolute top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">
                            🔍 Lihat
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Section Dokumen PDF -->
        @if($pdfs->count() > 0)
        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 mt-4 sm:mt-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>📄</span>
                Dokumen PDF BA HASIL PENAWASAN 
                <span class="text-sm font-normal text-gray-500">({{ $pdfs->count() }} dokumen)</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                @foreach($pdfs as $dok)
                    <div class="group bg-gradient-to-br from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-lg p-4 hover:shadow-xl transition-all">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center text-white text-2xl group-hover:scale-110 transition-transform">
                                    📄
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 truncate mb-1">
                                    {{ $dok->keterangan ?? 'Dokumen PDF' }}
                                </h4>
                                <p class="text-xs text-gray-600 mb-2">
                                    📅 {{ $dok->tanggal_foto->format('d M Y') }}
                                </p>
                                <div class="flex gap-2">
                                    <!-- Tombol View -->
                                    <a href="{{ Storage::url($dok->foto) }}" 
                                       target="_blank"
                                       class="flex-1 px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition-all flex items-center justify-center gap-1">
                                        <span>👁️</span>
                                        <span>Lihat</span>
                                    </a>
                                    <!-- Tombol Download -->
                                    <a href="{{ Storage::url($dok->foto) }}" 
                                       download
                                       class="flex-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-lg transition-all flex items-center justify-center gap-1">
                                        <span>⬇️</span>
                                        <span>Download</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Info File Size (opsional) -->
                        @php
                            $filePath = storage_path('app/public/' . $dok->foto);
                            $fileSize = file_exists($filePath) ? filesize($filePath) : 0;
                            $fileSizeMB = number_format($fileSize / 1024 / 1024, 2);
                        @endphp
                        @if($fileSize > 0)
                            <div class="mt-3 pt-3 border-t border-purple-200">
                                <p class="text-xs text-gray-600 flex items-center gap-1">
                                    <span>💾</span>
                                    <span>Ukuran: {{ $fileSizeMB }} MB</span>
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    @else
        <!-- Jika belum ada dokumentasi sama sekali -->
        <div class="bg-gray-50 rounded-lg p-8 mt-4 sm:mt-6 text-center border-2 border-dashed border-gray-300">
            <span class="text-5xl mb-3 block">📁</span>
            <p class="text-gray-500 font-medium">Belum ada dokumentasi</p>
            <p class="text-sm text-gray-400 mt-1">Upload foto atau dokumen PDF saat mengedit progress</p>
        </div>
    @endif

    <!-- Info Tambahan -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4 rounded-lg mt-4 sm:mt-6">
        <div class="flex items-start gap-2">
            <span class="text-lg sm:text-xl">ℹ️</span>
            <div class="text-xs sm:text-sm text-blue-800">
                <p class="font-semibold mb-1">Catatan:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Progress fisik dihitung berdasarkan: (Volume Realisasi / Volume Target) × 100%</li>
                    <li>Biaya realisasi dihitung berdasarkan: Volume Realisasi × Biaya per Satuan</li>
                    <li>Kegiatan dengan status "Disetujui" tidak dapat diedit atau dihapus</li>
                    <li>Klik foto dokumentasi untuk melihat dalam ukuran penuh</li>
                    <li>Dokumen PDF dapat dilihat langsung di browser atau didownload</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Preview Foto Full Size -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-[90vh] w-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-3xl font-bold">
            ✕
        </button>
        <img id="modalImage" src="" alt="Preview" class="w-full h-auto max-h-[80vh] object-contain rounded-lg shadow-2xl">
        <div class="bg-white p-4 rounded-b-lg">
            <p id="modalKeterangan" class="text-sm font-semibold text-gray-900"></p>
            <p id="modalTanggal" class="text-xs text-gray-600 mt-1"></p>
        </div>
    </div>
</div>

<script>
function openImageModal(imageUrl, keterangan, tanggal) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('modalKeterangan').textContent = keterangan || 'Dokumentasi Kegiatan';
    document.getElementById('modalTanggal').textContent = '📅 ' + tanggal;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal dengan ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endsection