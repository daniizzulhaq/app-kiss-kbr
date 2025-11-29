<?php

namespace App\Http\Controllers;

use App\Models\Permasalahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        $kelompok = auth()->user()->kelompok;

        $permasalahan = Permasalahan::where('kelompok_id', $kelompok->id)
            ->latest()
            ->paginate(10);

        return view('kelompok.permasalahan.index', compact('permasalahan'));
    }

    public function create()
    {
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        return view('kelompok.permasalahan.create');
    }

    public function store(Request $request)
    {
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
    }

    public function show(Permasalahan $permasalahan)
    {
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        $kelompok = auth()->user()->kelompok;

        // Pastikan kelompok hanya bisa melihat permasalahan mereka sendiri
        if ($permasalahan->kelompok_id !== $kelompok->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        return view('kelompok.permasalahan.show', compact('permasalahan'));
    }

    public function edit(Permasalahan $permasalahan)
    {
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        $kelompok = auth()->user()->kelompok;

        if ($permasalahan->kelompok_id !== $kelompok->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        if ($permasalahan->status !== 'pending') {
            return redirect()->route('kelompok.permasalahan.index')
                ->with('error', 'Permasalahan yang sudah ditangani tidak dapat diedit!');
        }

        return view('kelompok.permasalahan.edit', compact('permasalahan'));
    }

    public function update(Request $request, Permasalahan $permasalahan)
    {
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        $kelompok = auth()->user()->kelompok;

        if ($permasalahan->kelompok_id !== $kelompok->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate data ini.');
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

        return redirect()->route('kelompok.permasalahan.show', $permasalahan)
            ->with('success', 'Laporan permasalahan berhasil diupdate!');
    }

    public function destroy(Permasalahan $permasalahan)
    {
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        $kelompok = auth()->user()->kelompok;

        if ($permasalahan->kelompok_id !== $kelompok->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data ini.');
        }

        // Hanya bisa dihapus jika masih pending
        if ($permasalahan->status !== 'pending') {
            return redirect()->route('kelompok.permasalahan.index')
                ->with('error', 'Permasalahan yang sudah ditangani tidak dapat dihapus!');
        }

        $permasalahan->delete();

        return redirect()->route('kelompok.permasalahan.index')
            ->with('success', 'Laporan permasalahan berhasil dihapus!');
    }

    /**
     * Store tanggapan kelompok terhadap solusi BPDAS
     */
    public function storeTanggapan(Request $request, Permasalahan $permasalahan)
    {
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        $kelompok = auth()->user()->kelompok;

        // Validasi akses
        if ($permasalahan->kelompok_id !== $kelompok->id) {
            abort(403, 'Anda tidak memiliki akses untuk memberikan tanggapan pada data ini.');
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

        return redirect()->route('kelompok.permasalahan.show', $permasalahan)
            ->with('success', 'Terima kasih! Tanggapan Anda telah berhasil disimpan.');
    }
}