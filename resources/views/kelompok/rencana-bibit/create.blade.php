@extends('layouts.dashboard')

@section('title', 'Tambah Rencana Bibit - Sistem KBR')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 slide-in">
        <a href="{{ route('kelompok.rencana-bibit.index') }}" 
           class="inline-flex items-center text-green-600 hover:text-green-700 mb-4 font-medium">
            ← Kembali ke Daftar
        </a>
        <h1 class="text-4xl font-bold text-gray-800 mb-2">🌱 Tambah Rencana Bibit</h1>
        <p class="text-gray-600">Isi form berikut untuk menambahkan rencana kebutuhan bibit baru</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden slide-in">
        <div class="p-8">
            <form action="{{ route('kelompok.rencana-bibit.store') }}" method="POST">
                @csrf

                <!-- Jenis Bibit -->
                <div class="mb-6">
                    <label for="jenis_bibit" class="block text-sm font-semibold text-gray-700 mb-2">
                        Jenis Bibit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="jenis_bibit" 
                           id="jenis_bibit"
                           value="{{ old('jenis_bibit') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('jenis_bibit') border-red-500 @enderror"
                           placeholder="Contoh: Sengon, Mahoni, Jati, dll"
                           required>
                    @error('jenis_bibit')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Golongan -->
                <div class="mb-6">
                    <label for="golongan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Golongan <span class="text-red-500">*</span>
                    </label>
                    <select name="golongan" 
                            id="golongan"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('golongan') border-red-500 @enderror"
                            required>
                        <option value="">-- Pilih Golongan --</option>
                        <option value="MPTS" {{ old('golongan') == 'MPTS' ? 'selected' : '' }}>🌳 MPTS (Multi Purpose Tree Species)</option>
                        <option value="Kayu" {{ old('golongan') == 'Kayu' ? 'selected' : '' }}>🪵 Kayu</option>
                       
                    </select>
                    @error('golongan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid untuk Jumlah dan Tinggi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Jumlah Batang -->
                    <div>
                        <label for="jumlah_btg" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jumlah Batang <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="jumlah_btg" 
                               id="jumlah_btg"
                               value="{{ old('jumlah_btg') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('jumlah_btg') border-red-500 @enderror"
                               placeholder="Contoh: 1000"
                               min="1"
                               required>
                        @error('jumlah_btg')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tinggi -->
                    <div>
                        <label for="tinggi" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tinggi (cm) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="tinggi" 
                               id="tinggi"
                               value="{{ old('tinggi') }}"
                               step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('tinggi') border-red-500 @enderror"
                               placeholder="Contoh: 30.5"
                               min="0"
                               required>
                        @error('tinggi')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Sertifikat -->
                <div class="mb-8">
                    <label for="sertifikat" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nomor/Jenis Sertifikat
                    </label>
                    <input type="text" 
                           name="sertifikat" 
                           id="sertifikat"
                           value="{{ old('sertifikat') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('sertifikat') border-red-500 @enderror"
                           placeholder="Contoh: SNI 7644:2014 (Opsional)">
                    @error('sertifikat')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">Kosongkan jika bibit tidak memiliki sertifikat</p>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('kelompok.rencana-bibit.index') }}" 
                       class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300 font-semibold">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                        💾 Simpan Rencana Bibit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection