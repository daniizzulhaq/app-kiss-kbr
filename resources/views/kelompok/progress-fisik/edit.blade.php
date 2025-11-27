@extends('layouts.dashboard')

@section('title', 'Update Progress Kegiatan - Sistem KBR')
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Update Progress Kegiatan</h1>
                <p class="text-gray-600 mt-1">Perbarui data progress dan realisasi kegiatan</p>
            </div>
            <a href="{{ route('kelompok.progress-fisik.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-all shadow-lg hover:shadow-xl">
                <span class="text-xl">←</span>
                <span class="font-medium">Kembali</span>
            </a>
        </div>
    </div>

    <!-- Alert Error -->
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-2xl mr-3">❌</span>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Info Kegiatan -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 p-6 mb-6 rounded-lg shadow-md">
        <div class="flex items-start gap-4">
            <span class="text-4xl">📋</span>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-blue-900">{{ $progressFisik->masterKegiatan->nama_kegiatan }}</h3>
                @if($progressFisik->nama_detail)
                    <p class="text-lg text-blue-700 font-semibold mt-1">📝 {{ $progressFisik->nama_detail }}</p>
                @endif
                <div class="flex flex-wrap gap-3 mt-2">
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
            </div>
        </div>
    </div>

    <!-- Status Verifikasi (jika sudah diverifikasi) -->
    @if($progressFisik->status_verifikasi != 'pending')
    <div class="mb-6 p-6 rounded-lg shadow-md border-l-4
                {{ $progressFisik->status_verifikasi == 'disetujui' ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500' }}">
        <div class="flex items-start gap-4">
            <span class="text-3xl">{{ $progressFisik->status_verifikasi == 'disetujui' ? '✅' : '❌' }}</span>
            <div class="flex-1">
                <p class="text-lg font-bold {{ $progressFisik->status_verifikasi == 'disetujui' ? 'text-green-900' : 'text-red-900' }}">
                    Status: {{ $progressFisik->status_verifikasi == 'disetujui' ? 'Disetujui' : 'Ditolak' }}
                </p>
                @if($progressFisik->catatan_verifikasi)
                    <p class="text-sm mt-2 {{ $progressFisik->status_verifikasi == 'disetujui' ? 'text-green-700' : 'text-red-700' }}">
                        <strong>Catatan:</strong> {{ $progressFisik->catatan_verifikasi }}
                    </p>
                @endif
                @if($progressFisik->verified_at)
                    <p class="text-xs mt-1 {{ $progressFisik->status_verifikasi == 'disetujui' ? 'text-green-600' : 'text-red-600' }}">
                        Diverifikasi pada {{ $progressFisik->verified_at->format('d M Y H:i') }}
                    </p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-8">
            <form action="{{ route('kelompok.progress-fisik.update', $progressFisik) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  id="formUpdateProgress">
                @csrf
                @method('PUT')

                <!-- Detail Kegiatan -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2 border-b pb-3">
                        <span>📝</span>
                        Detail Spesifik Kegiatan
                    </h3>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg mb-4">
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
                        <label for="nama_detail" class="block text-sm font-bold text-gray-700 mb-2">
                            Nama Detail Kegiatan
                        </label>
                        <input type="text" 
                               name="nama_detail" 
                               id="nama_detail"
                               value="{{ old('nama_detail', $progressFisik->nama_detail) }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-lg"
                               placeholder="Contoh: Pengadaan Benih Mahoni, Pembuatan Jalan Usaha Tani, dll.">
                        <p class="text-xs text-gray-500 mt-1">Opsional - Kosongkan jika tidak perlu detail tambahan</p>
                        @error('nama_detail')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Target & Biaya -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2 border-b pb-3">
                        <span>💰</span>
                        Target & Anggaran
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Volume Target -->
                        <div>
                            <label for="volume_target" class="block text-sm font-bold text-gray-700 mb-2">
                                Volume Target <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   step="0.01" 
                                   name="volume_target" 
                                   id="volume_target"
                                   value="{{ old('volume_target', $progressFisik->volume_target) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-lg"
                                   required>
                            @error('volume_target')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Biaya Satuan -->
                        <div>
                            <label for="biaya_satuan" class="block text-sm font-bold text-gray-700 mb-2">
                                Biaya Per Satuan (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   step="1" 
                                   name="biaya_satuan" 
                                   id="biaya_satuan"
                                   value="{{ old('biaya_satuan', $progressFisik->biaya_satuan) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-lg"
                                   required>
                            @error('biaya_satuan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Total Biaya Display -->
                    <div class="mt-6 p-6 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border-l-4 border-yellow-500 shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-700 font-medium">Total Biaya Kegiatan</p>
                                <p class="text-4xl font-bold text-yellow-600 mt-2" id="total_biaya_display">
                                    Rp {{ number_format($progressFisik->total_biaya, 0, ',', '.') }}
                                </p>
                            </div>
                            <span class="text-6xl">💵</span>
                        </div>
                    </div>
                </div>

                <!-- Realisasi -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2 border-b pb-3">
                        <span>📊</span>
                        Realisasi Kegiatan
                    </h3>

                    <!-- Volume Realisasi -->
                    <div class="mb-6">
                        <label for="volume_realisasi" class="block text-sm font-bold text-gray-700 mb-2">
                            Volume Realisasi <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               step="0.01" 
                               name="volume_realisasi" 
                               id="volume_realisasi"
                               value="{{ old('volume_realisasi', $progressFisik->volume_realisasi) }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-lg"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Maksimal: {{ number_format($progressFisik->volume_target, 2) }} {{ $progressFisik->masterKegiatan->satuan }}</p>
                        @error('volume_realisasi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Progress Bar -->
                    <div class="p-6 bg-gradient-to-r from-green-50 to-blue-50 rounded-lg shadow-md">
                        <div class="flex justify-between mb-3">
                            <span class="text-sm font-bold text-gray-700">Progress Fisik Kegiatan</span>
                            <span class="text-lg font-bold text-gray-800" id="persentase_display">
                                {{ number_format($progressFisik->persentase_fisik, 1) }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-8 shadow-inner">
                            <div id="progress_bar" 
                                 class="h-8 rounded-full transition-all duration-300 flex items-center justify-end pr-3
                                        {{ $progressFisik->persentase_fisik >= 100 ? 'bg-gradient-to-r from-green-400 to-green-600' : 
                                           ($progressFisik->persentase_fisik >= 50 ? 'bg-gradient-to-r from-blue-400 to-blue-600' : 'bg-gradient-to-r from-yellow-400 to-yellow-600') }}" 
                                 style="width: {{ min($progressFisik->persentase_fisik, 100) }}%">
                                <span class="text-white text-xs font-bold" id="progress_text">
                                    {{ number_format($progressFisik->persentase_fisik, 0) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2 border-b pb-3">
                        <span>📅</span>
                        Jadwal Pelaksanaan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-bold text-gray-700 mb-2">
                                📅 Tanggal Mulai
                            </label>
                            <input type="date" 
                                   name="tanggal_mulai" 
                                   id="tanggal_mulai"
                                   value="{{ old('tanggal_mulai', optional($progressFisik->tanggal_mulai)->format('Y-m-d')) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                            @error('tanggal_mulai')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_selesai" class="block text-sm font-bold text-gray-700 mb-2">
                                🏁 Tanggal Selesai
                            </label>
                            <input type="date" 
                                   name="tanggal_selesai" 
                                   id="tanggal_selesai"
                                   value="{{ old('tanggal_selesai', optional($progressFisik->tanggal_selesai)->format('Y-m-d')) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                            @error('tanggal_selesai')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mb-8">
                    <label for="keterangan" class="block text-sm font-bold text-gray-700 mb-2">
                        📄 Keterangan
                    </label>
                    <textarea name="keterangan" 
                              id="keterangan" 
                              rows="4"
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                              placeholder="Tambahkan catatan atau keterangan tentang progress kegiatan...">{{ old('keterangan', $progressFisik->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dokumentasi Existing -->
                @if($progressFisik->dokumentasi->count() > 0)
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span>📷</span>
                        Dokumentasi Tersimpan
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($progressFisik->dokumentasi as $dok)
                            <div class="relative group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all">
                                <img src="{{ Storage::url($dok->foto) }}" 
                                     alt="Dokumentasi" 
                                     class="w-full h-48 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-4">
                                    <form action="{{ route('kelompok.progress-fisik.delete-foto', $dok) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Yakin hapus foto ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium shadow-lg transition-all">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                                @if($dok->keterangan)
                                    <div class="p-2 bg-gray-50">
                                        <p class="text-xs text-gray-700 line-clamp-2">{{ $dok->keterangan }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

             <!-- Upload Foto Baru -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
        <span>📸</span>
        Tambah Dokumentasi Baru (Opsional)
    </h3>
    
    <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex items-start gap-2">
            <span class="text-lg">ℹ️</span>
            <div class="text-sm text-blue-800">
                <p class="font-semibold mb-1">Informasi Upload Foto:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Upload foto bersifat opsional - Anda bisa update data tanpa menambah foto</li>
                    <li>Format: JPG, JPEG, PNG (Max 2MB per file)</li>
                    <li>Foto yang sudah diupload sebelumnya tidak akan hilang</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Container untuk foto items -->
    <div id="foto-container" class="space-y-4 mb-4"></div>

    <!-- Tombol Tambah -->
    <button type="button" 
            id="btn-tambah-foto" 
            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition-all inline-flex items-center gap-2 cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        <span>Tambah Field Foto</span>
    </button>
    <p class="text-xs text-gray-500 mt-2">Klik tombol di atas untuk menambah field upload foto</p>

    @error('foto.*')
        <div class="mt-3 bg-red-50 border-l-4 border-red-500 p-3 rounded">
            <p class="text-red-700 text-sm font-medium">{{ $message }}</p>
        </div>
    @enderror
</div>

                <!-- Tombol Action -->
                <div class="flex justify-end gap-4 pt-6 border-t">
                    <a href="{{ route('kelompok.progress-fisik.index') }}" 
                       class="px-8 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-all shadow-md hover:shadow-lg">
                        ❌ Batal
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-lg transition-all shadow-lg hover:shadow-xl">
                        ✅ Update Progress
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
console.log('Script loaded');

// Hitung total biaya
function hitungTotalBiaya() {
    const volume = parseFloat(document.getElementById('volume_target').value) || 0;
    const biaya = parseFloat(document.getElementById('biaya_satuan').value) || 0;
    const total = volume * biaya;
    document.getElementById('total_biaya_display').textContent = 
        'Rp ' + new Intl.NumberFormat('id-ID').format(total);
}

// Hitung persentase
function hitungPersentase() {
    const target = parseFloat(document.getElementById('volume_target').value) || 0;
    const realisasi = parseFloat(document.getElementById('volume_realisasi').value) || 0;
    
    if (target > 0) {
        const persentase = (realisasi / target) * 100;
        const persentaseDisplay = Math.min(persentase, 100);
        
        document.getElementById('persentase_display').textContent = persentase.toFixed(1) + '%';
        document.getElementById('progress_text').textContent = Math.round(persentase) + '%';
        
        const progressBar = document.getElementById('progress_bar');
        progressBar.style.width = persentaseDisplay + '%';
        
        if (persentase >= 100) {
            progressBar.className = 'h-8 rounded-full transition-all duration-300 flex items-center justify-end pr-3 bg-gradient-to-r from-green-400 to-green-600';
        } else if (persentase >= 50) {
            progressBar.className = 'h-8 rounded-full transition-all duration-300 flex items-center justify-end pr-3 bg-gradient-to-r from-blue-400 to-blue-600';
        } else {
            progressBar.className = 'h-8 rounded-full transition-all duration-300 flex items-center justify-end pr-3 bg-gradient-to-r from-yellow-400 to-yellow-600';
        }
        
        if (persentaseDisplay < 10) {
            document.getElementById('progress_text').style.display = 'none';
        } else {
            document.getElementById('progress_text').style.display = 'block';
        }
    }
}

// Counter untuk ID unik
let fotoCounter = 0;

// Fungsi tambah foto item
function tambahFotoItem() {
    fotoCounter++;
    console.log('Menambah foto item ke-' + fotoCounter);
    
    const container = document.getElementById('foto-container');
    
    // Buat wrapper div
    const wrapper = document.createElement('div');
    wrapper.className = 'foto-item bg-gradient-to-r from-gray-50 to-gray-100 p-5 rounded-xl border-2 border-blue-300 relative shadow-md';
    wrapper.id = 'foto-item-' + fotoCounter;
    
    // HTML content
    wrapper.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    📷 Pilih Foto
                </label>
                <input type="file" 
                       name="foto[]" 
                       accept="image/jpeg,image/jpg,image/png"
                       onchange="previewImage(this)"
                       class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none p-2">
                <p class="text-xs text-gray-500 mt-1">Max 2MB - JPG, JPEG, PNG</p>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    📝 Keterangan Foto
                </label>
                <input type="text" 
                       name="keterangan_foto[]" 
                       placeholder="Contoh: Pekerjaan pembibitan tahap 1"
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 px-3 py-2">
            </div>
        </div>
        <div class="mt-4 preview-container hidden">
            <p class="text-xs font-semibold text-gray-600 mb-2">Preview:</p>
            <img src="" alt="Preview" class="preview-image w-48 h-48 object-cover rounded-lg border-4 border-green-400 shadow-lg">
        </div>
        <button type="button" 
                onclick="hapusFotoItem('foto-item-${fotoCounter}')"
                class="absolute -top-3 -right-3 bg-red-500 hover:bg-red-600 text-white w-10 h-10 rounded-full font-bold shadow-lg transition-all hover:scale-110 flex items-center justify-center"
                title="Hapus">
            ✕
        </button>
    `;
    
    container.appendChild(wrapper);
    console.log('Foto item ditambahkan');
}

// Fungsi hapus foto item
function hapusFotoItem(itemId) {
    console.log('Menghapus ' + itemId);
    const item = document.getElementById(itemId);
    if (item) {
        item.remove();
        console.log('Item dihapus');
    }
}

// Fungsi preview image
function previewImage(input) {
    const wrapper = input.closest('.foto-item');
    const previewContainer = wrapper.querySelector('.preview-container');
    const previewImage = wrapper.querySelector('.preview-image');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Cleanup input kosong sebelum submit
function cleanupEmptyFotoInputs() {
    const allFotoItems = document.querySelectorAll('.foto-item');
    allFotoItems.forEach(item => {
        const fileInput = item.querySelector('input[type="file"]');
        if (fileInput && (!fileInput.files || fileInput.files.length === 0)) {
            item.remove();
        }
    });
}

// Event saat DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Ready');
    
    // Event listeners untuk kalkulasi
    const volumeTarget = document.getElementById('volume_target');
    const biayaSatuan = document.getElementById('biaya_satuan');
    const volumeRealisasi = document.getElementById('volume_realisasi');
    
    if (volumeTarget) {
        volumeTarget.addEventListener('input', function() {
            hitungTotalBiaya();
            hitungPersentase();
        });
    }
    
    if (biayaSatuan) {
        biayaSatuan.addEventListener('input', hitungTotalBiaya);
    }
    
    if (volumeRealisasi) {
        volumeRealisasi.addEventListener('input', hitungPersentase);
    }
    
    // Event untuk tombol tambah foto
    const btnTambah = document.getElementById('btn-tambah-foto');
    if (btnTambah) {
        console.log('Tombol tambah foto ditemukan');
        btnTambah.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Tombol diklik!');
            tambahFotoItem();
        });
    } else {
        console.error('Tombol tidak ditemukan!');
    }
    
    // Event form submit
    const form = document.getElementById('formUpdateProgress');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submitting...');
            
            // Validasi
            const volumeTargetVal = parseFloat(document.getElementById('volume_target').value);
            const volumeRealisasiVal = parseFloat(document.getElementById('volume_realisasi').value);
            
            if (volumeRealisasiVal > volumeTargetVal) {
                alert('Volume realisasi tidak boleh melebihi volume target!');
                return false;
            }
            
            // Cleanup
            cleanupEmptyFotoInputs();
            
            // Submit
            console.log('Submitting...');
            this.submit();
        });
    }
    
    // Init
    hitungPersentase();
    console.log('Inisialisasi selesai');
});
</script>
@endpush
@endsection