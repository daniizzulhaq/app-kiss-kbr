<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Realisasi Bibit</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #16A34A;
        }

        .header h1 {
            font-size: 18pt;
            color: #16A34A;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10pt;
            color: #666;
        }

        .info-box {
            background: #f8f9fa;
            padding: 12px;
            margin-bottom: 15px;
            border-left: 4px solid #16A34A;
            border-radius: 4px;
        }

        .info-box table {
            width: 100%;
        }

        .info-box td {
            padding: 3px 0;
            font-size: 9pt;
        }

        .info-box td:first-child {
            width: 30%;
            font-weight: bold;
            color: #555;
        }

        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .summary-card {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            text-align: center;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            margin-right: 10px;
        }

        .summary-card .label {
            font-size: 9pt;
            color: #666;
            margin-bottom: 5px;
        }

        .summary-card .value {
            font-size: 16pt;
            font-weight: bold;
            color: #16A34A;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.data-table thead {
            background: #16A34A;
            color: white;
        }

        table.data-table th {
            padding: 10px 8px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
            border: 1px solid #15803d;
        }

        table.data-table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 9pt;
        }

        table.data-table tbody tr:nth-child(even) {
            background: #f9fafb;
        }

        table.data-table tbody tr:hover {
            background: #f0fdf4;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: bold;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-green {
            background: #dcfce7;
            color: #16a34a;
        }

        .footer {
            margin-top: 25px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            font-size: 8pt;
            color: #666;
            text-align: center;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }

        /* Page break */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN REALISASI BIBIT</h1>
        <p>Sistem Kebun Bibit Rakyat (KBR)</p>
        <p style="margin-top: 5px; font-size: 9pt;">Dicetak pada: {{ $tanggalCetak }}</p>
    </div>

    <!-- Filter Information -->
    <div class="info-box">
        <table>
            <tr>
                <td>Filter Kelompok</td>
                <td>: {{ $filters['kelompok'] }}</td>
                <td>Filter Golongan</td>
                <td>: {{ $filters['golongan'] }}</td>
            </tr>
            <tr>
                <td>Filter Jenis Bibit</td>
                <td>: {{ $filters['jenis_bibit'] }}</td>
                <td>Total Data</td>
                <td>: {{ number_format($realBibits->count()) }} record</td>
            </tr>
        </table>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="label">Total Bibit</div>
            <div class="value">{{ number_format($totalBibit) }}</div>
            <div class="label" style="font-size: 8pt; margin-top: 3px;">Batang</div>
        </div>
        <div class="summary-card">
            <div class="label">Total Jenis</div>
            <div class="value">{{ number_format($totalJenis) }}</div>
            <div class="label" style="font-size: 8pt; margin-top: 3px;">Jenis Bibit</div>
        </div>
        <div class="summary-card" style="margin-right: 0;">
            <div class="label">Total Kelompok</div>
            <div class="value">{{ number_format($totalKelompok) }}</div>
            <div class="label" style="font-size: 8pt; margin-top: 3px;">Kelompok</div>
        </div>
    </div>

    <!-- Data Table -->
    <table class="data-table">
    <thead>
        <tr>
            <th width="4%" class="text-center">No</th>
            <th width="18%">Kelompok</th>
            <th width="15%">Pengelola</th>
            <th width="15%">Desa</th>
            <th width="18%">Jenis Bibit</th>
            <th width="10%">Golongan</th>
            <th width="10%" class="text-right">Jumlah (Btg)</th>
            <th width="10%" class="text-right">Tinggi (cm)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($realBibits as $index => $bibit)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $bibit->kelompok->nama_kelompok ?? '-' }}</td>
            <td>{{ $bibit->kelompok->user->name ?? '-' }}</td>
            <td>{{ $bibit->kelompok->desa ?? '-' }}</td>
            <td><strong style="color: #16a34a;">{{ $bibit->jenis_bibit }}</strong></td>
            <td>
                <span class="badge badge-blue">{{ $bibit->golongan ?? '-' }}</span>
            </td>
            <td class="text-right">
                <strong style="color: #16a34a;">{{ number_format($bibit->jumlah_btg) }}</strong>
            </td>
            <td class="text-right">{{ $bibit->tinggi ? number_format($bibit->tinggi, 2) : '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="no-data">
                🌱 Tidak ada data realisasi bibit yang sesuai dengan filter
            </td>
        </tr>
        @endforelse
    </tbody>
    @if($realBibits->count() > 0)
    <tfoot>
        <tr style="background: #f0fdf4; font-weight: bold;">
            <td colspan="6" class="text-right" style="padding-right: 15px;">TOTAL</td>
            <td class="text-right" style="color: #16a34a; font-size: 10pt;">
                {{ number_format($totalBibit) }}
            </td>
            <td></td>
        </tr>
    </tfoot>
    @endif
</table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Sistem Kebun Bibit Rakyat (KBR)</strong></p>
        <p style="margin-top: 3px;">Dokumen ini digenerate secara otomatis oleh sistem</p>
    </div>
</body>
</html>