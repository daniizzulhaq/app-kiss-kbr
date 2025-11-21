@extends('layouts.dashboard')

@section('title', 'Rencana Bibit Kelompok - Sistem KBR')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 slide-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">🌱 Rencana Bibit Kelompok</h1>
                <p class="text-gray-600">Monitor rencana kebutuhan bibit dari semua kelompok tani</p>
            </div>
            <div class="flex space-x-3">
                <!-- Export Buttons -->
                <div class="relative group">
                    <button class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold flex items-center space-x-2">
                        <span>📥</span>
                        <span>Export</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-10">
                        <div class="py-2">
                            <a href="{{ route('bpdas.rencana-bibit.export.excel', request()->all()) }}" 
                               class="flex items-center space-x-3 px-4 py-3 hover:bg-green-50 transition-colors">
                                <span class="text-2xl">📊</span>
                                <div>
                                    <div class="font-semibold text-gray-800">Excel</div>
                                    <div class="text-xs text-gray-500">.xlsx format</div>
                                </div>
                            </a>
                            <a href="{{ route('bpdas.rencana-bibit.export.pdf', request()->all()) }}" 
                               class="flex items-center space-x-3 px-4 py-3 hover:bg-red-50 transition-colors">
                                <span class="text-2xl">📄</span>
                                <div>
                                    <div class="font-semibold text-gray-800">PDF</div>
                                    <div class="text-xs text-gray-500">.pdf format</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('bpdas.rencana-bibit.statistik') }}" 
                   class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                    📊 Lihat Statistik
                </a>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Kelompok</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalKelompok }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center text-2xl">
                    👥
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Jenis Bibit</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ number_format($totalJenisBibit, 0, ',', '.') }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center text-2xl">
                    🌱
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Batang</p>
                    <h3 class="text-3xl font-bold text-blue-600">{{ number_format($totalBatang, 0, ',', '.') }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-2xl">
                    🌳
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Bersertifikat</p>
                    <h3 class="text-3xl font-bold text-amber-600">{{ number_format($totalBersertifikat, 0, ',', '.') }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-amber-200 rounded-xl flex items-center justify-center text-2xl">
                    📜
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 slide-in">
        <form method="GET" action="{{ route('bpdas.rencana-bibit.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="🔍 Cari jenis bibit..."
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <!-- Filter Kelompok -->
            <div>
                <select name="kelompok" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Semua Kelompok</option>
                    @foreach($kelompoks as $kelompok)
                        <option value="{{ $kelompok->id_kelompok }}" {{ request('kelompok') == $kelompok->id_kelompok ? 'selected' : '' }}>
                            {{ $kelompok->nama_kelompok }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Golongan -->
            <div>
                <select name="golongan" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Semua Golongan</option>
                    <option value="MPTS" {{ request('golongan') == 'MPTS' ? 'selected' : '' }}>🌳 MPTS</option>
                    <option value="Kayu" {{ request('golongan') == 'Kayu' ? 'selected' : '' }}>🪵 Kayu</option>
                    <option value="Buah" {{ request('golongan') == 'Buah' ? 'selected' : '' }}>🍎 Buah</option>
                    <option value="Bambu" {{ request('golongan') == 'Bambu' ? 'selected' : '' }}>🎋 Bambu</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex space-x-2">
                <button type="submit" 
                        class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all font-medium">
                    Filter
                </button>
                <a href="{{ route('bpdas.rencana-bibit.index') }}" 
                   class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Info Alert for Filtered Results -->
    @if(request()->hasAny(['search', 'kelompok', 'golongan']))
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 flex items-start space-x-3">
        <span class="text-2xl">ℹ️</span>
        <div class="flex-1">
            <p class="text-blue-800 font-medium">Filter Aktif</p>
            <p class="text-blue-600 text-sm">Data yang ditampilkan dan diexport sesuai dengan filter yang Anda terapkan.</p>
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden slide-in">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kelompok</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Jenis Bibit</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Golongan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Jumlah (Btg)</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tinggi (cm)</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Sertifikat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($rencanaBibits as $index => $bibit)
                    <tr class="hover:bg-green-50 transition-colors duration-200">
                        <td class="px-6 py-4 text-gray-700">{{ $rencanaBibits->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $bibit->kelompok->nama_kelompok ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $bibit->kelompok->desa ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $bibit->jenis_bibit }}</div>
                        </td>
                        <td class="px-6 py-4">
                            {!! $bibit->golongan_badge !!}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-green-700">{{ $bibit->jumlah_format }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $bibit->tinggi_format }}</td>
                        <td class="px-6 py-4">
                            {!! $bibit->sertifikat_badge !!}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center">
                                <a href="{{ route('bpdas.rencana-bibit.show', $bibit) }}" 
                                   class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                                    👁️ Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-6xl mb-4">🌱</span>
                                <p class="text-gray-500 font-medium">Belum ada data rencana bibit</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($rencanaBibits->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $rencanaBibits->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .group:hover .group-hover\:opacity-100 {
        opacity: 1;
    }
    .group:hover .group-hover\:visible {
        visibility: visible;
    }
</style>
@endsection