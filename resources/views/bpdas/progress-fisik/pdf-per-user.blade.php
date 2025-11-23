<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Progress Fisik - {{ $pengelola->name }} - Tahun {{ $tahun }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2c3e50;
        }
        
        .header h1 {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .header h2 {
            font-size: 14px;
            color: #34495e;
            margin-bottom: 3px;
        }
        
        .header .pengelola-info {
            font-size: 12px;
            color: #2c3e50;
            margin-top: 8px;
            padding: 8px;
            background: #ecf0f1;
            border-radius: 5px;
            display: inline-block;
        }
        
        .header p {
            font-size: 11px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        
        .statistik-pengelola {
            background: #667eea;
            color: white;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 2px solid #5a67d8;
        }
        
        .statistik-pengelola h3 {
            font-size: 12px;
            margin-bottom: 10px;
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding-bottom: 5px;
        }
        
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        
        .stats-row {
            display: table-row;
        }
        
        .stats-cell {
            display: table-cell;
            padding: 5px;
            width: 50%;
        }
        
        .stat-item {
            padding: 6px;
            background: white;
            border-radius: 3px;
            margin-bottom: 3px;
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        .stat-label {
            font-size: 9px;
            color: #667eea;
            font-weight: normal;
        }
        
        .stat-value {
            font-size: 11px;
            color: #2c3e50;
            font-weight: bold;
        }
        
        .kelompok-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .kelompok-header {
            background: #11998e;
            color: white;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            margin-bottom: 0;
            border: 2px solid #0d8478;
            border-bottom: none;
        }
        
        .kelompok-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .kelompok-info {
            font-size: 9px;
            opacity: 0.9;
        }
        
        .kelompok-stats {
            background: #f8f9fa;
            padding: 8px;
            display: table;
            width: 100%;
        }
        
        .kelompok-stats-row {
            display: table-row;
        }
        
        .kelompok-stats-cell {
            display: table-cell;
            padding: 4px 8px;
            font-size: 9px;
            border-right: 1px solid #dee2e6;
        }
        
        .kelompok-stats-cell:last-child {
            border-right: none;
        }
        
        .stats-label-small {
            color: #6c757d;
            display: block;
            margin-bottom: 2px;
        }
        
        .stats-value-small {
            font-weight: bold;
            color: #2c3e50;
            font-size: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        table thead {
            background: #11998e;
            color: white;
        }
        
        table th {
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #0d8478;
        }
        
        table td {
            padding: 6px 5px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
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
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .progress-bar-container {
            width: 100%;
            height: 18px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-bar {
            height: 100%;
            line-height: 18px;
            color: white;
            text-align: center;
            font-size: 8px;
            font-weight: bold;
        }
        
        .progress-success {
            background: #28a745;
        }
        
        .progress-warning {
            background: #ffc107;
        }
        
        .progress-danger {
            background: #dc3545;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-style: italic;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #6c757d;
            padding: 10px 0;
            border-top: 1px solid #dee2e6;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        tr {
            page-break-inside: avoid;
        }
        
        .currency {
            font-family: 'Courier New', monospace;
        }
        
        .summary-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin-top: 10px;
            border-radius: 3px;
        }
        
        .summary-box h4 {
            font-size: 10px;
            color: #856404;
            margin-bottom: 5px;
        }
        
        .summary-box p {
            font-size: 9px;
            color: #856404;
            margin: 3px 0;
        }
    </style>
</head>
<body>
    <!-- Header Laporan -->
    <div class="header">
        <h1>LAPORAN PROGRESS FISIK KEGIATAN</h1>
        <h2>PER PENGELOLA KELOMPOK</h2>
        <div class="pengelola-info">
            <strong> Pengelola:</strong> {{ $pengelola->name }} 
            @if($pengelola->email)
                |  {{ $pengelola->email }}
            @endif
        </div>
        <p>Tahun Anggaran: {{ $tahun }} | Dicetak: {{ date('d F Y H:i') }} WIB</p>
    </div>

    <!-- Statistik Pengelola -->
    <div class="statistik-pengelola">
        <h3> RINGKASAN KELOMPOK YANG DIKELOLA</h3>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stat-item">
                        <span class="stat-label">Total Kelompok Dikelola</span><br>
                        <span class="stat-value">{{ number_format($statistikPengelola['total_kelompok']) }} Kelompok</span>
                    </div>
                </div>
                <div class="stats-cell">
                    <div class="stat-item">
                        <span class="stat-label">Total Kegiatan</span><br>
                        <span class="stat-value">{{ number_format($statistikPengelola['total_kegiatan']) }} Kegiatan</span>
                    </div>
                </div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stat-item">
                        <span class="stat-label">Total Anggaran</span><br>
                        <span class="stat-value currency">Rp {{ number_format($statistikPengelola['total_anggaran'], 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="stats-cell">
                    <div class="stat-item">
                        <span class="stat-label">Total Realisasi</span><br>
                        <span class="stat-value currency">Rp {{ number_format($statistikPengelola['total_realisasi'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="stats-row">
                <div class="stats-cell">
                    <div class="stat-item">
                        <span class="stat-label">Kegiatan Disetujui</span><br>
                        <span class="stat-value">{{ number_format($statistikPengelola['kegiatan_disetujui']) }} Kegiatan</span>
                    </div>
                </div>
                <div class="stats-cell">
                    <div class="stat-item">
                        <span class="stat-label">Kegiatan Pending</span><br>
                        <span class="stat-value">{{ number_format($statistikPengelola['kegiatan_pending']) }} Kegiatan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loop Data Per Kelompok -->
    @forelse($kelompokList as $kelompok)
        @php
            $anggaran = $kelompok->anggaranKelompok->first();
            $progressList = $kelompok->progressFisik;
            $approvedProgress = $progressList->where('status_verifikasi', 'disetujui');
            
            $totalProgress = $approvedProgress->count() > 0 
                ? round($approvedProgress->avg('persentase_fisik'), 2) 
                : 0;
            
            $progressClass = $totalProgress >= 75 ? 'progress-success' 
                : ($totalProgress >= 50 ? 'progress-warning' : 'progress-danger');
        @endphp

        <div class="kelompok-section">
            <!-- Header Kelompok -->
            <div class="kelompok-header">
                <div class="kelompok-title">
                    {{ $kelompok->kode_kelompok }} - {{ $kelompok->nama_kelompok }}
                </div>
                <div class="kelompok-info">
                    Desa: {{ $kelompok->desa ?? '-' }} | 
                    Kecamatan: {{ $kelompok->kecamatan ?? '-' }}
                </div>
            </div>

            <!-- Statistik Kelompok -->
            <div class="kelompok-stats">
                <div class="kelompok-stats-row">
                    <div class="kelompok-stats-cell">
                        <span class="stats-label-small">Anggaran</span>
                        <span class="stats-value-small currency">Rp {{ number_format($anggaran->total_anggaran ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="kelompok-stats-cell">
                        <span class="stats-label-small">Dialokasikan</span>
                        <span class="stats-value-small currency">Rp {{ number_format($anggaran->anggaran_dialokasikan ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="kelompok-stats-cell">
                        <span class="stats-label-small">Realisasi</span>
                        <span class="stats-value-small currency">Rp {{ number_format($anggaran->realisasi_anggaran ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="kelompok-stats-cell">
                        <span class="stats-label-small">Total Kegiatan</span>
                        <span class="stats-value-small">{{ $progressList->count() }} Kegiatan</span>
                    </div>
                    <div class="kelompok-stats-cell">
                        <span class="stats-label-small">Progress Rata-rata</span>
                        <span class="stats-value-small">{{ number_format($totalProgress, 1) }}%</span>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div style="padding: 8px; background: white;">
                <div class="progress-bar-container">
                    <div class="progress-bar {{ $progressClass }}" style="width: {{ $totalProgress }}%">
                        {{ number_format($totalProgress, 1) }}%
                    </div>
                </div>
            </div>

            <!-- Tabel Detail Kegiatan -->
            @if($progressList->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th width="3%" class="text-center">No</th>
                            <th width="25%">Nama Kegiatan</th>
                            <th width="10%" class="text-center">Volume Target</th>
                            <th width="10%" class="text-center">Volume Realisasi</th>
                            <th width="12%" class="text-right">Biaya Target</th>
                            <th width="12%" class="text-right">Biaya Realisasi</th>
                            <th width="10%" class="text-center">Progress</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="8%" class="text-center">Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($progressList as $index => $progress)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $progress->masterKegiatan->nama_kegiatan }}</strong>
                                    @if($progress->masterKegiatan->kategori)
                                        <br><small style="color: #6c757d;">{{ $progress->masterKegiatan->kategori->nama }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ rtrim(rtrim(number_format($progress->volume_target, 2), '0'), '.') }}<br>
                                    <small>{{ $progress->masterKegiatan->satuan }}</small>
                                </td>
                                <td class="text-center">
                                    {{ rtrim(rtrim(number_format($progress->volume_realisasi, 2), '0'), '.') }}<br>
                                    <small>{{ $progress->masterKegiatan->satuan }}</small>
                                </td>
                                <td class="text-right currency">
                                    Rp {{ number_format($progress->biaya_target, 0, ',', '.') }}
                                </td>
                                <td class="text-right currency">
                                    Rp {{ number_format($progress->biaya_realisasi, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <strong style="font-size: 10px;">{{ number_format($progress->persentase_fisik, 1) }}%</strong>
                                </td>
                                <td class="text-center">
                                    @if($progress->status_verifikasi == 'disetujui')
                                        <span class="badge badge-success">Disetujui</span>
                                    @elseif($progress->status_verifikasi == 'ditolak')
                                        <span class="badge badge-danger">Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($progress->is_selesai)
                                        <span class="badge badge-success">Ya</span>
                                    @else
                                        <span class="badge badge-secondary">Belum</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="background-color: #f8f9fa; font-weight: bold;">
                        <tr>
                            <td colspan="4" class="text-right">TOTAL:</td>
                            <td class="text-right currency">
                                Rp {{ number_format($progressList->sum('biaya_target'), 0, ',', '.') }}
                            </td>
                            <td class="text-right currency">
                                Rp {{ number_format($progressList->where('status_verifikasi', 'disetujui')->sum('biaya_realisasi'), 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                {{ number_format($totalProgress, 1) }}%
                            </td>
                            <td colspan="2" class="text-center">
                                {{ $progressList->where('is_selesai', true)->count() }} / {{ $progressList->count() }} Selesai
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Summary Box untuk Kelompok -->
                @if($progressList->where('status_verifikasi', 'pending')->count() > 0)
                    <div class="summary-box">
                        <h4>Perhatian:</h4>
                        <p>Terdapat <strong>{{ $progressList->where('status_verifikasi', 'pending')->count() }} kegiatan</strong> yang masih menunggu verifikasi dari BPDAS.</p>
                    </div>
                @endif
            @else
                <div class="no-data">
                    Belum ada kegiatan yang tercatat untuk kelompok ini.
                </div>
            @endif
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @empty
        <div class="no-data">
            <p><strong>{{ $pengelola->name }}</strong> belum mengelola kelompok apapun pada tahun {{ $tahun }}</p>
        </div>
    @endforelse

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari Sistem Informasi Progress Fisik Kegiatan</p>
        <p>© {{ date('Y') }} BPDAS - Balai Pengelolaan Daerah Aliran Sungai | Pengelola: {{ $pengelola->name }}</p>
    </div>
</body>
</html>