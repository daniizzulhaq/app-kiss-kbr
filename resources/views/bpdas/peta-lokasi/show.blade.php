@extends('layouts.dashboard')

@section('title', 'Verifikasi Peta Lokasi - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="text-2xl">📍</span>
                    Verifikasi Peta Lokasi
                </h1>
                <p class="text-gray-600 mt-2">Detail dan verifikasi peta lokasi dari kelompok</p>
            </div>
            <a href="{{ route('bpdas.peta-lokasi.index') }}" 
               class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg flex items-center justify-center gap-2 transition-all shadow hover:shadow-lg text-sm sm:text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-xl mr-3">✅</span>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-xl mr-3">❌</span>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Detail Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span>📋</span>
                        Detail Peta Lokasi
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Header Info -->
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $petaLokasi->judul }}</h2>
                        <div class="flex flex-wrap items-center gap-4 text-sm">
                            <div class="flex items-center gap-2 text-gray-600">
                                <span>📅</span>
                                <span>{{ $petaLokasi->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                {!! $petaLokasi->status_badge !!}
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <span>📎</span>
                                <span>{{ $petaLokasi->file_count }} File PDF</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-600">
                                <span>💾</span>
                                <span>{{ $petaLokasi->formatted_total_size }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    @if($petaLokasi->keterangan)
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <span>📝</span>
                            Keterangan
                        </h4>
                        <div class="bg-gray-50 p-4 rounded-lg text-gray-700 border border-gray-200">
                            {{ $petaLokasi->keterangan }}
                        </div>
                    </div>
                    @endif

                    <!-- File List -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <span>📁</span>
                            File PDF
                        </h4>
                        <div class="space-y-3">
                            @if(!empty($petaLokasi->files) && is_array($petaLokasi->files))
                                @foreach($petaLokasi->files as $file)
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <div class="flex items-center gap-3 flex-1">
                                        <svg class="w-8 h-8 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
                                       class="ml-3 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-4 text-gray-500">
                                    <p>Tidak ada file tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- PDF Preview -->
                    @if(!empty($petaLokasi->files) && is_array($petaLokasi->files) && count($petaLokasi->files) > 0)
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <span>👁️</span>
                            Preview PDF
                        </h4>
                        <div class="border border-gray-300 rounded-lg overflow-hidden bg-gray-100">
                            <iframe src="{{ Storage::url($petaLokasi->files[0]['path']) }}#toolbar=0" 
                                    class="w-full h-[500px]"
                                    frameborder="0"
                                    loading="lazy"></iframe>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            Preview file pertama. Gunakan tombol download untuk mengunduh semua file.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Info Kelompok Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span>🏢</span>
                        Informasi Kelompok
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-500 block mb-1">Kelompok:</label>
                            <p class="font-semibold text-gray-900">{{ $petaLokasi->kelompok->nama_kelompok }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 block mb-1">Pengelola:</label>
                            <p class="font-semibold text-gray-900">{{ $petaLokasi->user->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 block mb-1">Desa:</label>
                            <p class="text-gray-900">{{ $petaLokasi->kelompok->desa }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 block mb-1">Kecamatan:</label>
                            <p class="text-gray-900">{{ $petaLokasi->kelompok->kecamatan }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 block mb-1">Kabupaten:</label>
                            <p class="text-gray-900">{{ $petaLokasi->kelompok->kabupaten }}</p>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <label class="text-sm text-gray-500 block mb-1">Kontak:</label>
                            <p class="font-medium text-gray-900">{{ $petaLokasi->kelompok->kontak }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Verifikasi -->
            @if($petaLokasi->verified_at)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span>📊</span>
                        Riwayat Verifikasi
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-500 block mb-1">Diverifikasi oleh:</label>
                            <p class="font-semibold text-gray-900">{{ $petaLokasi->verifiedBy->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 block mb-1">Tanggal:</label>
                            <p class="text-gray-900">{{ $petaLokasi->verified_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-500 block mb-1">Status:</label>
                            <div class="mt-1">{!! $petaLokasi->status_badge !!}</div>
                        </div>
                        @if($petaLokasi->catatan_bpdas)
                        <div class="pt-4 border-t border-gray-200">
                            <label class="text-sm text-gray-500 block mb-2">Catatan:</label>
                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-700">{{ $petaLokasi->catatan_bpdas }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Form Verifikasi -->
            @if($petaLokasi->status === 'pending')
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span>✅</span>
                        Verifikasi Peta Lokasi
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('bpdas.peta-lokasi.verifikasi', $petaLokasi) }}" 
                          method="POST"
                          id="verificationForm">
                        @csrf
                        @method('PUT')

                        <!-- Status -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Status Verifikasi <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-green-50 hover:border-green-200 transition-all has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                    <input type="radio" 
                                           name="status" 
                                           value="diterima" 
                                           class="text-green-600 focus:ring-green-500"
                                           required>
                                    <div class="ml-3 flex items-center gap-2">
                                        <span class="text-2xl">✅</span>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Terima</p>
                                            <p class="text-xs text-gray-500">Peta lokasi memenuhi syarat</p>
                                        </div>
                                    </div>
                                </label>
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-red-50 hover:border-red-200 transition-all has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                                    <input type="radio" 
                                           name="status" 
                                           value="ditolak" 
                                           class="text-red-600 focus:ring-red-500"
                                           required>
                                    <div class="ml-3 flex items-center gap-2">
                                        <span class="text-2xl">❌</span>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">Tolak</p>
                                            <p class="text-xs text-gray-500">Peta lokasi tidak memenuhi syarat</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="mb-6">
                            <label for="catatan_bpdas" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan (Opsional)
                            </label>
                            <textarea name="catatan_bpdas" 
                                      id="catatan_bpdas" 
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all"
                                      placeholder="Tambahkan catatan atau alasan verifikasi..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-semibold transition-all shadow hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Verifikasi
                        </button>
                    </form>
                </div>
            </div>
            @else
            <!-- Already Verified -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span>✅</span>
                        Status Verifikasi
                    </h3>
                </div>
                <div class="p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Sudah Diverifikasi</h4>
                    <p class="text-sm text-gray-600 mb-4">
                        Peta lokasi ini sudah diverifikasi pada 
                        {{ $petaLokasi->verified_at->format('d M Y') }}
                    </p>
                    <div class="mt-4">
                        {!! $petaLokasi->status_badge !!}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('verificationForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const status = document.querySelector('input[name="status"]:checked');
            if (!status) {
                e.preventDefault();
                alert('Pilih status verifikasi terlebih dahulu!');
                return;
            }
            
            const action = status.value === 'diterima' ? 'menerima' : 'menolak';
            if (!confirm(`Yakin ingin ${action} peta lokasi ini?`)) {
                e.preventDefault();
            }
        });
    }
});
</script>
@endsection