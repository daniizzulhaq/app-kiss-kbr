@extends('layouts.dashboard')

@section('title', 'Realisasi Bibit - Sistem KBR')
@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Realisasi Bibit</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Kelola data realisasi bibit kelompok <strong class="text-green-600">{{ $kelompok->nama_kelompok }}</strong></p>
            </div>
            <a href="{{ route('kelompok.realisasi-bibit.create') }}" 
               class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-xl text-sm sm:text-base">
                <span class="text-lg sm:text-xl">➕</span>
                <span class="font-medium">Tambah Data</span>
            </a>
        </div>
        
        <!-- Info Box -->
        <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-3 sm:p-4 rounded-lg">
            <div class="flex items-start">
                <span class="text-xl sm:text-2xl mr-2 sm:mr-3 flex-shrink-0">ℹ️</span>
                <div>
                    <p class="text-xs sm:text-sm text-blue-800">
                        <strong>Informasi:</strong> Data realisasi bibit tahun {{ date('Y') }} untuk kelompok <strong>{{ $kelompok->nama_kelompok }}</strong>.
                        Anda dapat melihat semua data anggota kelompok, namun hanya dapat mengedit dan menghapus data milik Anda sendiri.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-xl sm:text-2xl mr-2 sm:mr-3">✅</span>
            <p class="text-sm sm:text-base font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-xl sm:text-2xl mr-2 sm:mr-3">❌</span>
            <p class="text-sm sm:text-base font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Summary Cards -->
    @if($realBibits->total() > 0)
    <div class="mb-4 sm:mb-6 grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Total Data</p>
                    <p class="text-xl sm:text-3xl font-bold text-gray-800 mt-1">{{ $realBibits->total() }}</p>
                </div>
                <span class="text-2xl sm:text-4xl">📊</span>
            </div>
        </div>
        
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Total Batang</p>
                    <p class="text-xl sm:text-3xl font-bold text-blue-600 mt-1">
                        {{ number_format(\App\Models\RealBibit::where('id_kelompok', $kelompok->id)->sum('jumlah_btg')) }}
                    </p>
                </div>
                <span class="text-2xl sm:text-4xl">🌳</span>
            </div>
        </div>
        
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Data Anda</p>
                    <p class="text-xl sm:text-3xl font-bold text-yellow-600 mt-1">
                        {{ \App\Models\RealBibit::where('id_kelompok', $kelompok->id)->count() }}
                    </p>
                </div>
                <span class="text-2xl sm:text-4xl">👤</span>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Jenis Bibit</p>
                    <p class="text-xl sm:text-3xl font-bold text-purple-600 mt-1">
                        {{ \App\Models\RealBibit::where('id_kelompok', $kelompok->id)->distinct('jenis_bibit')->count('jenis_bibit') }}
                    </p>
                </div>
                <span class="text-2xl sm:text-4xl">🌱</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Desktop Table View (Hidden on Mobile) -->
    <div class="hidden lg:block bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span>🌱</span>
                    Daftar Realisasi Bibit
                </h3>
                @if($realBibits->total() > 0)
                <span class="text-sm text-green-100">
                    Menampilkan {{ $realBibits->firstItem() }} - {{ $realBibits->lastItem() }} dari {{ $realBibits->total() }} data
                </span>
                @endif
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Dibuat Oleh</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Jenis Bibit</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Golongan</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Jumlah (Btg)</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Tinggi (cm)</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Sertifikat</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($realBibits as $index => $bibit)
                    @php
                        $isOwner = $bibit->id_kelompok == $kelompok->id;
                        $pemilikNama = $bibit->kelompok->user->name ?? 'Unknown';
                    @endphp
                    <tr class="hover:bg-green-50 transition-colors {{ $isOwner ? 'bg-green-50/50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $realBibits->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-green-600 font-bold text-sm">
                                        {{ strtoupper(substr($pemilikNama, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $pemilikNama }}</div>
                                    @if($isOwner)
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            ✓ Anda
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $bibit->jenis_bibit }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($bibit->golongan)
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                    {{ $bibit->golongan }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-lg font-bold text-green-600">{{ number_format($bibit->jumlah_btg) }}</span>
                                <span class="text-xs text-gray-500">batang</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($bibit->tinggi)
                                <span class="text-sm font-semibold text-gray-700">{{ number_format($bibit->tinggi, 2) }}</span>
                                <span class="text-xs text-gray-500 ml-1">cm</span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($bibit->sertifikat)
                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                    {{ $bibit->sertifikat }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                            <div class="flex flex-col">
                                <span class="font-medium">{{ $bibit->created_at->format('d M Y') }}</span>
                                <span class="text-xs text-gray-400">{{ $bibit->created_at->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('kelompok.realisasi-bibit.show', $bibit->id_bibit) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium transition-colors text-sm">
                                    👁️ Lihat
                                </a>
                                
                                @if($isOwner)
                                    <a href="{{ route('kelompok.realisasi-bibit.edit', $bibit->id_bibit) }}" 
                                       class="text-yellow-600 hover:text-yellow-800 font-medium transition-colors text-sm">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('kelompok.realisasi-bibit.destroy', $bibit->id_bibit) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('⚠️ Yakin ingin menghapus data bibit ini?\n\nJenis: {{ $bibit->jenis_bibit }}\nJumlah: {{ number_format($bibit->jumlah_btg) }} batang')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 font-medium transition-colors text-sm">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs italic px-2 py-1 bg-gray-100 rounded">
                                        🔒 Bukan milik Anda
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-7xl mb-6">🌱</span>
                                <p class="text-gray-500 text-2xl font-medium mb-2">Belum ada data realisasi bibit</p>
                                <p class="text-gray-400 text-sm mb-6">Mulai tambahkan data realisasi bibit untuk kelompok Anda</p>
                                <a href="{{ route('kelompok.realisasi-bibit.create') }}" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-medium transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                                    <span class="text-xl">➕</span>
                                    Tambah Data Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($realBibits->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $realBibits->links() }}
        </div>
        @endif
    </div>

    <!-- Mobile Card View (Visible on Mobile/Tablet) -->
    <div class="lg:hidden space-y-4">
        @forelse($realBibits as $index => $bibit)
        @php
            $isOwner = $bibit->id_kelompok == $kelompok->id;
            $pemilikNama = $bibit->kelompok->user->name ?? 'Unknown';
        @endphp
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden {{ $isOwner ? 'ring-2 ring-green-500' : '' }}">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-white text-xs font-semibold bg-white/20 px-2 py-1 rounded">
                            #{{ $realBibits->firstItem() + $index }}
                        </span>
                        <h3 class="text-white font-bold text-sm">{{ $bibit->jenis_bibit }}</h3>
                    </div>
                    @if($bibit->golongan)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-white/90 text-green-700">
                            {{ $bibit->golongan }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Card Content -->
            <div class="p-4 space-y-3">
                <!-- Pemilik -->
                <div class="flex items-center gap-2 pb-2 border-b">
                    <div class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-green-600 font-bold text-sm">
                            {{ strtoupper(substr($pemilikNama, 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">{{ $pemilikNama }}</div>
                        @if($isOwner)
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                ✓ Data Anda
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Jumlah & Tinggi -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-green-50 rounded-lg p-3">
                        <p class="text-xs text-gray-600 mb-1">Jumlah</p>
                        <p class="text-lg font-bold text-green-700">{{ number_format($bibit->jumlah_btg) }}</p>
                        <p class="text-xs text-gray-500">Batang</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3">
                        <p class="text-xs text-gray-600 mb-1">Tinggi</p>
                        <p class="text-lg font-bold text-blue-700">
                            {{ $bibit->tinggi ? number_format($bibit->tinggi, 2) : '-' }}
                        </p>
                        <p class="text-xs text-gray-500">Centimeter</p>
                    </div>
                </div>

                <!-- Sertifikat & Tanggal -->
                <div class="space-y-2 pt-2 border-t">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-600">Sertifikat:</span>
                        @if($bibit->sertifikat)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                {{ $bibit->sertifikat }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-600">Tanggal Input:</span>
                        <span class="text-xs font-medium text-gray-700">
                            {{ $bibit->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-3 gap-2 pt-2">
                    <a href="{{ route('kelompok.realisasi-bibit.show', $bibit->id_bibit) }}" 
                       class="flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-medium">
                        <span class="mr-1">👁️</span>
                        <span>Lihat</span>
                    </a>
                    
                    @if($isOwner)
                        <a href="{{ route('kelompok.realisasi-bibit.edit', $bibit->id_bibit) }}" 
                           class="flex items-center justify-center px-3 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-xs font-medium">
                            <span class="mr-1">✏️</span>
                            <span>Edit</span>
                        </a>
                        <form action="{{ route('kelompok.realisasi-bibit.destroy', $bibit->id_bibit) }}" 
                              method="POST" 
                              onsubmit="return confirm('⚠️ Yakin ingin menghapus data bibit ini?\n\nJenis: {{ $bibit->jenis_bibit }}\nJumlah: {{ number_format($bibit->jumlah_btg) }} batang')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full h-full flex items-center justify-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-medium">
                                <span class="mr-1">🗑️</span>
                                <span>Hapus</span>
                            </button>
                        </form>
                    @else
                        <div class="col-span-2 flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-500 rounded-lg text-xs italic">
                            🔒 Bukan milik Anda
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <span class="text-5xl mb-4 block">🌱</span>
            <p class="text-gray-500 font-medium text-base mb-2">Belum ada data realisasi bibit</p>
            <p class="text-gray-400 text-sm mb-4">Mulai tambahkan data realisasi bibit untuk kelompok Anda</p>
            <a href="{{ route('kelompok.realisasi-bibit.create') }}" 
               class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                ➕ Tambah Data Pertama
            </a>
        </div>
        @endforelse

        <!-- Mobile Pagination -->
        @if($realBibits->hasPages())
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-100">
            {{ $realBibits->links() }}
        </div>
        @endif
    </div>

    <!-- Statistik Detail -->
    @if($realBibits->total() > 0)
    <div class="mt-4 sm:mt-6 bg-white rounded-xl shadow-lg p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="text-xl sm:text-2xl">📈</span>
            Statistik Detail
        </h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <!-- Rata-rata Tinggi -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                <p class="text-xs sm:text-sm text-blue-700 font-medium mb-2">Rata-rata Tinggi Bibit</p>
                <p class="text-2xl sm:text-3xl font-bold text-blue-600">
                    @php
                        $avgHeight = \App\Models\RealBibit::where('id_kelompok', $kelompok->id)
                            ->whereNotNull('tinggi')
                            ->avg('tinggi');
                    @endphp
                    {{ $avgHeight ? number_format($avgHeight, 2) : '0' }} <span class="text-base sm:text-lg">cm</span>
                </p>
            </div>

            <!-- Total Bersertifikat -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200">
                <p class="text-xs sm:text-sm text-purple-700 font-medium mb-2">Bibit Bersertifikat</p>
                <p class="text-2xl sm:text-3xl font-bold text-purple-600">
                    {{ \App\Models\RealBibit::where('id_kelompok', $kelompok->id)->whereNotNull('sertifikat')->count() }}
                    <span class="text-base sm:text-lg">data</span>
                </p>
            </div>

            <!-- Data Terbaru -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                <p class="text-xs sm:text-sm text-green-700 font-medium mb-2">Data Terbaru Ditambahkan</p>
                <p class="text-base sm:text-lg font-bold text-green-600">
                    @php
                        $latestBibit = \App\Models\RealBibit::where('id_kelompok', $kelompok->id)
                            ->latest()
                            ->first();
                    @endphp
                    @if($latestBibit)
                        {{ $latestBibit->created_at->diffForHumans() }}
                    @else
                        Belum ada data
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection