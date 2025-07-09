<style>
    /* Ukuran untuk ikon di kartu ringkasan */
    .summary-icon {
        font-size: 1.75rem;
    }
    /* Membuat ikon mata bisa di-klik (pointer) */
    .toggle-password {
        cursor: pointer;
    }
</style>

<div class="container-fluid px-4">

    <?php if ($this->session->flashdata('password_success')): ?>
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            <?php echo $this->session->flashdata('password_success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($this->session->flashdata('password_error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
            <?php echo $this->session->flashdata('password_error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm mt-4">
        <div class="card-body p-4">
            
            <div>
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-grow-1">
                        <h4 class="mb-0">Profil: <?php echo $user_data->namaLengkap; ?></h4>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#ubahPasswordModal" class="text-primary text-decoration-underline">
                            Ubah Password?
                        </a>
                    </div>
                </div>
                
                <hr>
                <h6 class="text-muted mb-3">Detail Kontak</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="fw-bold">NIK</span>
                        <span><?php echo $user_data->NIK; ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="fw-bold">Email</span>
                        <span><?php echo $user_data->email; ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="fw-bold">No. Handphone</span>
                        <span><?php echo $user_data->telp; ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="fw-bold">Jenis Akun</span>
                        <span><?php echo $user_data->jenisAkun; ?></span>
                    </li>
                    <?php if ($this->session->userdata('Level') == 'PJ'): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="fw-bold">Alamat</span>
                        <span class="text-end"><?php echo $user_data->alamat; ?></span>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <hr class="my-4">

            <div>
                <h5 class="mb-4">Ringkasan & Aktivitas</h5>

                <?php if ($this->session->userdata('Level') == 'KALING') : ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card card-body border-primary-subtle">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 text-primary">
                                        <i class="fas fa-users summary-icon"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mb-0"><?php echo $specific_data['jumlah_pj']; ?></h4>
                                        <p class="mb-0 text-muted small">Penanggung Jawab</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-body border-success-subtle">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 text-success">
                                        <i class="fas fa-street-view summary-icon"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mb-0"><?php echo $specific_data['jumlah_pendatang']; ?></h4>
                                        <p class="mb-0 text-muted small">Total Pendatang</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($this->session->userdata('Level') == 'PJ') : ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card card-body border-info-subtle h-100">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 text-info">
                                        <i class="fas fa-user-shield summary-icon"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-1 text-muted small">Kepala Lingkungan</p>
                                        <h5 class="mb-0"><?php echo $specific_data['kaling_info'] ? $specific_data['kaling_info']->namaLengkap : 'Tidak ada'; ?></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-body border-warning-subtle h-100">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 text-warning">
                                        <i class="fas fa-walking summary-icon"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="mb-0"><?php echo $specific_data['jumlah_pendatang']; ?></h4>
                                        <p class="mb-0 text-muted small">Pendatang Anda</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="ubahPasswordModal" tabindex="-1" aria-labelledby="ubahPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ubahPasswordModalLabel">Ubah Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('profil/proses_ubah_password'); ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="password_lama" class="form-label">Password Lama</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_lama" name="password_lama" required>
                            <span class="input-group-text toggle-password">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password_baru" class="form-label">Password Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_baru" name="password_baru" required>
                            <span class="input-group-text toggle-password">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="konfirmasi_password" class="form-label">Ulangi Password Baru</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required>
                            <span class="input-group-text toggle-password">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePasswordIcons = document.querySelectorAll('.toggle-password');

        togglePasswordIcons.forEach(function(iconSpan) {
            iconSpan.addEventListener('click', function() {
                const passwordInput = this.previousElementSibling;
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>