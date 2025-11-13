<?php

namespace App\Http\Controllers;

use App\Models\Permasalahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermasalahanKelompokController extends Controller
{
    public function index()
    {
        $permasalahan = Permasalahan::where('kelompok_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('kelompok.permasalahan.index', compact('permasalahan'));
    }

    public function create()
    {
        return view('kelompok.permasalahan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelompok' => 'required|string|max:255',
            'sarpras' => 'required|string|max:255',
            'bibit' => 'required|string|max:255',
            'lokasi_tanam' => 'required|string|max:255',
            'permasalahan' => 'required|string',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
        ]);

        Permasalahan::create([
            'kelompok_id' => Auth::id(),
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

    public function show(Permasalahan $permasalahan)
    {
        // Pastikan kelompok hanya bisa melihat permasalahan mereka sendiri
        if ($permasalahan->kelompok_id !== Auth::id()) {
            abort(403);
        }

        return view('kelompok.permasalahan.show', compact('permasalahan'));
    }

    public function edit(Permasalahan $permasalahan)
    {
        if ($permasalahan->kelompok_id !== Auth::id()) {
            abort(403);
        }

        if ($permasalahan->status !== 'pending') {
            return redirect()->back()->with('error', 'Permasalahan yang sudah ditangani tidak dapat diedit!');
        }

        return view('kelompok.permasalahan.edit', compact('permasalahan'));
    }

    public function update(Request $request, Permasalahan $permasalahan)
    {
        if ($permasalahan->kelompok_id !== Auth::id()) {
            abort(403);
        }

        if ($permasalahan->status !== 'pending') {
            return redirect()->back()->with('error', 'Permasalahan yang sudah ditangani tidak dapat diedit!');
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

        return redirect()->route('kelompok.permasalahan.show', $permasalahan)
            ->with('success', 'Laporan permasalahan berhasil diupdate!');
    }

    public function destroy(Permasalahan $permasalahan)
    {
        if ($permasalahan->kelompok_id !== Auth::id()) {
            abort(403);
        }

        // Hanya bisa dihapus jika masih pending
        if ($permasalahan->status !== 'pending') {
            return redirect()->back()->with('error', 'Permasalahan yang sudah ditangani tidak dapat dihapus!');
        }

        $permasalahan->delete();

        return redirect()->route('kelompok.permasalahan.index')
            ->with('success', 'Laporan permasalahan berhasil dihapus!');
    }
}