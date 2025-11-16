<?php

namespace App\Http\Controllers;

use App\Models\CalonLokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CalonLokasiKelompokController extends Controller
{
    public function index()
    {
        $calonLokasis = CalonLokasi::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('kelompok.calon-lokasi.index', compact('calonLokasis'));
    }

    public function create()
    {
        return view('kelompok.calon-lokasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelompok_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'koordinat_pdf_lokasi' => 'nullable|file|mimes:pdf|max:5120',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        // Upload PDF jika ada
        if ($request->hasFile('koordinat_pdf_lokasi')) {
            $path = $request->file('koordinat_pdf_lokasi')->store('koordinat-lokasi', 'public');
            $validated['koordinat_pdf_lokasi'] = $path;
        }

        CalonLokasi::create($validated);

        return redirect()->route('kelompok.calon-lokasi.index')
            ->with('success', 'Calon lokasi berhasil ditambahkan!');
    }

    public function show(CalonLokasi $calonLokasi)
    {
        // Pastikan hanya pemilik yang bisa melihat
        if ($calonLokasi->user_id !== auth()->id()) {
            abort(403);
        }

        return view('kelompok.calon-lokasi.show', compact('calonLokasi'));
    }

    public function edit(CalonLokasi $calonLokasi)
    {
        // Hanya bisa edit jika status masih pending
        if ($calonLokasi->user_id !== auth()->id() || $calonLokasi->status_verifikasi !== 'pending') {
            abort(403);
        }

        return view('kelompok.calon-lokasi.edit', compact('calonLokasi'));
    }

    public function update(Request $request, CalonLokasi $calonLokasi)
    {
        if ($calonLokasi->user_id !== auth()->id() || $calonLokasi->status_verifikasi !== 'pending') {
            abort(403);
        }

        $validated = $request->validate([
            'nama_kelompok_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'koordinat_pdf_lokasi' => 'nullable|file|mimes:pdf|max:5120',
            'deskripsi' => 'nullable|string',
        ]);

        // Upload PDF baru jika ada
        if ($request->hasFile('koordinat_pdf_lokasi')) {
            // Hapus file lama
            if ($calonLokasi->koordinat_pdf_lokasi) {
                Storage::disk('public')->delete($calonLokasi->koordinat_pdf_lokasi);
            }
            $path = $request->file('koordinat_pdf_lokasi')->store('koordinat-lokasi', 'public');
            $validated['koordinat_pdf_lokasi'] = $path;
        }

        $calonLokasi->update($validated);

        return redirect()->route('kelompok.calon-lokasi.index')
            ->with('success', 'Calon lokasi berhasil diperbarui!');
    }

    public function destroy(CalonLokasi $calonLokasi)
    {
        if ($calonLokasi->user_id !== auth()->id()) {
            abort(403);
        }

        // Hapus file PDF jika ada
        if ($calonLokasi->koordinat_pdf_lokasi) {
            Storage::disk('public')->delete($calonLokasi->koordinat_pdf_lokasi);
        }

        $calonLokasi->delete();

        return redirect()->route('kelompok.calon-lokasi.index')
            ->with('success', 'Calon lokasi berhasil dihapus!');
    }
}