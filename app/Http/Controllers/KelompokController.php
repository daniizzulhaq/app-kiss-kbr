<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    public function index()
    {
        $kelompok = Kelompok::where('user_id', auth()->id())->first();
        return view('kelompok.data-kelompok.index', compact('kelompok'));
    }

    public function create()
    {
        // Cek apakah user sudah memiliki data kelompok
        if (Kelompok::where('user_id', auth()->id())->exists()) {
            return redirect()->route('kelompok.data-kelompok.index')
                ->with('error', 'Anda sudah memiliki data kelompok');
        }
        
        return view('kelompok.data-kelompok.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'blok' => 'nullable|string|max:255',
            'desa' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'koordinat' => 'nullable|string|max:255',
            'anggota' => 'nullable|integer|min:0',
            'kontak' => 'nullable|string|max:255',
            'spks' => 'nullable|string|max:255',
            'rekening' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        Kelompok::create($validated);

        return redirect()->route('kelompok.data-kelompok.index')
            ->with('success', 'Data kelompok berhasil ditambahkan');
    }

    public function edit(Kelompok $kelompok)
    {
        // Pastikan user hanya bisa edit kelompoknya sendiri
        if ($kelompok->user_id !== auth()->id()) {
            abort(403);
        }

        return view('kelompok.data-kelompok.edit', compact('kelompok'));
    }

    public function update(Request $request, Kelompok $kelompok)
    {
        // Pastikan user hanya bisa update kelompoknya sendiri
        if ($kelompok->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'blok' => 'nullable|string|max:255',
            'desa' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'koordinat' => 'nullable|string|max:255',
            'anggota' => 'nullable|integer|min:0',
            'kontak' => 'nullable|string|max:255',
            'spks' => 'nullable|string|max:255',
            'rekening' => 'nullable|string',
        ]);

        $kelompok->update($validated);

        return redirect()->route('kelompok.data-kelompok.index')
            ->with('success', 'Data kelompok berhasil diperbarui');
    }

    public function destroy(Kelompok $kelompok)
    {
        // Pastikan user hanya bisa hapus kelompoknya sendiri
        if ($kelompok->user_id !== auth()->id()) {
            abort(403);
        }

        $kelompok->delete();

        return redirect()->route('kelompok.data-kelompok.index')
            ->with('success', 'Data kelompok berhasil dihapus');
    }
}