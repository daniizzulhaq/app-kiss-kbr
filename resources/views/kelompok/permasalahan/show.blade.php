@extends('layouts.dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('kelompok.permasalahan.index') }}" 
               class="text-green-600 hover:text-green-700 font-medium flex items-center gap-2 mb-4">
                <span>←</span> Kembali ke Daftar
            </a>
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Detail Laporan Permasalahan</h2>
                    <p class="text-gray-600 mt-1">ID: #{{ $permasalahan->id }}</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $permasalahan->getStatusBadgeClass() }}">
                    {{ $permasalahan->getStatusLabel() }}
                </span>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Detail Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-8 space-y-6">
                <!-- Informasi Umum -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">📋</span>
                        Informasi Umum
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Kelompok</label>
                            <p class="mt-1 text-gray-900">{{ $permasalahan->kelompok ?? $permasalahan->kelompokRelasi->nama_kelompok ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Sarana Prasarana</label>
                            <p class="mt-1 text-gray-900">{{ $permasalahan->sarpras ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Bibit</label>
                            <p class="mt-1 text-gray-900">{{ $permasalahan->bibit ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Lokasi Tanam</label>
                            <p class="mt-1 text-gray-900">{{ $permasalahan->lokasi_tanam ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Prioritas</label>
                            <p class="mt-1">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $permasalahan->getPrioritasBadgeClass() }}">
                                    {{ ucfirst($permasalahan->prioritas ?? '-') }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Laporan</label>
                            <p class="mt-1 text-gray-900">{{ $permasalahan->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Masalah -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">⚠️</span>
                        Deskripsi Permasalahan
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $permasalahan->permasalahan ?? '-' }}</p>
                    </div>
                </div>

                <!-- Solusi dari BPDAS -->
                @if($permasalahan->solusi)
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">💡</span>
                        Solusi dari BPDAS
                    </h3>
                    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-line">{{ $permasalahan->solusi }}</p>

                        @if($permasalahan->ditangani_pada)
                            <div class="mt-4 pt-4 border-t border-green-200 text-sm text-gray-600">
                                <p><strong>Ditangani oleh:</strong> {{ $permasalahan->penangananBpdas->name ?? 'BPDAS' }}</p>
                                <p><strong>Pada:</strong> {{ $permasalahan->ditangani_pada->format('d M Y, H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Tanggapan Kelompok -->
                @if($permasalahan->tanggapan_kelompok)
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">✅</span>
                        Tanggapan Kelompok
                    </h3>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $permasalahan->tanggapan_kelompok }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <div class="flex gap-3 justify-end">
                    @if($permasalahan->status === 'selesai' && !$permasalahan->tanggapan_kelompok)
                    <button onclick="document.getElementById('tanggapanModal').classList.remove('hidden')"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        Berikan Tanggapan
                    </button>
                    @endif
                    
                    @if($permasalahan->status !== 'selesai')
                    <a href="{{ route('kelompok.permasalahan.edit', $permasalahan) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                        Edit Laporan
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tanggapan -->
<div id="tanggapanModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full">
        <form action="{{ route('kelompok.permasalahan.tanggapan.store', $permasalahan) }}" method="POST">
            @csrf
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Berikan Tanggapan</h3>
                <textarea name="tanggapan_kelompok" 
                          rows="5" 
                          class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                          placeholder="Tuliskan tanggapan Anda terhadap solusi yang diberikan..."
                          required></textarea>
            </div>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex gap-3 justify-end rounded-b-xl">
                <button type="button" 
                        onclick="document.getElementById('tanggapanModal').classList.add('hidden')"
                        class="px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-lg font-medium transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition">
                    Kirim Tanggapan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
