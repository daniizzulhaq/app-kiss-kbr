@extends('layouts.dashboard')

@section('title', 'Rencana Bibit - Sistem KBR')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 sm:mb-8 slide-in">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-2">🌱 Rencana Bibit</h1>
                <p class="text-sm sm:text-base text-gray-600">Kelola rencana kebutuhan bibit untuk kegiatan kelompok tani</p>
            </div>
            <a href="{{ route('kelompok.rencana-bibit.create') }}" 
               class="w-full sm:w-auto text-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold text-sm sm:text-base">
                ➕ Tambah Bibit
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="mb-4 sm:mb-6 bg-green-50 border-l-4 border-green-500 p-3 sm:p-4 rounded-lg slide-in">
        <div class="flex items-center">
            <span class="text-xl sm:text-2xl mr-2 sm:mr-3">✅</span>
            <p class="text-sm sm:text-base text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Jenis Bibit</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $rencanaBibits->total() }}</h3>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center text-xl sm:text-2xl">
                    🌱
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm mb-1">Total Batang</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-green-600">{{ number_format($rencanaBibits->sum('jumlah_btg'), 0, ',', '.') }}</h3>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-xl sm:text-2xl">
                    🌳
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 border border-gray-100 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-xs sm:text-sm mb-1">Bersertifikat</p>
                    <h3 class="text-2xl sm:text-3xl font-bold text-blue-600">{{ $rencanaBibits->where('sertifikat', '!=', null)->count() }}</h3>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-amber-100 to-amber-200 rounded-xl flex items-center justify-center text-xl sm:text-2xl">
                    📜
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Table (Hidden on Mobile) -->
    <div class="hidden lg:block bg-white rounded-2xl shadow-xl overflow-hidden slide-in">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
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
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('kelompok.rencana-bibit.show', $bibit) }}" 
                                   class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                                    👁️ Lihat
                                </a>
                                <a href="{{ route('kelompok.rencana-bibit.edit', $bibit) }}" 
                                   class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-sm font-medium">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('kelompok.rencana-bibit.destroy', $bibit) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus rencana bibit ini?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-6xl mb-4">🌱</span>
                                <p class="text-gray-500 font-medium">Belum ada rencana bibit</p>
                                <a href="{{ route('kelompok.rencana-bibit.create') }}" 
                                   class="mt-4 text-green-600 hover:text-green-700 font-semibold">
                                    + Tambahkan rencana bibit pertama
                                </a>
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

    <!-- Mobile Card View (Visible on Mobile/Tablet) -->
    <div class="lg:hidden space-y-4 slide-in">
        @forelse($rencanaBibits as $index => $bibit)
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Header Card -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-white text-xs font-semibold bg-white/20 px-2 py-1 rounded">
                            #{{ $rencanaBibits->firstItem() + $index }}
                        </span>
                        <h3 class="text-white font-bold text-sm sm:text-base">{{ $bibit->jenis_bibit }}</h3>
                    </div>
                    {!! $bibit->golongan_badge !!}
                </div>
            </div>

            <!-- Content Card -->
            <div class="p-4 space-y-3">
                <!-- Jumlah & Tinggi -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-green-50 rounded-lg p-3">
                        <p class="text-xs text-gray-600 mb-1">Jumlah</p>
                        <p class="text-lg sm:text-xl font-bold text-green-700">{{ $bibit->jumlah_format }}</p>
                        <p class="text-xs text-gray-500">Batang</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3">
                        <p class="text-xs text-gray-600 mb-1">Tinggi</p>
                        <p class="text-lg sm:text-xl font-bold text-blue-700">{{ $bibit->tinggi_format }}</p>
                        <p class="text-xs text-gray-500">Centimeter</p>
                    </div>
                </div>

                <!-- Sertifikat -->
                <div class="flex items-center justify-between py-2 border-t border-gray-100">
                    <span class="text-xs sm:text-sm text-gray-600">Sertifikat:</span>
                    {!! $bibit->sertifikat_badge !!}
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-3 gap-2 pt-2">
                    <a href="{{ route('kelompok.rencana-bibit.show', $bibit) }}" 
                       class="flex items-center justify-center px-3 py-2.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs sm:text-sm font-medium">
                        <span class="mr-1">👁️</span>
                        <span>Lihat</span>
                    </a>
                    <a href="{{ route('kelompok.rencana-bibit.edit', $bibit) }}" 
                       class="flex items-center justify-center px-3 py-2.5 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-xs sm:text-sm font-medium">
                        <span class="mr-1">✏️</span>
                        <span>Edit</span>
                    </a>
                    <form action="{{ route('kelompok.rencana-bibit.destroy', $bibit) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus rencana bibit ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full h-full flex items-center justify-center px-3 py-2.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs sm:text-sm font-medium">
                            <span class="mr-1">🗑️</span>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-lg p-8 sm:p-12 text-center">
            <span class="text-5xl sm:text-6xl mb-4 block">🌱</span>
            <p class="text-gray-500 font-medium text-sm sm:text-base mb-2">Belum ada rencana bibit</p>
            <p class="text-gray-400 text-xs sm:text-sm mb-4">Mulai tambahkan rencana bibit untuk kelompok Anda</p>
            <a href="{{ route('kelompok.rencana-bibit.create') }}" 
               class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                ➕ Tambah Bibit Pertama
            </a>
        </div>
        @endforelse

        <!-- Mobile Pagination -->
        @if($rencanaBibits->hasPages())
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-100">
            {{ $rencanaBibits->links() }}
        </div>
        @endif
    </div>
</div>
@endsection