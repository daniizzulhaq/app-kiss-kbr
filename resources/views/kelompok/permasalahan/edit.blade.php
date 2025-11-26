@extends('layouts.dashboard')

@section('title', 'Edit Laporan Permasalahan')

@section('content')
<div class="py-4 sm:py-6 lg:py-12">
    <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-4 sm:mb-6">
            <div class="flex items-center gap-2 sm:gap-3 mb-2">
                <a href="{{ route('kelompok.permasalahan.index') }}" 
                   class="text-gray-600 hover:text-gray-900 transition p-1">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">Edit Laporan</h2>
                    <p class="text-xs sm:text-sm lg:text-base text-gray-600 mt-0.5 sm:mt-1">Perbarui informasi laporan permasalahan</p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg">
                <p class="font-medium text-xs sm:text-sm lg:text-base">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg">
                <p class="font-medium text-xs sm:text-sm lg:text-base mb-2">Terdapat kesalahan pada form:</p>
                <ul class="list-disc list-inside text-xs sm:text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-green-100 px-4 sm:px-6 py-3 sm:py-4 border-b border-green-200">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">✏️</span>
                    <span>Form Edit Laporan</span>
                </h3>
            </div>

            <form action="{{ route('kelompok.permasalahan.update', $permasalahan) }}" method="POST" class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Kelompok -->
                <div>
                    <label for="kelompok" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                        Nama Kelompok <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="kelompok" 
                           name="kelompok" 
                           value="{{ old('kelompok', $permasalahan->kelompok) }}"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('kelompok') border-red-500 @enderror" 
                           placeholder="Masukkan nama kelompok"
                           required>
                    @error('kelompok')
                        <p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sarana dan Prasarana -->
                <div>
                    <label for="sarpras" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                        Sarana dan Prasarana <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="sarpras" 
                           name="sarpras" 
                           value="{{ old('sarpras', $permasalahan->sarpras) }}"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('sarpras') border-red-500 @enderror" 
                           placeholder="Contoh: Cangkul, pompa air, dll"
                           required>
                    @error('sarpras')
                        <p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bibit -->
                <div>
                    <label for="bibit" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                        Bibit <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="bibit" 
                           name="bibit" 
                           value="{{ old('bibit', $permasalahan->bibit) }}"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('bibit') border-red-500 @enderror" 
                           placeholder="Contoh: Bibit jagung, padi, dll"
                           required>
                    @error('bibit')
                        <p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lokasi Tanam -->
                <div>
                    <label for="lokasi_tanam" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                        Lokasi Tanam <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="lokasi_tanam" 
                           name="lokasi_tanam" 
                           value="{{ old('lokasi_tanam', $permasalahan->lokasi_tanam) }}"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('lokasi_tanam') border-red-500 @enderror" 
                           placeholder="Contoh: Desa Sukamaju, Blok A"
                           required>
                    @error('lokasi_tanam')
                        <p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi Permasalahan -->
                <div>
                    <label for="permasalahan" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                        Deskripsi Permasalahan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="permasalahan" 
                              name="permasalahan" 
                              rows="4"
                              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('permasalahan') border-red-500 @enderror resize-none" 
                              placeholder="Jelaskan permasalahan secara detail..."
                              required>{{ old('permasalahan', $permasalahan->permasalahan) }}</textarea>
                    @error('permasalahan')
                        <p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-gray-500">Min. 10 karakter</p>
                </div>

                <!-- Prioritas -->
                <div>
                    <label for="prioritas" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1.5 sm:mb-2">
                        Prioritas <span class="text-red-500">*</span>
                    </label>
                    <select id="prioritas" 
                            name="prioritas"
                            class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('prioritas') border-red-500 @enderror appearance-none bg-white"
                            required>
                        <option value="">-- Pilih Prioritas --</option>
                        <option value="rendah" {{ old('prioritas', $permasalahan->prioritas) == 'rendah' ? 'selected' : '' }}>
                            🟢 Rendah
                        </option>
                        <option value="sedang" {{ old('prioritas', $permasalahan->prioritas) == 'sedang' ? 'selected' : '' }}>
                            🟡 Sedang
                        </option>
                        <option value="tinggi" {{ old('prioritas', $permasalahan->prioritas) == 'tinggi' ? 'selected' : '' }}>
                            🔴 Tinggi
                        </option>
                    </select>
                    @error('prioritas')
                        <p class="mt-1.5 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-2.5 sm:gap-3 pt-3 sm:pt-4 border-t border-gray-200">
                    <button type="submit" 
                            class="w-full px-4 sm:px-6 py-2.5 sm:py-3 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white text-sm sm:text-base font-semibold rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <span class="text-base sm:text-lg">💾</span>
                        <span>Update Laporan</span>
                    </button>
                    <a href="{{ route('kelompok.permasalahan.show', $permasalahan) }}" 
                       class="w-full px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-500 hover:bg-gray-600 active:bg-gray-700 text-white text-sm sm:text-base font-semibold rounded-lg transition-all duration-300 flex items-center justify-center gap-2">
                        <span class="text-base sm:text-lg">❌</span>
                        <span>Batal</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Info Note -->
        <div class="mt-4 sm:mt-6 bg-blue-50 border-l-4 border-blue-500 p-3 sm:p-4 rounded-lg">
            <div class="flex items-start gap-2 sm:gap-3">
                <span class="text-xl sm:text-2xl flex-shrink-0">ℹ️</span>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-blue-800 font-medium mb-1">Catatan:</p>
                    <ul class="text-xs sm:text-sm text-blue-700 space-y-0.5 sm:space-y-1">
                        <li>• Pastikan semua data sudah benar</li>
                        <li>• Laporan yang diproses tidak dapat diedit</li>
                        <li>• Field bertanda <span class="text-red-500">*</span> wajib diisi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection