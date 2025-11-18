<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BpdasDashboardController;
use App\Http\Controllers\KelompokDashboardController;
use App\Http\Controllers\PermasalahanKelompokController;
use App\Http\Controllers\PermasalahanBpdasController;
use App\Http\Controllers\CalonLokasiKelompokController;
use App\Http\Controllers\GeotaggingBpdasController;
use App\Http\Controllers\KelompokController;// ← WAJIB ADA
use App\Http\Controllers\KelompokBpdasController;//
use App\Http\Controllers\RencanaBibitController;//
use App\Http\Controllers\RencanaBibitBpdasController;//
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

    /*
    |--------------------------------------------------------------------------
    | ROUTE BPDAS
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:bpdas'])->prefix('bpdas')->name('bpdas.')->group(function () {
        Route::get('/dashboard', [BpdasDashboardController::class, 'index'])->name('dashboard');

        // Permasalahan BPDAS
        Route::get('/permasalahan', [PermasalahanBpdasController::class, 'index'])->name('permasalahan.index');
        Route::get('/permasalahan/{permasalahan}', [PermasalahanBpdasController::class, 'show'])->name('permasalahan.show');
        Route::post('/permasalahan/{permasalahan}/terima', [PermasalahanBpdasController::class, 'terima'])->name('permasalahan.terima');
        Route::put('/permasalahan/{permasalahan}/solusi', [PermasalahanBpdasController::class, 'updateSolusi'])->name('permasalahan.solusi');

        // Geotagging
        Route::get('/geotagging', [GeotaggingBpdasController::class, 'index'])->name('geotagging.index');
        Route::get('/geotagging/{calonLokasi}', [GeotaggingBpdasController::class, 'show'])->name('geotagging.show');
        Route::put('/geotagging/{calonLokasi}/verifikasi', [GeotaggingBpdasController::class, 'verifikasi'])->name('geotagging.verifikasi');

        Route::get('/kelompok', [KelompokBpdasController::class, 'index'])->name('kelompok.index'); 
        Route::get('/kelompok/{kelompok}', [KelompokBpdasController::class, 'show'])->name('kelompok.show');

         Route::get('/rencana-bibit', [RencanaBibitBpdasController::class, 'index'])->name('rencana-bibit.index');
    Route::get('/rencana-bibit/statistik', [RencanaBibitBpdasController::class, 'statistik'])->name('rencana-bibit.statistik');
    Route::get('/rencana-bibit/{rencanaBibit}', [RencanaBibitBpdasController::class, 'show'])->name('rencana-bibit.show');

    });

    /*
    |--------------------------------------------------------------------------
    | ROUTE KELOMPOK
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:kelompok'])->prefix('kelompok')->name('kelompok.')->group(function () {
        Route::get('/dashboard', [KelompokDashboardController::class, 'index'])->name('dashboard');

        // Permasalahan Kelompok
        Route::resource('permasalahan', PermasalahanKelompokController::class);

        // Tanggapan tambahan
        Route::get('/permasalahan/{permasalahan}/tanggapan', [PermasalahanKelompokController::class, 'tanggapan'])
            ->name('permasalahan.tanggapan');
        Route::post('/permasalahan/{permasalahan}/tanggapan', [PermasalahanKelompokController::class, 'storeTanggapan'])
            ->name('permasalahan.tanggapan.store');

        // CRUD Data Kelompok
        Route::get('/data-kelompok', [KelompokController::class, 'index'])->name('data-kelompok.index');
        Route::get('/data-kelompok/create', [KelompokController::class, 'create'])->name('data-kelompok.create');
        Route::post('/data-kelompok', [KelompokController::class, 'store'])->name('data-kelompok.store');
        Route::get('/data-kelompok/{kelompok}/edit', [KelompokController::class, 'edit'])->name('data-kelompok.edit');
        Route::put('/data-kelompok/{kelompok}', [KelompokController::class, 'update'])->name('data-kelompok.update');
        Route::delete('/data-kelompok/{kelompok}', [KelompokController::class, 'destroy'])->name('data-kelompok.destroy');

        // Calon Lokasi
        Route::resource('calon-lokasi', CalonLokasiKelompokController::class);

        Route::resource('rencana-bibit', RencanaBibitController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | PROFILE ROUTES
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication routes
require __DIR__.'/auth.php';
