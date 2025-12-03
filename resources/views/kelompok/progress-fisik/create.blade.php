@extends('layouts.dashboard')

@section('title', 'Tambah Kegiatan Progress Fisik - Sistem KBR')
@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Tambah Kegiatan Progress Fisik</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Pilih dan tambahkan kegiatan baru untuk kelompok Anda</p>
            </div>
            <a href="{{ route('kelompok.progress-fisik.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-xl text-sm sm:text-base">
                <span class="text-lg sm:text-xl">←</span>
                <span class="font-medium">Kembali</span>
            </a>
        </div>
    </div>

    <!-- Alert Error -->
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-xl sm:text-2xl mr-2 sm:mr-3">❌</span>
            <p class="font-medium text-sm sm:text-base">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Statistik Kegiatan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-3 sm:p-4 rounded-lg border-l-4 border-blue-500 shadow">
            <p class="text-xs sm:text-sm text-gray-600 font-medium">Total Kegiatan</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600 mt-1">{{ $totalKegiatan }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-50 to-green-100 p-3 sm:p-4 rounded-lg border-l-4 border-green-500 shadow">
            <p class="text-xs sm:text-sm text-gray-600 font-medium">Sudah Ditambahkan</p>
            <p class="text-2xl sm:text-3xl font-bold text-green-600 mt-1">{{ $totalTambahkan }}</p>
        </div>
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-3 sm:p-4 rounded-lg border-l-4 border-yellow-500 shadow">
            <p class="text-xs sm:text-sm text-gray-600 font-medium">Kegiatan Tersedia</p>
            <p class="text-2xl sm:text-3xl font-bold text-yellow-600 mt-1">{{ $sisaKegiatan }}</p>
        </div>
    </div>

    <!-- Info Anggaran -->
    <div class="bg-gradient-to-r from-blue-50 to-green-50 border-l-4 border-blue-500 p-4 sm:p-6 mb-4 sm:mb-6 rounded-lg shadow-md">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
            <span class="text-3xl sm:text-4xl">💰</span>
            <div class="flex-1">
                <p class="text-xs sm:text-sm text-gray-700 font-medium">Sisa Anggaran Tersedia</p>
                <p class="text-2xl sm:text-3xl font-bold text-blue-600 mt-1 break-words">
                    Rp {{ number_format($anggaran->sisa_anggaran, 0, ',', '.') }}
                </p>
            </div>
            <div class="w-full sm:w-auto sm:text-right">
                <p class="text-xs text-gray-600">Total Anggaran</p>
                <p class="text-base sm:text-lg font-semibold text-gray-700">
                    Rp {{ number_format($anggaran->total_anggaran, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Alert jika semua kegiatan sudah ditambahkan -->
    @if($sisaKegiatan == 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 sm:p-6 mb-4 sm:mb-6 rounded-lg shadow">
        <div class="flex items-start gap-3">
            <span class="text-2xl sm:text-3xl">⚠️</span>
            <div>
                <p class="font-bold text-yellow-800 text-sm sm:text-base">Semua kegiatan sudah ditambahkan!</p>
                <p class="text-xs sm:text-sm text-yellow-700 mt-1">Anda telah menambahkan semua kegiatan yang tersedia. Silakan kelola kegiatan yang sudah ada di halaman utama.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 sm:p-8">
            <form action="{{ route('kelompok.progress-fisik.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Pilih Kegiatan per Kategori -->
                <div class="mb-6 sm:mb-8">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                        <span>📋</span>
                        Pilih Kegiatan
                    </h3>
                    
                    @foreach($kategoriList as $kategori)
                        @php
                            $kegiatanTersedia = $kategori->masterKegiatan->filter(function($k) use ($existingKegiatan) {
                                return !in_array($k->id, $existingKegiatan);
                            });
                            $kegiatanDitambahkan = $kategori->masterKegiatan->filter(function($k) use ($existingKegiatan) {
                                return in_array($k->id, $existingKegiatan);
                            });
                        @endphp

                        <div class="mb-4 sm:mb-8 border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 sm:px-6 py-3 sm:py-4">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0">
                                    <div class="flex-1">
                                        <h4 class="text-base sm:text-lg font-bold text-white">
                                            {{ $kategori->kode }}. {{ $kategori->nama }}
                                        </h4>
                                        @if($kategori->deskripsi)
                                            <p class="text-xs sm:text-sm text-green-100 mt-1">{{ $kategori->deskripsi }}</p>
                                        @endif
                                    </div>
                                    <div class="text-left sm:text-right">
                                        <p class="text-xs text-green-200">Kegiatan</p>
                                        <p class="text-base sm:text-lg font-bold text-white">
                                            {{ $kegiatanTersedia->count() }} / {{ $kategori->masterKegiatan->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 sm:p-4 bg-gray-50">
                                <!-- Kegiatan yang masih tersedia -->
                                @if($kegiatanTersedia->count() > 0)
                                    <div class="mb-3 sm:mb-4">
                                        <p class="text-xs sm:text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                            <span class="w-2 h-2 sm:w-3 sm:h-3 bg-green-500 rounded-full"></span>
                                            Kegiatan Tersedia ({{ $kegiatanTersedia->count() }})
                                        </p>
                                        <div class="space-y-2">
                                            @foreach($kegiatanTersedia as $kegiatan)
                                                <div class="bg-white p-3 sm:p-4 rounded-lg shadow-sm hover:shadow-md transition-all cursor-pointer border-2 border-transparent hover:border-green-400 kegiatan-item"
                                                     data-kegiatan-id="{{ $kegiatan->id }}"
                                                     data-kegiatan-nama="{{ $kegiatan->nama_kegiatan }}">
                                                    <label class="flex items-start gap-2 sm:gap-4 cursor-pointer">
                                                        <input type="radio" 
                                                               name="master_kegiatan_id" 
                                                               value="{{ $kegiatan->id }}" 
                                                               id="kegiatan_{{ $kegiatan->id }}"
                                                               class="mt-0.5 sm:mt-1 text-green-600 focus:ring-green-500 w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0 kegiatan-radio"
                                                               {{ old('master_kegiatan_id') == $kegiatan->id ? 'checked' : '' }}>
                                                        
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-xs sm:text-sm font-semibold text-gray-900 break-words">
                                                                {{ $kegiatan->nomor }}. {{ $kegiatan->nama_kegiatan }}
                                                            </p>
                                                            <div class="flex items-center gap-1.5 sm:gap-2 mt-2 flex-wrap">
                                                                <span class="px-2 py-0.5 sm:py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                    📏 {{ $kegiatan->satuan }}
                                                                </span>
                                                                @if($kegiatan->is_honor)
                                                                    <span class="px-2 py-0.5 sm:py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                        💰 Honor/Upah
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Kegiatan yang sudah ditambahkan -->
                                @if($kegiatanDitambahkan->count() > 0)
                                    <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-200">
                                        <p class="text-xs sm:text-sm font-semibold text-gray-500 mb-2 flex items-center gap-2">
                                            <span class="w-2 h-2 sm:w-3 sm:h-3 bg-gray-400 rounded-full"></span>
                                            Sudah Ditambahkan ({{ $kegiatanDitambahkan->count() }})
                                        </p>
                                        <div class="space-y-2">
                                            @foreach($kegiatanDitambahkan as $kegiatan)
                                                <div class="bg-gray-100 p-3 sm:p-4 rounded-lg border border-gray-300 opacity-60">
                                                    <div class="flex items-start gap-2 sm:gap-4">
                                                        <input type="radio" 
                                                               disabled
                                                               class="mt-0.5 sm:mt-1 w-4 h-4 sm:w-5 sm:h-5 cursor-not-allowed flex-shrink-0">
                                                        
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-xs sm:text-sm font-semibold text-gray-600 break-words">
                                                                {{ $kegiatan->nomor }}. {{ $kegiatan->nama_kegiatan }}
                                                            </p>
                                                            <div class="flex items-center gap-1.5 sm:gap-2 mt-2 flex-wrap">
                                                                <span class="px-2 py-0.5 sm:py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-600">
                                                                    📏 {{ $kegiatan->satuan }}
                                                                </span>
                                                                <span class="px-2 py-0-5 sm:py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                                    ✅ Sudah Ditambahkan
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Info jika semua kegiatan kategori ini sudah ditambahkan -->
                                @if($kegiatanTersedia->count() == 0)
                                    <div class="text-center py-4 sm:py-6">
                                        <span class="text-3xl sm:text-4xl">✅</span>
                                        <p class="text-gray-600 font-medium mt-2 text-sm sm:text-base">
                                            Semua kegiatan dalam kategori ini sudah ditambahkan
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('master_kegiatan_id')
                    <div class="mb-3 sm:mb-4 bg-red-50 border-l-4 border-red-500 p-3 sm:p-4 rounded">
                        <p class="text-red-700 text-xs sm:text-sm font-medium">{{ $message }}</p>
                    </div>
                @enderror

                <!-- Nama Detail Kegiatan -->
                <div class="border-t pt-6 sm:pt-8">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                        <span>📝</span>
                        Detail Spesifik Kegiatan
                    </h3>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 sm:p-6 rounded-lg mb-4 sm:mb-6">
                        <div class="flex items-start gap-3">
                            <span class="text-xl">💡</span>
                            <div class="text-sm text-yellow-800">
                                <p class="font-semibold mb-1">Keterangan:</p>
                                <p>Gunakan field ini untuk memberikan detail spesifik dari kegiatan yang dipilih. 
                                   Contoh: Untuk "Pengadaan Benih", bisa diisi "Pengadaan Benih Mahoni" atau "Pengadaan Benih Jati".</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="nama_detail" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                            Nama Detail Kegiatan
                        </label>
                        <input type="text" 
                               name="nama_detail" 
                               id="nama_detail"
                               value="{{ old('nama_detail') }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-base sm:text-lg px-3 py-2"
                               placeholder="Contoh: Pengadaan Benih Mahoni, Pembuatan Jalan Usaha Tani, dll.">
                        <p class="text-xs text-gray-500 mt-1">Opsional - Kosongkan jika tidak perlu detail tambahan</p>
                        @error('nama_detail')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Detail Kegiatan -->
                <div class="border-t pt-6 sm:pt-8">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                        <span>📊</span>
                        Rincian Kegiatan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Volume Target -->
                        <div>
                            <label for="volume_target" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                                Volume Target <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   step="0.01" 
                                   name="volume_target" 
                                   id="volume_target"
                                   value="{{ old('volume_target') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-base sm:text-lg px-3 py-2"
                                   placeholder="Masukkan volume target"
                                   required>
                            @error('volume_target')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Biaya Satuan -->
                        <div>
                            <label for="biaya_satuan" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                                Biaya Per Satuan (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   step="1" 
                                   name="biaya_satuan" 
                                   id="biaya_satuan"
                                   value="{{ old('biaya_satuan') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-base sm:text-lg px-3 py-2"
                                   placeholder="Masukkan biaya per satuan"
                                   required>
                            @error('biaya_satuan')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Total Biaya Display -->
                    <div class="mt-4 sm:mt-6 p-4 sm:p-6 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border-l-4 border-yellow-500 shadow-md">
                        <div class="flex items-start sm:items-center justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm text-gray-700 font-medium">Total Biaya Kegiatan</p>
                                <p class="text-2xl sm:text-4xl font-bold text-yellow-600 mt-1 sm:mt-2 break-words" id="total_biaya_display">Rp 0</p>
                            </div>
                            <span class="text-4xl sm:text-6xl flex-shrink-0">💵</span>
                        </div>
                        <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-yellow-200">
                            <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0 text-xs sm:text-sm">
                                <span class="text-gray-600">Sisa anggaran setelah kegiatan ini:</span>
                                <span class="font-bold text-gray-800 break-words" id="sisa_anggaran_display">
                                    Rp {{ number_format($anggaran->sisa_anggaran, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="mt-4 sm:mt-6">
                        <label for="tanggal_mulai" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                            📅 Tanggal Mulai
                        </label>
                        <input type="date" 
                               name="tanggal_mulai" 
                               id="tanggal_mulai"
                               value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-base sm:text-lg px-3 py-2">
                        @error('tanggal_mulai')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="mt-4 sm:mt-6">
                        <label for="keterangan" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                            📄 Keterangan Tambahan
                        </label>
                        <textarea name="keterangan" 
                                  id="keterangan" 
                                  rows="4"
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-sm sm:text-base px-3 py-2"
                                  placeholder="Tambahkan keterangan atau catatan untuk kegiatan ini...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Upload Dokumentasi -->
                <div class="border-t pt-6 sm:pt-8 mt-6 sm:mt-8">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
                        <span>📸</span>
                        Dokumentasi Kegiatan (Opsional)
                    </h3>
                    
                    <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4 rounded-lg">
                        <div class="flex items-start gap-2">
                            <span class="text-lg sm:text-xl">ℹ️</span>
                            <div class="text-xs sm:text-sm text-blue-800">
                                <p class="font-semibold mb-1">Tips Upload Dokumentasi:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Format: JPG, JPEG, PNG (Max 2MB per file)</li>
                                    <li>Tambahkan keterangan untuk setiap foto</li>
                                    <li>Anda bisa menambahkan beberapa foto sekaligus</li>
                                    <li>Dokumentasi bisa ditambahkan/diedit setelah kegiatan dibuat</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div id="foto-container" class="space-y-3 sm:space-y-4">
                        <div class="foto-item bg-gray-50 p-3 sm:p-4 rounded-lg border-2 border-dashed border-gray-300 hover:border-green-400 transition-colors">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                                        📷 Pilih Foto
                                    </label>
                                    <input type="file" 
                                           name="foto[]" 
                                           accept="image/jpeg,image/jpg,image/png"
                                           class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                    <p class="text-xs text-gray-500 mt-1">Max 2MB - JPG, JPEG, PNG</p>
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                                        📝 Keterangan Foto
                                    </label>
                                    <input type="text" 
                                           name="keterangan_foto[]" 
                                           placeholder="Contoh: Kondisi awal lahan sebelum kegiatan"
                                           class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" 
                            id="tambah-foto" 
                            class="mt-3 sm:mt-4 px-4 sm:px-6 py-2 sm:py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm sm:text-base font-medium rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                        <span class="text-lg">➕</span>
                        Tambah Foto Lainnya
                    </button>

                    @error('foto.*')
                        <div class="mt-3 bg-red-50 border-l-4 border-red-500 p-3 rounded">
                            <p class="text-red-700 text-xs sm:text-sm font-medium">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <!-- Tambahkan ini setelah section Upload Dokumentasi Foto -->

<!-- Upload Dokumen PDF -->
<div class="border-t pt-6 sm:pt-8 mt-6 sm:mt-8">
    <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 sm:mb-6 flex items-center gap-2">
        <span>📄</span>
        Dokumen PDF Pendukung (Opsional)
    </h3>
    
    <div class="mb-4 bg-purple-50 border-l-4 border-purple-400 p-3 sm:p-4 rounded-lg">
        <div class="flex items-start gap-2">
            <span class="text-lg sm:text-xl">ℹ️</span>
            <div class="text-xs sm:text-sm text-purple-800">
                <p class="font-semibold mb-1">Tips Upload Dokumen PDF:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Format: PDF (Max 5MB per file)</li>
                    <li>Dapat berupa: RAB, Proposal, Surat, Kontrak, dll</li>
                    <li>Tambahkan keterangan untuk setiap dokumen</li>
                    <li>Anda bisa menambahkan beberapa PDF sekaligus</li>
                    <li>Dokumen dapat ditambahkan/dihapus setelah kegiatan dibuat</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="pdf-container" class="space-y-3 sm:space-y-4">
        <div class="pdf-item bg-purple-50 p-3 sm:p-4 rounded-lg border-2 border-dashed border-purple-300 hover:border-purple-400 transition-colors">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                        📄 Pilih Dokumen PDF
                    </label>
                    <input type="file" 
                           name="dokumen_pdf[]" 
                           accept="application/pdf"
                           class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    <p class="text-xs text-gray-500 mt-1">Max 5MB - Format PDF</p>
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                        📝 Keterangan Dokumen
                    </label>
                    <input type="text" 
                           name="keterangan_pdf[]" 
                           placeholder="Contoh: RAB Kegiatan, Proposal, Surat Permohonan"
                           class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
        </div>
    </div>

    <button type="button" 
            id="tambah-pdf" 
            class="mt-3 sm:mt-4 px-4 sm:px-6 py-2 sm:py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm sm:text-base font-medium rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2">
        <span class="text-lg">➕</span>
        Tambah Dokumen PDF Lainnya
    </button>

    @error('dokumen_pdf.*')
        <div class="mt-3 bg-red-50 border-l-4 border-red-500 p-3 rounded">
            <p class="text-red-700 text-xs sm:text-sm font-medium">{{ $message }}</p>
        </div>
    @enderror
</div>

<!-- Tambahkan JavaScript di bagian script (setelah script foto) -->
<script>
// ==========================================
// SCRIPT UNTUK MULTIPLE PDF UPLOAD
// ==========================================
let pdfCounter = 1;
const maxPdf = 10;

document.getElementById('tambah-pdf').addEventListener('click', function() {
    if (pdfCounter >= maxPdf) {
        alert('⚠️ Maksimal ' + maxPdf + ' dokumen PDF per kegiatan');
        return;
    }

    const container = document.getElementById('pdf-container');
    const newItem = document.querySelector('.pdf-item').cloneNode(true);
    
    // Reset value input
    newItem.querySelectorAll('input').forEach(input => {
        input.value = '';
    });
    
    // Hapus preview jika ada
    const oldPreview = newItem.querySelector('.pdf-preview');
    if (oldPreview) oldPreview.remove();
    
    // Tambahkan tombol hapus untuk PDF tambahan
    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.className = 'mt-2 w-full sm:w-auto px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-all flex items-center justify-center gap-2';
    deleteBtn.innerHTML = '<span class="text-lg">🗑️</span> Hapus Dokumen Ini';
    deleteBtn.onclick = function() {
        newItem.remove();
        pdfCounter--;
        updateTambahPdfButton();
    };
    
    newItem.appendChild(deleteBtn);
    container.appendChild(newItem);
    pdfCounter++;
    updateTambahPdfButton();
});

function updateTambahPdfButton() {
    const btn = document.getElementById('tambah-pdf');
    if (pdfCounter >= maxPdf) {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = '<span class="text-lg">⚠️</span> Maksimal ' + maxPdf + ' Dokumen';
    } else {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = '<span class="text-lg">➕</span> Tambah Dokumen PDF Lainnya';
    }
}

// Preview PDF info saat dipilih
document.addEventListener('change', function(e) {
    if (e.target.type === 'file' && e.target.accept.includes('pdf')) {
        const file = e.target.files[0];
        if (file) {
            // Validasi ukuran
            if (file.size > 5242880) { // 5MB
                alert('⚠️ Ukuran file terlalu besar! Maksimal 5MB');
                e.target.value = '';
                return;
            }

            // Validasi tipe file
            if (file.type !== 'application/pdf') {
                alert('⚠️ Format file tidak didukung! Hanya file PDF yang diperbolehkan');
                e.target.value = '';
                return;
            }

            // Tampilkan info PDF
            const container = e.target.closest('.pdf-item');
            
            // Hapus preview lama jika ada
            const oldPreview = container.querySelector('.pdf-preview');
            if (oldPreview) oldPreview.remove();
            
            // Buat preview info PDF
            const preview = document.createElement('div');
            preview.className = 'pdf-preview mt-3 p-3 bg-white rounded-lg shadow-md border-2 border-purple-400';
            
            // Format ukuran file
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            preview.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <span class="text-4xl">📄</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">${file.name}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full font-medium">
                                ${fileSize} MB
                            </span>
                            <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full font-bold">
                                ✓ Siap Upload
                            </span>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(preview);
        }
    }
});
</script>

                <!-- Tombol Action -->
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 sm:gap-4 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t">
                    <a href="{{ route('kelompok.progress-fisik.index') }}" 
                       class="w-full sm:w-auto px-6 sm:px-8 py-2.5 sm:py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-all shadow-md hover:shadow-lg text-center text-sm sm:text-base">
                        ❌ Batal
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto px-6 sm:px-8 py-2.5 sm:py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-lg transition-all shadow-lg hover:shadow-xl text-sm sm:text-base"
                            {{ $sisaKegiatan == 0 ? 'disabled' : '' }}>
                        ✅ Simpan Kegiatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sisaAnggaranAwal = {{ $anggaran->sisa_anggaran }};
    const volumeInput = document.getElementById('volume_target');
    const biayaInput = document.getElementById('biaya_satuan');
    const totalBiayaDisplay = document.getElementById('total_biaya_display');
    const sisaAnggaranDisplay = document.getElementById('sisa_anggaran_display');
    const namaDetailInput = document.getElementById('nama_detail');

    // Fungsi untuk format rupiah
    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }

    // Fungsi hitung total biaya
    function hitungTotalBiaya() {
        const volume = parseFloat(volumeInput.value) || 0;
        const biaya = parseFloat(biayaInput.value) || 0;
        const total = volume * biaya;
        const sisaAnggaran = sisaAnggaranAwal - total;
        
        // Update tampilan total biaya
        totalBiayaDisplay.textContent = formatRupiah(total);
        
        // Update tampilan sisa anggaran
        sisaAnggaranDisplay.textContent = formatRupiah(sisaAnggaran);
        
        // Ubah warna jika melebihi anggaran
        if (sisaAnggaran < 0) {
            sisaAnggaranDisplay.classList.remove('text-gray-800');
            sisaAnggaranDisplay.classList.add('text-red-600', 'font-bold');
            
            // Tampilkan peringatan
            if (!document.getElementById('warning_anggaran')) {
                const warning = document.createElement('div');
                warning.id = 'warning_anggaran';
                warning.className = 'mt-2 p-2 sm:p-3 bg-red-100 border border-red-400 text-red-700 rounded text-xs sm:text-sm font-medium';
                warning.innerHTML = '⚠️ Total biaya melebihi sisa anggaran yang tersedia!';
                sisaAnggaranDisplay.parentElement.parentElement.appendChild(warning);
            }
        } else {
            sisaAnggaranDisplay.classList.remove('text-red-600');
            sisaAnggaranDisplay.classList.add('text-gray-800');
            
            // Hapus peringatan jika ada
            const warning = document.getElementById('warning_anggaran');
            if (warning) {
                warning.remove();
            }
        }
    }

    // Event listener untuk input
    volumeInput.addEventListener('input', hitungTotalBiaya);
    volumeInput.addEventListener('change', hitungTotalBiaya);
    biayaInput.addEventListener('input', hitungTotalBiaya);
    biayaInput.addEventListener('change', hitungTotalBiaya);
    
    // Hitung sekali saat load jika ada nilai old
    hitungTotalBiaya();

    // ==========================================
    // SCRIPT UNTUK AUTO-SUGGEST NAMA DETAIL
    // ==========================================
    const kegiatanRadios = document.querySelectorAll('.kegiatan-radio');
    
    // Data contoh untuk auto-suggest berdasarkan nama kegiatan
    const autoCompleteData = {
        'Pengadaan Benih': ['Mahoni', 'Jati', 'Sengon', 'Akasia', 'Jabon', 'Gmelina'],
        'Pupuk': ['Pupuk Kandang', 'Pupuk NPK', 'Pupuk Organik', 'Pupuk Urea', 'Pupuk SP-36'],
        'Pestisida': ['Insektisida', 'Fungisida', 'Herbisida', 'Rodentisida'],
        'Peralatan': ['Cangkul', 'Sabit', 'Gergaji', 'Parang', 'Ember', 'Sprayer'],
        'Pembibitan': ['Pembibitan Mahoni', 'Pembibitan Jati', 'Pembibitan Sengon'],
        'Penanaman': ['Penanaman Mahoni', 'Penanaman Jati', 'Penanaman Sengon'],
        'Pemeliharaan': ['Penyulaman', 'Penyiangan', 'Pemupukan', 'Penyemprotan']
    };

    kegiatanRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const kegiatanId = this.value;
            const kegiatanItem = this.closest('.kegiatan-item');
            const kegiatanNama = kegiatanItem.getAttribute('data-kegiatan-nama');
            
            // Reset nama detail
            namaDetailInput.value = '';
            namaDetailInput.placeholder = 'Contoh: Detail spesifik kegiatan...';
            
            // Cari kata kunci untuk auto-suggest
            let keyword = '';
            for (const [key, values] of Object.entries(autoCompleteData)) {
                if (kegiatanNama.includes(key)) {
                    keyword = key;
                    break;
                }
            }
            
            // Jika ditemukan keyword, update placeholder dengan contoh
            if (keyword && autoCompleteData[keyword]) {
                const examples = autoCompleteData[keyword].slice(0, 3).join(', ');
                namaDetailInput.placeholder = `Contoh: ${kegiatanNama} ${examples}, dll.`;
                
                // Tambahkan datalist untuk auto-complete
                let datalist = document.getElementById('nama_detail_suggestions');
                if (!datalist) {
                    datalist = document.createElement('datalist');
                    datalist.id = 'nama_detail_suggestions';
                    document.body.appendChild(datalist);
                }
                
                // Kosongkan dan isi ulang datalist
                datalist.innerHTML = '';
                autoCompleteData[keyword].forEach(item => {
                    const option = document.createElement('option');
                    option.value = `${kegiatanNama} ${item}`;
                    datalist.appendChild(option);
                });
                
                namaDetailInput.setAttribute('list', 'nama_detail_suggestions');
            } else {
                namaDetailInput.removeAttribute('list');
            }
        });
    });

    // Trigger change event untuk radio yang sudah terpilih
    const selectedRadio = document.querySelector('.kegiatan-radio:checked');
    if (selectedRadio) {
        selectedRadio.dispatchEvent(new Event('change'));
    }

    // ==========================================
    // SCRIPT UNTUK MULTIPLE FOTO UPLOAD
    // ==========================================
    let fotoCounter = 1;
    const maxFoto = 10;

    document.getElementById('tambah-foto').addEventListener('click', function() {
        if (fotoCounter >= maxFoto) {
            alert('⚠️ Maksimal ' + maxFoto + ' foto per kegiatan');
            return;
        }

        const container = document.getElementById('foto-container');
        const newItem = document.querySelector('.foto-item').cloneNode(true);
        
        // Reset value input
        newItem.querySelectorAll('input').forEach(input => {
            input.value = '';
        });
        
        // Hapus preview jika ada
        const oldPreview = newItem.querySelector('.image-preview');
        if (oldPreview) oldPreview.remove();
        
        // Tambahkan tombol hapus untuk foto tambahan
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'mt-2 w-full sm:w-auto px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-all flex items-center justify-center gap-2';
        deleteBtn.innerHTML = '<span class="text-lg">🗑️</span> Hapus Foto Ini';
        deleteBtn.onclick = function() {
            newItem.remove();
            fotoCounter--;
            updateTambahFotoButton();
        };
        
        newItem.appendChild(deleteBtn);
        container.appendChild(newItem);
        fotoCounter++;
        updateTambahFotoButton();
    });

    function updateTambahFotoButton() {
        const btn = document.getElementById('tambah-foto');
        if (fotoCounter >= maxFoto) {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btn.innerHTML = '<span class="text-lg">⚠️</span> Maksimal ' + maxFoto + ' Foto';
        } else {
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            btn.innerHTML = '<span class="text-lg">➕</span> Tambah Foto Lainnya';
        }
    }

    // Preview image saat dipilih
    document.addEventListener('change', function(e) {
        if (e.target.type === 'file' && e.target.accept.includes('image')) {
            const file = e.target.files[0];
            if (file) {
                // Validasi ukuran
                if (file.size > 2048000) { // 2MB
                    alert('⚠️ Ukuran file terlalu besar! Maksimal 2MB');
                    e.target.value = '';
                    return;
                }

                // Validasi tipe file
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('⚠️ Format file tidak didukung! Gunakan JPG, JPEG, atau PNG');
                    e.target.value = '';
                    return;
                }

                // Tampilkan preview
                const reader = new FileReader();
                reader.onload = function(event) {
                    // Cari container parent
                    const container = e.target.closest('.foto-item');
                    
                    // Hapus preview lama jika ada
                    const oldPreview = container.querySelector('.image-preview');
                    if (oldPreview) oldPreview.remove();
                    
                    // Buat preview baru
                    const preview = document.createElement('div');
                    preview.className = 'image-preview mt-3 relative';
                    preview.innerHTML = `
                        <img src="${event.target.result}" 
                             class="w-full h-40 object-cover rounded-lg shadow-md border-2 border-green-400" 
                             alt="Preview">
                        <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">
                            ✓ Siap Upload
                        </div>
                    `;
                    container.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        }
    });
});
</script>
@endsection