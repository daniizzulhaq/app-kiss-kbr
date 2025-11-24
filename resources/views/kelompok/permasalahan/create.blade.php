@extends('layouts.dashboard')

@section('title', 'Buat Laporan Permasalahan')

@section('content')
<div class="py-6 sm:py-12">
    <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('kelompok.permasalahan.index') }}" 
               class="text-green-600 hover:text-green-700 font-medium flex items-center gap-2 mb-3 sm:mb-4 text-sm sm:text-base">
                <span class="text-lg sm:text-xl">←</span>
                <span>Kembali ke Daftar Laporan</span>
            </a>
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 mb-2">Buat Laporan Permasalahan</h2>
            <p class="text-sm sm:text-base text-gray-600">Isi formulir di bawah untuk melaporkan permasalahan kelompok Anda.</p>
        </div>

        <!-- Form -->
        <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-xl shadow-lg">
            <form action="{{ route('kelompok.permasalahan.store') }}" method="POST">
                @csrf

                <!-- Kelompok -->
                <div class="mb-4 sm:mb-5">
                    <label class="block text-sm sm:text-base text-gray-700 font-medium mb-2 flex items-center gap-2">
                        <span class="text-base sm:text-lg">👥</span>
                        <span>Nama Kelompok</span>
                    </label>
                    <input type="text" 
                           name="kelompok" 
                           value="{{ old('kelompok') }}"
                           placeholder="Masukkan nama kelompok"
                           class="w-full text-sm sm:text-base border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-3 sm:px-4 py-2 sm:py-2.5">
                    @error('kelompok')
                        <p class="text-red-600 text-xs sm:text-sm mt-1 flex items-center gap-1">
                            <span>⚠️</span>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Sarana dan Prasarana -->
                <div class="mb-4 sm:mb-5">
                    <label class="block text-sm sm:text-base text-gray-700 font-medium mb-2 flex items-center gap-2">
                        <span class="text-base sm:text-lg">🛠️</span>
                        <span>Sarana dan Prasarana</span>
                    </label>
                    <input type="text" 
                           name="sarpras" 
                           value="{{ old('sarpras') }}"
                           placeholder="Contoh: Cangkul, sekop, dll"
                           class="w-full text-sm sm:text-base border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-3 sm:px-4 py-2 sm:py-2.5">
                    @error('sarpras')
                        <p class="text-red-600 text-xs sm:text-sm mt-1 flex items-center gap-1">
                            <span>⚠️</span>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Bibit -->
                <div class="mb-4 sm:mb-5">
                    <label class="block text-sm sm:text-base text-gray-700 font-medium mb-2 flex items-center gap-2">
                        <span class="text-base sm:text-lg">🌱</span>
                        <span>Bibit</span>
                    </label>
                    <input type="text" 
                           name="bibit" 
                           value="{{ old('bibit') }}"
                           placeholder="Masukkan jenis bibit"
                           class="w-full text-sm sm:text-base border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-3 sm:px-4 py-2 sm:py-2.5">
                    @error('bibit')
                        <p class="text-red-600 text-xs sm:text-sm mt-1 flex items-center gap-1">
                            <span>⚠️</span>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Lokasi Tanam -->
                <div class="mb-4 sm:mb-5">
                    <label class="block text-sm sm:text-base text-gray-700 font-medium mb-2 flex items-center gap-2">
                        <span class="text-base sm:text-lg">📍</span>
                        <span>Lokasi Tanam</span>
                    </label>
                    <input type="text" 
                           name="lokasi_tanam" 
                           value="{{ old('lokasi_tanam') }}"
                           placeholder="Masukkan lokasi tanam"
                           class="w-full text-sm sm:text-base border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-3 sm:px-4 py-2 sm:py-2.5">
                    @error('lokasi_tanam')
                        <p class="text-red-600 text-xs sm:text-sm mt-1 flex items-center gap-1">
                            <span>⚠️</span>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Permasalahan -->
                <div class="mb-4 sm:mb-5">
                    <label class="block text-sm sm:text-base text-gray-700 font-medium mb-2 flex items-center gap-2">
                        <span class="text-base sm:text-lg">⚠️</span>
                        <span>Deskripsi Permasalahan</span>
                    </label>
                    <textarea name="permasalahan" 
                              rows="4"
                              placeholder="Jelaskan permasalahan yang dihadapi secara detail..."
                              class="w-full text-sm sm:text-base border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-3 sm:px-4 py-2 sm:py-2.5">{{ old('permasalahan') }}</textarea>
                    @error('permasalahan')
                        <p class="text-red-600 text-xs sm:text-sm mt-1 flex items-center gap-1">
                            <span>⚠️</span>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">💡 Jelaskan dengan detail agar solusi yang diberikan lebih tepat</p>
                </div>

                <!-- Prioritas -->
                <div class="mb-6 sm:mb-8">
                    <label class="block text-sm sm:text-base text-gray-700 font-medium mb-2 flex items-center gap-2">
                        <span class="text-base sm:text-lg">🎯</span>
                        <span>Prioritas</span>
                    </label>
                    <select name="prioritas"
                            class="w-full text-sm sm:text-base border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-3 sm:px-4 py-2 sm:py-2.5">
                        <option value="">-- Pilih Prioritas --</option>
                        <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>
                            🟢 Rendah - Tidak mendesak
                        </option>
                        <option value="sedang" {{ old('prioritas') == 'sedang' ? 'selected' : '' }}>
                            🟡 Sedang - Perlu segera ditangani
                        </option>
                        <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>
                            🔴 Tinggi - Sangat mendesak
                        </option>
                    </select>
                    @error('prioritas')
                        <p class="text-red-600 text-xs sm:text-sm mt-1 flex items-center gap-1">
                            <span>⚠️</span>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4 rounded-lg">
                    <div class="flex items-start gap-2">
                        <span class="text-lg sm:text-xl">ℹ️</span>
                        <div class="text-xs sm:text-sm text-blue-800">
                            <p class="font-semibold mb-1">Tips Membuat Laporan:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                <li>Jelaskan permasalahan dengan detail dan jelas</li>
                                <li>Sertakan informasi lokasi dan waktu kejadian</li>
                                <li>Pilih prioritas sesuai dengan tingkat urgensi</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 sm:justify-end">
                    <a href="{{ route('kelompok.permasalahan.index') }}"
                       class="w-full sm:w-auto text-center px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-gray-300 hover:border-gray-400 text-gray-700 hover:bg-gray-50 rounded-lg font-medium transition-all duration-300 text-sm sm:text-base">
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg text-sm sm:text-base flex items-center justify-center gap-2">
                        <span>💾</span>
                        <span>Simpan Laporan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection