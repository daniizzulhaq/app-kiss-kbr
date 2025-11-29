<?php

namespace App\Http\Controllers;

use App\Models\Permasalahan;
use Illuminate\Http\Request;

class PermasalahanKelompokController extends Controller
{
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
        $check = $this->checkKelompok();
        if ($check) return $check;

        $kelompok = auth()->user()->kelompok;

        $permasalahan = Permasalahan::where('kelompok_id', $kelompok->id)
            ->latest()
            ->paginate(10);

        return view('kelompok.permasalahan.index', compact('permasalahan'));
    }

    public function create()
    {
        $check = $this->checkKelompok();
        if ($check) return $check;

        return view('kelompok.permasalahan.create');
    }

    public function store(Request $request)
    {
        $check = $this->checkKelompok();
        if ($check) return $check;

        $kelompok = auth()->user()->kelompok;

        $validated = $request->validate([
            'kelompok' => 'required|string|max:255',
            'sarpras' => 'required|string|max:255',
            'bibit' => 'required|string|max:255',
            'lokasi_tanam' => 'required|string|max:255',
            'permasalahan' => 'required|string',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
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
            ->with('success', 'Laporan permasalahan berhasil dikirim!');
    }

    public function show($id)
    {
        $check = $this->checkKelompok();
        if ($check) return $check;

        $kelompok = auth()->user()->kelompok;

        $permasalahan = Permasalahan::where('id', $id)
            ->where('kelompok_id', $kelompok->id)
            ->firstOrFail();

        return view('kelompok.permasalahan.show', compact('permasalahan'));
    }

    public function edit($id)
    {
        $check = $this->checkKelompok();
        if ($check) return $check;

        $kelompok = auth()->user()->kelompok;

        $permasalahan = Permasalahan::where('id', $id)
            ->where('kelompok_id', $kelompok->id)
            ->firstOrFail();

        if ($permasalahan->status !== 'pending') {
            return redirect()->route('kelompok.permasalahan.index')
                ->with('error', 'Permasalahan yang sudah ditangani tidak dapat diedit!');
        }

        return view('kelompok.permasalahan.edit', compact('permasalahan'));
    }

    public function update(Request $request, $id)
    {
        $check = $this->checkKelompok();
        if ($check) return $check;

        $kelompok = auth()->user()->kelompok;

        $permasalahan = Permasalahan::where('id', $id)
            ->where('kelompok_id', $kelompok->id)
            ->firstOrFail();

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
        ]);

        $permasalahan->update($validated);

        return redirect()->route('kelompok.permasalahan.show', $permasalahan->id)
            ->with('success', 'Laporan permasalahan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $check = $this->checkKelompok();
        if ($check) return $check;

        $kelompok = auth()->user()->kelompok;

        $permasalahan = Permasalahan::where('id', $id)
            ->where('kelompok_id', $kelompok->id)
            ->firstOrFail();

        if ($permasalahan->status !== 'pending') {
            return redirect()->route('kelompok.permasalahan.index')
                ->with('error', 'Permasalahan yang sudah ditangani tidak dapat dihapus!');
        }

        $permasalahan->delete();

        return redirect()->route('kelompok.permasalahan.index')
            ->with('success', 'Laporan permasalahan berhasil dihapus!');
    }

    public function storeTanggapan(Request $request, $id)
    {
        $check = $this->checkKelompok();
        if ($check) return $check;

        $kelompok = auth()->user()->kelompok;

        $permasalahan = Permasalahan::where('id', $id)
            ->where('kelompok_id', $kelompok->id)
            ->firstOrFail();

        if ($permasalahan->status !== 'selesai') {
            return redirect()->back()->with('error', 'Tanggapan hanya dapat diberikan jika status selesai.');
        }

        if ($permasalahan->tanggapan_kelompok) {
            return redirect()->back()->with('error', 'Tanggapan sudah pernah diberikan.');
        }

        $validated = $request->validate([
            'tanggapan_kelompok' => 'required|string|min:10|max:1000',
        ]);

        $permasalahan->update([
            'tanggapan_kelompok' => $validated['tanggapan_kelompok'],
        ]);

        return redirect()->route('kelompok.permasalahan.show', $permasalahan->id)
            ->with('success', 'Tanggapan berhasil disimpan!');
    }
}
