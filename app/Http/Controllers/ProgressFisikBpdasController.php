<?php

namespace App\Http\Controllers;

use App\Models\ProgressFisik;
use App\Models\Kelompok;
use App\Models\AnggaranKelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgressFisikBpdasController extends Controller
{
    /**
     * Halaman utama monitoring semua kelompok
     */
    public function index(Request $request)
    {
        $query = Kelompok::with(['anggaranKelompok' => function($q) {
            $q->where('tahun', date('Y'));
        }, 'user']);

        // Filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_kelompok', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($query) use ($request) {
                      $query->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            $query->whereHas('progressFisik', function($q) use ($request) {
                $q->where('status_verifikasi', $request->status);
            });
        }

        $kelompokList = $query->paginate(10);

        // Statistik Global
        $statistik = [
            'total_kelompok' => Kelompok::count(),
            'total_anggaran' => AnggaranKelompok::where('tahun', date('Y'))->sum('total_anggaran'),
            'total_dialokasikan' => AnggaranKelompok::where('tahun', date('Y'))->sum('anggaran_dialokasikan'),
            'realisasi_anggaran' => AnggaranKelompok::where('tahun', date('Y'))->sum('realisasi_anggaran'),
            'sisa_anggaran' => AnggaranKelompok::where('tahun', date('Y'))->sum('sisa_anggaran'),
            'progress_pending' => ProgressFisik::where('status_verifikasi', 'pending')->count(),
            'progress_disetujui' => ProgressFisik::where('status_verifikasi', 'disetujui')->count(),
            'progress_ditolak' => ProgressFisik::where('status_verifikasi', 'ditolak')->count(),
        ];

        return view('bpdas.progress-fisik.index', compact('kelompokList', 'statistik'));
    }

    /**
     * Detail progress fisik per kelompok
     */
    public function show(Kelompok $kelompok)
    {
        $anggaran = AnggaranKelompok::where('kelompok_id', $kelompok->id)
            ->where('tahun', date('Y'))
            ->first();

        $progressList = ProgressFisik::with(['masterKegiatan.kategori', 'dokumentasi', 'verifier'])
            ->where('kelompok_id', $kelompok->id)
            ->orderBy('master_kegiatan_id')
            ->get();

        // Group by kategori
        $progressByKategori = $progressList->groupBy(function($item) {
            return $item->masterKegiatan->kategori->nama ?? 'Tanpa Kategori';
        });

        // Hitung total persentase HANYA dari yang disetujui
        $approvedProgress = $progressList->where('status_verifikasi', 'disetujui');
        $totalProgress = $approvedProgress->count() > 0 
            ? round($approvedProgress->avg('persentase_fisik'), 2)
            : 0;

        // Statistik kegiatan
        $statistikKegiatan = [
            'total' => $progressList->count(),
            'selesai' => $progressList->where('is_selesai', true)->count(),
            'pending' => $progressList->where('status_verifikasi', 'pending')->count(),
            'disetujui' => $progressList->where('status_verifikasi', 'disetujui')->count(),
            'ditolak' => $progressList->where('status_verifikasi', 'ditolak')->count(),
        ];

        return view('bpdas.progress-fisik.show', compact(
            'kelompok',
            'anggaran',
            'progressList',
            'progressByKategori',
            'totalProgress',
            'statistikKegiatan'
        ));
    }

    /**
     * Verifikasi progress fisik (Approve/Reject) - DENGAN INPUT VOLUME REALISASI
     */
    public function verifikasi(Request $request, ProgressFisik $progressFisik)
    {
        // Validasi dasar
        $validation = [
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string|max:500',
        ];

        // PENTING: Jika approve, volume realisasi WAJIB diisi
        if ($request->status === 'disetujui') {
            $validation['volume_realisasi'] = [
                'required',
                'numeric',
                'min:0',
                'max:' . $progressFisik->volume_target
            ];
        } else {
            // Jika tolak, catatan wajib diisi
            $validation['catatan'] = 'required|string|max:500';
        }

        $request->validate($validation, [
            'volume_realisasi.required' => 'Volume realisasi wajib diisi saat menyetujui',
            'volume_realisasi.numeric' => 'Volume realisasi harus berupa angka',
            'volume_realisasi.min' => 'Volume realisasi harus lebih dari 0',
            'volume_realisasi.max' => 'Volume realisasi tidak boleh melebihi target (' . number_format($progressFisik->volume_target, 2) . ' ' . $progressFisik->masterKegiatan->satuan . ')',
            'catatan.required' => 'Catatan penolakan wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            // JIKA APPROVE - UPDATE VOLUME REALISASI DULU SEBELUM UPDATE STATUS
            if ($request->status === 'disetujui') {
                // Set volume realisasi
                $progressFisik->volume_realisasi = $request->volume_realisasi;
                
                // Hitung biaya realisasi
                $progressFisik->biaya_realisasi = $progressFisik->volume_realisasi * $progressFisik->biaya_satuan;
                
                // Hitung persentase fisik
                $progressFisik->persentase_fisik = $progressFisik->volume_target > 0 
                    ? ($progressFisik->volume_realisasi / $progressFisik->volume_target) * 100 
                    : 0;
                
                // Set tanggal selesai jika progress 100%
                if ($progressFisik->persentase_fisik >= 100) {
                    $progressFisik->is_selesai = true;
                    if (!$progressFisik->tanggal_selesai) {
                        $progressFisik->tanggal_selesai = now();
                    }
                }
                
                // Log untuk debugging
                \Log::info('=== BEFORE SAVE ===');
                \Log::info("Progress ID: {$progressFisik->id}");
                \Log::info("Volume Realisasi: {$progressFisik->volume_realisasi}");
                \Log::info("Biaya Satuan: {$progressFisik->biaya_satuan}");
                \Log::info("Biaya Realisasi: {$progressFisik->biaya_realisasi}");
                \Log::info("Persentase Fisik: {$progressFisik->persentase_fisik}%");
            }

            // Update status verifikasi
            $progressFisik->status_verifikasi = $request->status;
            $progressFisik->catatan_verifikasi = $request->catatan;
            $progressFisik->verified_by = auth()->id();
            $progressFisik->verified_at = now();
            
            // SAVE - ini akan trigger observer untuk update anggaran
            $progressFisik->save();

            // Refresh progress fisik untuk mendapatkan data terbaru dari database
            $progressFisik->refresh();

            // Log setelah save
            \Log::info('=== AFTER SAVE ===');
            \Log::info("Status: {$progressFisik->status_verifikasi}");
            \Log::info("Volume Realisasi (DB): {$progressFisik->volume_realisasi}");
            \Log::info("Biaya Realisasi (DB): {$progressFisik->biaya_realisasi}");
            \Log::info("Persentase Fisik (DB): {$progressFisik->persentase_fisik}%");

            // Update anggaran kelompok (sudah otomatis via observer, tapi kita refresh untuk message)
            $anggaran = AnggaranKelompok::where('kelompok_id', $progressFisik->kelompok_id)
                ->where('tahun', date('Y'))
                ->first();

            if ($anggaran) {
                $anggaran->updateRealisasi();
                $anggaran->refresh();
                
                \Log::info('=== ANGGARAN UPDATED ===');
                \Log::info("Realisasi Anggaran: {$anggaran->realisasi_anggaran}");
            }

            DB::commit();

            // Pesan sukses yang informatif
            if ($request->status == 'disetujui') {
                $message = '✅ Progress berhasil DISETUJUI! | ' .
                    'Kegiatan: ' . $progressFisik->masterKegiatan->nama_kegiatan . ' | ' .
                    'Realisasi: ' . number_format($progressFisik->volume_realisasi, 2) . ' ' . $progressFisik->masterKegiatan->satuan . ' | ' .
                    'Biaya Realisasi: Rp ' . number_format($progressFisik->biaya_realisasi, 0, ',', '.') . ' | ' .
                    'Progress: ' . number_format($progressFisik->persentase_fisik, 1) . '% | ' .
                    'Total Realisasi Kelompok: Rp ' . number_format($anggaran->realisasi_anggaran ?? 0, 0, ',', '.');
            } else {
                $message = '❌ Progress berhasil DITOLAK! | ' .
                    'Kegiatan: ' . $progressFisik->masterKegiatan->nama_kegiatan . ' | ' .
                    'Catatan: ' . ($request->catatan ?? 'Tidak ada catatan');
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('=== ERROR VERIFIKASI ===');
            \Log::error('Message: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Terjadi kesalahan saat verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Reset status verifikasi ke pending
     */
    public function resetVerifikasi(ProgressFisik $progressFisik)
    {
        DB::beginTransaction();
        try {
            $progressFisik->update([
                'status_verifikasi' => 'pending',
                'catatan_verifikasi' => null,
                'verified_by' => null,
                'verified_at' => null,
            ]);

            // Anggaran akan terupdate otomatis via observer
            DB::commit();

            return back()->with('success', 
                '🔄 Status verifikasi berhasil direset ke PENDING untuk kegiatan: ' . 
                $progressFisik->masterKegiatan->nama_kegiatan
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Halaman monitoring progress semua kelompok
     */
    public function monitoring(Request $request)
    {
        $query = Kelompok::with(['anggaranKelompok' => function($q) {
            $q->where('tahun', date('Y'));
        }, 'progressFisik.masterKegiatan', 'user']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_kelompok', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($query) use ($request) {
                      $query->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $kelompokList = $query->get()->map(function($kelompok) {
            $progressList = $kelompok->progressFisik;
            $anggaran = $kelompok->anggaranKelompok->first();
            
            // Hitung progress hanya dari yang disetujui
            $approvedProgress = $progressList->where('status_verifikasi', 'disetujui');
            
            return [
                'id' => $kelompok->id,
                'user_id' => $kelompok->user_id,
                'nama' => $kelompok->nama_kelompok,
                'ketua' => $kelompok->nama_ketua,
                'pengelola' => $kelompok->user->name ?? '-',
                'anggaran' => $anggaran,
                'total_kegiatan' => $progressList->count(),
                'kegiatan_selesai' => $progressList->where('is_selesai', true)->count(),
                'kegiatan_pending' => $progressList->where('status_verifikasi', 'pending')->count(),
                'kegiatan_disetujui' => $progressList->where('status_verifikasi', 'disetujui')->count(),
                'kegiatan_ditolak' => $progressList->where('status_verifikasi', 'ditolak')->count(),
                'progress_rata' => $approvedProgress->count() > 0 
                    ? round($approvedProgress->avg('persentase_fisik'), 2) 
                    : 0,
                'pending_verifikasi' => $progressList->where('status_verifikasi', 'pending')->count(),
            ];
        });

        return view('bpdas.progress-fisik.monitoring', compact('kelompokList'));
    }

    /**
     * Halaman khusus verifikasi (daftar progress yang pending)
     */
    public function indexVerifikasi(Request $request)
    {
        $statusFilter = $request->get('status', 'pending');

        $query = ProgressFisik::with([
            'kelompok', 
            'masterKegiatan.kategori', 
            'dokumentasi',
            'verifier'
        ]);

        // Filter berdasarkan status
        if ($statusFilter != 'semua') {
            $query->where('status_verifikasi', $statusFilter);
        }

        // Filter berdasarkan kelompok
        if ($request->filled('kelompok_id')) {
            $query->where('kelompok_id', $request->kelompok_id);
        }

        // Filter berdasarkan search
        if ($request->filled('search')) {
            $query->whereHas('masterKegiatan', function($q) use ($request) {
                $q->where('nama_kegiatan', 'like', '%' . $request->search . '%');
            });
        }

        $progressList = $query->orderBy('created_at', 'desc')->paginate(20);

        // Daftar kelompok untuk filter
        $kelompokList = Kelompok::orderBy('nama_kelompok')->get();

        // Statistik
        $statistik = [
            'pending' => ProgressFisik::where('status_verifikasi', 'pending')->count(),
            'disetujui' => ProgressFisik::where('status_verifikasi', 'disetujui')->count(),
            'ditolak' => ProgressFisik::where('status_verifikasi', 'ditolak')->count(),
            'total' => ProgressFisik::count(),
        ];

        return view('bpdas.progress-fisik.verifikasi-index', compact(
            'progressList',
            'kelompokList',
            'statistik',
            'statusFilter'
        ));
    }

    /**
     * Verifikasi massal (multiple progress sekaligus)
     */
    public function verifikasiMassal(Request $request)
    {
        $request->validate([
            'progress_ids' => 'required|array',
            'progress_ids.*' => 'exists:progress_fisik,id',
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $jumlahDiverifikasi = 0;

            foreach ($request->progress_ids as $progressId) {
                $progress = ProgressFisik::find($progressId);
                
                if ($progress && $progress->status_verifikasi == 'pending') {
                    $progress->update([
                        'status_verifikasi' => $request->status,
                        'catatan_verifikasi' => $request->catatan,
                        'verified_by' => auth()->id(),
                        'verified_at' => now(),
                    ]);
                    
                    $jumlahDiverifikasi++;
                }
            }

            DB::commit();

            $message = $request->status == 'disetujui' 
                ? "✅ Berhasil menyetujui {$jumlahDiverifikasi} progress kegiatan"
                : "❌ Berhasil menolak {$jumlahDiverifikasi} progress kegiatan";

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Monitoring anggaran semua kelompok
     */
    public function monitoringAnggaran(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $anggaranList = AnggaranKelompok::with('kelompok')
            ->where('tahun', $tahun)
            ->orderBy('kelompok_id')
            ->get();

        // Statistik global
        $statistik = [
            'total_anggaran' => $anggaranList->sum('total_anggaran'),
            'total_dialokasikan' => $anggaranList->sum('anggaran_dialokasikan'),
            'total_realisasi' => $anggaranList->sum('realisasi_anggaran'),
            'total_sisa' => $anggaranList->sum('sisa_anggaran'),
            'rata_progress_alokasi' => $anggaranList->count() > 0 
                ? round($anggaranList->avg('persentase_alokasi'), 2) 
                : 0,
            'rata_progress_realisasi' => $anggaranList->count() > 0 
                ? round($anggaranList->avg('persentase_realisasi'), 2) 
                : 0,
        ];

        // Daftar tahun untuk filter
        $tahunList = AnggaranKelompok::selectRaw('DISTINCT tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('bpdas.progress-fisik.monitoring-anggaran', compact(
            'anggaranList',
            'statistik',
            'tahun',
            'tahunList'
        ));
    }

    /**
     * Export PDF per kelompok
     */
    public function exportPdf(Kelompok $kelompok)
    {
        $anggaran = AnggaranKelompok::where('kelompok_id', $kelompok->id)
            ->where('tahun', date('Y'))
            ->first();

        $progressList = ProgressFisik::with(['masterKegiatan.kategori', 'dokumentasi', 'verifier'])
            ->where('kelompok_id', $kelompok->id)
            ->orderBy('master_kegiatan_id')
            ->get();

        $progressByKategori = $progressList->groupBy(function($item) {
            return $item->masterKegiatan->kategori->nama ?? 'Tanpa Kategori';
        });

        // Hitung progress hanya dari yang disetujui
        $approvedProgress = $progressList->where('status_verifikasi', 'disetujui');
        $totalProgress = $approvedProgress->count() > 0 
            ? round($approvedProgress->avg('persentase_fisik'), 2)
            : 0;

        $statistikKegiatan = [
            'total' => $progressList->count(),
            'selesai' => $progressList->where('is_selesai', true)->count(),
            'pending' => $progressList->where('status_verifikasi', 'pending')->count(),
            'disetujui' => $progressList->where('status_verifikasi', 'disetujui')->count(),
            'ditolak' => $progressList->where('status_verifikasi', 'ditolak')->count(),
        ];

        $pdf = Pdf::loadView('bpdas.progress-fisik.pdf', compact(
            'kelompok',
            'anggaran',
            'progressList',
            'progressByKategori',
            'totalProgress',
            'statistikKegiatan'
        ));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('laporan-progress-fisik-' . $kelompok->nama_kelompok . '-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export PDF global (semua kelompok)
     */
    public function exportPdfGlobal(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $kelompokList = Kelompok::with([
            'anggaranKelompok' => function($q) use ($tahun) {
                $q->where('tahun', $tahun);
            },
            'progressFisik.masterKegiatan'
        ])->get();

        $statistikGlobal = [
            'total_kelompok' => $kelompokList->count(),
            'total_anggaran' => AnggaranKelompok::where('tahun', $tahun)->sum('total_anggaran'),
            'total_dialokasikan' => AnggaranKelompok::where('tahun', $tahun)->sum('anggaran_dialokasikan'),
            'total_realisasi' => AnggaranKelompok::where('tahun', $tahun)->sum('realisasi_anggaran'),
            'total_sisa' => AnggaranKelompok::where('tahun', $tahun)->sum('sisa_anggaran'),
            'total_kegiatan' => ProgressFisik::whereHas('kelompok.anggaranKelompok', function($q) use ($tahun) {
                $q->where('tahun', $tahun);
            })->count(),
        ];

        $pdf = Pdf::loadView('bpdas.progress-fisik.pdf-global', compact(
            'kelompokList',
            'statistikGlobal',
            'tahun'
        ));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('laporan-progress-fisik-semua-kelompok-' . $tahun . '.pdf');
    }

    /**
     * Export Excel (Opsional)
     */
    public function exportExcel(Kelompok $kelompok)
    {
        // Implementasi export Excel menggunakan Maatwebsite/Excel
        // Akan dibuat terpisah jika diperlukan
        return back()->with('info', 'Fitur export Excel sedang dalam pengembangan');
    }

    public function exportPdfPerUser(Request $request, $userId)
    {
        $tahun = $request->get('tahun', date('Y'));
        
        $pengelola = \App\Models\User::findOrFail($userId);
        
        $kelompokList = Kelompok::with([
            'anggaranKelompok' => function($q) use ($tahun) {
                $q->where('tahun', $tahun);
            },
            'progressFisik.masterKegiatan.kategori',
            'user'
        ])
        ->where('user_id', $userId)
        ->get();

        $statistikPengelola = [
            'total_kelompok' => $kelompokList->count(),
            'total_anggaran' => $kelompokList->sum(function($k) {
                return $k->anggaranKelompok->first()->total_anggaran ?? 0;
            }),
            'total_dialokasikan' => $kelompokList->sum(function($k) {
                return $k->anggaranKelompok->first()->anggaran_dialokasikan ?? 0;
            }),
            'total_realisasi' => $kelompokList->sum(function($k) {
                return $k->anggaranKelompok->first()->realisasi_anggaran ?? 0;
            }),
            'total_sisa' => $kelompokList->sum(function($k) {
                return $k->anggaranKelompok->first()->sisa_anggaran ?? 0;
            }),
            'total_kegiatan' => $kelompokList->sum(function($k) {
                return $k->progressFisik->count();
            }),
            'kegiatan_disetujui' => $kelompokList->sum(function($k) {
                return $k->progressFisik->where('status_verifikasi', 'disetujui')->count();
            }),
            'kegiatan_pending' => $kelompokList->sum(function($k) {
                return $k->progressFisik->where('status_verifikasi', 'pending')->count();
            }),
            'kegiatan_ditolak' => $kelompokList->sum(function($k) {
                return $k->progressFisik->where('status_verifikasi', 'ditolak')->count();
            }),
        ];

        $pdf = Pdf::loadView('bpdas.progress-fisik.pdf-per-user', compact(
            'kelompokList',
            'statistikPengelola',
            'pengelola',
            'tahun'
        ));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('laporan-progress-fisik-' . \Illuminate\Support\Str::slug($pengelola->name) . '-' . $tahun . '.pdf');
    }

    /**
     * Export PDF untuk user yang sedang login
     */
    public function exportPdfMy(Request $request)
    {
        return $this->exportPdfPerUser($request, auth()->id());
    }

    
    /**
     * Dashboard ringkasan untuk BPDAS
     */
    public function dashboard()
    {
        $tahun = date('Y');

        // Statistik keseluruhan
        $statistik = [
            'total_kelompok' => Kelompok::count(),
            'kelompok_aktif' => Kelompok::whereHas('progressFisik')->distinct()->count(),
            'total_anggaran' => AnggaranKelompok::where('tahun', $tahun)->sum('total_anggaran'),
            'total_dialokasikan' => AnggaranKelompok::where('tahun', $tahun)->sum('anggaran_dialokasikan'),
            'total_realisasi' => AnggaranKelompok::where('tahun', $tahun)->sum('realisasi_anggaran'),
            'progress_pending' => ProgressFisik::where('status_verifikasi', 'pending')->count(),
            'progress_disetujui' => ProgressFisik::where('status_verifikasi', 'disetujui')->count(),
            'progress_ditolak' => ProgressFisik::where('status_verifikasi', 'ditolak')->count(),
        ];

        // Progress yang perlu perhatian (pending lama)
        $progressPerluPerhatian = ProgressFisik::with(['kelompok', 'masterKegiatan'])
            ->where('status_verifikasi', 'pending')
            ->where('created_at', '<', now()->subDays(7))
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        // Kelompok dengan progress terbaik
        $kelompokTerbaik = Kelompok::with(['anggaranKelompok' => function($q) use ($tahun) {
            $q->where('tahun', $tahun);
        }])
        ->whereHas('progressFisik', function($q) {
            $q->where('status_verifikasi', 'disetujui');
        })
        ->get()
        ->map(function($kelompok) {
            $approvedProgress = $kelompok->progressFisik->where('status_verifikasi', 'disetujui');
            return [
                'kelompok' => $kelompok,
                'progress_rata' => $approvedProgress->count() > 0 
                    ? round($approvedProgress->avg('persentase_fisik'), 2) 
                    : 0,
                'total_realisasi' => $approvedProgress->sum('biaya_realisasi'),
            ];
        })
        ->sortByDesc('progress_rata')
        ->take(5);

        return view('bpdas.progress-fisik.dashboard', compact(
            'statistik',
            'progressPerluPerhatian',
            'kelompokTerbaik'
        ));
    }
}