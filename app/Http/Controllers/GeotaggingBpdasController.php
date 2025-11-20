<?php

namespace App\Http\Controllers;

use App\Models\CalonLokasi;
use App\Exports\GeotaggingExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class GeotaggingBpdasController extends Controller
{
    public function index(Request $request)
    {
        $query = CalonLokasi::with('user');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        // Filter kabupaten
        if ($request->filled('kabupaten')) {
            $query->where('kabupaten', 'like', '%' . $request->kabupaten . '%');
        }

        // Filter kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', 'like', '%' . $request->kecamatan . '%');
        }

        $calonLokasis = $query->latest()->paginate(15);

        // Data untuk peta (semua lokasi dengan koordinat valid)
        $lokasiMap = CalonLokasi::whereNotNull('center_latitude')
            ->whereNotNull('center_longitude')
            ->get(['id', 'nama_kelompok_desa', 'center_latitude as latitude', 'center_longitude as longitude', 'status_verifikasi']);

        return view('bpdas.geotagging.index', compact('calonLokasis', 'lokasiMap'));
    }

    public function show(CalonLokasi $calonLokasi)
    {
        $calonLokasi->load('user');

        // Pastikan polygon_coordinates selalu array agar show Blade aman
        $calonLokasi->polygon_coordinates = is_array($calonLokasi->polygon_coordinates)
            ? $calonLokasi->polygon_coordinates
            : json_decode($calonLokasi->polygon_coordinates, true) ?? [];

        return view('bpdas.geotagging.show', compact('calonLokasi'));
    }

    public function verifikasi(Request $request, CalonLokasi $calonLokasi)
    {
        $validated = $request->validate([
            'status_verifikasi' => 'required|in:diverifikasi,ditolak',
            'catatan_bpdas' => 'nullable|string',
        ]);

        $calonLokasi->update($validated);

        $status = $validated['status_verifikasi'] === 'diverifikasi' ? 'diverifikasi' : 'ditolak';

        return redirect()->route('bpdas.geotagging.index')
            ->with('success', "Lokasi berhasil {$status}!");
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        $filters = [
            'status' => $request->status,
            'kabupaten' => $request->kabupaten,
            'kecamatan' => $request->kecamatan,
        ];

        $filename = 'geotagging_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new GeotaggingExport($filters), $filename);
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $query = CalonLokasi::with('user');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        if ($request->filled('kabupaten')) {
            $query->where('kabupaten', 'like', '%' . $request->kabupaten . '%');
        }

        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', 'like', '%' . $request->kecamatan . '%');
        }

        $data = $query->latest()->get();

        $pdf = Pdf::loadView('bpdas.geotagging.pdf', compact('data'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('geotagging_' . date('Y-m-d_His') . '.pdf');
    }
}