@extends('layouts.dashboard')

@section('title', 'Calon Lokasi - Sistem KBR')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 slide-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">📍 Calon Lokasi</h1>
                <p class="text-gray-600">Kelola data calon lokasi kegiatan kelompok tani</p>
            </div>
            <a href="{{ route('kelompok.calon-lokasi.create') }}" 
               class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                ➕ Tambah Lokasi
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg slide-in">
        <div class="flex items-center">
            <span class="text-2xl mr-3">✅</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Lokasi</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $calonLokasis->total() }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-2xl">
                    📍
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Diverifikasi</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ $calonLokasis->where('status_verifikasi', 'diverifikasi')->count() }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center text-2xl">
                    ✅
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Menunggu</p>
                    <h3 class="text-3xl font-bold text-yellow-600">{{ $calonLokasis->where('status_verifikasi', 'pending')->count() }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl flex items-center justify-center text-2xl">
                    ⏳
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden slide-in">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kelompok Desa</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kecamatan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kabupaten</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Koordinat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($calonLokasis as $index => $lokasi)
                    <tr class="hover:bg-green-50 transition-colors duration-200">
                        <td class="px-6 py-4 text-gray-700">{{ $calonLokasis->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $lokasi->nama_kelompok_desa }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $lokasi->kecamatan }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $lokasi->kabupaten }}</td>
                        <td class="px-6 py-4">
                            @if($lokasi->latitude && $lokasi->longitude)
                                <span class="text-xs text-green-700 font-mono bg-green-50 px-2 py-1 rounded">
                                    {{ number_format($lokasi->latitude, 6) }}, {{ number_format($lokasi->longitude, 6) }}
                                </span>
                            @else
                                <span class="text-xs text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {!! $lokasi->status_badge !!}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('kelompok.calon-lokasi.show', $lokasi) }}" 
                                   class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                                    👁️ Lihat
                                </a>
                                @if($lokasi->status_verifikasi === 'pending')
                                <a href="{{ route('kelompok.calon-lokasi.edit', $lokasi) }}" 
                                   class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-sm font-medium">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('kelompok.calon-lokasi.destroy', $lokasi) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus lokasi ini?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                        🗑️ Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-6xl mb-4">📍</span>
                                <p class="text-gray-500 font-medium">Belum ada calon lokasi</p>
                                <a href="{{ route('kelompok.calon-lokasi.create') }}" 
                                   class="mt-4 text-green-600 hover:text-green-700 font-semibold">
                                    + Tambahkan lokasi pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($calonLokasis->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $calonLokasis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection