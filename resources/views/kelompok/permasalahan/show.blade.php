@extends('layouts.dashboard')

@section('content')
<div class="py-6 sm:py-12">
    <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('kelompok.permasalahan.index') }}" 
               class="text-green-600 hover:text-green-700 font-medium flex items-center gap-2 mb-3 sm:mb-4 text-sm sm:text-base">
                <span>←</span> Kembali ke Daftar
            </a>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                <div>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">Detail Laporan Permasalahan</h2>
                    <p class="hidden text-sm sm:text-base text-gray-600 mt-1">ID: #{{ $permasalahan->id }}</p>
                </div>
                <span class="inline-block px-3 sm:px-4 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold {{ $permasalahan->getStatusBadgeClass() }}">
                    {{ $permasalahan->getStatusLabel() }}
                </span>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg">
                <p class="font-medium text-sm sm:text-base">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Detail Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-6">
                <!-- Informasi Umum -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                        <span class="text-xl sm:text-2xl">📋</span>
                        <span>Informasi Umum</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Kelompok</label>
                            <p class="mt-1 text-sm sm:text-base text-gray-900 break-words">
                                {{ $permasalahan->kelompok ?? $permasalahan->kelompokRelasi->nama_kelompok ?? '-' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Sarana Prasarana</label>
                            <p class="mt-1 text-sm sm:text-base text-gray-900 break-words">
                                {{ $permasalahan->sarpras ?? '-' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Bibit</label>
                            <p class="mt-1 text-sm sm:text-base text-gray-900 break-words">
                                {{ $permasalahan->bibit ?? '-' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Lokasi Tanam</label>
                            <p class="mt-1 text-sm sm:text-base text-gray-900 break-words">
                                {{ $permasalahan->lokasi_tanam ?? '-' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 sm:col-span-2">
                            <label class="text-xs sm:text-sm font-medium text-gray-500">Tanggal Laporan</label>
                            <p class="mt-1 text-sm sm:text-base text-gray-900">
                                {{ $permasalahan->created_at->format('d F Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Masalah -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                        <span class="text-xl sm:text-2xl">⚠️</span>
                        <span>Deskripsi Permasalahan</span>
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200">
                        <p class="text-sm sm:text-base text-gray-700 whitespace-pre-wrap break-words">
                            {{ $permasalahan->permasalahan ?? '-' }}
                        </p>
                    </div>
                </div>

                <!-- Solusi dari BPDAS -->
                @if($permasalahan->solusi)
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                        <span class="text-xl sm:text-2xl">💡</span>
                        <span>Solusi dari BPDAS</span>
                    </h3>
                    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-3 sm:p-4">
                        <p class="text-sm sm:text-base text-gray-900 whitespace-pre-line break-words">
                            {{ $permasalahan->solusi }}
                        </p>

                        @if($permasalahan->ditangani_pada)
                            <div class="mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-green-200 text-xs sm:text-sm text-gray-600 space-y-1">
                                <p>
                                    <strong>Ditangani oleh:</strong> 
                                    <span class="break-words">{{ $permasalahan->penangananBpdas->name ?? 'BPDAS' }}</span>
                                </p>
                                <p>
                                    <strong>Pada:</strong> 
                                    {{ $permasalahan->ditangani_pada->format('d M Y, H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Tanggapan Kelompok (Jika Ada) -->
                @if($permasalahan->tanggapan_kelompok)
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                        <span class="text-xl sm:text-2xl">✅</span>
                        <span>Tanggapan Kelompok</span>
                    </h3>
                    <div class="bg-purple-50 rounded-lg p-3 sm:p-4 border border-purple-200">
                        <p class="text-sm sm:text-base text-gray-700 whitespace-pre-wrap break-words">
                            {{ $permasalahan->tanggapan_kelompok }}
                        </p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="bg-gray-50 px-4 sm:px-6 lg:px-8 py-3 sm:py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 sm:justify-end">
                    @if($permasalahan->status !== 'selesai')
                    <a href="{{ route('kelompok.permasalahan.edit', $permasalahan) }}" 
                       class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 rounded-lg font-medium transition text-sm sm:text-base">
                        Edit Laporan
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection