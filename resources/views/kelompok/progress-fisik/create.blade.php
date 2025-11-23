@extends('layouts.dashboard')

@section('title', 'Tambah Kegiatan Progress Fisik - Sistem KBR')
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Tambah Kegiatan Progress Fisik</h1>
                <p class="text-gray-600 mt-1">Pilih dan tambahkan kegiatan baru untuk kelompok Anda</p>
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

    <!-- Statistik Kegiatan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg border-l-4 border-blue-500 shadow">
            <p class="text-sm text-gray-600 font-medium">Total Kegiatan</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $totalKegiatan }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg border-l-4 border-green-500 shadow">
            <p class="text-sm text-gray-600 font-medium">Sudah Ditambahkan</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $totalTambahkan }}</p>
        </div>
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-4 rounded-lg border-l-4 border-yellow-500 shadow">
            <p class="text-sm text-gray-600 font-medium">Kegiatan Tersedia</p>
            <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $sisaKegiatan }}</p>
        </div>
    </div>

    <!-- Info Anggaran -->
    <div class="bg-gradient-to-r from-blue-50 to-green-50 border-l-4 border-blue-500 p-6 mb-6 rounded-lg shadow-md">
        <div class="flex items-center gap-4">
            <span class="text-4xl">💰</span>
            <div class="flex-1">
                <p class="text-sm text-gray-700 font-medium">Sisa Anggaran Tersedia</p>
                <p class="text-3xl font-bold text-blue-600 mt-1">
                    Rp {{ number_format($anggaran->sisa_anggaran, 0, ',', '.') }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-600">Total Anggaran</p>
                <p class="text-lg font-semibold text-gray-700">
                    Rp {{ number_format($anggaran->total_anggaran, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Alert jika semua kegiatan sudah ditambahkan -->
    @if($sisaKegiatan == 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 mb-6 rounded-lg shadow">
        <div class="flex items-center gap-3">
            <span class="text-3xl">⚠️</span>
            <div>
                <p class="font-bold text-yellow-800">Semua kegiatan sudah ditambahkan!</p>
                <p class="text-sm text-yellow-700 mt-1">Anda telah menambahkan semua kegiatan yang tersedia. Silakan kelola kegiatan yang sudah ada di halaman utama.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-8">
            <form action="{{ route('kelompok.progress-fisik.store') }}" method="POST">
                @csrf

                <!-- Pilih Kegiatan per Kategori -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span>📋</span>
                        Pilih Kegiatan
                    </h3>
                    
                    @foreach($kategoriList as $kategori)
                        @php
                            $kegiatanTersedia = $kategori->masterKegiatan->filter(function($k) use ($existingKegiatan) {
                                return !in_array($k->id, $existingKegiatan);
                            });
                            $kegiatanDitambahkan = $kategori->masterKegiatan->filter(function($k) use ($existingKegiatan) {
                                return in_array($k->id, $existingKegiatan);
                            });
                        @endphp

                        <div class="mb-8 border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-bold text-white">
                                            {{ $kategori->kode }}. {{ $kategori->nama }}
                                        </h4>
                                        @if($kategori->deskripsi)
                                            <p class="text-sm text-green-100 mt-1">{{ $kategori->deskripsi }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-green-200">Kegiatan</p>
                                        <p class="text-lg font-bold text-white">
                                            {{ $kegiatanTersedia->count() }} / {{ $kategori->masterKegiatan->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-50">
                                <!-- Kegiatan yang masih tersedia -->
                                @if($kegiatanTersedia->count() > 0)
                                    <div class="mb-4">
                                        <p class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                                            Kegiatan Tersedia ({{ $kegiatanTersedia->count() }})
                                        </p>
                                        <div class="space-y-2">
                                            @foreach($kegiatanTersedia as $kegiatan)
                                                <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-md transition-all cursor-pointer border-2 border-transparent hover:border-green-400">
                                                    <label class="flex items-start gap-4 cursor-pointer">
                                                        <input type="radio" 
                                                               name="master_kegiatan_id" 
                                                               value="{{ $kegiatan->id }}" 
                                                               id="kegiatan_{{ $kegiatan->id }}"
                                                               class="mt-1 text-green-600 focus:ring-green-500 w-5 h-5"
                                                               {{ old('master_kegiatan_id') == $kegiatan->id ? 'checked' : '' }}>
                                                        
                                                        <div class="flex-1">
                                                            <div class="flex items-start justify-between gap-4">
                                                                <div class="flex-1">
                                                                    <p class="text-sm font-semibold text-gray-900">
                                                                        {{ $kegiatan->nomor }}. {{ $kegiatan->nama_kegiatan }}
                                                                    </p>
                                                                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                            📏 {{ $kegiatan->satuan }}
                                                                        </span>
                                                                        @if($kegiatan->is_honor)
                                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                                💰 Honor/Upah
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Kegiatan yang sudah ditambahkan -->
                                @if($kegiatanDitambahkan->count() > 0)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <p class="text-sm font-semibold text-gray-500 mb-2 flex items-center gap-2">
                                            <span class="w-3 h-3 bg-gray-400 rounded-full"></span>
                                            Sudah Ditambahkan ({{ $kegiatanDitambahkan->count() }})
                                        </p>
                                        <div class="space-y-2">
                                            @foreach($kegiatanDitambahkan as $kegiatan)
                                                <div class="bg-gray-100 p-4 rounded-lg border border-gray-300 opacity-60">
                                                    <div class="flex items-start gap-4">
                                                        <input type="radio" 
                                                               disabled
                                                               class="mt-1 w-5 h-5 cursor-not-allowed">
                                                        
                                                        <div class="flex-1">
                                                            <p class="text-sm font-semibold text-gray-600">
                                                                {{ $kegiatan->nomor }}. {{ $kegiatan->nama_kegiatan }}
                                                            </p>
                                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-600">
                                                                    📏 {{ $kegiatan->satuan }}
                                                                </span>
                                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                                                    ✅ Sudah Ditambahkan
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Info jika semua kegiatan kategori ini sudah ditambahkan -->
                                @if($kegiatanTersedia->count() == 0)
                                    <div class="text-center py-6">
                                        <span class="text-4xl">✅</span>
                                        <p class="text-gray-600 font-medium mt-2">
                                            Semua kegiatan dalam kategori ini sudah ditambahkan
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @error('master_kegiatan_id')
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <p class="text-red-700 text-sm font-medium">{{ $message }}</p>
                    </div>
                @enderror

                <!-- Detail Kegiatan -->
                <div class="border-t pt-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span>📝</span>
                        Detail Kegiatan
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
                                   value="{{ old('volume_target') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-lg"
                                   placeholder="Masukkan volume target"
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
                                   value="{{ old('biaya_satuan') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-lg"
                                   placeholder="Masukkan biaya per satuan"
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
                                <p class="text-4xl font-bold text-yellow-600 mt-2" id="total_biaya_display">Rp 0</p>
                            </div>
                            <span class="text-6xl">💵</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-yellow-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Sisa anggaran setelah kegiatan ini:</span>
                                <span class="font-bold text-gray-800" id="sisa_anggaran_display">
                                    Rp {{ number_format($anggaran->sisa_anggaran, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="mt-6">
                        <label for="tanggal_mulai" class="block text-sm font-bold text-gray-700 mb-2">
                            📅 Tanggal Mulai
                        </label>
                        <input type="date" 
                               name="tanggal_mulai" 
                               id="tanggal_mulai"
                               value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 text-lg">
                        @error('tanggal_mulai')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="mt-6">
                        <label for="keterangan" class="block text-sm font-bold text-gray-700 mb-2">
                            📄 Keterangan
                        </label>
                        <textarea name="keterangan" 
                                  id="keterangan" 
                                  rows="4"
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                                  placeholder="Tambahkan keterangan atau catatan untuk kegiatan ini...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tombol Action -->
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                    <a href="{{ route('kelompok.progress-fisik.index') }}" 
                       class="px-8 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-all shadow-md hover:shadow-lg">
                        ❌ Batal
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-lg transition-all shadow-lg hover:shadow-xl"
                            {{ $sisaKegiatan == 0 ? 'disabled' : '' }}>
                        ✅ Simpan Kegiatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sisaAnggaranAwal = {{ $anggaran->sisa_anggaran }};
    const volumeInput = document.getElementById('volume_target');
    const biayaInput = document.getElementById('biaya_satuan');
    const totalBiayaDisplay = document.getElementById('total_biaya_display');
    const sisaAnggaranDisplay = document.getElementById('sisa_anggaran_display');

    // Fungsi untuk format rupiah
    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }

    // Fungsi hitung total biaya
    function hitungTotalBiaya() {
        const volume = parseFloat(volumeInput.value) || 0;
        const biaya = parseFloat(biayaInput.value) || 0;
        const total = volume * biaya;
        const sisaAnggaran = sisaAnggaranAwal - total;
        
        // Update tampilan total biaya
        totalBiayaDisplay.textContent = formatRupiah(total);
        
        // Update tampilan sisa anggaran
        sisaAnggaranDisplay.textContent = formatRupiah(sisaAnggaran);
        
        // Ubah warna jika melebihi anggaran
        if (sisaAnggaran < 0) {
            sisaAnggaranDisplay.classList.remove('text-gray-800');
            sisaAnggaranDisplay.classList.add('text-red-600', 'font-bold');
            
            // Tampilkan peringatan
            if (!document.getElementById('warning_anggaran')) {
                const warning = document.createElement('div');
                warning.id = 'warning_anggaran';
                warning.className = 'mt-2 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm font-medium';
                warning.innerHTML = '⚠️ Total biaya melebihi sisa anggaran yang tersedia!';
                sisaAnggaranDisplay.parentElement.parentElement.appendChild(warning);
            }
        } else {
            sisaAnggaranDisplay.classList.remove('text-red-600');
            sisaAnggaranDisplay.classList.add('text-gray-800');
            
            // Hapus peringatan jika ada
            const warning = document.getElementById('warning_anggaran');
            if (warning) {
                warning.remove();
            }
        }
    }

    // Event listener untuk input
    volumeInput.addEventListener('input', hitungTotalBiaya);
    volumeInput.addEventListener('change', hitungTotalBiaya);
    biayaInput.addEventListener('input', hitungTotalBiaya);
    biayaInput.addEventListener('change', hitungTotalBiaya);
    
    // Hitung sekali saat load jika ada nilai old
    hitungTotalBiaya();
});
</script>
@endsection