<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
  /* Style untuk kolom yang berpotensi memiliki teks panjang agar bisa wrap */
  .kolom-teks-panjang {
    word-wrap: break-word;
    white-space: normal !important;
  }

  /* Kolom Alamat (jika Anda ingin style khusus untuk alamat di tabel PJ) */
  .kolom-alamat-pj { 
    word-wrap: break-word;
    white-space: normal !important;
  }

  /* Kolom Status: jaga agar badge tidak pecah */
  .kolom-status {
    white-space: nowrap;
    width: auto; 
  }

  /* PERUBAHAN DI SINI: Tombol aksi di tabel menjadi sedikit rounded */
  .btn-group-square.btn-group-sm > .btn,
  .btn-group-square.btn-group > .btn {
    border-radius: 3px !important; /* Sebelumnya 0px, sekarang 3px untuk sedikit lengkungan */
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
    border-radius: 3px !important; /* <<<--- PERUBAHAN DI SINI */
    border: 1px solid transparent;
  }

  .status-terverifikasi { color: #198754; border-color: #198754; background-color: transparent; }
  .status-belum { color: #ffc107; border-color: #ffc107; background-color: transparent; }
  .status-ditolak { color: #dc3545; border-color: #dc3545; background-color: transparent; }
  .status-secondary { color: #6c757d; border-color: #6c757d; background-color: transparent; }

  /* ----- Media Queries untuk Responsivitas Ekstra ----- */
  @media (max-width: 767.98px) { /* Layar SM ke bawah */
    #dataTablePj th,
    #dataTablePj td {
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
    .btn-group-aksi-responsive button.btn {
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

<h3>Data Penanggung Jawab</h3>
<p>Berikut adalah data Penanggung Jawab (PJ) yang telah terdaftar.</p>

<div class="mb-3">
    <input type="text" id="searchInputPj" class="form-control" placeholder="Cari berdasarkan Nama atau NIK PJ...">
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover" id="dataTablePj">
        <thead>
            <tr class="table-secondary text-center">
                <th>No</th>
                <th>NIK</th>
                <th>Nama Lengkap</th>
                <th class="d-none d-sm-table-cell">No. Handphone</th> 
                <th class="kolom-alamat-pj d-none d-lg-table-cell">Alamat Rumah</th> 
                <th class="d-none d-md-table-cell">Email</th> 
                <th class="d-none d-sm-table-cell">Jenis Akun</th> 
                <th class="kolom-status">Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Kolom minimal yang selalu tampil: No, NIK, Nama, Status, Aksi (5 kolom)
            $colspan_minimal_pj = 9; // Mengubah colspan menjadi 9 (sesuai jumlah TH)
            if (empty($hasil)) { // Menggunakan variabel $hasil dari controller Pj.php
                echo "<tr><td colspan='" . $colspan_minimal_pj . "' class='text-center'>Data tidak tersedia</td></tr>";
            } else {
                $no = 1;
                foreach ($hasil as $data): // Menggunakan variabel $hasil
            ?>
            <tr>
                <td class="text-center"><?php echo $no; ?></td>
                <td class="kolom-teks-panjang"><?php echo htmlspecialchars($data->NIK); ?></td>
                <td class="kolom-teks-panjang"><?php echo htmlspecialchars($data->namaLengkap); ?></td>
                <td class="d-none d-sm-table-cell"><?php echo htmlspecialchars($data->telp); ?></td>
                <td class="kolom-alamat-pj d-none d-lg-table-cell"><?php echo htmlspecialchars($data->alamat); ?></td>
                <td class="d-none d-md-table-cell"><?php echo htmlspecialchars($data->email); ?></td>
                <td class="d-none d-sm-table-cell"><?php echo htmlspecialchars($data->jenisAkun); ?></td>
                <td class="text-center kolom-status">
                    <?php
                        if ($data->statusAktivasi == 'Terverifikasi') {
                            echo "<span class='status-badge status-terverifikasi'>" . htmlspecialchars($data->statusAktivasi) . "</span>";
                        } elseif ($data->statusAktivasi == 'Belum') { // Pastikan nilai ini sesuai dengan DB
                            echo "<span class='status-badge status-belum'>" . htmlspecialchars($data->statusAktivasi) . "</span>";
                        } else { // Fallback untuk status lain jika ada
                            echo "<span class='status-badge status-secondary'>" . htmlspecialchars($data->statusAktivasi) . "</span>";
                        }
                    ?>
                </td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm btn-group-square btn-group-aksi-responsive" role="group" aria-label="Aksi PJ">
                        <?php if ($data->statusAktivasi == 'Belum'): ?>
                            <a href="<?php echo site_url('pj/verifikasidata/' . $data->kodeDaftar); ?>" 
                               class="btn btn-outline-success" title="Verifikasi Akun"
                               onclick="return confirm('Verifikasi akun PJ an. <?php echo htmlspecialchars($data->namaLengkap); ?>?')">
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
    document.addEventListener('DOMContentLoaded', function () {
        // --- JavaScript untuk Fitur Pencarian Tabel PJ ---
        const searchInputPj = document.getElementById('searchInputPj'); // ID baru untuk input pencarian PJ
        const dataTablePj = document.getElementById('dataTablePj');
        const tableRowsPj = dataTablePj ? dataTablePj.getElementsByTagName('tbody')[0].getElementsByTagName('tr') : [];

        if (searchInputPj) {
            searchInputPj.addEventListener('keyup', function() {
                const filter = searchInputPj.value.toLowerCase();

                for (let i = 0; i < tableRowsPj.length; i++) {
                    const row = tableRowsPj[i];
                    // Mengambil teks dari kolom NIK (indeks 1) dan Nama Lengkap (indeks 2)
                    const nikCell = row.cells[1]; // Kolom NIK (indeks 1)
                    const namaCell = row.cells[2]; // Kolom Nama Lengkap (indeks 2)

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

        // --- JavaScript untuk Modal Edit/Hapus (Kode lama Anda) ---
        // Karena fungsi editData dan hapusData sudah global atau didefinisikan di luar DOMContentLoaded
        // Saya akan pastikan modal logic ada di sini.
        // Jika editData dan hapusData didefinisikan secara global, mereka tidak perlu di dalam DOMContentLoaded.

        // Modal "Tolak Verifikasi" (meskipun di tabel PJ ini bukan untuk verifikasi pendatang, 
        // saya asumsikan ini adalah modal untuk "Tolak Akun" atau semacamnya, 
        // atau ini adalah kode lama dari pendatang_table yang tidak relevan di sini)
        // Jika modal ini tidak digunakan di halaman PJ, Anda bisa menghapusnya.
        // Jika digunakan, pastikan fungsinya sesuai untuk PJ.
        // Berdasarkan kode Anda, tidak ada tombol di PJ yang memicu modalTolakVerifikasi.
        // Saya akan tetap menyertakan modal HTML dan JS-nya,
        // namun perlu diingat bahwa tombol yang memicunya ("btn-tolak-verifikasi") tidak ada di tabel PJ Anda.
        // Pastikan Anda memicu modal ini dengan benar dari controller/view jika ingin menggunakannya untuk PJ.
    });

    // Fungsi global seperti hapusData dan editData
    function hapusData(kodeDaftar) {
        var confirmDelete = confirm("Apakah Anda yakin ingin menghapus data ini?");
        if (confirmDelete) {
            window.location.href = "<?php echo site_url('pj/hapusdata/'); ?>" + kodeDaftar;
        }
    }

    function editData(kodeDaftar) {
        $.ajax({
            url: "<?php echo site_url('pj/editdata/'); ?>" + kodeDaftar,
            type: "GET",
            dataType: "json",
            success: function(data) {
                $('#kodeDaftar').val(data.kodeDaftar);
                $('#NIK').val(data.NIK);
                $('#Nama').val(data.namaLengkap);
                $('#Telp').val(data.telp);    
                $('#Alamat').val(data.alamat); 
                $('#Email').val(data.email);   
                $('#jenisAkun').val(data.jenisAkun);
                // Jika Anda ingin field password juga diisi saat edit, pastikan ada inputnya di form pj_view.php
                // $('#password').val(data.password); 
                // Jika Anda memiliki modal untuk edit data, Anda perlu menampilkan modal di sini
                // contoh: $('#modalEditPj').modal('show');
            },
            error: function(xhr, status, error) {
                alert("Gagal mengambil data untuk diedit: " + error);
            }
        });
    }

    // Catatan: Modal tolak verifikasi ini sepertinya milik tabel pendatang sebelumnya.
    // Jika Anda ingin modal serupa untuk PJ, Anda harus membuat modal baru di sini
    // dengan ID dan elemen yang sesuai untuk konteks PJ.
    // Kode modal di bawah ini adalah duplikat dari pendatang_table.php dan mungkin tidak relevan untuk PJ
    // kecuali Anda memiliki fungsionalitas penolakan verifikasi akun PJ yang mirip.
    // Saat ini, tidak ada tombol di tabel PJ yang memicu modal #modalTolakVerifikasi.
</script>

<div class="modal fade" id="modalTolakVerifikasi" tabindex="-1" aria-labelledby="modalTolakVerifikasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formTolakVerifikasi" method="post" action=""> 
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTolakVerifikasiLabel">Tolak Verifikasi Data PJ</h5> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menolak verifikasi untuk data PJ berikut:</p>
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td style="width: 150px;">Nama Lengkap</td>
                                <td style="width: 10px;">:</td>
                                <td id="modalNamaPendatang"></td> </tr>
                            <tr>
                                <td>NIK</td>
                                <td>:</td>
                                <td><span id="modalNikPendatang"></span></td> </tr>
                            <tr>
                                <td>Tanggal Masuk</td>
                                <td>:</td>
                                <td><span id="modalTglMasukPendatang"></span></td> </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="mb-3">
                        <label for="alasanPenolakan" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasanPenolakan" name="alasan_penolakan" rows="4" required></textarea>
                        <div class="invalid-feedback" id="alasanError" style="display: none;">Alasan penolakan wajib diisi.</div>
                    </div>
                    <input type="hidden" name="id_pendatang_tolak" id="idPendatangTolak"> <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak & Simpan Alasan</button>
                </div>
            </form>
        </div>
    </div>
</div>