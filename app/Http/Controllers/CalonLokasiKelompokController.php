<?php

namespace App\Http\Controllers;

use App\Models\CalonLokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CalonLokasiKelompokController extends Controller
{
    public function index()
    {
        try {
            $calonLokasis = CalonLokasi::where('user_id', auth()->id())
                ->latest()
                ->paginate(10);

            return view('kelompok.calon-lokasi.index', compact('calonLokasis'));
        } catch (\Exception $e) {
            Log::error('Error on calon lokasi index: ' . $e->getMessage());
            return redirect()->route('kelompok.dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('kelompok.calon-lokasi.create');
        } catch (\Exception $e) {
            Log::error('Error on calon lokasi create page: ' . $e->getMessage());
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
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

            DB::beginTransaction();

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

            DB::commit();

            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('success', 'Calon lokasi berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded files on error
            for ($i = 1; $i <= 5; $i++) {
                $fieldName = "pdf_dokumen_{$i}";
                if (isset($validated[$fieldName]) && Storage::disk('public')->exists($validated[$fieldName])) {
                    Storage::disk('public')->delete($validated[$fieldName]);
                }
            }

            Log::error('Error on calon lokasi store: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $calonLokasi = CalonLokasi::find($id);
            
            if (!$calonLokasi) {
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Data calon lokasi tidak ditemukan.');
            }
            
            // Pastikan user hanya bisa melihat miliknya sendiri
            if ($calonLokasi->user_id !== auth()->id()) {
                Log::warning("User " . auth()->id() . " mencoba akses calon lokasi milik user {$calonLokasi->user_id}");
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Anda tidak memiliki akses ke data ini.');
            }

            return view('kelompok.calon-lokasi.show', compact('calonLokasi'));
        } catch (\Exception $e) {
            Log::error('Error on calon lokasi show: ' . $e->getMessage());
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $calonLokasi = CalonLokasi::find($id);
            
            if (!$calonLokasi) {
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Data calon lokasi tidak ditemukan.');
            }
            
            // Cek kepemilikan
            if ($calonLokasi->user_id !== auth()->id()) {
                Log::warning("User " . auth()->id() . " mencoba edit calon lokasi milik user {$calonLokasi->user_id}");
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit data ini.');
            }
            
            // Cek status verifikasi
            if ($calonLokasi->status_verifikasi !== 'pending') {
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Tidak dapat mengubah calon lokasi yang sudah diverifikasi.');
            }

            return view('kelompok.calon-lokasi.edit', compact('calonLokasi'));
        } catch (\Exception $e) {
            Log::error('Error on calon lokasi edit page: ' . $e->getMessage());
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $calonLokasi = CalonLokasi::find($id);
            
            if (!$calonLokasi) {
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Data calon lokasi tidak ditemukan.');
            }
            
            // Cek kepemilikan
            if ($calonLokasi->user_id !== auth()->id()) {
                Log::warning("User " . auth()->id() . " mencoba update calon lokasi milik user {$calonLokasi->user_id}");
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk mengupdate data ini.');
            }
            
            // Cek status verifikasi
            if ($calonLokasi->status_verifikasi !== 'pending') {
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Tidak dapat mengubah calon lokasi yang sudah diverifikasi.');
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

            DB::beginTransaction();

            // Decode polygon_coordinates
            $validated['polygon_coordinates'] = json_decode($request->polygon_coordinates, true);

            // Upload PDF baru
            for ($i = 1; $i <= 5; $i++) {
                $fieldName = "pdf_dokumen_{$i}";
                if ($request->hasFile($fieldName)) {
                    // Hapus file lama jika ada
                    if ($calonLokasi->$fieldName) {
                        Storage::disk('public')->delete($calonLokasi->$fieldName);
                    }
                    $path = $request->file($fieldName)->store('dokumen-lokasi', 'public');
                    $validated[$fieldName] = $path;
                }
            }

            $calonLokasi->update($validated);

            DB::commit();

            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('success', 'Calon lokasi berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error on calon lokasi update: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate calon lokasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $calonLokasi = CalonLokasi::find($id);
            
            if (!$calonLokasi) {
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Data calon lokasi tidak ditemukan.');
            }
            
            // Cek kepemilikan
            if ($calonLokasi->user_id !== auth()->id()) {
                Log::warning("User " . auth()->id() . " mencoba hapus calon lokasi milik user {$calonLokasi->user_id}");
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
            }

            // Hapus file PDF
            for ($i = 1; $i <= 5; $i++) {
                $fieldName = "pdf_dokumen_{$i}";
                if ($calonLokasi->$fieldName) {
                    Storage::disk('public')->delete($calonLokasi->$fieldName);
                }
            }

            $calonLokasi->delete();

            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('success', 'Calon lokasi berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error on calon lokasi destroy: ' . $e->getMessage());
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Terjadi kesalahan saat menghapus calon lokasi: ' . $e->getMessage());
        }
    }
}