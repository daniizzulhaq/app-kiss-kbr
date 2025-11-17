<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

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

    protected $casts = [
        'anggota' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}