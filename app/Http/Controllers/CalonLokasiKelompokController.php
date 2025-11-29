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
        try {
            $calonLokasis = CalonLokasi::where('user_id', auth()->id())
                ->latest()
                ->paginate(10);

            // Debug log untuk melihat data
            Log::info('Calon Lokasi Data:', [
                'count' => $calonLokasis->count(),
                'user_id' => auth()->id(),
                'items' => $calonLokasis->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama' => $item->nama_kelompok_desa,
                        'user_id' => $item->user_id
                    ];
                })
            ]);

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
            Log::error('Error on create page: ' . $e->getMessage());
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

            $calonLokasi = CalonLokasi::create($validated);

            Log::info('Calon Lokasi Created:', ['id' => $calonLokasi->id]);

            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('success', 'Calon lokasi berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error on store: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            Log::info('Edit Request:', [
                'id' => $id,
                'user_id' => auth()->id(),
                'request_url' => request()->url()
            ]);

            // Cari calon lokasi berdasarkan ID
            $calonLokasi = CalonLokasi::find($id);
            
            // Jika tidak ditemukan
            if (!$calonLokasi) {
                Log::warning("Calon Lokasi ID {$id} tidak ditemukan");
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Data calon lokasi tidak ditemukan');
            }
            
            Log::info('Calon Lokasi Found:', [
                'id' => $calonLokasi->id,
                'user_id' => $calonLokasi->user_id,
                'auth_user_id' => auth()->id(),
                'status' => $calonLokasi->status_verifikasi
            ]);

            // Jika bukan milik user yang login
            if ($calonLokasi->user_id !== auth()->id()) {
                Log::warning("User " . auth()->id() . " mencoba akses calon lokasi milik user {$calonLokasi->user_id}");
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Anda tidak memiliki akses ke data ini');
            }

            // Jika sudah diverifikasi (bukan pending)
            if ($calonLokasi->status_verifikasi !== 'pending') {
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Tidak dapat mengubah calon lokasi yang sudah diverifikasi');
            }

            return view('kelompok.calon-lokasi.edit', compact('calonLokasi'));
        } catch (\Exception $e) {
            Log::error('Error on edit page: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Update Request:', [
                'id' => $id,
                'user_id' => auth()->id()
            ]);

            $calonLokasi = CalonLokasi::find($id);
            
            if (!$calonLokasi) {
                Log::warning("Update: Calon Lokasi ID {$id} tidak ditemukan");
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Data calon lokasi tidak ditemukan');
            }
            
            if ($calonLokasi->user_id !== auth()->id()) {
                Log::warning("Update: User " . auth()->id() . " mencoba update calon lokasi milik user {$calonLokasi->user_id}");
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

            Log::info('Calon Lokasi Updated:', ['id' => $calonLokasi->id]);

            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('success', 'Calon lokasi berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error on update: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            Log::info('Show Request:', [
                'id' => $id,
                'user_id' => auth()->id()
            ]);

            // Cari calon lokasi berdasarkan ID
            $calonLokasi = CalonLokasi::find($id);
            
            // Jika tidak ditemukan
            if (!$calonLokasi) {
                Log::warning("Show: Calon Lokasi ID {$id} tidak ditemukan");
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Data calon lokasi tidak ditemukan');
            }
            
            // Jika bukan milik user yang login
            if ($calonLokasi->user_id !== auth()->id()) {
                Log::warning("Show: User " . auth()->id() . " mencoba akses calon lokasi milik user {$calonLokasi->user_id}");
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Anda tidak memiliki akses ke data ini');
            }

            return view('kelompok.calon-lokasi.show', compact('calonLokasi'));
        } catch (\Exception $e) {
            Log::error('Error on show page: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Delete Request:', [
                'id' => $id,
                'user_id' => auth()->id()
            ]);

            $calonLokasi = CalonLokasi::find($id);
            
            if (!$calonLokasi) {
                Log::warning("Delete: Calon Lokasi ID {$id} tidak ditemukan");
                return redirect()->route('kelompok.calon-lokasi.index')
                    ->with('error', 'Data calon lokasi tidak ditemukan');
            }
            
            if ($calonLokasi->user_id !== auth()->id()) {
                Log::warning("Delete: User " . auth()->id() . " mencoba hapus calon lokasi milik user {$calonLokasi->user_id}");
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

            Log::info('Calon Lokasi Deleted:', ['id' => $id]);

            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('success', 'Calon lokasi berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error on destroy: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return redirect()->route('kelompok.calon-lokasi.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}