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
            
            $rencanaBibits = RencanaBibit::with('kelompok')
                ->where('id_kelompok', $kelompok->id)
                ->latest()
                ->paginate(10);

            return view('kelompok.rencana-bibit.index', compact('rencanaBibits'));
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
            
            if (!$kelompok || $rencanaBibit->id_kelompok !== $kelompok->id) {
                Log::warning("User {$user->id} mencoba akses rencana bibit milik kelompok lain");
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
            
            if (!$kelompok || $rencanaBibit->id_kelompok !== $kelompok->id) {
                Log::warning("User {$user->id} mencoba edit rencana bibit milik kelompok lain");
                return redirect()->route('kelompok.rencana-bibit.index')
                    ->with('error', 'Anda tidak memiliki akses ke data ini.');
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
            
            if (!$kelompok || $rencanaBibit->id_kelompok !== $kelompok->id) {
                Log::warning("User {$user->id} mencoba update rencana bibit milik kelompok lain");
                return redirect()->route('kelompok.rencana-bibit.index')
                    ->with('error', 'Anda tidak memiliki akses ke data ini.');
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
            
            if (!$kelompok || $rencanaBibit->id_kelompok !== $kelompok->id) {
                Log::warning("User {$user->id} mencoba hapus rencana bibit milik kelompok lain");
                return redirect()->route('kelompok.rencana-bibit.index')
                    ->with('error', 'Anda tidak memiliki akses ke data ini.');
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