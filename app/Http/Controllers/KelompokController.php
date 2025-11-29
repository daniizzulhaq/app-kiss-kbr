<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class KelompokController extends Controller
{
    public function index()
    {
        $kelompok = Kelompok::where('user_id', auth()->id())->first();
        return view('kelompok.data-kelompok.index', compact('kelompok'));
    }

    public function create()
    {
        try {
            if (Kelompok::where('user_id', auth()->id())->exists()) {
                return redirect()->route('kelompok.data-kelompok.index')
                    ->with('error', 'Anda sudah memiliki data kelompok');
            }
            
            return view('kelompok.data-kelompok.create');
        } catch (\Exception $e) {
            Log::error('Error on create page: ' . $e->getMessage());
            return redirect()->route('kelompok.data-kelompok.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error on store: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id = null)
    {
        try {
            // Ambil kelompok milik user yang login (abaikan ID dari URL)
            $kelompok = Kelompok::where('user_id', auth()->id())->first();
            
            // Jika tidak ditemukan
            if (!$kelompok) {
                Log::warning("User " . auth()->id() . " tidak memiliki data kelompok");
                return redirect()->route('kelompok.data-kelompok.index')
                    ->with('error', 'Anda belum memiliki data kelompok. Silakan buat terlebih dahulu.');
            }

            return view('kelompok.data-kelompok.edit', compact('kelompok'));
        } catch (\Exception $e) {
            Log::error('Error on edit page: ' . $e->getMessage());
            return redirect()->route('kelompok.data-kelompok.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id = null)
    {
        try {
            // Ambil kelompok milik user yang login (abaikan ID dari URL)
            $kelompok = Kelompok::where('user_id', auth()->id())->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.index')
                    ->with('error', 'Data kelompok tidak ditemukan');
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
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
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
        } catch (\Exception $e) {
            Log::error('Error on update: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deletePhoto(Request $request, $id = null)
    {
        try {
            // Ambil kelompok milik user yang login
            $kelompok = Kelompok::where('user_id', auth()->id())->first();
            
            if (!$kelompok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $request->validate([
                'photo_path' => 'required|string',
            ]);

            $photoPath = $request->photo_path;

            // Hapus file dari storage
            if (Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
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
                $kelompok->dokumentasi = array_values($dokumentasi);
                $kelompok->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error on delete photo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id = null)
    {
        try {
            // Ambil kelompok milik user yang login
            $kelompok = Kelompok::where('user_id', auth()->id())->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.index')
                    ->with('error', 'Data kelompok tidak ditemukan');
            }

            // Hapus dokumentasi dari storage
            if ($kelompok->dokumentasi) {
                foreach ($kelompok->dokumentasi as $path) {
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }

            $kelompok->delete();

            return redirect()->route('kelompok.data-kelompok.index')
                ->with('success', 'Data kelompok berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error on destroy: ' . $e->getMessage());
            return redirect()->route('kelompok.data-kelompok.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}