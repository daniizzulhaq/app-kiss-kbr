@extends('layouts.dashboard')

@section('title', 'Rencana Bibit - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('kelompok.realisasi-bibit.index') }}" 
           class="text-green-600 hover:text-green-800 font-medium flex items-center gap-2 mb-4">
            <span>←</span> Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Tambah Realisasi Bibit</h1>
        <p class="text-gray-600 mt-1">Masukkan data realisasi bibit yang telah direalisasikan</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('kelompok.realisasi-bibit.store') }}" method="POST">
            @csrf

            <!-- Jenis Bibit -->
            <div class="mb-6">
                <label for="jenis_bibit" class="block text-sm font-bold text-gray-700 mb-2">
                    Jenis Bibit <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="jenis_bibit" 
                       id="jenis_bibit" 
                       value="{{ old('jenis_bibit') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('jenis_bibit') border-red-500 @enderror"
                       placeholder="Contoh: Mahoni, Jati, Sengon"
                       required>
                @error('jenis_bibit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Golongan -->
            <div class="mb-6">
                <label for="golongan" class="block text-sm font-bold text-gray-700 mb-2">
                    Golongan
                </label>
                <select name="golongan" 
                        id="golongan"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('golongan') border-red-500 @enderror">
                    <option value="">-- Pilih Golongan --</option>
                    <option value="Cepat Tumbuh" {{ old('golongan') == 'Cepat Tumbuh' ? 'selected' : '' }}>Cepat Tumbuh</option>
                    <option value="Sedang" {{ old('golongan') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Lambat" {{ old('golongan') == 'Lambat' ? 'selected' : '' }}>Lambat</option>
                    <option value="MPTS" {{ old('golongan') == 'MPTS' ? 'selected' : '' }}>MPTS</option>
                </select>
                @error('golongan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jumlah Batang -->
            <div class="mb-6">
                <label for="jumlah_btg" class="block text-sm font-bold text-gray-700 mb-2">
                    Jumlah Batang <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       name="jumlah_btg" 
                       id="jumlah_btg" 
                       value="{{ old('jumlah_btg') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('jumlah_btg') border-red-500 @enderror"
                       placeholder="Masukkan jumlah batang"
                       min="1"
                       required>
                @error('jumlah_btg')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tinggi -->
            <div class="mb-6">
                <label for="tinggi" class="block text-sm font-bold text-gray-700 mb-2">
                    Tinggi Rata-rata (cm)
                </label>
                <input type="number" 
                       name="tinggi" 
                       id="tinggi" 
                       value="{{ old('tinggi') }}"
                       step="0.01"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('tinggi') border-red-500 @enderror"
                       placeholder="Contoh: 25.50">
                @error('tinggi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sertifikat -->
            <div class="mb-6">
                <label for="sertifikat" class="block text-sm font-bold text-gray-700 mb-2">
                    Nomor Sertifikat
                </label>
                <input type="text" 
                       name="sertifikat" 
                       id="sertifikat" 
                       value="{{ old('sertifikat') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('sertifikat') border-red-500 @enderror"
                       placeholder="Masukkan nomor sertifikat">
                @error('sertifikat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <button type="submit" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-all shadow-lg hover:shadow-xl">
                    💾 Simpan Data
                </button>
                <a href="{{ route('kelompok.realisasi-bibit.index') }}" 
                   class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg transition-all text-center">
                    ❌ Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection