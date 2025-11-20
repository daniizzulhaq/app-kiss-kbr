<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Kelompok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #2563eb;
        }
        .header h2 {
            margin: 5px 0;
            color: #2563eb;
        }
        .header p {
            margin: 3px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #2563eb;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #1e40af;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>DATA KELOMPOK</h2>
        <p>Balai Pengelolaan Daerah Aliran Sungai</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kelompok</th>
                <th>Pengelola</th>
                <th>Blok</th>
                <th>Desa</th>
                <th>Kecamatan</th>
                <th>Kabupaten</th>
                <th>Anggota</th>
                <th>Koordinat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kelompoks as $index => $kelompok)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $kelompok->nama_kelompok }}</td>
                <td>{{ $kelompok->user->name ?? '-' }}</td>
                <td>{{ $kelompok->blok ?? '-' }}</td>
                <td>{{ $kelompok->desa ?? '-' }}</td>
                <td>{{ $kelompok->kecamatan ?? '-' }}</td>
                <td>{{ $kelompok->kabupaten ?? '-' }}</td>
                <td>{{ $kelompok->anggota ?? '0' }} orang</td>
                <td style="font-size: 8px;">{{ $kelompok->koordinat ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; color: #999;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total: {{ $kelompoks->count() }} Kelompok</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>