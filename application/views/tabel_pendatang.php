<div class="card shadow-sm mt-4">
    <div class="card-header card-header-minimalist">
        Tabel Pengajuan Surat Pengantar
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="datatablePengajuan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomer Surat</th>
                        <th>Nama</th>
                        <th>Keperluan</th>
                        <th>Tanggal Pengajuan</th>
                        <th class="text-center">Status Verifikasi</th>
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
                                    <strong><?= htmlspecialchars($pengajuan->nama_pendatang); ?></strong><br>
                                    <small class="text-muted">NIK: <?= htmlspecialchars($pengajuan->nik_pendatang); ?></small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($pengajuan->nama_keperluan); ?>
                                    <?php if($pengajuan->nama_keperluan == 'Lainnya' && !empty($pengajuan->keperluan_lainnya_text)): ?>
                                        <br><small class="text-info">(<?= htmlspecialchars($pengajuan->keperluan_lainnya_text); ?>)</small>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d M Y, H:i', strtotime($pengajuan->tanggal_pengajuan)); ?> WITA</td>
                                <td class="text-center">
                                    <?php
                                        $status = $pengajuan->status;
                                        $badge_class = 'bg-secondary';
                                        if ($status == 'Menunggu Verifikasi') $badge_class = 'bg-warning text-dark';
                                        if ($status == 'Terverifikasi') $badge_class = 'bg-success';
                                        if ($status == 'Ditolak') $badge_class = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge_class; ?>"><?= htmlspecialchars($status); ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-success" title="Verifikasi"><i class="mdi mdi-check-circle"></i></a>
                                    <a href="#" class="btn btn-sm btn-danger" title="Tolak"><i class="mdi mdi-close-circle"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data pengajuan surat.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('datatablePengajuan')) {
        new simpleDatatables.DataTable("#datatablePengajuan");
    }
});
</script>