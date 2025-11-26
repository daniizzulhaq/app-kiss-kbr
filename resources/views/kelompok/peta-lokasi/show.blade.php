@extends('layouts.dashboard')

@section('title', 'Detail Peta Lokasi - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 py-6 sm:py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('kelompok.peta-lokasi.index') }}" 
               class="text-gray-600 hover:text-gray-800 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                Detail Peta Lokasi
            </h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto">
        <!-- Info Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">{{ $petaLokasi->judul }}</h2>
            </div>

            <div class="p-6 space-y-4">
                <!-- Status -->
                <div class="flex items-center justify-between pb-4 border-b">
                    <span class="text-gray-600 font-medium">Status:</span>
                    {!! $petaLokasi->status_badge !!}
                </div>

                <!-- Kelompok Info -->
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 font-medium">Kelompok:</span>
                    <span class="font-semibold text-gray-900">{{ $petaLokasi->kelompok->nama_kelompok }}</span>
                </div>

                <!-- Tanggal Upload -->
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 font-medium">Tanggal Upload:</span>
                    <span class="text-gray-900">{{ $petaLokasi->created_at->format('d M Y, H:i') }}</span>
                </div>

                <!-- File Count -->
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 font-medium">Jumlah File:</span>
                    <span class="font-semibold text-gray-900">{{ $petaLokasi->file_count }} PDF</span>
                </div>

                <!-- Total Size -->
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 font-medium">Total Ukuran:</span>
                    <span class="font-semibold text-gray-900">{{ $petaLokasi->formatted_total_size }}</span>
                </div>

                <!-- Keterangan -->
                @if($petaLokasi->keterangan)
                <div class="pt-4 border-t">
                    <span class="text-gray-600 font-medium block mb-2">Keterangan:</span>
                    <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $petaLokasi->keterangan }}</p>
                </div>
                @endif

                <!-- Catatan BPDAS -->
                @if($petaLokasi->status === 'ditolak' && $petaLokasi->catatan_bpdas)
                <div class="pt-4 border-t">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <p class="font-medium text-red-800 mb-2">Alasan Penolakan:</p>
                        <p class="text-red-700">{{ $petaLokasi->catatan_bpdas }}</p>
                    </div>
                </div>
                @endif

                @if($petaLokasi->status === 'diterima' && $petaLokasi->verified_at)
                <div class="pt-4 border-t">
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                        <p class="font-medium text-green-800 mb-1">✅ Diverifikasi oleh BPDAS</p>
                        <p class="text-sm text-green-700">
                            {{ $petaLokasi->verified_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Files List Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span>📎</span>
                    Daftar File PDF ({{ $petaLokasi->file_count }})
                </h3>
            </div>

            <div class="p-6">
                <div class="space-y-3">
                    @foreach($petaLokasi->files as $index => $file)
                    <div class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition-colors">
                        <div class="flex items-center gap-4 flex-1">
                            <!-- File Icon -->
                            <div class="flex-shrink-0">
                                <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                            </div>

                            <!-- File Info -->
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">
                                    Dokumen {{ $index + 1 }}: {{ $file['name'] }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ number_format($file['size'] / 1024 / 1024, 2) }} MB
                                </p>
                            </div>
                        </div>

                        <!-- Download Button -->
                        <a href="{{ Storage::url($file['path']) }}" 
                           target="_blank"
                           download
                           class="flex-shrink-0 ml-4 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download
                        </a>
                    </div>
                    @endforeach
                </div>

                <!-- Download All Button -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <button onclick="downloadAllFiles()" 
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold flex items-center justify-center gap-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Semua File ({{ $petaLokasi->file_count }} PDF)
                    </button>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex gap-3">
            <a href="{{ route('kelompok.peta-lokasi.index') }}" 
               class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center font-medium">
                Kembali
            </a>

            @if($petaLokasi->status !== 'diterima')
            <a href="{{ route('kelompok.peta-lokasi.edit', $petaLokasi) }}" 
               class="flex-1 px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors text-center font-medium">
                Edit Peta Lokasi
            </a>
            @endif
        </div>
    </div>
</div>

<script>
function downloadAllFiles() {
    const files = @json($petaLokasi->files);
    
    files.forEach((file, index) => {
        setTimeout(() => {
            const link = document.createElement('a');
            link.href = '/storage/' + file.path;
            link.download = file.name;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }, index * 500); // Delay 500ms per file
    });
}
</script>
@endsection