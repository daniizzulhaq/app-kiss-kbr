<?php

namespace App\Http\Controllers;

use App\Models\CalonLokasi;
use Illuminate\Http\Request;

class GeotaggingBpdasController extends Controller
{
    public function index(Request $request)
    {
        $query = CalonLokasi::with('user');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        // Filter berdasarkan kabupaten
        if ($request->filled('kabupaten')) {
            $query->where('kabupaten', 'like', '%' . $request->kabupaten . '%');
        }

        // Filter berdasarkan kecamatan
        if ($request->filled('kecamatan')) {
            $query->where('kecamatan', 'like', '%' . $request->kecamatan . '%');
        }

        $calonLokasis = $query->latest()->paginate(15);

        // Data untuk peta (semua lokasi dengan koordinat)
        $lokasiMap = CalonLokasi::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'nama_kelompok_desa', 'latitude', 'longitude', 'status_verifikasi']);

        return view('bpdas.geotagging.index', compact('calonLokasis', 'lokasiMap'));
    }

    public function show(CalonLokasi $calonLokasi)
    {
        $calonLokasi->load('user');
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
}