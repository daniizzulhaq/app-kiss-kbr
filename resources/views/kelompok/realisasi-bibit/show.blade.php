@extends('layouts.dashboard')

@section('title', 'Detail Realisasi Bibit - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8 max-w-4xl">
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('kelompok.realisasi-bibit.index') }}" 
           class="text-green-600 hover:text-green-800 font-medium flex items-center gap-2 mb-4 transition-colors text-sm sm:text-base">
            <span>←</span> Kembali ke Daftar
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Detail Realisasi Bibit</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-1">Informasi lengkap data realisasi bibit</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header Card dengan Gradient -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white">{{ $realisasiBibit->jenis_bibit }}</h2>
                    <p class="text-sm sm:text-base text-green-100 mt-1">ID: #{{ $realisasiBibit->id_bibit }}</p>
                </div>
                <div class="sm:text-right">
                    @if($realisasiBibit->golongan)
                    <span class="inline-block px-3 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-semibold rounded-full bg-white text-green-700">
                        {{ $realisasiBibit->golongan }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <!-- Info Kelompok & Pemilik -->
            <div class="mb-6 sm:mb-8 pb-4 sm:pb-6 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">👥</span>
                    <span>Informasi Kelompok</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                        <p class="text-xs sm:text-sm text-gray-600 font-medium mb-1">Nama Kelompok</p>
                        <p class="text-base sm:text-lg font-bold text-gray-800 break-words">{{ $realisasiBibit->kelompok->nama_kelompok ?? '-' }}</p>
                    </div>
                    <div class="bg-purple-50 p-3 sm:p-4 rounded-lg">
                        <p class="text-xs sm:text-sm text-gray-600 font-medium mb-1">Dibuat Oleh</p>
                        <p class="text-base sm:text-lg font-bold text-gray-800 break-words">{{ $realisasiBibit->kelompok->user->name ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Bibit -->
            <div class="mb-6 sm:mb-8">
                <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">🌱</span>
                    <span>Detail Bibit</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Jenis Bibit -->
                    <div class="flex items-start gap-3 sm:gap-4 p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-xl sm:text-2xl">🌿</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 font-medium">Jenis Bibit</p>
                            <p class="text-base sm:text-lg font-bold text-gray-800 mt-1 break-words">{{ $realisasiBibit->jenis_bibit }}</p>
                        </div>
                    </div>

                    <!-- Golongan -->
                    <div class="flex items-start gap-3 sm:gap-4 p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-xl sm:text-2xl">📋</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 font-medium">Golongan</p>
                            <p class="text-base sm:text-lg font-bold text-gray-800 mt-1">{{ $realisasiBibit->golongan ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Jumlah Batang -->
                    <div class="flex items-start gap-3 sm:gap-4 p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <span class="text-xl sm:text-2xl">🌳</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 font-medium">Jumlah Batang</p>
                            <p class="text-base sm:text-lg font-bold text-green-600 mt-1">{{ number_format($realisasiBibit->jumlah_btg) }} Batang</p>
                        </div>
                    </div>

                    <!-- Tinggi -->
                    <div class="flex items-start gap-3 sm:gap-4 p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="text-xl sm:text-2xl">📏</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 font-medium">Tinggi Rata-rata</p>
                            <p class="text-base sm:text-lg font-bold text-gray-800 mt-1">
                                {{ $realisasiBibit->tinggi ? number_format($realisasiBibit->tinggi, 2) . ' cm' : '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Sertifikat -->
                    <div class="flex items-start gap-3 sm:gap-4 p-3 sm:p-4 bg-gray-50 rounded-lg sm:col-span-2">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <span class="text-xl sm:text-2xl">📜</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm text-gray-600 font-medium">Nomor Sertifikat</p>
                            <p class="text-base sm:text-lg font-bold text-gray-800 mt-1 break-words">{{ $realisasiBibit->sertifikat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timestamp Info -->
            <div class="pt-4 sm:pt-6 border-t border-gray-200">
                <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                    <span class="text-lg sm:text-xl">⏰</span>
                    <span>Informasi Waktu</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div class="flex items-center gap-3 p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <span class="text-xl sm:text-2xl flex-shrink-0">📅</span>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 font-medium">Dibuat Pada</p>
                            <p class="text-xs sm:text-sm font-bold text-gray-800 mt-1">
                                {{ $realisasiBibit->created_at ? $realisasiBibit->created_at->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 sm:p-4 bg-gray-50 rounded-lg">
                        <span class="text-xl sm:text-2xl flex-shrink-0">🔄</span>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 font-medium">Terakhir Diupdate</p>
                            <p class="text-xs sm:text-sm font-bold text-gray-800 mt-1">
                                {{ $realisasiBibit->updated_at ? $realisasiBibit->updated_at->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:justify-end">
                @php
                    $userKelompok = \App\Models\Kelompok::where('user_id', auth()->id())->first();
                    $isOwner = $userKelompok && $realisasiBibit->id_kelompok == $userKelompok->id;
                @endphp

                <a href="{{ route('kelompok.realisasi-bibit.index') }}" 
                   class="w-full sm:w-auto text-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition-all shadow-md hover:shadow-lg text-sm sm:text-base">
                    ← Kembali
                </a>

                @if($isOwner)
                    <a href="{{ route('kelompok.realisasi-bibit.edit', $realisasiBibit->id_bibit) }}" 
                       class="w-full sm:w-auto text-center px-4 sm:px-6 py-2.5 sm:py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg text-sm sm:text-base">
                        ✏️ Edit Data
                    </a>

                    <form action="{{ route('kelompok.realisasi-bibit.destroy', $realisasiBibit->id_bibit) }}" 
                          method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                          class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 sm:px-6 py-2.5 sm:py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg text-sm sm:text-base">
                            🗑️ Hapus Data
                        </button>
                    </form>
                @else
                    <div class="w-full sm:w-auto text-center px-4 sm:px-6 py-2.5 sm:py-3 bg-blue-100 text-blue-800 font-semibold rounded-lg border border-blue-300 text-xs sm:text-sm">
                        ℹ️ Data ini dibuat oleh anggota kelompok lain
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Additional Info Card -->
    <div class="mt-4 sm:mt-6 bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-xl p-4 sm:p-6">
        <div class="flex items-start gap-3 sm:gap-4">
            <span class="text-2xl sm:text-3xl flex-shrink-0">💡</span>
            <div class="min-w-0 flex-1">
                <h4 class="text-sm sm:text-base font-bold text-gray-800 mb-2">Informasi</h4>
                <p class="text-xs sm:text-sm text-gray-700">
                    Data ini merupakan bagian dari realisasi bibit kelompok <strong>{{ $realisasiBibit->kelompok->nama_kelompok ?? '-' }}</strong>.
                    @if($isOwner)
                        Anda dapat mengedit atau menghapus data ini karena Anda adalah pembuatnya.
                    @else
                        Anda hanya dapat melihat data ini karena dibuat oleh anggota kelompok lain.
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection