<style>
    /* =================================================================
       SOLUSI BARU & LEBIH SPESIFIK UNTUK HEADER SIMPLE-DATATABLE
       ================================================================= */
    /* Targetkan LANGSUNG link (<a>) di dalam header tabel */
    #datatablePengajuan thead th a {
        color: #212529 !important; /* Warna teks hitam standar */
        text-decoration: none !important; /* Hapus garis bawah */
    }

    /* Targetkan ikon panah sort agar warnanya juga tidak biru */
    #datatablePengajuan .dataTable-sorter::after,
    #datatablePengajuan .dataTable-sorter::before {
        opacity: 0.5 !important; /* Buat panah sedikit transparan agar tidak mencolok */
    }


    /* =================================================================
       CSS LAMA ANDA (TETAP DIPAKAI)
       ================================================================= */
    /* CSS untuk STATUS BADGE dengan border dan background transparan */
    .status-badge {
        display: inline-block;
        padding: 0.30em 0.60em;
        font-size: 0.875em;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 3px !important;
        border: 1px solid transparent;
    }

    .status-terverifikasi { color: #198754; border-color: #198754; background-color: transparent; }
    .status-menunggu { color: #ffc107; border-color: #ffc107; background-color: transparent; }
    .status-ditolak { color: #dc3545; border-color: #dc3545; background-color: transparent; }
    .status-secondary { color: #6c757d; border-color: #6c757d; background-color: transparent; }

    /* CSS untuk membuat grup tombol aksi menjadi responsif */
    @media (max-width: 767.98px) {
        .btn-group-aksi-responsive {
            display: flex;
            flex-direction: column;
            align-items: stretch; 
        }
        .btn-group-aksi-responsive .btn,
        .btn-group-aksi-responsive button.btn {
            width: 100%;
            margin-top: 2px;
            margin-bottom: 2px;
        }
    }
</style>

<div class="card shadow-sm mt-4">
    <div class="card-header card-header-minimalist">
        Pengajuan Surat Pengantar
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="datatablePengajuan">
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
                                    <?= htmlspecialchars($pengajuan->nama_pendatang); ?><br>
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
                                        if ($status == 'Terverifikasi') {
                                            echo "<span class='status-badge status-terverifikasi'>" . htmlspecialchars($status) . "</span>";
                                        } elseif ($status == 'Menunggu Verifikasi') {
                                            echo "<span class='status-badge status-menunggu'>" . htmlspecialchars($status) . "</span>";
                                        } elseif ($status == 'Ditolak') {
                                            echo "<span class='status-badge status-ditolak'>" . htmlspecialchars($status) . "</span>";
                                        } else { // Fallback untuk status lain jika ada
                                            echo "<span class='status-badge status-secondary'>" . htmlspecialchars($status) . "</span>";
                                        }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm btn-group-aksi-responsive" role="group" aria-label="Aksi Pengajuan">
                                        <?php
                                            // Aksi yang hanya bisa dilakukan jika status masih 'Menunggu Verifikasi'
                                            if ($pengajuan->status == 'Menunggu Verifikasi'):
                                        ?>
                                            <a href="<?= site_url('surat_kedatangan/verifikasi_pengajuan/' . $pengajuan->id); ?>" 
                                            class="btn btn-outline-success" 
                                            title="Verifikasi" 
                                            onclick="return confirm('Anda yakin ingin MEMVERIFIKASI pengajuan ini?');">
                                            <i class="mdi mdi-check-circle-outline"></i>
                                            </a>

                                            <a href="javascript:void(0);" 
                                            class="btn btn-outline-warning" 
                                            title="Tolak" 
                                            onclick="tolakPengajuan(<?= $pengajuan->id; ?>);">
                                            <i class="mdi mdi-close-circle-outline"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="<?= site_url('surat_kedatangan/hapus_pengajuan/' . $pengajuan->id); ?>" 
                                        class="btn btn-outline-danger" 
                                        title="Hapus Data" 
                                        onclick="return confirm('PERINGATAN! Anda yakin ingin MENGHAPUS data ini secara permanen? Aksi ini tidak bisa dibatalkan.');">
                                        <i class="mdi mdi-delete-outline"></i>
                                        </a>
                                    </div>
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