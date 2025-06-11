<style>
    /* Style untuk kolom yang berpotensi memiliki teks panjang agar bisa wrap */
.kolom-teks-panjang {
        word-wrap: break-word;
        white-space: normal !important;
        min-width: 120px;
    }

    /* Kolom Alamat (jika Anda ingin style khusus untuk alamat di tabel Kaling) */
    .kolom-alamat-kaling { /* Class berbeda jika treatmentnya beda */
    word-wrap: break-word;
    white-space: normal !important;
    min-width: 150px; /* Mungkin butuh lebih lebar */
    }
    
    /* Kolom Email (jika Anda ingin style khusus untuk email di tabel Kaling) */
    .kolom-email-kaling {
    word-wrap: break-word; /* Agar email panjang bisa wrap */
    white-space: normal !important;
    }

    /* Kolom Status: jaga agar badge tidak pecah */
    .kolom-status {
    white-space: nowrap;
    width: auto;
    }

    /* Tombol aksi di tabel menjadi sedikit rounded */
    .btn-group-square.btn-group-sm > .btn,
    .btn-group-square.btn-group > .btn {
    border-radius: 3px !important;
    }

    /* CSS untuk STATUS AKTIVASI dengan sedikit lengkungan */
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

    /* ----- Media Queries untuk Responsivitas Ekstra ----- */
    @media (max-width: 767.98px) { /* Layar SM ke bawah */
    #dataTableKaling th, /* Target ID tabel Kaling */
    #dataTableKaling td {
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
    .btn-group-aksi-responsive .btn,
    .btn-group-aksi-responsive button.btn { /* Target juga button jika ada */
        width: 100%;
        margin-top: 2px;
        margin-bottom: 2px;
    }
        .btn-group-aksi-responsive .btn:first-child,
        .btn-group-aksi-responsive button.btn:first-child {
            margin-top: 0;
    }
    .btn-group-aksi-responsive .btn:last-child,
    .btn-group-aksi-responsive button.btn:last-child {
            margin-bottom: 0;
    }
    }
</style>

<h3>Data Kaling</h3>
<p>Berikut adalah data Kepala Lingkungan (Kaling) yang telah terdaftar.</p>

<div class="table-responsive">
    <table class="table table-bordered table-hover" id="dataTableKaling">
        <thead>
            <tr class="table-secondary text-center">
                <th>No</th>
                <th>NIK</th>
                <th>Nama Lengkap</th>
                <th class="d-none d-sm-table-cell">No. Telp</th>
                <th class="kolom-alamat-kaling d-none d-md-table-cell">Alamat Rumah</th> 
                <th class="kolom-email-kaling d-none d-md-table-cell">Email</th>
                <th class="d-none d-sm-table-cell">Jenis Akun</th>
                <th class="kolom-status">Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Kolom minimal yang selalu tampil: No, NIK, Nama, Status, Aksi (5 kolom)
            // Sesuaikan colspan berdasarkan jumlah kolom minimal yang selalu tampil di layar terkecil
            // Karena "Alamat Rumah" juga menggunakan d-none d-md-table-cell (tersembunyi di layar kecil),
            // $colspan_minimal_kaling tidak perlu diubah.
            $colspan_minimal_kaling = 5;
            if (empty($hasil)) { // Menggunakan variabel $hasil dari controller Kaling
                echo "<tr><td colspan='" . $colspan_minimal_kaling . "' class='text-center'>Data tidak tersedia</td></tr>";
            } else {
                $no = 1;
                foreach ($hasil as $data): // Menggunakan variabel $hasil
            ?>
            <tr>
                <td class="text-center"><?php echo $no; ?></td>
                <td class="kolom-teks-panjang"><?php echo htmlspecialchars($data->NIK); ?></td>
                <td class="kolom-teks-panjang"><?php echo htmlspecialchars($data->namaLengkap); ?></td>
                <td class="d-none d-sm-table-cell"><?php echo htmlspecialchars($data->telp); ?></td>
                <td class="kolom-alamat-kaling d-none d-md-table-cell">
                <?php echo isset($data->Alamat_Rumah) ? htmlspecialchars($data->Alamat_Rumah) : ''; ?>                </td>
                <td class="kolom-email-kaling d-none d-md-table-cell"><?php echo htmlspecialchars($data->email); ?></td>
                <td class="d-none d-sm-table-cell"><?php echo htmlspecialchars($data->jenisAkun); ?></td>
                <td class="text-center kolom-status">
                    <?php
                        if ($data->statusAktivasi == 'Terverifikasi') {
                            echo "<span class='status-badge status-terverifikasi'>" . htmlspecialchars($data->statusAktivasi) . "</span>";
                        } elseif ($data->statusAktivasi == 'Belum') { // Controller Kaling Anda menggunakan 'Belum'
                            echo "<span class='status-badge status-belum'>" . htmlspecialchars($data->statusAktivasi) . "</span>";
                        } else {
                            echo "<span class='status-badge status-secondary'>" . htmlspecialchars($data->statusAktivasi) . "</span>";
                        }
                    ?>
                </td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm btn-group-square btn-group-aksi-responsive" role="group" aria-label="Aksi Kaling">
                        <?php if ($data->statusAktivasi == 'Belum'): // Atau != 'Terverifikasi' ?>
                            <a href="<?php echo site_url('kaling/verifikasidata/' . $data->kodeDaftar); ?>"
                                class="btn btn-outline-success" title="Verifikasi Akun"
                                onclick="return confirm('Verifikasi akun Kaling an. <?php echo htmlspecialchars($data->namaLengkap); ?>?')">
                                <i class="mdi mdi-check-circle-outline"></i>
                            </a>
                        <?php endif; ?>
                        
                        <button class="btn btn-outline-primary" title="Edit Data" onClick="editData('<?php echo $data->kodeDaftar; ?>')">
                            <i class="mdi mdi-pencil-outline"></i>
                        </button>

                        <button class="btn btn-outline-danger" title="Hapus Data" onClick="hapusData('<?php echo $data->kodeDaftar; ?>')">
                            <i class="mdi mdi-delete-outline"></i>
                        </button>
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

<script type="text/javascript">
    function hapusData(kodeDaftar) {
        var confirmDelete = confirm("Apakah Anda yakin ingin menghapus data ini?");
        if (confirmDelete) {
            window.location.href = "<?php echo site_url('kaling/hapusdata/'); ?>" + kodeDaftar;
        }
    }

    function editData(kodeDaftar) {
        $.ajax({
            url: "<?php echo site_url('kaling/editdata/'); ?>" + kodeDaftar,
            type: "GET",
            dataType: "json",
            success: function(data) {
                // Mengisi form kaling_view.php
                // Pastikan ID input di kaling_view.php sesuai
                $('#kodeDaftar_Kaling').val(data.kodeDaftar); // Gunakan ID unik jika ada
                $('#NIK_Kaling').val(data.NIK);
                $('#Nama_Kaling').val(data.namaLengkap);
                $('#Alamat_Rumah').val(data.Alamat_Rumah); // ID dari form kaling_view.php
                $('#Telp_Kaling').val(data.telp);   
                $('#Email_Kaling').val(data.email);  
                $('#jenisAkun_Kaling').val(data.jenisAkun);
                // Untuk password, biasanya tidak diisi ulang di form edit kecuali untuk diubah
                // $('#password_Kaling').val(data.password);
            },
            error: function(xhr, status, error) {
                alert("Gagal mengambil data untuk diedit: " + error);
            }
        });
    }
</script>