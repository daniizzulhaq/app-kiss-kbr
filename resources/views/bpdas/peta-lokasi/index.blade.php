@extends('layouts.dashboard')

@section('title', 'Verifikasi Peta Lokasi - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 flex items-center gap-2">
            <span class="text-2xl">📍</span>
            Verifikasi Peta Lokasi Kelompok
        </h1>
        <p class="text-gray-600 mt-2">Kelola dan verifikasi peta lokasi yang diupload oleh kelompok</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-xl mr-3">✅</span>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-xl mr-3">❌</span>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Total Peta</p>
                    <p class="text-xl sm:text-3xl font-bold text-gray-800 mt-1">{{ \App\Models\PetaLokasi::count() }}</p>
                </div>
                <span class="text-2xl sm:text-4xl">📊</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Menunggu</p>
                    <p class="text-xl sm:text-3xl font-bold text-yellow-600 mt-1">{{ \App\Models\PetaLokasi::where('status', 'pending')->count() }}</p>
                </div>
                <span class="text-2xl sm:text-4xl">⏳</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Diterima</p>
                    <p class="text-xl sm:text-3xl font-bold text-green-600 mt-1">{{ \App\Models\PetaLokasi::where('status', 'diterima')->count() }}</p>
                </div>
                <span class="text-2xl sm:text-4xl">✅</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Ditolak</p>
                    <p class="text-xl sm:text-3xl font-bold text-red-600 mt-1">{{ \App\Models\PetaLokasi::where('status', 'ditolak')->count() }}</p>
                </div>
                <span class="text-2xl sm:text-4xl">❌</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('bpdas.peta-lokasi.index') }}" class="flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari judul, kelompok, atau pengelola..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
            </div>

            <!-- Status Filter -->
            <div class="sm:w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Kelompok Filter -->
            <div class="sm:w-64">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelompok</label>
                <select name="kelompok_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
                    <option value="">Semua Kelompok</option>
                    @foreach($kelompoks as $kelompok)
                        <option value="{{ $kelompok->id }}" {{ request('kelompok_id') == $kelompok->id ? 'selected' : '' }}>
                            {{ $kelompok->nama_kelompok }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="h-[42px] bg-green-600 hover:bg-green-700 text-white px-4 sm:px-6 py-2 rounded-lg transition-all shadow hover:shadow-lg">
                    Filter
                </button>
                <a href="{{ route('bpdas.peta-lokasi.index') }}" 
                   class="h-[42px] bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 sm:px-6 py-2 rounded-lg transition-all inline-flex items-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span>📋</span>
                    Daftar Peta Lokasi
                </h3>
                @if($petaLokasis->total() > 0)
                <span class="text-sm text-green-100">
                    Menampilkan {{ $petaLokasis->firstItem() }} - {{ $petaLokasis->lastItem() }} dari {{ $petaLokasis->total() }} data
                </span>
                @endif
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Judul & File</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kelompok</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Pengelola</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($petaLokasis as $index => $peta)
                    <tr class="hover:bg-green-50 transition-colors">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $petaLokasis->firstItem() + $index }}
                        </td>
                        <td class="px-4 sm:px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $peta->judul }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    📎 {{ $peta->file_count }} File PDF
                                </span>
                                <span class="text-xs text-gray-500">
                                    💾 {{ $peta->formatted_total_size }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $peta->kelompok->nama_kelompok }}</div>
                            <div class="text-xs text-gray-500">{{ $peta->kelompok->desa }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">
                            {{ $peta->user->name }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">{{ $peta->created_at->format('d M Y') }}</span>
                                <span class="text-xs text-gray-500">{{ $peta->created_at->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                            {!! $peta->status_badge !!}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('bpdas.peta-lokasi.show', $peta) }}" 
                                   class="text-blue-600 hover:text-blue-900 p-2 rounded hover:bg-blue-50 transition-colors" 
                                   title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                
                                @if(!empty($peta->files) && is_array($peta->files))
                                    @foreach($peta->files as $file)
                                    <a href="{{ Storage::url($file['path']) }}" 
                                       target="_blank" 
                                       class="text-green-600 hover:text-green-900 p-2 rounded hover:bg-green-50 transition-colors" 
                                       title="Download {{ $file['name'] }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </a>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 sm:px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="text-2xl font-medium text-gray-900 mb-2">Tidak ada data</h3>
                                <p class="text-sm text-gray-500 mb-6">Belum ada peta lokasi yang diupload kelompok.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($petaLokasis->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $petaLokasis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection