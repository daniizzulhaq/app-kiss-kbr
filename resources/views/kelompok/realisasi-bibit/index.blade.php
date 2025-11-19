@extends('layouts.dashboard')

@section('title', 'Rencana Bibit - Sistem KBR')
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Realisasi Bibit</h1>
            <p class="text-gray-600 mt-1">Kelola data realisasi bibit kelompok Anda</p>
        </div>
        <a href="{{ route('kelompok.realisasi-bibit.create') }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-all shadow-lg hover:shadow-xl">
            <span class="text-xl">➕</span>
            <span class="font-medium">Tambah Data</span>
        </a>
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
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $realBibits->firstItem() + $index }}
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
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
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
</div>
@endsection