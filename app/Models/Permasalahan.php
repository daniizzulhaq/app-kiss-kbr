<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permasalahan extends Model
{
    protected $table = 'permasalahan';

    protected $fillable = [
        'kelompok_id',
        'kelompok',
        'sarpras',
        'bibit',
        'lokasi_tanam',
        'permasalahan',
        'prioritas',
        'status',
        'solusi',
        'ditangani_oleh',
        'ditangani_pada',
    ];

    protected $casts = [
        'ditangani_pada' => 'datetime',
    ];

    public function kelompokUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kelompok_id');
    }

    public function penangananBpdas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'diterima' => 'bg-blue-100 text-blue-800',
            'diproses' => 'bg-purple-100 text-purple-800',
            'selesai' => 'bg-green-100 text-green-800',
            'ditolak' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPrioritasBadgeClass(): string
    {
        return match($this->prioritas) {
            'rendah' => 'bg-gray-100 text-gray-800',
            'sedang' => 'bg-orange-100 text-orange-800',
            'tinggi' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'diterima' => 'Diterima',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => 'Unknown',
        };
    }
}