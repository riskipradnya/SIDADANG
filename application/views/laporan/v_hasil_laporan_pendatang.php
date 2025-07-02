<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pendatang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { background-color: #fff; padding: 2rem; border-radius: .5rem; margin-top: 2rem; }
        @media print {
            .no-print { display: none; }
            body { background-color: #fff; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-4">
            <h3>Laporan Data Pendatang Masuk</h3>
            <h5>Periode: <?= !empty($filter_info['bulan_tahun']) ? date('F Y', strtotime($filter_info['bulan_tahun'])) : 'Semua Data'; ?></h5>
            <h6>Penanggung Jawab: <?= htmlspecialchars($filter_info['nama_pj']); ?></h6>
        </div>

        <button onclick="window.print()" class="btn btn-success no-print mb-3">
            <i class="mdi mdi-printer"></i> Cetak Laporan
        </button>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tgl Datang</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>L/P</th>
                    <th>Pekerjaan</th>
                    <th>Alamat Asal</th>
                    <th>Alamat Tujuan</th>
                    <th>PJ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($laporan_data)): ?>
                    <?php $no = 1; foreach ($laporan_data as $row): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= date('d-m-Y', strtotime($row->tanggal_datang)); ?></td>
                            <td>'<?= htmlspecialchars($row->nik_pendatang); ?></td>
                            <td><?= htmlspecialchars($row->nama_pendatang); ?></td>
                            <td><?= substr($row->jenis_kelamin, 0, 1); ?></td>
                            <td><?= htmlspecialchars($row->pekerjaan); ?></td>
                            <td><?= htmlspecialchars($row->alamat_asal); ?></td>
                            <td><?= htmlspecialchars($row->alamat_tujuan); ?></td>
                            <td><?= htmlspecialchars($row->nama_pj ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>