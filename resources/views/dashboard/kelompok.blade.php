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

    <div class="bg-gradient-to-r from-green-600 to-emerald-600 shadow-2xl rounded-2xl p-8 mb-8 text-white relative overflow-hidden slide-in">
        <div class="absolute top-0 right-0 text-9xl opacity-10">👥</div>
        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-3">Selamat Datang! 👋</h2>
            <p class="text-xl mb-2">
                Halo, <span class="font-bold text-green-100">{{ Auth::user()->name }}</span>
            </p>
            <p class="text-green-100">
                Anda login sebagai <span class="font-semibold bg-green-500 px-3 py-1 rounded-full">Kelompok Tani</span>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <a href="{{ route('kelompok.permasalahan.index') }}" class="group bg-red-50 hover:bg-red-100 p-6 rounded-xl transition-all border border-red-200 hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">⚠️</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Permasalahan</h4>
                    <p class="text-sm text-gray-600">Laporkan permasalahan di lapangan</p>
                </div>
            </div>
        </a>

        <a href="#" class="group bg-blue-50 hover:bg-blue-100 p-6 rounded-xl border border-blue-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">📍</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Calon Lokasi</h4>
                    <p class="text-sm text-gray-600">Tentukan lokasi untuk penanaman</p>
                </div>
            </div>
        </a>

        <a href="#" class="group bg-yellow-50 hover:bg-yellow-100 p-6 rounded-xl border border-yellow-200 transition-all hover:shadow-lg hover:-translate-y-1">
            <div class="flex items-start space-x-4">
                <div class="text-4xl">📝</div>
                <div>
                    <h4 class="font-bold text-lg text-gray-800 mb-2">Laporan</h4>
                    <p class="text-sm text-gray-600">Lihat semua laporan kegiatan</p>
                </div>
            </div>
        </a>
    </div>
@endsection
