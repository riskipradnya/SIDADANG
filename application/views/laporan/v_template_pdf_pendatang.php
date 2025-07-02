<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pendatang</title>
    <style>
        /* CSS Wajib diletakkan di dalam tag <style> untuk Dompdf */
        body { 
            font-family: 'Helvetica', 'sans-serif'; 
            font-size: 11px; 
            color: #333; 
        }
        .container { 
            width: 100%; 
            margin: 0 auto; 
        }
        .header { 
            text-align: center; 
            border-bottom: 3px double #333; 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
        }
        .header h3 { 
            margin: 0; 
            font-size: 22px; 
        }
        .header p { 
            margin: 5px 0; 
            font-size: 14px; 
        }
        .info { 
            margin-bottom: 20px; 
        }
        .info table { 
            width: 50%; 
            border-collapse: collapse; 
        }
        .info td { 
            padding: 3px; 
        }
        .report-table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .report-table th, .report-table td { 
            border: 1px solid #888; 
            padding: 6px; 
            text-align: left; 
        }
        .report-table th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
            text-align: center;
        }
        .report-table tr:nth-child(even) { 
            background-color: #f9f9f9; 
        }
        .text-center {
            text-align: center;
        }
        .footer { 
            text-align: right; 
            margin-top: 40px; 
            font-size: 11px; 
        }
        .signature { 
            margin-top: 50px; 
            text-align: right;
            width: 250px;
            float: right;
        }
        .signature p {
            margin-bottom: 60px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3>LAPORAN DATA PENDATANG MASUK</h3>
            <p>Sistem Pendataan Pendatang (SIDADANG)</p>
        </div>

        <div class="info">
            <table>
                <tr>
                    <td><strong>Periode</strong></td>
                    <td>: <?= !empty($filter_info['bulan_tahun']) ? date('F Y', strtotime($filter_info['bulan_tahun'])) : 'Semua Data'; ?></td>
                </tr>
                <tr>
                    <td><strong>Penanggung Jawab</strong></td>
                    <td>: <?= htmlspecialchars($filter_info['nama_pj']); ?></td>
                </tr>
                 <tr>
                    <td><strong>Tanggal Cetak</strong></td>
                    <td>: <?= date('d F Y'); ?></td>
                </tr>
            </table>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 9%;">Tgl Datang</th>
                    <th style="width: 12%;">NIK</th>
                    <th>Nama Lengkap</th>
                    <th style="width: 4%;">L/P</th>
                    <th style="width: 15%;">Alamat Asal</th>
                    <th style="width: 15%;">Alamat Tujuan</th>
                    <th style="width: 12%;">Penanggung Jawab</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($laporan_data)): ?>
                    <?php $no = 1; foreach ($laporan_data as $row): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= date('d-m-Y', strtotime($row->tanggal_datang)); ?></td>
                            <td>'<?= htmlspecialchars($row->nik_pendatang); ?></td>
                            <td><?= htmlspecialchars($row->nama_pendatang); ?></td>
                            <td class="text-center"><?= substr($row->jenis_kelamin, 0, 1); ?></td>
                            <td><?= htmlspecialchars($row->alamat_asal); ?></td>
                            <td><?= htmlspecialchars($row->alamat_tujuan); ?></td>
                            <td><?= htmlspecialchars($row->nama_pj ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data yang ditemukan untuk filter yang dipilih.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="footer">
            <div class="signature">
                <p>Jimbaran, <?= date('d F Y'); ?></p>
                
                <p>(_________________________)</p>
                <strong>Petugas / Admin</strong>
            </div>
        </div>
    </div>
</body>
</html>