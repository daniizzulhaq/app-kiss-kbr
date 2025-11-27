<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgressFisik extends Model
{
    protected $table = 'progress_fisik';

    protected $fillable = [
        'kelompok_id',
        'master_kegiatan_id',
        'volume_target',
        'nama_detail',
        'biaya_satuan',
        'total_biaya',
        'volume_realisasi',
        'biaya_realisasi',
        'persentase_fisik',
        'is_selesai',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'status_verifikasi',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'volume_target' => 'decimal:2',
        'biaya_satuan' => 'decimal:2',
        'total_biaya' => 'decimal:2',
        'volume_realisasi' => 'decimal:2',
        'biaya_realisasi' => 'decimal:2',
        'persentase_fisik' => 'decimal:2',
        'is_selesai' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'verified_at' => 'datetime',
    ];

    // ============================================
    // RELASI
    // ============================================
    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function masterKegiatan(): BelongsTo
    {
        return $this->belongsTo(MasterKegiatan::class, 'master_kegiatan_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function dokumentasi(): HasMany
    {
        return $this->hasMany(DokumentasiProgress::class, 'progress_fisik_id');
    }

    // ============================================
    // OBSERVER - OTOMATIS UPDATE ANGGARAN - FIXED
    // ============================================
    protected static function boot()
    {
        parent::boot();

        // Event SAVING - Sebelum data disimpan (create & update)
        static::saving(function ($model) {
            // PENTING: Pastikan nilai disimpan ke database, bukan hanya accessor
            
            // Hitung total biaya (volume_target × biaya_satuan)
            $model->total_biaya = $model->volume_target * $model->biaya_satuan;
            
            // Hitung biaya realisasi (volume_realisasi × biaya_satuan)
            $model->biaya_realisasi = $model->volume_realisasi * $model->biaya_satuan;
            
            // Hitung persentase fisik
            if ($model->volume_target > 0) {
                $model->persentase_fisik = ($model->volume_realisasi / $model->volume_target) * 100;
            } else {
                $model->persentase_fisik = 0;
            }
            
            // Set is_selesai jika persentase 100%
            if ($model->persentase_fisik >= 100) {
                $model->is_selesai = true;
                if (!$model->tanggal_selesai) {
                    $model->tanggal_selesai = now();
                }
            } else {
                $model->is_selesai = false;
            }
            
            // Debug log
            \Log::info('=== SAVING PROGRESS FISIK ===');
            \Log::info("ID: {$model->id}");
            \Log::info("Volume Target: {$model->volume_target}");
            \Log::info("Volume Realisasi: {$model->volume_realisasi}");
            \Log::info("Biaya Satuan: {$model->biaya_satuan}");
            \Log::info("Total Biaya: {$model->total_biaya}");
            \Log::info("Biaya Realisasi: {$model->biaya_realisasi}");
            \Log::info("Persentase Fisik: {$model->persentase_fisik}%");
            \Log::info("Status: {$model->status_verifikasi}");
        });

        // Event SAVED - Setelah data berhasil disimpan (create & update)
        static::saved(function ($model) {
            // Update anggaran kelompok
            $anggaran = AnggaranKelompok::where('kelompok_id', $model->kelompok_id)
                ->where('tahun', date('Y'))
                ->first();
            
            if ($anggaran) {
                $anggaran->updateRealisasi();
                
                \Log::info('=== ANGGARAN UPDATED ===');
                \Log::info("Kelompok ID: {$model->kelompok_id}");
                \Log::info("Anggaran Dialokasikan: {$anggaran->anggaran_dialokasikan}");
                \Log::info("Realisasi Anggaran: {$anggaran->realisasi_anggaran}");
            }
        });

        // Event DELETED - Setelah data dihapus
        static::deleted(function ($model) {
            // Update anggaran kelompok (akan recalculate)
            $anggaran = AnggaranKelompok::where('kelompok_id', $model->kelompok_id)
                ->where('tahun', date('Y'))
                ->first();
            
            if ($anggaran) {
                $anggaran->updateRealisasi();
            }
        });
    }
    
    // ============================================
    // ACCESSOR - HAPUS ATAU SIMPLIFY
    // ============================================
    // CATATAN: Accessor di bawah sebenarnya tidak perlu karena sudah dihitung di boot()
    // Tapi tetap ada untuk backward compatibility
    
    public function getTotalBiayaAttribute($value)
    {
        // Langsung return value dari database
        return $value;
    }

    public function getBiayaRealisasiAttribute($value)
    {
        // Langsung return value dari database
        return $value;
    }
}