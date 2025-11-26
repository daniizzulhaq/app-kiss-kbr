@extends('layouts.dashboard')

@section('title', 'Edit Peta Lokasi - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('kelompok.peta-lokasi.show', $petaLokasi) }}" 
               class="text-gray-600 hover:text-gray-800 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 flex items-center gap-2">
                <span class="text-xl sm:text-2xl">✏️</span>
                Edit Peta Lokasi
            </h1>
        </div>
        <p class="text-sm sm:text-base text-gray-600 ml-9">
            Edit informasi peta lokasi untuk kelompok <strong class="text-green-600">{{ $kelompok->nama_kelompok }}</strong>
        </p>
    </div>

    <!-- Main Form Card -->
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 sm:p-6 lg:p-8">
                <!-- Info Box -->
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-3 sm:p-4 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm sm:text-base text-blue-700">
                                <strong>Informasi:</strong> Setelah diupdate, status verifikasi akan direset ke "Menunggu" dan perlu diverifikasi ulang oleh BPDAS.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Current File Info -->
                <div class="mb-6 bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                        File Saat Ini
                    </h4>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="bg-red-100 p-2 rounded-lg">
                                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $petaLokasi->file_name }}</p>
                                <p class="text-sm text-gray-500">Upload: {{ $petaLokasi->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($petaLokasi->file_path) }}" 
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat File
                        </a>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('kelompok.peta-lokasi.update', $petaLokasi) }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Kelompok Info -->
                    <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            📍 Kelompok
                        </label>
                        <p class="text-lg font-bold text-gray-900">{{ $kelompok->nama_kelompok }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $kelompok->desa }}, {{ $kelompok->kecamatan }}</p>
                    </div>

                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Peta <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="judul" 
                               id="judul" 
                               value="{{ old('judul', $petaLokasi->judul) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition @error('judul') border-red-500 @enderror"
                               placeholder="Contoh: Peta Lokasi Penanaman 2025"
                               required>
                        @error('judul')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan (Opsional)
                        </label>
                        <textarea name="keterangan" 
                                  id="keterangan" 
                                  rows="4"
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 transition @error('keterangan') border-red-500 @enderror"
                                  placeholder="Tambahkan keterangan atau catatan tentang peta lokasi ini...">{{ old('keterangan', $petaLokasi->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- File Upload (Optional) -->
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                            Ganti File PDF (Opsional)
                        </label>
                        <p class="text-sm text-gray-500 mb-3">💡 Biarkan kosong jika tidak ingin mengganti file</p>
                        <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-all cursor-pointer @error('file') border-red-500 @enderror"
                             onclick="document.getElementById('file').click()">
                            <div class="space-y-2 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                        <span>Upload file baru</span>
                                        <input id="file" 
                                               name="file" 
                                               type="file" 
                                               class="sr-only" 
                                               accept=".pdf"
                                               onchange="displayFileName(this)">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF hingga 10MB</p>
                                <p id="file-name" class="text-sm font-medium text-green-600"></p>
                            </div>
                        </div>
                        @error('file')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('kelompok.peta-lokasi.show', $petaLokasi) }}" 
                           class="w-full sm:w-auto px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all text-center font-medium">
                            Batal
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-8 py-2.5 rounded-lg flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-xl font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Peta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function displayFileName(input) {
        const fileNameDisplay = document.getElementById('file-name');
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
            fileNameDisplay.textContent = `📄 ${fileName} (${fileSize} MB)`;
        }
    }
</script>
@endsection