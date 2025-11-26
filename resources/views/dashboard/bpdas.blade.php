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
        <!-- Dashboard -->
        <a href="{{ route('bpdas.dashboard') }}" 
            class="group bg-red-50 hover:bg-red-100 p-6 rounded-xl border border-red-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">🏠</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Dashboard</h4>
                    <p class="text-sm text-gray-600">Ringkasan dan statistik sistem</p>
                </div>
            </div>
        </a>

        <!-- Terima Permasalahan -->
        <a href="{{ route('bpdas.permasalahan.index') }}" 
            class="group bg-yellow-50 hover:bg-yellow-100 p-6 rounded-xl border border-yellow-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">⚠️</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Terima Permasalahan</h4>
                    <p class="text-sm text-gray-600">Lihat dan tindaklanjuti laporan dari kelompok tani</p>
                </div>
            </div>
        </a>

        <!-- Data Kelompok -->
        <a href="{{ route('bpdas.kelompok.index') }}" 
            class="group bg-purple-50 hover:bg-purple-100 p-6 rounded-xl border border-purple-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">👥</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Data Kelompok</h4>
                    <p class="text-sm text-gray-600">Kelola data kelompok tani KBR</p>
                </div>
            </div>
        </a>

        <!-- Geotagging -->
        <a href="{{ route('bpdas.geotagging.index') }}" 
            class="group bg-pink-50 hover:bg-pink-100 p-6 rounded-xl border border-pink-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">📍</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Geotagging</h4>
                    <p class="text-sm text-gray-600">Lihat peta lokasi dan koordinat kegiatan</p>
                </div>
            </div>
        </a>

        <!-- Rencana Bibit Kelompok -->
        <a href="{{ route('bpdas.rencana-bibit.index') }}" 
            class="group bg-green-50 hover:bg-green-100 p-6 rounded-xl border border-green-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">🌱</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Rencana Bibit Kelompok</h4>
                    <p class="text-sm text-gray-600">Monitoring rencana pengadaan bibit kelompok</p>
                </div>
            </div>
        </a>

        <!-- Realisasi Bibit Kelompok -->
        <a href="{{ route('bpdas.realisasi-bibit.index') }}" 
            class="group bg-emerald-50 hover:bg-emerald-100 p-6 rounded-xl border border-emerald-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">🌳</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Realisasi Bibit Kelompok</h4>
                    <p class="text-sm text-gray-600">Monitor realisasi distribusi bibit</p>
                </div>
            </div>
        </a>

        <!-- Progress Fisik Kelompok -->
        <a href="{{ route('bpdas.progress-fisik.index') }}" 
            class="group bg-blue-50 hover:bg-blue-100 p-6 rounded-xl border border-blue-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">📊</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Progress Fisik Kelompok</h4>
                    <p class="text-sm text-gray-600">Monitoring progress dan verifikasi kegiatan</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <!-- Total Kelompok -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Kelompok</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalKelompok ?? 0 }}</h3>
                </div>
                <div class="text-4xl">👥</div>
            </div>
        </div>

        <!-- Permasalahan Pending -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Permasalahan Pending</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $permasalahanPending ?? 0 }}</h3>
                </div>
                <div class="text-4xl">⚠️</div>
            </div>
        </div>

        <!-- Progress Menunggu Verifikasi -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Menunggu Verifikasi</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $progressPending ?? 0 }}</h3>
                </div>
                <div class="text-4xl">⏳</div>
            </div>
        </div>

        <!-- Total Geotagging -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-pink-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Geotagging</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalGeotagging ?? 0 }}</h3>
                </div>
                <div class="text-4xl">📍</div>
            </div>
        </div>
    </div>
@endsection