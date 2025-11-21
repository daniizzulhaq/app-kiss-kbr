<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rencana Bibit Kelompok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #16A34A;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            color: #16A34A;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-box {
            background: #f3f4f6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #16A34A;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 8px;
            color: #666;
        }
        .summary {
            margin-top: 20px;
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-mpts { background: #dbeafe; color: #1e40af; }
        .badge-kayu { background: #fef3c7; color: #92400e; }
        .badge-buah { background: #dcfce7; color: #166534; }
        .badge-bambu { background: #f3e8ff; color: #6b21a8; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN RENCANA BIBIT KELOMPOK</h1>
        <p>Sistem Kebun Bibit Rakyat (KBR)</p>
        <p>Tanggal Cetak: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    @if(request('kelompok') || request('golongan') || request('search'))
    <div class="info-box">
        <strong>Filter yang Diterapkan:</strong>
        @if(request('kelompok'))
        <div class="info-row">
            <span class="info-label">Kelompok:</span>
            <span>{{ $kelompoks->where('id', request('kelompok'))->first()->nama_kelompok ?? '-' }}</span>
        </div>
        @endif
        @if(request('golongan'))
        <div class="info-row">
            <span class="info-label">Golongan:</span>
            <span>{{ request('golongan') }}</span>
        </div>
        @endif
        @if(request('search'))
        <div class="info-row">
            <span class="info-label">Pencarian:</span>
            <span>{{ request('search') }}</span>
        </div>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th class="text-center" width="4%">No</th>
                <th width="16%">Kelompok</th>
                <th width="14%">Pengelola</th>
                <th width="12%">Desa</th>
                <th width="18%">Jenis Bibit</th>
                <th class="text-center" width="10%">Golongan</th>
                <th class="text-right" width="10%">Jumlah (Btg)</th>
                <th class="text-center" width="10%">Tinggi (cm)</th>
                <th class="text-center" width="6%">Sertif.</th>
            </tr>
        </thead>
        <tbody>
            @php $totalBatang = 0; @endphp
            @forelse($rencanaBibits as $index => $bibit)
            @php $totalBatang += $bibit->jumlah_btg; @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $bibit->kelompok->nama_kelompok ?? '-' }}</td>
                <td>{{ $bibit->kelompok->user->name ?? '-' }}</td>
                <td>{{ $bibit->kelompok->desa ?? '-' }}</td>
                <td><strong>{{ $bibit->jenis_bibit }}</strong></td>
                <td class="text-center">
                    @if($bibit->golongan == 'MPTS')
                        <span class="badge badge-mpts">MPTS</span>
                    @elseif($bibit->golongan == 'Kayu')
                        <span class="badge badge-kayu">Kayu</span>
                    @elseif($bibit->golongan == 'Buah')
                        <span class="badge badge-buah">Buah</span>
                    @elseif($bibit->golongan == 'Bambu')
                        <span class="badge badge-bambu">Bambu</span>
                    @endif
                </td>
                <td class="text-right"><strong>{{ number_format($bibit->jumlah_btg, 0, ',', '.') }}</strong></td>
                <td class="text-center">{{ $bibit->tinggi ? number_format($bibit->tinggi, 2, ',', '.') . ' cm' : '-' }}</td>
                <td class="text-center">{{ $bibit->sertifikat ? '✓' : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">TOTAL:</th>
                <th class="text-right">{{ number_format($totalBatang, 0, ',', '.') }}</th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <div class="summary-item">Total Jenis: {{ $rencanaBibits->count() }}</div>
        <div class="summary-item">Total Batang: {{ number_format($totalBatang, 0, ',', '.') }}</div>
        <div class="summary-item">Bersertifikat: {{ $rencanaBibits->whereNotNull('sertifikat')->count() }}</div>
    </div>

    <div class="footer">
        <p>Dicetak dari Sistem KBR - {{ config('app.name') }}</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>
</html>