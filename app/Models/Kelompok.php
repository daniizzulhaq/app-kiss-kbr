<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks'; // Nama tabel
    // Primary key otomatis 'id'

    protected $fillable = [
        'user_id',
        'nama_kelompok',
        'blok',
        'desa',
        'kecamatan',
        'kabupaten',
        'koordinat',
        'anggota',
        'kontak',
        'spks',
        'rekening',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Rencana Bibit
     */
    public function rencanaBibits()
    {
        return $this->hasMany(RencanaBibit::class, 'id_kelompok', 'id');
    }

    /**
     * Relasi ke Calon Lokasi
     */
    public function calonLokasis()
    {
        return $this->hasMany(CalonLokasi::class, 'id_kelompok', 'id');
    }

    /**
     * Relasi ke Permasalahan
     */
    public function permasalahans()
    {
        return $this->hasMany(Permasalahan::class, 'id_kelompok', 'id');
    }
}