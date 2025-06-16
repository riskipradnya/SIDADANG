<div class="card shadow-sm" id="form-surat-edge-to-edge">
    <div class="card-header card-header-minimalist"> Formulir Pengajuan Surat Pengantar
    </div>

        <?php
            // Ambil flashdata dari session
            $pesan_sukses = $this->session->flashdata('pesan');
            $pesan_error = $this->session->flashdata('error');
            
            // Tampilkan pesan sukses jika ada
            if (!empty($pesan_sukses)) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        ' . $pesan_sukses . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }

            // Tampilkan pesan error jika ada (untuk jaga-jaga ke depan)
            if (!empty($pesan_error)) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . $pesan_error . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
            }
        ?>
    
    <div class="card-body">

        <?php 
        // Menampilkan pesan error jika ada dari controller
        if ($this->session->flashdata('error')) {
            echo '<div class="alert alert-danger" role="alert">' . $this->session->flashdata('error') . '</div>';
        }
        ?>


        <form action="<?= site_url('surat_kedatangan/proses_pengajuan'); ?>" method="POST" id="formPengajuanSurat">

            <div class="mb-4">
                <label for="id_pendatang" class="form-label">1. Pilih Pendatang (Terverifikasi)</label>
                <select class="form-select" id="id_pendatang" name="id_pendatang" required>
                    <option value="" selected disabled>--- Cari dan Pilih Nama Pendatang ---</option>
                    <?php if (isset($pendatang_terverifikasi) && !empty($pendatang_terverifikasi)): ?>
                        <?php foreach ($pendatang_terverifikasi as $pendatang): ?>
                            <option value="<?= htmlspecialchars($pendatang->id); ?>">
                                <?= htmlspecialchars($pendatang->nama); ?> (NIK: <?= htmlspecialchars($pendatang->nik); ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>Tidak ada data pendatang terverifikasi yang tersedia</option>
                    <?php endif; ?>
                </select>
                <div class="form-text">Hanya pendatang dengan status "Terverifikasi" yang akan muncul di sini.</div>
            </div>

            <div class="mb-4">
                <label for="id_keperluan" class="form-label">2. Pilih Keperluan Surat Pengantar</label>
                <select class="form-select" id="id_keperluan" name="id_keperluan" required>
                    <option value="" selected disabled>--- Pilih Keperluan ---</option>
                    
                    <?php if (isset($list_keperluan) && !empty($list_keperluan)): ?>
                        <?php foreach ($list_keperluan as $keperluan): ?>
                            <option value="<?= htmlspecialchars($keperluan->id); ?>" data-nama="<?= strtolower(str_replace(' ', '', $keperluan->nama_keperluan)); ?>">
                                <?= htmlspecialchars($keperluan->nama_keperluan); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </select>
            </div>
            
            <div class="mb-4" id="keperluan_lainnya_div" style="display: none;">
                <label for="keperluan_lainnya_text" class="form-label">Sebutkan Keperluan Lainnya</label>
                <input type="text" class="form-control" name="keperluan_lainnya_text" id="keperluan_lainnya_text" placeholder="Contoh: Pengantar SKCK">
            </div>


            <div class="text-center border-top pt-3 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-send" style="transform: rotate(-45deg); display: inline-block;"></i> Ajukan Surat
                </button>
            </div>

        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
    /* Menghilangkan lengkungan card saat di layar kecil agar menempel sempurna */
    @media (max-width: 767.98px) {
        #form-surat-edge-to-edge {
            border-radius: 0;
            border-left: 0;
            border-right: 0;
        }
    }
</style>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // Inisialisasi Select2 untuk dropdown pendatang agar bisa dicari
    if ($('#id_pendatang').length) {
        $('#id_pendatang').select2({
            theme: 'bootstrap-5',
            placeholder: 'Cari dan Pilih Nama Pendatang',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        });
    }

    // Ambil elemen-elemen yang dibutuhkan dari DOM
    const keperluanSelect = document.getElementById('id_keperluan'); // Ganti ID
    const lainnyaDiv = document.getElementById('keperluan_lainnya_div');
    const lainnyaInput = document.getElementById('keperluan_lainnya_text');

    keperluanSelect.addEventListener('change', function() {
        // Ambil data-nama dari option yang dipilih
        const selectedOptionName = this.options[this.selectedIndex].getAttribute('data-nama');
        
        if (selectedOptionName === 'lainnya') { // Cek berdasarkan data-nama
            lainnyaDiv.style.display = 'block';
            lainnyaInput.required = true;
        } else {
            lainnyaDiv.style.display = 'none';
            lainnyaInput.required = false;
        }
    });

});
</script>