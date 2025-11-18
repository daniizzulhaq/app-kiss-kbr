@extends('layouts.dashboard')

@section('title', 'Statistik Rencana Bibit - Sistem KBR')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 slide-in">
        <a href="{{ route('bpdas.rencana-bibit.index') }}" 
           class="inline-flex items-center text-green-600 hover:text-green-700 mb-4 font-medium">
            ← Kembali ke Daftar
        </a>
        <div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">📊 Statistik Rencana Bibit</h1>
            <p class="text-gray-600">Analisis dan ringkasan data rencana bibit dari semua kelompok</p>
        </div>
    </div>

    <!-- Statistik Per Golongan -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 slide-in">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-4">
            <h2 class="text-xl font-bold text-white">🌳 Statistik Per Golongan Bibit</h2>
        </div>
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($statPerGolongan as $stat)
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">{{ $stat->golongan }}</h3>
                        <span class="text-3xl">
                            @if($stat->golongan == 'MPTS') 🌳
                            @elseif($stat->golongan == 'Kayu') 🪵
                            @elseif($stat->golongan == 'Buah') 🍎
                            @elseif($stat->golongan == 'Bambu') 🎋
                            @else 🌱
                            @endif
                        </span>
                    </div>
                    <div class="space-y-2">
                        <div>
                            <p class="text-xs text-gray-600">Total Jenis</p>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($stat->total_jenis, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Total Batang</p>
                            <p class="text-xl font-semibold text-gray-800">{{ number_format($stat->total_batang, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top 10 Jenis Bibit -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 slide-in">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-4">
            <h2 class="text-xl font-bold text-white">🏆 Top 10 Jenis Bibit Terbanyak</h2>
        </div>
        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Peringkat</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Jenis Bibit</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Golongan</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total Batang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($topBibit as $index => $bibit)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4">
                                <div class="flex items-center">
                                    @if($index < 3)
                                        <span class="text-2xl mr-2">
                                            @if($index == 0) 🥇
                                            @elseif($index == 1) 🥈
                                            @else 🥉
                                            @endif
                                        </span>
                                    @endif
                                    <span class="font-semibold text-gray-800">{{ $index + 1 }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="font-semibold text-gray-800">{{ $bibit->jenis_bibit }}</span>
                            </td>
                            <td class="px-4 py-4">
                                @if($bibit->golongan == 'MPTS')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">🌳 MPTS</span>
                                @elseif($bibit->golongan == 'Kayu')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">🪵 Kayu</span>
                                @elseif($bibit->golongan == 'Buah')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">🍎 Buah</span>
                                @elseif($bibit->golongan == 'Bambu')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">🎋 Bambu</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="text-lg font-bold text-green-600">{{ number_format($bibit->total_batang, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Statistik Per Kelompok -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden slide-in">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-8 py-4">
            <h2 class="text-xl font-bold text-white">👥 Statistik Per Kelompok</h2>
        </div>
        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">No</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama Kelompok</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Lokasi</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Jenis Bibit</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total Batang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($statPerKelompok as $index => $stat)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-4 py-4">
                                <span class="font-semibold text-gray-800">{{ $stat->kelompok->nama_kelompok ?? '-' }}</span>
                            </td>
                            <td class="px-4 py-4 text-gray-700">
                                {{ $stat->kelompok->desa ?? '-' }}, {{ $stat->kelompok->kecamatan ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                    {{ $stat->total_jenis }} Jenis
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="text-lg font-bold text-green-600">{{ number_format($stat->total_batang, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Belum ada data statistik
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection