@extends('layouts.dashboard')

@section('title', 'Buat Laporan Permasalahan')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('kelompok.permasalahan.index') }}" 
               class="text-green-600 hover:text-green-700 font-medium flex items-center gap-2 mb-4">
                <span class="text-xl">←</span>
                <span>Kembali ke Daftar Laporan</span>
            </a>
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Buat Laporan Permasalahan</h2>
            <p class="text-gray-600">Isi formulir di bawah untuk melaporkan permasalahan kelompok Anda.</p>
        </div>

        <!-- Form -->
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <form action="{{ route('kelompok.permasalahan.store') }}" method="POST">
                @csrf

                <!-- Kelompok -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Nama Kelompok</label>
                    <input type="text" name="kelompok" value="{{ old('kelompok') }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                    @error('kelompok')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sarana dan Prasarana -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Sarana dan Prasarana</label>
                    <input type="text" name="sarpras" value="{{ old('sarpras') }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                    @error('sarpras')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bibit -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Bibit</label>
                    <input type="text" name="bibit" value="{{ old('bibit') }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                    @error('bibit')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lokasi Tanam -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Lokasi Tanam</label>
                    <input type="text" name="lokasi_tanam" value="{{ old('lokasi_tanam') }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                    @error('lokasi_tanam')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Permasalahan -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Deskripsi Permasalahan</label>
                    <textarea name="permasalahan" rows="4"
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">{{ old('permasalahan') }}</textarea>
                    @error('permasalahan')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prioritas -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Prioritas</label>
                    <select name="prioritas"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500">
                        <option value="">-- Pilih Prioritas --</option>
                        <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                        <option value="sedang" {{ old('prioritas') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                    </select>
                    @error('prioritas')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Simpan -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg">
                        Simpan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
