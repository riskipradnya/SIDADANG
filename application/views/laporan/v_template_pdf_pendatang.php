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
        .header h3 { margin: 0; font-size: 22px; }
        .header p { margin: 5px 0; font-size: 14px; }
        
        /* --- PERUBAHAN CSS INFO DI SINI --- */
        .info { 
            margin-bottom: 20px; 
        }
        .info table { 
            width: auto; /* Dibuat auto agar lebarnya sesuai konten */
            border-collapse: collapse; 
        }
        .info .label-col {
            width: 130px; /* Lebar tetap untuk kolom label */
        }
        .info .separator-col {
            width: 15px;  /* Lebar tetap untuk kolom titik dua */
            text-align: center;
        }
        .info td { 
            padding: 2px; /* Mengurangi padding agar lebih rapat */
        }
        /* --- SELESAI PERUBAHAN CSS --- */

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
        .signature-section { 
            margin-top: 40px;
            width: 250px;
            float: right;
            text-align: center;
        }
        .signature-section p {
            margin-bottom: 0;
        }
        .signature-space {
            height: 50px;
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
                    <td class="label-col"><strong>Periode</strong></td>
                    <td class="separator-col">:</td>
                    <td><?= !empty($filter_info['bulan_tahun']) ? date('F Y', strtotime($filter_info['bulan_tahun'])) : 'Semua Data'; ?></td>
                </tr>
                <tr>
                    <td class="label-col"><strong>Penanggung Jawab</strong></td>
                    <td class="separator-col">:</td>
                    <td><?= htmlspecialchars($filter_info['nama_pj']); ?></td>
                </tr>
                 <tr>
                    <td class="label-col"><strong>Tanggal Cetak</strong></td>
                    <td class="separator-col">:</td>
                    <td><?= date('d F Y'); ?></td>
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

        <div class="signature-section">
            <p>Jimbaran, <?= date('d F Y'); ?></p>
            <p>Petugas / Admin</p>
            <div class="signature-space"></div>
            <p><strong>(_________________________)</strong></p>
        </div>
    </div>
</body>
</html>