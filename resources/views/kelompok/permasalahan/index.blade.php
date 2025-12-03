@extends('layouts.dashboard')

@section('title', 'Laporan Permasalahan')

@section('content')
<div class="py-6 sm:py-12">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800">Laporan Permasalahan</h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Kelola laporan permasalahan Anda</p>
            </div>
            <a href="{{ route('kelompok.permasalahan.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-lg font-medium transition-all duration-300 shadow-lg hover:shadow-xl flex items-center justify-center gap-2 text-sm sm:text-base">
                <span class="text-lg sm:text-xl">➕</span>
                <span>Buat Laporan Baru</span>
            </a>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg">
                <p class="font-medium text-sm sm:text-base">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded-lg">
                <p class="font-medium text-sm sm:text-base">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Desktop Table View (lg and above) -->
        <div class="hidden lg:block bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelompok</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sarpras</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bibit</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Tanam</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permasalahan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($permasalahan as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->kelompok }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->sarpras }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->bibit }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->lokasi_tanam }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $item->permasalahan }}">
                                    {{ Str::limit($item->permasalahan, 50) }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                        @if($item->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($item->status == 'diproses') bg-blue-100 text-blue-800
                                        @elseif($item->status == 'selesai') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-center space-x-2">
                                    <a href="{{ route('kelompok.permasalahan.show', $item->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 font-medium">Detail</a>
                                    @if($item->status == 'pending')
                                        <form action="{{ route('kelompok.permasalahan.destroy', $item->id) }}" 
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <span class="text-6xl mb-4">📋</span>
                                        <p class="text-lg font-medium">Belum ada laporan permasalahan</p>
                                        <p class="text-sm mt-2">Klik tombol "Buat Laporan Baru" untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($permasalahan->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $permasalahan->links() }}
                </div>
            @endif
        </div>

        <!-- Mobile & Tablet Card View (below lg) -->
        <div class="lg:hidden space-y-4">
            @forelse($permasalahan as $item)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-semibold text-gray-500 bg-gray-200 px-2 py-1 rounded">
                                        #{{ $loop->iteration }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($item->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($item->status == 'diproses') bg-blue-100 text-blue-800
                                        @elseif($item->status == 'selesai') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-gray-900">
                                    {{ $item->kelompok }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-4 space-y-3">
                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                                <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                    <span>🛠️</span>
                                    <span>Sarpras</span>
                                </p>
                                <p class="text-sm font-semibold text-gray-900 break-words">
                                    {{ $item->sarpras }}
                                </p>
                            </div>

                            <div class="bg-green-50 p-3 rounded-lg border border-green-200">
                                <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                    <span>🌱</span>
                                    <span>Bibit</span>
                                </p>
                                <p class="text-sm font-semibold text-gray-900 break-words">
                                    {{ $item->bibit }}
                                </p>
                            </div>

                            <div class="bg-purple-50 p-3 rounded-lg border border-purple-200 sm:col-span-2">
                                <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                    <span>📍</span>
                                    <span>Lokasi Tanam</span>
                                </p>
                                <p class="text-sm font-semibold text-gray-900 break-words">
                                    {{ $item->lokasi_tanam }}
                                </p>
                            </div>
                        </div>

                        <!-- Permasalahan -->
                        <div class="bg-red-50 p-3 rounded-lg border border-red-200">
                            <p class="text-xs text-gray-600 mb-1 flex items-center gap-1">
                                <span>⚠️</span>
                                <span>Permasalahan</span>
                            </p>
                            <p class="text-sm text-gray-900 break-words">
                                {{ $item->permasalahan }}
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-2 flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('kelompok.permasalahan.show', $item->id) }}" 
                               class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 text-sm font-semibold">
                                <span>👁️</span>
                                <span>Lihat Detail</span>
                            </a>
                            
                            @if($item->status == 'pending')
                                <form action="{{ route('kelompok.permasalahan.destroy', $item->id) }}" 
                                      method="POST" 
                                      class="flex-1"
                                      onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-300 text-sm font-semibold">
                                        <span>🗑️</span>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-lg p-8 sm:p-12 text-center">
                    <span class="text-5xl sm:text-6xl mb-4 block">📋</span>
                    <p class="text-base sm:text-lg font-medium text-gray-500">Belum ada laporan permasalahan</p>
                    <p class="text-xs sm:text-sm text-gray-400 mt-2">Klik tombol "Buat Laporan Baru" untuk memulai</p>
                </div>
            @endforelse

            <!-- Mobile Pagination -->
            @if($permasalahan->hasPages())
                <div class="bg-white rounded-xl shadow-lg p-4">
                    {{ $permasalahan->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection