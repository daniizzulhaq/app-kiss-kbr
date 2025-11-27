<?php

namespace App\Http\Controllers\Kelompok;

use App\Http\Controllers\Controller;
use App\Models\Permasalahan;
use App\Models\RencanaBibit;
use App\Models\RealBibit;
use App\Models\ProgressFisik;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $kelompokId = $user->kelompok_id ?? $user->id;
        
        $totalPermasalahan = Permasalahan::where('kelompok_id', $kelompokId)->count();
        $totalRencanaBibit = RencanaBibit::where('id_kelompok', $kelompokId)->count();
        
        try {
            $totalRealisasiBibit = RealBibit::where('id_kelompok', $kelompokId)->count();
        } catch (\Exception $e) {
            try {
                $totalRealisasiBibit = RealBibit::where('kelompok_id', $kelompokId)->count();
            } catch (\Exception $e2) {
                $totalRealisasiBibit = 0;
            }
        }
        
        $totalProgressFisik = ProgressFisik::where('kelompok_id', $kelompokId)->count();
        
        return view('dashboard.kelompok', compact(  // ← 'dashboard.kelompok'
            'totalPermasalahan',
            'totalRencanaBibit',
            'totalRealisasiBibit',
            'totalProgressFisik'
        ));
    }
}