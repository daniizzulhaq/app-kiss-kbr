<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Geotagging Lokasi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 9px;
            margin: 15px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #059669;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0 0 8px 0;
            color: #059669;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 10px;
        }
        
        .info-box {
            background-color: #f0fdf4;
            border-left: 4px solid #059669;
            padding: 10px;
            margin-bottom: 15px;
        }
        
        .info-box p {
            margin: 3px 0;
            font-size: 9px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 1px solid #ddd;
        }
        
        thead {
            background: linear-gradient(to right, #059669, #047857);
        }
        
        th {
            background-color: #059669;
            color: white;
            padding: 10px 6px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            border-right: 1px solid rgba(255,255,255,0.2);
        }
        
        th:last-child {
            border-right: none;
        }
        
        td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 8px;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        tr:hover {
            background-color: #f0fdf4;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
            border: 1px solid #FCD34D;
        }
        
        .status-diverifikasi {
            background-color: #D1FAE5;
            color: #065F46;
            border: 1px solid #6EE7B7;
        }
        
        .status-ditolak {
            background-color: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FCA5A5;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
        }
        
        .footer-stats {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .footer-stat {
            display: table-cell;
            text-align: center;
            padding: 8px;
            background-color: #f9fafb;
            border-radius: 4px;
            margin: 0 2px;
        }
        
        .footer-stat strong {
            display: block;
            font-size: 14px;
            color: #059669;
            margin-bottom: 3px;
        }
        
        .footer-stat span {
            font-size: 8px;
            color: #666;
        }
        
        .footer-info {
            text-align: right;
            font-size: 8px;
            color: #999;
            margin-top: 10px;
        }
        
        .koordinat {
            font-family: 'Courier New', monospace;
            font-size: 7px;
            color: #059669;
            background-color: #f0fdf4;
            padding: 2px 4px;
            border-radius: 2px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #9ca3af;
        }
        
        .empty-state p {
            font-size: 10px;
            margin-top: 10px;
        }
        
        strong {
            font-weight: 600;
            color: #1f2937;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🗺️ LAPORAN DATA GEOTAGGING LOKASI KELOMPOK TANI</h1>
        <p>Balai Pengelolaan Daerah Aliran Sungai (BPDAS)</p>
        <p style="margin-top: 8px; font-weight: 600;">Dicetak pada: {{ date('d F Y, H:i:s') }} WIB</p>
    </div>

    <div class="info-box">
        <p><strong>Informasi Laporan:</strong></p>
        <p>Total Data Lokasi: <strong>{{ $data->count() }}</strong> lokasi</p>
        @if(request('status'))
        <p>Filter Status: <strong>{{ strtoupper(request('status')) }}</strong></p>
        @endif
        @if(request('kabupaten'))
        <p>Filter Kabupaten: <strong>{{ request('kabupaten') }}</strong></p>
        @endif
        @if(request('kecamatan'))
        <p>Filter Kecamatan: <strong>{{ request('kecamatan') }}</strong></p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 3%; text-align: center;">No</th>
                <th style="width: 18%;">Nama Kelompok</th>
                <th style="width: 13%;">Lokasi</th>
                <th style="width: 13%;">Koordinat</th>
                <th style="width: 8%; text-align: center;">Luas</th>
                <th style="width: 10%; text-align: center;">Status</th>
                <th style="width: 12%;">Pengusul</th>
                <th style="width: 10%;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $lokasi)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                
                <td>
                    <strong>{{ $lokasi->nama_kelompok_desa }}</strong>
                    @if($lokasi->desa)
                    <br><span style="color: #666; font-size: 7px;">Desa {{ $lokasi->desa }}</span>
                    @endif
                </td>
                
                <td>
                    <strong>{{ $lokasi->kecamatan }}</strong>
                    <br><span style="color: #666;">{{ $lokasi->kabupaten }}</span>
                </td>
                
                <td>
                    @if($lokasi->center_latitude && $lokasi->center_longitude)
                        <span class="koordinat">
                            Lat: {{ number_format($lokasi->center_latitude, 6) }}<br>
                            Lng: {{ number_format($lokasi->center_longitude, 6) }}
                        </span>
                    @else
                        <span style="color: #999;">-</span>
                    @endif
                </td>
                
                <td style="text-align: center;">
                    @if($lokasi->polygon_area)
                        <strong style="color: #059669;">{{ $lokasi->formatted_area }}</strong>
                    @else
                        <span style="color: #999;">-</span>
                    @endif
                </td>
                
                
                <td style="text-align: center;">
                    <span class="status-badge status-{{ $lokasi->status_verifikasi }}">
                        {{ strtoupper($lokasi->status_verifikasi) }}
                    </span>
                </td>
                
                <td>{{ $lokasi->user->name ?? '-' }}</td>
                
                <td style="font-size: 7px;">
                    {{ $lokasi->created_at ? $lokasi->created_at->format('d/m/Y') : '-' }}
                    @if($lokasi->created_at)
                    <br><span style="color: #999;">{{ $lokasi->created_at->format('H:i') }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="empty-state">
                    <div style="font-size: 30px;">📍</div>
                    <p><strong>Tidak ada data yang tersedia</strong></p>
                    <p style="font-size: 8px;">Tidak ditemukan data lokasi sesuai filter yang diterapkan</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-stats">
            <div class="footer-stat" style="background-color: #FEF3C7;">
                <strong style="color: #92400E;">{{ $data->where('status_verifikasi', 'pending')->count() }}</strong>
                <span>Pending</span>
            </div>
            <div class="footer-stat" style="background-color: #D1FAE5; margin-left: 5px;">
                <strong style="color: #065F46;">{{ $data->where('status_verifikasi', 'diverifikasi')->count() }}</strong>
                <span>Diverifikasi</span>
            </div>
            <div class="footer-stat" style="background-color: #FEE2E2; margin-left: 5px;">
                <strong style="color: #991B1B;">{{ $data->where('status_verifikasi', 'ditolak')->count() }}</strong>
                <span>Ditolak</span>
            </div>
            <div class="footer-stat" style="margin-left: 5px;">
                <strong>{{ $data->count() }}</strong>
                <span>Total Lokasi</span>
            </div>
        </div>

        @if($lokasi->catatan_bpdas ?? false)
        <div style="margin-top: 15px; padding: 10px; background-color: #fffbeb; border-left: 3px solid #f59e0b;">
            <p style="font-size: 8px; color: #92400e;"><strong>Catatan BPDAS:</strong></p>
            <p style="font-size: 8px; color: #92400e; margin-top: 3px;">{{ $lokasi->catatan_bpdas }}</p>
        </div>
        @endif

        <div class="footer-info">
            <p><strong>Balai Pengelolaan Daerah Aliran Sungai (BPDAS)</strong></p>
            <p style="margin-top: 3px;">Dokumen ini dicetak secara otomatis dari sistem</p>
            <p style="margin-top: 2px;">Halaman 1 dari 1</p>
        </div>
    </div>
</body>
</html>