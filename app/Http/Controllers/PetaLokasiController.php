<?php

namespace App\Http\Controllers;

use App\Models\PetaLokasi;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PetaLokasiController extends Controller
{
    public function index()
    {
        // Ambil kelompok dari user yang login
        $kelompok = auth()->user()->kelompok;
        
        // Ambil data peta lokasi dengan pagination
        $petaLokasi = PetaLokasi::where('kelompok_id', $kelompok->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kelompok.peta-lokasi.index', compact('kelompok', 'petaLokasi'));
    }

    public function create()
    {
        $kelompok = Kelompok::where('user_id', auth()->id())->first();
        
        if (!$kelompok) {
            return redirect()->route('kelompok.dashboard')
                ->with('error', 'Data kelompok tidak ditemukan.');
        }

        return view('kelompok.peta-lokasi.create', compact('kelompok'));
    }

    public function store(Request $request)
{
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
            $path = $file->storeAs('peta-lokasi', $filename, 'public');
            
            $uploadedFiles[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ];
        }

        // Create single record with all files
        $petaLokasi = PetaLokasi::create([
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
            $message = "Peta lokasi berhasil diupload dengan 1 file PDF. Menunggu verifikasi BPDAS.";
        } else {
            $message = "Peta lokasi berhasil diupload dengan {$fileCount} file PDF. Menunggu verifikasi BPDAS.";
        }

        return redirect()->route('kelompok.peta-lokasi.index')
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

    public function show(PetaLokasi $petaLokasi)
    {
        // Pastikan user hanya bisa lihat peta lokasi miliknya
        if ($petaLokasi->user_id !== auth()->id()) {
            abort(403);
        }

        return view('kelompok.peta-lokasi.show', compact('petaLokasi'));
    }

    public function edit(PetaLokasi $petaLokasi)
    {
        // Hanya bisa edit jika status pending atau ditolak
        if ($petaLokasi->user_id !== auth()->id() || $petaLokasi->status === 'diterima') {
            abort(403);
        }

        $kelompok = $petaLokasi->kelompok;
        return view('kelompok.peta-lokasi.edit', compact('petaLokasi', 'kelompok'));
    }

    public function update(Request $request, PetaLokasi $petaLokasi)
    {
        if ($petaLokasi->user_id !== auth()->id() || $petaLokasi->status === 'diterima') {
            abort(403);
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
                $petaLokasi->deleteFiles();

                // Upload file baru
                foreach ($request->file('files') as $file) {
                    $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('peta-lokasi', $filename, 'public');
                    
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

            $petaLokasi->update($data);

            DB::commit();

            return redirect()->route('kelompok.peta-lokasi.index')
                ->with('success', 'Peta lokasi berhasil diupdate dan menunggu verifikasi ulang.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate peta lokasi: ' . $e->getMessage());
        }
    }

    public function destroy(PetaLokasi $petaLokasi)
    {
        try {
            // Pastikan user hanya bisa menghapus data kelompoknya sendiri
            if ($petaLokasi->kelompok_id !== auth()->user()->kelompok->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
            }

            $fileCount = $petaLokasi->file_count;

            // Hapus file fisik dengan method yang aman
            $petaLokasi->deleteFiles();

            // Hapus record dari database
            $petaLokasi->delete();

            $fileText = $fileCount === 1 ? 'file PDF' : 'file PDF';
            return redirect()->route('kelompok.peta-lokasi.index')
                ->with('success', "Peta lokasi berhasil dihapus beserta {$fileCount} {$fileText}.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus peta lokasi: ' . $e->getMessage());
        }
    }
}