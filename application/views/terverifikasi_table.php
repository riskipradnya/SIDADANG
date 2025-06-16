<?php // File: application/views/terverifikasi_table.php ?>

<style>
    /* ... (Semua style CSS Anda bisa tetap di sini atau dipindahkan ke file CSS utama) ... */
    /* Pastikan selector ID di CSS juga diubah jika Anda mengubah ID tabel */
    #datatableTerverifikasi thead th a {
        color: #212529 !important;
        text-decoration: none !important;
    }
</style>

<div class="card shadow-sm mt-4">
    <div class="card-header card-header-minimalist">
        Surat Terverifikasi (Siap Cetak)
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="datatableTerverifikasi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomer Surat</th>
                        <th>Nama</th>
                        <th>Keperluan</th>
                        <th>Tanggal Verifikasi</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($list_pengajuan) && !empty($list_pengajuan)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($list_pengajuan as $pengajuan): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($pengajuan->nomor_surat ?? '-'); ?></td>
                                <td>
                                    <?= htmlspecialchars($pengajuan->nama_pendatang); ?><br>
                                    <small class="text-muted">NIK: <?= htmlspecialchars($pengajuan->nik_pendatang); ?></small>
                                </td>
                                <td><?= htmlspecialchars($pengajuan->nama_keperluan); ?></td>
                                <td><?= date('d M Y, H:i', strtotime($pengajuan->tanggal_verifikasi)); ?> WITA</td>
                                <td class="text-center">
                                    <span class='status-badge status-terverifikasi'><?= htmlspecialchars($pengajuan->status); ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">

                                        <a href="<?= site_url('surat_kedatangan/hapus_pengajuan/' . $pengajuan->id); ?>" 
                                           class="btn btn-outline-danger" 
                                           title="Hapus Data" 
                                           onclick="return confirm('PERINGATAN! Anda yakin ingin MENGHAPUS data ini?');">
                                           <i class="mdi mdi-delete-outline"></i>
                                        </a>

                                        <a href="<?= site_url('surat_kedatangan/cetak_surat/' . $pengajuan->id); ?>" 
                                           class="btn btn-outline-primary" 
                                           title="Cetak Surat"
                                           target="_blank">
                                           <i class="mdi mdi-printer"></i> 
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data surat yang terverifikasi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi untuk tabel yang baru
        if (document.getElementById('datatableTerverifikasi')) {
            new simpleDatatables.DataTable("#datatableTerverifikasi");
        }
    });
</script>