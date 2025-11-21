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
use App\Http\Controllers\RealisasiBibitController;
use App\Http\Controllers\RealisasiBibitBpdasController;
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
         Route::get('/permasalahan/export/pdf', [PermasalahanBpdasController::class, 'exportPdf'])->name('permasalahan.export.pdf');
         Route::get('/permasalahan/export/excel', [PermasalahanBpdasController::class, 'exportExcel'])->name('permasalahan.export.excel');
        // Geotagging
        Route::get('/geotagging', [GeotaggingBpdasController::class, 'index'])->name('geotagging.index');
        Route::get('/geotagging/{calonLokasi}', [GeotaggingBpdasController::class, 'show'])->name('geotagging.show');
        Route::put('/geotagging/{calonLokasi}/verifikasi', [GeotaggingBpdasController::class, 'verifikasi'])->name('geotagging.verifikasi');
        Route::get('geotagging/export/excel', [GeotaggingBpdasController::class, 'exportExcel'])->name('geotagging.export.excel');
        Route::get('geotagging/export/pdf', [GeotaggingBpdasController::class, 'exportPdf'])->name('geotagging.export.pdf');
    

        // Kelompok Routes
Route::get('/kelompok', [KelompokBpdasController::class, 'index'])->name('kelompok.index'); 
Route::get('/kelompok/export/pdf', [KelompokBpdasController::class, 'exportPdf'])->name('kelompok.export.pdf');
Route::get('/kelompok/export/excel', [KelompokBpdasController::class, 'exportExcel'])->name('kelompok.export.excel');
Route::get('/kelompok/{kelompok}', [KelompokBpdasController::class, 'show'])->name('kelompok.show');


         Route::get('/rencana-bibit', [RencanaBibitBpdasController::class, 'index'])->name('rencana-bibit.index');
    Route::get('/rencana-bibit/statistik', [RencanaBibitBpdasController::class, 'statistik'])->name('rencana-bibit.statistik');
    Route::get('/rencana-bibit/{rencanaBibit}', [RencanaBibitBpdasController::class, 'show'])->name('rencana-bibit.show');
    Route::get('/rencana-bibit/export/excel', [RencanaBibitBpdasController::class, 'exportExcel'])->name('rencana-bibit.export.excel');
    Route::get('/rencana-bibit/export/pdf', [RencanaBibitBpdasController::class, 'exportPdf'])->name('rencana-bibit.export.pdf');
    

    Route::get('/realisasi-bibit', [RealisasiBibitBpdasController::class, 'index'])->name('realisasi-bibit.index');
    Route::get('/realisasi-bibit/statistik', [RealisasiBibitBpdasController::class, 'statistik'])->name('realisasi-bibit.statistik');
    Route::get('/realisasi-bibit/{realisasiBibit}', [RealisasiBibitBpdasController::class, 'show'])->name('realisasi-bibit.show');
    Route::get('/realisasi-bibit/export/excel', [RealisasiBibitBpdasController::class, 'exportExcel'])->name('realisasi-bibit.export.excel');
        Route::get('/realisasi-bibit/export/pdf', [RealisasiBibitBpdasController::class, 'exportPdf'])->name('realisasi-bibit.export.pdf');
        Route::get('/realisasi-bibit/export/pdf-preview', [RealisasiBibitBpdasController::class, 'previewPdf'])->name('realisasi-bibit.export.pdf-preview');

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

       Route::get('/data-kelompok', [KelompokController::class, 'index'])->name('data-kelompok.index');
Route::get('/data-kelompok/create', [KelompokController::class, 'create'])->name('data-kelompok.create');
Route::post('/data-kelompok', [KelompokController::class, 'store'])->name('data-kelompok.store');
Route::get('/data-kelompok/{kelompok}/edit', [KelompokController::class, 'edit'])->name('data-kelompok.edit');
Route::put('/data-kelompok/{kelompok}', [KelompokController::class, 'update'])->name('data-kelompok.update');
Route::delete('/data-kelompok/{kelompok}', [KelompokController::class, 'destroy'])->name('data-kelompok.destroy');

// Route untuk delete foto - TARUH DI SINI, BUKAN DI LUAR GRUP
Route::post('/data-kelompok/{kelompok}/delete-photo', [KelompokController::class, 'deletePhoto'])
    ->name('data-kelompok.delete-photo');





        // Calon Lokasi
        Route::resource('calon-lokasi', CalonLokasiKelompokController::class);

        Route::resource('rencana-bibit', RencanaBibitController::class);

        Route::resource('realisasi-bibit', RealisasiBibitController::class);

        
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
