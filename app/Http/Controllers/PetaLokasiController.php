<?php

namespace App\Http\Controllers;

use App\Models\PetaLokasi;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetaLokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = auth()->user();
            
            // Ambil kelompok berdasarkan user_id
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.create')
                    ->with('error', 'Anda belum memiliki data kelompok. Silakan buat data kelompok terlebih dahulu.');
            }
            
            // Ambil semua kelompok dengan nama yang sama
            $kelompokIds = Kelompok::where('nama_kelompok', $kelompok->nama_kelompok)
                ->pluck('id')
                ->toArray();
            
            // Ambil peta lokasi dari semua kelompok dengan nama yang sama
            $petaLokasi = PetaLokasi::with('kelompok.user')
                ->whereIn('kelompok_id', $kelompokIds)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('kelompok.peta-lokasi.index', compact('petaLokasi', 'kelompok'));
        } catch (\Exception $e) {
            Log::error('Error on peta lokasi index: ' . $e->getMessage());
            return redirect()->route('kelompok.dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $user = auth()->user();
            
            // Ambil kelompok berdasarkan user_id
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.create')
                    ->with('error', 'Anda belum memiliki data kelompok. Silakan buat data kelompok terlebih dahulu.');
            }
            
            return view('kelompok.peta-lokasi.create', compact('kelompok'));
        } catch (\Exception $e) {
            Log::error('Error on peta lokasi create page: ' . $e->getMessage());
            return redirect()->route('kelompok.peta-lokasi.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Ambil kelompok berdasarkan user_id
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return back()
                    ->withInput()
                    ->with('error', 'Data kelompok tidak ditemukan. Silakan buat data kelompok terlebih dahulu.');
            }
            
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

            DB::beginTransaction();

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
            PetaLokasi::create([
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
            $message = $fileCount === 1 
                ? "Peta lokasi berhasil diupload dengan 1 file PDF. Menunggu verifikasi BPDAS."
                : "Peta lokasi berhasil diupload dengan {$fileCount} file PDF. Menunggu verifikasi BPDAS.";

            return redirect()->route('kelompok.peta-lokasi.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete any uploaded files if error occurs
            if (isset($uploadedFiles)) {
                foreach ($uploadedFiles as $file) {
                    if (isset($file['path']) && Storage::disk('public')->exists($file['path'])) {
                        Storage::disk('public')->delete($file['path']);
                    }
                }
            }

            Log::error('Error on peta lokasi store: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat upload: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $petaLokasi = PetaLokasi::find($id);
            
            if (!$petaLokasi) {
                return redirect()->route('kelompok.peta-lokasi.index')
                    ->with('error', 'Data peta lokasi tidak ditemukan.');
            }
            
            $user = auth()->user();
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.create')
                    ->with('error', 'Anda belum memiliki kelompok.');
            }
            
            // Cek apakah peta lokasi ini dari kelompok dengan nama yang sama
            $kelompokPeta = Kelompok::find($petaLokasi->kelompok_id);
            
            if (!$kelompokPeta || $kelompokPeta->nama_kelompok !== $kelompok->nama_kelompok) {
                Log::warning("User {$user->id} mencoba akses peta lokasi dari kelompok berbeda");
                return redirect()->route('kelompok.peta-lokasi.index')
                    ->with('error', 'Anda tidak memiliki akses ke data ini.');
            }

            return view('kelompok.peta-lokasi.show', compact('petaLokasi'));
        } catch (\Exception $e) {
            Log::error('Error on peta lokasi show: ' . $e->getMessage());
            return redirect()->route('kelompok.peta-lokasi.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $petaLokasi = PetaLokasi::find($id);
            
            if (!$petaLokasi) {
                return redirect()->route('kelompok.peta-lokasi.index')
                    ->with('error', 'Data peta lokasi tidak ditemukan.');
            }
            
            $user = auth()->user();
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.create')
                    ->with('error', 'Anda belum memiliki data kelompok.');
            }
            
            // Cek berdasarkan nama kelompok yang sama
            $kelompokPeta = Kelompok::find($petaLokasi->kelompok_id);
            
            // Izinkan edit jika peta lokasi dari kelompok dengan nama yang sama
            $canEdit = ($petaLokasi->kelompok_id === $kelompok->id) || 
                       ($kelompokPeta && $kelompokPeta->nama_kelompok === $kelompok->nama_kelompok);
            
            if (!$canEdit) {
                Log::warning("User {$user->id} mencoba edit peta lokasi milik kelompok lain");
                return redirect()->route('kelompok.peta-lokasi.index')
                    ->with('error', 'Anda hanya dapat mengedit data milik kelompok Anda.');
            }

            // Cek status - tidak bisa edit jika sudah diterima
            if ($petaLokasi->status === 'diterima') {
                return redirect()->route('kelompok.peta-lokasi.index')
                    ->with('error', 'Tidak dapat mengubah peta lokasi yang sudah diterima.');
            }

            return view('kelompok.peta-lokasi.edit', compact('petaLokasi', 'kelompok'));
        } catch (\Exception $e) {
            Log::error('Error on peta lokasi edit page: ' . $e->getMessage());
            return redirect()->route('kelompok.peta-lokasi.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $petaLokasi = PetaLokasi::find($id);
            
            if (!$petaLokasi) {
                return redirect()->route('kelompok.peta-lokasi.index')
                    ->with('error', 'Data peta lokasi tidak ditemukan.');
            }
            
            $user = auth()->user();
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.create')
                    ->with('error', 'Anda belum memiliki data kelompok.');
            }
            
            // Cek berdasarkan nama kelompok yang sama
            $kelompokPeta = Kelompok::find($petaLokasi->kelompok_id);
            
            $canUpdate = ($petaLokasi->kelompok_id === $kelompok->id) || 
                         ($kelompokPeta && $kelompokPeta->nama_kelompok === $kelompok->nama_kelompok);
            
            if (!$canUpdate) {
                Log::warning("User {$user->id} mencoba update peta lokasi milik kelompok lain");
                return redirect()->route('kelompok.peta-lokasi.index')
                    ->with('error', 'Anda hanya dapat mengupdate data milik kelompok Anda.');
            }

            // Cek status
            if ($petaLokasi->status === 'diterima') {
                return redirect()->route('kelompok.peta-lokasi.index')
                    ->with('error', 'Tidak dapat mengubah peta lokasi yang sudah diterima.');
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

            DB::beginTransaction();

            $data = [
                'judul' => $request->judul,
                'keterangan' => $request->keterangan,
                'status' => 'pending',
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
            Log::error('Error on peta lokasi update: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate peta lokasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $petaLokasi = PetaLokasi::find($id);
            
            if (!$petaLokasi) {
                return redirect()->route('kelompok.peta-lokasi.index')
                    ->with('error', 'Data peta lokasi tidak ditemukan.');
            }
            
            $user = auth()->user();
            $kelompok = Kelompok::where('user_id', $user->id)->first();
            
            if (!$kelompok) {
                return redirect()->route('kelompok.data-kelompok.create')
                    ->with('error', 'Anda belum memiliki data kelompok.');
            }
            
            // Cek berdasarkan nama kelompok yang sama
            $kelompokPeta = Kelompok::find($petaLokasi->kelompok_id);
            
            $canDelete = ($petaLokasi->kelompok_id === $kelompok->id) || 
                         ($kelompokPeta && $kelompokPeta->nama_kelompok === $kelompok->nama_kelompok);
            
            if (!$canDelete) {
                Log::warning("User {$user->id} mencoba hapus peta lokasi milik kelompok lain");
                return redirect()->route('kelompok.peta-lokasi.index')
                    ->with('error', 'Anda hanya dapat menghapus data milik kelompok Anda.');
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
            Log::error('Error on peta lokasi destroy: ' . $e->getMessage());
            return redirect()->route('kelompok.peta-lokasi.index')
                ->with('error', 'Terjadi kesalahan saat menghapus peta lokasi: ' . $e->getMessage());
        }
    }
}