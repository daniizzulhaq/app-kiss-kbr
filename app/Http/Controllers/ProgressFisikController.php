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

class ProgressFisikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelompok = auth()->user()->kelompok;
        
        // Get atau create anggaran kelompok
        $anggaran = AnggaranKelompok::firstOrCreate(
            [
                'kelompok_id' => $kelompok->id,
                'tahun' => date('Y')
            ],
            [
                'total_anggaran' => 100000000,
                'anggaran_dialokasikan' => 0,
                'realisasi_anggaran' => 0,
                'sisa_anggaran' => 100000000,
            ]
        );

        // Update realisasi
        $anggaran->updateRealisasi();
        $anggaran->refresh();

        // Get progress fisik dengan relasi
        $progressList = ProgressFisik::with(['masterKegiatan.kategori', 'dokumentasi', 'verifier'])
            ->where('kelompok_id', $kelompok->id)
            ->orderBy('master_kegiatan_id')
            ->get();

        // Hitung total persentase HANYA dari yang disetujui
        $approvedProgress = $progressList->where('status_verifikasi', 'disetujui');
        $totalProgress = $approvedProgress->count() > 0 
            ? round($approvedProgress->avg('persentase_fisik'), 2)
            : 0;

        // Group by kategori
        $progressByKategori = $progressList->groupBy(function($item) {
            return $item->masterKegiatan->kategori->nama ?? 'Tanpa Kategori';
        });

        // Get daftar kegiatan yang sudah ditambahkan (untuk ditampilkan sebagai info)
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelompok = auth()->user()->kelompok;
        
        // Get anggaran
        $anggaran = AnggaranKelompok::where('kelompok_id', $kelompok->id)
            ->where('tahun', date('Y'))
            ->first();

        if (!$anggaran) {
            return redirect()->route('kelompok.progress-fisik.index')
                ->with('error', 'Anggaran belum tersedia');
        }

        // Get kategori dengan master kegiatan
        $kategoriList = KategoriKegiatan::with('masterKegiatan')
            ->orderBy('kode')
            ->get();

        // Get kegiatan yang sudah ada - PENTING untuk mencegah duplikasi
        $existingKegiatan = ProgressFisik::where('kelompok_id', $kelompok->id)
            ->pluck('master_kegiatan_id')
            ->toArray();

        // Hitung jumlah kegiatan tersedia vs sudah ditambahkan
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
        $request->validate([
            'master_kegiatan_id' => 'required|exists:master_kegiatan,id',
            'volume_target' => 'required|numeric|min:0',
            'biaya_satuan' => 'required|numeric|min:0',
            'tanggal_mulai' => 'nullable|date',
            'keterangan' => 'nullable|string',
        ]);

        $kelompok = auth()->user()->kelompok;
        
        // VALIDASI DUPLIKASI - Cek apakah kegiatan sudah ditambahkan
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

        // Hitung total biaya
        $totalBiaya = $request->volume_target * $request->biaya_satuan;

        // Cek sisa anggaran
        $anggaran = AnggaranKelompok::where('kelompok_id', $kelompok->id)
            ->where('tahun', date('Y'))
            ->first();

        if (!$anggaran) {
            return back()->with('error', 'Anggaran belum tersedia')->withInput();
        }

        // VALIDASI: Cek apakah sisa anggaran cukup
        if ($anggaran->sisa_anggaran < $totalBiaya) {
            return back()->with('error', 
                'Anggaran tidak mencukupi! ' .
                'Total biaya kegiatan: Rp ' . number_format($totalBiaya, 0, ',', '.') . ' | ' .
                'Sisa anggaran: Rp ' . number_format($anggaran->sisa_anggaran, 0, ',', '.')
            )->withInput();
        }

        DB::beginTransaction();
        try {
            // Simpan progress
            $progress = ProgressFisik::create([
                'kelompok_id' => $kelompok->id,
                'master_kegiatan_id' => $request->master_kegiatan_id,
                'volume_target' => $request->volume_target,
                'volume_realisasi' => 0,
                'biaya_satuan' => $request->biaya_satuan,
                'tanggal_mulai' => $request->tanggal_mulai,
                'keterangan' => $request->keterangan,
                'status_verifikasi' => 'pending',
            ]);

            // Refresh anggaran
            $anggaran->refresh();

            DB::commit();

            $kegiatan = MasterKegiatan::find($request->master_kegiatan_id);
            return redirect()->route('kelompok.progress-fisik.index')
                ->with('success', 
                    'Kegiatan "' . $kegiatan->nama_kegiatan . '" berhasil ditambahkan! ' .
                    'Anggaran dialokasikan: Rp ' . number_format($totalBiaya, 0, ',', '.') . ' | ' .
                    'Sisa anggaran: Rp ' . number_format($anggaran->sisa_anggaran, 0, ',', '.')
                );

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgressFisik $progressFisik)
    {
        // Pastikan progress ini milik kelompok user atau user adalah admin
        if ($progressFisik->kelompok_id != auth()->user()->kelompok->id 
            && !auth()->user()->hasRole(['admin', 'verifikator'])) {
            abort(403);
        }

        $progressFisik->load(['masterKegiatan.kategori', 'dokumentasi', 'verifier', 'kelompok']);

        return view('kelompok.progress-fisik.show', compact('progressFisik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgressFisik $progressFisik)
    {
        // Pastikan progress ini milik kelompok user
        if ($progressFisik->kelompok_id != auth()->user()->kelompok->id) {
            abort(403);
        }

        // Tidak bisa edit jika sudah diverifikasi
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
        // Pastikan progress ini milik kelompok user
        if ($progressFisik->kelompok_id != auth()->user()->kelompok->id) {
            abort(403);
        }

        // Tidak bisa edit jika sudah diverifikasi
        if ($progressFisik->status_verifikasi == 'disetujui') {
            return redirect()->route('kelompok.progress-fisik.index')
                ->with('error', 'Tidak dapat mengubah kegiatan yang sudah diverifikasi');
        }

        $request->validate([
            'volume_target' => 'required|numeric|min:0',
            'biaya_satuan' => 'required|numeric|min:0',
            'volume_realisasi' => 'required|numeric|min:0|max:' . $request->volume_target,
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string',
            'foto.*' => 'nullable|image|max:2048',
            'keterangan_foto.*' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Hitung total biaya baru
            $totalBiayaBaru = $request->volume_target * $request->biaya_satuan;
            $totalBiayaLama = $progressFisik->total_biaya;
            $selisihBiaya = $totalBiayaBaru - $totalBiayaLama;

            // Jika ada penambahan biaya, validasi sisa anggaran
            if ($selisihBiaya > 0) {
                $anggaran = AnggaranKelompok::where('kelompok_id', $progressFisik->kelompok_id)
                    ->where('tahun', date('Y'))
                    ->first();

                if ($anggaran && $anggaran->sisa_anggaran < $selisihBiaya) {
                    return back()->with('error', 
                        'Anggaran tidak mencukupi untuk perubahan ini! ' .
                        'Tambahan biaya: Rp ' . number_format($selisihBiaya, 0, ',', '.') . ' | ' .
                        'Sisa anggaran: Rp ' . number_format($anggaran->sisa_anggaran, 0, ',', '.')
                    )->withInput();
                }
            }

            // Update progress
            $progressFisik->update([
                'volume_target' => $request->volume_target,
                'biaya_satuan' => $request->biaya_satuan,
                'volume_realisasi' => $request->volume_realisasi,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'keterangan' => $request->keterangan,
                'status_verifikasi' => 'pending', // Reset ke pending setelah update
            ]);

            // Upload foto dokumentasi
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $index => $file) {
                    $path = $file->store('dokumentasi-progress', 'public');
                    
                    DokumentasiProgress::create([
                        'progress_fisik_id' => $progressFisik->id,
                        'foto' => $path,
                        'keterangan' => $request->keterangan_foto[$index] ?? null,
                        'tanggal_foto' => now(),
                    ]);
                }
            }

            DB::commit();

            $message = 'Progress berhasil diperbarui';
            if ($selisihBiaya > 0) {
                $message .= ' dengan penambahan biaya Rp ' . number_format($selisihBiaya, 0, ',', '.');
            } elseif ($selisihBiaya < 0) {
                $message .= ' dengan pengurangan biaya Rp ' . number_format(abs($selisihBiaya), 0, ',', '.');
            }

            return redirect()->route('kelompok.progress-fisik.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgressFisik $progressFisik)
    {
        // Pastikan progress ini milik kelompok user
        if ($progressFisik->kelompok_id != auth()->user()->kelompok->id) {
            abort(403);
        }

        // Tidak bisa hapus jika sudah diverifikasi
        if ($progressFisik->status_verifikasi == 'disetujui') {
            return redirect()->route('kelompok.progress-fisik.index')
                ->with('error', 'Tidak dapat menghapus kegiatan yang sudah diverifikasi');
        }

        DB::beginTransaction();
        try {
            $totalBiaya = $progressFisik->total_biaya;
            $namaKegiatan = $progressFisik->masterKegiatan->nama_kegiatan;

            // Hapus dokumentasi foto dari storage
            foreach ($progressFisik->dokumentasi as $dok) {
                if (Storage::disk('public')->exists($dok->foto)) {
                    Storage::disk('public')->delete($dok->foto);
                }
            }

            // Hapus progress (dokumentasi akan terhapus otomatis via cascade)
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
        // Pastikan dokumentasi ini milik kelompok user
        if ($dokumentasi->progressFisik->kelompok_id != auth()->user()->kelompok->id) {
            abort(403);
        }

        try {
            // Hapus file dari storage
            if (Storage::disk('public')->exists($dokumentasi->foto)) {
                Storage::disk('public')->delete($dokumentasi->foto);
            }

            // Hapus record dari database
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
        // Pastikan user adalah admin atau verifikator
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

            // Anggaran akan terupdate otomatis via observer di model
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
        // Pastikan user adalah admin atau verifikator
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
     * Admin reset verifikasi (untuk kembalikan ke pending)
     */
    public function resetVerifikasi(ProgressFisik $progressFisik)
    {
        // Pastikan user adalah admin
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