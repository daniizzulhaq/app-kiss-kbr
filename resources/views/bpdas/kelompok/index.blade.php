@extends('layouts.dashboard')

@section('title', 'Data Kelompok')

@section('content')

<div class="py-4 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            <div class="p-4 md:p-6">
                <!-- Header Section -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-4">
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800">Daftar Kelompok</h3>
                    
                    <!-- Tombol Export -->
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('bpdas.kelompok.export.pdf') }}" 
                           class="px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center gap-2 text-sm md:text-base">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span>Export PDF</span>
                        </a>
                        
                        <a href="{{ route('bpdas.kelompok.export.excel') }}" 
                           class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-2 text-sm md:text-base">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Export Excel</span>
                        </a>
                    </div>
                </div>

                <!-- Desktop Table View -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelompok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Desa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kecamatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kabupaten</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($kelompoks as $index => $kelompok)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $kelompoks->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $kelompok->nama_kelompok }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $kelompok->blok ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $kelompok->desa ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $kelompok->kecamatan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $kelompok->kabupaten ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $kelompok->anggota ?? '-' }} orang
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('bpdas.kelompok.show', $kelompok) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada data kelompok
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden space-y-4">
                    @forelse($kelompoks as $index => $kelompok)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow p-4">
                            <!-- Header Card -->
                            <div class="flex justify-between items-start mb-3 pb-3 border-b border-gray-200">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            #{{ $kelompoks->firstItem() + $index }}
                                        </span>
                                        <h4 class="text-base font-bold text-gray-900">{{ $kelompok->nama_kelompok }}</h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Grid -->
                            <div class="space-y-2 mb-3">
                                <div class="flex items-start">
                                    <span class="text-xs font-medium text-gray-500 w-24 flex-shrink-0">Blok:</span>
                                    <span class="text-sm text-gray-900">{{ $kelompok->blok ?? '-' }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-xs font-medium text-gray-500 w-24 flex-shrink-0">Desa:</span>
                                    <span class="text-sm text-gray-900">{{ $kelompok->desa ?? '-' }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-xs font-medium text-gray-500 w-24 flex-shrink-0">Kecamatan:</span>
                                    <span class="text-sm text-gray-900">{{ $kelompok->kecamatan ?? '-' }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-xs font-medium text-gray-500 w-24 flex-shrink-0">Kabupaten:</span>
                                    <span class="text-sm text-gray-900">{{ $kelompok->kabupaten ?? '-' }}</span>
                                </div>
                                <div class="flex items-start">
                                    <span class="text-xs font-medium text-gray-500 w-24 flex-shrink-0">Anggota:</span>
                                    <span class="text-sm font-semibold text-green-600">{{ $kelompok->anggota ?? '-' }} orang</span>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div class="pt-3 border-t border-gray-200">
                                <a href="{{ route('bpdas.kelompok.show', $kelompok) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada data kelompok</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $kelompoks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection