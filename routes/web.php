<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BpdasDashboardController;
use App\Http\Controllers\KelompokDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Arahkan langsung ke halaman login
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
    Route::middleware(['role:bpdas'])->group(function () {
        Route::get('/bpdas/dashboard', [BpdasDashboardController::class, 'index'])
            ->name('bpdas.dashboard');
    });

    // Dashboard Kelompok
    Route::middleware(['role:kelompok'])->group(function () {
        Route::get('/kelompok/dashboard', [KelompokDashboardController::class, 'index'])
            ->name('kelompok.dashboard');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
