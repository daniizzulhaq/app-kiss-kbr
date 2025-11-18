@extends('layouts.dashboard')

@section('title', 'Rencana Bibit - Sistem KBR')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 slide-in">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-2">🌱 Rencana Bibit</h1>
                <p class="text-gray-600">Kelola rencana kebutuhan bibit untuk kegiatan kelompok tani</p>
            </div>
            <a href="{{ route('kelompok.rencana-bibit.create') }}" 
               class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl font-semibold">
                ➕ Tambah Bibit
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg slide-in">
        <div class="flex items-center">
            <span class="text-2xl mr-3">✅</span>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Jenis Bibit</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $rencanaBibits->total() }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center text-2xl">
                    🌱
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Batang</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ number_format($rencanaBibits->sum('jumlah_btg'), 0, ',', '.') }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-2xl">
                    🌳
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Bersertifikat</p>
                    <h3 class="text-3xl font-bold text-blue-600">{{ $rencanaBibits->where('sertifikat', '!=', null)->count() }}</h3>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-amber-200 rounded-xl flex items-center justify-center text-2xl">
                    📜
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden slide-in">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Jenis Bibit</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Golongan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Jumlah (Btg)</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tinggi (cm)</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Sertifikat</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($rencanaBibits as $index => $bibit)
                    <tr class="hover:bg-green-50 transition-colors duration-200">
                        <td class="px-6 py-4 text-gray-700">{{ $rencanaBibits->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $bibit->jenis_bibit }}</div>
                        </td>
                        <td class="px-6 py-4">
                            {!! $bibit->golongan_badge !!}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-green-700">{{ $bibit->jumlah_format }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $bibit->tinggi_format }}</td>
                        <td class="px-6 py-4">
                            {!! $bibit->sertifikat_badge !!}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('kelompok.rencana-bibit.show', $bibit) }}" 
                                   class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                                    👁️ Lihat
                                </a>
                                <a href="{{ route('kelompok.rencana-bibit.edit', $bibit) }}" 
                                   class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors text-sm font-medium">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('kelompok.rencana-bibit.destroy', $bibit) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus rencana bibit ini?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-6xl mb-4">🌱</span>
                                <p class="text-gray-500 font-medium">Belum ada rencana bibit</p>
                                <a href="{{ route('kelompok.rencana-bibit.create') }}" 
                                   class="mt-4 text-green-600 hover:text-green-700 font-semibold">
                                    + Tambahkan rencana bibit pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($rencanaBibits->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $rencanaBibits->links() }}
        </div>
        @endif
    </div>
</div>
@endsection