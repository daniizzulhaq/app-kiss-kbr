<?php

namespace App\Http\Controllers;

use App\Models\ProgressFisik;
use App\Models\MasterKegiatan;
use App\Models\KategoriKegiatan;
use App\Models\AnggaranKelompok;
use App\Models\DokumentasiProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProgressFisikController extends Controller
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        $kelompok = auth()->user()->kelompok;
        
        $anggaran = AnggaranKelompok::firstOrCreate(
            [
                'kelompok_id' => $kelompok->id,
                'tahun' => date('Y')
            ],
            [
                'total_anggaran' => 0,
                'anggaran_dialokasikan' => 0,
                'realisasi_anggaran' => 0,
                'sisa_anggaran' => 0,
            ]
        );

        if ($anggaran->total_anggaran == 0) {
            return redirect()->route('kelompok.anggaran.setup')
                ->with('info', 'Silakan input total anggaran kelompok Anda terlebih dahulu');
        }

        $anggaran->updateRealisasi();
        $anggaran->refresh();

        $progressList = ProgressFisik::with(['masterKegiatan.kategori', 'dokumentasi', 'verifier'])
            ->where('kelompok_id', $kelompok->id)
            ->orderBy('master_kegiatan_id')
            ->get();

        $approvedProgress = $progressList->where('status_verifikasi', 'disetujui');
        $totalProgress = $approvedProgress->count() > 0 
            ? round($approvedProgress->avg('persentase_fisik'), 2)
            : 0;

        $progressByKategori = $progressList->groupBy(function($item) {
            return $item->masterKegiatan->kategori->nama ?? 'Tanpa Kategori';
        });

        $addedKegiatanIds = $progressList->pluck('master_kegiatan_id')->toArray();
        $addedKegiatanList = MasterKegiatan::with('kategori')
            ->whereIn('id', $addedKegiatanIds)
            ->orderBy('kategori_id')
            ->orderBy('nomor')
            ->get()
            ->groupBy('kategori.nama');

        return view('kelompok.progress-fisik.index', compact(
            'anggaran',
            'progressList',
            'totalProgress',
            'progressByKategori',
            'addedKegiatanList'
        ));
    }

    public function setupAnggaran()
    {
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        $kelompok = auth()->user()->kelompok;
        
        $anggaran = AnggaranKelompok::firstOrCreate(
            [
                'kelompok_id' => $kelompok->id,
                'tahun' => date('Y')
            ],
            [
                'total_anggaran' => 0,
                'anggaran_dialokasikan' => 0,
                'realisasi_anggaran' => 0,
                'sisa_anggaran' => 0,
            ]
        );

        return view('kelompok.anggaran.setup', compact('anggaran'));
    }

    public function storeAnggaran(Request $request)
    {
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        $request->validate([
            'total_anggaran' => 'required|numeric|min:1000000|max:1000000000',
        ], [
            'total_anggaran.required' => 'Total anggaran harus diisi',
            'total_anggaran.numeric' => 'Total anggaran harus berupa angka',
            'total_anggaran.min' => 'Total anggaran minimal Rp 1.000.000',
            'total_anggaran.max' => 'Total anggaran maksimal Rp 1.000.000.000',
        ]);

        $kelompok = auth()->user()->kelompok;
        
        $anggaran = AnggaranKelompok::where('kelompok_id', $kelompok->id)
            ->where('tahun', date('Y'))
            ->first();

        if (!$anggaran) {
            return back()->with('error', 'Data anggaran tidak ditemukan')->withInput();
        }

        $hasProgress = ProgressFisik::where('kelompok_id', $kelompok->id)->exists();
        
        if ($hasProgress && $anggaran->total_anggaran > 0) {
            return back()->with('error', 
                'Tidak dapat mengubah total anggaran karena sudah ada kegiatan yang diajukan. ' .
                'Silakan hubungi admin jika perlu mengubah anggaran.'
            )->withInput();
        }

        if ($request->total_anggaran < $anggaran->anggaran_dialokasikan) {
            return back()->with('error', 
                'Total anggaran baru (Rp ' . number_format($request->total_anggaran, 0, ',', '.') . ') ' .
                'tidak boleh lebih kecil dari anggaran yang sudah dialokasikan ' .
                '(Rp ' . number_format($anggaran->anggaran_dialokasikan, 0, ',', '.') . ')'
            )->withInput();
        }

        DB::beginTransaction();
        try {
            $anggaranLama = $anggaran->total_anggaran;
            
            $anggaran->update([
                'total_anggaran' => $request->total_anggaran,
                'sisa_anggaran' => $request->total_anggaran - $anggaran->anggaran_dialokasikan,
            ]);

            DB::commit();

            $message = $anggaranLama == 0 
                ? 'Total anggaran berhasil disimpan: Rp ' . number_format($request->total_anggaran, 0, ',', '.')
                : 'Total anggaran berhasil diperbarui dari Rp ' . number_format($anggaranLama, 0, ',', '.') . 
                  ' menjadi Rp ' . number_format($request->total_anggaran, 0, ',', '.');

            return redirect()->route('kelompok.progress-fisik.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        $kelompok = auth()->user()->kelompok;
        
        $anggaran = AnggaranKelompok::where('kelompok_id', $kelompok->id)
            ->where('tahun', date('Y'))
            ->first();

        if (!$anggaran || $anggaran->total_anggaran == 0) {
            return redirect()->route('kelompok.anggaran.setup')
                ->with('error', 'Silakan input total anggaran terlebih dahulu');
        }

        $kategoriList = KategoriKegiatan::with('masterKegiatan')
            ->orderBy('kode')
            ->get();

        $existingKegiatan = ProgressFisik::where('kelompok_id', $kelompok->id)
            ->pluck('master_kegiatan_id')
            ->toArray();

        $totalKegiatan = MasterKegiatan::count();
        $totalTambahkan = count($existingKegiatan);
        $sisaKegiatan = $totalKegiatan - $totalTambahkan;

        return view('kelompok.progress-fisik.create', compact(
            'kategoriList',
            'anggaran',
            'existingKegiatan',
            'totalKegiatan',
            'totalTambahkan',
            'sisaKegiatan'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        $request->validate([
            'master_kegiatan_id' => 'required|exists:master_kegiatan,id',
            'volume_target' => 'required|numeric|min:0',
            'nama_detail' => 'nullable|string|max:255',
            'biaya_satuan' => 'required|numeric|min:0',
            'tanggal_mulai' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|array',
            'foto.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'keterangan_foto' => 'nullable|array',
            'keterangan_foto.*' => 'nullable|string|max:255',
        ], [
            'foto.*.image' => 'File harus berupa gambar',
            'foto.*.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
            'foto.*.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        $kelompok = auth()->user()->kelompok;
        
        $exists = ProgressFisik::where('kelompok_id', $kelompok->id)
            ->where('master_kegiatan_id', $request->master_kegiatan_id)
            ->exists();

        if ($exists) {
            $kegiatan = MasterKegiatan::find($request->master_kegiatan_id);
            return back()->with('error', 
                'Kegiatan "' . $kegiatan->nama_kegiatan . '" sudah ditambahkan sebelumnya. ' .
                'Silakan pilih kegiatan lain atau edit kegiatan yang sudah ada.'
            )->withInput();
        }

        $totalBiaya = $request->volume_target * $request->biaya_satuan;

        $anggaran = AnggaranKelompok::where('kelompok_id', $kelompok->id)
            ->where('tahun', date('Y'))
            ->first();

        if (!$anggaran) {
            return back()->with('error', 'Anggaran belum tersedia')->withInput();
        }

        if ($anggaran->sisa_anggaran < $totalBiaya) {
            return back()->with('error', 
                'Anggaran tidak mencukupi! ' .
                'Total biaya kegiatan: Rp ' . number_format($totalBiaya, 0, ',', '.') . ' | ' .
                'Sisa anggaran: Rp ' . number_format($anggaran->sisa_anggaran, 0, ',', '.')
            )->withInput();
        }

        DB::beginTransaction();
        try {
            $progress = ProgressFisik::create([
                'kelompok_id' => $kelompok->id,
                'master_kegiatan_id' => $request->master_kegiatan_id,
                'volume_target' => $request->volume_target,
                'nama_detail' => $request->nama_detail,
                'volume_realisasi' => 0,
                'biaya_satuan' => $request->biaya_satuan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'keterangan' => $request->keterangan,
                'status_verifikasi' => 'pending',
            ]);

            $jumlahFoto = 0;
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $index => $file) {
                    if ($file && $file->isValid()) {
                        try {
                            $filename = 'dok_' . $progress->id . '_' . time() . '_' . $index . '.' . $file->extension();
                            $path = $file->storeAs('dokumentasi-progress', $filename, 'public');
                            
                            DokumentasiProgress::create([
                                'progress_fisik_id' => $progress->id,
                                'foto' => $path,
                                'keterangan' => $request->keterangan_foto[$index] ?? null,
                                'tanggal_foto' => now(),
                            ]);
                            
                            $jumlahFoto++;
                        } catch (\Exception $e) {
                            Log::error('Error upload foto: ' . $e->getMessage());
                            continue;
                        }
                    }
                }
            }

            $anggaran->refresh();

            DB::commit();

            $kegiatan = MasterKegiatan::find($request->master_kegiatan_id);
            
            $message = 'Kegiatan "' . $kegiatan->nama_kegiatan . '" berhasil ditambahkan! ';
            
            if ($jumlahFoto > 0) {
                $message .= $jumlahFoto . ' foto dokumentasi berhasil diupload. ';
            }
            
            $message .= 'Anggaran dialokasikan: Rp ' . number_format($totalBiaya, 0, ',', '.') . ' | ' .
                        'Sisa anggaran: Rp ' . number_format($anggaran->sisa_anggaran, 0, ',', '.');

            return redirect()->route('kelompok.progress-fisik.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan progress fisik: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgressFisik $progressFisik)
    {
        $user = auth()->user();
        
        if (!$user->kelompok) {
            return redirect()->route('kelompok.data-kelompok.create')
                ->with('warning', 'Anda belum tergabung dalam kelompok. Silakan buat atau bergabung dengan kelompok terlebih dahulu.');
        }
        
        if ($progressFisik->kelompok_id != $user->kelompok->id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat data ini.');
        }

        $progressFisik->load([
            'masterKegiatan.kategori', 
            'dokumentasi', 
            'verifier', 
            'kelompok'
        ]);

        return view('kelompok.progress-fisik.show', compact('progressFisik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgressFisik $progressFisik)
    {
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        if ($progressFisik->kelompok_id != auth()->user()->kelompok->id) {
            abort(403);
        }

        if ($progressFisik->status_verifikasi == 'disetujui') {
            return redirect()->route('kelompok.progress-fisik.index')
                ->with('error', 'Tidak dapat mengubah kegiatan yang sudah diverifikasi');
        }

        $anggaran = AnggaranKelompok::where('kelompok_id', $progressFisik->kelompok_id)
            ->where('tahun', date('Y'))
            ->first();

        return view('kelompok.progress-fisik.edit', compact('progressFisik', 'anggaran'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, ProgressFisik $progressFisik)
{
    // Log untuk debugging
    Log::info('Update Progress - Request Data:', [
        'has_foto' => $request->hasFile('foto'),
        'foto_count' => $request->hasFile('foto') ? count($request->file('foto')) : 0,
        'all_data' => $request->except(['foto'])
    ]);

    $checkResult = $this->checkKelompok();
    if ($checkResult) return $checkResult;

    if ($progressFisik->kelompok_id != auth()->user()->kelompok->id) {
        abort(403);
    }

    if ($progressFisik->status_verifikasi == 'disetujui') {
        return redirect()->route('kelompok.progress-fisik.index')
            ->with('error', 'Tidak dapat mengubah kegiatan yang sudah diverifikasi');
    }

    // Validasi dasar tanpa foto
    $rules = [
        'nama_detail' => 'nullable|string|max:255',
        'volume_target' => 'required|numeric|min:0',
        'biaya_satuan' => 'required|numeric|min:0',
        'volume_realisasi' => 'required|numeric|min:0|max:' . ($request->volume_target ?: 0),
        'tanggal_mulai' => 'nullable|date',
        'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        'keterangan' => 'nullable|string',
    ];

    // Validasi foto HANYA jika ada file
    if ($request->hasFile('foto') && is_array($request->file('foto')) && count($request->file('foto')) > 0) {
        $rules['foto'] = 'array|max:10'; // Maksimal 10 foto
        $rules['foto.*'] = 'nullable|image|mimes:jpeg,jpg,png|max:2048';
        $rules['keterangan_foto'] = 'nullable|array';
        $rules['keterangan_foto.*'] = 'nullable|string|max:255';
    }

    try {
        $validated = $request->validate($rules, [
            'foto.*.image' => 'File harus berupa gambar',
            'foto.*.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
            'foto.*.max' => 'Ukuran gambar maksimal 2MB',
            'volume_realisasi.max' => 'Volume realisasi tidak boleh melebihi volume target',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed:', $e->errors());
        throw $e;
    }

    DB::beginTransaction();
    try {
        $totalBiayaBaru = $request->volume_target * $request->biaya_satuan;
        $totalBiayaLama = $progressFisik->total_biaya;
        $selisihBiaya = $totalBiayaBaru - $totalBiayaLama;

        if ($selisihBiaya > 0) {
            $anggaran = AnggaranKelompok::where('kelompok_id', $progressFisik->kelompok_id)
                ->where('tahun', date('Y'))
                ->first();

            if ($anggaran && $anggaran->sisa_anggaran < $selisihBiaya) {
                DB::rollBack();
                return back()->with('error', 
                    'Anggaran tidak mencukupi! Tambahan: Rp ' . number_format($selisihBiaya, 0, ',', '.') . 
                    ' | Sisa: Rp ' . number_format($anggaran->sisa_anggaran, 0, ',', '.')
                )->withInput();
            }
        }

        // Update data progress
        $updateData = [
            'nama_detail' => $request->nama_detail,
            'volume_target' => $request->volume_target,
            'biaya_satuan' => $request->biaya_satuan,
            'volume_realisasi' => $request->volume_realisasi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'status_verifikasi' => 'pending',
        ];

        Log::info('Updating progress with data:', $updateData);
        $progressFisik->update($updateData);

        // Upload foto baru HANYA jika benar-benar ada
        $jumlahFotoBaru = 0;
        if ($request->hasFile('foto')) {
            $files = $request->file('foto');
            
            Log::info('Processing ' . count($files) . ' foto files');
            
            foreach ($files as $index => $file) {
                if (!$file || !$file->isValid()) {
                    Log::warning('Skipping invalid file at index ' . $index);
                    continue;
                }
                
                try {
                    $filename = 'dok_' . $progressFisik->id . '_' . time() . '_' . uniqid() . '.' . $file->extension();
                    $path = $file->storeAs('dokumentasi-progress', $filename, 'public');
                    
                    $keteranganFoto = null;
                    if ($request->has('keterangan_foto') && isset($request->keterangan_foto[$index])) {
                        $keteranganFoto = $request->keterangan_foto[$index];
                    }
                    
                    DokumentasiProgress::create([
                        'progress_fisik_id' => $progressFisik->id,
                        'foto' => $path,
                        'keterangan' => $keteranganFoto,
                        'tanggal_foto' => now(),
                    ]);
                    
                    $jumlahFotoBaru++;
                    Log::info('Successfully uploaded foto: ' . $filename);
                    
                } catch (\Exception $e) {
                    Log::error('Error upload foto at index ' . $index . ': ' . $e->getMessage());
                    continue;
                }
            }
        }

        DB::commit();

        $message = 'Progress berhasil diperbarui';
        
        if ($selisihBiaya > 0) {
            $message .= ' dengan penambahan biaya Rp ' . number_format($selisihBiaya, 0, ',', '.') . '.';
        } elseif ($selisihBiaya < 0) {
            $message .= ' dengan pengurangan biaya Rp ' . number_format(abs($selisihBiaya), 0, ',', '.') . '.';
        } else {
            $message .= '.';
        }

        if ($jumlahFotoBaru > 0) {
            $message .= ' ' . $jumlahFotoBaru . ' foto baru ditambahkan.';
        }

        Log::info('Update success: ' . $message);

        return redirect()->route('kelompok.progress-fisik.index')
            ->with('success', $message);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error update progress fisik: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    }
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgressFisik $progressFisik)
    {
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        if ($progressFisik->kelompok_id != auth()->user()->kelompok->id) {
            abort(403);
        }

        if ($progressFisik->status_verifikasi == 'disetujui') {
            return redirect()->route('kelompok.progress-fisik.index')
                ->with('error', 'Tidak dapat menghapus kegiatan yang sudah diverifikasi');
        }

        DB::beginTransaction();
        try {
            $totalBiaya = $progressFisik->total_biaya;
            $namaKegiatan = $progressFisik->masterKegiatan->nama_kegiatan;

            foreach ($progressFisik->dokumentasi as $dok) {
                if (Storage::disk('public')->exists($dok->foto)) {
                    Storage::disk('public')->delete($dok->foto);
                }
            }

            $progressFisik->delete();

            DB::commit();

            return redirect()->route('kelompok.progress-fisik.index')
                ->with('success', 
                    'Kegiatan "' . $namaKegiatan . '" berhasil dihapus. ' .
                    'Anggaran sebesar Rp ' . number_format($totalBiaya, 0, ',', '.') . ' dikembalikan.'
                );

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kelompok.progress-fisik.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete foto dokumentasi
     */
    public function deleteFoto(DokumentasiProgress $dokumentasi)
    {
        $checkResult = $this->checkKelompok();
        if ($checkResult) return $checkResult;

        if ($dokumentasi->progressFisik->kelompok_id != auth()->user()->kelompok->id) {
            abort(403);
        }

        try {
            if (Storage::disk('public')->exists($dokumentasi->foto)) {
                Storage::disk('public')->delete($dokumentasi->foto);
            }

            $dokumentasi->delete();

            return back()->with('success', 'Foto berhasil dihapus');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Admin menyetujui progress fisik
     */
    public function approve(Request $request, ProgressFisik $progressFisik)
    {
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('verifikator')) {
            abort(403, 'Anda tidak memiliki akses untuk menyetujui progress');
        }

        $request->validate([
            'catatan_verifikasi' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $progressFisik->update([
                'status_verifikasi' => 'disetujui',
                'catatan_verifikasi' => $request->catatan_verifikasi,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $anggaran = AnggaranKelompok::where('kelompok_id', $progressFisik->kelompok_id)
                ->where('tahun', date('Y'))
                ->first();

            DB::commit();

            return redirect()->back()->with('success', 
                'Progress berhasil disetujui! ' .
                'Realisasi kegiatan: Rp ' . number_format($progressFisik->biaya_realisasi, 0, ',', '.') . ' | ' .
                'Total realisasi anggaran: Rp ' . number_format($anggaran->realisasi_anggaran, 0, ',', '.')
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Admin menolak progress fisik
     */
    public function reject(Request $request, ProgressFisik $progressFisik)
    {
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('verifikator')) {
            abort(403, 'Anda tidak memiliki akses untuk menolak progress');
        }

        $request->validate([
            'catatan_verifikasi' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $progressFisik->update([
                'status_verifikasi' => 'ditolak',
                'catatan_verifikasi' => $request->catatan_verifikasi,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 
                'Progress telah ditolak. Catatan: ' . $request->catatan_verifikasi
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Admin reset verifikasi
     */
    public function resetVerifikasi(ProgressFisik $progressFisik)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Hanya admin yang dapat mereset verifikasi');
        }

        DB::beginTransaction();
        try {
            $progressFisik->update([
                'status_verifikasi' => 'pending',
                'catatan_verifikasi' => null,
                'verified_by' => null,
                'verified_at' => null,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Status verifikasi berhasil direset ke pending');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}