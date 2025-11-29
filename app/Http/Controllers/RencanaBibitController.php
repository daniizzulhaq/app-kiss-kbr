<?php

namespace App\Http\Controllers;

use App\Models\RencanaBibit;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RencanaBibitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = auth()->user();
            
            // Ambil kelompok berdasarkan user_id
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.create')
                    ->with('error', 'Anda belum memiliki data kelompok. Silakan buat data kelompok terlebih dahulu.');
            }
            
            // Ambil semua kelompok dengan nama yang sama
            $kelompokIds = Kelompok::where('nama_kelompok', $kelompok->nama_kelompok)
                ->pluck('id')
                ->toArray();
            
            // Ambil rencana bibit dari semua kelompok dengan nama yang sama
            $rencanaBibits = RencanaBibit::with('kelompok.user')
                ->whereIn('id_kelompok', $kelompokIds)
                ->latest()
                ->paginate(10);

            return view('kelompok.rencana-bibit.index', compact('rencanaBibits', 'kelompok'));
        } catch (\Exception $e) {
            Log::error('Error on rencana bibit index: ' . $e->getMessage());
            return redirect()->route('kelompok.dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $user = auth()->user();
            
            // Ambil kelompok berdasarkan user_id
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.create')
                    ->with('error', 'Anda belum memiliki data kelompok. Silakan buat data kelompok terlebih dahulu.');
            }
            
            return view('kelompok.rencana-bibit.create', compact('kelompok'));
        } catch (\Exception $e) {
            Log::error('Error on rencana bibit create page: ' . $e->getMessage());
            return redirect()->route('kelompok.rencana-bibit.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Ambil kelompok berdasarkan user_id
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return back()
                    ->withInput()
                    ->with('error', 'Data kelompok tidak ditemukan. Silakan buat data kelompok terlebih dahulu.');
            }
            
            if (!$kelompok->id) {
                return back()
                    ->withInput()
                    ->with('error', 'ID Kelompok tidak valid. Silakan hubungi administrator.');
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
        } catch (\Exception $e) {
            Log::error('Error on rencana bibit store: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $rencanaBibit = RencanaBibit::find($id);
            
            if (!$rencanaBibit) {
                return redirect()->route('kelompok.rencana-bibit.index')
                    ->with('error', 'Data rencana bibit tidak ditemukan.');
            }
            
            $user = auth()->user();
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.create')
                    ->with('error', 'Anda belum memiliki kelompok.');
            }
            
            // Cek apakah bibit ini dari kelompok dengan nama yang sama
            $kelompokBibit = Kelompok::find($rencanaBibit->id_kelompok);
            
            if (!$kelompokBibit || $kelompokBibit->nama_kelompok !== $kelompok->nama_kelompok) {
                Log::warning("User {$user->id} mencoba akses rencana bibit dari kelompok berbeda");
                return redirect()->route('kelompok.rencana-bibit.index')
                    ->with('error', 'Anda tidak memiliki akses ke data ini.');
            }

            return view('kelompok.rencana-bibit.show', compact('rencanaBibit'));
        } catch (\Exception $e) {
            Log::error('Error on rencana bibit show: ' . $e->getMessage());
            return redirect()->route('kelompok.rencana-bibit.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $rencanaBibit = RencanaBibit::find($id);
            
            if (!$rencanaBibit) {
                return redirect()->route('kelompok.rencana-bibit.index')
                    ->with('error', 'Data rencana bibit tidak ditemukan.');
            }
            
            $user = auth()->user();
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.create')
                    ->with('error', 'Anda belum memiliki data kelompok.');
            }
            
            // Cek berdasarkan nama kelompok yang sama (bukan hanya id_kelompok)
            $kelompokBibit = Kelompok::find($rencanaBibit->id_kelompok);
            
            // Izinkan edit jika bibit dari kelompok dengan nama yang sama
            $canEdit = ($rencanaBibit->id_kelompok === $kelompok->id) || 
                       ($kelompokBibit && $kelompokBibit->nama_kelompok === $kelompok->nama_kelompok);
            
            if (!$canEdit) {
                Log::warning("User {$user->id} mencoba edit rencana bibit milik kelompok lain");
                return redirect()->route('kelompok.rencana-bibit.index')
                    ->with('error', 'Anda hanya dapat mengedit data milik kelompok Anda.');
            }

            return view('kelompok.rencana-bibit.edit', compact('rencanaBibit'));
        } catch (\Exception $e) {
            Log::error('Error on rencana bibit edit page: ' . $e->getMessage());
            return redirect()->route('kelompok.rencana-bibit.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $rencanaBibit = RencanaBibit::find($id);
            
            if (!$rencanaBibit) {
                return redirect()->route('kelompok.rencana-bibit.index')
                    ->with('error', 'Data rencana bibit tidak ditemukan.');
            }
            
            $user = auth()->user();
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.create')
                    ->with('error', 'Anda belum memiliki data kelompok.');
            }
            
            // Cek berdasarkan nama kelompok yang sama
            $kelompokBibit = Kelompok::find($rencanaBibit->id_kelompok);
            
            $canUpdate = ($rencanaBibit->id_kelompok === $kelompok->id) || 
                         ($kelompokBibit && $kelompokBibit->nama_kelompok === $kelompok->nama_kelompok);
            
            if (!$canUpdate) {
                Log::warning("User {$user->id} mencoba update rencana bibit milik kelompok lain");
                return redirect()->route('kelompok.rencana-bibit.index')
                    ->with('error', 'Anda hanya dapat mengupdate data milik kelompok Anda.');
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
        } catch (\Exception $e) {
            Log::error('Error on rencana bibit update: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $rencanaBibit = RencanaBibit::find($id);
            
            if (!$rencanaBibit) {
                return redirect()->route('kelompok.rencana-bibit.index')
                    ->with('error', 'Data rencana bibit tidak ditemukan.');
            }
            
            $user = auth()->user();
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.create')
                    ->with('error', 'Anda belum memiliki data kelompok.');
            }
            
            // Cek berdasarkan nama kelompok yang sama
            $kelompokBibit = Kelompok::find($rencanaBibit->id_kelompok);
            
            $canDelete = ($rencanaBibit->id_kelompok === $kelompok->id) || 
                         ($kelompokBibit && $kelompokBibit->nama_kelompok === $kelompok->nama_kelompok);
            
            if (!$canDelete) {
                Log::warning("User {$user->id} mencoba hapus rencana bibit milik kelompok lain");
                return redirect()->route('kelompok.rencana-bibit.index')
                    ->with('error', 'Anda hanya dapat menghapus data milik kelompok Anda.');
            }

            $rencanaBibit->delete();

            return redirect()
                ->route('kelompok.rencana-bibit.index')
                ->with('success', 'Rencana bibit berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error on rencana bibit destroy: ' . $e->getMessage());
            return redirect()->route('kelompok.rencana-bibit.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}