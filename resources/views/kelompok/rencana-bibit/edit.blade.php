@extends('layouts.dashboard')

@section('title', 'Edit Rencana Bibit - Sistem KBR')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8 slide-in">
        <a href="{{ route('kelompok.rencana-bibit.index') }}" 
           class="inline-flex items-center text-green-600 hover:text-green-700 mb-4 font-medium text-sm sm:text-base">
            ← Kembali ke Daftar
        </a>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-2">✏️ Edit Rencana Bibit</h1>
        <p class="text-sm sm:text-base text-gray-600">Perbarui informasi rencana kebutuhan bibit</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden slide-in">
        <div class="p-4 sm:p-6 lg:p-8">
            <form action="{{ route('kelompok.rencana-bibit.update', $rencanaBibit) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Jenis Bibit -->
                <div class="mb-4 sm:mb-6">
                    <label for="jenis_bibit" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                        Jenis Bibit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="jenis_bibit" 
                           id="jenis_bibit"
                           value="{{ old('jenis_bibit', $rencanaBibit->jenis_bibit) }}"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('jenis_bibit') border-red-500 @enderror"
                           placeholder="Contoh: Sengon, Mahoni, Jati, dll"
                           required>
                    @error('jenis_bibit')
                        <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Golongan -->
                <div class="mb-4 sm:mb-6">
                    <label for="golongan" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                        Golongan <span class="text-red-500">*</span>
                    </label>
                    <select name="golongan" 
                            id="golongan"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('golongan') border-red-500 @enderror"
                            required>
                        <option value="">-- Pilih Golongan --</option>
                        <option value="MPTS" {{ old('golongan', $rencanaBibit->golongan) == 'MPTS' ? 'selected' : '' }}>🌳 MPTS (Multi Purpose Tree Species)</option>
                        <option value="Kayu" {{ old('golongan', $rencanaBibit->golongan) == 'Kayu' ? 'selected' : '' }}>🪵 Kayu</option>
                        <option value="Buah" {{ old('golongan', $rencanaBibit->golongan) == 'Buah' ? 'selected' : '' }}>🍎 Buah</option>
                        <option value="Bambu" {{ old('golongan', $rencanaBibit->golongan) == 'Bambu' ? 'selected' : '' }}>🎋 Bambu</option>
                    </select>
                    @error('golongan')
                        <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid untuk Jumlah dan Tinggi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
                    <!-- Jumlah Batang -->
                    <div>
                        <label for="jumlah_btg" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                            Jumlah Batang <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="jumlah_btg" 
                               id="jumlah_btg"
                               value="{{ old('jumlah_btg', $rencanaBibit->jumlah_btg) }}"
                               class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('jumlah_btg') border-red-500 @enderror"
                               placeholder="Contoh: 1000"
                               min="1"
                               required>
                        @error('jumlah_btg')
                            <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tinggi -->
                    <div>
                        <label for="tinggi" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                            Tinggi (cm) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="tinggi" 
                               id="tinggi"
                               value="{{ old('tinggi', $rencanaBibit->tinggi) }}"
                               step="0.01"
                               class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('tinggi') border-red-500 @enderror"
                               placeholder="Contoh: 30.5"
                               min="0"
                               required>
                        @error('tinggi')
                            <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sertifikat -->
                <div class="mb-6 sm:mb-8">
                    <label for="sertifikat" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                        Nomor/Jenis Sertifikat
                    </label>
                    <input type="text" 
                           name="sertifikat" 
                           id="sertifikat"
                           value="{{ old('sertifikat', $rencanaBibit->sertifikat) }}"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('sertifikat') border-red-500 @enderror"
                           placeholder="Contoh: SNI 7644:2014 (Opsional)">
                    @error('sertifikat')
                        <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">Kosongkan jika bibit tidak memiliki sertifikat</p>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 sm:gap-4 pt-4 sm:pt-6 border-t">
                    <a href="{{ route('kelompok.rencana-bibit.index') }}" 
                       class="w-full sm:w-auto text-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-100 text-gray-700 rounded-lg sm:rounded-xl hover:bg-gray-200 transition-all duration-300 font-semibold text-sm sm:text-base">
                        Batal
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg sm:rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold text-sm sm:text-base">
                        💾 Update Rencana Bibit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection