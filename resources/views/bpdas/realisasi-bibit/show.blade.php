@extends('layouts.dashboard')

@section('title', 'Detail Realisasi Bibit - Sistem KBR')
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Realisasi Bibit</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap realisasi bibit</p>
        </div>
        <a href="{{ route('bpdas.realisasi-bibit.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-all shadow-lg hover:shadow-xl">
            <span class="text-xl">←</span>
            <span class="font-medium">Kembali</span>
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header Card -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-6">
            <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                <span class="text-3xl">🌱</span>
                {{ $realisasiBibit->jenis_bibit }}
            </h2>
            <p class="text-green-100 mt-1">ID Bibit: #{{ $realisasiBibit->id_bibit }}</p>
        </div>

        <!-- Content -->
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Informasi Kelompok -->
                <div class="space-y-6">
                    <div class="border-l-4 border-green-600 pl-4">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="text-2xl">👥</span>
                            Informasi Kelompok
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-40 text-sm font-medium text-gray-600">Nama Kelompok</div>
                                <div class="flex-1">
                                    <span class="text-gray-900 font-semibold">
                                        {{ $realisasiBibit->kelompok->nama_kelompok ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            @if($realisasiBibit->kelompok)
                                <div class="flex items-start">
                                    <div class="w-40 text-sm font-medium text-gray-600">Desa</div>
                                    <div class="flex-1 text-gray-900">
                                        {{ $realisasiBibit->kelompok->desa ?? '-' }}
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="w-40 text-sm font-medium text-gray-600">Kecamatan</div>
                                    <div class="flex-1 text-gray-900">
                                        {{ $realisasiBibit->kelompok->kecamatan ?? '-' }}
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="w-40 text-sm font-medium text-gray-600">Kabupaten</div>
                                    <div class="flex-1 text-gray-900">
                                        {{ $realisasiBibit->kelompok->kabupaten ?? '-' }}
                                    </div>
                                </div>

                                @if($realisasiBibit->kelompok->ketua)
                                <div class="flex items-start">
                                    <div class="w-40 text-sm font-medium text-gray-600">Ketua Kelompok</div>
                                    <div class="flex-1 text-gray-900">
                                        {{ $realisasiBibit->kelompok->ketua }}
                                    </div>
                                </div>
                                @endif

                                @if($realisasiBibit->kelompok->no_hp)
                                <div class="flex items-start">
                                    <div class="w-40 text-sm font-medium text-gray-600">No. HP</div>
                                    <div class="flex-1 text-gray-900">
                                        {{ $realisasiBibit->kelompok->no_hp }}
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informasi Bibit -->
                <div class="space-y-6">
                    <div class="border-l-4 border-blue-600 pl-4">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="text-2xl">🌿</span>
                            Informasi Bibit
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-40 text-sm font-medium text-gray-600">Jenis Bibit</div>
                                <div class="flex-1">
                                    <span class="text-green-700 font-bold text-lg">
                                        {{ $realisasiBibit->jenis_bibit }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-40 text-sm font-medium text-gray-600">Golongan</div>
                                <div class="flex-1">
                                    <span class="px-4 py-1.5 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $realisasiBibit->golongan ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-40 text-sm font-medium text-gray-600">Jumlah</div>
                                <div class="flex-1">
                                    <span class="text-green-600 font-bold text-2xl">
                                        {{ number_format($realisasiBibit->jumlah_btg) }}
                                    </span>
                                    <span class="text-gray-600 text-sm ml-2">Batang</span>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-40 text-sm font-medium text-gray-600">Tinggi</div>
                                <div class="flex-1">
                                    @if($realisasiBibit->tinggi)
                                        <span class="text-gray-900 font-semibold text-lg">
                                            {{ number_format($realisasiBibit->tinggi, 2) }}
                                        </span>
                                        <span class="text-gray-600 text-sm ml-1">cm</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </div>

                            @if(isset($realisasiBibit->sertifikat))
                            <div class="flex items-start">
                                <div class="w-40 text-sm font-medium text-gray-600">Sertifikat</div>
                                <div class="flex-1">
                                    @if($realisasiBibit->sertifikat)
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                            ✓ Bersertifikat
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Non-Sertifikat
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <!-- Informasi Waktu -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <span class="text-xl">📅</span>
                        <div>
                            <div class="font-medium">Tanggal Input</div>
                            <div class="text-gray-900">{{ $realisasiBibit->created_at->format('d F Y, H:i') }} WIB</div>
                        </div>
                    </div>
                    
                    @if($realisasiBibit->updated_at != $realisasiBibit->created_at)
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <span class="text-xl">🔄</span>
                        <div>
                            <div class="font-medium">Terakhir Diupdate</div>
                            <div class="text-gray-900">{{ $realisasiBibit->updated_at->format('d F Y, H:i') }} WIB</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer Card dengan Action Buttons -->
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex justify-between items-center">
            <a href="{{ route('bpdas.realisasi-bibit.index') }}" 
               class="text-gray-600 hover:text-gray-800 font-medium flex items-center gap-2">
                <span>←</span>
                Kembali ke Daftar
            </a>
            
            <div class="flex gap-3">
                <!-- Tombol Print (Opsional) -->
                <button onclick="window.print()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-all flex items-center gap-2">
                    <span>🖨️</span>
                    Print
                </button>
            </div>
        </div>
    </div>

    <!-- Card Statistik Tambahan (Opsional) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <!-- Card 1 -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Bibit</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($realisasiBibit->jumlah_btg) }}</p>
                    <p class="text-green-100 text-xs mt-1">Batang</p>
                </div>
                <div class="text-5xl opacity-50">🌱</div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Tinggi Rata-rata</p>
                    <p class="text-3xl font-bold mt-1">
                        {{ $realisasiBibit->tinggi ? number_format($realisasiBibit->tinggi, 2) : '-' }}
                    </p>
                    <p class="text-blue-100 text-xs mt-1">Centimeter</p>
                </div>
                <div class="text-5xl opacity-50">📏</div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Golongan</p>
                    <p class="text-2xl font-bold mt-1">{{ $realisasiBibit->golongan ?? '-' }}</p>
                    <p class="text-purple-100 text-xs mt-1">Kategori Tumbuh</p>
                </div>
                <div class="text-5xl opacity-50">📊</div>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        body {
            background: white;
        }
        .container {
            max-width: 100%;
        }
    }
</style>
@endsection