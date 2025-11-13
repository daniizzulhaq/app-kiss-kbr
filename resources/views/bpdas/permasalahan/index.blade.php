@extends('layouts.dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Laporan Permasalahan dari Kelompok</h2>
            <p class="text-gray-600 mt-1">Kelola dan tangani laporan permasalahan</p>
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
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Kelompok</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Sarpras</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Bibit</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Lokasi Tanam</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Permasalahan</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Solusi</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($permasalahan as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $permasalahan->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="text-gray-900 font-medium">{{ $item->kelompok }}</div>
                                    <div class="text-gray-500 text-xs">{{ $item->kelompokUser->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->sarpras }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->bibit }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->lokasi_tanam }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="max-w-xs truncate" title="{{ $item->permasalahan }}">
                                        {{ Str::limit($item->permasalahan, 50) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->getStatusBadgeClass() }}">
                                        {{ $item->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($item->solusi)
                                        <div class="max-w-xs truncate" title="{{ $item->solusi }}">
                                            {{ Str::limit($item->solusi, 50) }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Belum ada</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('bpdas.permasalahan.show', $item) }}" 
                                       class="text-blue-600 hover:text-blue-900">Tangani</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <span class="text-6xl mb-4">📋</span>
                                        <p class="text-lg font-medium">Belum ada laporan permasalahan</p>
                                        <p class="text-sm mt-2">Laporan dari kelompok akan muncul di sini</p>
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
