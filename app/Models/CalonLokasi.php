<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonLokasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_kelompok_desa',
        'kecamatan',
        'kabupaten',
        'latitude',
        'longitude',
        'koordinat_pdf_lokasi',
        'deskripsi',
        'status_verifikasi',
        'catatan_bpdas',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status_verifikasi) {
            'pending' => '<span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">⏳ Menunggu</span>',
            'diverifikasi' => '<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✅ Diverifikasi</span>',
            'ditolak' => '<span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">❌ Ditolak</span>',
            default => '<span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">-</span>',
        };
    }
}