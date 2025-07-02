<?php // File: application/views/laporan/v_halaman_laporan.php ?>

<div class="card shadow-sm">
    <div class="card-header card-header-minimalist">
        Pusat Laporan
    </div>
    <div class="card-body">
        <!-- <p class="card-text">Pilih jenis laporan yang ingin Anda buat dari daftar di bawah ini.</p> -->
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nama Laporan</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="align-middle">
                            <strong>Laporan Pendatang Masuk</strong>
                        </td>
                        <td class="align-middle">
                            Menampilkan daftar lengkap semua pendatang yang sudah terverifikasi.
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#laporanPendatangModal">
                                <i class="mdi mdi-settings-outline"></i> Atur & Buat Laporan
                            </button>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="laporanPendatangModal" tabindex="-1" aria-labelledby="laporanPendatangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="laporanPendatangModalLabel">Filter Laporan Pendatang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('laporan/generate_laporan_pendatang'); ?>" method="post" target="_blank">
                <div class="modal-body">
                    <p class="text-muted">Pilih filter di bawah ini. Kosongkan untuk menampilkan semua data.</p>
                    
                    <div class="mb-3">
                        <label for="bulan_tahun" class="form-label">Pilih Bulan (Opsional)</label>
                        <input type="month" class="form-control" name="bulan_tahun" id="bulan_tahun">
                        <small class="form-text">Jika dikosongkan, akan mengambil semua data.</small>
                    </div>

                    <div class="mb-3">
                        <label for="id_pj" class="form-label">Pilih Penanggung Jawab (Opsional)</label>
                        <select class="form-select" name="id_pj" id="id_pj">
                            <option value="">-- Semua PJ --</option>
                            <?php if (!empty($list_pj)): ?>
                                <?php foreach ($list_pj as $pj): ?>
                                    <option value="<?= $pj->kodeDaftar; ?>">
                                        <?= htmlspecialchars($pj->namaLengkap); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">
                        <i class="mdi mdi-file-document-outline"></i> Buat Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>