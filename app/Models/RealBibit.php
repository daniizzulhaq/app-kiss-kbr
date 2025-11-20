<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealBibit extends Model
{
    use HasFactory;

    protected $table = 'real_bibit';
    protected $primaryKey = 'id_bibit';

    protected $fillable = [
        'id_kelompok',
        'jenis_bibit',
        'golongan',
        'jumlah_btg',
        'tinggi',
        'sertifikat'
    ];

    protected $casts = [
        'jumlah_btg' => 'integer',
        'tinggi' => 'decimal:2'
    ];

    /**
     * Relasi ke Kelompok
     */
    
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id');
    }

    
}