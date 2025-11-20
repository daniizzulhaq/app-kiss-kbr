@extends('layouts.dashboard')

@section('title', 'Detail Realisasi Bibit - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('kelompok.realisasi-bibit.index') }}" 
           class="text-green-600 hover:text-green-800 font-medium flex items-center gap-2 mb-4 transition-colors">
            <span>←</span> Kembali ke Daftar
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Detail Realisasi Bibit</h1>
        <p class="text-gray-600 mt-1">Informasi lengkap data realisasi bibit</p>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header Card dengan Gradient -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $realisasiBibit->jenis_bibit }}</h2>
                    <p class="text-green-100 mt-1">ID: #{{ $realisasiBibit->id_bibit }}</p>
                </div>
                <div class="text-right">
                    @if($realisasiBibit->golongan)
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-white text-green-700">
                        {{ $realisasiBibit->golongan }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="px-8 py-6">
            <!-- Info Kelompok & Pemilik -->
            <div class="mb-8 pb-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span>👥</span>
                    <span>Informasi Kelompok</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 font-medium mb-1">Nama Kelompok</p>
                        <p class="text-lg font-bold text-gray-800">{{ $realisasiBibit->kelompok->nama_kelompok ?? '-' }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 font-medium mb-1">Dibuat Oleh</p>
                        <p class="text-lg font-bold text-gray-800">{{ $realisasiBibit->kelompok->user->name ?? 'Unknown' }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Bibit -->
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span>🌱</span>
                    <span>Detail Bibit</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jenis Bibit -->
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">🌿</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 font-medium">Jenis Bibit</p>
                            <p class="text-lg font-bold text-gray-800 mt-1">{{ $realisasiBibit->jenis_bibit }}</p>
                        </div>
                    </div>

                    <!-- Golongan -->
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">📋</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 font-medium">Golongan</p>
                            <p class="text-lg font-bold text-gray-800 mt-1">{{ $realisasiBibit->golongan ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Jumlah Batang -->
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">🌳</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 font-medium">Jumlah Batang</p>
                            <p class="text-lg font-bold text-green-600 mt-1">{{ number_format($realisasiBibit->jumlah_btg) }} Batang</p>
                        </div>
                    </div>

                    <!-- Tinggi -->
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">📏</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 font-medium">Tinggi Rata-rata</p>
                            <p class="text-lg font-bold text-gray-800 mt-1">
                                {{ $realisasiBibit->tinggi ? number_format($realisasiBibit->tinggi, 2) . ' cm' : '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Sertifikat -->
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg md:col-span-2">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">📜</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 font-medium">Nomor Sertifikat</p>
                            <p class="text-lg font-bold text-gray-800 mt-1">{{ $realisasiBibit->sertifikat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timestamp Info -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span>⏰</span>
                    <span>Informasi Waktu</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                        <span class="text-2xl">📅</span>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Dibuat Pada</p>
                            <p class="text-sm font-bold text-gray-800 mt-1">
                                {{ $realisasiBibit->created_at ? $realisasiBibit->created_at->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
                        <span class="text-2xl">🔄</span>
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Terakhir Diupdate</p>
                            <p class="text-sm font-bold text-gray-800 mt-1">
                                {{ $realisasiBibit->updated_at ? $realisasiBibit->updated_at->format('d M Y, H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-wrap gap-3 justify-end">
                @php
                    $userKelompok = \App\Models\Kelompok::where('user_id', auth()->id())->first();
                    $isOwner = $userKelompok && $realisasiBibit->id_kelompok == $userKelompok->id;
                @endphp

                <a href="{{ route('kelompok.realisasi-bibit.index') }}" 
                   class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                    ← Kembali
                </a>

                @if($isOwner)
                    <a href="{{ route('kelompok.realisasi-bibit.edit', $realisasiBibit->id_bibit) }}" 
                       class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                        ✏️ Edit Data
                    </a>

                    <form action="{{ route('kelompok.realisasi-bibit.destroy', $realisasiBibit->id_bibit) }}" 
                          method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg">
                            🗑️ Hapus Data
                        </button>
                    </form>
                @else
                    <div class="px-6 py-3 bg-blue-100 text-blue-800 font-semibold rounded-lg border border-blue-300">
                        ℹ️ Data ini dibuat oleh anggota kelompok lain
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Additional Info Card -->
    <div class="mt-6 bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-xl p-6">
        <div class="flex items-start gap-4">
            <span class="text-3xl">💡</span>
            <div>
                <h4 class="font-bold text-gray-800 mb-2">Informasi</h4>
                <p class="text-sm text-gray-700">
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