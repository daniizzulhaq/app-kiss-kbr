@extends('layouts.dashboard')

@section('title', 'Setup Anggaran - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <span class="text-6xl mb-4 block">💰</span>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                {{ $anggaran->total_anggaran == 0 ? 'Setup Total Anggaran' : 'Edit Total Anggaran' }}
            </h1>
            <p class="text-gray-600">
                Kelompok: <strong class="text-green-600">{{ auth()->user()->kelompok->nama_kelompok }}</strong>
            </p>
            <p class="text-sm text-gray-500 mt-1">Tahun {{ date('Y') }}</p>
        </div>

        <!-- Alert Messages -->
        @include('components.alert')

        <!-- Info Box -->
        @if($anggaran->total_anggaran == 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
            <div class="flex items-start">
                <span class="text-2xl mr-3">⚠️</span>
                <div class="text-sm text-yellow-800">
                    <p class="font-semibold mb-1">Penting!</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Total anggaran <strong>hanya bisa diinput sekali</strong> di awal tahun</li>
                        <li>Setelah ada kegiatan yang diajukan, anggaran <strong>tidak bisa diubah</strong></li>
                        <li>Pastikan jumlah yang Anda masukkan <strong>sudah benar</strong></li>
                        <li>Minimal anggaran: <strong>Rp 1.000.000</strong></li>
                        <li>Maksimal anggaran: <strong>Rp 1.000.000.000</strong></li>
                    </ul>
                </div>
            </div>
        </div>
        @else
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-lg">
            <div class="flex items-start">
                <span class="text-2xl mr-3">ℹ️</span>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Informasi:</p>
                    <p>Anda dapat mengubah total anggaran <strong>hanya jika belum ada kegiatan yang diajukan</strong>. Jika sudah ada kegiatan, hubungi admin untuk perubahan anggaran.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <form action="{{ route('kelompok.anggaran.store') }}" method="POST" id="anggaranForm">
                @csrf

                <!-- Current Budget (jika sudah ada) -->
                @if($anggaran->total_anggaran > 0)
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold text-gray-700">Total Anggaran Saat Ini:</span>
                        <span class="text-xl font-bold text-green-600">
                            Rp {{ number_format($anggaran->total_anggaran, 0, ',', '.') }}
                        </span>
                    </div>
                    @if($anggaran->anggaran_dialokasikan > 0)
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-sm text-gray-600">Sudah Dialokasikan:</span>
                        <span class="text-sm font-bold text-orange-600">
                            Rp {{ number_format($anggaran->anggaran_dialokasikan, 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Input Total Anggaran - SIMPLE VERSION -->
                <div class="mb-6">
                    <label for="total_anggaran" class="block text-sm font-bold text-gray-700 mb-2">
                        Total Anggaran <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="number" 
                               name="total_anggaran" 
                               id="total_anggaran" 
                               class="w-full pl-12 pr-4 py-3 border-2 border-gray-300 rounded-lg focus:border-green-500 focus:ring-2 focus:ring-green-200 text-lg font-semibold"
                               placeholder="Contoh: 5000000"
                               min="1000000"
                               max="1000000000"
                               step="1"
                               value="{{ old('total_anggaran', $anggaran->total_anggaran > 0 ? $anggaran->total_anggaran : '') }}"
                               required>
                    </div>
                    @error('total_anggaran')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">
                        Masukkan angka tanpa titik atau koma. Contoh: 5000000 untuk 5 juta
                    </p>
                </div>

                <!-- Preview -->
                <div class="mb-6 p-4 bg-green-50 rounded-lg border-2 border-green-200">
                    <h3 class="text-sm font-bold text-green-800 mb-3">📊 Preview Anggaran</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-700">Total Anggaran:</span>
                            <span class="text-sm font-bold text-gray-900" id="preview_total">Rp 0</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-700">Tersedia untuk Dialokasikan:</span>
                            <span class="text-sm font-bold text-green-600" id="preview_tersedia">Rp 0</span>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <span class="text-xl">💾</span>
                        {{ $anggaran->total_anggaran == 0 ? 'Simpan Anggaran' : 'Update Anggaran' }}
                    </button>
                    
                    @if($anggaran->total_anggaran > 0)
                    <a href="{{ route('kelompok.progress-fisik.index') }}" 
                       class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-bold transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <span class="text-xl">❌</span>
                        Batal
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Helper Info -->
        <div class="mt-6 bg-gray-50 border border-gray-200 p-4 rounded-lg">
            <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                <span>💡</span> Tips Menentukan Total Anggaran:
            </h4>
            <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                <li>Hitung total kebutuhan dana untuk semua kegiatan yang akan dilakukan</li>
                <li>Tambahkan buffer 10-15% untuk kebutuhan tak terduga</li>
                <li>Sesuaikan dengan kemampuan keuangan kelompok</li>
                <li>Konsultasikan dengan pengurus kelompok sebelum input</li>
            </ul>
        </div>

        <!-- Contoh Anggaran -->
        <div class="mt-4 bg-blue-50 border border-blue-200 p-4 rounded-lg">
            <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                <span>📝</span> Contoh Format Input:
            </h4>
            <div class="text-sm text-gray-700 space-y-1">
                <p>• Untuk Rp 1.000.000 → ketik: <strong class="text-blue-600">1000000</strong></p>
                <p>• Untuk Rp 5.000.000 → ketik: <strong class="text-blue-600">5000000</strong></p>
                <p>• Untuk Rp 10.000.000 → ketik: <strong class="text-blue-600">10000000</strong></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('total_anggaran');
    const previewTotal = document.getElementById('preview_total');
    const previewTersedia = document.getElementById('preview_tersedia');
    const form = document.getElementById('anggaranForm');

    // Format number untuk display
    function formatRupiah(angka) {
        if (!angka || angka == 0) return 'Rp 0';
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }

    // Update preview saat input berubah
    function updatePreview() {
        const value = parseInt(input.value) || 0;
        previewTotal.textContent = formatRupiah(value);
        previewTersedia.textContent = formatRupiah(value);
    }

    // Event listener
    input.addEventListener('input', updatePreview);
    input.addEventListener('change', updatePreview);

    // Validasi saat submit
    form.addEventListener('submit', function(e) {
        const value = parseInt(input.value) || 0;
        
        console.log('Submit - Nilai:', value); // Debug
        
        if (value < 1000000) {
            e.preventDefault();
            alert('⚠️ Total anggaran minimal Rp 1.000.000');
            input.focus();
            return false;
        }
        
        if (value > 1000000000) {
            e.preventDefault();
            alert('⚠️ Total anggaran maksimal Rp 1.000.000.000');
            input.focus();
            return false;
        }
        
        const confirmed = confirm(
            '⚠️ Apakah Anda yakin dengan total anggaran ' + formatRupiah(value) + '?\n\n' +
            'Setelah ada kegiatan yang diajukan, anggaran tidak dapat diubah!'
        );
        
        if (!confirmed) {
            e.preventDefault();
            return false;
        }
    });

    // Initial update
    updatePreview();
});
</script>
@endpush
@endsection