<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $table = 'kelompoks';

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
        'dokumentasi', // Tambahkan ini
    ];

    // Cast dokumentasi sebagai array
    protected $casts = [
        'dokumentasi' => 'array',
    ];

    public function user()
   {
    return $this->belongsTo(User::class, 'user_id');
}
    public function rencanaBibits()
    {
        return $this->hasMany(RencanaBibit::class, 'id_kelompok', 'id');
    }

    public function calonLokasis()
    {
        return $this->hasMany(CalonLokasi::class, 'id_kelompok', 'id');
    }

    public function permasalahans()
    {
        return $this->hasMany(Permasalahan::class, 'id_kelompok', 'id');
    }

    public function realBibits()
{
    return $this->hasMany(RealBibit::class, 'id_kelompok');
}
}