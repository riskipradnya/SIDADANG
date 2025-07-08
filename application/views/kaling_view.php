<style>
    /* Minimalist Card Header */
    .card-header-minimalist {
        background-color: #f8f9fa; /* Warna abu-abu sangat muda, atau bisa juga transparent */
        border-bottom: 1px solid #dee2e6; /* Garis bawah tipis */
        color: #212529; /* Warna teks standar (gelap) */
        font-weight: 500; /* Sedikit tebal untuk judul */
        padding: 0.75rem 1.25rem;
    }

    /* Style untuk tombol agar ada sedikit jarak jika bersebelahan */
    /* Ini akan memberi jarak antar tombol Simpan dan Batal jika tidak dalam btn-group */
    .btn-form-custom + .btn-form-custom {
        margin-left: 0.5rem;
    }
    /* Jika tombolnya bersebelahan langsung tanpa class custom tambahan, Anda bisa gunakan ini */
    /* .btn + .btn {
        margin-left: 0.5rem;
    } */


    /* Opsional: Sedikit efek pada input saat focus */
    .form-control:focus {
        border-color: #86b7fe; /* Warna border Bootstrap saat focus */
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25); /* Shadow Bootstrap saat focus */
    }

    /* CSS untuk tombol form kustom dengan sedikit lengkungan */
    .btn-form-custom {
        border-radius: 3px !important; /* Nilai kecil untuk lengkungan halus */
    }

    /* Tombol close pada alert sudah cukup minimalis dengan btn-close bawaan Bootstrap 5 */
</style>

<script language="javascript">
function simpankaling() {
    // Validasi sederhana sebelum submit (opsional, bisa ditambahkan)
    let nik_kaling = document.getElementById('NIK_Kaling').value.trim(); // Ganti ID jika berbeda
    let nama_kaling = document.getElementById('Nama_Kaling').value.trim(); // Ganti ID jika berbeda
    // Anda bisa menambahkan validasi untuk Alamat_Rumah_Kaling di sini jika diperlukan
    // let alamat_rumah_kaling = document.getElementById('Alamat_Rumah_Kaling').value.trim();
    // if (alamat_rumah_kaling === '') {
    //     alert('Alamat Rumah wajib diisi!');
    //     return false;
    // }
    if (nik_kaling === '' || nama_kaling === '') {
        alert('NIK dan Nama Lengkap Kaling wajib diisi!');
        return false; // Mencegah submit jika validasi gagal
    }
    $('#formkaling').submit();
}
</script>

<div class="card mb-4">
    <div class="card-header card-header-minimalist"> Form Data Kepala Lingkungan
    </div>
    <div class="card-body">
        <?php
            $pesan = $this->session->flashdata('pesan');
            $error = $this->session->flashdata('error'); // Menambahkan penanganan untuk 'error'

            if (!empty($pesan)) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        ' . $pesan . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
            if (!empty($error)) {
                 echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . $error . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        ?>

        <form name="formkaling" id="formkaling" method="post" action="<?php echo base_url('kaling/simpandata') ?>">
            <input type="hidden" name="kodeDaftar" id="kodeDaftar_Kaling"/> <div class="row mb-3">
                <label for="NIK_Kaling" class="col-sm-2 col-form-label">NIK</label>
                <div class="col-sm-10">
                    <input type="text" name="NIK" id="NIK_Kaling" class="form-control" required pattern="\d{16}" title="NIK harus 16 digit angka">
                </div>
            </div>

            <div class="row mb-3">
                <label for="Nama_Kaling" class="col-sm-2 col-form-label">Nama Lengkap</label>
                <div class="col-sm-10">
                    <input type="text" name="Nama" id="Nama_Kaling" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="Alamat_Rumah" class="col-sm-2 col-form-label">Alamat Rumah</label>
                <div class="col-sm-10">
                    <textarea name="Alamat_Rumah" id="Alamat_Rumah" class="form-control" rows="3" required></textarea>
                </div>
            </div>
            <div class="row mb-3">
                <label for="Telp_Kaling" class="col-sm-2 col-form-label">No. Telp</label>
                <div class="col-sm-10">
                    <input type="tel" name="Telp" id="Telp_Kaling" class="form-control" required pattern="[0-9]+" title="Hanya angka yang diperbolehkan">
                </div>
            </div>

            <div class="row mb-3">
                <label for="Email_Kaling" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="Email" id="Email_Kaling" class="form-control" required>
                </div>
            </div>

            <input type="hidden" name="jenisAkun" id="jenisAkun_Kaling" value="Kaling"/>
            <!-- <input type="hidden" name="password" id="password_Kaling" /> -->

            <hr>

            <div class="row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="button" class="btn btn-outline-primary btn-sm btn-form-custom" onClick="simpankaling();">
                        <i class=""></i> Simpan Data
                    </button>
                    <button type="reset" class="btn btn-outline-secondary btn-sm btn-form-custom">
                        <i class=""></i> Reset Form
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>