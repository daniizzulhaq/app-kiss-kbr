@extends('layouts.dashboard')

@section('title', 'Monitoring Progress Fisik - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Monitoring Progress Fisik Kelompok</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Pantau progress fisik kegiatan semua kelompok tahun {{ date('Y') }}</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <a href="{{ route('bpdas.progress-fisik.monitoring-anggaran') }}" 
                   class="px-4 sm:px-6 py-2 sm:py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 flex items-center justify-center gap-2 text-sm sm:text-base">
                    💰 Monitoring Anggaran
                </a>
                <a href="{{ route('bpdas.progress-fisik.export.pdf-global') }}" 
                   class="px-4 sm:px-6 py-2 sm:py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-300 flex items-center justify-center gap-2 text-sm sm:text-base"
                   target="_blank">
                    📄 Export PDF Global
                </a>
            </div>
        </div>
    </div>

    <!-- Statistik Global -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-lg border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-600 font-medium uppercase">Total Kelompok</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1">{{ $kelompokList->count() }}</p>
                </div>
                <span class="text-2xl sm:text-4xl flex-shrink-0 ml-2">👥</span>
            </div>
        </div>
        
        <div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-lg border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-600 font-medium uppercase">Total Kegiatan</p>
                    <p class="text-2xl sm:text-3xl font-bold text-green-600 mt-1">{{ $kelompokList->sum('total_kegiatan') }}</p>
                </div>
                <span class="text-2xl sm:text-4xl flex-shrink-0 ml-2">📋</span>
            </div>
        </div>
        
        <div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-lg border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-600 font-medium uppercase">Pending Verifikasi</p>
                    <p class="text-2xl sm:text-3xl font-bold text-yellow-600 mt-1">{{ $kelompokList->sum('pending_verifikasi') }}</p>
                </div>
                <span class="text-2xl sm:text-4xl flex-shrink-0 ml-2">⏳</span>
            </div>
        </div>
        
        <div class="bg-white p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-lg border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-600 font-medium uppercase">Rata-rata Progress</p>
                    <p class="text-2xl sm:text-3xl font-bold text-purple-600 mt-1">
                        {{ $kelompokList->count() > 0 ? number_format($kelompokList->avg('progress_rata'), 1) : 0 }}%
                    </p>
                </div>
                <span class="text-2xl sm:text-4xl flex-shrink-0 ml-2">📊</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white overflow-hidden shadow-lg rounded-lg sm:rounded-xl mb-6">
        <div class="p-4 sm:p-6">
            <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           placeholder="Cari nama kelompok, kode, atau ketua..."
                           value="{{ request('search') }}"
                           class="w-full text-sm sm:text-base border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                </div>
                <div class="flex gap-2 sm:gap-4">
                    <button type="submit" 
                            class="flex-1 sm:flex-none px-4 sm:px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-300 flex items-center justify-center gap-2 text-sm sm:text-base">
                        🔍 Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('bpdas.progress-fisik.monitoring') }}" 
                           class="px-4 sm:px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-300 text-sm sm:text-base">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="bg-white overflow-hidden shadow-lg rounded-lg sm:rounded-xl mb-6">
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-green-600 to-green-700">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">No</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Nama Kelompok</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Pengelola</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Kegiatan</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Status</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Progress</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Anggaran</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Pending</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kelompokList as $index => $item)
                        <tr class="hover:bg-green-50 transition-colors">
                            <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-4 py-4 text-sm font-semibold text-gray-900">{{ $item['nama'] }}</td>
                            <td class="px-4 py-4 text-sm text-gray-700">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    {{ $item['pengelola'] ?? '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="font-bold text-lg text-gray-800">{{ $item['total_kegiatan'] }}</span>
                                    <span class="text-xs text-gray-500">total</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <div class="flex flex-col items-center gap-1">
                                    <div class="flex gap-1">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800" title="Disetujui">
                                            ✅ {{ $item['kegiatan_disetujui'] }}
                                        </span>
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800" title="Pending">
                                            ⏳ {{ $item['kegiatan_pending'] }}
                                        </span>
                                    </div>
                                    @if($item['kegiatan_ditolak'] > 0)
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800" title="Ditolak">
                                            ❌ {{ $item['kegiatan_ditolak'] }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <div class="flex flex-col items-center">
                                    <span class="font-bold text-lg mb-1
                                        {{ $item['progress_rata'] >= 75 ? 'text-green-600' : 
                                           ($item['progress_rata'] >= 50 ? 'text-blue-600' : 
                                           ($item['progress_rata'] > 0 ? 'text-yellow-600' : 'text-gray-400')) }}">
                                        {{ number_format($item['progress_rata'], 1) }}%
                                    </span>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="h-2.5 rounded-full transition-all duration-300
                                            {{ $item['progress_rata'] >= 75 ? 'bg-green-500' : 
                                               ($item['progress_rata'] >= 50 ? 'bg-blue-500' : 
                                               ($item['progress_rata'] > 0 ? 'bg-yellow-500' : 'bg-gray-300')) }}" 
                                             style="width: {{ $item['progress_rata'] }}%"></div>
                                    </div>
                                    @if($item['kegiatan_selesai'] > 0)
                                        <span class="text-xs text-green-600 mt-1">{{ $item['kegiatan_selesai'] }} selesai</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-center">
                                @if($item['anggaran'])
                                    <div class="flex flex-col items-center">
                                        <span class="text-lg font-bold text-green-600">
                                            {{ number_format($item['anggaran']->persentase_realisasi, 1) }}%
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            Rp {{ number_format($item['anggaran']->realisasi_anggaran / 1000000, 1) }}jt
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            / Rp {{ number_format($item['anggaran']->total_anggaran / 1000000, 1) }}jt
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-400">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm text-center">
                                @if($item['pending_verifikasi'] > 0)
                                    <span class="px-3 py-1.5 text-sm font-bold rounded-full bg-yellow-100 text-yellow-800 border-2 border-yellow-200">
                                        ⏳ {{ $item['pending_verifikasi'] }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('bpdas.progress-fisik.show', $item['id']) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 font-medium text-xs">
                                        👁️ Detail
                                    </a>
                                    <a href="{{ route('bpdas.progress-fisik.export.pdf-per-user', $item['user_id']) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-300 font-medium text-xs"
                                       target="_blank"
                                       title="Export PDF untuk {{ $item['pengelola'] }}">
                                        📄 PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-7xl mb-4">📋</span>
                                    <p class="text-gray-500 text-xl font-medium mb-2">Tidak ada data kelompok</p>
                                    @if(request('search'))
                                        <p class="text-gray-400 text-sm">Coba dengan kata kunci lain</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards View -->
        <div class="lg:hidden divide-y divide-gray-200">
            @forelse($kelompokList as $index => $item)
            <div class="p-4 hover:bg-green-50 transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-medium text-gray-500">#{!! $index + 1 !!}</span>
                            @if($item['pending_verifikasi'] > 0)
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    ⏳ {{ $item['pending_verifikasi'] }}
                                </span>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-800 text-sm sm:text-base mb-1">{{ $item['nama'] }}</h3>
                        <p class="text-xs text-gray-600 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                            {{ $item['pengelola'] ?? '-' }}
                        </p>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div class="bg-gray-50 p-2 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Kegiatan</p>
                        <p class="font-bold text-gray-800">{{ $item['total_kegiatan'] }}</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Selesai</p>
                        <p class="font-bold text-green-600">{{ $item['kegiatan_selesai'] }}</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs text-gray-600">Progress Fisik</span>
                        <span class="font-bold text-sm
                            {{ $item['progress_rata'] >= 75 ? 'text-green-600' : 
                               ($item['progress_rata'] >= 50 ? 'text-blue-600' : 
                               ($item['progress_rata'] > 0 ? 'text-yellow-600' : 'text-gray-400')) }}">
                            {{ number_format($item['progress_rata'], 1) }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all duration-300
                            {{ $item['progress_rata'] >= 75 ? 'bg-green-500' : 
                               ($item['progress_rata'] >= 50 ? 'bg-blue-500' : 
                               ($item['progress_rata'] > 0 ? 'bg-yellow-500' : 'bg-gray-300')) }}" 
                             style="width: {{ $item['progress_rata'] }}%"></div>
                    </div>
                </div>

                <!-- Status Badges -->
                <div class="flex flex-wrap gap-1 mb-3">
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        ✅ {{ $item['kegiatan_disetujui'] }}
                    </span>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        ⏳ {{ $item['kegiatan_pending'] }}
                    </span>
                    @if($item['kegiatan_ditolak'] > 0)
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            ❌ {{ $item['kegiatan_ditolak'] }}
                        </span>
                    @endif
                </div>

                <!-- Anggaran Info -->
                @if($item['anggaran'])
                <div class="bg-green-50 p-2 rounded-lg mb-3">
                    <p class="text-xs text-gray-600 mb-1">Realisasi Anggaran</p>
                    <p class="font-bold text-green-600">{{ number_format($item['anggaran']->persentase_realisasi, 1) }}%</p>
                    <p class="text-xs text-gray-500">
                        Rp {{ number_format($item['anggaran']->realisasi_anggaran / 1000000, 1) }}jt / 
                        Rp {{ number_format($item['anggaran']->total_anggaran / 1000000, 1) }}jt
                    </p>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="pt-3 border-t border-gray-200 flex gap-2">
                    <a href="{{ route('bpdas.progress-fisik.show', $item['id']) }}" 
                       class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium inline-flex items-center justify-center gap-1">
                        👁️ Detail
                    </a>
                    <a href="{{ route('bpdas.progress-fisik.export.pdf-per-user', $item['user_id']) }}" 
                       class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium inline-flex items-center justify-center gap-1"
                       target="_blank">
                        📄 PDF
                    </a>
                </div>
            </div>
            @empty
            <div class="p-8 sm:p-16 text-center">
                <span class="text-5xl sm:text-7xl mb-4 block">📋</span>
                <p class="text-gray-500 text-lg sm:text-xl font-medium mb-2">Tidak ada data kelompok</p>
                @if(request('search'))
                    <p class="text-gray-400 text-sm">Coba dengan kata kunci lain</p>
                @endif
            </div>
            @endforelse
        </div>
    </div>

    <!-- Legenda -->
    <div class="bg-white overflow-hidden shadow-lg rounded-lg sm:rounded-xl">
        <div class="p-4 sm:p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                <span class="text-lg sm:text-xl">ℹ️</span>
                Informasi dan Legenda
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <!-- Legenda Progress -->
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase mb-3">Legenda Progress Fisik:</p>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-5 h-5 sm:w-6 sm:h-6 rounded bg-gray-300 flex-shrink-0"></div>
                            <span class="text-xs sm:text-sm text-gray-700">0%: Belum Mulai</span>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-5 h-5 sm:w-6 sm:h-6 rounded bg-yellow-500 flex-shrink-0"></div>
                            <span class="text-xs sm:text-sm text-gray-700">1-49%: Tahap Awal</span>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-5 h-5 sm:w-6 sm:h-6 rounded bg-blue-500 flex-shrink-0"></div>
                            <span class="text-xs sm:text-sm text-gray-700">50-74%: Sedang Berjalan</span>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-5 h-5 sm:w-6 sm:h-6 rounded bg-green-500 flex-shrink-0"></div>
                            <span class="text-xs sm:text-sm text-gray-700">75-100%: Hampir/Sudah Selesai</span>
                        </div>
                    </div>
                </div>

                <!-- Legenda Status -->
                <div>
                    <p class="text-xs font-semibold text-gray-600 uppercase mb-3">Status Verifikasi:</p>
                    <div class="space-y-2">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="px-2 sm:px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 flex-shrink-0">⏳ Pending</span>
                            <span class="text-xs sm:text-sm text-gray-700">Menunggu verifikasi BPDAS</span>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="px-2 sm:px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 flex-shrink-0">✅ Disetujui</span>
                            <span class="text-xs sm:text-sm text-gray-700">Sudah diverifikasi & dihitung realisasi</span>
                        </div>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <span class="px-2 sm:px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 flex-shrink-0">❌ Ditolak</span>
                            <span class="text-xs sm:text-sm text-gray-700">Ditolak oleh BPDAS</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
                <p class="text-xs sm:text-sm text-blue-800">
                    <strong>Catatan:</strong> Progress fisik dihitung hanya dari kegiatan yang sudah <strong>disetujui</strong>. 
                    Kegiatan dengan status pending atau ditolak tidak masuk dalam perhitungan progress.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection