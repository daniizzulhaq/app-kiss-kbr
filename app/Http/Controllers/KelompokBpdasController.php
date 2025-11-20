<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use App\Exports\KelompokExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class KelompokBpdasController extends Controller
{
    public function index()
    {
        $kelompoks = Kelompok::with('user')->latest()->paginate(15);
        return view('bpdas.kelompok.index', compact('kelompoks'));
    }

    public function show(Kelompok $kelompok)
    {
        $kelompok->load('user');
        return view('bpdas.kelompok.show', compact('kelompok'));
    }

    public function exportPdf()
    {
        $kelompoks = Kelompok::with('user')->get();
        
        $pdf = Pdf::loadView('bpdas.kelompok.pdf', compact('kelompoks'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('data-kelompok-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(
            new KelompokExport, 
            'data-kelompok-' . date('Y-m-d') . '.xlsx'
        );
    }
}