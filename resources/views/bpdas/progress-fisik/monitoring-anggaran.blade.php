@extends('layouts.dashboard')

@section('title', 'Monitoring Anggaran Kelompok - Sistem KBR')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">Monitoring Anggaran Kelompok</h1>
                <p class="text-xs sm:text-base text-gray-600 mt-1">Pantau penggunaan dan realisasi anggaran semua kelompok</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <a href="{{ route('bpdas.progress-fisik.monitoring') }}" 
                   class="px-4 sm:px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-300 flex items-center justify-center gap-2 text-sm">
                    ← Kembali
                </a>
                <a href="{{ route('bpdas.progress-fisik.export.pdf-global') }}?tahun={{ $tahun }}" 
                   class="px-4 sm:px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-300 flex items-center justify-center gap-2 text-sm"
                   target="_blank">
                    📄 Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Tahun -->
    <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-4 sm:mb-6">
        <div class="p-4 sm:p-6">
            <form method="GET" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Filter Tahun</label>
                    <select name="tahun" 
                            class="w-full text-sm border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500"
                            onchange="this.form.submit()">
                        @foreach($tahunList as $year)
                            <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>
                                Tahun {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik Global Anggaran -->
    <div class="mb-4 sm:mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
            <!-- Total Anggaran -->
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-medium uppercase">Total Anggaran</p>
                        <p class="text-xl sm:text-2xl font-bold text-blue-600 mt-1">
                            Rp {{ number_format($statistik['total_anggaran'] / 1000000, 1) }}jt
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Tahun {{ $tahun }}</p>
                    </div>
                    <span class="text-3xl sm:text-4xl">💰</span>
                </div>
            </div>

            <!-- Anggaran Dialokasikan -->
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-medium uppercase">Dialokasikan</p>
                        <p class="text-xl sm:text-2xl font-bold text-orange-600 mt-1">
                            Rp {{ number_format($statistik['total_dialokasikan'] / 1000000, 1) }}jt
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format(($statistik['total_dialokasikan'] / $statistik['total_anggaran']) * 100, 1) }}% dari total
                        </p>
                    </div>
                    <span class="text-3xl sm:text-4xl">📊</span>
                </div>
            </div>

            <!-- Realisasi -->
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-medium uppercase">Realisasi</p>
                        <p class="text-xl sm:text-2xl font-bold text-green-600 mt-1">
                            Rp {{ number_format($statistik['total_realisasi'] / 1000000, 1) }}jt
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format(($statistik['total_realisasi'] / $statistik['total_anggaran']) * 100, 1) }}% dari total
                        </p>
                    </div>
                    <span class="text-3xl sm:text-4xl">✅</span>
                </div>
            </div>

            <!-- Sisa Anggaran -->
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-medium uppercase">Sisa Anggaran</p>
                        <p class="text-xl sm:text-2xl font-bold text-purple-600 mt-1">
                            Rp {{ number_format($statistik['total_sisa'] / 1000000, 1) }}jt
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format(($statistik['total_sisa'] / $statistik['total_anggaran']) * 100, 1) }}% tersisa
                        </p>
                    </div>
                    <span class="text-3xl sm:text-4xl">💵</span>
                </div>
            </div>

            <!-- Rata-rata Progress -->
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-medium uppercase">Rata-rata Realisasi</p>
                        <p class="text-xl sm:text-2xl font-bold text-indigo-600 mt-1">
                            {{ number_format($statistik['rata_progress_realisasi'], 1) }}%
                        </p>
                        <p class="text-xs text-gray-500 mt-1">dari semua kelompok</p>
                    </div>
                    <span class="text-3xl sm:text-4xl">📈</span>
                </div>
            </div>
        </div>

        <!-- Progress Bar Global -->
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
            <!-- Progress Alokasi -->
            <div class="bg-white p-3 sm:p-4 rounded-lg shadow">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs sm:text-sm font-semibold text-gray-700">Total Alokasi Anggaran</span>
                    <span class="text-xs sm:text-sm font-bold text-orange-600">
                        {{ number_format($statistik['rata_progress_alokasi'], 1) }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 sm:h-4">
                    <div class="bg-orange-500 h-3 sm:h-4 rounded-full transition-all duration-500" 
                         style="width: {{ $statistik['rata_progress_alokasi'] }}%"></div>
                </div>
            </div>

            <!-- Progress Realisasi -->
            <div class="bg-white p-3 sm:p-4 rounded-lg shadow">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs sm:text-sm font-semibold text-gray-700">Total Realisasi Anggaran</span>
                    <span class="text-xs sm:text-sm font-bold text-green-600">
                        {{ number_format($statistik['rata_progress_realisasi'], 1) }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 sm:h-4">
                    <div class="bg-green-500 h-3 sm:h-4 rounded-full transition-all duration-500" 
                         style="width: {{ $statistik['rata_progress_realisasi'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Anggaran per Kelompok - Desktop View -->
    <div class="hidden lg:block bg-white overflow-hidden shadow-lg rounded-xl">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <span>💰</span>
                Detail Anggaran per Kelompok
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kelompok</th>
                        <th class="px-4 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Total Anggaran</th>
                        <th class="px-4 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Dialokasikan</th>
                        <th class="px-4 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Realisasi</th>
                        <th class="px-4 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Sisa</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Progress Alokasi</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Progress Realisasi</th>
                        <th class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($anggaranList as $index => $anggaran)
                        <tr class="hover:bg-green-50 transition-colors">
                            <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $anggaran->kelompok->nama_kelompok }}</div>
                                <div class="text-xs text-gray-500">{{ $anggaran->kelompok->kode_kelompok }}</div>
                                <div class="text-xs text-gray-500">Ketua: {{ $anggaran->kelompok->nama_ketua }}</div>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="text-sm font-bold text-blue-600">
                                    Rp {{ number_format($anggaran->total_anggaran / 1000000, 2) }}jt
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ number_format($anggaran->total_anggaran, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="text-sm font-bold text-orange-600">
                                    Rp {{ number_format($anggaran->anggaran_dialokasikan / 1000000, 2) }}jt
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ number_format($anggaran->anggaran_dialokasikan, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="text-sm font-bold text-green-600">
                                    Rp {{ number_format($anggaran->realisasi_anggaran / 1000000, 2) }}jt
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ number_format($anggaran->realisasi_anggaran, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="text-sm font-bold text-purple-600">
                                    Rp {{ number_format($anggaran->sisa_anggaran / 1000000, 2) }}jt
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ number_format($anggaran->sisa_anggaran, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-col items-center">
                                    <span class="text-lg font-bold text-orange-600 mb-1">
                                        {{ number_format($anggaran->persentase_alokasi, 1) }}%
                                    </span>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-orange-500 h-2.5 rounded-full transition-all duration-300" 
                                             style="width: {{ $anggaran->persentase_alokasi }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-col items-center">
                                    <span class="text-lg font-bold mb-1
                                        {{ $anggaran->persentase_realisasi >= 75 ? 'text-green-600' : 
                                           ($anggaran->persentase_realisasi >= 50 ? 'text-blue-600' : 
                                           ($anggaran->persentase_realisasi > 0 ? 'text-yellow-600' : 'text-gray-400')) }}">
                                        {{ number_format($anggaran->persentase_realisasi, 1) }}%
                                    </span>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="h-2.5 rounded-full transition-all duration-300
                                            {{ $anggaran->persentase_realisasi >= 75 ? 'bg-green-500' : 
                                               ($anggaran->persentase_realisasi >= 50 ? 'bg-blue-500' : 
                                               ($anggaran->persentase_realisasi > 0 ? 'bg-yellow-500' : 'bg-gray-300')) }}" 
                                             style="width: {{ $anggaran->persentase_realisasi }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <a href="{{ route('bpdas.progress-fisik.show', $anggaran->kelompok_id) }}" 
                                   class="inline-flex items-center gap-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 text-sm font-medium">
                                    👁️ Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-7xl mb-4">💰</span>
                                    <p class="text-gray-500 text-xl font-medium mb-2">Tidak ada data anggaran</p>
                                    <p class="text-gray-400 text-sm">Belum ada kelompok yang memiliki anggaran tahun {{ $tahun }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                
                @if($anggaranList->count() > 0)
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td colspan="2" class="px-4 py-4 text-sm text-gray-900">
                                TOTAL
                            </td>
                            <td class="px-4 py-4 text-right text-sm">
                                <div class="text-blue-600">
                                    Rp {{ number_format($statistik['total_anggaran'] / 1000000, 2) }}jt
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right text-sm">
                                <div class="text-orange-600">
                                    Rp {{ number_format($statistik['total_dialokasikan'] / 1000000, 2) }}jt
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right text-sm">
                                <div class="text-green-600">
                                    Rp {{ number_format($statistik['total_realisasi'] / 1000000, 2) }}jt
                                </div>
                            </td>
                            <td class="px-4 py-4 text-right text-sm">
                                <div class="text-purple-600">
                                    Rp {{ number_format($statistik['total_sisa'] / 1000000, 2) }}jt
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center text-sm text-orange-600">
                                {{ number_format($statistik['rata_progress_alokasi'], 1) }}%
                            </td>
                            <td class="px-4 py-4 text-center text-sm text-green-600">
                                {{ number_format($statistik['rata_progress_realisasi'], 1) }}%
                            </td>
                            <td class="px-4 py-4"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Card View untuk Mobile & Tablet -->
    <div class="lg:hidden space-y-4">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3 rounded-t-xl">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <span>💰</span>
                Detail Anggaran per Kelompok
            </h3>
        </div>

        @forelse($anggaranList as $index => $anggaran)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Header Card -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <span class="text-xs font-semibold text-gray-500 bg-gray-200 px-2 py-1 rounded">
                                #{{ $index + 1 }}
                            </span>
                            <h4 class="text-sm sm:text-base font-bold text-gray-900 mt-2">
                                {{ $anggaran->kelompok->nama_kelompok }}
                            </h4>
                            <p class="text-xs text-gray-600 mt-1">{{ $anggaran->kelompok->kode_kelompok }}</p>
                            <p class="text-xs text-gray-500">Ketua: {{ $anggaran->kelompok->nama_ketua }}</p>
                        </div>
                    </div>
                </div>

                <!-- Content Card -->
                <div class="p-4 space-y-3">
                    <!-- Anggaran Details -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                            <p class="text-xs text-gray-600 mb-1">Total Anggaran</p>
                            <p class="text-sm sm:text-base font-bold text-blue-600">
                                Rp {{ number_format($anggaran->total_anggaran / 1000000, 2) }}jt
                            </p>
                            <p class="text-xs text-gray-500 mt-1 break-words">
                                {{ number_format($anggaran->total_anggaran, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-orange-50 p-3 rounded-lg border border-orange-200">
                            <p class="text-xs text-gray-600 mb-1">Dialokasikan</p>
                            <p class="text-sm sm:text-base font-bold text-orange-600">
                                Rp {{ number_format($anggaran->anggaran_dialokasikan / 1000000, 2) }}jt
                            </p>
                            <p class="text-xs text-gray-500 mt-1 break-words">
                                {{ number_format($anggaran->anggaran_dialokasikan, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                            <p class="text-xs text-gray-600 mb-1">Realisasi</p>
                            <p class="text-sm sm:text-base font-bold text-green-600">
                                Rp {{ number_format($anggaran->realisasi_anggaran / 1000000, 2) }}jt
                            </p>
                            <p class="text-xs text-gray-500 mt-1 break-words">
                                {{ number_format($anggaran->realisasi_anggaran, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="bg-purple-50 p-3 rounded-lg border border-purple-200">
                            <p class="text-xs text-gray-600 mb-1">Sisa Anggaran</p>
                            <p class="text-sm sm:text-base font-bold text-purple-600">
                                Rp {{ number_format($anggaran->sisa_anggaran / 1000000, 2) }}jt
                            </p>
                            <p class="text-xs text-gray-500 mt-1 break-words">
                                {{ number_format($anggaran->sisa_anggaran, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Progress Bars -->
                    <div class="space-y-3 pt-2">
                        <!-- Progress Alokasi -->
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-semibold text-gray-700">Progress Alokasi</span>
                                <span class="text-sm font-bold text-orange-600">
                                    {{ number_format($anggaran->persentase_alokasi, 1) }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-orange-500 h-2.5 rounded-full transition-all duration-300" 
                                     style="width: {{ $anggaran->persentase_alokasi }}%"></div>
                            </div>
                        </div>

                        <!-- Progress Realisasi -->
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-semibold text-gray-700">Progress Realisasi</span>
                                <span class="text-sm font-bold
                                    {{ $anggaran->persentase_realisasi >= 75 ? 'text-green-600' : 
                                       ($anggaran->persentase_realisasi >= 50 ? 'text-blue-600' : 
                                       ($anggaran->persentase_realisasi > 0 ? 'text-yellow-600' : 'text-gray-400')) }}">
                                    {{ number_format($anggaran->persentase_realisasi, 1) }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full transition-all duration-300
                                    {{ $anggaran->persentase_realisasi >= 75 ? 'bg-green-500' : 
                                       ($anggaran->persentase_realisasi >= 50 ? 'bg-blue-500' : 
                                       ($anggaran->persentase_realisasi > 0 ? 'bg-yellow-500' : 'bg-gray-300')) }}" 
                                     style="width: {{ $anggaran->persentase_realisasi }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-2">
                        <a href="{{ route('bpdas.progress-fisik.show', $anggaran->kelompok_id) }}" 
                           class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 text-sm font-semibold">
                            👁️ Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <span class="text-6xl mb-4 block">💰</span>
                <p class="text-gray-500 text-lg font-medium mb-2">Tidak ada data anggaran</p>
                <p class="text-gray-400 text-sm">Belum ada kelompok yang memiliki anggaran tahun {{ $tahun }}</p>
            </div>
        @endforelse
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4 rounded-lg">
        <div class="flex items-start">
            <span class="text-xl sm:text-2xl mr-2 sm:mr-3">ℹ️</span>
            <div class="text-xs sm:text-sm text-blue-800">
                <p class="font-semibold mb-2">Keterangan:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Total Anggaran:</strong> Anggaran yang dialokasikan untuk kelompok</li>
                    <li><strong>Dialokasikan:</strong> Total biaya dari kegiatan yang pending dan disetujui</li>
                    <li><strong>Realisasi:</strong> Total biaya realisasi dari kegiatan yang sudah disetujui</li>
                    <li><strong>Sisa:</strong> Total anggaran - Anggaran dialokasikan</li>
                    <li><strong>Progress Alokasi:</strong> Persentase anggaran yang sudah dialokasikan</li>
                    <li><strong>Progress Realisasi:</strong> Persentase anggaran yang sudah terealisasi</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection