<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* --- CSS LAMA ANDA (Dipertahankan) --- */
    .kolom-teks-panjang {
        word-wrap: break-word;
        white-space: normal !important; 
        min-width: 120px; 
    }
    .kolom-alamat {
        word-wrap: break-word;
        white-space: normal !important;
    }
    .kolom-status {
        white-space: nowrap; 
        width: auto; 
    }
    .btn-group-square.btn-group-sm > .btn,
    .btn-group-square.btn-group > .btn {
        border-radius: 3px !important;
    }
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
    .status-belum { color: #ffc107; border-color: #ffc107; background-color: transparent; }
    .status-ditolak { color: #dc3545; border-color: #dc3545; background-color: transparent; }
    .status-secondary { color: #6c757d; border-color: #6c757d; background-color: transparent; }

    @media (max-width: 767.98px) { 
        #dataTablePendatang th,
        #dataTablePendatang td {
            padding: 0.5rem 0.4rem;
            font-size: 0.85rem;  
        }
        .btn-group-sm > .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
        .status-badge {
            padding: 0.2em 0.4em;
            font-size: 0.75em;
        }
        .btn-group-aksi-responsive {
            display: flex;
            flex-direction: column;
            align-items: stretch; 
        }
        .btn-group-aksi-responsive .btn {
            width: 100%;
            margin-top: 2px;
            margin-bottom: 2px;
        }
        .btn-group-aksi-responsive .btn:first-child {
            margin-top: 0;
        }
        .btn-group-aksi-responsive .btn:last-child {
            margin-bottom: 0;
        }
    }
</style>

<h3>Data Pendatang</h3>
<p>Berikut adalah seluruh data pendatang yang telah terdaftar dalam sistem.</p>

<div class="mb-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan Nama atau NIK...">
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover" id="dataTablePendatang">
        <thead>
            <tr class="table-secondary text-center">
                <th>No</th>
                <th>NIK</th>
                <th>Nama Lengkap</th>
                <th class="d-none d-md-table-cell">No. Handphone</th>
                <th class="kolom-alamat d-none d-lg-table-cell">Alamat Domisili</th>
                <th class="d-none d-sm-table-cell">Tgl Masuk</th>
                <th class="kolom-teks-panjang d-none d-md-table-cell">Tujuan Kedatangan</th>
                <th class="kolom-teks-panjang d-none d-md-table-cell">Penanggung Jawab</th> 
                <th class="kolom-status">Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Pastikan $Level di sini sudah dikirim dari controller (seperti yang sudah kita bahas sebelumnya)
            // Example: $data['Level'] = $this->session->userdata('Level'); in your controller
            $user_level = $this->session->userdata('Level'); // Ambil level pengguna di sini
            
            $colspan_minimal = 10; 
            if (empty($pendatang_data)) {
                echo "<tr><td colspan='" . $colspan_minimal . "' class='text-center'>Belum ada data pendatang yang tersimpan.</td></tr>";
            } else {
                $no = 1;
                foreach ($pendatang_data as $data):
            ?>
            <tr>
                <td class="text-center"><?php echo $no; ?></td>
                <td class="kolom-teks-panjang"><?php echo htmlspecialchars($data->nik); ?></td>
                <td class="kolom-teks-panjang"><?php echo htmlspecialchars($data->nama); ?></td>
                <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($data->no_hp); ?></td>
                <td class="kolom-alamat d-none d-lg-table-cell"><?php echo htmlspecialchars($data->alamat_sekarang); ?></td>
                <td class="text-center d-none d-sm-table-cell"><?php echo (!empty($data->tgl_masuk) && $data->tgl_masuk != '0000-00-00') ? date('d-m-Y', strtotime($data->tgl_masuk)) : '-'; ?></td>
                <td class="kolom-teks-panjang d-none d-md-table-cell"><?php echo htmlspecialchars($data->tujuan); ?></td>
                <td class="kolom-teks-panjang d-none d-md-table-cell">
                    <?php
                    // Menampilkan PJ dan Kaling terkait
                    $pj_info = '';
                    if (!empty($data->pj_nama_lengkap)) {
                        $pj_info .= "PJ: " . htmlspecialchars($data->pj_nama_lengkap);
                    }
                    if (!empty($data->kaling_nama_lengkap)) {
                        $pj_info .= ($pj_info != '' ? "<br>" : "") . "<small class='text-muted'>Kaling: " . htmlspecialchars($data->kaling_nama_lengkap) . "</small>";
                    }
                    echo !empty($pj_info) ? $pj_info : "<small class='text-muted'><em>Belum ada</em></small>";
                    ?>
                </td>
                <td class="text-center kolom-status">
                    <?php
                        $statusClass = 'secondary'; $statusText = htmlspecialchars($data->statusAktivasi);
                        if ($data->statusAktivasi == 'Terverifikasi') { $statusClass = 'terverifikasi';
                        } elseif ($data->statusAktivasi == 'Verifikasi Ditolak') { $statusClass = 'ditolak';
                        } elseif ($data->statusAktivasi == 'Belum Terverifikasi') { $statusClass = 'belum'; }
                        echo "<span class='status-badge status-".$statusClass."'>" . $statusText . "</span>";
                    ?>
                </td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm btn-group-square btn-group-aksi-responsive" role="group" aria-label="Aksi Pendatang">
                        <a href="<?php echo site_url('pendatang/detail/' . $data->id); ?>" class="btn btn-outline-info" title="Lihat Detail">
                            <i class="mdi mdi-eye-outline"></i>
                        </a>
                        
                        <?php if (isset($user_level) && ($user_level == 'Admin' || $user_level == 'KALING')): // <-- KONDISI BARU DI SINI ?>
                            <?php if ($data->statusAktivasi == 'Belum Terverifikasi'): ?>
                                <a href="<?php echo site_url('pendatang/verifikasi/' . $data->id); ?>" class="btn btn-outline-success" title="Verifikasi Data" onclick="return confirm('Apakah Anda yakin ingin MEMVERIFIKASI data pendatang an. <?= htmlspecialchars(addslashes($data->nama)); ?>?')">
                                    <i class="mdi mdi-check-circle-outline"></i>
                                </a>
                                <button type="button" class="btn btn-outline-warning btn-tolak-verifikasi" 
                                        data-bs-toggle="modal" data-bs-target="#modalTolakVerifikasi"
                                        data-id="<?= $data->id; ?>"
                                        data-nama="<?= htmlspecialchars($data->nama); ?>"
                                        data-nik="<?= htmlspecialchars($data->nik); ?>"
                                        data-tglmasuk="<?= (!empty($data->tgl_masuk) && $data->tgl_masuk != '0000-00-00') ? date('d F Y', strtotime($data->tgl_masuk)) : '-'; ?>"
                                        title="Tolak Verifikasi">
                                    <i class="mdi mdi-close-circle-outline"></i>
                                </button>
                            <?php endif; ?>
                        <?php endif; // <-- PENUTUP KONDISI LEVEL ?>

                        <a href="<?php echo site_url('pendatang/hapus/' . $data->id); ?>" class="btn btn-outline-danger" title="Hapus Data" onclick="return confirm('Apakah Anda yakin ingin menghapus data an. <?= htmlspecialchars(addslashes($data->nama)); ?>?')">
                            <i class="mdi mdi-delete-outline"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php
                $no++;
                endforeach;
            }
            ?> 
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalTolakVerifikasi" tabindex="-1" aria-labelledby="modalTolakVerifikasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formTolakVerifikasi" method="post" action=""> 
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTolakVerifikasiLabel">Tolak Verifikasi Data Pendatang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menolak verifikasi untuk data pendatang berikut:</p>
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td style="width: 150px;">Nama Lengkap</td>
                                <td style="width: 10px;">:</td>
                                <td id="modalNamaPendatang"></td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td>:</td>
                                <td><span id="modalNikPendatang"></span></td>
                            </tr>
                            <tr>
                                <td>Tanggal Masuk</td>
                                <td>:</td>
                                <td><span id="modalTglMasukPendatang"></span></td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="mb-3">
                        <label for="alasanPenolakan" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasanPenolakan" name="alasan_penolakan" rows="4" required></textarea>
                        <div class="invalid-feedback" id="alasanError" style="display: none;">Alasan penolakan wajib diisi.</div>
                    </div>
                    <input type="hidden" name="id_pendatang_tolak" id="idPendatangTolak"> 
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak & Simpan Alasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- JavaScript untuk Fitur Pencarian ---
    const searchInput = document.getElementById('searchInput');
    const dataTable = document.getElementById('dataTablePendatang');
    const tableRows = dataTable ? dataTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr') : [];

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();

            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                // Mengambil teks dari kolom NIK (indeks 1) dan Nama Lengkap (indeks 2)
                const nikCell = row.cells[1]; // Kolom NIK
                const namaCell = row.cells[2]; // Kolom Nama Lengkap

                if (nikCell && namaCell) {
                    const nikText = nikCell.textContent || nikCell.innerText;
                    const namaText = namaCell.textContent || namaCell.innerText;

                    // Periksa apakah teks pencarian ada di NIK atau Nama Lengkap
                    if (nikText.toLowerCase().indexOf(filter) > -1 || namaText.toLowerCase().indexOf(filter) > -1) {
                        row.style.display = ""; // Tampilkan baris
                    } else {
                        row.style.display = "none"; // Sembunyikan baris
                    }
                }
            }
        });
    }

    // --- JavaScript untuk Modal Tolak Verifikasi (Kode lama Anda) ---
    var modalTolakVerifikasiElement = document.getElementById('modalTolakVerifikasi');
    if (modalTolakVerifikasiElement) {
        var modalTolakVerifikasi = new bootstrap.Modal(modalTolakVerifikasiElement);
    }
    
    var formTolakVerifikasi = document.getElementById('formTolakVerifikasi');
    var idPendatangTolakInput = document.getElementById('idPendatangTolak');
    var alasanPenolakanTextarea = document.getElementById('alasanPenolakan');
    var alasanErrorDiv = document.getElementById('alasanError');

    var tombolTolakList = document.querySelectorAll('.btn-tolak-verifikasi');
    tombolTolakList.forEach(function (button) {
        button.addEventListener('click', function () {
            var id = this.dataset.id;
            var nama = this.dataset.nama;
            var nik = this.dataset.nik;
            var tglMasuk = this.dataset.tglmasuk;

            document.getElementById('modalNamaPendatang').textContent = nama;
            document.getElementById('modalNikPendatang').textContent = nik;
            document.getElementById('modalTglMasukPendatang').textContent = tglMasuk;
            
            if(idPendatangTolakInput) idPendatangTolakInput.value = id;
            
            if(formTolakVerifikasi) {
                formTolakVerifikasi.action = "<?= site_url('pendatang/tolak_verifikasi_dengan_alasan/'); ?>" + id;
            }
            
            if(alasanPenolakanTextarea) {
                alasanPenolakanTextarea.classList.remove('is-invalid');
                alasanPenolakanTextarea.value = ''; 
            }
            if(alasanErrorDiv) alasanErrorDiv.style.display = 'none';

            if(modalTolakVerifikasi) modalTolakVerifikasi.show();
        });
    });

    if (formTolakVerifikasi) {
        formTolakVerifikasi.addEventListener('submit', function (event) {
            if (alasanPenolakanTextarea.value.trim() === '') {
                event.preventDefault();
                event.stopPropagation();
                alasanPenolakanTextarea.classList.add('is-invalid');
                if(alasanErrorDiv) alasanErrorDiv.style.display = 'block';
            } else {
                alasanPenolakanTextarea.classList.remove('is-invalid');
                if(alasanErrorDiv) alasanErrorDiv.style.display = 'none';
            }
        }, false);
    }
});
</script>