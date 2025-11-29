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
            'polygon_coordinates' => 'required|string',
            'center_latitude' => 'required|numeric|between:-90,90',
            'center_longitude' => 'required|numeric|between:-180,180',
            'pdf_dokumen_1' => 'nullable|file|mimes:pdf|max:5120',
            'pdf_dokumen_2' => 'nullable|file|mimes:pdf|max:5120',
            'pdf_dokumen_3' => 'nullable|file|mimes:pdf|max:5120',
            'pdf_dokumen_4' => 'nullable|file|mimes:pdf|max:5120',
            'pdf_dokumen_5' => 'nullable|file|mimes:pdf|max:5120',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['polygon_coordinates'] = json_decode($request->polygon_coordinates, true);

        // Upload PDF
        for ($i = 1; $i <= 5; $i++) {
            $fieldName = "pdf_dokumen_{$i}";
            if ($request->hasFile($fieldName)) {
                $validated[$fieldName] = $request->file($fieldName)->store('dokumen-lokasi', 'public');
            }
        }

        CalonLokasi::create($validated);

        return redirect()->route('kelompok.calon-lokasi.index')
            ->with('success', 'Calon lokasi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $calonLokasi = CalonLokasi::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('kelompok.calon-lokasi.edit', compact('calonLokasi'));
    }

    public function update(Request $request, $id)
    {
        $calonLokasi = CalonLokasi::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $validated = $request->validate([
            'nama_kelompok_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'polygon_coordinates' => 'required|string',
            'center_latitude' => 'required|numeric|between:-90,90',
            'center_longitude' => 'required|numeric|between:-180,180',
            'pdf_dokumen_1' => 'nullable|file|mimes:pdf|max:5120',
            'pdf_dokumen_2' => 'nullable|file|mimes:pdf|max:5120',
            'pdf_dokumen_3' => 'nullable|file|mimes:pdf|max:5120',
            'pdf_dokumen_4' => 'nullable|file|mimes:pdf|max:5120',
            'pdf_dokumen_5' => 'nullable|file|mimes:pdf|max:5120',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['polygon_coordinates'] = json_decode($request->polygon_coordinates, true);

        for ($i = 1; $i <= 5; $i++) {
            $fieldName = "pdf_dokumen_{$i}";
            if ($request->hasFile($fieldName)) {
                if ($calonLokasi->$fieldName) {
                    Storage::disk('public')->delete($calonLokasi->$fieldName);
                }

                $validated[$fieldName] = $request->file($fieldName)->store('dokumen-lokasi', 'public');
            }
        }

        $calonLokasi->update($validated);

        return redirect()->route('kelompok.calon-lokasi.index')
            ->with('success', 'Calon lokasi berhasil diperbarui!');
    }

    public function show($id)
    {
        $calonLokasi = CalonLokasi::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('kelompok.calon-lokasi.show', compact('calonLokasi'));
    }

    public function destroy($id)
    {
        $calonLokasi = CalonLokasi::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        for ($i = 1; $i <= 5; $i++) {
            $fieldName = "pdf_dokumen_{$i}";
            if ($calonLokasi->$fieldName) {
                Storage::disk('public')->delete($calonLokasi->$fieldName);
            }
        }

        $calonLokasi->delete();

        return redirect()->route('kelompok.calon-lokasi.index')
            ->with('success', 'Calon lokasi berhasil dihapus!');
    }
}
