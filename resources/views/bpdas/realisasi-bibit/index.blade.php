@extends('layouts.dashboard')

@section('title', 'Rencana Bibit - Sistem KBR')
@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Realisasi Bibit Kelompok</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Monitoring realisasi bibit dari seluruh kelompok</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
            <!-- Tombol Export Excel -->
            <a href="{{ route('bpdas.realisasi-bibit.export.excel', request()->query()) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-xl text-sm sm:text-base">
                <span class="text-lg sm:text-xl">📗</span>
                <span class="font-medium">Export Excel</span>
            </a>
            
            <!-- Tombol Export PDF -->
            <a href="{{ route('bpdas.realisasi-bibit.export.pdf', request()->query()) }}" 
               class="bg-red-600 hover:bg-red-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-xl text-sm sm:text-base">
                <span class="text-lg sm:text-xl">📕</span>
                <span class="font-medium">Export PDF</span>
            </a>
            
            <!-- Tombol Statistik -->
            <a href="{{ route('bpdas.realisasi-bibit.statistik') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-xl text-sm sm:text-base">
                <span class="text-lg sm:text-xl">📊</span>
                <span class="font-medium">Lihat Statistik</span>
            </a>
        </div>
    </div>
   
    <!-- Filter Card -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-6">
        <form method="GET" action="{{ route('bpdas.realisasi-bibit.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <!-- Filter Kelompok -->
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Kelompok</label>
                <select name="kelompok_id" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Kelompok</option>
                    @foreach($kelompoks as $kelompok)
                        <option value="{{ $kelompok->id }}" {{ request('kelompok_id') == $kelompok->id ? 'selected' : '' }}>
                            {{ $kelompok->nama_kelompok }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Jenis Bibit -->
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Jenis Bibit</label>
                <input type="text" 
                       name="jenis_bibit" 
                       value="{{ request('jenis_bibit') }}"
                       placeholder="Cari jenis bibit..."
                       class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <!-- Filter Golongan -->
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Golongan</label>
                <select name="golongan" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Golongan</option>
                    <option value="Cepat Tumbuh" {{ request('golongan') == 'Cepat Tumbuh' ? 'selected' : '' }}>Cepat Tumbuh</option>
                    <option value="Sedang" {{ request('golongan') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Lambat" {{ request('golongan') == 'Lambat' ? 'selected' : '' }}>Lambat</option>
                    <option value="MPTS" {{ request('golongan') == 'MPTS' ? 'selected' : '' }}>MPTS</option>
                </select>
            </div>

            <!-- Button -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-2 rounded-lg font-medium transition-all text-sm sm:text-base">
                    🔍 Filter
                </button>
                <a href="{{ route('bpdas.realisasi-bibit.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-3 sm:px-4 py-2 rounded-lg font-medium transition-all text-sm sm:text-base">
                    ↺ Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Desktop Table View -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-green-600 to-green-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Kelompok</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Pengelola</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Jenis Bibit</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Golongan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Jumlah (Btg)</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Tinggi (cm)</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($realBibits as $index => $bibit)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $realBibits->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">
                                {{ $bibit->kelompok->nama_kelompok ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700">
                                {{ $bibit->kelompok->user->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-green-700">{{ $bibit->jenis_bibit }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $bibit->golongan ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-green-600">{{ number_format($bibit->jumlah_btg) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $bibit->tinggi ? number_format($bibit->tinggi, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('bpdas.realisasi-bibit.show', $bibit->id_bibit) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                👁️ Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-6xl mb-4">🌱</span>
                                <p class="text-gray-500 text-lg font-medium">Belum ada data realisasi bibit</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards View -->
        <div class="lg:hidden divide-y divide-gray-200">
            @forelse($realBibits as $index => $bibit)
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-medium text-gray-500">#{!! $realBibits->firstItem() + $index !!}</span>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $bibit->golongan ?? '-' }}
                            </span>
                        </div>
                        <h3 class="font-bold text-gray-800 text-sm sm:text-base mb-1">{{ $bibit->jenis_bibit }}</h3>
                        <p class="text-xs text-gray-600">{{ $bibit->kelompok->nama_kelompok ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                    <div class="bg-green-50 p-2 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Jumlah Batang</p>
                        <p class="font-bold text-green-700">{{ number_format($bibit->jumlah_btg) }}</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Tinggi (cm)</p>
                        <p class="font-semibold text-gray-700">{{ $bibit->tinggi ? number_format($bibit->tinggi, 2) : '-' }}</p>
                    </div>
                </div>

                <div class="space-y-2 text-sm mb-3">
                    <div class="flex items-start">
                        <span class="text-gray-500 w-20 flex-shrink-0 text-xs">Pengelola:</span>
                        <span class="text-gray-700 text-xs">{{ $bibit->kelompok->user->name ?? '-' }}</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-200">
                    <a href="{{ route('bpdas.realisasi-bibit.show', $bibit->id_bibit) }}" 
                       class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium inline-flex items-center justify-center">
                        👁️ Lihat Detail
                    </a>
                </div>
            </div>
            @empty
            <div class="p-8 sm:p-12 text-center">
                <span class="text-4xl sm:text-6xl mb-3 sm:mb-4 block">🌱</span>
                <p class="text-gray-500 font-medium text-sm sm:text-base">Belum ada data realisasi bibit</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($realBibits->hasPages())
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
            {{ $realBibits->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection