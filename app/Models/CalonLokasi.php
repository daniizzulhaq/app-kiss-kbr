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
        'pdf_dokumen_1',
        'pdf_dokumen_2',
        'pdf_dokumen_3',
        'pdf_dokumen_4',
        'pdf_dokumen_5',
        'polygon_coordinates',
        'center_latitude',
        'center_longitude',
        'latitude',
        'longitude',
        'koordinat_pdf_lokasi',
        'deskripsi',
        'status_verifikasi',
        'catatan_bpdas',
    ];

    protected $casts = [
        'polygon_coordinates' => 'array',
        'center_latitude' => 'float',
        'center_longitude' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status_verifikasi) {
            'pending' => '<span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">⏳ Menunggu</span>',
            'diverifikasi' => '<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✅ Diverifikasi</span>',
            'ditolak' => '<span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">❌ Ditolak</span>',
            default => '<span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">-</span>',
        };
    }

    /**
     * Calculate polygon area in hectares
     */
    public function getPolygonAreaAttribute()
    {
        try {
            $coords = $this->polygon_coordinates;

            if (!$coords || !is_array($coords) || count($coords) < 3) {
                return null;
            }

            $area = 0;
            $numPoints = count($coords);

            for ($i = 0; $i < $numPoints; $i++) {
                $j = ($i + 1) % $numPoints;
                if (!isset($coords[$i][0]) || !isset($coords[$i][1]) || 
                    !isset($coords[$j][0]) || !isset($coords[$j][1])) {
                    continue;
                }

                $lat1 = floatval($coords[$i][0]);
                $lng1 = floatval($coords[$i][1]);
                $lat2 = floatval($coords[$j][0]);
                $lng2 = floatval($coords[$j][1]);

                $area += $lng1 * $lat2 - $lng2 * $lat1;
            }

            $area = abs($area) / 2.0;

            $centerLat = floatval($this->center_latitude ?? 0);
            $latMeters = 111320; 
            $lngMeters = 111320 * cos(deg2rad($centerLat));

            $areaSquareMeters = $area * $latMeters * $lngMeters;
            $areaHectares = $areaSquareMeters / 10000;

            return round($areaHectares, 2);

        } catch (\Exception $e) {
            \Log::error('Error calculating polygon area: ' . $e->getMessage(), [
                'location_id' => $this->id,
                'location_name' => $this->nama_kelompok_desa
            ]);
            return null;
        }
    }

    /**
     * Get formatted area with unit
     */
    public function getFormattedAreaAttribute()
    {
        $area = $this->polygon_area;

        if ($area === null) return '-';

        if ($area < 1) {
            return number_format($area * 10000, 0, ',', '.') . ' m²';
        }

        return number_format($area, 2, ',', '.') . ' ha';
    }

    /**
     * Count uploaded PDFs
     */
    public function getPdfCountAttribute()
    {
        $count = 0;
        for ($i = 1; $i <= 5; $i++) {
            $fieldName = "pdf_dokumen_{$i}";
            if (!empty($this->$fieldName)) $count++;
        }
        return $count;
    }

    /**
     * Check if location has polygon
     */
    public function hasPolygon()
    {
        $coords = $this->polygon_coordinates;
        return is_array($coords) && count($coords) >= 3;
    }

    /**
     * Get polygon points count
     */
    public function getPolygonPointsCountAttribute()
    {
        $coords = $this->polygon_coordinates;
        return is_array($coords) ? count($coords) : 0;
    }
}
