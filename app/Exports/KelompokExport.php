<?php

namespace App\Exports;

use App\Models\Kelompok;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class KelompokExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    public function collection()
    {
        return Kelompok::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kelompok',
            'Pengelola',
            'Blok',
            'Desa',
            'Kecamatan',
            'Kabupaten',
            'Koordinat',
            'Jumlah Anggota',
            'Kontak',
            'SPKS',
            'Rekening'
        ];
    }

    public function map($kelompok): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $kelompok->nama_kelompok,
            $kelompok->user->name ?? '-',
            $kelompok->blok ?? '-',
            $kelompok->desa ?? '-',
            $kelompok->kecamatan ?? '-',
            $kelompok->kabupaten ?? '-',
            $kelompok->koordinat ?? '-',
            $kelompok->anggota ?? '0',
            $kelompok->kontak ?? '-',
            $kelompok->spks ?? '-',
            $kelompok->rekening ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 25,
            'C' => 20,
            'D' => 15,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 25,
            'I' => 15,
            'J' => 20,
            'K' => 20,
            'L' => 25,
        ];
    }

    public function title(): string
    {
        return 'Data Kelompok';
    }
}