@extends('layouts.dashboard')

@section('title', 'Realisasi Bibit - Sistem KBR')
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Realisasi Bibit</h1>
                <p class="text-gray-600 mt-1">Kelola data realisasi bibit kelompok <strong class="text-green-600">{{ $kelompok->nama_kelompok }}</strong></p>
            </div>
            <a href="{{ route('kelompok.realisasi-bibit.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-all shadow-lg hover:shadow-xl">
                <span class="text-xl">➕</span>
                <span class="font-medium">Tambah Data</span>
            </a>
        </div>
        
        <!-- Info Box -->
        <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
            <div class="flex items-start">
                <span class="text-2xl mr-3">ℹ️</span>
                <div>
                    <p class="text-sm text-blue-800">
                        <strong>Informasi:</strong> Data realisasi bibit tahun {{ date('Y') }} untuk kelompok <strong>{{ $kelompok->nama_kelompok }}</strong>.
                        Anda dapat melihat semua data anggota kelompok, namun hanya dapat mengedit dan menghapus data milik Anda sendiri.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-2xl mr-3">✅</span>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-2xl mr-3">❌</span>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Summary Cards - Tampilkan di atas tabel -->
    @if($realBibits->total() > 0)
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Total Data Bibit</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $realBibits->total() }}</p>
                </div>
                <span class="text-4xl">📊</span>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Total Batang</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">
                        {{ number_format(\App\Models\RealBibit::where('id_kelompok', $kelompok->id)->sum('jumlah_btg')) }}
                    </p>
                </div>
                <span class="text-4xl">🌳</span>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Data Anda</p>
                    <p class="text-3xl font-bold text-yellow-600 mt-1">
                        {{ \App\Models\RealBibit::where('id_kelompok', $kelompok->id)->count() }}
                    </p>
                </div>
                <span class="text-4xl">👤</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Jenis Bibit</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1">
                        {{ \App\Models\RealBibit::where('id_kelompok', $kelompok->id)->distinct('jenis_bibit')->count('jenis_bibit') }}
                    </p>
                </div>
                <span class="text-4xl">🌱</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Card Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Table Header with Stats -->
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
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal Input</th>
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
                                   class="text-blue-600 hover:text-blue-800 font-medium transition-colors"
                                   title="Lihat Detail">
                                    👁️ Lihat
                                </a>
                                
                                @if($isOwner)
                                    <a href="{{ route('kelompok.realisasi-bibit.edit', $bibit->id_bibit) }}" 
                                       class="text-yellow-600 hover:text-yellow-800 font-medium transition-colors"
                                       title="Edit Data">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('kelompok.realisasi-bibit.destroy', $bibit->id_bibit) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('⚠️ Yakin ingin menghapus data bibit ini?\n\nJenis: {{ $bibit->jenis_bibit }}\nJumlah: {{ number_format($bibit->jumlah_btg) }} batang')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 font-medium transition-colors"
                                                title="Hapus Data">
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

    <!-- Data Detail Cards - Tampilkan di bawah tabel jika ada data -->
    @if($realBibits->total() > 0)
    <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="text-2xl">📈</span>
            Statistik Detail
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Rata-rata Tinggi -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                <p class="text-sm text-blue-700 font-medium mb-2">Rata-rata Tinggi Bibit</p>
                <p class="text-3xl font-bold text-blue-600">
                    @php
                        $avgHeight = \App\Models\RealBibit::where('id_kelompok', $kelompok->id)
                            ->whereNotNull('tinggi')
                            ->avg('tinggi');
                    @endphp
                    {{ $avgHeight ? number_format($avgHeight, 2) : '0' }} <span class="text-lg">cm</span>
                </p>
            </div>

            <!-- Total Jenis dengan Sertifikat -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200">
                <p class="text-sm text-purple-700 font-medium mb-2">Bibit Bersertifikat</p>
                <p class="text-3xl font-bold text-purple-600">
                    {{ \App\Models\RealBibit::where('id_kelompok', $kelompok->id)->whereNotNull('sertifikat')->count() }}
                    <span class="text-lg">data</span>
                </p>
            </div>

            <!-- Data Terbaru -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                <p class="text-sm text-green-700 font-medium mb-2">Data Terbaru Ditambahkan</p>
                <p class="text-lg font-bold text-green-600">
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