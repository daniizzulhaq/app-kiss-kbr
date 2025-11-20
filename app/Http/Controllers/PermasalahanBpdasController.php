<?php

namespace App\Http\Controllers;

use App\Models\Permasalahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PermasalahanExport;

class PermasalahanBpdasController extends Controller
{
    public function index()
    {
        $permasalahan = Permasalahan::with('kelompokUser')
            ->latest()
            ->paginate(10);

        return view('bpdas.permasalahan.index', compact('permasalahan'));
    }

    public function show(Permasalahan $permasalahan)
    {
        $permasalahan->load('kelompokUser', 'penangananBpdas');
        return view('bpdas.permasalahan.show', compact('permasalahan'));
    }

    public function terima(Permasalahan $permasalahan)
    {
        if ($permasalahan->status !== 'pending') {
            return redirect()->back()->with('error', 'Permasalahan sudah ditangani!');
        }

        $permasalahan->update([
            'status' => 'diproses',
            'ditangani_oleh' => Auth::id(),
            'ditangani_pada' => now(),
        ]);

        return redirect()->route('bpdas.permasalahan.show', $permasalahan)
            ->with('success', 'Permasalahan berhasil diterima!');
    }

    public function updateSolusi(Request $request, Permasalahan $permasalahan)
    {
        $validated = $request->validate([
            'solusi' => 'required|string',
            'status' => 'required|in:pending,diproses,selesai',
        ]);

        $permasalahan->update([
            'solusi' => $validated['solusi'],
            'status' => $validated['status'],
            'ditangani_oleh' => Auth::id(),
            'ditangani_pada' => now(),
        ]);

        return redirect()->route('bpdas.permasalahan.show', $permasalahan)
            ->with('success', 'Solusi berhasil disimpan!');
    }

    /**
     * Export ke PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Permasalahan::with('kelompokUser');

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan prioritas jika ada
        if ($request->has('prioritas') && $request->prioritas != '') {
            $query->where('prioritas', $request->prioritas);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $permasalahan = $query->latest()->get();

        $pdf = Pdf::loadView('bpdas.permasalahan.pdf', [
            'permasalahan' => $permasalahan,
            'tanggal_export' => now()->format('d M Y H:i')
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('laporan-permasalahan-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export ke Excel
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new PermasalahanExport($request->all()), 
            'laporan-permasalahan-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}