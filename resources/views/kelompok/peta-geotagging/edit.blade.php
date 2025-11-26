@extends('layouts.dashboard')

@section('title', 'Edit Peta Geotagging - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 flex items-center gap-2">
                <span class="text-2xl">✏️</span>
                Edit Peta Geotagging
            </h1>
            <p class="text-gray-600 mt-2">Perbarui data peta geotagging yang telah diupload</p>
        </div>

        <!-- Info Box -->
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-semibold text-yellow-800">Informasi Edit</p>
                    <ul class="text-sm text-yellow-700 mt-1 list-disc list-inside space-y-1">
                        <li>Status akan direset ke <strong>Menunggu Verifikasi</strong> setelah edit</li>
                        <li>Jika mengubah file, file lama akan dihapus dan diganti dengan file baru</li>
                        <li>Upload <strong>1-5 file PDF</strong> sekaligus dalam satu data</li>
                        <li>Maksimal total size: <strong>20MB</strong></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <form action="{{ route('kelompok.peta-geotagging.update', $petaGeotagging) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                @method('PUT')

                <!-- Current Status -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Status Saat Ini:</p>
                            <div class="mt-1">{!! $petaGeotagging->status_badge !!}</div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Tanggal Upload:</p>
                            <p class="text-sm font-medium text-gray-900">{{ $petaGeotagging->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Judul -->
                <div class="mb-6">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                        Judul Peta Geotagging <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="judul" 
                           id="judul"
                           value="{{ old('judul', $petaGeotagging->judul) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                           placeholder="Contoh: Peta Geotagging Lahan Reboisasi Desa ABC"
                           required>
                    @error('judul')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload Section -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        File PDF <span class="text-sm text-gray-500">(1-5 file PDF) - Kosongkan jika tidak ingin mengubah file</span>
                    </label>
                    
                    <!-- Current Files -->
                    @if(!empty($petaGeotagging->files) && is_array($petaGeotagging->files))
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">File Saat Ini:</p>
                        <div class="space-y-2">
                            @foreach($petaGeotagging->files as $file)
                            <div class="flex items-center justify-between bg-green-50 p-3 rounded-lg border border-green-200">
                                <div class="flex items-center gap-3 flex-1">
                                    <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $file['name'] ?? 'Unnamed file' }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ isset($file['size']) ? number_format($file['size'] / 1024 / 1024, 2) : '0.00' }} MB
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($file['path']) }}" 
                                   target="_blank"
                                   class="ml-3 text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition-colors"
                                   title="Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Add File Button -->
                    <div class="mb-4">
                        <button type="button" 
                                id="addFileBtn"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah File PDF Baru
                        </button>
                        <input type="file" 
                               id="fileInput"
                               accept=".pdf"
                               class="hidden">
                    </div>

                    <!-- File List Container -->
                    <div id="fileListContainer" class="space-y-3">
                        <!-- New files will be added here dynamically -->
                    </div>

                    <!-- File Count Info -->
                    <div id="fileCountInfo" class="mt-3 text-sm">
                        <span class="text-gray-600">File baru terpilih: <strong id="fileCount">0</strong> / 5</span>
                        <span id="totalSize" class="ml-4 text-gray-600">Total: <strong>0 MB</strong></span>
                    </div>

                    <!-- Validation Messages -->
                    <div id="fileValidationMessage" class="text-sm mt-2"></div>
                    
                    @error('files')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('files.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea name="keterangan" 
                              id="keterangan"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                              placeholder="Tambahkan keterangan tentang peta geotagging ini...">{{ old('keterangan', $petaGeotagging->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <a href="{{ route('kelompok.peta-geotagging.show', $petaGeotagging) }}" 
                       class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-center transition-all">
                        Batal
                    </a>
                    <button type="submit" 
                            id="submitBtn"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-all">
                        Update Peta Geotagging
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const addFileBtn = document.getElementById('addFileBtn');
    const fileListContainer = document.getElementById('fileListContainer');
    const fileCount = document.getElementById('fileCount');
    const totalSizeEl = document.getElementById('totalSize');
    const submitBtn = document.getElementById('submitBtn');
    const fileValidationMessage = document.getElementById('fileValidationMessage');
    const form = document.getElementById('uploadForm');

    let selectedFiles = [];
    const MAX_FILES = 5;
    const MAX_TOTAL_SIZE = 20 * 1024 * 1024; // 20MB in bytes

    // Click button to select file
    addFileBtn.addEventListener('click', () => {
        if (selectedFiles.length >= MAX_FILES) {
            showValidationMessage(`Maksimal ${MAX_FILES} file PDF`, 'error');
            return;
        }
        fileInput.click();
    });

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file type
        if (file.type !== 'application/pdf') {
            showValidationMessage('File harus berupa PDF', 'error');
            fileInput.value = '';
            return;
        }

        // Check if file already exists
        const existingFile = selectedFiles.find(f => f.name === file.name && f.size === file.size);
        if (existingFile) {
            showValidationMessage('File sudah ditambahkan', 'error');
            fileInput.value = '';
            return;
        }

        // Check max files
        if (selectedFiles.length >= MAX_FILES) {
            showValidationMessage(`Maksimal ${MAX_FILES} file PDF`, 'error');
            fileInput.value = '';
            return;
        }

        // Add file to array
        selectedFiles.push(file);
        
        // Reset file input
        fileInput.value = '';

        // Update display
        updateFileList();
        updateFileInfo();
        validateForm();
    });

    function updateFileList() {
        fileListContainer.innerHTML = selectedFiles.map((file, index) => `
            <div class="flex items-center justify-between bg-blue-50 p-4 rounded-lg border border-blue-200" data-index="${index}">
                <div class="flex items-center gap-3 flex-1">
                    <svg class="w-8 h-8 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                        <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                    </div>
                </div>
                <button type="button" 
                        onclick="removeFile(${index})"
                        class="ml-3 text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-lg transition-colors"
                        title="Hapus file">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        `).join('');
    }

    function updateFileInfo() {
        const count = selectedFiles.length;
        const totalSize = selectedFiles.reduce((sum, file) => sum + file.size, 0);
        
        fileCount.textContent = count;
        totalSizeEl.innerHTML = `Total: <strong>${formatFileSize(totalSize)}</strong>`;

        // Update button state
        if (count >= MAX_FILES) {
            addFileBtn.disabled = true;
            addFileBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            addFileBtn.disabled = false;
            addFileBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    function validateForm() {
        const count = selectedFiles.length;
        const totalSize = selectedFiles.reduce((sum, file) => sum + file.size, 0);

        // Clear previous messages
        fileValidationMessage.innerHTML = '';

        // Validate file count if files are selected
        if (count > 0) {
            if (count > MAX_FILES) {
                showValidationMessage(`Maksimal ${MAX_FILES} file PDF`, 'error');
                submitBtn.disabled = true;
                return false;
            }

            // Validate total size
            if (totalSize > MAX_TOTAL_SIZE) {
                showValidationMessage(`Total ukuran file melebihi ${formatFileSize(MAX_TOTAL_SIZE)}`, 'error');
                submitBtn.disabled = true;
                return false;
            }

            showValidationMessage(`${count} file PDF baru siap diupload`, 'success');
        } else {
            showValidationMessage('Tidak ada file baru yang dipilih. File lama akan tetap digunakan.', 'info');
        }

        submitBtn.disabled = false;
        return true;
    }

    function showValidationMessage(message, type) {
        const colorClass = type === 'error' ? 'text-red-600' : 
                          type === 'success' ? 'text-green-600' : 'text-blue-600';
        fileValidationMessage.innerHTML = `<p class="${colorClass}">${message}</p>`;
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
    }

    // Make removeFile function global
    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updateFileList();
        updateFileInfo();
        validateForm();
    };

    // Form submission
    form.addEventListener('submit', function(e) {
        if (selectedFiles.length > 0) {
            // Create FormData and add files
            const formData = new FormData(form);
            
            // Remove any existing file inputs
            const existingInputs = form.querySelectorAll('input[name="files[]"]');
            existingInputs.forEach(input => input.remove());

            // Add selected files to form
            selectedFiles.forEach((file, index) => {
                const input = document.createElement('input');
                input.type = 'file';
                input.name = 'files[]';
                input.style.display = 'none';
                
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
                
                form.appendChild(input);
            });

            // Show loading state
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Updating...';
            submitBtn.disabled = true;
            addFileBtn.disabled = true;
        }
    });

    // Initial validation
    validateForm();
});
</script>

<style>
@keyframes spin {
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
@endsection