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
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                        Tambah Dokumentasi Baru
                    </h3>
                    
                    <div id="foto-container" class="space-y-4">
                        <div class="foto-item bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Foto</label>
                                    <input type="file" 
                                           name="foto[]" 
                                           accept="image/*"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Foto</label>
                                    <input type="text" 
                                           name="keterangan_foto[]" 
                                           placeholder="Contoh: Pekerjaan pembibitan tahap 1"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" 
                            id="tambah-foto" 
                            class="mt-4 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all">
                        ➕ Tambah Foto Lainnya
                    </button>

                    @error('foto.*')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
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
    // Hitung total biaya otomatis
    function hitungTotalBiaya() {
        const volume = parseFloat(document.getElementById('volume_target').value) || 0;
        const biaya = parseFloat(document.getElementById('biaya_satuan').value) || 0;
        const total = volume * biaya;
        
        document.getElementById('total_biaya_display').textContent = 
            'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    // Hitung persentase progress
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
            
            // Ubah warna dan gradient berdasarkan persentase
            if (persentase >= 100) {
                progressBar.className = 'h-8 rounded-full transition-all duration-300 flex items-center justify-end pr-3 bg-gradient-to-r from-green-400 to-green-600';
            } else if (persentase >= 50) {
                progressBar.className = 'h-8 rounded-full transition-all duration-300 flex items-center justify-end pr-3 bg-gradient-to-r from-blue-400 to-blue-600';
            } else {
                progressBar.className = 'h-8 rounded-full transition-all duration-300 flex items-center justify-end pr-3 bg-gradient-to-r from-yellow-400 to-yellow-600';
            }
            
            // Sembunyikan teks jika progress terlalu kecil
            if (persentaseDisplay < 10) {
                document.getElementById('progress_text').style.display = 'none';
            } else {
                document.getElementById('progress_text').style.display = 'block';
            }
        }
    }

    document.getElementById('volume_target').addEventListener('input', () => {
        hitungTotalBiaya();
        hitungPersentase();
    });
    document.getElementById('biaya_satuan').addEventListener('input', hitungTotalBiaya);
    document.getElementById('volume_realisasi').addEventListener('input', hitungPersentase);

    // Tambah input foto
    document.getElementById('tambah-foto').addEventListener('click', function() {
        const container = document.getElementById('foto-container');
        const newItem = document.querySelector('.foto-item').cloneNode(true);
        newItem.querySelectorAll('input').forEach(input => input.value = '');
        container.appendChild(newItem);
    });

    // Inisialisasi perhitungan saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        hitungPersentase();
    });
</script>
@endpush
@endsection