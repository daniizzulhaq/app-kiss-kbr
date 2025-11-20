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

        <!-- Filter & Export Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-wrap items-end gap-4">
                <!-- Filter Form -->
                <form method="GET" action="{{ route('bpdas.permasalahan.index') }}" class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Filter Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <!-- Filter Prioritas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prioritas</label>
                        <select name="prioritas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Prioritas</option>
                            <option value="tinggi" {{ request('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="sedang" {{ request('prioritas') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="rendah" {{ request('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                        </select>
                    </div>

                    <!-- Filter Tanggal Dari -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>

                    <!-- Filter Tanggal Sampai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>

                    <!-- Tombol Filter -->
                    <div class="md:col-span-4 flex gap-3">
                        <button type="submit" 
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-all flex items-center gap-2">
                            <span>🔍</span> Filter
                        </button>
                        <a href="{{ route('bpdas.permasalahan.index') }}" 
                           class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-medium transition-all">
                            Reset
                        </a>
                    </div>
                </form>

                <!-- Export Buttons -->
                <div class="flex gap-3">
                    <form action="{{ route('bpdas.permasalahan.export.pdf') }}" method="GET" class="inline">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="prioritas" value="{{ request('prioritas') }}">
                        <input type="hidden" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                        <input type="hidden" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                        <button type="submit" 
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                            <span>📄</span> Export PDF
                        </button>
                    </form>

                    <form action="{{ route('bpdas.permasalahan.export.excel') }}" method="GET" class="inline">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="hidden" name="prioritas" value="{{ request('prioritas') }}">
                        <input type="hidden" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                        <input type="hidden" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                        <button type="submit" 
                                class="px-6 py-3 bg-green-700 hover:bg-green-800 text-white rounded-lg font-medium transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                            <span>📊</span> Export Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

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