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
                                    <?php
                                        // Aksi yang hanya bisa dilakukan jika status masih 'Menunggu Verifikasi'
                                        if ($pengajuan->status == 'Menunggu Verifikasi'):
                                    ?>
                                        <a href="<?= site_url('surat_kedatangan/verifikasi_pengajuan/' . $pengajuan->id); ?>" 
                                        class="btn btn-sm btn-success" 
                                        title="Verifikasi" 
                                        onclick="return confirm('Anda yakin ingin MEMVERIFIKASI pengajuan ini?');">
                                        <i class="mdi mdi-check-circle"></i>
                                        </a>

                                        <a href="javascript:void(0);" 
                                        class="btn btn-sm btn-danger" 
                                        title="Tolak" 
                                        onclick="tolakPengajuan(<?= $pengajuan->id; ?>);">
                                        <i class="mdi mdi-close-circle"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?= site_url('surat_kedatangan/hapus_pengajuan/' . $pengajuan->id); ?>" 
                                    class="btn btn-sm btn-dark" 
                                    title="Hapus Data" 
                                    onclick="return confirm('PERINGATAN! Anda yakin ingin MENGHAPUS data ini secara permanen? Aksi ini tidak bisa dibatalkan.');">
                                    <i class="mdi mdi-delete-forever"></i>
                                    </a>
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
    // Inisialisasi DataTable (kode ini sudah ada)
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('datatablePengajuan')) {
            new simpleDatatables.DataTable("#datatablePengajuan");
        }
    });

    /**
     * Fungsi untuk menangani aksi penolakan
     * @param {number} id - ID pengajuan yang akan ditolak
     */
    function tolakPengajuan(id) {
        // 1. Munculkan kotak dialog untuk meminta alasan
        const alasan = prompt("Harap masukkan alasan penolakan untuk pengajuan ini:");

        // 2. Cek apakah pengguna menekan "Cancel" atau tidak mengisi alasan
        if (alasan === null) {
            // Jika user klik "Cancel", jangan lakukan apa-apa
            return; 
        }

        if (alasan.trim() === "") {
            // Jika alasan kosong, beri peringatan
            alert("Alasan penolakan tidak boleh kosong.");
            return;
        }

        // 3. Jika alasan diisi, arahkan ke URL controller sambil membawa alasan
        const baseUrl = "<?= site_url('surat_kedatangan/tolak_pengajuan/'); ?>";
        const redirectUrl = baseUrl + id + "?alasan=" + encodeURIComponent(alasan);
        
        // Arahkan browser ke URL tersebut
        window.location.href = redirectUrl;
    }
</script>