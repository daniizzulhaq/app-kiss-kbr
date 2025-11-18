<?php

namespace App\Http\Controllers;

use App\Models\RencanaBibit;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class RencanaBibitController extends Controller
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
        
        $rencanaBibits = RencanaBibit::with('kelompok')
            ->where('id_kelompok', $kelompok->id)
            ->latest()
            ->paginate(10);

        return view('kelompok.rencana-bibit.index', compact('rencanaBibits'));
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
        
        return view('kelompok.rencana-bibit.create', compact('kelompok'));
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
            'golongan' => 'required|in:MPTS,Kayu,Buah,Bambu',
            'jumlah_btg' => 'required|integer|min:1',
            'tinggi' => 'required|numeric|min:0',
            'sertifikat' => 'nullable|string|max:100',
        ]);

        $validated['id_kelompok'] = $kelompok->id;

        RencanaBibit::create($validated);
        
        return redirect()
            ->route('kelompok.rencana-bibit.index')
            ->with('success', 'Rencana bibit berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(RencanaBibit $rencanaBibit)
    {
        $user = auth()->user();
        $kelompok = Kelompok::where('user_id', $user->id)->first();
        
        if (!$kelompok || $rencanaBibit->id_kelompok !== $kelompok->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('kelompok.rencana-bibit.show', compact('rencanaBibit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RencanaBibit $rencanaBibit)
    {
        $user = auth()->user();
        $kelompok = Kelompok::where('user_id', $user->id)->first();
        
        if (!$kelompok || $rencanaBibit->id_kelompok !== $kelompok->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('kelompok.rencana-bibit.edit', compact('rencanaBibit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RencanaBibit $rencanaBibit)
    {
        $user = auth()->user();
        $kelompok = Kelompok::where('user_id', $user->id)->first();
        
        if (!$kelompok || $rencanaBibit->id_kelompok !== $kelompok->id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'jenis_bibit' => 'required|string|max:100',
            'golongan' => 'required|in:MPTS,Kayu,Buah,Bambu',
            'jumlah_btg' => 'required|integer|min:1',
            'tinggi' => 'required|numeric|min:0',
            'sertifikat' => 'nullable|string|max:100',
        ]);

        $rencanaBibit->update($validated);

        return redirect()
            ->route('kelompok.rencana-bibit.index')
            ->with('success', 'Rencana bibit berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RencanaBibit $rencanaBibit)
    {
        $user = auth()->user();
        $kelompok = Kelompok::where('user_id', $user->id)->first();
        
        if (!$kelompok || $rencanaBibit->id_kelompok !== $kelompok->id) {
            abort(403, 'Unauthorized access.');
        }

        $rencanaBibit->delete();

        return redirect()
            ->route('kelompok.rencana-bibit.index')
            ->with('success', 'Rencana bibit berhasil dihapus!');
    }
}