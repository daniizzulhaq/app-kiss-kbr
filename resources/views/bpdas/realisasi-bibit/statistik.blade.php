@extends('layouts.dashboard')

@section('title', 'Rencana Bibit - Sistem KBR')
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Statistik Realisasi Bibit</h1>
            <p class="text-gray-600 mt-1">Analisis data realisasi bibit dari seluruh kelompok</p>
        </div>
        <a href="{{ route('bpdas.realisasi-bibit.index') }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-all shadow-lg hover:shadow-xl">
            <span>←</span>
            <span class="font-medium">Kembali ke List</span>
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Bibit -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Total Bibit Direalisasikan</p>
                    <h3 class="text-3xl font-bold">{{ number_format($totalKeseluruhan) }}</h3>
                    <p class="text-green-100 text-xs mt-1">batang</p>
                </div>
                <div class="text-5xl opacity-20">🌳</div>
            </div>
        </div>

        <!-- Total Kelompok -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Kelompok Aktif</p>
                    <h3 class="text-3xl font-bold">{{ $totalKelompok }}</h3>
                    <p class="text-blue-100 text-xs mt-1">kelompok</p>
                </div>
                <div class="text-5xl opacity-20">👥</div>
            </div>
        </div>

        <!-- Rata-rata per Kelompok -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Rata-rata per Kelompok</p>
                    <h3 class="text-3xl font-bold">{{ $totalKelompok > 0 ? number_format($totalKeseluruhan / $totalKelompok, 0) : 0 }}</h3>
                    <p class="text-purple-100 text-xs mt-1">batang</p>
                </div>
                <div class="text-5xl opacity-20">📊</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Statistik per Kelompok -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>📈</span> Realisasi per Kelompok
            </h2>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($perKelompok as $index => $item)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="bg-green-600 text-white font-bold w-8 h-8 rounded-full flex items-center justify-center text-sm">
                            {{ $index + 1 }}
                        </span>
                        <span class="font-medium text-gray-800">{{ $item->kelompok->nama_kelompok ?? 'N/A' }}</span>
                    </div>
                    <span class="text-green-600 font-bold text-lg">{{ number_format($item->total_bibit) }}</span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">Belum ada data</p>
                @endforelse
            </div>
        </div>

        <!-- Statistik per Jenis Bibit -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>🌲</span> Realisasi per Jenis Bibit
            </h2>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($perJenisBibit as $index => $item)
                <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-medium text-gray-800">{{ $item->jenis_bibit }}</span>
                        <span class="text-green-600 font-bold">{{ number_format($item->total_bibit) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" 
                             style="width: {{ $totalKeseluruhan > 0 ? ($item->total_bibit / $totalKeseluruhan * 100) : 0 }}%">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $totalKeseluruhan > 0 ? number_format($item->total_bibit / $totalKeseluruhan * 100, 1) : 0 }}% dari total
                    </p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Statistik per Golongan -->
    <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span>📊</span> Realisasi per Golongan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @forelse($perGolongan as $item)
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border-l-4 border-blue-500">
                <p class="text-blue-600 text-sm font-medium mb-1">{{ $item->golongan }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($item->total_bibit) }}</p>
                <p class="text-xs text-gray-600 mt-1">batang</p>
            </div>
            @empty
            <div class="col-span-4 text-center py-8 text-gray-500">
                Belum ada data golongan
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection