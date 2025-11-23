@extends('layouts.dashboard')

@section('title', 'Progress Fisik - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Progress Fisik Kegiatan</h1>
                <p class="text-gray-600 mt-1">Kelola progress fisik kegiatan <strong class="text-green-600">{{ auth()->user()->kelompok->nama_kelompok }}</strong></p>
            </div>
            <a href="{{ route('kelompok.progress-fisik.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg flex items-center gap-2 transition-all shadow-lg hover:shadow-xl">
                <span class="text-xl">➕</span>
                <span class="font-medium">Tambah Kegiatan</span>
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-2xl mr-3">✅</span>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow">
        <div class="flex items-center">
            <span class="text-2xl mr-3">❌</span>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Ringkasan Anggaran Tahun {{ date('Y') }} -->
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-2xl">💰</span>
            <h2 class="text-xl font-bold text-gray-800">Ringkasan Anggaran Tahun {{ date('Y') }}</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Total Anggaran -->
            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-medium uppercase">Total Anggaran</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">
                            Rp {{ number_format($anggaran->total_anggaran, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">100% dari total</p>
                    </div>
                    <span class="text-4xl">💰</span>
                </div>
            </div>
            
            <!-- Anggaran Dialokasikan -->
            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-medium uppercase">Anggaran Dialokasikan</p>
                        <p class="text-2xl font-bold text-orange-600 mt-1">
                            Rp {{ number_format($anggaran->anggaran_dialokasikan, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format($anggaran->persentase_alokasi, 1) }}% dari total
                        </p>
                    </div>
                    <span class="text-4xl">📊</span>
                </div>
            </div>
            
            <!-- Realisasi Anggaran (Disetujui) -->
            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-medium uppercase">Realisasi (Disetujui)</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">
                            Rp {{ number_format($anggaran->realisasi_anggaran, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format($anggaran->persentase_realisasi, 1) }}% dari total
                        </p>
                    </div>
                    <span class="text-4xl">✅</span>
                </div>
            </div>
            
            <!-- Sisa Anggaran -->
            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-medium uppercase">Sisa Anggaran</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">
                            Rp {{ number_format($anggaran->sisa_anggaran, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ number_format($anggaran->persentase_sisa, 1) }}% tersisa
                        </p>
                    </div>
                    <span class="text-4xl">💵</span>
                </div>
            </div>

            <!-- Progress Realisasi -->
            <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-medium uppercase">Progress Realisasi</p>
                        <p class="text-2xl font-bold text-indigo-600 mt-1">
                            {{ number_format($totalProgress, 1) }}%
                        </p>
                        <p class="text-xs text-gray-500 mt-1">dari yang disetujui</p>
                    </div>
                    <span class="text-4xl">📈</span>
                </div>
            </div>
        </div>

        <!-- Progress Bar Alokasi -->
        <div class="mt-4 bg-white p-4 rounded-lg shadow">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-gray-700">Alokasi Anggaran (termasuk pending)</span>
                <span class="text-sm font-bold text-orange-600">{{ number_format($anggaran->persentase_alokasi, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-orange-500 h-4 rounded-full transition-all duration-500" 
                     style="width: {{ $anggaran->persentase_alokasi }}%"></div>
            </div>
        </div>

        <!-- Progress Bar Realisasi -->
        <div class="mt-3 bg-white p-4 rounded-lg shadow">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-gray-700">Realisasi Anggaran (disetujui)</span>
                <span class="text-sm font-bold text-green-600">{{ number_format($anggaran->persentase_realisasi, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-green-500 h-4 rounded-full transition-all duration-500" 
                     style="width: {{ $anggaran->persentase_realisasi }}%"></div>
            </div>
        </div>

        <!-- Progress Bar Fisik -->
        <div class="mt-3 bg-white p-4 rounded-lg shadow">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-gray-700">Total Progress Fisik</span>
                <span class="text-sm font-bold text-indigo-600">{{ number_format($totalProgress, 1) }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-indigo-500 h-4 rounded-full transition-all duration-500" 
                     style="width: {{ $totalProgress }}%"></div>
            </div>
        </div>
    </div>

    <!-- Daftar Progress Fisik per Kategori -->
    @if($progressList->count() > 0)
        @foreach($progressByKategori as $kategoriNama => $progressItems)
        <div class="mb-6">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header Kategori -->
                <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span>🌱</span>
                            {{ $kategoriNama }}
                        </h3>
                        <span class="text-sm text-green-100">
                            {{ $progressItems->count() }} kegiatan
                        </span>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama Kegiatan</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Target</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Realisasi</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Biaya Total</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Biaya Realisasi</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Progress</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($progressItems as $index => $progress)
                            <tr class="hover:bg-green-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $progress->masterKegiatan->nama_kegiatan }}</div>
                                    @if($progress->keterangan)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($progress->keterangan, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
    <div class="text-sm font-bold text-gray-900">
        {{ rtrim(rtrim(number_format($progress->volume_target, 2), '0'), '.') }}
    </div>
    <div class="text-xs text-gray-500">{{ $progress->masterKegiatan->satuan }}</div>
</td>

<td class="px-6 py-4 whitespace-nowrap text-center">
    <div class="text-sm font-bold text-green-600">
        {{ rtrim(rtrim(number_format($progress->volume_realisasi, 2), '0'), '.') }}
    </div>
    <div class="text-xs text-gray-500">{{ $progress->masterKegiatan->satuan }}</div>
</td>

{{-- Untuk biaya tetap tanpa desimal --}}
<td class="px-6 py-4 whitespace-nowrap text-center">
    <div class="text-sm font-bold text-blue-600">
        Rp {{ number_format($progress->total_biaya, 0, ',', '.') }}
    </div>
</td>

<td class="px-6 py-4 whitespace-nowrap text-center">
    <div class="text-sm font-bold text-green-600">
        Rp {{ number_format($progress->biaya_realisasi, 0, ',', '.') }}
    </div>
</td>

{{-- Progress tetap dengan 1 desimal --}}
<td class="px-6 py-4 whitespace-nowrap text-center">
    <div class="flex flex-col items-center">
        <span class="text-lg font-bold text-indigo-600">
            {{ number_format($progress->persentase_fisik, 1) }}%
        </span>
        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
            <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $progress->persentase_fisik }}%"></div>
        </div>
    </div>
</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($progress->status_verifikasi == 'pending')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            ⏳ Pending
                                        </span>
                                    @elseif($progress->status_verifikasi == 'disetujui')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            ✅ Disetujui
                                        </span>
                                    @elseif($progress->status_verifikasi == 'ditolak')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            ❌ Ditolak
                                        </span>
                                    @endif

                                    @if($progress->verified_at)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $progress->verified_at->format('d M Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('kelompok.progress-fisik.show', $progress->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 font-medium transition-colors"
                                           title="Lihat Detail">
                                            👁️
                                        </a>
                                        
                                        @if($progress->status_verifikasi != 'disetujui')
                                            <a href="{{ route('kelompok.progress-fisik.edit', $progress->id) }}" 
                                               class="text-yellow-600 hover:text-yellow-800 font-medium transition-colors"
                                               title="Edit Progress">
                                                ✏️
                                            </a>
                                            
                                            <form action="{{ route('kelompok.progress-fisik.destroy', $progress->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('⚠️ Yakin ingin menghapus kegiatan ini?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-800 font-medium transition-colors"
                                                        title="Hapus Kegiatan">
                                                    🗑️
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Sudah diverifikasi</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-lg p-16 text-center">
            <span class="text-7xl mb-6 block">📊</span>
            <p class="text-gray-500 text-2xl font-medium mb-2">Belum ada kegiatan</p>
            <p class="text-gray-400 text-sm mb-6">Mulai tambahkan kegiatan untuk melacak progress fisik</p>
            <a href="{{ route('kelompok.progress-fisik.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-medium transition-all shadow-lg hover:shadow-xl inline-flex items-center gap-2">
                <span class="text-xl">➕</span>
                Tambah Kegiatan Pertama
            </a>
        </div>
    @endif

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex items-start">
            <span class="text-2xl mr-3">ℹ️</span>
            <div class="text-sm text-blue-800">
                <p class="font-semibold mb-1">Informasi Penting:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Anggaran Dialokasikan:</strong> Total biaya dari kegiatan yang pending dan disetujui</li>
                    <li><strong>Realisasi Anggaran:</strong> Total biaya realisasi dari kegiatan yang sudah disetujui admin</li>
                    <li><strong>Progress Fisik:</strong> Dihitung dari volume_realisasi / volume_target × 100%</li>
                    <li><strong>Status Pending:</strong> Menunggu verifikasi dari admin/verifikator</li>
                    <li><strong>Status Disetujui:</strong> Progress sudah diverifikasi dan realisasi anggaran sudah dihitung</li>
                    <li>Kegiatan yang sudah disetujui <strong>tidak dapat diedit atau dihapus</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection