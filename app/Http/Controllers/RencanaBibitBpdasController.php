<?php

namespace App\Http\Controllers;

use App\Models\RencanaBibit;
use App\Models\Kelompok;
use App\Exports\RencanaBibitExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class RencanaBibitBpdasController extends Controller
{
    /**
     * Display a listing of all rencana bibit from all kelompok
     */
    public function index(Request $request)
    {
        $query = RencanaBibit::with('kelompok');

        // Filter by kelompok (menggunakan id dari tabel kelompoks)
        if ($request->filled('kelompok')) {
            $query->where('id_kelompok', $request->kelompok);
        }

        // Filter by golongan
        if ($request->filled('golongan')) {
            $query->where('golongan', $request->golongan);
        }

        // Search by jenis bibit
        if ($request->filled('search')) {
            $query->where('jenis_bibit', 'like', '%' . $request->search . '%');
        }

        $rencanaBibits = $query->latest()->paginate(10);
        $kelompoks = Kelompok::orderBy('nama_kelompok')->get();

        // Statistik
        $totalJenisBibit = RencanaBibit::count();
        $totalBatang = RencanaBibit::sum('jumlah_btg');
        $totalBersertifikat = RencanaBibit::whereNotNull('sertifikat')->count();
        $totalKelompok = RencanaBibit::distinct('id_kelompok')->count();

        return view('bpdas.rencana-bibit.index', compact(
            'rencanaBibits',
            'kelompoks',
            'totalJenisBibit',
            'totalBatang',
            'totalBersertifikat',
            'totalKelompok'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(RencanaBibit $rencanaBibit)
    {
        $rencanaBibit->load('kelompok');
        return view('bpdas.rencana-bibit.show', compact('rencanaBibit'));
    }

    /**
     * Show statistics and summary
     */
    public function statistik()
    {
        // Statistik per golongan
        $statPerGolongan = RencanaBibit::selectRaw('golongan, COUNT(*) as total_jenis, SUM(jumlah_btg) as total_batang')
            ->groupBy('golongan')
            ->get();

        // Statistik per kelompok
        $statPerKelompok = RencanaBibit::with('kelompok')
            ->selectRaw('id_kelompok, COUNT(*) as total_jenis, SUM(jumlah_btg) as total_batang')
            ->groupBy('id_kelompok')
            ->orderByDesc('total_batang')
            ->get();

        // Top 10 jenis bibit terbanyak
        $topBibit = RencanaBibit::selectRaw('jenis_bibit, golongan, SUM(jumlah_btg) as total_batang')
            ->groupBy('jenis_bibit', 'golongan')
            ->orderByDesc('total_batang')
            ->limit(10)
            ->get();

        return view('bpdas.rencana-bibit.statistik', compact(
            'statPerGolongan',
            'statPerKelompok',
            'topBibit'
        ));
    }

    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $filename = 'rencana-bibit-' . date('Y-m-d-His') . '.xlsx';
        
        return Excel::download(new RencanaBibitExport($request), $filename);
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = RencanaBibit::with('kelompok');

        // Apply same filters as index
        if ($request->filled('kelompok')) {
            $query->where('id_kelompok', $request->kelompok);
        }

        if ($request->filled('golongan')) {
            $query->where('golongan', $request->golongan);
        }

        if ($request->filled('search')) {
            $query->where('jenis_bibit', 'like', '%' . $request->search . '%');
        }

        $rencanaBibits = $query->latest()->get();
        $kelompoks = Kelompok::orderBy('nama_kelompok')->get();

        $pdf = Pdf::loadView('bpdas.rencana-bibit.pdf', compact('rencanaBibits', 'kelompoks'))
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10);

        $filename = 'rencana-bibit-' . date('Y-m-d-His') . '.pdf';
        
        return $pdf->download($filename);
    }
}