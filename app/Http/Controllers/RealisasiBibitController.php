<?php

namespace App\Http\Controllers;

use App\Models\RealBibit;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class RealisasiBibitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Ambil kelompok berdasarkan user_id
        $kelompok = Kelompok::where('user_id', $user->id)->first();
        
        if (!$kelompok) {
            return redirect()->route('kelompok.data-kelompok.create')
                ->with('error', 'Anda belum memiliki data kelompok. Silakan buat data kelompok terlebih dahulu.');
        }
        
        $realBibits = RealBibit::with('kelompok')
            ->where('id_kelompok', $kelompok->id)
            ->latest()
            ->paginate(10);

        return view('kelompok.realisasi-bibit.index', compact('realBibits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Ambil kelompok berdasarkan user_id
        $kelompok = Kelompok::where('user_id', $user->id)->first();
        
        if (!$kelompok) {
            return redirect()->route('kelompok.data-kelompok.create')
                ->with('error', 'Anda belum memiliki data kelompok. Silakan buat data kelompok terlebih dahulu.');
        }
        
        return view('kelompok.realisasi-bibit.create', compact('kelompok'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Ambil kelompok berdasarkan user_id
        $kelompok = Kelompok::where('user_id', $user->id)->first();
        
        if (!$kelompok) {
            return back()
                ->withInput()
                ->withErrors(['kelompok' => 'Data kelompok tidak ditemukan. Silakan buat data kelompok terlebih dahulu.']);
        }
        
        if (!$kelompok->id) {
            return back()
                ->withInput()
                ->withErrors(['kelompok' => 'ID Kelompok tidak valid. Silakan hubungi administrator.']);
        }
        
        $validated = $request->validate([
            'jenis_bibit' => 'required|string|max:100',
            'golongan' => 'nullable|string|max:50',
            'jumlah_btg' => 'required|integer|min:1',
            'tinggi' => 'nullable|numeric|min:0',
            'sertifikat' => 'nullable|string|max:100',
        ]);

        $validated['id_kelompok'] = $kelompok->id;

        RealBibit::create($validated);
        
        return redirect()
            ->route('kelompok.realisasi-bibit.index')
            ->with('success', 'Data realisasi bibit berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RealBibit $realisasiBibit)
    {
        $user = auth()->user();
        $kelompok = Kelompok::where('user_id', $user->id)->first();
        
        if (!$kelompok || $realisasiBibit->id_kelompok !== $kelompok->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('kelompok.realisasi-bibit.show', compact('realisasiBibit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RealBibit $realisasiBibit)
    {
        $user = auth()->user();
        $kelompok = Kelompok::where('user_id', $user->id)->first();
        
        if (!$kelompok || $realisasiBibit->id_kelompok !== $kelompok->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('kelompok.realisasi-bibit.edit', compact('realisasiBibit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RealBibit $realisasiBibit)
    {
        $user = auth()->user();
        $kelompok = Kelompok::where('user_id', $user->id)->first();
        
        if (!$kelompok || $realisasiBibit->id_kelompok !== $kelompok->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'jenis_bibit' => 'required|string|max:100',
            'golongan' => 'nullable|string|max:50',
            'jumlah_btg' => 'required|integer|min:1',
            'tinggi' => 'nullable|numeric|min:0',
            'sertifikat' => 'nullable|string|max:100',
        ]);

        $realisasiBibit->update($validated);

        return redirect()
            ->route('kelompok.realisasi-bibit.index')
            ->with('success', 'Data realisasi bibit berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RealBibit $realisasiBibit)
    {
        $user = auth()->user();
        $kelompok = Kelompok::where('user_id', $user->id)->first();
        
        if (!$kelompok || $realisasiBibit->id_kelompok !== $kelompok->id) {
            abort(403, 'Unauthorized access.');
        }

        $realisasiBibit->delete();

        return redirect()
            ->route('kelompok.realisasi-bibit.index')
            ->with('success', 'Data realisasi bibit berhasil dihapus.');
    }
}