<?php

namespace App\Http\Controllers;

use App\Models\RealBibit;
use App\Models\Kelompok;
use App\Exports\RealisasiBibitExport;
use App\Exports\RealisasiBibitPdfExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RealisasiBibitBpdasController extends Controller
{
    /**
     * Display a listing of all realisasi bibit from all kelompok
     */
    public function index(Request $request)
    {
        $query = RealBibit::with(['kelompok', 'kelompok.user']);

        // Filter berdasarkan kelompok
        if ($request->filled('kelompok_id')) {
            $query->where('id_kelompok', $request->kelompok_id);
        }

        // Filter berdasarkan jenis bibit
        if ($request->filled('jenis_bibit')) {
            $query->where('jenis_bibit', 'like', '%' . $request->jenis_bibit . '%');
        }

        // Filter berdasarkan golongan
        if ($request->filled('golongan')) {
            $query->where('golongan', $request->golongan);
        }

        $realBibits = $query->latest()->paginate(15);
        $kelompoks = Kelompok::orderBy('nama_kelompok')->get();

        return view('bpdas.realisasi-bibit.index', compact('realBibits', 'kelompoks'));
    }

    /**
     * Display the specified resource.
     */
    public function show(RealBibit $realisasiBibit)
    {
        $realisasiBibit->load(['kelompok', 'kelompok.user']);
        return view('bpdas.realisasi-bibit.show', compact('realisasiBibit'));
    }

    /**
     * Statistik realisasi bibit
     */
    public function statistik()
    {
        // Total realisasi per kelompok
        $perKelompok = RealBibit::select('id_kelompok', DB::raw('SUM(jumlah_btg) as total_bibit'))
            ->with('kelompok:id,nama_kelompok')
            ->groupBy('id_kelompok')
            ->orderByDesc('total_bibit')
            ->get();

        // Total per jenis bibit
        $perJenisBibit = RealBibit::select('jenis_bibit', DB::raw('SUM(jumlah_btg) as total_bibit'))
            ->groupBy('jenis_bibit')
            ->orderByDesc('total_bibit')
            ->get();

        // Total per golongan
        $perGolongan = RealBibit::select('golongan', DB::raw('SUM(jumlah_btg) as total_bibit'))
            ->whereNotNull('golongan')
            ->groupBy('golongan')
            ->orderByDesc('total_bibit')
            ->get();

        // Total keseluruhan
        $totalKeseluruhan = RealBibit::sum('jumlah_btg');
        $totalKelompok = RealBibit::distinct('id_kelompok')->count('id_kelompok');

        return view('bpdas.realisasi-bibit.statistik', compact(
            'perKelompok',
            'perJenisBibit',
            'perGolongan',
            'totalKeseluruhan',
            'totalKelompok'
        ));
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $fileName = 'realisasi-bibit-' . date('Y-m-d-His') . '.xlsx';
        return Excel::download(new RealisasiBibitExport($request), $fileName);
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $pdfExport = new RealisasiBibitPdfExport($request);
        $pdf = $pdfExport->generate();
        
        $fileName = 'realisasi-bibit-' . date('Y-m-d-His') . '.pdf';
        return $pdf->download($fileName);
    }

    /**
     * Preview PDF
     */
    public function previewPdf(Request $request)
    {
        $pdfExport = new RealisasiBibitPdfExport($request);
        $pdf = $pdfExport->generate();
        
        return $pdf->stream('realisasi-bibit-preview.pdf');
    }
}