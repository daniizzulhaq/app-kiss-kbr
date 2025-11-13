@extends('layouts.dashboard')

@section('title', 'Dashboard BPDAS')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-green-800 mb-2">Dashboard BPDAS</h1>
                <p class="text-gray-600">Sistem Kolaborasi Informasi dan Supervisi KBR</p>
            </div>
            <div class="text-6xl float-animation">🌾</div>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 shadow-2xl rounded-2xl p-8 mb-8 text-white relative overflow-hidden slide-in">
        <div class="absolute top-0 right-0 text-9xl opacity-10">🌿</div>
        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-3">Selamat Datang! 👋</h2>
            <p class="text-xl mb-2">
                Halo, <span class="font-bold text-green-100">{{ Auth::user()->name }}</span>
            </p>
            <p class="text-green-100">
                Anda login sebagai <span class="font-semibold bg-green-500 px-3 py-1 rounded-full">BPDAS</span>
            </p>
        </div>
    </div>

    <!-- Menu Fitur Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Terima Permasalahan -->
        <a href="{{ route('bpdas.permasalahan.index') }}" 
            class="group bg-red-50 hover:bg-red-100 p-6 rounded-xl border border-red-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">⚠️</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Terima Permasalahan</h4>
                    <p class="text-sm text-gray-600">Lihat dan tindaklanjuti laporan dari kelompok tani</p>
                </div>
            </div>
        </a>

        <!-- Realisasi Tenaga -->
        <a href="#" 
            class="group bg-green-50 hover:bg-green-100 p-6 rounded-xl border border-green-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">👷‍♂️</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Realisasi Tenaga</h4>
                    <p class="text-sm text-gray-600">Monitor pelaksanaan tenaga kerja di lapangan</p>
                </div>
            </div>
        </a>

        <!-- Geotagging -->
        <a href="#" 
            class="group bg-blue-50 hover:bg-blue-100 p-6 rounded-xl border border-blue-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">📍</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Geotagging</h4>
                    <p class="text-sm text-gray-600">Lihat peta lokasi dan koordinat kegiatan</p>
                </div>
            </div>
        </a>

        <!-- Rencana Tenaga -->
        <a href="#" 
            class="group bg-purple-50 hover:bg-purple-100 p-6 rounded-xl border border-purple-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">📋</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Rencana Tenaga</h4>
                    <p class="text-sm text-gray-600">Atur perencanaan kebutuhan tenaga kerja</p>
                </div>
            </div>
        </a>

        <!-- Rencana Pembuatan -->
        <a href="#" 
            class="group bg-orange-50 hover:bg-orange-100 p-6 rounded-xl border border-orange-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">🧱</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Rencana Pembuatan</h4>
                    <p class="text-sm text-gray-600">Susun rencana pembangunan sarana & prasarana</p>
                </div>
            </div>
        </a>

        <!-- Rencana Pembelian Bibit -->
        <a href="#" 
            class="group bg-emerald-50 hover:bg-emerald-100 p-6 rounded-xl border border-emerald-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">🌳</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Rencana Pembelian Bibit</h4>
                    <p class="text-sm text-gray-600">Rencana pengadaan bibit tanaman KBR</p>
                </div>
            </div>
        </a>
    </div>
@endsection
