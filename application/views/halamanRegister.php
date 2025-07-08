<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Register - Users</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
        <link href="<?php echo base_url(); ?>css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
<body>
<div class="container py-5">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h4 class="text-center mb-4">Halaman Registrasi SIDADANG (Sistem Pendataan Pendatang)
                    </h4>
                    <form name="formregister" id="formregister" method="post" action="<?php echo site_url('Register/Register'); ?>">

                        <div class="form-outline mb-4" data-mdb-input-init>
                            <select class="form-select" id="jabatan" name="jabatan">
                                <option value="" disabled selected>Pilih Jabatan</option>
                                <option value="KALING">KALING</option>
                                <option value="PJ">PJ</option>
                            </select>
                            <?php echo form_error('jabatan', '<small class="text-danger">', '</small>'); ?>
                        </div>

                        <div class="form-outline mb-4" data-mdb-input-init>
                            <input class="form-control" id="nik" name="nik" type="text" placeholder="NIK" />
                            <label class="form-label" for="nik">NIK</label>
                            <?php echo form_error('nik', '<small class="text-danger">', '</small>'); ?>
                        </div>

                        <div class="form-outline mb-4" data-mdb-input-init>
                            <input class="form-control" id="namaLengkap" name="namaLengkap" type="text" placeholder="Nama Lengkap" />
                            <label class="form-label" for="namaLengkap">Nama Lengkap</label>
                            <?php echo form_error('namaLengkap', '<small class="text-danger">', '</small>'); ?>
                        </div>

                        <div class="form-outline mb-4" data-mdb-input-init id="alamatWrapper">
                            <input class="form-control" id="alamat" name="alamat" type="text" placeholder="Alamat" />
                            <label class="form-label" for="alamat">Alamat</label>
                            <?php echo form_error('alamat', '<small class="text-danger">', '</small>'); ?>
                        </div>


                        <div class="form-outline mb-4" data-mdb-input-init>
                            <input class="form-control" id="nomerTelepon" name="nomerTelepon" type="nomerTelepon" placeholder="Nomer Telepon" />
                            <label class="form-label" for="nomerTelepon">Nomer Telepon</label>
                            <?php echo form_error('nomerTelepon', '<small class="text-danger">', '</small>'); ?>
                        </div>


                        <div class="form-outline mb-4" data-mdb-input-init>
                            <input class="form-control" id="email" name="email" type="text" placeholder="Email" />
                            <label class="form-label" for="email">Email</label>
                            <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                        </div>

                        <!-- Submit button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block mb-4" data-mdb-button-init data-mdb-ripple-init onClick="prosesRegister();">
                                Regsiter
                            </button>
                        </div>


                        <!-- Login link -->
                        <div class="text-center">
                            <p>Sudah punya akun? <a href="<?php echo site_url('Halaman'); ?>">Login</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="<?php echo base_url(); ?>js/scripts.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script language="javascript">

    document.addEventListener("DOMContentLoaded", function () {
            const jabatanSelect = document.getElementById("jabatan");
            const alamatWrapper = document.getElementById("alamatWrapper");

            // Sembunyikan kolom alamat di awal
            alamatWrapper.style.display = "none";

            // Tambahkan event listener saat jabatan berubah
            jabatanSelect.addEventListener("change", function () {
                if (jabatanSelect.value === "PJ") {
                    alamatWrapper.style.display = "block";
                } else {
                    alamatWrapper.style.display = "none";
                }
            });
        });

    function prosesRegister() {
        var jabatan = $('#jabatan').val();
        if (jabatan == "") {
            alert("Jabatan harus dipilih");
            $('#jabatan').focus();
            return false;
        }

        $('#formregister').submit();
    }
</script>

<?php
$pesan = $this->session->flashdata('pesan');
$alert_type = $this->session->flashdata('alert_type');

if ($pesan != "") {
    // Tentukan warna alert berdasarkan tipe (sukses atau gagal)
    if ($alert_type == 'success') {
        $alert_class = 'alert-success'; // Hijau untuk sukses
    } else {
        $alert_class = 'alert-danger'; // Merah untuk gagal
    }
    ?>
    <div class="alert <?php echo $alert_class; ?> alert-dismissible fade show" role="alert">
        <button type="button" class="btn btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
        <?php echo $pesan; ?>
    </div>
    <?php
}
?>


</body>
</html>