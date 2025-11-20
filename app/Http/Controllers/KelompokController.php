<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KelompokController extends Controller
{
    public function index()
    {
        $kelompok = Kelompok::where('user_id', auth()->id())->first();
        return view('kelompok.data-kelompok.index', compact('kelompok'));
    }

    public function create()
    {
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
            'dokumentasi.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // Max 2MB per gambar
        ]);

        $validated['user_id'] = auth()->id();

        // Handle upload dokumentasi
        if ($request->hasFile('dokumentasi')) {
            $dokumentasiPaths = [];
            
            foreach ($request->file('dokumentasi') as $file) {
                $path = $file->store('dokumentasi-kelompok', 'public');
                $dokumentasiPaths[] = $path;
            }
            
            $validated['dokumentasi'] = $dokumentasiPaths;
        }

        Kelompok::create($validated);

        return redirect()->route('kelompok.data-kelompok.index')
            ->with('success', 'Data kelompok berhasil ditambahkan');
    }

    public function edit(Kelompok $kelompok)
    {
        if ($kelompok->user_id !== auth()->id()) {
            abort(403);
        }

        return view('kelompok.data-kelompok.edit', compact('kelompok'));
    }

    public function update(Request $request, Kelompok $kelompok)
    {
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
            'dokumentasi.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // Handle upload dokumentasi baru
        if ($request->hasFile('dokumentasi')) {
            // Hapus dokumentasi lama
            if ($kelompok->dokumentasi) {
                foreach ($kelompok->dokumentasi as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $dokumentasiPaths = [];
            
            foreach ($request->file('dokumentasi') as $file) {
                $path = $file->store('dokumentasi-kelompok', 'public');
                $dokumentasiPaths[] = $path;
            }
            
            $validated['dokumentasi'] = $dokumentasiPaths;
        }

        $kelompok->update($validated);

        return redirect()->route('kelompok.data-kelompok.index')
            ->with('success', 'Data kelompok berhasil diperbarui');
    }

     public function deletePhoto(Request $request, Kelompok $kelompok)
{
    $request->validate([
        'photo_path' => 'required|string',
    ]);

    $photoPath = $request->photo_path;

    // Hapus file dari storage
    if (Storage::exists($photoPath)) {
        Storage::delete($photoPath);
    }

    // Update database: hapus path dari array dokumentasi
    $dokumentasi = $kelompok->dokumentasi;

    if (is_string($dokumentasi)) {
        $dokumentasi = json_decode($dokumentasi, true);
    }

    if (is_array($dokumentasi)) {
        $dokumentasi = array_filter($dokumentasi, function ($item) use ($photoPath) {
            return $item !== $photoPath;
        });
        $kelompok->dokumentasi = array_values($dokumentasi); // reset index
        $kelompok->save();
    }

    return response()->json([
        'success' => true,
        'message' => 'Foto berhasil dihapus!'
    ]);
}



    public function destroy(Kelompok $kelompok)
    {
        if ($kelompok->user_id !== auth()->id()) {
            abort(403);
        }

        // Hapus dokumentasi dari storage
        if ($kelompok->dokumentasi) {
            foreach ($kelompok->dokumentasi as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $kelompok->delete();

        return redirect()->route('kelompok.data-kelompok.index')
            ->with('success', 'Data kelompok berhasil dihapus');
    }
}