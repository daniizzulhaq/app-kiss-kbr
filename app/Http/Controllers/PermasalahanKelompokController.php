<?php

namespace App\Http\Controllers;

use App\Models\Permasalahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PermasalahanKelompokController extends Controller
{
    /**
     * Cek apakah user memiliki kelompok
     */
    private function checkKelompok()
    {
        $user = auth()->user();
        
        if (!$user->kelompok) {
            return redirect()->route('kelompok.data-kelompok.create')
                ->with('warning', 'Anda belum tergabung dalam kelompok. Silakan buat atau bergabung dengan kelompok terlebih dahulu.');
        }
        
        return null;
    }

    public function index()
    {
        try {
            // Cek kelompok
            $checkResult = $this->checkKelompok();
            if ($checkResult) return $checkResult;

            $kelompok = auth()->user()->kelompok;

            $permasalahan = Permasalahan::where('kelompok_id', $kelompok->id)
                ->latest()
                ->paginate(10);

            return view('kelompok.permasalahan.index', compact('permasalahan'));
        } catch (\Exception $e) {
            Log::error('Error on permasalahan index: ' . $e->getMessage());
            return redirect()->route('kelompok.dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            // Cek kelompok
            $checkResult = $this->checkKelompok();
            if ($checkResult) return $checkResult;

            return view('kelompok.permasalahan.create');
        } catch (\Exception $e) {
            Log::error('Error on permasalahan create page: ' . $e->getMessage());
            return redirect()->route('kelompok.permasalahan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            // Cek kelompok
            $checkResult = $this->checkKelompok();
            if ($checkResult) return $checkResult;

            $kelompok = auth()->user()->kelompok;

            $validated = $request->validate([
                'kelompok' => 'required|string|max:255',
                'sarpras' => 'required|string|max:255',
                'bibit' => 'required|string|max:255',
                'lokasi_tanam' => 'required|string|max:255',
                'permasalahan' => 'required|string',
                'prioritas' => 'required|in:rendah,sedang,tinggi',
            ], [
                'kelompok.required' => 'Nama kelompok harus diisi',
                'sarpras.required' => 'Sarana prasarana harus diisi',
                'bibit.required' => 'Bibit harus diisi',
                'lokasi_tanam.required' => 'Lokasi tanam harus diisi',
                'permasalahan.required' => 'Deskripsi permasalahan harus diisi',
                'prioritas.required' => 'Prioritas harus dipilih',
                'prioritas.in' => 'Prioritas harus berupa rendah, sedang, atau tinggi',
            ]);

            Permasalahan::create([
                'kelompok_id' => $kelompok->id,
                'kelompok' => $validated['kelompok'],
                'sarpras' => $validated['sarpras'],
                'bibit' => $validated['bibit'],
                'lokasi_tanam' => $validated['lokasi_tanam'],
                'permasalahan' => $validated['permasalahan'],
                'prioritas' => $validated['prioritas'],
                'status' => 'pending',
            ]);

            return redirect()->route('kelompok.permasalahan.index')
                ->with('success', 'Laporan permasalahan berhasil dikirim dan menunggu ditangani oleh BPDAS!');
        } catch (\Exception $e) {
            Log::error('Error on permasalahan store: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            // Cek kelompok
            $checkResult = $this->checkKelompok();
            if ($checkResult) return $checkResult;

            $permasalahan = Permasalahan::find($id);
            
            if (!$permasalahan) {
                return redirect()->route('kelompok.permasalahan.index')
                    ->with('error', 'Data permasalahan tidak ditemukan.');
            }

            $kelompok = auth()->user()->kelompok;

            // Pastikan kelompok hanya bisa melihat permasalahan mereka sendiri
            if ($permasalahan->kelompok_id !== $kelompok->id) {
                Log::warning("User " . auth()->id() . " mencoba akses permasalahan milik kelompok {$permasalahan->kelompok_id}");
                return redirect()->route('kelompok.permasalahan.index')
                    ->with('error', 'Anda tidak memiliki akses untuk melihat data ini.');
            }

            return view('kelompok.permasalahan.show', compact('permasalahan'));
        } catch (\Exception $e) {
            Log::error('Error on permasalahan show: ' . $e->getMessage());
            return redirect()->route('kelompok.permasalahan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            // Cek kelompok
            $checkResult = $this->checkKelompok();
            if ($checkResult) return $checkResult;

            $permasalahan = Permasalahan::find($id);
            
            if (!$permasalahan) {
                return redirect()->route('kelompok.permasalahan.index')
                    ->with('error', 'Data permasalahan tidak ditemukan.');
            }

            $kelompok = auth()->user()->kelompok;

            if ($permasalahan->kelompok_id !== $kelompok->id) {
                Log::warning("User " . auth()->id() . " mencoba edit permasalahan milik kelompok {$permasalahan->kelompok_id}");
                return redirect()->route('kelompok.permasalahan.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
            }

            if ($permasalahan->status !== 'pending') {
                return redirect()->route('kelompok.permasalahan.index')
                    ->with('error', 'Permasalahan yang sudah ditangani tidak dapat diedit!');
            }

            return view('kelompok.permasalahan.edit', compact('permasalahan'));
        } catch (\Exception $e) {
            Log::error('Error on permasalahan edit page: ' . $e->getMessage());
            return redirect()->route('kelompok.permasalahan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Cek kelompok
            $checkResult = $this->checkKelompok();
            if ($checkResult) return $checkResult;

            $permasalahan = Permasalahan::find($id);
            
            if (!$permasalahan) {
                return redirect()->route('kelompok.permasalahan.index')
                    ->with('error', 'Data permasalahan tidak ditemukan.');
            }

            $kelompok = auth()->user()->kelompok;

            if ($permasalahan->kelompok_id !== $kelompok->id) {
                Log::warning("User " . auth()->id() . " mencoba update permasalahan milik kelompok {$permasalahan->kelompok_id}");
                return redirect()->route('kelompok.permasalahan.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengupdate data ini.');
            }

            if ($permasalahan->status !== 'pending') {
                return redirect()->route('kelompok.permasalahan.index')
                    ->with('error', 'Permasalahan yang sudah ditangani tidak dapat diedit!');
            }

            $validated = $request->validate([
                'kelompok' => 'required|string|max:255',
                'sarpras' => 'required|string|max:255',
                'bibit' => 'required|string|max:255',
                'lokasi_tanam' => 'required|string|max:255',
                'permasalahan' => 'required|string',
                'prioritas' => 'required|in:rendah,sedang,tinggi',
            ], [
                'kelompok.required' => 'Nama kelompok harus diisi',
                'sarpras.required' => 'Sarana prasarana harus diisi',
                'bibit.required' => 'Bibit harus diisi',
                'lokasi_tanam.required' => 'Lokasi tanam harus diisi',
                'permasalahan.required' => 'Deskripsi permasalahan harus diisi',
                'prioritas.required' => 'Prioritas harus dipilih',
            ]);

            $permasalahan->update($validated);

            return redirect()->route('kelompok.permasalahan.show', $id)
                ->with('success', 'Laporan permasalahan berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Error on permasalahan update: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Cek kelompok
            $checkResult = $this->checkKelompok();
            if ($checkResult) return $checkResult;

            $permasalahan = Permasalahan::find($id);
            
            if (!$permasalahan) {
                return redirect()->route('kelompok.permasalahan.index')
                    ->with('error', 'Data permasalahan tidak ditemukan.');
            }

            $kelompok = auth()->user()->kelompok;

            if ($permasalahan->kelompok_id !== $kelompok->id) {
                Log::warning("User " . auth()->id() . " mencoba hapus permasalahan milik kelompok {$permasalahan->kelompok_id}");
                return redirect()->route('kelompok.permasalahan.index')
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
            }

            // Hanya bisa dihapus jika masih pending
            if ($permasalahan->status !== 'pending') {
                return redirect()->route('kelompok.permasalahan.index')
                    ->with('error', 'Permasalahan yang sudah ditangani tidak dapat dihapus!');
            }

            $permasalahan->delete();

            return redirect()->route('kelompok.permasalahan.index')
                ->with('success', 'Laporan permasalahan berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error on permasalahan destroy: ' . $e->getMessage());
            return redirect()->route('kelompok.permasalahan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store tanggapan kelompok terhadap solusi BPDAS
     */
    public function storeTanggapan(Request $request, $id)
    {
        try {
            // Cek kelompok
            $checkResult = $this->checkKelompok();
            if ($checkResult) return $checkResult;

            $permasalahan = Permasalahan::find($id);
            
            if (!$permasalahan) {
                return redirect()->route('kelompok.permasalahan.index')
                    ->with('error', 'Data permasalahan tidak ditemukan.');
            }

            $kelompok = auth()->user()->kelompok;

            // Validasi akses
            if ($permasalahan->kelompok_id !== $kelompok->id) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk memberikan tanggapan pada data ini.');
            }

            // Validasi status - hanya bisa memberikan tanggapan jika status selesai
            if ($permasalahan->status !== 'selesai') {
                return redirect()->back()
                    ->with('error', 'Tanggapan hanya dapat diberikan pada permasalahan yang sudah selesai ditangani.');
            }

            // Validasi jika sudah ada tanggapan
            if ($permasalahan->tanggapan_kelompok) {
                return redirect()->back()
                    ->with('error', 'Tanggapan sudah pernah diberikan sebelumnya.');
            }

            // Validasi input
            $validated = $request->validate([
                'tanggapan_kelompok' => 'required|string|min:10|max:1000',
            ], [
                'tanggapan_kelompok.required' => 'Tanggapan harus diisi',
                'tanggapan_kelompok.min' => 'Tanggapan minimal 10 karakter',
                'tanggapan_kelompok.max' => 'Tanggapan maksimal 1000 karakter',
            ]);

            // Update tanggapan
            $permasalahan->update([
                'tanggapan_kelompok' => $validated['tanggapan_kelompok'],
            ]);

            return redirect()->route('kelompok.permasalahan.show', $id)
                ->with('success', 'Terima kasih! Tanggapan Anda telah berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Error on permasalahan storeTanggapan: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}