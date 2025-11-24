@extends('layouts.dashboard')

@section('title', 'Kelompok Belum Terdaftar - Sistem KBR')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-lg p-16 text-center">
            <!-- Icon -->
            <div class="mb-6">
                <span class="text-8xl">🏘️</span>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4">
                Anda Belum Terdaftar dalam Kelompok
            </h1>

            <!-- Description -->
            <p class="text-gray-600 text-lg mb-8">
                Untuk dapat menggunakan fitur Progress Fisik, Anda harus terdaftar dalam kelompok terlebih dahulu.
            </p>

            <!-- Info Box -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8 text-left">
                <div class="flex items-start">
                    <span class="text-2xl mr-3">⚠️</span>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-2">Langkah yang harus dilakukan:</p>
                        <ol class="list-decimal list-inside space-y-2">
                            <li><strong>Hubungi Administrator</strong> untuk mendaftarkan Anda ke dalam kelompok</li>
                            <li>Atau <strong>lengkapi data kelompok</strong> Anda melalui menu Profil</li>
                            <li>Setelah terdaftar dalam kelompok, Anda dapat mengakses fitur Progress Fisik</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <span class="text-xl">📞</span>
                    <h3 class="font-bold text-blue-900">Butuh Bantuan?</h3>
                </div>
                <p class="text-sm text-blue-800">
                    Hubungi admin sistem atau ketua kelompok untuk informasi lebih lanjut tentang pendaftaran kelompok.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('kelompok.dashboard') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-lg font-medium transition-all shadow-lg hover:shadow-xl inline-flex items-center justify-center gap-2">
                    <span class="text-xl">🏠</span>
                    Kembali ke Dashboard
                </a>
                
                @if(Route::has('kelompok.profile.index'))
                <a href="{{ route('kelompok.profile.index') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-medium transition-all shadow-lg hover:shadow-xl inline-flex items-center justify-center gap-2">
                    <span class="text-xl">👤</span>
                    Lengkapi Profil
                </a>
                @endif
            </div>

            <!-- Additional Info -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    <strong>Catatan:</strong> Fitur ini hanya dapat diakses oleh user yang sudah terdaftar dalam kelompok.
                    Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator sistem.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection