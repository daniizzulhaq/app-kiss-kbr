<?php

namespace App\Http\Controllers;

use App\Models\PetaLokasi;
use Illuminate\Http\Request;

class PetaLokasiBpdasController extends Controller
{
    public function index(Request $request)
    {
        $query = PetaLokasi::with(['kelompok', 'user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by kelompok
        if ($request->filled('kelompok_id')) {
            $query->where('kelompok_id', $request->kelompok_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhereHas('kelompok', function($q) use ($search) {
                      $q->where('nama_kelompok', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $petaLokasis = $query->latest()->paginate(15);

        // Data untuk filter
        $kelompoks = \App\Models\Kelompok::orderBy('nama_kelompok')->get();

        return view('bpdas.peta-lokasi.index', compact('petaLokasis', 'kelompoks'));
    }

    public function show(PetaLokasi $petaLokasi)
    {
        $petaLokasi->load(['kelompok', 'user', 'verifiedBy']);
        return view('bpdas.peta-lokasi.show', compact('petaLokasi'));
    }

    public function verifikasi(Request $request, PetaLokasi $petaLokasi)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'catatan_bpdas' => 'nullable|string',
        ]);

        $petaLokasi->update([
            'status' => $request->status,
            'catatan_bpdas' => $request->catatan_bpdas,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $statusText = $request->status === 'diterima' ? 'diterima' : 'ditolak';

        return redirect()->route('bpdas.peta-lokasi.index')
            ->with('success', "Peta lokasi berhasil {$statusText}.");
    }
}