@extends('layouts.dashboard')

@section('title', 'Rencana Bibit Kelompok - Sistem KBR')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8 slide-in">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-2">🌱 Rencana Bibit Kelompok</h1>
                <p class="text-sm sm:text-base text-gray-600">Monitor rencana kebutuhan bibit dari semua kelompok tani</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <!-- Export Buttons -->
                <div class="relative group">
                    <button class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-lg sm:rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold flex items-center justify-center space-x-2">
                        <span>📥</span>
                        <span>Export</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="absolute left-0 sm:right-0 sm:left-auto mt-2 w-full sm:w-48 bg-white rounded-xl shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-10">
                        <div class="py-2">
                            <a href="{{ route('bpdas.rencana-bibit.export.excel', request()->all()) }}" 
                               class="flex items-center space-x-3 px-4 py-3 hover:bg-green-50 transition-colors">
                                <span class="text-xl sm:text-2xl">📊</span>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm sm:text-base">Excel</div>
                                    <div class="text-xs text-gray-500">.xlsx format</div>
                                </div>
                            </a>
                            <a href="{{ route('bpdas.rencana-bibit.export.pdf', request()->all()) }}" 
                               class="flex items-center space-x-3 px-4 py-3 hover:bg-red-50 transition-colors">
                                <span class="text-xl sm:text-2xl">📄</span>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm sm:text-base">PDF</div>
                                    <div class="text-xs text-gray-500">.pdf format</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('bpdas.rencana-bibit.statistik') }}" 
                   class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg sm:rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold text-center">
                    📊 Lihat Statistik
                </a>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-2 sm:mb-0">
                    <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Kelompok</p>
                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">{{ $totalKelompok }}</h3>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg sm:rounded-xl flex items-center justify-center text-lg sm:text-xl lg:text-2xl">
                    👥
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-2 sm:mb-0">
                    <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Jenis Bibit</p>
                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-600">{{ number_format($totalJenisBibit, 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-lg sm:rounded-xl flex items-center justify-center text-lg sm:text-xl lg:text-2xl">
                    🌱
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-2 sm:mb-0">
                    <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Batang</p>
                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-blue-600">{{ number_format($totalBatang, 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg sm:rounded-xl flex items-center justify-center text-lg sm:text-xl lg:text-2xl">
                    🌳
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-2 sm:mb-0">
                    <p class="text-gray-600 text-xs sm:text-sm mb-1">Bersertifikat</p>
                    <h3 class="text-xl sm:text-2xl lg:text-3xl font-bold text-amber-600">{{ number_format($totalBersertifikat, 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 bg-gradient-to-br from-amber-100 to-amber-200 rounded-lg sm:rounded-xl flex items-center justify-center text-lg sm:text-xl lg:text-2xl">
                    📜
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8 slide-in">
        <form method="GET" action="{{ route('bpdas.rencana-bibit.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <!-- Search -->
            <div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="🔍 Cari jenis bibit..."
                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <!-- Filter Kelompok -->
            <div>
                <select name="kelompok" 
                        class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Semua Kelompok</option>
                    @foreach($kelompoks as $kelompok)
                        <option value="{{ $kelompok->id }}" {{ request('kelompok') == $kelompok->id ? 'selected' : '' }}>
                            {{ $kelompok->nama_kelompok }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Golongan -->
            <div>
                <select name="golongan" 
                        class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
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
                        class="flex-1 px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base bg-green-600 text-white rounded-lg sm:rounded-xl hover:bg-green-700 transition-all font-medium">
                    Filter
                </button>
                <a href="{{ route('bpdas.rencana-bibit.index') }}" 
                   class="px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base bg-gray-200 text-gray-700 rounded-lg sm:rounded-xl hover:bg-gray-300 transition-all font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Info Alert for Filtered Results -->
    @if(request()->hasAny(['search', 'kelompok', 'golongan']))
    <div class="bg-blue-50 border border-blue-200 rounded-lg sm:rounded-xl p-3 sm:p-4 mb-4 sm:mb-6 flex items-start space-x-2 sm:space-x-3">
        <span class="text-xl sm:text-2xl">ℹ️</span>
        <div class="flex-1">
            <p class="text-blue-800 font-medium text-sm sm:text-base">Filter Aktif</p>
            <p class="text-blue-600 text-xs sm:text-sm">Data yang ditampilkan dan diexport sesuai dengan filter yang Anda terapkan.</p>
        </div>
    </div>
    @endif

    <!-- Table - Desktop View -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden slide-in">
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kelompok</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Pengelola</th>
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
                            <div class="text-sm text-gray-700">{{ $bibit->kelompok->user->name ?? '-' }}</div>
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
                        <td colspan="9" class="px-6 py-12 text-center">
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

        <!-- Mobile Cards View -->
        <div class="lg:hidden divide-y divide-gray-200">
            @forelse($rencanaBibits as $index => $bibit)
            <div class="p-4 hover:bg-green-50 transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-medium text-gray-500">#{!! $rencanaBibits->firstItem() + $index !!}</span>
                            {!! $bibit->golongan_badge !!}
                        </div>
                        <h3 class="font-bold text-gray-800 text-sm sm:text-base mb-1">{{ $bibit->jenis_bibit }}</h3>
                        <p class="text-xs text-gray-600">{{ $bibit->kelompok->nama_kelompok ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                    <div class="bg-gray-50 p-2 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Jumlah</p>
                        <p class="font-bold text-green-700">{{ $bibit->jumlah_format }}</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Tinggi</p>
                        <p class="font-semibold text-gray-700">{{ $bibit->tinggi_format }}</p>
                    </div>
                </div>

                <div class="space-y-2 text-sm mb-3">
                    <div class="flex items-start">
                        <span class="text-gray-500 w-20 flex-shrink-0 text-xs">Pengelola:</span>
                        <span class="text-gray-700 text-xs">{{ $bibit->kelompok->user->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-start">
                        <span class="text-gray-500 w-20 flex-shrink-0 text-xs">Desa:</span>
                        <span class="text-gray-700 text-xs">{{ $bibit->kelompok->desa ?? '-' }}</span>
                    </div>
                    <div class="flex items-start">
                        <span class="text-gray-500 w-20 flex-shrink-0 text-xs">Sertifikat:</span>
                        <div>{!! $bibit->sertifikat_badge !!}</div>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-200">
                    <a href="{{ route('bpdas.rencana-bibit.show', $bibit) }}" 
                       class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium inline-flex items-center justify-center">
                        👁️ Lihat Detail
                    </a>
                </div>
            </div>
            @empty
            <div class="p-8 sm:p-12 text-center">
                <span class="text-4xl sm:text-6xl mb-3 sm:mb-4 block">🌱</span>
                <p class="text-gray-500 font-medium text-sm sm:text-base">Belum ada data rencana bibit</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($rencanaBibits->hasPages())
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200 bg-gray-50">
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
    
    /* Touch-friendly dropdown for mobile */
    @media (max-width: 640px) {
        .group:active .group-hover\:opacity-100 {
            opacity: 1;
        }
        .group:active .group-hover\:visible {
            visibility: visible;
        }
    }
</style>
@endsection