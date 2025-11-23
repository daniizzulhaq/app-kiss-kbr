<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggaranKelompok extends Model
{
    protected $table = 'anggaran_kelompok';

    protected $fillable = [
        'kelompok_id',
        'total_anggaran',
        'anggaran_dialokasikan',
        'realisasi_anggaran',
        'sisa_anggaran',
        'tahun',
    ];

    protected $casts = [
        'total_anggaran' => 'decimal:2',
        'anggaran_dialokasikan' => 'decimal:2',
        'realisasi_anggaran' => 'decimal:2',
        'sisa_anggaran' => 'decimal:2',
    ];

    // ============================================
    // RELASI
    // ============================================
    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }

    // ============================================
    // METHOD UPDATE REALISASI - FIXED
    // ============================================
    /**
     * Update realisasi anggaran berdasarkan progress fisik
     * 
     * LOGIKA:
     * - anggaran_dialokasikan = Total biaya dari progress PENDING + DISETUJUI
     * - realisasi_anggaran = Total biaya_realisasi dari progress DISETUJUI saja
     * - sisa_anggaran = total_anggaran - anggaran_dialokasikan
     */
    public function updateRealisasi()
    {
        // PERBAIKAN: Gunakan sum() langsung dari query, bukan dari collection
        
        // 1. Hitung total anggaran yang dialokasikan (pending + disetujui)
        $totalAlokasi = ProgressFisik::where('kelompok_id', $this->kelompok_id)
            ->whereIn('status_verifikasi', ['pending', 'disetujui'])
            ->sum('total_biaya'); // Langsung sum dari database
        
        $this->anggaran_dialokasikan = $totalAlokasi;
        
        // 2. Hitung total realisasi (hanya yang DISETUJUI)
        $totalRealisasi = ProgressFisik::where('kelompok_id', $this->kelompok_id)
            ->where('status_verifikasi', 'disetujui')
            ->sum('biaya_realisasi'); // Langsung sum dari database
        
        $this->realisasi_anggaran = $totalRealisasi;
        
        // 3. Hitung sisa anggaran
        $this->sisa_anggaran = $this->total_anggaran - $totalAlokasi;
        
        // Debug log
        \Log::info('=== UPDATE REALISASI ANGGARAN ===');
        \Log::info("Kelompok ID: {$this->kelompok_id}");
        \Log::info("Total Alokasi: {$totalAlokasi}");
        \Log::info("Total Realisasi: {$totalRealisasi}");
        \Log::info("Sisa Anggaran: {$this->sisa_anggaran}");
        
        $this->save();
        
        return $this;
    }

    // ============================================
    // ACCESSOR UNTUK PERSENTASE
    // ============================================
    
    /**
     * Hitung persentase realisasi dari yang sudah disetujui
     */
    public function getPersentaseRealisasiAttribute()
    {
        if ($this->total_anggaran == 0) return 0;
        return round(($this->realisasi_anggaran / $this->total_anggaran) * 100, 2);
    }

    /**
     * Hitung persentase alokasi (termasuk pending)
     */
    public function getPersentaseAlokasiAttribute()
    {
        if ($this->total_anggaran == 0) return 0;
        return round(($this->anggaran_dialokasikan / $this->total_anggaran) * 100, 2);
    }

    /**
     * Hitung persentase sisa anggaran
     */
    public function getPersentaseSisaAttribute()
    {
        if ($this->total_anggaran == 0) return 0;
        return round(($this->sisa_anggaran / $this->total_anggaran) * 100, 2);
    }
}