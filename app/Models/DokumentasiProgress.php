<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class DokumentasiProgress extends Model
{
    protected $table = 'dokumentasi_progress';

    protected $fillable = [
        'progress_fisik_id',
        'foto',
        'keterangan',
        'tanggal_foto',
    ];

    protected $casts = [
        'tanggal_foto' => 'date',
    ];

    public function progressFisik(): BelongsTo
    {
        return $this->belongsTo(ProgressFisik::class, 'progress_fisik_id');
    }

    public function getFotoUrlAttribute()
    {
        return Storage::url($this->foto);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            if ($model->foto && Storage::exists($model->foto)) {
                Storage::delete($model->foto);
            }
        });
    }
}