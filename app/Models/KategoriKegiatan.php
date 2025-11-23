<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriKegiatan extends Model
{
    protected $table = 'kategori_kegiatan';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
    ];

    public function masterKegiatan(): HasMany
    {
        return $this->hasMany(MasterKegiatan::class, 'kategori_id');
    }
}