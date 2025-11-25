@extends('layouts.dashboard')

@section('title', 'Edit Realisasi Bibit - Sistem KBR')
@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8 max-w-3xl">
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('kelompok.realisasi-bibit.index') }}" 
           class="text-green-600 hover:text-green-800 font-medium flex items-center gap-2 mb-4 text-sm sm:text-base">
            <span>←</span> Kembali
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Realisasi Bibit</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-1">Perbarui data realisasi bibit</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8">
        <form action="{{ route('kelompok.realisasi-bibit.update', $realisasiBibit->id_bibit) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Jenis Bibit -->
            <div class="mb-4 sm:mb-6">
                <label for="jenis_bibit" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                    Jenis Bibit <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="jenis_bibit" 
                       id="jenis_bibit" 
                       value="{{ old('jenis_bibit', $realisasiBibit->jenis_bibit) }}"
                       class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('jenis_bibit') border-red-500 @enderror"
                       placeholder="Contoh: Mahoni, Jati, Sengon"
                       required>
                @error('jenis_bibit')
                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Golongan -->
            <div class="mb-4 sm:mb-6">
                <label for="golongan" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                    Golongan
                </label>
                <select name="golongan" 
                        id="golongan"
                        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('golongan') border-red-500 @enderror">
                    <option value="">-- Pilih Golongan --</option>
                    <option value="Cepat Tumbuh" {{ old('golongan', $realisasiBibit->golongan) == 'Cepat Tumbuh' ? 'selected' : '' }}>Cepat Tumbuh</option>
                    <option value="Sedang" {{ old('golongan', $realisasiBibit->golongan) == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Lambat" {{ old('golongan', $realisasiBibit->golongan) == 'Lambat' ? 'selected' : '' }}>Lambat</option>
                    <option value="MPTS" {{ old('golongan', $realisasiBibit->golongan) == 'MPTS' ? 'selected' : '' }}>MPTS</option>
                </select>
                @error('golongan')
                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jumlah Batang & Tinggi Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                <!-- Jumlah Batang -->
                <div>
                    <label for="jumlah_btg" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                        Jumlah Batang <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="jumlah_btg" 
                           id="jumlah_btg" 
                           value="{{ old('jumlah_btg', $realisasiBibit->jumlah_btg) }}"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('jumlah_btg') border-red-500 @enderror"
                           placeholder="Contoh: 1000"
                           min="1"
                           required>
                    @error('jumlah_btg')
                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tinggi -->
                <div>
                    <label for="tinggi" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                        Tinggi Rata-rata (cm)
                    </label>
                    <input type="number" 
                           name="tinggi" 
                           id="tinggi" 
                           value="{{ old('tinggi', $realisasiBibit->tinggi) }}"
                           step="0.01"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tinggi') border-red-500 @enderror"
                           placeholder="Contoh: 25.50">
                    @error('tinggi')
                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sertifikat -->
            <div class="mb-6 sm:mb-8">
                <label for="sertifikat" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                    Nomor Sertifikat
                </label>
                <input type="text" 
                       name="sertifikat" 
                       id="sertifikat" 
                       value="{{ old('sertifikat', $realisasiBibit->sertifikat) }}"
                       class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('sertifikat') border-red-500 @enderror"
                       placeholder="Masukkan nomor sertifikat (opsional)">
                @error('sertifikat')
                    <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500">Kosongkan jika tidak memiliki sertifikat</p>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col-reverse sm:flex-row gap-3 sm:gap-4 pt-4 sm:pt-6 border-t border-gray-200">
                <a href="{{ route('kelompok.realisasi-bibit.index') }}" 
                   class="w-full sm:flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition-all text-center text-sm sm:text-base">
                    ❌ Batal
                </a>
                <button type="submit" 
                        class="w-full sm:flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-6 rounded-lg transition-all shadow-lg hover:shadow-xl text-sm sm:text-base">
                    💾 Update Data
                </button>
            </div>
        </form>
    </div>

    <!-- Info Helper Card -->
    <div class="mt-4 sm:mt-6 bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4 rounded-lg">
        <div class="flex items-start gap-2 sm:gap-3">
            <span class="text-xl sm:text-2xl flex-shrink-0">💡</span>
            <div>
                <h4 class="text-xs sm:text-sm font-bold text-blue-800 mb-1">Tips Pengisian</h4>
                <ul class="text-xs sm:text-sm text-blue-700 space-y-1">
                    <li>• <strong>Jenis Bibit:</strong> Nama bibit yang direalisasikan (wajib diisi)</li>
                    <li>• <strong>Golongan:</strong> Klasifikasi pertumbuhan bibit (opsional)</li>
                    <li>• <strong>Jumlah Batang:</strong> Total batang yang direalisasikan (wajib diisi)</li>
                    <li>• <strong>Tinggi:</strong> Tinggi rata-rata bibit dalam centimeter (opsional)</li>
                    <li>• <strong>Sertifikat:</strong> Nomor sertifikat jika tersedia (opsional)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection