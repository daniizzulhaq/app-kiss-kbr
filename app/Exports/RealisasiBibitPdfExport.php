<?php

namespace App\Exports;

use App\Models\RealBibit;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RealisasiBibitPdfExport
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Generate PDF
     */
    public function generate()
    {
        $query = RealBibit::with('kelompok');

        // Apply filters
        if ($this->request->filled('kelompok_id')) {
            $query->where('id_kelompok', $this->request->kelompok_id);
        }

        if ($this->request->filled('jenis_bibit')) {
            $query->where('jenis_bibit', 'like', '%' . $this->request->jenis_bibit . '%');
        }

        if ($this->request->filled('golongan')) {
            $query->where('golongan', $this->request->golongan);
        }

        $realBibits = $query->latest()->get();
        
        // Calculate statistics
        $totalBibit = $realBibits->sum('jumlah_btg');
        $totalJenis = $realBibits->unique('jenis_bibit')->count();
        $totalKelompok = $realBibits->unique('id_kelompok')->count();

        $data = [
            'realBibits' => $realBibits,
            'totalBibit' => $totalBibit,
            'totalJenis' => $totalJenis,
            'totalKelompok' => $totalKelompok,
            'tanggalCetak' => now()->format('d/m/Y H:i'),
            'filters' => [
                'kelompok' => $this->request->kelompok_id ? 
                    \App\Models\Kelompok::find($this->request->kelompok_id)->nama_kelompok ?? 'Semua Kelompok' : 
                    'Semua Kelompok',
                'jenis_bibit' => $this->request->jenis_bibit ?? 'Semua Jenis',
                'golongan' => $this->request->golongan ?? 'Semua Golongan'
            ]
        ];

        $pdf = Pdf::loadView('bpdas.realisasi-bibit.pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf;
    }
}