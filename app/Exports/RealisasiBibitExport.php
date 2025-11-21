<?php

namespace App\Exports;

use App\Models\RealBibit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Http\Request;

class RealisasiBibitExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $request;
    protected $rowNumber = 0;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = RealBibit::with('kelompok');

        // Apply filters
        if ($this->request->filled('kelompok_id')) {
            $query->where('id_kelompok', $this->request->kelompok_id);
        }

        if ($this->request->filled('jenis_bibit')) {
            $query->where('jenis_bibit', 'like', '%' . $this->request->jenis_bibit . '%');
        }

        if ($this->request->filled('golongan')) {
            $query->where('golongan', $this->request->golongan);
        }

        return $query->latest()->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Kelompok',
            'Desa',
            'Jenis Bibit',
            'Golongan',
            'Jumlah (Batang)',
            'Tinggi (cm)',
            'Sertifikat',
            'Tanggal Input'
        ];
    }

    /**
     * @param mixed $bibit
     * @return array
     */
    public function map($bibit): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $bibit->kelompok->nama_kelompok ?? '-',
            $bibit->kelompok->desa ?? '-',
            $bibit->jenis_bibit,
            $bibit->golongan ?? '-',
            number_format($bibit->jumlah_btg, 0, ',', '.'),
            $bibit->tinggi ? number_format($bibit->tinggi, 2, ',', '.') : '-',
            $bibit->sertifikat ? 'Bersertifikat' : 'Non-Sertifikat',
            $bibit->created_at->format('d/m/Y H:i')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '16A34A']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 30,
            'C' => 25,
            'D' => 30,
            'E' => 15,
            'F' => 18,
            'G' => 15,
            'H' => 20,
            'I' => 20,
        ];
    }
}