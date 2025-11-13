@extends('layouts.dashboard')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('bpdas.permasalahan.index') }}" 
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

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Detail Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="p-8 space-y-6">
                <!-- Informasi Pelapor -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">👤</span>
                        Informasi Pelapor
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nama Pengguna</label>
                            <p class="mt-1 text-gray-900">{{ $permasalahan->kelompokUser->name ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-gray-900">{{ $permasalahan->kelompokUser->email ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Informasi Laporan -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-2xl">📋</span>
                        Informasi Laporan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Kelompok</label>
                            <p class="mt-1 text-gray-900">{{ $permasalahan->kelompok }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Sarana Prasarana</label>
                            <p class="mt-1 text-gray-900">{{ $permasalahan->sarpras }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Jenis Bibit</label>
                            <p class="mt-1 text-gray-900">{{ $permasalahan->bibit }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Lokasi Tanam</label>
                            <p class="mt-1 text-gray-900">{{ $permasalahan->lokasi_tanam }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Prioritas</label>
                            <p class="mt-1">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $permasalahan->getPrioritasBadgeClass() }}">
                                    {{ ucfirst($permasalahan->prioritas) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Tanggal Laporan</label>
                            <p class="mt-1 text-gray-900">{{ $permasalahan->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                <!-- Permasalahan -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <span class="text-2xl">⚠️</span>
                        Deskripsi Permasalahan
                    </h3>
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
                        <p class="text-gray-900 whitespace-pre-line">{{ $permasalahan->permasalahan }}</p>
                    </div>
                </div>

                <!-- Quick Action - Terima Laporan -->
                @if($permasalahan->status === 'pending')
                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                        <p class="text-blue-800 font-medium mb-3">Laporan ini belum ditangani</p>
                        <form action="{{ route('bpdas.permasalahan.terima', $permasalahan) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300">
                                ✓ Tandai Diproses
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Form Solusi -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="text-2xl">💡</span>
                    Solusi & Tindak Lanjut
                </h3>

                @if($permasalahan->solusi)
                    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 mb-6">
                        <p class="text-sm text-gray-600 mb-2">Solusi saat ini:</p>
                        <p class="text-gray-900 whitespace-pre-line">{{ $permasalahan->solusi }}</p>
                    </div>
                @endif

                <!-- Form Input/Update Solusi -->
                <form action="{{ route('bpdas.permasalahan.solusi', $permasalahan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="solusi" class="block text-sm font-medium text-gray-700 mb-2">
                            Solusi <span class="text-red-500">*</span>
                        </label>
                        <textarea name="solusi" id="solusi" rows="6"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('solusi') border-red-500 @enderror"
                                  placeholder="Tulis solusi untuk permasalahan ini..." required>{{ old('solusi', $permasalahan->solusi) }}</textarea>
                        @error('solusi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Update Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('status') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Status</option>
                            <option value="pending" {{ old('status', $permasalahan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="diproses" {{ old('status', $permasalahan->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ old('status', $permasalahan->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-lg hover:shadow-xl">
                            💾 Simpan Solusi
                        </button>
                        <a href="{{ route('bpdas.permasalahan.index') }}" 
                           class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-medium transition-all duration-300 text-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
