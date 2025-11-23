<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterKegiatan extends Model
{
    protected $table = 'master_kegiatan';

    protected $fillable = [
        'kategori_id',
        'nomor',
        'nama_kegiatan',
        'satuan',
        'is_honor',
        'urutan',
    ];

    protected $casts = [
        'is_honor' => 'boolean',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriKegiatan::class, 'kategori_id');
    }

    public function progressFisik(): HasMany
    {
        return $this->hasMany(ProgressFisik::class, 'master_kegiatan_id');
    }
}