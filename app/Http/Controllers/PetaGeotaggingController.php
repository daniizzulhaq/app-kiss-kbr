<?php

namespace App\Http\Controllers;

use App\Models\PetaGeotagging;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PetaGeotaggingController extends Controller
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

        // Ambil kelompok dari user yang login
        $kelompok = auth()->user()->kelompok;
        
        // Ambil data peta geotagging dengan pagination
        $petaGeotagging = PetaGeotagging::where('kelompok_id', $kelompok->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kelompok.peta-geotagging.index', compact('kelompok', 'petaGeotagging'));
    }

    public function create()
    {
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        $kelompok = auth()->user()->kelompok;

        return view('kelompok.peta-geotagging.create', compact('kelompok'));
    }

    public function store(Request $request)
    {
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        // Validasi
        $request->validate([
            'judul' => 'required|string|max:255',
            'files' => 'required|array|min:1|max:5',
            'files.*' => 'required|file|mimes:pdf|max:20480',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'files.required' => 'File PDF wajib diupload',
            'files.min' => 'Minimal 1 file PDF harus diupload',
            'files.max' => 'Maksimal 5 file PDF',
            'files.*.mimes' => 'File harus berupa PDF',
            'files.*.max' => 'Maksimal size per file 20MB',
        ]);

        try {
            DB::beginTransaction();

            $kelompok = auth()->user()->kelompok;
            $uploadedFiles = [];
            $totalSize = 0;

            // Validasi total size
            foreach ($request->file('files') as $file) {
                $totalSize += $file->getSize();
            }

            // Maksimal total 20MB
            if ($totalSize > 20 * 1024 * 1024) {
                return redirect()->back()
                    ->with('error', 'Total ukuran file tidak boleh melebihi 20MB')
                    ->withInput();
            }

            // Process each file
            foreach ($request->file('files') as $file) {
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('peta-geotagging', $filename, 'public');
                
                $uploadedFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }

            // Create single record with all files
            $petaGeotagging = PetaGeotagging::create([
                'kelompok_id' => $kelompok->id,
                'user_id' => auth()->id(),
                'judul' => $request->judul,
                'keterangan' => $request->keterangan,
                'files' => $uploadedFiles,
                'file_count' => count($uploadedFiles),
                'status' => 'pending',
            ]);

            DB::commit();

            $fileCount = count($uploadedFiles);
            
            // Pesan sukses berdasarkan jumlah file
            if ($fileCount === 1) {
                $message = "Peta geotagging berhasil diupload dengan 1 file PDF. Menunggu verifikasi BPDAS.";
            } else {
                $message = "Peta geotagging berhasil diupload dengan {$fileCount} file PDF. Menunggu verifikasi BPDAS.";
            }

            return redirect()->route('kelompok.peta-geotagging.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete any uploaded files if error occurs
            foreach ($uploadedFiles as $file) {
                if (isset($file['path']) && Storage::disk('public')->exists($file['path'])) {
                    Storage::disk('public')->delete($file['path']);
                }
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat upload: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(PetaGeotagging $petaGeotagging)
    {
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        // Pastikan user hanya bisa lihat peta geotagging milik kelompoknya
        if ($petaGeotagging->kelompok_id !== auth()->user()->kelompok->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        return view('kelompok.peta-geotagging.show', compact('petaGeotagging'));
    }

    public function edit(PetaGeotagging $petaGeotagging)
    {
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        // Hanya bisa edit jika status pending atau ditolak dan milik kelompoknya
        if ($petaGeotagging->kelompok_id !== auth()->user()->kelompok->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit data ini.');
        }

        if ($petaGeotagging->status === 'diterima') {
            return redirect()->route('kelompok.peta-geotagging.index')
                ->with('error', 'Tidak dapat mengubah peta geotagging yang sudah diterima.');
        }

        $kelompok = $petaGeotagging->kelompok;
        return view('kelompok.peta-geotagging.edit', compact('petaGeotagging', 'kelompok'));
    }

    public function update(Request $request, PetaGeotagging $petaGeotagging)
    {
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        if ($petaGeotagging->kelompok_id !== auth()->user()->kelompok->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate data ini.');
        }

        if ($petaGeotagging->status === 'diterima') {
            return redirect()->route('kelompok.peta-geotagging.index')
                ->with('error', 'Tidak dapat mengubah peta geotagging yang sudah diterima.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:1000',
            'files' => 'nullable|array|min:1|max:5',
            'files.*' => 'nullable|file|mimes:pdf|max:20480',
        ], [
            'files.min' => 'Minimal 1 file PDF harus diupload',
            'files.max' => 'Maksimal 5 file PDF',
            'files.*.mimes' => 'File harus berupa PDF',
            'files.*.max' => 'Maksimal size per file 20MB',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'judul' => $request->judul,
                'keterangan' => $request->keterangan,
                'status' => 'pending', // Reset ke pending saat diupdate
                'verified_at' => null,
                'verified_by' => null,
                'catatan_bpdas' => null,
            ];

            // Jika ada file baru
            if ($request->hasFile('files')) {
                $uploadedFiles = [];
                $totalSize = 0;

                // Validasi total size
                foreach ($request->file('files') as $file) {
                    $totalSize += $file->getSize();
                }

                if ($totalSize > 20 * 1024 * 1024) {
                    return redirect()->back()
                        ->with('error', 'Total ukuran file tidak boleh melebihi 20MB')
                        ->withInput();
                }

                // Hapus file lama
                $petaGeotagging->deleteFiles();

                // Upload file baru
                foreach ($request->file('files') as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('peta-geotagging', $filename, 'public');
                    
                    $uploadedFiles[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ];
                }

                $data['files'] = $uploadedFiles;
                $data['file_count'] = count($uploadedFiles);
            }

            $petaGeotagging->update($data);

            DB::commit();

            return redirect()->route('kelompok.peta-geotagging.index')
                ->with('success', 'Peta geotagging berhasil diupdate dan menunggu verifikasi ulang.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate peta geotagging: ' . $e->getMessage());
        }
    }

    public function destroy(PetaGeotagging $petaGeotagging)
    {
        // Cek kelompok
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        try {
            // Pastikan user hanya bisa menghapus data kelompoknya sendiri
            if ($petaGeotagging->kelompok_id !== auth()->user()->kelompok->id) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
            }

            $fileCount = $petaGeotagging->file_count;

            // Hapus file fisik dengan method yang aman
            $petaGeotagging->deleteFiles();

            // Hapus record dari database
            $petaGeotagging->delete();

            $fileText = $fileCount === 1 ? 'file PDF' : 'file PDF';
            return redirect()->route('kelompok.peta-geotagging.index')
                ->with('success', "Peta geotagging berhasil dihapus beserta {$fileCount} {$fileText}.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus peta geotagging: ' . $e->getMessage());
        }
    }
}