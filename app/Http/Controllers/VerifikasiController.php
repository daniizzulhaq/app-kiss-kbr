<?php

namespace App\Http\Controllers;

use App\Models\ProgressFisik;
use App\Models\AnggaranKelompok;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function approve(Request $request, ProgressFisik $progressFisik)
    {
        $request->validate([
            'catatan_verifikasi' => 'nullable|string',
        ]);

        $progressFisik->update([
            'status_verifikasi' => 'disetujui',
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // Update anggaran kelompok (akan otomatis via observer)
        $anggaran = AnggaranKelompok::where('kelompok_id', $progressFisik->kelompok_id)
            ->where('tahun', date('Y'))
            ->first();
        
        if ($anggaran) {
            $anggaran->updateRealisasi();
        }

        return redirect()->back()
            ->with('success', 'Progress fisik berhasil disetujui');
    }

    public function reject(Request $request, ProgressFisik $progressFisik)
    {
        $request->validate([
            'catatan_verifikasi' => 'required|string',
        ]);

        $progressFisik->update([
            'status_verifikasi' => 'ditolak',
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Progress fisik ditolak');
    }
}