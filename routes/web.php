<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BpdasDashboardController;
use App\Http\Controllers\KelompokDashboardController;
use App\Http\Controllers\PermasalahanKelompokController;
use App\Http\Controllers\PermasalahanBpdasController;
use App\Http\Controllers\CalonLokasiKelompokController;
use App\Http\Controllers\GeotaggingBpdasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Redirect berdasarkan role setelah login
    Route::get('/dashboard', function () {
        if (auth()->user()->isBpdas()) {
            return redirect()->route('bpdas.dashboard');
        }
        return redirect()->route('kelompok.dashboard');
    })->name('dashboard');

    // Dashboard BPDAS
    Route::middleware(['role:bpdas'])->prefix('bpdas')->name('bpdas.')->group(function () {
        Route::get('/dashboard', [BpdasDashboardController::class, 'index'])->name('dashboard');
        
        // Routes Permasalahan BPDAS
        Route::get('/permasalahan', [PermasalahanBpdasController::class, 'index'])->name('permasalahan.index');
        Route::get('/permasalahan/{permasalahan}', [PermasalahanBpdasController::class, 'show'])->name('permasalahan.show');
        Route::post('/permasalahan/{permasalahan}/terima', [PermasalahanBpdasController::class, 'terima'])->name('permasalahan.terima');
        Route::put('/permasalahan/{permasalahan}/solusi', [PermasalahanBpdasController::class, 'updateSolusi'])->name('permasalahan.solusi');
        
        // Routes Geotagging BPDAS
        Route::get('/geotagging', [GeotaggingBpdasController::class, 'index'])->name('geotagging.index');
        Route::get('/geotagging/{calonLokasi}', [GeotaggingBpdasController::class, 'show'])->name('geotagging.show');
        Route::put('/geotagging/{calonLokasi}/verifikasi', [GeotaggingBpdasController::class, 'verifikasi'])->name('geotagging.verifikasi');
    });

    // Dashboard Kelompok
    Route::middleware(['role:kelompok'])->prefix('kelompok')->name('kelompok.')->group(function () {
        Route::get('/dashboard', [KelompokDashboardController::class, 'index'])->name('dashboard');
        
        // Routes Permasalahan Kelompok
        Route::resource('permasalahan', PermasalahanKelompokController::class);
        
        // Route tambahan untuk tanggapan
        Route::get('/permasalahan/{permasalahan}/tanggapan', [PermasalahanKelompokController::class, 'tanggapan'])
            ->name('permasalahan.tanggapan');
        
        Route::post('/permasalahan/{permasalahan}/tanggapan', [PermasalahanKelompokController::class, 'storeTanggapan'])
            ->name('permasalahan.tanggapan.store');
        
        // Routes Calon Lokasi Kelompok
        Route::resource('calon-lokasi', CalonLokasiKelompokController::class);
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';