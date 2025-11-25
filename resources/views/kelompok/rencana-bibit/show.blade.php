@extends('layouts.dashboard')

@section('title', 'Detail Rencana Bibit - Sistem KBR')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8 slide-in">
        <a href="{{ route('kelompok.rencana-bibit.index') }}" 
           class="inline-flex items-center text-green-600 hover:text-green-700 mb-4 font-medium text-sm sm:text-base">
            ← Kembali ke Daftar
        </a>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-2">🌱 Detail Rencana Bibit</h1>
                <p class="text-sm sm:text-base text-gray-600">Informasi lengkap rencana kebutuhan bibit</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <a href="{{ route('kelompok.rencana-bibit.edit', $rencanaBibit) }}" 
                   class="w-full sm:w-auto text-center px-4 sm:px-5 py-2 sm:py-2.5 bg-amber-100 text-amber-700 rounded-lg sm:rounded-xl hover:bg-amber-200 transition-all duration-300 font-semibold text-sm sm:text-base">
                    ✏️ Edit
                </a>
                <form action="{{ route('kelompok.rencana-bibit.destroy', $rencanaBibit) }}" 
                      method="POST" 
                      onsubmit="return confirm('Yakin ingin menghapus rencana bibit ini?')"
                      class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 sm:px-5 py-2 sm:py-2.5 bg-red-100 text-red-700 rounded-lg sm:rounded-xl hover:bg-red-200 transition-all duration-300 font-semibold text-sm sm:text-base">
                        🗑️ Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden slide-in">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white mb-1">{{ $rencanaBibit->jenis_bibit }}</h2>
                    <p class="text-sm sm:text-base text-green-100">ID Bibit: #{{ $rencanaBibit->id_bibit }}</p>
                </div>
                <div class="sm:text-right">
                    {!! $rencanaBibit->golongan_badge !!}
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-4 sm:p-6 lg:p-8">
            <!-- Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                <!-- Kelompok -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl mr-3 sm:mr-4 flex-shrink-0">
                            👥
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Kelompok</p>
                            <p class="text-base sm:text-lg font-semibold text-gray-800 break-words">{{ $rencanaBibit->kelompok->nama_kelompok ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Golongan -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl mr-3 sm:mr-4 flex-shrink-0">
                            🏷️
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Golongan Bibit</p>
                            <p class="text-base sm:text-lg font-semibold text-gray-800">{{ $rencanaBibit->golongan }}</p>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Batang -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl mr-3 sm:mr-4 flex-shrink-0">
                            🌳
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Jumlah Batang</p>
                            <p class="text-base sm:text-lg font-semibold text-gray-800">{{ $rencanaBibit->jumlah_format }} Batang</p>
                        </div>
                    </div>
                </div>

                <!-- Tinggi -->
                <div class="bg-gray-50 rounded-lg sm:rounded-xl p-4 sm:p-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl mr-3 sm:mr-4 flex-shrink-0">
                            📏
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Tinggi Bibit</p>
                            <p class="text-base sm:text-lg font-semibold text-gray-800">{{ $rencanaBibit->tinggi_format }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sertifikat Section -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg sm:rounded-xl p-4 sm:p-6 border-2 border-blue-100">
                <div class="flex items-start">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center text-xl sm:text-2xl mr-3 sm:mr-4 flex-shrink-0">
                        📜
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-gray-600 mb-2">Status Sertifikat</p>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div class="min-w-0">
                                {!! $rencanaBibit->sertifikat_badge !!}
                                @if($rencanaBibit->sertifikat)
                                <p class="text-xs sm:text-sm text-gray-700 mt-2 break-words">
                                    <span class="font-semibold">Nomor/Jenis:</span> {{ $rencanaBibit->sertifikat }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timestamp -->
            <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm text-gray-600">
                    <div>
                        <span class="font-semibold">Dibuat:</span> 
                        <span class="block sm:inline mt-1 sm:mt-0">{{ $rencanaBibit->created_at->format('d F Y, H:i') }} WIB</span>
                    </div>
                    <div class="sm:text-right">
                        <span class="font-semibold">Terakhir diupdate:</span> 
                        <span class="block sm:inline mt-1 sm:mt-0">{{ $rencanaBibit->updated_at->format('d F Y, H:i') }} WIB</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection