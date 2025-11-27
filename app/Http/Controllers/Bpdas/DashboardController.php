<?php

namespace App\Http\Controllers\Bpdas;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Permasalahan;
use App\Models\ProgressFisik;
use App\Models\PetaGeotagging;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKelompok = Kelompok::count();
        $permasalahanPending = Permasalahan::where('status', 'pending')->count();
        
        // Hitung Progress Fisik yang BELUM diverifikasi
        // Coba beberapa kemungkinan:
        try {
            // Kemungkinan 1: Ada kolom 'verified_at' (NULL = belum verifikasi)
            $progressPending = ProgressFisik::whereNull('verified_at')->count();
        } catch (\Exception $e) {
            try {
                // Kemungkinan 2: Ada kolom 'status'
                $progressPending = ProgressFisik::where('status', 'pending')->count();
            } catch (\Exception $e2) {
                try {
                    // Kemungkinan 3: Ada kolom 'is_verified'
                    $progressPending = ProgressFisik::where('is_verified', false)->count();
                } catch (\Exception $e3) {
                    // Fallback: set 0 jika tidak ada kolom verifikasi
                    $progressPending = 0;
                }
            }
        }
        
        $totalGeotagging = PetaGeotagging::count();
        
        return view('dashboard.bpdas', compact(
            'totalKelompok',
            'permasalahanPending',
            'progressPending',
            'totalGeotagging'
        ));
    }
}