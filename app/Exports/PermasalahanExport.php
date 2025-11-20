<?php

namespace App\Exports;

use App\Models\Permasalahan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PermasalahanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $filters;
    protected $rowNumber = 0;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Ambil data permasalahan
     */
    public function collection()
    {
        $query = Permasalahan::with('kelompokUser');

        // Filter berdasarkan status
        if (isset($this->filters['status']) && $this->filters['status'] != '') {
            $query->where('status', $this->filters['status']);
        }

        // Filter berdasarkan prioritas
        if (isset($this->filters['prioritas']) && $this->filters['prioritas'] != '') {
            $query->where('prioritas', $this->filters['prioritas']);
        }

        // Filter berdasarkan tanggal
        if (isset($this->filters['tanggal_dari']) && $this->filters['tanggal_dari'] != '') {
            $query->whereDate('created_at', '>=', $this->filters['tanggal_dari']);
        }
        if (isset($this->filters['tanggal_sampai']) && $this->filters['tanggal_sampai'] != '') {
            $query->whereDate('created_at', '<=', $this->filters['tanggal_sampai']);
        }

        return $query->latest()->get();
    }

    /**
     * Header kolom
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal Laporan',
            'Nama Pelapor',
            'Email Pelapor',
            'Kelompok',
            'Sarpras',
            'Bibit',
            'Lokasi Tanam',
            'Prioritas',
            'Permasalahan',
            'Solusi',
            'Status',
            'Ditangani Pada'
        ];
    }

    /**
     * Mapping data ke row
     */
    public function map($permasalahan): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $permasalahan->created_at->format('d/m/Y H:i'),
            $permasalahan->kelompokUser->name ?? '-',
            $permasalahan->kelompokUser->email ?? '-',
            $permasalahan->kelompok,
            $permasalahan->sarpras,
            $permasalahan->bibit,
            $permasalahan->lokasi_tanam,
            ucfirst($permasalahan->prioritas),
            $permasalahan->permasalahan,
            $permasalahan->solusi ?? 'Belum ada solusi',
            $permasalahan->getStatusLabel(),
            $permasalahan->ditangani_pada ? $permasalahan->ditangani_pada->format('d/m/Y H:i') : '-'
        ];
    }

    /**
     * Styling untuk Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'] // Green-600
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Auto height untuk semua baris
        foreach ($sheet->getRowIterator() as $row) {
            $sheet->getRowDimension($row->getRowIndex())->setRowHeight(-1);
        }

        // Wrap text untuk kolom permasalahan dan solusi
        $sheet->getStyle('J:K')->getAlignment()->setWrapText(true);

        // Border untuk semua data
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:M' . $highestRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        return [];
    }

    /**
     * Lebar kolom
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 18,  // Tanggal Laporan
            'C' => 20,  // Nama Pelapor
            'D' => 25,  // Email Pelapor
            'E' => 20,  // Kelompok
            'F' => 15,  // Sarpras
            'G' => 15,  // Bibit
            'H' => 20,  // Lokasi Tanam
            'I' => 12,  // Prioritas
            'J' => 40,  // Permasalahan
            'K' => 40,  // Solusi
            'L' => 15,  // Status
            'M' => 18   // Ditangani Pada
        ];
    }

    /**
     * Judul sheet
     */
    public function title(): string
    {
        return 'Laporan Permasalahan';
    }
}