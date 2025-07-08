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
    .btn + .btn {
        margin-left: 0.5rem;
    }

    /* Opsional: Sedikit efek pada input saat focus */
    .form-control:focus {
        border-color: #86b7fe; /* Warna border Bootstrap saat focus */
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25); /* Shadow Bootstrap saat focus */
    }

    /* Tombol close pada alert sudah cukup minimalis dengan btn-close bawaan Bootstrap 5 */

    /* CSS Kustom untuk membuat tombol di dalam btn-group menjadi kotak (jika masih digunakan di tempat lain) */
    .btn-group-square.btn-group-sm > .btn,
    .btn-group-square.btn-group > .btn {
        border-radius: 0 !important; 
    }

    /* --- CSS BARU atau MODIFIKASI untuk tombol form kustom --- */
    .btn-form-custom {
        border-radius: 3px !important; /* Nilai kecil untuk lengkungan halus. Anda bisa coba 2px, 4px, atau 0.15rem */
        /* Tidak perlu margin di sini jika Anda ingin mereka rapat, 
        atau tambahkan margin jika ingin ada sedikit jarak antar tombol individual */
    }

    /* Jika Anda ingin ada sedikit jarak antar tombol kustom ini */
    .btn-form-custom + .btn-form-custom { /* Memberi jarak pada tombol kedua dan seterusnya */
        margin-left: 0.5rem; /* Sesuaikan jaraknya */
    }
</style>

<script language="javascript">
function simpanpj() {
    // Validasi sederhana sebelum submit (opsional, bisa ditambahkan)
    // Misalnya, cek apakah NIK dan Nama sudah diisi
    let nik = document.getElementById('NIK').value.trim();
    let nama = document.getElementById('Nama').value.trim();
    if (nik === '' || nama === '') {
        alert('NIK dan Nama Lengkap wajib diisi!');
        return false; // Mencegah submit jika validasi gagal
    }
    $('#formpj').submit();
}
</script>

<div class="card mb-4">
    <div class="card-header card-header-minimalist">
        Form Data Penanggung Jawab
    </div>
    <div class="card-body"> 
        <?php
            $pesan = $this->session->flashdata('pesan'); // Untuk konsistensi dengan controller PJ
            $error = $this->session->flashdata('error'); // Menangkap juga flashdata 'error'
            
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

        <form name="formpj" id="formpj" method="post" action="<?php echo base_url('pj/simpandata') ?>">
            <input type="hidden" name="kodeDaftar" id="kodeDaftar"/>

            <div class="row mb-3">
                <label for="NIK" class="col-sm-2 col-form-label">NIK</label>
                <div class="col-sm-10">
                    <input type="text" name="NIK" id="NIK" class="form-control" required pattern="\d{16}" title="NIK harus 16 digit angka">
                </div>
            </div>

            <div class="row mb-3">
                <label for="Nama" class="col-sm-2 col-form-label">Nama Lengkap</label>
                <div class="col-sm-10">
                    <input type="text" name="Nama" id="Nama" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="Telp" class="col-sm-2 col-form-label">No. Handphone</label>
                <div class="col-sm-10">
                    <input type="tel" name="Telp" id="Telp" class="form-control" required pattern="[0-9]+" title="Hanya angka yang diperbolehkan">
                </div>
            </div>

            <div class="row mb-3">
                <label for="Alamat" class="col-sm-2 col-form-label">Alamat Rumah</label>
                <div class="col-sm-10">
                    <textarea name="Alamat" id="Alamat" class="form-control" rows="3" required></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <label for="Email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="Email" id="Email" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="Password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" name="Password" id="Password" class="form-control" required>
                </div>
            </div>

            <input type="hidden" name="jenisAkun" id="jenisAkun" value="PJ"/>
            <!-- <input type="hidden" name="password" id="password" />  -->

          <hr> 
            <div class="row">
                <div class="col-sm-10 offset-sm-2"> 
                    <button type="button" class="btn btn-outline-primary btn-sm btn-form-custom" onClick="simpanpj();">
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