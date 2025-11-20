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
                        <strong>Informasi:</strong> Anda dapat melihat data realisasi bibit dari semua anggota kelompok <strong>{{ $kelompok->nama_kelompok }}</strong>.
                        Namun, Anda hanya dapat mengedit dan menghapus data yang Anda buat sendiri.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-2xl mr-3">✅</span>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Card Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-green-600 to-green-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Dibuat Oleh</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Jenis Bibit</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Golongan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Jumlah (Btg)</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Tinggi (cm)</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Sertifikat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($realBibits as $index => $bibit)
                    @php
                        $isOwner = $bibit->id_kelompok == $kelompok->id;
                        $pemilikNama = $bibit->kelompok->user->name ?? 'Unknown';
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors {{ $isOwner ? 'bg-green-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $realBibits->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-900">{{ $pemilikNama }}</span>
                                @if($isOwner)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Anda
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $bibit->jenis_bibit }}</span>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $bibit->sertifikat ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex gap-2">
                                <a href="{{ route('kelompok.realisasi-bibit.show', $bibit->id_bibit) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    👁️ Lihat
                                </a>
                                
                                @if($isOwner)
                                    <a href="{{ route('kelompok.realisasi-bibit.edit', $bibit->id_bibit) }}" 
                                       class="text-yellow-600 hover:text-yellow-800 font-medium">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('kelompok.realisasi-bibit.destroy', $bibit->id_bibit) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs italic">Bukan milik Anda</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-6xl mb-4">🌱</span>
                                <p class="text-gray-500 text-lg font-medium">Belum ada data realisasi bibit</p>
                                <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Data" untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($realBibits->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $realBibits->links() }}
        </div>
        @endif
    </div>

    <!-- Summary Card -->
    @if($realBibits->count() > 0)
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Data</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $realBibits->total() }}</p>
                </div>
                <span class="text-4xl">📊</span>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Batang</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($realBibits->sum('jumlah_btg')) }}</p>
                </div>
                <span class="text-4xl">🌳</span>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Data Anda</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $realBibits->where('id_kelompok', $kelompok->id)->count() }}</p>
                </div>
                <span class="text-4xl">👤</span>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection