@extends('layouts.dashboard')

@section('title', 'Laporan Permasalahan')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Laporan Permasalahan</h2>
                <p class="text-gray-600 mt-1">Kelola laporan permasalahan Anda</p>
            </div>
            <a href="{{ route('kelompok.permasalahan.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                <span class="text-xl">➕</span>
                <span>Buat Laporan Baru</span>
            </a>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Tabel Permasalahan -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
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
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
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
                                <td class="px-6 py-4 text-sm text-gray-900 capitalize">{{ $item->prioritas }}</td>
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
                                       class="text-blue-600 hover:text-blue-900">Detail</a>
                                    @if($item->status == 'pending')
                                        <form action="{{ route('kelompok.permasalahan.destroy', $item->id) }}" 
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
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
    </div>
</div>
@endsection
