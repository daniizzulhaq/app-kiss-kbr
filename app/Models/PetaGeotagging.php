<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PetaGeotagging extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelompok_id',
        'user_id',
        'judul',
        'keterangan',
        'files',
        'file_count',
        'status',
        'catatan_bpdas',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'files' => 'array',
    ];

    // Relationships
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Accessor untuk badge status
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu Verifikasi</span>',
            'diterima' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Diterima</span>',
            'ditolak' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>',
            default => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>'
        };
    }

    // Helper method untuk mendapatkan file pertama
    public function getFirstFileAttribute()
    {
        return $this->files[0] ?? null;
    }

    // Helper method untuk total size
    public function getTotalSizeAttribute()
    {
        if (!is_array($this->files)) return 0;
        
        return collect($this->files)->sum('size');
    }

    // Helper method untuk format total size
    public function getFormattedTotalSizeAttribute()
    {
        $bytes = $this->total_size;
        $mb = $bytes / 1024 / 1024;
        return number_format($mb, 2) . ' MB';
    }

    // Method untuk menghapus file dengan safety check
    public function deleteFiles()
    {
        if (!is_array($this->files)) {
            return;
        }

        foreach ($this->files as $file) {
            if (isset($file['path']) && !empty($file['path'])) {
                // Cek apakah file exists sebelum menghapus
                if (Storage::disk('public')->exists($file['path'])) {
                    Storage::disk('public')->delete($file['path']);
                }
            }
        }
    }

    // Boot method untuk handle deletion
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($petaGeotagging) {
            // Hapus file fisik sebelum menghapus record
            $petaGeotagging->deleteFiles();
        });
    }
}