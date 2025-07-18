<div class="card shadow-sm" id="form-surat-edge-to-edge">
    <div class="card-header card-header-minimalist"> 
        Formulir Pengajuan Surat Pengantar

        <a href="#" class="float-end" data-bs-toggle="modal" data-bs-target="#modalTambahKeperluan">
            Tambah Tipe Keperluan ?
        </a>
    </div>

    <div class="card-body">

        <?php
            // Menampilkan pesan SUKSES dari proses apapun (termasuk dari modal)
            if ($this->session->flashdata('pesan')) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        ' . $this->session->flashdata('pesan') . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }

            // Menampilkan pesan ERROR umum
            if ($this->session->flashdata('error')) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ' . $this->session->flashdata('error') . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
            
            // [2] BLOK KHUSUS UNTUK MENAMPILKAN PESAN ERROR DARI VALIDASI MODAL
            if ($this->session->flashdata('error_keperluan')) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal Menambahkan!</strong> ' . $this->session->flashdata('error_keperluan') . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
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


<div class="modal fade" id="modalTambahKeperluan" tabindex="-1" aria-labelledby="modalTambahKeperluanLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <form action="<?= site_url('surat_kedatangan/tambah_keperluan'); ?>" method="POST">

        <div class="modal-header">
          <h5 class="modal-title" id="modalTambahKeperluanLabel">Tambah Tipe Keperluan Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label for="nama_keperluan_input" class="form-label">Nama Keperluan</label>
                <input type="text" class="form-control" id="nama_keperluan_input" name="nama_keperluan" />
                <!-- placeholder="Contoh: Pengantar Nikah" required> -->
                <small class="form-text text-muted">Pastikan nama keperluan belum ada dalam daftar.</small>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>

      </form>

    </div>
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
    const keperluanSelect = document.getElementById('id_keperluan');
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