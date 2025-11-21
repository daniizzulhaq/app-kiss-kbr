<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaBibit extends Model
{
    use HasFactory;

    protected $table = 'renc_bibit';
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
        'tinggi' => 'decimal:2',
        'jumlah_btg' => 'integer',
    ];

    /**
     * Relasi ke Kelompok (tabel kelompoks dengan primary key 'id')
     */
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'id_kelompok', 'id');
    }

    /**
     * Accessor untuk format tinggi
     */
    public function getTinggiFormatAttribute()
    {
        return number_format($this->tinggi, 2) . ' cm';
    }

    /**
     * Accessor untuk format jumlah
     */
    public function getJumlahFormatAttribute()
    {
        return number_format($this->jumlah_btg, 0, ',', '.');
    }

    /**
     * Accessor untuk badge golongan
     */
    public function getGolonganBadgeAttribute()
    {
        $badges = [
            'MPTS' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">🌳 MPTS</span>',
            'Kayu' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800">🪵 Kayu</span>',
            'Buah' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">🍎 Buah</span>',
            'Bambu' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">🎋 Bambu</span>',
        ];

        return $badges[$this->golongan] ?? '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">🌱 ' . $this->golongan . '</span>';
    }

    /**
     * Accessor untuk badge sertifikat
     */
    public function getSertifikatBadgeAttribute()
    {
        if ($this->sertifikat) {
            return '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">✅ Bersertifikat</span>';
        }
        return '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">➖ Non-Sertifikat</span>';
    }

    
}