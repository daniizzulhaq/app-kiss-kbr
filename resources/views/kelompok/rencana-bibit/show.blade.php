@extends('layouts.dashboard')

@section('title', 'Detail Rencana Bibit - Sistem KBR')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 slide-in">
        <a href="{{ route('kelompok.rencana-bibit.index') }}" 
           class="inline-flex items-center text-green-600 hover:text-green-700 mb-4 font-medium">
            ← Kembali ke Daftar
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">🌱 Detail Rencana Bibit</h1>
                <p class="text-gray-600">Informasi lengkap rencana kebutuhan bibit</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('kelompok.rencana-bibit.edit', $rencanaBibit) }}" 
                   class="px-5 py-2.5 bg-amber-100 text-amber-700 rounded-xl hover:bg-amber-200 transition-all duration-300 font-semibold">
                    ✏️ Edit
                </a>
                <form action="{{ route('kelompok.rencana-bibit.destroy', $rencanaBibit) }}" 
                      method="POST" 
                      onsubmit="return confirm('Yakin ingin menghapus rencana bibit ini?')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-5 py-2.5 bg-red-100 text-red-700 rounded-xl hover:bg-red-200 transition-all duration-300 font-semibold">
                        🗑️ Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Detail Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden slide-in">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-1">{{ $rencanaBibit->jenis_bibit }}</h2>
                    <p class="text-green-100">ID Bibit: #{{ $rencanaBibit->id_bibit }}</p>
                </div>
                <div class="text-right">
                    {!! $rencanaBibit->golongan_badge !!}
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="p-8">
            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Kelompok -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-2xl mr-4">
                            👥
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Kelompok</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $rencanaBibit->kelompok->nama_kelompok ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Golongan -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-2xl mr-4">
                            🏷️
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Golongan Bibit</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $rencanaBibit->golongan }}</p>
                        </div>
                    </div>
                </div>

                <!-- Jumlah Batang -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center text-2xl mr-4">
                            🌳
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Jumlah Batang</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $rencanaBibit->jumlah_format }} Batang</p>
                        </div>
                    </div>
                </div>

                <!-- Tinggi -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-2xl mr-4">
                            📏
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tinggi Bibit</p>
                            <p class="text-lg font-semibold text-gray-800">{{ $rencanaBibit->tinggi_format }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sertifikat Section -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border-2 border-blue-100">
                <div class="flex items-start">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-2xl mr-4">
                        📜
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600 mb-2">Status Sertifikat</p>
                        <div class="flex items-center justify-between">
                            <div>
                                {!! $rencanaBibit->sertifikat_badge !!}
                                @if($rencanaBibit->sertifikat)
                                <p class="text-sm text-gray-700 mt-2">
                                    <span class="font-semibold">Nomor/Jenis:</span> {{ $rencanaBibit->sertifikat }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timestamp -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <span class="font-semibold">Dibuat:</span> 
                        {{ $rencanaBibit->created_at->format('d F Y, H:i') }} WIB
                    </div>
                    <div class="md:text-right">
                        <span class="font-semibold">Terakhir diupdate:</span> 
                        {{ $rencanaBibit->updated_at->format('d F Y, H:i') }} WIB
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection