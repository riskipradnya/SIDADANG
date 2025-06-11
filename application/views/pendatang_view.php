<?php
// Bagian ini memuat semua tag <link> dan <style> yang tadinya ada di <head>
// Ini memastikan halaman tetap memiliki tampilan yang benar.
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />

<style>
    .preview-container {
        border: 2px dashed #ddd; border-radius: .25rem; padding: 1rem;
        text-align: center; margin-bottom: 1rem; background-color: #f8f9fa;
    }
    .preview-img {
        max-height: 150px; width: auto; display: block; margin-left: auto;
        margin-right: auto; margin-bottom: 1rem; border: 1px solid #eee;
    }
    #map {
        width: 100%; height: 250px; position: relative;
        z-index: 1; border-radius: .25rem; border: 1px solid #ccc; 
    }
    #scanKtpSpinner { display: none; margin-right: 5px; }
    #progressText { font-size: 0.9em; color: #666; min-height: 1.2em; }
    #alamatSekarang { overflow-y: hidden; min-height: calc(1.5em + .75rem + 2px); }
    .form-control-m[type="file"] { height: auto; padding-top: 0.25rem; padding-bottom: 0.25rem; }
    .input-group-m .form-control, .input-group-m .btn {
        padding-top: 0.4rem; padding-bottom: 0.4rem; font-size: 0.9rem; height: auto;
    }
    .input-group-m .form-control[type="file"] { padding-top: 0.4rem; padding-bottom: 0.4rem; }
    .form-select:disabled { background-color: #e9ecef; }

    .btn-form-custom {
        border-radius: 3px !important; /* Memberikan lengkungan halus */
    }

    /* Opsional: Jika Anda ingin ada sedikit jarak antar tombol kustom ini */
    .btn-form-custom + .btn-form-custom { 
        margin-left: 0.5rem; 
    }

    /* Efek hover untuk tombol Simpan Data (btn-outline-primary) */
    .btn-outline-primary.btn-form-custom:hover {
        background-color: #0d6efd; /* Warna primary Bootstrap, atau sesuaikan */
        color: white; /* Warna teks menjadi putih saat hover */
    }

        select.form-select {
        color: #212529; /* Warna teks standar Bootstrap */
        background-color: #ffffff; /* Warna background putih */
        /* Opsional: pastikan border juga standar seperti input lain jika perlu */
        /* border: 1px solid #ced4da; */ /* Border standar Bootstrap untuk form-control */
    }

    /* Target option pertama (placeholder) jika nilainya kosong */
    select.form-select option[value=""] {
        color: #6c757d;
        background-color: #ffffff; /* Pastikan background option placeholder juga putih */
    }

    /* Target option yang valid (bukan placeholder) */
    select.form-select option:not([value=""]) {
        color: #212529;
        background-color: #ffffff; /* Pastikan background option valid juga putih */
    }
    
    /* Efek saat option di-hover (opsional, beberapa browser mungkin tidak sepenuhnya mendukung styling ini) */
    select.form-select option:hover {
        background-color: #f8f9fa; /* Warna background abu-abu sangat muda saat hover */
        color: #212529;
    }

    /* Style untuk select saat disabled (sudah ada di kode Anda, pastikan background sesuai) */
    .form-select:disabled { 
        background-color: #e9ecef; /* Warna background standar Bootstrap untuk disabled */
        /* color: #6c757d; */ /* Warna teks untuk disabled */
    }

    /* Khusus untuk option yang disabled (jika ada, seperti 'Pilih Provinsi Dahulu') */
    select.form-select option:disabled {
        color: #adb5bd; 
        background-color: #f8f9fa; /* Background sedikit berbeda untuk disabled option */
    }

</style>

<?php
// Ini adalah konten utama (formulir) yang tadinya ada di dalam <body>
?>
<div class="container mt-4">

    <?php 
    if ($this->session->flashdata('success')) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                ' . $this->session->flashdata('success') . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
    if ($this->session->flashdata('error')) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                ' . $this->session->flashdata('error') . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
    ?>
    <h4 class="mb-4">Form Pendataan Pendatang</h4>
    
    <form method="post" enctype="multipart/form-data" action="<?= base_url('pendatang/simpan') ?>">
        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3">Data Diri & Alamat Asal</h5>
                <div class="preview-container">
                    <label for="foto_diri" class="form-label fw-bold">Foto Diri <span class="text-danger">*</span></label>
                    <img id="previewDiri" src="<?= base_url('assets/img/selfie1.png') ?>" alt="Preview Foto Diri" class="preview-img">
                    <input type="file" name="foto_diri" id="foto_diri" class="form-control form-control-m" onchange="previewImage(this, 'previewDiri')" accept="image/*" required>
                </div>
                
                <?php
                // Menjadikan semua field di $fields_kiri required jika belum
                $fields_kiri = [
                    'nik' => ['label' => 'NIK (Sesuai KTP)', 'type' => 'text', 'pattern' => '\d{16}', 'title' => 'NIK harus 16 digit angka', 'required' => true],
                    'nama' => ['label' => 'Nama Lengkap (Sesuai KTP)', 'type' => 'text', 'required' => true],
                    'no_hp' => ['label' => 'No Handphone Aktif', 'type' => 'tel', 'placeholder' => '08xxxxxxxxxx', 'required' => true],
                    'tempat_lahir' => ['label' => 'Tempat Lahir (Sesuai KTP)', 'type' => 'text', 'required' => true],
                    'tgl_lahir' => ['label' => 'Tanggal Lahir (Sesuai KTP)', 'type' => 'date', 'required' => true],
                ];
                foreach ($fields_kiri as $name => $attr) {
                    echo '<div class="mb-3">';
                    echo "<label for='{$name}' class='form-label'>{$attr['label']}" . (isset($attr['required']) && $attr['required'] ? ' <span class="text-danger">*</span>' : '') . "</label>";
                    echo "<input type='{$attr['type']}' class='form-control' name='{$name}' id='{$name}' " 
                            . (isset($attr['pattern']) ? "pattern='{$attr['pattern']}' " : "") 
                            . (isset($attr['title']) ? "title='{$attr['title']}' " : "") 
                            . (isset($attr['placeholder']) ? "placeholder='{$attr['placeholder']}' " : "Masukkan {$attr['label']}") 
                            . (isset($attr['required']) && $attr['required'] ? 'required' : '') . ">";
                    echo '</div>';
                }
                ?>
                
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin (Sesuai KTP) <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="golongan_darah" class="form-label">Golongan Darah <span class="text-danger">*</span></label>
                    <select name="golongan_darah" id="golongan_darah" class="form-select" required>
                        <option value="">Pilih Golongan Darah</option>
                        <option value="A">A</option><option value="B">B</option><option value="AB">AB</option><option value="O">O</option>
                        <option value="A+">A+</option><option value="B+">B+</option><option value="AB+">AB+</option><option value="O+">O+</option>
                        <option value="A-">A-</option><option value="B-">B-</option><option value="AB-">AB-</option><option value="O-">O-</option>
                        <option value="TIDAK TAHU">TIDAK TAHU</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="agama" class="form-label">Agama <span class="text-danger">*</span></label>
                    <select name="agama" id="agama" class="form-select" required>
                        <option value="">Pilih Agama</option>
                        <option value="Islam">Islam</option><option value="Kristen Protestan">Kristen Protestan</option><option value="Kristen Katolik">Kristen Katolik</option>
                        <option value="Hindu">Hindu</option><option value="Buddha">Buddha</option><option value="Khonghucu">Khonghucu</option><option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="provinsi_asal" class="form-label">Provinsi Asal (Sesuai KTP) <span class="text-danger">*</span></label>
                    <select name="provinsi_asal_id" id="provinsi_asal" class="form-select" required>
                        <option value="">Memuat provinsi...</option>
                    </select>
                    <input type="hidden" name="provinsi_asal" id="provinsi_asal_nama">
                </div>

                <div class="mb-3">
                    <label for="kabupaten_asal" class="form-label">Kabupaten/Kota Asal (Sesuai KTP) <span class="text-danger">*</span></label>
                    <select name="kabupaten_asal_id" id="kabupaten_asal" class="form-select" required disabled>
                        <option value="">Pilih Provinsi Dahulu</option>
                    </select>
                    <input type="hidden" name="kabupaten_asal" id="kabupaten_asal_nama">
                </div>

                <div class="mb-3">
                    <label for="kecamatan_asal" class="form-label">Kecamatan Asal (Sesuai KTP) <span class="text-danger">*</span></label>
                    <select name="kecamatan_asal_id" id="kecamatan_asal" class="form-select" required disabled >
                        <option value="">Pilih Kabupaten/Kota Dahulu</option>
                    </select>
                    <input type="hidden" name="kecamatan_asal" id="kecamatan_asal_nama">
                </div>

                <div class="mb-3">
                    <label for="kelurahan_asal" class="form-label">Kelurahan/Desa Asal (Sesuai KTP) <span class="text-danger">*</span></label>
                    <select name="kelurahan_asal_id" id="kelurahan_asal" class="form-select" required disabled >
                        <option value="">Pilih Kecamatan Dahulu</option>
                    </select>
                    <input type="hidden" name="kelurahan_asal" id="kelurahan_asal_nama">
                </div>

                <?php
                $fields_rt_rw = [
                    'rt' => ['label' => 'RT Asal (Sesuai KTP)', 'type' => 'text', 'pattern' => '\d{1,3}', 'title' => 'RT terdiri dari 1-3 digit angka', 'required' => true],
                    'rw' => ['label' => 'RW Asal (Sesuai KTP)', 'type' => 'text', 'pattern' => '\d{1,3}', 'title' => 'RW terdiri dari 1-3 digit angka', 'required' => true],
                ];
                foreach ($fields_rt_rw as $name => $attr) {
                    echo '<div class="mb-3">';
                    echo "<label for='{$name}' class='form-label'>{$attr['label']}" . (isset($attr['required']) && $attr['required'] ? ' <span class="text-danger">*</span>' : '') . "</label>";
                    echo "<input type='{$attr['type']}' class='form-control' name='{$name}' id='{$name}' " 
                        . (isset($attr['pattern']) ? "pattern='{$attr['pattern']}' " : "") 
                        . (isset($attr['title']) ? "title='{$attr['title']}' " : "") 
                        . (isset($attr['placeholder']) ? "placeholder='{$attr['placeholder']}' " : "Masukkan {$attr['label']}") 
                        . (isset($attr['required']) && $attr['required'] ? 'required' : '') . ">";
                    echo '</div>';
                }
                ?>
                
                <div class="mb-3">
                    <label for="alamat_asal" class="form-label">Detail Alamat Asal (Jalan, No Rumah, dll) <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="alamat_asal" id="alamat_asal" rows="2" required></textarea>
                </div>
            </div>

            <div class="col-md-6">
                <h5 class="mb-3">Data Domisili & Tambahan</h5>
                <div class="preview-container">
                    <label for="foto_ktp" class="form-label fw-bold">Scan KTP <span class="text-danger">*</span></label>
                    <img id="previewKtp" src="<?= base_url('assets/img/default-ktp.png') ?>" alt="Preview KTP" class="preview-img">
                    <div class="input-group input-group-m">
                        <input type="file" name="foto_ktp" id="foto_ktp" class="form-control" onchange="previewImage(this, 'previewKtp')" accept="image/*" capture="environment" required>
                        <button class="btn btn-outline-success" type="button" id="scanBtn" disabled>
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" id="scanKtpSpinner"></span>
                            Scan & Isi Otomatis
                        </button>
                    </div>
                    <div id="progressText" class="mt-1"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Lokasi Domisili (Klik pada peta) <span class="text-danger">*</span></label>
                    <div id="map"></div>
                </div>

                <div class="mb-3">
                    <label for="alamatSekarang" class="form-label">Alamat Domisili Sekarang (Hasil dari Peta) <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="alamatSekarang" id="alamatSekarang" rows="1" required></textarea> 
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="latitude" class="form-label">Latitude <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="latitude" id="latitude" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="longitude" class="form-label">Longitude <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="longitude" id="longitude" required> 
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="tujuan" class="form-label">Tujuan Kedatangan <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="tujuan" id="tujuan" rows="2" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tgl_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tgl_masuk" id="tgl_masuk" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tgl_keluar" class="form-label">Perkiraan Tanggal Keluar</label>
                            <input type="date" class="form-control" name="tgl_keluar" id="tgl_keluar">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="id_penanggung_jawab" class="form-label">Penanggung Jawab / Kepala Lingkungan <span class="text-danger">*</span></label>
                    <select name="id_penanggung_jawab" id="id_penanggung_jawab" class="form-select <?php if(form_error('id_penanggung_jawab')) echo 'is-invalid'; ?>" required>
                        <option value="">Pilih Penanggung Jawab (beserta Kaling terkait)</option>

                        <?php
                        // Variabel $responsible_persons_list dikirim dari controller Pendatang.php (fungsi index)
                        // dan berisi hasil dari _get_pj_with_kaling_details()
                        if (isset($responsible_persons_list) && !empty($responsible_persons_list)):
                            foreach ($responsible_persons_list as $person):
                        ?>
                                <?php
                                    // Siapkan teks informasi Kaling
                                    $kaling_info_text = "<span class='text-muted'>Kaling: (Belum terhubung)</span>"; // Default jika tidak ada id kaling di pj
                                    if (!empty($person->kaling_id_at_pj)) { // kaling_id_at_pj adalah alias untuk tbpj.id_kepala_lingkungan
                                        if (!empty($person->kaling_namaLengkap)) {
                                            $kaling_info_text = "Kaling: " . htmlspecialchars($person->kaling_namaLengkap);
                                            // NIK Kaling tidak lagi ditambahkan di sini
                                        } else {
                                            // Ada ID Kaling di PJ, tapi data Kaling tidak ditemukan (misal Kaling sudah dihapus dari tbkaling)
                                            $kaling_info_text = "<span class='text-warning'>Kaling: (Data Kaling tidak ditemukan untuk ID: ".htmlspecialchars($person->kaling_id_at_pj).")</span>";
                                        }
                                    }
                                ?>
                                <option value="<?= htmlspecialchars($person->pj_kodeDaftar); ?>" <?= set_select('id_penanggung_jawab', $person->pj_kodeDaftar); ?>>
                                    PJ: <?= htmlspecialchars($person->pj_namaLengkap); ?> (NIK: <?= htmlspecialchars($person->pj_NIK); ?>) - <?= $kaling_info_text; ?>
                                </option>
                        <?php
                            endforeach;
                        else:
                        ?>
                            <option value="" disabled>Tidak ada Penanggung Jawab terverifikasi yang tersedia</option>
                        <?php endif; ?>
                    </select>
                    <?php if(form_error('id_penanggung_jawab')): ?>
                        <div class="invalid-feedback">
                            <?= form_error('id_penanggung_jawab'); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="wilayah" class="form-label">Wilayah (Banjar/Lingkungan di Jimbaran) <span class="text-danger">*</span></label>
                    <select name="wilayah" id="wilayah" class="form-select" required>
                        <option value="">Pilih Wilayah (Banjar/Lingkungan)</option>
                        <?php
                            $banjar_list = ["Pesalakan", "Menega", "Pasek", "Tegal", "Perarudan", "Mumbul", "Mekar Sari", "Taman Griya", "Kori Nuansa", "Kalanganyar", "Angga Suara", "Mertha Sari", "Buana Gubug", "Telaga", "Lainnya"];
                            foreach ($banjar_list as $banjar) {
                                echo "<option value=\"$banjar\">$banjar</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <hr>

        <div class="text-center mt-4 mb-5">
            <button type="submit" class="btn btn-outline-primary px-5 btn-form-custom">  <i class=""></i> Simpan Data
            </button>
            <button type="reset" class="btn btn-outline-secondary px-5 ms-2 btn-form-custom">
                <i class=""></i> Reset Form
            </button>
        </div>
    </form>
</div>

<?php
// Ini adalah semua tag <script> yang tadinya ada di akhir <body>
// Ini memastikan semua fungsionalitas interaktif tetap berjalan.
?>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

<script>
    // FUNGSI UTILITAS DASAR
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) { preview.src = e.target.result; }
            reader.readAsDataURL(file);
        } else {
            preview.src = (previewId === 'previewDiri') ? "<?= base_url('assets/img/selfie1.png') ?>" : "<?= base_url('assets/img/default-ktp.png') ?>";
        }
    }

    function convertDate(dateStr) { // Digunakan oleh parseKTPData
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            if (parts[0].length === 2 && parts[1].length === 2 && parts[2].length === 4) { // DD-MM-YYYY -> YYYY-MM-DD
                return `${parts[2]}-${parts[1]}-${parts[0]}`;
            } else if (parts[0].length === 4 && parts[1].length === 2 && parts[2].length === 2) { // YYYY-MM-DD (sudah benar)
                return dateStr;
            }
        }
        return dateStr; // Kembalikan apa adanya jika format tidak dikenali
    }

    // --- BAGIAN LOGIKA TESSERACT OCR ---
    let tesseractWorker = null;
    async function initializeTesseract() {
        const progressTextElement = $('#progressText');
        const scanBtnElement = $('#scanBtn');
        try {
            if (progressTextElement.length) progressTextElement.text("Inisialisasi Tesseract OCR...");
            tesseractWorker = await Tesseract.createWorker('ind', 0, {
                logger: m => {
                    if (progressTextElement.length) {
                        let statusText = m.status;
                        if (m.progress && (m.status === 'recognizing text' || m.status === 'loading language model' || m.status === 'downloading')) {
                            statusText += ` (${(m.progress * 100).toFixed(0)}%)`;
                        }
                        progressTextElement.text(statusText.charAt(0).toUpperCase() + statusText.slice(1));
                    }
                },
            });
            await tesseractWorker.setParameters({
                tessedit_pageseg_mode: Tesseract.PSM.AUTO_OSD,
                tessedit_char_whitelist: '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz :/.-,\'',
                preserve_interword_spaces: '1'
            });
            if (progressTextElement.length) progressTextElement.text("Tesseract OCR siap.");
            if ($('#foto_ktp').get(0).files.length > 0 && scanBtnElement.length) {
                scanBtnElement.prop('disabled', false);
            }
        } catch (error) {
            console.error("Error initializing Tesseract:", error);
            if (progressTextElement.length) progressTextElement.text("Gagal memuat Tesseract OCR.");
            if (scanBtnElement.length) scanBtnElement.prop('disabled', true);
        }
    }
    
    function parseKTPData(text) {
        const data = {};
        const lines = text.split('\n').map(line => line.trim()).filter(line => line.length > 1);
        const nikRegex = /(?:NIK|HIK|MK|HIN|NIK)\s*[:\s]*([\d\s]{16,})/;
        const namaRegex = /Nama\s*[:\s]*([A-Z\s.'’`]+)/i;
        const tempatTglLahirRegex = /(?:Tempat(?:rrgl|\/Tgl)? Lahir|TempatTgiLahir)\s*[:\s]*([A-Z\s.'()\/,-]+?)\s*,\s*(\d{2}[-\/.]\d{2}[-\/.]\d{4})/i;
        const jenisKelaminRegex = /Jenis Kelamin\s*[:\s]*([A-Z\s'-]+?)(?:\s+Gol|\s*$)/i;
        const golDarahRegex = /Gol\.\s*Darah\s*[:\s]*([A-Z0-9ABO+\-]+)/i;
        const rtRwRegex = /(?:RT\/RW)\s*[:\s]*(\d{1,3})\s*[\/|Ii]?\s*(\d{1,3})/;
        const kelDesaRegex = /(?:Kel\/Desa|Kelurahan|Desa)\s*[:\s]*([A-Z\s.'()/-]+)/i;
        const kecamatanRegex = /(?:Kecamatan)\s*[:\s]*([A-Z\s.'()/-]+)/i;
        const agamaRegex = /Agama\s*[:\s]*([A-Z\s.'-]+)/i;
        const provinsiCaptureRegex = /^PROVINSI\s+([A-Z\s]+)/i;
        const kabKotaCaptureRegex = /^(?:KABUPATEN|KOTA)\s+([A-Z\s]+)/i;

        let fullTextOriginal = text;
        let firstLine = lines.length > 0 ? lines[0].toUpperCase() : "";
        let secondLine = lines.length > 1 ? lines[1].toUpperCase() : "";

        if (firstLine.startsWith("PROVINSI ")) {
            data.provinsi_asal = firstLine.substring("PROVINSI ".length).trim();
            if (secondLine && !secondLine.startsWith("KABUPATEN") && !secondLine.startsWith("KOTA") && !secondLine.startsWith("NIK") && !secondLine.toUpperCase().includes("TEMPAT") && !secondLine.toUpperCase().includes("NAMA")) {
                data.kabupaten_asal = lines[1].trim();
            }
        }
        let matchKab = fullTextOriginal.match(kabKotaCaptureRegex);
        if (matchKab) {
            data.kabupaten_asal = matchKab[1].trim();
        }
        
        let alamatLengkapBuffer = "";
        let isCapturingAlamat = false;
        let rtRwProcessed = false;

        for (const line of lines) {
            let match;
            if (!data.nik && (match = line.match(nikRegex))) { data.nik = match[1].replace(/\D/g, '').substring(0, 16); isCapturingAlamat = false; }
            else if (!data.nama && (match = line.match(namaRegex))) { data.nama = match[1].replace(/^\.\s*/, '').replace(/\s\s+/g, ' ').trim(); isCapturingAlamat = false; }
            else if (!data.tgl_lahir && (match = line.match(tempatTglLahirRegex))) {
                data.tempat_lahir = match[1].replace(/,/g, '').trim(); data.tgl_lahir = convertDate(match[2].replace(/[.\/]/g, '-')); isCapturingAlamat = false;
            } else if (!data.jenis_kelamin && (match = line.match(jenisKelaminRegex))) {
                const jkText = match[1].toUpperCase().replace(/[:\s-]/g, '');
                if (jkText.startsWith('LAKI')) data.jenis_kelamin = 'Laki-laki'; else if (jkText.startsWith('PEREMPUAN')) data.jenis_kelamin = 'Perempuan';
                let matchGolDarahInline = line.substring(line.toUpperCase().indexOf(match[1].toUpperCase()) + match[1].length).match(golDarahRegex);
                if (matchGolDarahInline && !data.golongan_darah) data.golongan_darah = matchGolDarahInline[1].trim().toUpperCase();
                isCapturingAlamat = false;
            } else if (!data.golongan_darah && (match = line.match(golDarahRegex))) { data.golongan_darah = match[1].trim().toUpperCase(); isCapturingAlamat = false; }
            else if (!data.agama && (match = line.match(agamaRegex))) { data.agama = match[1].trim(); isCapturingAlamat = false; }
            else if (line.toUpperCase().startsWith('ALAMAT')) {
                isCapturingAlamat = true; alamatLengkapBuffer = line.substring(line.toUpperCase().indexOf('ALAMAT') + 6).replace(/^[:\s]+/, '').trim();
            } else if ((match = line.match(rtRwRegex)) && !rtRwProcessed) {
                if (!data.rt) data.rt = match[1].padStart(3, '0'); if (!data.rw) data.rw = match[2].padStart(3, '0');
                if (isCapturingAlamat && alamatLengkapBuffer && !data.alamat_asal) data.alamat_asal = alamatLengkapBuffer.trim();
                isCapturingAlamat = false; alamatLengkapBuffer = ""; rtRwProcessed = true;
            } else if (isCapturingAlamat) {
                if (line.toUpperCase().startsWith("KEL/DESA") || line.toUpperCase().startsWith("KECAMATAN") || line.toUpperCase().startsWith("STATUS PERKAWINAN")) {
                    isCapturingAlamat = false; if (alamatLengkapBuffer && !data.alamat_asal) data.alamat_asal = alamatLengkapBuffer.trim();
                    if (!data.kelurahan_asal && (match = line.match(kelDesaRegex))) { data.kelurahan_asal = match[1].trim(); }
                    else if (!data.kecamatan_asal && (match = line.match(kecamatanRegex))) { data.kecamatan_asal = match[1].trim(); }
                } else {
                    alamatLengkapBuffer += (alamatLengkapBuffer ? " " : "") + line.trim();
                }
            } else if (!isCapturingAlamat) {
                if (!data.kelurahan_asal && (match = line.match(kelDesaRegex))) { data.kelurahan_asal = match[1].trim(); }
                else if (!data.kecamatan_asal && (match = line.match(kecamatanRegex))) { data.kecamatan_asal = match[1].trim(); }
            }
        }
        if (alamatLengkapBuffer && !data.alamat_asal) data.alamat_asal = alamatLengkapBuffer.trim();
        if (data.alamat_asal && (!data.rt || !data.rw)) {
            let rtRwInAlamatMatch = data.alamat_asal.match(/(.*?)\s*(RT\/RW\s*[:\s]*(\d{1,3})\s*[\/|Ii]?\s*(\d{1,3}))/i);
            if (rtRwInAlamatMatch) {
                data.alamat_asal = rtRwInAlamatMatch[1].trim(); data.rt = rtRwInAlamatMatch[3].padStart(3, '0'); data.rw = rtRwInAlamatMatch[4].padStart(3, '0');
            }
        }
        if ((!data.rt || !data.rw) && fullTextOriginal.match(/(?:RT\/RW)\s*[:\s]*(\d{3})(\d{3})/i)) {
            let plainRtRwMatch = fullTextOriginal.match(/(?:RT\/RW)\s*[:\s]*(\d{3})(\d{3})/i);
            if (plainRtRwMatch) {
                data.rt = plainRtRwMatch[1]; data.rw = plainRtRwMatch[2];
                if(data.alamat_asal && data.alamat_asal.includes(plainRtRwMatch[0])) data.alamat_asal = data.alamat_asal.replace(plainRtRwMatch[0], "").trim();
            }
        }
        if (data.alamat_asal) data.alamat_asal = data.alamat_asal.replace(/A7I66/gi, 'A7/66');
        console.log("[parseKTPData] Final Extracted Data:", data);
        return data;
    }
    
    const apiBaseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api/';

    async function fetchAndPopulateDropdown(url, dropdownSelector, placeholderText) {
        const select = $(dropdownSelector);
        select.empty().append(`<option value="">Memuat...</option>`).prop('disabled', true);
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP error ${response.status} for ${url}`);
            const data = await response.json();
            select.empty().append(`<option value="">${placeholderText}</option>`);
            $.each(data, function(index, item) {
                select.append($('<option>', { value: item.id, text: item.name }));
            });
            select.prop('disabled', false);
            return data;
        } catch (error) {
            console.error(`[Wilayah] ERROR fetching for ${dropdownSelector}:`, error);
            select.empty().append(`<option value="">Gagal memuat data</option>`).prop('disabled', true);
            return null;
        }
    }
    
    function findAndSelectOption(dropdownSelector, textToFindFromOCR, hiddenInputSelector) {
        let selectedId = null;
        let selectedText = '';
        if (!textToFindFromOCR || textToFindFromOCR.trim() === "") {
            if(hiddenInputSelector) $(hiddenInputSelector).val('');
            $(dropdownSelector).val('');
            return null;
        }
        const searchNormalized = textToFindFromOCR.toUpperCase().replace("KABUPATEN", "KAB").replace("KOTA ADMINISTRASI", "KOTA").replace("KOTA", "").replace("ADM.", "").replace(/\s+/g, ' ').trim();
        
        let bestMatch = { id: null, text: '', score: 0 };
        $(dropdownSelector + ' option').each(function() {
            const optionTextOriginal = $(this).text();
            if (!optionTextOriginal) return;
            const optionNormalized = optionTextOriginal.toUpperCase().replace("KABUPATEN", "KAB").replace("KOTA ADMINISTRASI", "KOTA").replace("KOTA", "").replace("ADM.", "").replace(/\s+/g, ' ').trim();
            let currentScore = 0;
            if (optionNormalized === searchNormalized) { currentScore = 100; }
            else if (optionNormalized.includes(searchNormalized)) { currentScore = (searchNormalized.length / optionNormalized.length) * 90; }
            else if (searchNormalized.includes(optionNormalized)) { currentScore = (optionNormalized.length / searchNormalized.length) * 80; }
            if (currentScore > bestMatch.score) bestMatch = { id: $(this).val(), text: optionTextOriginal, score: currentScore };
        });
        
        if (bestMatch.id && bestMatch.score > 50) {
            selectedId = bestMatch.id; selectedText = bestMatch.text;
            $(dropdownSelector).val(selectedId);
            if (hiddenInputSelector) $(hiddenInputSelector).val(selectedText);
        } else {
            if (hiddenInputSelector) $(hiddenInputSelector).val(''); $(dropdownSelector).val('');
        }
        return selectedId;
    }

    async function fillFormWithKTPData(dataFromOCR) {
        if (dataFromOCR.nik) $('#nik').val(dataFromOCR.nik);
        if (dataFromOCR.nama) $('#nama').val(dataFromOCR.nama);
        if (dataFromOCR.tempat_lahir) $('#tempat_lahir').val(dataFromOCR.tempat_lahir);
        if (dataFromOCR.tgl_lahir) $('#tgl_lahir').val(dataFromOCR.tgl_lahir);
        if (dataFromOCR.jenis_kelamin) $('#jenis_kelamin').val(dataFromOCR.jenis_kelamin);
        if (dataFromOCR.alamat_asal) $('#alamat_asal').val(dataFromOCR.alamat_asal);
        if (dataFromOCR.rt) $('#rt').val(dataFromOCR.rt);
        if (dataFromOCR.rw) $('#rw').val(dataFromOCR.rw);
        if (dataFromOCR.agama) findAndSelectOption('#agama', dataFromOCR.agama, null);
        if (dataFromOCR.golongan_darah) findAndSelectOption('#golongan_darah', dataFromOCR.golongan_darah, null);

        let provId = null;
        if (dataFromOCR.provinsi_asal) provId = findAndSelectOption('#provinsi_asal', dataFromOCR.provinsi_asal, '#provinsi_asal_nama');
        
        let kabId = null;
        if (provId && dataFromOCR.kabupaten_asal) {
            const kabData = await fetchAndPopulateDropdown(apiBaseUrl + `regencies/${provId}.json`, '#kabupaten_asal', 'Pilih Kabupaten/Kota');
            if (kabData) kabId = findAndSelectOption('#kabupaten_asal', dataFromOCR.kabupaten_asal, '#kabupaten_asal_nama');
        }

        let kecId = null;
        if (kabId && dataFromOCR.kecamatan_asal) {
            const kecData = await fetchAndPopulateDropdown(apiBaseUrl + `districts/${kabId}.json`, '#kecamatan_asal', 'Pilih Kecamatan');
            if (kecData) kecId = findAndSelectOption('#kecamatan_asal', dataFromOCR.kecamatan_asal, '#kecamatan_asal_nama');
        }

        if (kecId && dataFromOCR.kelurahan_asal) {
            const kelData = await fetchAndPopulateDropdown(apiBaseUrl + `villages/${kecId}.json`, '#kelurahan_asal', 'Pilih Kelurahan/Desa');
            if (kelData) findAndSelectOption('#kelurahan_asal', dataFromOCR.kelurahan_asal, '#kelurahan_asal_nama');
        }
        
        $('#no_hp').focus();
    }

    $(document).ready(function() {
        fetchAndPopulateDropdown(apiBaseUrl + 'provinces.json', '#provinsi_asal', 'Pilih Provinsi');

        $('#provinsi_asal').change(function() {
            const provinceId = $(this).val();
            const provinceName = $(this).find('option:selected').text();
            $('#provinsi_asal_nama').val(provinceId ? provinceName : '');
            $('#kabupaten_asal').empty().append('<option value="">Pilih Provinsi Dahulu</option>').prop('disabled', true).val('');
            $('#kecamatan_asal').empty().append('<option value="">Pilih Kabupaten/Kota Dahulu</option>').prop('disabled', true).val('');
            $('#kelurahan_asal').empty().append('<option value="">Pilih Kecamatan Dahulu</option>').prop('disabled', true).val('');
            $('#kabupaten_asal_nama').val(''); $('#kecamatan_asal_nama').val(''); $('#kelurahan_asal_nama').val('');
            if (provinceId) fetchAndPopulateDropdown(apiBaseUrl + `regencies/${provinceId}.json`, '#kabupaten_asal', 'Pilih Kabupaten/Kota');
        });

        $('#kabupaten_asal').change(function() {
            const regencyId = $(this).val();
            const regencyName = $(this).find('option:selected').text();
            $('#kabupaten_asal_nama').val(regencyId ? regencyName : '');
            $('#kecamatan_asal').empty().append('<option value="">Pilih Kabupaten/Kota Dahulu</option>').prop('disabled', true).val('');
            $('#kelurahan_asal').empty().append('<option value="">Pilih Kecamatan Dahulu</option>').prop('disabled', true).val('');
            $('#kecamatan_asal_nama').val(''); $('#kelurahan_asal_nama').val('');
            if (regencyId) fetchAndPopulateDropdown(apiBaseUrl + `districts/${regencyId}.json`, '#kecamatan_asal', 'Pilih Kecamatan');
        });

        $('#kecamatan_asal').change(function() {
            const districtId = $(this).val();
            const districtName = $(this).find('option:selected').text();
            $('#kecamatan_asal_nama').val(districtId ? districtName : '');
            $('#kelurahan_asal').empty().append('<option value="">Pilih Kecamatan Dahulu</option>').prop('disabled', true).val('');
            $('#kelurahan_asal_nama').val('');
            if (districtId) fetchAndPopulateDropdown(apiBaseUrl + `villages/${districtId}.json`, '#kelurahan_asal', 'Pilih Kelurahan/Desa');
        });

        $('#kelurahan_asal').change(function() {
            const villageId = $(this).val();
            const villageName = $(this).find('option:selected').text();
            $('#kelurahan_asal_nama').val(villageId ? villageName : '');
        });
        
        const latitudeInput = $('#latitude');
        const longitudeInput = $('#longitude');
        const alamatSekarangInput = $('#alamatSekarang');
        const initialLat = -8.790738, initialLng = 115.162806, initialZoom = 14;
        const map = L.map('map').setView([initialLat, initialLng], initialZoom);
        let marker = L.marker([initialLat, initialLng]).addTo(map);
        latitudeInput.val(initialLat.toFixed(7)); longitudeInput.val(initialLng.toFixed(7));
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
        const geosearchProvider = new GeoSearch.OpenStreetMapProvider();
        map.on('click', async function(e) {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]); latitudeInput.val(lat.toFixed(7)); longitudeInput.val(lng.toFixed(7));
            alamatSekarangInput.val("Mencari alamat...");
            try {
                const results = await geosearchProvider.search({ query: `${lat}, ${lng}` });
                alamatSekarangInput.val(results && results.length > 0 ? results[0].label : "Alamat tidak ditemukan.");
            } catch (error) { alamatSekarangInput.val("Gagal mendapatkan alamat."); }
        });

        initializeTesseract();
        $('#foto_ktp').change(function(event) {
            $('#scanBtn').prop('disabled', !(tesseractWorker && event.target.files[0]));
        });
        $('#scanBtn').click(async function() {
            if (!$('#foto_ktp').get(0).files[0]) { alert('Silakan unggah foto KTP.'); return; }
            const btn = $(this), progressTextElement = $('#progressText'), scanKtpSpinner = $('#scanKtpSpinner');
            const originalButtonText = "Scan & Isi Otomatis";
            btn.prop('disabled', true); scanKtpSpinner.show();
            btn.contents().filter(function(){ return this.nodeType === Node.TEXT_NODE && this.nodeValue.trim() !== ""; }).first().replaceWith(" Memproses...");
            progressTextElement.text("Memulai proses scan KTP...");
            try {
                const { data: { text } } = await tesseractWorker.recognize($('#foto_ktp').get(0).files[0]);
                const ktpData = parseKTPData(text);
                await fillFormWithKTPData(ktpData); 
                progressTextElement.text("Data KTP berhasil diurai. Periksa kembali!");
                setTimeout(() => { if (progressTextElement.text() === "Data KTP berhasil diurai. Periksa kembali!") progressTextElement.text("Tesseract OCR siap."); }, 7000);
            } catch (err) {
                console.error('[Scan KTP Error]', err);
                progressTextElement.text("Gagal membaca KTP: " + (err.message || "Coba foto ulang."));
            } finally {
                btn.prop('disabled', false); scanKtpSpinner.hide();
                btn.contents().filter(function(){ return this.nodeType === Node.TEXT_NODE; }).first().replaceWith(` ${originalButtonText}`);
            }
        });
    });
</script>