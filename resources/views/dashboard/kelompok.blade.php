@extends('layouts.dashboard')

@section('title', 'Dashboard Kelompok')

@section('content')
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-green-800 mb-2">Dashboard Kelompok</h1>
                <p class="text-gray-600">Kelola data dan laporan kelompok tani Anda</p>
            </div>
            <div class="text-6xl float-animation">🌾</div>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 shadow-2xl rounded-2xl p-8 mb-8 text-white relative overflow-hidden slide-in">
        <div class="absolute top-0 right-0 text-9xl opacity-10">👥</div>
        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-3">Selamat Datang! 👋</h2>
            <p class="text-xl mb-2">
                Halo, <span class="font-bold text-green-100">{{ Auth::user()->name }}</span>
            </p>
            <p class="text-green-100">
                Anda login sebagai <span class="font-semibold bg-green-500 px-3 py-1 rounded-full">Pengelola Kelompok</span>
            </p>
        </div>
    </div>

    <!-- Menu Fitur Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Dashboard -->
        <a href="{{ route('kelompok.dashboard') }}" 
            class="group bg-green-50 hover:bg-green-100 p-6 rounded-xl border border-green-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">🏠</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Dashboard</h4>
                    <p class="text-sm text-gray-600">Ringkasan data kelompok Anda</p>
                </div>
            </div>
        </a>

        <!-- Permasalahan -->
        <a href="{{ route('kelompok.permasalahan.index') }}" 
            class="group bg-yellow-50 hover:bg-yellow-100 p-6 rounded-xl transition-all border border-yellow-200 hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">⚠️</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Permasalahan</h4>
                    <p class="text-sm text-gray-600">Laporkan permasalahan di lapangan</p>
                </div>
            </div>
        </a>

        <!-- Calon Lokasi Persemaian -->
        <a href="{{ route('kelompok.calon-lokasi.index') }}" 
            class="group bg-pink-50 hover:bg-pink-100 p-6 rounded-xl border border-pink-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">📍</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Calon Lokasi Persemaian</h4>
                    <p class="text-sm text-gray-600">Tentukan lokasi untuk persemaian</p>
                </div>
            </div>
        </a>

        <!-- Data Kelompok -->
        <a href="{{ route('kelompok.data-kelompok.index') }}" 
            class="group bg-orange-50 hover:bg-orange-100 p-6 rounded-xl border border-orange-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">👨‍🌾</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Data Kelompok</h4>
                    <p class="text-sm text-gray-600">Data profil kelompok tani</p>
                </div>
            </div>
        </a>

        <!-- Rencana Bibit -->
        <a href="{{ route('kelompok.rencana-bibit.index') }}" 
            class="group bg-lime-50 hover:bg-lime-100 p-6 rounded-xl border border-lime-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">🌱</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Rencana Bibit</h4>
                    <p class="text-sm text-gray-600">Input rencana kebutuhan bibit</p>
                </div>
            </div>
        </a>

        <!-- Realisasi Bibit -->
        <a href="{{ route('kelompok.realisasi-bibit.index') }}" 
            class="group bg-emerald-50 hover:bg-emerald-100 p-6 rounded-xl border border-emerald-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">🌳</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Realisasi Bibit</h4>
                    <p class="text-sm text-gray-600">Catat realisasi distribusi bibit</p>
                </div>
            </div>
        </a>

        <!-- Progress Fisik -->
        <a href="{{ route('kelompok.progress-fisik.index') }}" 
            class="group bg-blue-50 hover:bg-blue-100 p-6 rounded-xl border border-blue-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">📊</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Progress Fisik</h4>
                    <p class="text-sm text-gray-600">Laporan progress kegiatan fisik</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
        <!-- Total Permasalahan -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Permasalahan</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalPermasalahan ?? 0 }}</h3>
                </div>
                <div class="text-4xl">⚠️</div>
            </div>
        </div>

        <!-- Total Rencana Bibit -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-lime-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Rencana Bibit</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalRencanaBibit ?? 0 }}</h3>
                </div>
                <div class="text-4xl">🌱</div>
            </div>
        </div>

        <!-- Total Realisasi Bibit -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Realisasi Bibit</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalRealisasiBibit ?? 0 }}</h3>
                </div>
                <div class="text-4xl">🌳</div>
            </div>
        </div>

        <!-- Progress Fisik -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Progress Fisik</p>
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalProgressFisik ?? 0 }}</h3>
                </div>
                <div class="text-4xl">📊</div>
            </div>
        </div>
    </div>
@endsection