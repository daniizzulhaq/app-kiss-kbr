<?php

namespace App\Http\Controllers;

use App\Models\CalonLokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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

        // Decode polygon_coordinates JSON menjadi array
        $validated['polygon_coordinates'] = json_decode($request->polygon_coordinates, true);

        // Upload PDF
        for ($i = 1; $i <= 5; $i++) {
            $fieldName = "pdf_dokumen_{$i}";
            if ($request->hasFile($fieldName)) {
                $path = $request->file($fieldName)->store('dokumen-lokasi', 'public');
                $validated[$fieldName] = $path;
            }
        }

        CalonLokasi::create($validated);

        return redirect()->route('kelompok.calon-lokasi.index')
            ->with('success', 'Calon lokasi berhasil ditambahkan!');
    }

    public function show($id)
    {
        $calonLokasi = CalonLokasi::find($id);
        
        if (!$calonLokasi) {
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Data calon lokasi tidak ditemukan');
        }
        
        // Pastikan user hanya bisa melihat miliknya sendiri
        if ($calonLokasi->user_id !== auth()->id()) {
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Anda tidak memiliki akses ke data ini');
        }

        return view('kelompok.calon-lokasi.show', compact('calonLokasi'));
    }

    public function edit($id)
    {
        $calonLokasi = CalonLokasi::find($id);
        
        if (!$calonLokasi) {
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Data calon lokasi tidak ditemukan');
        }
        
        if ($calonLokasi->user_id !== auth()->id()) {
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Anda tidak memiliki akses ke data ini');
        }
        
        if ($calonLokasi->status_verifikasi !== 'pending') {
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Tidak dapat mengubah calon lokasi yang sudah diverifikasi');
        }

        return view('kelompok.calon-lokasi.edit', compact('calonLokasi'));
    }

    public function update(Request $request, $id)
    {
        $calonLokasi = CalonLokasi::find($id);
        
        if (!$calonLokasi) {
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Data calon lokasi tidak ditemukan');
        }
        
        if ($calonLokasi->user_id !== auth()->id()) {
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Anda tidak memiliki akses ke data ini');
        }
        
        if ($calonLokasi->status_verifikasi !== 'pending') {
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Tidak dapat mengubah calon lokasi yang sudah diverifikasi');
        }

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

        // Decode polygon_coordinates
        $validated['polygon_coordinates'] = json_decode($request->polygon_coordinates, true);

        // Upload PDF baru
        for ($i = 1; $i <= 5; $i++) {
            $fieldName = "pdf_dokumen_{$i}";
            if ($request->hasFile($fieldName)) {
                if ($calonLokasi->$fieldName) {
                    Storage::disk('public')->delete($calonLokasi->$fieldName);
                }
                $path = $request->file($fieldName)->store('dokumen-lokasi', 'public');
                $validated[$fieldName] = $path;
            }
        }

        $calonLokasi->update($validated);

        return redirect()->route('kelompok.calon-lokasi.index')
            ->with('success', 'Calon lokasi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $calonLokasi = CalonLokasi::find($id);
        
        if (!$calonLokasi) {
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Data calon lokasi tidak ditemukan');
        }
        
        if ($calonLokasi->user_id !== auth()->id()) {
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Anda tidak memiliki akses ke data ini');
        }

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