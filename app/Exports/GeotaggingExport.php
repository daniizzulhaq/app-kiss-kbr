<?php

namespace App\Exports;

use App\Models\CalonLokasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GeotaggingExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = CalonLokasi::with('user');

        // Apply filters
        if (!empty($this->filters['status'])) {
            $query->where('status_verifikasi', $this->filters['status']);
        }

        if (!empty($this->filters['kabupaten'])) {
            $query->where('kabupaten', 'like', '%' . $this->filters['kabupaten'] . '%');
        }

        if (!empty($this->filters['kecamatan'])) {
            $query->where('kecamatan', 'like', '%' . $this->filters['kecamatan'] . '%');
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kelompok',
            'Kabupaten',
            'Kecamatan',

            'Latitude',
            'Longitude',
            'Luas Lahan (Ha)',
            'Status Verifikasi',
            'Pengusul',
            'Tanggal Pengajuan',
            'Catatan BPDAS',
        ];
    }

    public function map($lokasi): array
    {
        static $no = 0;
        $no++;

        // Ambil luas dari polygon_area atau formatted_area
        $luas = $lokasi->polygon_area ? $lokasi->formatted_area : '-';

        return [
            $no,
            $lokasi->nama_kelompok_desa ?? '-',
            $lokasi->kabupaten ?? '-',
            $lokasi->kecamatan ?? '-',
            $lokasi->center_latitude ? number_format($lokasi->center_latitude, 6) : '-',
            $lokasi->center_longitude ? number_format($lokasi->center_longitude, 6) : '-',
            $luas,
            strtoupper($lokasi->status_verifikasi ?? 'pending'),
            $lokasi->user->name ?? '-',
            $lokasi->created_at ? $lokasi->created_at->format('d/m/Y H:i') : '-',
            $lokasi->catatan_bpdas ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 20,
            'J' => 18,
            'K' => 20,
            'L' => 20,
            'M' => 35,
        ];
    }

    public function title(): string
    {
        return 'Data Geotagging';
    }
}