@extends('layouts.dashboard')

@section('title', 'Data Peta Lokasi - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="text-xl sm:text-2xl">📍</span>
                    Data Peta Lokasi
                </h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">
                    Kelola peta lokasi kelompok <strong class="text-green-600">{{ $kelompok->nama_kelompok }}</strong>
                </p>
            </div>
            <a href="{{ route('kelompok.peta-lokasi.create') }}" 
               class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-xl text-sm sm:text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="font-medium">Upload Peta Baru</span>
            </a>
        </div>
        
        <!-- Info Box -->
        <div class="mt-4 bg-blue-50 border-l-4 border-blue-500 p-3 sm:p-4 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-semibold text-blue-800 text-sm sm:text-base">Kelompok: {{ $kelompok->nama_kelompok }}</p>
                
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
    @php
        // Handle both variable names for compatibility
        $dataPeta = $petaLokasis ?? $petaLokasi ?? collect();
        $totalPeta = method_exists($dataPeta, 'total') ? $dataPeta->total() : $dataPeta->count();
    @endphp

    @if($totalPeta > 0)
    <div class="mb-4 sm:mb-6 grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Total Peta</p>
                    <p class="text-xl sm:text-3xl font-bold text-gray-800 mt-1">{{ $totalPeta }}</p>
                </div>
                <span class="text-2xl sm:text-4xl">📊</span>
            </div>
        </div>
        
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Diterima</p>
                    <p class="text-xl sm:text-3xl font-bold text-blue-600 mt-1">
                        {{ $dataPeta->where('status', 'diterima')->count() }}
                    </p>
                </div>
                <span class="text-2xl sm:text-4xl">✅</span>
            </div>
        </div>
        
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Pending</p>
                    <p class="text-xl sm:text-3xl font-bold text-yellow-600 mt-1">
                        {{ $dataPeta->where('status', 'pending')->count() }}
                    </p>
                </div>
                <span class="text-2xl sm:text-4xl">⏳</span>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-lg border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600 font-medium uppercase">Ditolak</p>
                    <p class="text-xl sm:text-3xl font-bold text-red-600 mt-1">
                        {{ $dataPeta->where('status', 'ditolak')->count() }}
                    </p>
                </div>
                <span class="text-2xl sm:text-4xl">❌</span>
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
                    <span>📍</span>
                    Daftar Peta Lokasi
                </h3>
                @if($totalPeta > 0)
                <span class="text-sm text-green-100">
                    @if(method_exists($dataPeta, 'firstItem'))
                        Menampilkan {{ $dataPeta->firstItem() }} - {{ $dataPeta->lastItem() }} dari {{ $totalPeta }} data
                    @else
                        Total {{ $totalPeta }} data
                    @endif
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
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Judul & File</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tanggal Upload</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($dataPeta as $index => $peta)
                    <tr class="hover:bg-green-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            @if(method_exists($dataPeta, 'firstItem'))
                                {{ $dataPeta->firstItem() + $index }}
                            @else
                                {{ $index + 1 }}
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $peta->judul }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    📎 {{ $peta->file_count }} File PDF
                                </span>
                                <span class="text-xs text-gray-500">
                                    💾 {{ $peta->formatted_total_size }}
                                </span>
                            </div>
                            
                            <!-- Tampilkan daftar file PDF -->
                            @if(!empty($peta->files) && is_array($peta->files))
                            <div class="mt-2 space-y-1">
                                @foreach($peta->files as $file)
                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                    <svg class="w-3 h-3 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="truncate">{{ $file['name'] ?? 'Unnamed file' }}</span>
                                    <span class="text-gray-400">({{ isset($file['size']) ? number_format($file['size'] / 1024 / 1024, 2) : '0.00' }} MB)</span>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            
                            @if($peta->keterangan)
                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($peta->keterangan, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">{{ $peta->created_at->format('d M Y') }}</span>
                                <span class="text-xs text-gray-500">{{ $peta->created_at->format('H:i') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            {!! $peta->status_badge !!}
                            @if($peta->status === 'ditolak' && $peta->catatan_bpdas)
                                <div class="text-xs text-red-600 mt-1 italic">
                                    {{ Str::limit($peta->catatan_bpdas, 40) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('kelompok.peta-lokasi.show', $peta) }}" 
                                   class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors" 
                                   title="Lihat Detail & Download">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                
                                @if($peta->status !== 'diterima')
                                    <a href="{{ route('kelompok.peta-lokasi.edit', $peta) }}" 
                                       class="text-yellow-600 hover:text-yellow-900 p-1 rounded hover:bg-yellow-50 transition-colors" 
                                       title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                @endif

                                <form action="{{ route('kelompok.peta-lokasi.destroy', $peta) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus peta lokasi \"{{ $peta->judul }}\" beserta {{ $peta->file_count }} file PDF?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors" 
                                            title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="text-2xl font-medium text-gray-900 mb-2">Belum ada peta lokasi</h3>
                                <p class="text-sm text-gray-500 mb-6">Mulai upload peta lokasi kelompok Anda.</p>
                                <a href="{{ route('kelompok.peta-lokasi.create') }}" 
                                   class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Upload Peta Lokasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($dataPeta, 'hasPages') && $dataPeta->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $dataPeta->links() }}
        </div>
        @endif
    </div>

    <!-- Mobile Card View (Visible on Mobile/Tablet) -->
    <div class="lg:hidden space-y-4">
        @forelse($dataPeta as $index => $peta)
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3">
                <div class="flex items-center justify-between">
                    <span class="text-white text-xs font-semibold bg-white/20 px-2 py-1 rounded">
                        @if(method_exists($dataPeta, 'firstItem'))
                            #{{ $dataPeta->firstItem() + $index }}
                        @else
                            #{{ $index + 1 }}
                        @endif
                    </span>
                    <h3 class="text-white font-bold text-sm flex-1 ml-3">{{ Str::limit($peta->judul, 30) }}</h3>
                </div>
            </div>

            <!-- Card Content -->
            <div class="p-4 space-y-3">
                <!-- File Info Badge -->
                <div class="flex items-center gap-2 pb-2 border-b">
                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        📎 {{ $peta->file_count }} File PDF
                    </span>
                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                        💾 {{ $peta->formatted_total_size }}
                    </span>
                </div>

                <!-- Daftar File PDF -->
                @if(!empty($peta->files) && is_array($peta->files))
                <div class="pb-2 border-b">
                    <p class="text-xs text-gray-600 mb-2">File PDF:</p>
                    <div class="space-y-1">
                        @foreach($peta->files as $file)
                        <div class="flex items-center gap-2 text-xs text-gray-700">
                            <svg class="w-3 h-3 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="truncate flex-1">{{ $file['name'] ?? 'Unnamed file' }}</span>
                            <span class="text-gray-400 text-xs">{{ isset($file['size']) ? number_format($file['size'] / 1024 / 1024, 2) : '0.00' }} MB</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Deskripsi -->
                @if($peta->keterangan)
                <div class="pb-2 border-b">
                    <p class="text-xs text-gray-600 mb-1">Keterangan:</p>
                    <p class="text-sm text-gray-700">{{ Str::limit($peta->keterangan, 80) }}</p>
                </div>
                @endif

                <!-- Tanggal & Status -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-600">Tanggal Upload:</span>
                        <span class="text-xs font-medium text-gray-700">
                            {{ $peta->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-600">Status:</span>
                        {!! $peta->status_badge !!}
                    </div>
                    @if($peta->status === 'ditolak' && $peta->catatan_bpdas)
                    <div class="bg-red-50 rounded-lg p-2 border border-red-200">
                        <p class="text-xs text-red-700">
                            <strong>Alasan:</strong> {{ $peta->catatan_bpdas }}
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <a href="{{ route('kelompok.peta-lokasi.show', $peta) }}" 
                       class="flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Detail
                    </a>
                    
                    @if($peta->status !== 'diterima')
                    <a href="{{ route('kelompok.peta-lokasi.edit', $peta) }}" 
                       class="flex items-center justify-center px-3 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-xs font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    @endif
                    
                    <form action="{{ route('kelompok.peta-lokasi.destroy', $peta) }}" 
                          method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus peta lokasi \"{{ $peta->judul }}\" beserta {{ $peta->file_count }} file PDF?')"
                          class="{{ $peta->status !== 'diterima' ? '' : 'col-span-2' }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full h-full flex items-center justify-center px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs font-medium">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <svg class="mx-auto h-20 w-20 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-500 font-medium text-base mb-2">Belum ada peta lokasi</p>
            <p class="text-gray-400 text-sm mb-4">Mulai upload 2-5 file PDF peta lokasi kelompok Anda</p>
            <a href="{{ route('kelompok.peta-lokasi.create') }}" 
               class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold text-sm">
                📍 Upload Peta Lokasi
            </a>
        </div>
        @endforelse

        <!-- Mobile Pagination -->
        @if(method_exists($dataPeta, 'hasPages') && $dataPeta->hasPages())
        <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-100">
            {{ $dataPeta->links() }}
        </div>
        @endif
    </div>
</div>
@endsection