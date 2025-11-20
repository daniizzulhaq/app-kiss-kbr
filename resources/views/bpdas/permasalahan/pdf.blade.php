<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Permasalahan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #059669;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 20px;
            color: #059669;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 11px;
            color: #666;
        }
        
        .info-export {
            text-align: right;
            margin-bottom: 20px;
            font-size: 9px;
            color: #666;
        }
        
        .summary {
            background: #f0fdf4;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #059669;
        }
        
        .summary h3 {
            font-size: 12px;
            color: #059669;
            margin-bottom: 10px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        
        .summary-item {
            text-align: center;
        }
        
        .summary-item .label {
            font-size: 9px;
            color: #666;
            margin-bottom: 3px;
        }
        
        .summary-item .value {
            font-size: 16px;
            font-weight: bold;
            color: #059669;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table thead {
            background: #059669;
            color: white;
        }
        
        table th {
            padding: 10px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #047857;
        }
        
        table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 9px;
            vertical-align: top;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        table tbody tr:hover {
            background-color: #f0fdf4;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-diproses {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .status-selesai {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .prioritas-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8px;
            font-weight: bold;
        }
        
        .prioritas-tinggi {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .prioritas-sedang {
            background-color: #fed7aa;
            color: #9a3412;
        }
        
        .prioritas-rendah {
            background-color: #e0e7ff;
            color: #3730a3;
        }
        
        .text-truncate {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PERMASALAHAN KELOMPOK</h1>
        <p>Sistem Kebun Bibit Rakyat (KBR)</p>
    </div>

    <!-- Info Export -->
    <div class="info-export">
        <strong>Tanggal Export:</strong> {{ $tanggal_export }}<br>
        <strong>Total Data:</strong> {{ $permasalahan->count() }} laporan
    </div>

    <!-- Summary -->
    <div class="summary">
        <h3>Ringkasan</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total Laporan</div>
                <div class="value">{{ $permasalahan->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Pending</div>
                <div class="value">{{ $permasalahan->where('status', 'pending')->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Diproses</div>
                <div class="value">{{ $permasalahan->where('status', 'diproses')->count() }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Selesai</div>
                <div class="value">{{ $permasalahan->where('status', 'selesai')->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 8%;">Tanggal</th>
                <th style="width: 12%;">Pelapor</th>
                <th style="width: 10%;">Kelompok</th>
                <th style="width: 8%;">Sarpras</th>
                <th style="width: 8%;">Bibit</th>
                <th style="width: 10%;">Lokasi</th>
                <th style="width: 6%;">Prioritas</th>
                <th style="width: 20%;">Permasalahan</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 7%;">Ditangani</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permasalahan as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $item->kelompokUser->name ?? '-' }}</td>
                <td>{{ $item->kelompok }}</td>
                <td>{{ $item->sarpras }}</td>
                <td>{{ $item->bibit }}</td>
                <td>{{ $item->lokasi_tanam }}</td>
                <td style="text-align: center;">
                    <span class="prioritas-badge prioritas-{{ $item->prioritas }}">
                        {{ ucfirst($item->prioritas) }}
                    </span>
                </td>
                <td>{{ Str::limit($item->permasalahan, 100) }}</td>
                <td style="text-align: center;">
                    <span class="status-badge status-{{ $item->status }}">
                        {{ $item->getStatusLabel() }}
                    </span>
                </td>
                <td style="text-align: center;">
                    {{ $item->ditangani_pada ? $item->ditangani_pada->format('d/m/Y') : '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align: center; padding: 30px; color: #999;">
                    Tidak ada data permasalahan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Sistem Kebun Bibit Rakyat (KBR)</strong></p>
        <p>Dokumen ini digenerate secara otomatis pada {{ $tanggal_export }}</p>
    </div>
</body>
</html>
